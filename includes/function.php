<?php
declare(strict_types=1);

function getPdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $pdo = require __DIR__ . '/../config/database.php';

    if (!$pdo instanceof PDO) {
        throw new RuntimeException("PDO non initialisé");
    }

    return $pdo;
}
function isNafAutorise(?string $codeNaf, ?PDO $pdo = null): bool
{
    static $prefixes = null;

    if (!$codeNaf) {
        return true;
    }

    $codeNaf = preg_replace('/[^0-9A-Z]/', '', $codeNaf); // 5610A

    $pdo ??= getPdo();

    if ($prefixes === null) {
        $stmt = $pdo->query('SELECT prefixe FROM naf_autorises');
        $prefixes = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    if (empty($prefixes)) {
        return true;
    }

    foreach ($prefixes as $prefix) {
        $prefix = preg_replace('/[^0-9]/', '', $prefix); // 56.10 → 5610

        if (str_starts_with($codeNaf, $prefix)) {
            return true;
        }
    }

    return false;
}

function fetchUrlWithUserAgent(string $url, int $timeout = 10): ?string
{
    $ch = curl_init($url);

    if ($ch === false) {
        return null;
    }

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
    ]);

    $content = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);



    if ($content === false || $httpCode !== 200) {
        error_log("fetchUrlWithUserAgent: échec ({$httpCode}) {$error} pour {$url}");
        return null;
    }

    return $content;
}

function normaliseSireneAdresse(array $etablissement): string
{
    if (!empty($etablissement['adresse']) && is_string($etablissement['adresse'])) {
        return trim($etablissement['adresse']);
    }

    $parts = [];

    foreach ([
        'numero_voie',
        'indice_repetition_voie',
        'type_voie',
        'libelle_voie',
        'complement_adresse',
        'distribution_speciale',
        'code_postal',
        'libelle_commune',
    ] as $key) {
        if (!empty($etablissement[$key])) {
            $parts[] = trim((string) $etablissement[$key]);
        }
    }

    return trim(preg_replace('/\s+/', ' ', implode(' ', $parts)) ?: '');
}

/**
 * Récupère tous les résultats SIRENE d'une commune.
 * La commune de Montjean est 53158.
 */
function fetchSireneMontjean(string $codeCommune = '53158'): array
{
    $allResults = [];
    $page = 1;
    $perPage = 25;
    $maxPages = 20;
    $total = 0;

    do {
        $url = "https://recherche-entreprises.api.gouv.fr/search?code_commune=" . rawurlencode($codeCommune) . "&page={$page}&per_page={$perPage}";

        $json = fetchUrlWithUserAgent($url);
        if ($json === null) {
            break;
        }

        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['results']) || !is_array($data['results'])) {
            break;
        }

        $allResults = array_merge($allResults, $data['results']);
        $total = (int) ($data['total_results'] ?? 0);
        $page++;
    } while (($page - 1) * $perPage < $total && $page <= $maxPages);

    return $allResults;
}

function getLibelleActivite(?string $codeNaf, ?PDO $pdo = null): ?string
{
    $codeNaf = $codeNaf !== null ? trim($codeNaf) : '';
    if ($codeNaf === '') {
        return null;
    }

    $pdo ??= getPdo();

    $stmt = $pdo->prepare('SELECT libelle FROM naf WHERE code = :code LIMIT 1');
    $stmt->execute([':code' => $codeNaf]);
    $libelle = $stmt->fetchColumn();

    if ($libelle === false) {
        return $codeNaf;
    }

    return (string) $libelle;
}

function getHorairesMairie(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
            SELECT jour, horaires
            FROM horaires_mairie
            ORDER BY ordre ASC
        ');

    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
}

function getEntreprisesMontjean(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
            SELECT id, siret, nom, adresse, activite, codeNAF, description, updated_at
            FROM entreprises
            ORDER BY nom ASC
        ');

    return $stmt->fetchAll() ?: [];
}

function getAssociationsMontjean(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
            SELECT id, siret, nom, adresse, objet, telephone, email, site, codeNAF, updated_at
            FROM associations
            ORDER BY nom ASC
        ');

    return $stmt->fetchAll() ?: [];
}

function getEntreprisePhotos(int $entrepriseId, ?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
            SELECT id, lien
            FROM Photo
            WHERE entreprise_id = :id
            ORDER BY id ASC
        ');
    $stmt->execute([':id' => $entrepriseId]);

    return $stmt->fetchAll() ?: [];
}

function getEntrepriseReseaux(int $entrepriseId, ?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
            SELECT r.reseau, er.url
            FROM Entreprise_Reseau er
            INNER JOIN Reseau r ON r.id = er.reseau_id
            WHERE er.entreprise_id = :id
            ORDER BY r.reseau ASC
        ');
    $stmt->execute([':id' => $entrepriseId]);

    return $stmt->fetchAll() ?: [];
}

function getAssociationReseaux(int $associationId, ?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
            SELECT r.reseau, ar.url
            FROM Association_Reseau ar
            INNER JOIN Reseau r ON r.id = ar.reseau_id
            WHERE ar.association_id = :id
            ORDER BY r.reseau ASC
        ');
    $stmt->execute([':id' => $associationId]);

    return $stmt->fetchAll() ?: [];
}

function getEntrepriseHoraires(int $entrepriseId, ?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
            SELECT h.jour, eh.heure_debut, eh.heure_fin
            FROM Entreprise_Horaire eh
            INNER JOIN Horaire h ON h.id = eh.horaire_id
            WHERE eh.entreprise_id = :id
            ORDER BY h.id ASC, eh.heure_debut ASC
        ');
    $stmt->execute([':id' => $entrepriseId]);

    return $stmt->fetchAll() ?: [];
}

function getAssociationHoraires(int $associationId, ?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
            SELECT h.jour, ah.heure_debut, ah.heure_fin
            FROM Association_Horaire ah
            INNER JOIN Horaire h ON h.id = ah.horaire_id
            WHERE ah.association_id = :id
            ORDER BY h.id ASC, ah.heure_debut ASC
        ');
    $stmt->execute([':id' => $associationId]);

    return $stmt->fetchAll() ?: [];
}

