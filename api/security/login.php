<?php
session_start();

// Chargement de la config et des fonctions
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';


if (!isset($_POST['g-recaptcha-response'])) {
    $_SESSION['error_message'] = "Captcha manquant.";
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit;
}

$token = $_POST['g-recaptcha-response'];
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = [
    'secret'   => getenv('RECAPTCHA_SECRET_KEY'),
    'response' => $token
];

$options = [
    'http' => [
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    ]
];

$context  = stream_context_create($options);
$result   = file_get_contents($url, false, $context);
$response = json_decode($result);


if (!$response->success || $response->score < 0.5) {
    $_SESSION['error_message'] = "Échec du CAPTCHA - Score insuffisant.";
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit;
}


$pseudoMemb = ctrlSaisies($_POST['pseudoMemb'] ?? '');
$passMemb   = $_POST['passMemb'] ?? '';

$res = sql_select('membre', '*', "pseudoMemb = '$pseudoMemb'");

// Si le tableau est vide ou false, l'utilisateur n'existe pas
if (!$res || count($res) === 0) {
    $_SESSION['error_message'] = "Pseudo ou mot de passe incorrect.";
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit;
}

// On récupère la première ligne du résultat
$membre  = $res[0];
$passBDD = $membre['passMemb'] ?? ''; 


$ok = false;


if (password_verify($passMemb, $passBDD)) {
    $ok = true;
}


if (!$ok && $passMemb === $passBDD) {
    $ok = true;
}

// Si ni l'un ni l'autre ne marche
if (!$ok) {
    $_SESSION['error_message'] = "Pseudo ou mot de passe incorrect.";
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit;
}



// Sécurité 
session_regenerate_id(true);

$_SESSION['logged_at']  = time();
$_SESSION['numMemb']    = (int)$membre['numMemb'];
$_SESSION['pseudoMemb'] = $membre['pseudoMemb'];
$_SESSION['numStat']    = (int)$membre['numStat'];
$_SESSION['prenomMemb'] = $membre['prenomMemb']; 


if ($_SESSION['numStat'] === 1 || $_SESSION['numStat'] === 2) {
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit;
}


header("Location: " . ROOT_URL . "/index.php");
exit;
?>