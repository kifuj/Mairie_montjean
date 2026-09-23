<?php
declare(strict_types=1);

// ============================================================
//  Mairie de Montjean — fonctions globales
// ============================================================


// ── Connexion PDO ────────────────────────────────────────────

function getPdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $pdo = require __DIR__ . '/../config/database.php';

    if (!$pdo instanceof PDO) {
        throw new RuntimeException('PDO non initialisé');
    }

    return $pdo;
}


// ── Horaires ─────────────────────────────────────────────────

/**
 * Retourne les horaires de la mairie sous forme label => "HHhMM – HHhMM[ / HHhMM – HHhMM]" (ou "Fermé").
 * Compatible avec renderList().
 *
 * Plusieurs lignes en base avec le même label (même jour) sont
 * regroupées et affichées sur une seule entrée, séparées par " / ".
 * Trie d'abord par ordre, donc l'ordre des créneaux dans la
 * journée dépend de la colonne `ordre` en base.
 */
function getHorairesMairie(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        SELECT label, heure_debut, heure_fin, ferme
        FROM horaires
        WHERE service = :service
        ORDER BY ordre ASC
    ');
    $stmt->execute([':service' => 'mairie']);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($rows as $row) {
        $label = $row['label'];

        if ($row['ferme']) {
            // Si le jour est marqué fermé, on l'affiche tel quel
            // (n'écrase pas un éventuel créneau déjà présent).
            if (!isset($result[$label])) {
                $result[$label] = 'Fermé';
            }
            continue;
        }

        $debut = substr($row['heure_debut'], 0, 5);
        $fin = substr($row['heure_fin'], 0, 5);
        $creneau = $debut . ' – ' . $fin;

        if (!isset($result[$label]) || $result[$label] === 'Fermé') {
            $result[$label] = $creneau;
        } else {
            $result[$label] .= ' et ' . $creneau;
        }
    }

    return $result;
}




/**
 * Retourne les horaires d'un service quelconque.
 * Optionnellement filtré par période ('hors_vacances', 'vacances', null).
 */
