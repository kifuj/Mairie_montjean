<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/function.php';

$pageTitle = "Accueil";
$pageDescription = "Site officiel de la Mairie de Montjean (53320) : actualités, démarches administratives, vie locale et informations pratiques.";
require_once __DIR__ . '/../includes/header.php';

?>

<!--Banniere-->
<section id="hero">
    <img src="#" alt="banniere">
    <div>
        <h1>Bienvenue à Montjean</h1>
        <p>Site officiel de la commune</p>
    </div>
</section>

<!--Presentation-->
<section id="presentation">
    <div>
        <h2>Montjean et son histoire</h2>
    </div>
    <div>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis repudiandae maime dignissimos, itaque
        nam
        minima numqua
        m quaerat laudantium eius, veniam a nisi. Sint vero quam eercitationem, dicta commodi porro
        saepe.
    </div>
    <a href="/la-commune/presentation.php" class="btn">en savoir plus</a>
</section>

<!--Horaire-->
<section id="horaires">
    <h2>Horaires de la mairie</h2>
    <ul>
        <?php foreach (getHorairesMairie() as $jour => $heures): ?>
            <li><?= htmlspecialchars($jour) ?> : <?= htmlspecialchars($heures) ?></li>
        <?php endforeach; ?>
    </ul>
</section>

<!--infos importantes-->
<section id="infos-importantes">
    <h2>Info importante</h2>
    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Beatae explicabo doloribus molestiae nostrum
        accusantium a repellat cupiditate maiores error assumenda atque necessitatibus doloremque iusto aut,
        blanditiis inventore. Beatae, sint similique?</p>
</section>

<!--Actualités-->
<section id="actualites">
    <h2>Actualités</h2>

    <div id="panneau-pocket-feed">
            <iframe
                src="https://app.panneaupocket.com/embeded/1295380060?mode=widgetConfig&autoNavigation=0&widgetConfigId=1f1697e4-c2b7-6862-afd1-318931428377"
                style="height:518px;width:1280px; max-height:100%; width: 100%; border:none"></iframe>
            <iframe
                src="https://app.panneaupocket.com/embeded/1295380060?mode=widgetConfig&autoNavigation=0&widgetConfigId=1f1697e0-4c68-6c1e-a9f1-4bbb187e512f"
                style="height:518px;width:1280px; max-height:100%; width: 100%; border:none"></iframe>

    </div>

    <a href="#">Voir toutes les publications</a>
</section>

<!--Acces rapide-->
<section id="acces-rapides">
    <div>
        <h2>Acces rapide</h2>
    </div>
    <div>
        <div>
            <span><a href="#">État civil</a></span>
            <span><a href="#">Urbanisme</a></span>
        </div>
        <div>
            <span><a href="#">École</a></span>
            <span><a href="#">Bibliothèque</a></span>
        </div>
        <div>
            <span><a href="#">Associations</a></span>
            <span><a href="#">Comptes-rendus</a></span>
        </div>
        <div>
            <span><a href="#">Mon espace famille</a></span>
        </div>
    </div>
</section>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>