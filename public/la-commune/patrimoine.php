<?php
define('APP_RUNNING', true);
$pageTitle = "Patrimoine";
$pageDescription = "Découvrez le patrimoine de Montjean (53320), en Mayenne.";
include __DIR__ . '/../../includes/header.php';
?>
<h1>Patrimoine de Montjean en Mayenne</h1>

<section id="lanfriere" class="patrimoine-item">
    <div class="patrimoine-content">

        <img src="/assets/images/patrimoine/chateau-lanfriere.jpg"
            alt="Le château de la Lanfrière à Montjean, manoir du XIXe siècle" class="patrimoine-image" loading="lazy">

        <h2 class="patrimoine-titre">
            Le chateau de la Lanfrière
        </h2>

        <p class="patrimoine-texte">
            Le château de Lanfrière, situé à 800 mètres du bourg de Montjean en Mayenne, est un manoir du XIXe siècle
            érigé
            en 1840 par Morin de la Blottais. Ce dernier, en restaurant le domaine, y ajoute des éléments néogothiques
            comme
            des tourelles, des balustres et des sculptures en pierre, bien que le tuf friable utilisé menace leur
            durabilité. Le portail, datant de 1642, était autrefois flanqué d’une tour ronde, vestige d’une structure
            plus
            ancienne.
        </p>

    </div>
    <div class="patrimoine-actions">

        <a href="https://www.destination-mayenne.com/offres/chateau-de-la-lanfriere-montjean-fr-555477/#sheetPart-opening"
            class="patrimoine-btn" target="_blank" rel="noopener">
            Visiter
        </a>

        <a href="https://museedupatrimoine.fr/chateau-de-lanfriere-mayenne/89465.html" class="patrimoine-btn"
            target="_blank" rel="noopener">
            Son histoire
        </a>

    </div>
</section>

<section id="eglise" class="patrimoine-item">
    <div class="patrimoine-content">

        <img src="/assets/images/patrimoine/eglise.jpg" alt="Eglise Saint-Martin à Montjean, église du XIXe siècle"
            class="patrimoine-image" loading="lazy">

        <h2 class="patrimoine-titre">
            L'église Saint-Martin
        </h2>

        <p class="patrimoine-texte">
            L'église Saint-Martin de Montjean est un monument religieux implanté dans la commune de Montjean, en région
            Pays de la Loire.
        </p>

    </div>

    <div class="patrimoine-action">

        <a href="https://museedupatrimoine.fr/eglise-saint-martin-de-montjean-mayenne/76478.html" class="patrimoine-btn"
            target="_blank" rel="noopener">
            Son Histoire
        </a>

    </div>
</section>

<section id="chateau-montjean" class="patrimoine-item">

    <div class="patrimoine-content">

        <img src="/assets/images/patrimoine/chateau-montjean.jpg"
            alt="Le château de Montjean à Montjean, manoir du XVIe siècle" class="patrimoine-image" loading="lazy">

        <h2 class="patrimoine-titre">
            Chateau de Montjean
        </h2>

        <p class="patrimoine-texte">
            Le château de Montjean, situé près d’un étang à 2,5 km à l’est du bourg de Montjean (Mayenne), était au XVIe
            siècle une place forte défendant le comté de Laval contre les ligueurs. Reconstruit par André de Lohéac
            après 1450, il fut un enjeu stratégique pendant les guerres de Cent Ans et les conflits religieux.
        </p>

    </div>
    <div class="patrimoine-action">
        <a href="https://museedupatrimoine.fr/chateau-de-montjean-mayenne/89496.html" class="patrimoine-btn"
            target="_blank" rel="noopener">
            Son histoire
        </a>
    </div>

</section>
<?php
require_once __DIR__ . "/../../includes/footer.php";
?>