function getHorairesService(string $service, ?string $periode = null, ?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $sql = 'SELECT label, heure_debut, heure_fin, ferme, periode
            FROM horaires
            WHERE service = :service';

    $params = [':service' => $service];

    if ($periode !== null) {
        $sql .= ' AND periode = :periode';
        $params[':periode'] = $periode;
    }

    $sql .= ' ORDER BY ordre ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}


// ── Tarifs ───────────────────────────────────────────────────

/**
 * Retourne les tarifs du périscolaire.
 * Colonnes : groupe, label, tarif_commune, tarif_hors_commune
 * (aliasées depuis tarif_base / tarif_hors_commune pour compatibilité avec periscolaire.php)
 */
function getTarifsPeriscolaire(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        SELECT groupe,
               label,
               tarif_base          AS tarif_commune,
               tarif_hors_commune,
               unite
        FROM tarifs
        WHERE service = :service AND actif = 1
        ORDER BY ordre ASC
    ');
    $stmt->execute([':service' => 'periscolaire']);

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

/**
 * Retourne les tarifs d'un service quelconque.
 * Colonnes : groupe, label, tarif_base, tarif_hors_commune, unite
 */
function getTarifsService(string $service, ?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        SELECT groupe, label, tarif_base, tarif_hors_commune, unite
        FROM tarifs
        WHERE service = :service AND actif = 1
        ORDER BY ordre ASC
    ');
    $stmt->execute([':service' => $service]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}


// ── Associations & Entreprises ───────────────────────────────


// ============================================================
//  REMPLACE à nouveau getAssociationsMontjean() dans includes/function.php
//  (ajoute les réseaux de chaque association au résultat)
// ============================================================

function getAssociationsMontjean(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT id, nom, objet, adresse, telephone, email, site, logo
        FROM associations
        WHERE actif = 1
        ORDER BY ordre ASC, nom ASC
    ');
    $associations = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    if (empty($associations)) {
        return [];
    }

    $reseauxParAssociation = getReseauxGroupesParAssociation($pdo);

    foreach ($associations as &$asso) {
        $asso['reseaux'] = $reseauxParAssociation[(int) $asso['id']] ?? [];
    }
    unset($asso);

    return $associations;
}


function getEntreprisesMontjean(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT id, nom, activite, adresse, telephone, email, site
        FROM entreprises
        WHERE actif = 1
        ORDER BY ordre ASC, nom ASC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}


// ── Documents (PV & Arrêtés) ─────────────────────────────────

/**
 * Retourne les procès-verbaux triés par date décroissante.
 * Colonne date_document aliasée en date_seance pour compatibilité avec proces-verbaux.php.
 */
function getProcesVerbaux(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        SELECT id, titre, date_document AS date_seance, fichier
        FROM documents
        WHERE categorie = :cat AND visible = 1
        ORDER BY date_document DESC
    ');
    $stmt->execute([':cat' => 'pv']);

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

/**
 * Retourne les arrêtés d'une catégorie.
 * Catégories : 'municipal' | 'prefectoral' | 'departemental'
 * Colonne date_document aliasée en date_arrete pour compatibilité avec arretes.php.
 */
function getArretesParCategorie(string $categorie, ?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    // On préfixe la catégorie pour correspondre aux valeurs en base
    $categorieDb = 'arrete_' . $categorie;

    $stmt = $pdo->prepare('
        SELECT id, titre, date_document AS date_arrete, fichier
        FROM documents
        WHERE categorie = :cat AND visible = 1
        ORDER BY date_document DESC
    ');
    $stmt->execute([':cat' => $categorieDb]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}


// ── Assistants maternels ─────────────────────────────────────

function getAssistantsMaternels(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT nom, prenom, adresse, telephone, agrement, mam
        FROM assistants_maternels
        WHERE actif = 1
        ORDER BY ordre ASC, nom ASC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}


// ── Personnel périscolaire ───────────────────────────────────

function getPersonnelPeriscolaire(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT role, prenom, nom
        FROM personnel_periscolaire
        WHERE actif = 1
        ORDER BY ordre ASC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}


// ── Horaires périscolaire groupés pour cards ─────────────────

function getHorairesPeriscolaireGroupes(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        SELECT groupe, label, heure_debut, heure_fin
        FROM horaires
        WHERE service = :service
        ORDER BY ordre ASC
    ');
    $stmt->execute([':service' => 'periscolaire']);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    // Regroupe en cards
    $cards = [];
    foreach ($rows as $row) {
        $groupe = $row['groupe'];
        if (!isset($cards[$groupe])) {
            $cards[$groupe] = ['title' => $groupe, 'lines' => []];
        }
        $debut = substr($row['heure_debut'], 0, 5);
        $fin = substr($row['heure_fin'], 0, 5);
        $cards[$groupe]['lines'][] = $row['label'] . ' : ' . $debut . ' – ' . $fin;
    }

    return array_values($cards);
}

// ── Admin ───────────────────────────────────────────────────

function getAdminByEmail(string $email, ?PDO $pdo = null): ?array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        SELECT *
        FROM admin_users
        WHERE email = :email
        AND actif = 1
        LIMIT 1
    ');

    $stmt->execute([
        ':email' => $email
    ]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    return $admin ?: null;
}

function updateDerniereConnexion(int $id, ?PDO $pdo = null): void
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        UPDATE admin_users
        SET derniere_connexion = NOW()
        WHERE id = :id
    ');

    $stmt->execute([
        ':id' => $id
    ]);
}

function countTable(string $table, ?PDO $pdo = null): int
{
    $pdo ??= getPdo();

    $stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");

    return (int) $stmt->fetchColumn();
}

function countHoraires(): int
{
    return countTable('horaires');
}

function countTarifs(): int
{
    return countTable('tarifs');
}

function countDocuments(): int
{
    return countTable('documents');
}

function countAssociations(): int
{
    return countTable('associations');
}

function countEntreprises(): int
{
    return countTable('entreprises');
}

function getTousLesHoraires(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query("
        SELECT *
        FROM horaires
        ORDER BY service, ordre
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createHoraire(array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare("
        INSERT INTO horaires (
            service,
            groupe,
            label,
            heure_debut,
            heure_fin,
            periode,
            ferme,
            ordre
        ) VALUES (
            :service,
            :groupe,
            :label,
            :heure_debut,
            :heure_fin,
            :periode,
            :ferme,
            :ordre
        )
    ");

    return $stmt->execute([
        ':service' => $data['service'],
        ':groupe' => $data['groupe'] ?: null,
        ':label' => $data['label'],
        ':heure_debut' => $data['heure_debut'],
        ':heure_fin' => $data['heure_fin'],
        ':periode' => $data['periode'] ?: null,
        ':ferme' => $data['ferme'],
        ':ordre' => $data['ordre'],
    ]);
}

function getHoraireById(int $id, ?PDO $pdo = null): ?array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare("
        SELECT *
        FROM horaires
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function updateHoraire(int $id, array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare("
        UPDATE horaires
        SET
            service = :service,
            groupe = :groupe,
            label = :label,
            heure_debut = :heure_debut,
            heure_fin = :heure_fin,
            periode = :periode,
            ferme = :ferme,
            ordre = :ordre
        WHERE id = :id
    ");

    return $stmt->execute([
        ':id' => $id,
        ':service' => $data['service'],
        ':groupe' => $data['groupe'] ?: null,
        ':label' => $data['label'],
        ':heure_debut' => $data['heure_debut'],
        ':heure_fin' => $data['heure_fin'],
        ':periode' => $data['periode'] ?: null,
        ':ferme' => $data['ferme'],
        ':ordre' => $data['ordre'],
    ]);
}


function deleteHoraire(int $id, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('DELETE FROM horaires WHERE id = :id');

    return $stmt->execute([':id' => $id]);
}

/**
 * Retourne la liste distincte des services présents dans la table horaires.
 * Utile pour peupler un <select> dans le formulaire admin.
 */
function getServicesHoraires(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('SELECT DISTINCT service FROM horaires ORDER BY service ASC');

    return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
}

/**
 * Retourne TOUS les tarifs (actifs et inactifs), tous services confondus.
 * Pour usage admin uniquement — getTarifsService() reste la fonction
 * publique qui ne retourne que les tarifs actifs.
 */
function getTousLesTarifs(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT *
        FROM tarifs
        ORDER BY service ASC, ordre ASC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getTarifById(int $id, ?PDO $pdo = null): ?array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('SELECT * FROM tarifs WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function createTarif(array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        INSERT INTO tarifs (
            service, groupe, label, tarif_base, tarif_hors_commune, unite, ordre, actif
        ) VALUES (
            :service, :groupe, :label, :tarif_base, :tarif_hors_commune, :unite, :ordre, :actif
        )
    ');

    return $stmt->execute([
        ':service' => $data['service'],
        ':groupe' => $data['groupe'] !== '' ? $data['groupe'] : null,
        ':label' => $data['label'],
        ':tarif_base' => $data['tarif_base'] !== '' ? $data['tarif_base'] : null,
        ':tarif_hors_commune' => $data['tarif_hors_commune'] !== '' ? $data['tarif_hors_commune'] : null,
        ':unite' => $data['unite'] !== '' ? $data['unite'] : null,
        ':ordre' => $data['ordre'],
        ':actif' => $data['actif'],
    ]);
}

function updateTarif(int $id, array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        UPDATE tarifs
        SET
            service             = :service,
            groupe              = :groupe,
            label               = :label,
            tarif_base          = :tarif_base,
            tarif_hors_commune  = :tarif_hors_commune,
            unite               = :unite,
            ordre               = :ordre,
            actif               = :actif
        WHERE id = :id
    ');

    return $stmt->execute([
        ':id' => $id,
        ':service' => $data['service'],
        ':groupe' => $data['groupe'] !== '' ? $data['groupe'] : null,
        ':label' => $data['label'],
        ':tarif_base' => $data['tarif_base'] !== '' ? $data['tarif_base'] : null,
        ':tarif_hors_commune' => $data['tarif_hors_commune'] !== '' ? $data['tarif_hors_commune'] : null,
        ':unite' => $data['unite'] !== '' ? $data['unite'] : null,
        ':ordre' => $data['ordre'],
        ':actif' => $data['actif'],
    ]);
}

function deleteTarif(int $id, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('DELETE FROM tarifs WHERE id = :id');

    return $stmt->execute([':id' => $id]);
}

/**
 * Retourne TOUTES les associations (actives et inactives).
 * Pour usage admin — getAssociationsMontjean() reste la fonction
 * publique qui ne retourne que les associations actives.
 */
function getToutesLesAssociations(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT *
        FROM associations
        ORDER BY ordre ASC, nom ASC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getAssociationById(int $id, ?PDO $pdo = null): ?array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('SELECT * FROM associations WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function createAssociation(array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        INSERT INTO associations (
            nom, objet, adresse, telephone, email, site, logo, actif, ordre
        ) VALUES (
            :nom, :objet, :adresse, :telephone, :email, :site, :logo, :actif, :ordre
        )
    ');

    return $stmt->execute([
        ':nom' => $data['nom'],
        ':objet' => $data['objet'] !== '' ? $data['objet'] : null,
        ':adresse' => $data['adresse'] !== '' ? $data['adresse'] : null,
        ':telephone' => $data['telephone'] !== '' ? $data['telephone'] : null,
        ':email' => $data['email'] !== '' ? $data['email'] : null,
        ':site' => $data['site'] !== '' ? $data['site'] : null,
        ':logo' => $data['logo'] !== '' ? $data['logo'] : null,
        ':actif' => $data['actif'],
        ':ordre' => $data['ordre'],
    ]);
}

function updateAssociation(int $id, array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        UPDATE associations
        SET
            nom       = :nom,
            objet     = :objet,
            adresse   = :adresse,
            telephone = :telephone,
            email     = :email,
            site      = :site,
            logo      = :logo,
            actif     = :actif,
            ordre     = :ordre
        WHERE id = :id
    ');

    return $stmt->execute([
        ':id' => $id,
        ':nom' => $data['nom'],
        ':objet' => $data['objet'] !== '' ? $data['objet'] : null,
        ':adresse' => $data['adresse'] !== '' ? $data['adresse'] : null,
        ':telephone' => $data['telephone'] !== '' ? $data['telephone'] : null,
        ':email' => $data['email'] !== '' ? $data['email'] : null,
        ':site' => $data['site'] !== '' ? $data['site'] : null,
        ':logo' => $data['logo'] !== '' ? $data['logo'] : null,
        ':actif' => $data['actif'],
        ':ordre' => $data['ordre'],
    ]);
}

function deleteAssociation(int $id, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('DELETE FROM associations WHERE id = :id');

    return $stmt->execute([':id' => $id]);
}

function getToutesLesEntreprises(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT *
        FROM entreprises
        ORDER BY ordre ASC, nom ASC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getEntrepriseById(int $id, ?PDO $pdo = null): ?array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('SELECT * FROM entreprises WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function createEntreprise(array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        INSERT INTO entreprises (
            nom, activite, adresse, telephone, email, site, actif, ordre
        ) VALUES (
            :nom, :activite, :adresse, :telephone, :email, :site, :actif, :ordre
        )
    ');

    return $stmt->execute([
        ':nom' => $data['nom'],
        ':activite' => $data['activite'] !== '' ? $data['activite'] : null,
        ':adresse' => $data['adresse'] !== '' ? $data['adresse'] : null,
        ':telephone' => $data['telephone'] !== '' ? $data['telephone'] : null,
        ':email' => $data['email'] !== '' ? $data['email'] : null,
        ':site' => $data['site'] !== '' ? $data['site'] : null,
        ':actif' => $data['actif'],
        ':ordre' => $data['ordre'],
    ]);
}

function updateEntreprise(int $id, array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        UPDATE entreprises
        SET
            nom       = :nom,
            activite  = :activite,
            adresse   = :adresse,
            telephone = :telephone,
            email     = :email,
            site      = :site,
            actif     = :actif,
            ordre     = :ordre
        WHERE id = :id
    ');

    return $stmt->execute([
        ':id' => $id,
        ':nom' => $data['nom'],
        ':activite' => $data['activite'] !== '' ? $data['activite'] : null,
        ':adresse' => $data['adresse'] !== '' ? $data['adresse'] : null,
        ':telephone' => $data['telephone'] !== '' ? $data['telephone'] : null,
        ':email' => $data['email'] !== '' ? $data['email'] : null,
        ':site' => $data['site'] !== '' ? $data['site'] : null,
        ':actif' => $data['actif'],
        ':ordre' => $data['ordre'],
    ]);
}

function deleteEntreprise(int $id, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('DELETE FROM entreprises WHERE id = :id');

    return $stmt->execute([':id' => $id]);
}


/**
 * Retourne les réseaux d'une association donnée, triés par ordre.
 */
function getReseauxByAssociation(int $associationId, ?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        SELECT id, label, url, ordre
        FROM association_reseaux
        WHERE association_id = :association_id
        ORDER BY ordre ASC
    ');
    $stmt->execute([':association_id' => $associationId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

/**
 * Retourne tous les réseaux de toutes les associations, regroupés
 * par association_id. Utile pour éviter une requête par association
 * lors de l'affichage d'une liste complète.
 *
 * Format : [ association_id => [ ['label'=>..,'url'=>..], ... ], ... ]
 */
function getReseauxGroupesParAssociation(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT association_id, label, url, ordre
        FROM association_reseaux
        ORDER BY association_id ASC, ordre ASC
    ');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    $groupes = [];
    foreach ($rows as $row) {
        $groupes[(int) $row['association_id']][] = [
            'label' => $row['label'],
            'url' => $row['url'],
        ];
    }

    return $groupes;
}

/**
 * Remplace entièrement les réseaux d'une association (supprime puis
 * réinsère). Plus simple à gérer côté formulaire admin qu'un diff fin.
 *
 * $reseaux : tableau de ['label' => string, 'url' => string]
 */
function saveReseauxForAssociation(int $associationId, array $reseaux, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $pdo->beginTransaction();

    try {
        $stmtDelete = $pdo->prepare('DELETE FROM association_reseaux WHERE association_id = :association_id');
        $stmtDelete->execute([':association_id' => $associationId]);

        $stmtInsert = $pdo->prepare('
            INSERT INTO association_reseaux (association_id, label, url, ordre)
            VALUES (:association_id, :label, :url, :ordre)
        ');

        $ordre = 0;
        foreach ($reseaux as $reseau) {
            $label = trim((string) ($reseau['label'] ?? ''));
            $url = trim((string) ($reseau['url'] ?? ''));

            if ($label === '' || $url === '') {
                continue;
            }

            $stmtInsert->execute([
                ':association_id' => $associationId,
                ':label' => $label,
                ':url' => $url,
                ':ordre' => $ordre,
            ]);
            $ordre++;
        }

        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        $pdo->rollBack();
        return false;
    }
}

function getTousLesDocuments(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT *
        FROM documents
        ORDER BY categorie ASC, date_document DESC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getDocumentById(int $id, ?PDO $pdo = null): ?array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('SELECT * FROM documents WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function createDocument(array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        INSERT INTO documents (categorie, titre, fichier, date_document, visible, ordre)
        VALUES (:categorie, :titre, :fichier, :date_document, :visible, :ordre)
    ');

    return $stmt->execute([
        ':categorie' => $data['categorie'],
        ':titre' => $data['titre'],
        ':fichier' => $data['fichier'],
        ':date_document' => $data['date_document'] !== '' ? $data['date_document'] : null,
        ':visible' => $data['visible'],
        ':ordre' => $data['ordre'],
    ]);
}

function updateDocument(int $id, array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        UPDATE documents
        SET
            categorie     = :categorie,
            titre         = :titre,
            fichier       = :fichier,
            date_document = :date_document,
            visible       = :visible,
            ordre         = :ordre
        WHERE id = :id
    ');

    return $stmt->execute([
        ':id' => $id,
        ':categorie' => $data['categorie'],
        ':titre' => $data['titre'],
        ':fichier' => $data['fichier'],
        ':date_document' => $data['date_document'] !== '' ? $data['date_document'] : null,
        ':visible' => $data['visible'],
        ':ordre' => $data['ordre'],
    ]);
}

function deleteDocument(int $id, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('DELETE FROM documents WHERE id = :id');

    return $stmt->execute([':id' => $id]);
}

// ── Assistants maternels ─────────────────────────────────────

function getTousLesAssistants(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT * FROM assistants_maternels
        ORDER BY ordre ASC, nom ASC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getAssistantById(int $id, ?PDO $pdo = null): ?array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('SELECT * FROM assistants_maternels WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function createAssistant(array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        INSERT INTO assistants_maternels
            (nom, prenom, adresse, telephone, agrement, mam, actif, ordre)
        VALUES
            (:nom, :prenom, :adresse, :telephone, :agrement, :mam, :actif, :ordre)
    ');

    return $stmt->execute([
        ':nom' => $data['nom'],
        ':prenom' => $data['prenom'],
        ':adresse' => $data['adresse'] !== '' ? $data['adresse'] : null,
        ':telephone' => $data['telephone'] !== '' ? $data['telephone'] : null,
        ':agrement' => (int) $data['agrement'],
        ':mam' => $data['mam'] !== '' ? $data['mam'] : null,
        ':actif' => $data['actif'],
        ':ordre' => $data['ordre'],
    ]);
}

function updateAssistant(int $id, array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        UPDATE assistants_maternels
        SET
            nom       = :nom,
            prenom    = :prenom,
            adresse   = :adresse,
            telephone = :telephone,
            agrement  = :agrement,
            mam       = :mam,
            actif     = :actif,
            ordre     = :ordre
        WHERE id = :id
    ');

    return $stmt->execute([
        ':id' => $id,
        ':nom' => $data['nom'],
        ':prenom' => $data['prenom'],
        ':adresse' => $data['adresse'] !== '' ? $data['adresse'] : null,
        ':telephone' => $data['telephone'] !== '' ? $data['telephone'] : null,
        ':agrement' => (int) $data['agrement'],
        ':mam' => $data['mam'] !== '' ? $data['mam'] : null,
        ':actif' => $data['actif'],
        ':ordre' => $data['ordre'],
    ]);
}

function deleteAssistant(int $id, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('DELETE FROM assistants_maternels WHERE id = :id');

    return $stmt->execute([':id' => $id]);
}


// ── Personnel périscolaire ────────────────────────────────────

function getToutLePersonnel(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT * FROM personnel_periscolaire
        ORDER BY ordre ASC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getPersonnelById(int $id, ?PDO $pdo = null): ?array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('SELECT * FROM personnel_periscolaire WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function createPersonnel(array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        INSERT INTO personnel_periscolaire (role, prenom, nom, ordre, actif)
        VALUES (:role, :prenom, :nom, :ordre, :actif)
    ');

    return $stmt->execute([
        ':role' => $data['role'],
        ':prenom' => $data['prenom'],
        ':nom' => $data['nom'],
        ':ordre' => $data['ordre'],
        ':actif' => $data['actif'],
    ]);
}

function updatePersonnel(int $id, array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        UPDATE personnel_periscolaire
        SET role = :role, prenom = :prenom, nom = :nom, ordre = :ordre, actif = :actif
        WHERE id = :id
    ');

    return $stmt->execute([
        ':id' => $id,
        ':role' => $data['role'],
        ':prenom' => $data['prenom'],
        ':nom' => $data['nom'],
        ':ordre' => $data['ordre'],
        ':actif' => $data['actif'],
    ]);
}

function deletePersonnel(int $id, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('DELETE FROM personnel_periscolaire WHERE id = :id');

    return $stmt->execute([':id' => $id]);
}


// ============================================================
//  À AJOUTER À LA FIN DE includes/function.php
//  CRUD — Messages de contact
// ============================================================

function createMessage(array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        INSERT INTO messages_contact (nom, email, telephone, sujet, message)
        VALUES (:nom, :email, :telephone, :sujet, :message)
    ');

    return $stmt->execute([
        ':nom'       => $data['nom'],
        ':email'     => $data['email'],
        ':telephone' => $data['telephone'] !== '' ? $data['telephone'] : null,
        ':sujet'     => $data['sujet'],
        ':message'   => $data['message'],
    ]);
}

function getTousLesMessages(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT * FROM messages_contact
        ORDER BY lu ASC, created_at DESC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getMessageById(int $id, ?PDO $pdo = null): ?array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('SELECT * FROM messages_contact WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function marquerMessageLu(int $id, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('UPDATE messages_contact SET lu = 1 WHERE id = :id');

    return $stmt->execute([':id' => $id]);
}

function deleteMessage(int $id, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('DELETE FROM messages_contact WHERE id = :id');

    return $stmt->execute([':id' => $id]);
}

function countMessagesNonLus(?PDO $pdo = null): int
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('SELECT COUNT(*) FROM messages_contact WHERE lu = 0');

    return (int) $stmt->fetchColumn();
}


// ============================================================
//  À AJOUTER À LA FIN DE includes/function.php
//  CRUD — Liens/documents par page (table page_liens)
// ============================================================

/**
 * Retourne les liens visibles d'une page, triés par ordre.
 * Construit l'URL finale selon le type (fichier ou externe).
 *
 * Format retourné : [['label' => '...', 'link' => '...'], ...]
 * Compatible avec renderActions().
 */
function getLiensPage(string $page, ?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        SELECT label, type, valeur
        FROM page_liens
        WHERE page = :page AND visible = 1
        ORDER BY ordre ASC
    ');
    $stmt->execute([':page' => $page]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    return array_map(function ($row) {
        $link = $row['type'] === 'fichier'
            ? '/uploads/demarche/' . $row['valeur']
            : $row['valeur'];

        return ['label' => $row['label'], 'link' => $link];
    }, $rows);
}

/**
 * Retourne tous les liens (actifs et inactifs), tous slugs confondus.
 * Pour usage admin uniquement.
 */
function getTousLesLiens(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT * FROM page_liens
        ORDER BY page ASC, ordre ASC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getLienById(int $id, ?PDO $pdo = null): ?array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('SELECT * FROM page_liens WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function createLien(array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        INSERT INTO page_liens (page, label, type, valeur, visible, ordre)
        VALUES (:page, :label, :type, :valeur, :visible, :ordre)
    ');

    return $stmt->execute([
        ':page'    => $data['page'],
        ':label'   => $data['label'],
        ':type'    => $data['type'],
        ':valeur'  => $data['valeur'],
        ':visible' => $data['visible'],
        ':ordre'   => $data['ordre'],
    ]);
}

function updateLien(int $id, array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        UPDATE page_liens
        SET page = :page, label = :label, type = :type,
            valeur = :valeur, visible = :visible, ordre = :ordre
        WHERE id = :id
    ');

    return $stmt->execute([
        ':id'      => $id,
        ':page'    => $data['page'],
        ':label'   => $data['label'],
        ':type'    => $data['type'],
        ':valeur'  => $data['valeur'],
        ':visible' => $data['visible'],
        ':ordre'   => $data['ordre'],
    ]);
}

function deleteLien(int $id, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('DELETE FROM page_liens WHERE id = :id');

    return $stmt->execute([':id' => $id]);
}


// ============================================================
//  À AJOUTER À LA FIN DE includes/function.php
//  CRUD — Élus / Équipe municipale
// ============================================================

function getTousLesElus(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT * FROM elus
        ORDER BY ordre ASC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getElusActifs(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->query('
        SELECT * FROM elus
        WHERE actif = 1
        ORDER BY ordre ASC
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getEluById(int $id, ?PDO $pdo = null): ?array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('SELECT * FROM elus WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function createElu(array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        INSERT INTO elus (ordre, fonction, civilite, nom, prenom, actif)
        VALUES (:ordre, :fonction, :civilite, :nom, :prenom, :actif)
    ');

    return $stmt->execute([
        ':ordre'    => $data['ordre'],
        ':fonction' => $data['fonction'],
        ':civilite' => $data['civilite'],
        ':nom'      => $data['nom'],
        ':prenom'   => $data['prenom'],
        ':actif'    => $data['actif'],
    ]);
}

function updateElu(int $id, array $data, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        UPDATE elus
        SET ordre = :ordre, fonction = :fonction, civilite = :civilite,
            nom = :nom, prenom = :prenom, actif = :actif
        WHERE id = :id
    ');

    return $stmt->execute([
        ':id'       => $id,
        ':ordre'    => $data['ordre'],
        ':fonction' => $data['fonction'],
        ':civilite' => $data['civilite'],
        ':nom'      => $data['nom'],
        ':prenom'   => $data['prenom'],
        ':actif'    => $data['actif'],
    ]);
}

function deleteElu(int $id, ?PDO $pdo = null): bool
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('DELETE FROM elus WHERE id = :id');

    return $stmt->execute([':id' => $id]);
}


// ============================================================
//  À AJOUTER À LA FIN DE includes/function.php
//  Bulletins municipaux (réutilise la table `documents`)
// ============================================================

function getBulletinsMunicipaux(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        SELECT id, titre, date_document, fichier
        FROM documents
        WHERE categorie = :cat AND visible = 1
        ORDER BY date_document DESC
    ');
    $stmt->execute([':cat' => 'bulletin']);

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getTousLesBulletins(?PDO $pdo = null): array
{
    $pdo ??= getPdo();

    $stmt = $pdo->prepare('
        SELECT *
        FROM documents
        WHERE categorie = :cat
        ORDER BY date_document DESC
    ');
    $stmt->execute([':cat' => 'bulletin']);

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}