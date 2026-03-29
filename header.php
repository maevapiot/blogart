<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ charger la config AVANT d'utiliser ROOT_URL
require_once __DIR__ . '/config.php';

/* cookies consent */
$cookieConsent = $_COOKIE['cookie_consent'] ?? null;
?>

<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <script src="https://www.google.com/recaptcha/api.js?render=6LfCB50sAAAAAAs37uxE04I09rn4DGI5R-Vik7CY"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?= ROOT_URL ?>/src/images/article1.png" />

    <!-- CSS HEADER -->
    <style>
        body { margin: 0; }
        .header { background: linear-gradient(90deg, #ff77d9, #9bbcff); padding: 12px 30px; }
        .header-container { display: flex; align-items: center; justify-content: space-between; }
        :root { --blue: #9bbcff; }
        .main-nav a { position: relative; transition: color 0.25s ease; }
        .main-nav a:hover { color: var(--blue); }
        .main-nav a::after { content: ""; position: absolute; left: 0; bottom: -6px; width: 0; height: 2px; background: var(--blue); transition: width 0.3s ease; }
        .main-nav a:hover::after { width: 100%; }
        .insolite i { transition: transform 0.3s ease, color 0.3s ease; }
        .insolite:hover i { transform: scale(1.15); color: var(--blue); }

        .logo { display: flex; align-items: center; gap: 10px; }
        .logo img { width: 45px; }
        .logo span { font-size: 26px; font-weight: 800; letter-spacing: 2px; color: #000; }
        .logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .logo span { color: #000; }
        .logo:hover { opacity: 0.85; }

        .main-nav { display: flex; gap: 40px; }
        .main-nav a { text-decoration: none; font-weight: 600; color: #000; }
        .main-nav a:hover { opacity: 0.7; }

        .header-icon img { width: 38px; height: auto; display: block; }
        .header-icon img:hover { opacity: 0.7; cursor: pointer; }

        .auth a { text-decoration: none; font-weight: 600; color: #000; }
        .login-btn { text-decoration: none; font-weight: 600; color: #000; transition: color 0.25s ease; }

        @media (max-width: 768px) {
            .header-container { flex-direction: column; gap: 15px; }
            .main-nav { gap: 20px; }
        }
    </style>
</head>

<body>

<?php if ($cookieConsent === null): ?>
<style>
    #cookie-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.55);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 999999;
    }
    #cookie-box {
        background: #fff;
        max-width: 560px;
        width: calc(100% - 24px);
        padding: 18px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,.25);
    }
</style>

<div id="cookie-overlay" role="dialog" aria-modal="true" aria-labelledby="cookie-title">
    <div id="cookie-box" class="bg-white">
        <h5 id="cookie-title" class="mb-2">Gestion des cookies</h5>

        <p class="mb-3">
            Nous utilisons des cookies nécessaires au fonctionnement du site.
            Avec votre accord, nous pouvons aussi utiliser des cookies optionnels (mesure d’audience, etc.).
            Vous devez choisir avant de continuer.
        </p>

        <div class="d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-outline-secondary" id="cookie-refuse">Refuser</button>
            <button type="button" class="btn btn-primary" id="cookie-accept">Accepter</button>
        </div>
    </div>
</div>

<script>
    document.documentElement.style.overflow = 'hidden';

    function setCookieConsent(value) {
        const maxAge = 60 * 60 * 24 * 180;
        document.cookie = "cookie_consent=" + value + "; Max-Age=" + maxAge + "; Path=/; SameSite=Lax";
        document.documentElement.style.overflow = '';
        window.location.reload();
    }

    document.getElementById('cookie-accept').addEventListener('click', function() {
        setCookieConsent('accept');
    });

    document.getElementById('cookie-refuse').addEventListener('click', function() {
        setCookieConsent('reject');
    });
</script>
<?php endif; ?>

<header class="header">
    <div class="header-container">

        <a href="<?= ROOT_URL ?>/index.php" class="logo">
            <img src="<?= ROOT_URL ?>/src/images/logo-swag.png" alt="Logo SWAG" style="width: 65px; height: auto;">
            <span><img src="<?= ROOT_URL ?>/src/images/Group 22.png" alt="Group 22" style="width: 125px; height: auto;"></span>
        </a>

        <nav class="main-nav">
            <a href="<?= ROOT_URL ?>/views/frontend/events.php">ÉVÉNEMENTS</a>
            <a href="<?= ROOT_URL ?>/views/frontend/original.php">INSOLITE</a>
            <a href="<?= ROOT_URL ?>/views/frontend/actors.php">ACTEURS</a>

            <?php if (isset($_SESSION['numStat']) && $_SESSION['numStat'] !== 3): ?>
                <a href="<?= ROOT_URL ?>/views/backend/dashboard.php">ADMIN</a>
            <?php endif; ?>
        </nav>

        <div class="header-icon">
            <a href="<?= ROOT_URL ?>/index.php" class="header-icon">
                <img src="<?= ROOT_URL ?>/src/images/icon-star.png" alt="Recherche" style="width: 60px; height: auto;">
            </a>
        </div>

        <?php if (isset($_SESSION['pseudoMemb'])): ?>
            <a href="<?= ROOT_URL ?>/api/security/disconnect.php" class="login-btn">LOGOUT</a>
        <?php else: ?>
            <a href="<?= ROOT_URL ?>/views/backend/security/login.php" class="login-btn">LOGIN</a>
        <?php endif; ?>

    </div>
</header>

</body>
</html>
