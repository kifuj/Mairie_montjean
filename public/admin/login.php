<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

startAdminSession();

// Déjà connecté → on file directement au dashboard
if (isAdminLoggedIn()) {
    header('Location: /admin/index.php');
    exit;
}

$error    = '';
$redirect = $_GET['redirect'] ?? '/admin/index.php';

if (!is_string($redirect) || strpos($redirect, '/admin/') !== 0) {
    $redirect = '/admin/index.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $postRedirect = (string) ($_POST['redirect'] ?? $redirect);

    if (!is_string($postRedirect) || strpos($postRedirect, '/admin/') !== 0) {
        $postRedirect = '/admin/index.php';
    }

    if ($email === '' || $password === '') {
        $error = 'Merci de renseigner votre email et votre mot de passe.';
    } elseif (attemptAdminLogin($email, $password)) {
        header('Location: ' . $postRedirect);
        exit;
    } else {
        $error = 'Identifiants incorrects.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Espace admin | Mairie de Montjean</title>
    <link rel="icon" href="/asset/images/favicon.png">
    <link rel="stylesheet" href="/asset/css/style.css">
    <link rel="stylesheet" href="/asset/css/admin.css">
</head>
<body class="admin-login-body">

    <main class="admin-login-wrap">
        <form class="admin-login-card" method="post" action="/admin/login.php" novalidate>

            <img src="/asset/images/logo.png" alt="Mairie de Montjean" class="admin-login-logo">

            <h1>Espace administration</h1>
            <p class="admin-login-sub">Mairie de Montjean (53320)</p>

            <?php if ($error): ?>
                <p class="admin-alert admin-alert--error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

            <label class="admin-field">
                <span>Email</span>
                <input type="email" name="email" required autofocus autocomplete="username">
            </label>

            <label class="admin-field">
                <span>Mot de passe</span>
                <input type="password" name="password" required autocomplete="current-password">
            </label>

            <button type="submit" class="btn admin-login-btn">Se connecter</button>

            <a href="/" class="admin-login-back">← Retour au site</a>

        </form>
    </main>

</body>
</html>