<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/function.php';
require_once __DIR__ . '/../includes/components/loader.php';

$pageClass       = 'contact';
$pageTitle       = "Nous contacter";
$pageDescription = "Formulaire de contact de la Mairie de Montjean (53320).";

$success = false;
$error   = '';
$values  = ['nom' => '', 'email' => '', 'telephone' => '', 'sujet' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'nom'       => trim((string) ($_POST['nom'] ?? '')),
        'email'     => trim((string) ($_POST['email'] ?? '')),
        'telephone' => trim((string) ($_POST['telephone'] ?? '')),
        'sujet'     => trim((string) ($_POST['sujet'] ?? '')),
        'message'   => trim((string) ($_POST['message'] ?? '')),
    ];

    if ($values['nom'] === '') {
        $error = "Merci de renseigner votre nom.";
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } elseif ($values['sujet'] === '') {
        $error = "Merci de renseigner un sujet.";
    } elseif (strlen($values['message']) < 10) {
        $error = "Le message est trop court.";
    } else {
        if (createMessage($values)) {
            $success = true;
            $values  = ['nom' => '', 'email' => '', 'telephone' => '', 'sujet' => '', 'message' => ''];
        } else {
            $error = "Une erreur est survenue. Merci de réessayer.";
        }
    }
}

require_once __DIR__ . '/../includes/header.php';

renderHero($pageClass, "Nous contacter", "Posez-nous vos questions.", "/asset/images/contact.png");
?>

<section class="section contact__section">
    <div class="section-content contact__content">

        <div class="contact__grid">

            <!-- Formulaire -->
            <div class="contact__form-wrap">

                <h2 class="section-title">Envoyer un message</h2>

                <?php if ($success): ?>
                    <div class="contact-success">
                        <p>✓ Votre message a bien été envoyé. Nous vous répondrons dans les meilleurs délais.</p>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <p class="contact-error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <form class="contact-form" method="post" action="/contact.php" novalidate>

                    <div class="contact-form__row">
                        <label class="contact-form__field">
                            <span>Nom & Prénom <span class="required">*</span></span>
                            <input type="text" name="nom" value="<?= htmlspecialchars($values['nom']) ?>"
                                   required autocomplete="name">
                        </label>

                        <label class="contact-form__field">
                            <span>Email <span class="required">*</span></span>
                            <input type="email" name="email" value="<?= htmlspecialchars($values['email']) ?>"
                                   required autocomplete="email">
                        </label>
                    </div>

                    <div class="contact-form__row">
                        <label class="contact-form__field">
                            <span>Téléphone <span class="optional">(optionnel)</span></span>
                            <input type="tel" name="telephone" value="<?= htmlspecialchars($values['telephone']) ?>"
                                   autocomplete="tel">
                        </label>

                        <label class="contact-form__field">
                            <span>Sujet <span class="required">*</span></span>
                            <input type="text" name="sujet" value="<?= htmlspecialchars($values['sujet']) ?>" required>
                        </label>
                    </div>

                    <label class="contact-form__field contact-form__field--full">
                        <span>Message <span class="required">*</span></span>
                        <textarea name="message" rows="6" required><?= htmlspecialchars($values['message']) ?></textarea>
                    </label>

                    <p class="contact-form__mention">* Champs obligatoires</p>

                    <button type="submit" class="btn">Envoyer le message</button>

                </form>

            </div>

            <!-- Infos mairie -->
            <aside class="contact__info">

                <h2 class="section-title">Coordonnées</h2>

                <div class="contact-info-block">
                    <h3>Mairie de Montjean</h3>
                    <address>
                        Square Henri de Monti<br>
                        53320 Montjean<br><br>
                        <a href="tel:0243021108">02 43 02 11 08</a><br>
                        <a href="mailto:contact@mairie-montjean53.fr">contact@mairie-montjean53.fr</a>
                    </address>
                </div>

            </aside>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>