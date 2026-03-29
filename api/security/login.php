<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';

function redirectLoginError(string $message): void
{
    $_SESSION['error_message'] = $message;
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit;
}

// Mets ta vraie SECRET key v2 ici temporairement
$secretKey = '6LezC50sAAAAAKXF-hWlIgyT0FMCRfDaocmnL1tW';

if (empty($_POST['g-recaptcha-response'])) {
    redirectLoginError("Captcha manquant.");
}

if (empty($secretKey)) {
    redirectLoginError("Clé secrète CAPTCHA manquante.");
}

$token = $_POST['g-recaptcha-response'];

$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = [
    'secret'   => $secretKey,
    'response' => $token,
    'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
];

$options = [
    'http' => [
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
        'timeout' => 10
    ]
];

$context = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

if ($result === false) {
    redirectLoginError("Impossible de vérifier le captcha.");
}

$response = json_decode($result, true);

if (!$response || empty($response['success'])) {
    $errorCodes = isset($response['error-codes']) ? implode(', ', $response['error-codes']) : 'unknown';
    redirectLoginError("Captcha invalide : " . $errorCodes);
}

$pseudoMemb = ctrlSaisies($_POST['pseudoMemb'] ?? '');
$passMemb   = $_POST['passMemb'] ?? '';

if ($pseudoMemb === '' || $passMemb === '') {
    redirectLoginError("Pseudo ou mot de passe manquant.");
}

$res = sql_select('membre', '*', "pseudoMemb = '$pseudoMemb'");

if (!$res || count($res) === 0) {
    redirectLoginError("Pseudo ou mot de passe incorrect.");
}

$membre = $res[0];
$passBDD = $membre['passMemb'] ?? '';

$ok = false;

if (!empty($passBDD) && password_verify($passMemb, $passBDD)) {
    $ok = true;
}

if (!$ok && $passMemb === $passBDD) {
    $ok = true;
}

if (!$ok) {
    redirectLoginError("Pseudo ou mot de passe incorrect.");
}

session_regenerate_id(true);

$_SESSION['logged_at']  = time();
$_SESSION['numMemb']    = (int)($membre['numMemb'] ?? 0);
$_SESSION['pseudoMemb'] = $membre['pseudoMemb'] ?? '';
$_SESSION['numStat']    = (int)($membre['numStat'] ?? 0);
$_SESSION['prenomMemb'] = $membre['prenomMemb'] ?? '';

if ($_SESSION['numStat'] === 1 || $_SESSION['numStat'] === 2) {
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit;
}

header("Location: " . ROOT_URL . "/index.php");
exit;