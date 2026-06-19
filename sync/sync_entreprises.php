<?php
function syncEntreprisesFromSirene(string $codeCommune = '53158', ?PDO $pdo = null, bool $debug = false): int
{
    $pdo ??= getPdo();
    $data = fetchSireneMontjean($codeCommune);
    if (empty($data)) {
        return 0;
    }
    $count = 0;
    $skipped = 0;
    $stmt = $pdo->prepare("
        INSERT INTO entreprises (
            siret, nom, adresse, activite, codeNAF, description
        )
        VALUES (
            :siret, :nom, :adresse, :activite, :naf, :description
        )
        ON DUPLICATE KEY UPDATE
            nom = VALUES(nom),
            adresse = VALUES(adresse),
            activite = VALUES(activite),
            codeNAF = VALUES(codeNAF),
            updated_at = CURRENT_TIMESTAMP
    ");
    foreach ($data as $item) {
        // Le SIRET du siège est dans item['siege']['siret'],
        // pas à la racine ni dans 'etablissement' (cf. ancien code SireneMontjean).
        $etab = $item['siege'] ?? [];
        $siret = $etab['siret'] ?? null;
        if (!is_string($siret)) {
            $skipped++;
            continue;
        }
        $siret = preg_replace('/\D/', '', $siret);
        if (strlen($siret) !== 14) {
            $skipped++;
            continue;
        }
        $naf =
            $etab['activite_principale']
            ?? ($item['activite_principale'] ?? null);
        // ⚠️ IMPORTANT : ne pas bloquer NULL
        if (!$naf) {
            $naf = 'INCONNU';
        }
        if (!isNafAutorise($naf, $pdo)) {
            $skipped++;
            continue;
        }
        $nom =
            $item['nom_complet']
            ?? $item['denomination']
            ?? ($item['unite_legale']['denomination'] ?? 'Sans nom');
        $adresse = normaliseSireneAdresse($etab);
        $libelleActivite = getLibelleActivite($naf, $pdo);
        try {
            $stmt->execute([
                ':siret' => $siret,
                ':nom' => $nom,
                ':adresse' => $adresse,
                ':activite' => $libelleActivite,
                ':naf' => $naf,
                ':description' => $item['categorie_entreprise'] ?? null,
            ]);
            $count++;
            if ($debug) {
                echo "OK $siret | $nom | $naf ($libelleActivite)\n";
            }
        } catch (PDOException $e) {
            $skipped++;
            if ($debug) {
                echo "DB ERROR $siret : " . $e->getMessage() . PHP_EOL;
            }
        }
    }
    if ($debug) {
        echo "DONE | OK=$count | SKIP=$skipped\n";
    }
    return $count;
}