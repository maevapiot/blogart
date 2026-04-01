<?php
define('ROOT', __DIR__);

$isHttps =
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);

define('ROOT_URL', ($isHttps ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);

// Charger le .env seulement s'il existe
if (file_exists(__DIR__ . '/.env')) {
    require_once __DIR__ . '/includes/libs/DotEnv.php';
    (new DotEnv(__DIR__ . '/.env'))->load();
}

// defines
require_once ROOT . '/config/defines.php';

// debug
if (getenv('APP_DEBUG') === 'true') {
    require_once ROOT . '/config/debug.php';
}

// load functions
require_once ROOT . '/functions/global.inc.php';

// load security
require_once ROOT . '/config/security.php';