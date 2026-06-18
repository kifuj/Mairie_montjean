<?php
define('APP_RUNNING', true);
$pageTitle = "Présentation de la commune";
$pageDescription = "Découvrez l'histoire, la situation géographique et le patrimoine naturel de Montjean (53320), en Mayenne.";
include __DIR__ . '/../../includes/header.php';
?>

<h1>Présentation de la commune</h1>

<section id="origine" class="presentation-item">
    <h2 class="presentation-titles">Origine et histoire</h2>
    <p class="presentation-texte">
        Le nom de Montjean provient du latin <em>Mons Joannis</em> ("le Mont de Jean").
        Le bourg est mentionné dès le Moyen Âge, notamment sur la liste des croisés
        de Mayenne partis en 1158.
    </p>
</section>

<section id="situation" class="presentation-item">
    <h2 class="presentation-title">Situation géographique</h2>
    <p class="presentation-texte">
        Montjean se situe à seize kilomètres de Laval, dans le canton de Loiron,
        en bordure de l'Oudon. La commune s'étend sur une superficie de 20 km²
        et son altitude moyenne est de 90 mètres.
    </p>
    <!-- // TODO Possibilité d'intégrer une carte interactive ici !\ -->
</section>

<section id="cours-eau" class="presentation-item">
    <h2 class="presentation-title">Relief et cours d'eau</h2>
    <p class="presentation-texte">
        Le territoire communal, traversé par l'Oudon, présente un relief peu marqué.
        L'étang de Montjean, d'une superficie de 25 hectares, est dominé par les
        ruines d'un ancien château fort et constitue un site naturel remarquable.
    </p>
</section>


<?php include __DIR__ . '/../../includes/footer.php'; ?>