<?php
declare(strict_types=1);

// ============================================================
//  Mairie de Montjean — Authentification admin
//  Destination réelle : includes/admin/auth.php
// ============================================================

/**
 * Démarre (ou reprend) la session admin.
 * Nom de session dédié pour ne pas entrer en conflit avec
 * une éventuelle session côté site public.
 */
function startAdminSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_name('mtj_admin');
        session_start();
    }
}

function isAdminLoggedIn(): bool
{
    startAdminSession();

    return !empty($_SESSION['admin_id']);
}

/**
 * À appeler en tout début de chaque page admin protégée.
 * Redirige vers la connexion si l'utilisateur n'est pas authentifié.
 */
function requireAdmin(): void
{
    startAdminSession();

    if (empty($_SESSION['admin_id'])) {
        $redirect = $_SERVER['REQUEST_URI'] ?? '/admin/index.php';
        header('Location: /admin/login.php?redirect=' . urlencode($redirect));
        exit;
    }
}

/**
 * Tente une connexion. Retourne true si succès (session ouverte),
 * false sinon.
 */
function attemptAdminLogin(string $email, string $password): bool
{
    startAdminSession();

    $admin = getAdminByEmail($email);

    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        return false;
    }

    // Empêche la fixation de session
    session_regenerate_id(true);

    $_SESSION['admin_id']     = (int) $admin['id'];
    $_SESSION['admin_nom']    = $admin['nom'];
    $_SESSION['admin_prenom'] = $admin['prenom'];
    $_SESSION['admin_email']  = $admin['email'];

    updateDerniereConnexion((int) $admin['id']);

    return true;
}

function logoutAdmin(): void
{
    startAdminSession();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function currentAdminName(): string
{
    startAdminSession();

    return trim(($_SESSION['admin_prenom'] ?? '') . ' ' . ($_SESSION['admin_nom'] ?? ''));
}

function currentAdminId(): ?int
{
    startAdminSession();

    return isset($_SESSION['admin_id']) ? (int) $_SESSION['admin_id'] : null;
}