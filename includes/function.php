<?php

function getCacheDir(): string
{
    $dir = __DIR__ . '/../cache';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    return $dir;
}

function cacheGet(string $file, int $ttl)
{
    if (file_exists($file) && (time() - filemtime($file) < $ttl)) {
        $data = json_decode(file_get_contents($file), true);
        if (is_array($data)) return $data;
    }
    return null;
}

function cacheSet(string $file, array $data): void
{
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function getHorairesMairie(): array
{
    return [
        'Lundi'    => '9h-12h / 13h45-17h30',
        'Mardi'    => '9h-12h',
        'Mercredi' => '9h-12h',
        'Jeudi'    => '9h-12h',
        'Vendredi' => '9h-12h / 13h45-17h30',
        'Samedi'   => 'Fermé',
        'Dimanche' => 'Fermé',
    ];
}

function getLibelleActivite(?string $codeNaf): ?string
{
    if (!$codeNaf) return null;

    static $map = [
        '43.21A' => 'Électricité',
        '43.22A' => 'Plomberie',
        '43.22B' => 'Chauffage',
        '43.31Z' => 'Plâtrerie',
        '43.32A' => 'Menuiserie',
        '43.34Z' => 'Peinture',
        '43.91A' => 'Travaux de charpente',
        '43.99B' => 'Travaux d\'étanchéification',
        '43.99C' => 'Travaux de maçonnerie générale',
        '43.99D' => 'Autres travaux spécialisés de construction',
        '43.12A' => 'Travaux de terrassement',
        '45.11Z' => 'Commerce de voitures et véhicules légers',
        '45.20A' => 'Garage automobile',
        '47.11A' => 'Commerce alimentaire',
        '47.11B' => 'Commerce alimentaire généraliste',
        '56.10A' => 'Restaurant',
        '75.00Z' => 'Vétérinaire',
        '95.11Z' => 'Informatique',
        '96.02A' => 'Coiffure',
        '96.02B' => 'Soins de beauté',
        '96.04Z' => 'Bien-être',
        '94.99Z' => 'Activités associatives diverses',
        '93.12Z' => 'Activités de clubs de sports',
        '93.19Z' => 'Autres activités liées au sport',
        '90.01Z' => 'Arts du spectacle vivant',
        '85.20Z' => 'Enseignement primaire',
        '88.91A' => 'Accueil de jeunes enfants',
        '43.39Z' => 'Autres travaux de finition',
    ];

    return $map[$codeNaf] ?? $codeNaf;
}

function fetchUrlWithUserAgent(string $url, int $timeout = 5): ?string
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_USERAGENT,
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36'
    );

    $content = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error || $httpCode !== 200) {
        error_log("fetchUrlWithUserAgent: échec ({$httpCode}) {$error} pour {$url}");
        return null;
    }

    return $content;
}

/**
 * Récupère et met en cache l'ensemble des résultats Sirene pour Montjean,
 * en une seule pagination partagée entre associations et entreprises.
 *
 * Note : per_page=100 renvoie une erreur 400 côté API, 25 est la valeur
 * maximale qui fonctionne de façon fiable.
 */
function fetchSireneMontjean(): array
{
    static $cacheRaw = null;
    if ($cacheRaw !== null) {
        return $cacheRaw;
    }

    $cacheFile = getCacheDir() . '/sirene_raw.json';
    $cached = cacheGet($cacheFile, 86400);
    if ($cached !== null && !empty($cached)) {
        $cacheRaw = $cached;
        return $cached;
    }

    $allResults = [];
    $page = 1;
    $perPage = 25;
    $maxPages = 20;

    do {
        $url = "https://recherche-entreprises.api.gouv.fr/search?code_commune=53158&page={$page}&per_page={$perPage}";

        $json = fetchUrlWithUserAgent($url);
        if (!$json) break;

        $data = json_decode($json, true);
        if (!isset($data['results'])) break;

        $allResults = array_merge($allResults, $data['results']);

        $total = $data['total_results'] ?? 0;
        $page++;

    } while (($page - 1) * $perPage < $total && $page <= $maxPages);

    // Ne met en cache que si on a effectivement récupéré des résultats,
    // pour ne pas figer un échec temporaire de l'API pendant 24h.
    if (!empty($allResults)) {
        cacheSet($cacheFile, $allResults);
    }

    $cacheRaw = $allResults;

    return $allResults;
}

function getAssociationsMontjean(): array
{
    $cacheFile = getCacheDir() . '/associations.json';
    $cached = cacheGet($cacheFile, 86400);
    if ($cached) return $cached;

    $formesAsso = ['9210', '9220', '9221', '9230', '9300'];
    $results = [];
    $seen = [];

    foreach (fetchSireneMontjean() as $e) {

        if (!in_array($e['nature_juridique'] ?? '', $formesAsso)) continue;
        if (($e['etat_administratif'] ?? '') !== 'A') continue;

        $etab = $e['siege'] ?? null;
        if (!$etab) continue;

        // Sécurité : le siège doit vraiment être à Montjean
        if (($etab['commune'] ?? '') !== '53158') continue;

        $siret = $etab['siret'] ?? null;

        if ($siret && isset($seen[$siret])) continue;
        $seen[$siret] = true;

        $results[] = [
            'nom'       => $e['nom_complet'] ?? 'Association',
            'adresse'   => $etab['adresse'] ?? '',
            'objet'     => getLibelleActivite($e['activite_principale'] ?? null) ?? '',
            'siret'     => $siret,
            'telephone' => null,
            'email'     => null,
            'site'      => null,
            'facebook'  => null,
            'instagram' => null,
            'youtube'   => null,
            'linkedin'  => null,
        ];
    }

    usort($results, fn($a, $b) => strcmp($a['nom'], $b['nom']));
    cacheSet($cacheFile, $results);

    return $results;
}

function getEntreprisesMontjean(): array
{
    $cacheFile = getCacheDir() . '/entreprises.json';
    $cached = cacheGet($cacheFile, 86400);
    if ($cached) return $cached;

    $nafAutorises = [
        '41.', '42.', '43.',
        '45.',
        '47.11', '47.21',
        '56.10',
        '95.11',
        '96.02', '96.04',
        '75.00'
    ];

    $results = [];
    $seen = [];

    foreach (fetchSireneMontjean() as $e) {

        if (!empty($e['complements']['est_administration'])) continue;
        if (($e['etat_administratif'] ?? '') !== 'A') continue;

        $etab = $e['siege'] ?? null;
        if (!$etab) continue;

        // Sécurité : le siège doit vraiment être à Montjean
        if (($etab['commune'] ?? '') !== '53158') continue;

        $naf = $etab['activite_principale'] ?? '';
        if (!$naf) continue;

        $ok = false;
        foreach ($nafAutorises as $p) {
            if (str_starts_with($naf, $p)) {
                $ok = true;
                break;
            }
        }
        if (!$ok) continue;

        $siret = $etab['siret'] ?? null;
        if ($siret && isset($seen[$siret])) continue;
        $seen[$siret] = true;

        $results[] = [
            'nom'      => $e['nom_complet'] ?? 'Entreprise',
            'adresse'  => $etab['adresse'] ?? '',
            'activite' => getLibelleActivite($naf),
            'siret'    => $siret,
        ];
    }

    usort($results, fn($a, $b) => strcmp($a['nom'], $b['nom']));
    cacheSet($cacheFile, $results);

    return $results;
}