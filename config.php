<?php
define('ROOT', __DIR__);
define('ROOT_URL', ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);

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