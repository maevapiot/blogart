<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

// Check droits : Admin (1) ou Modo (2)
if (!isset($_SESSION['numStat']) || (int)$_SESSION['numStat'] > 2) {
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit();
}

/* ------------------------------------------------------
   1) ReCAPTCHA
------------------------------------------------------ */
if (!isset($_POST['g-recaptcha-response'])) {
    $_SESSION['error_message'] = "Captcha manquant.";
    header("Location: " . ROOT_URL . "/views/backend/members/create.php");
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
    header("Location: " . ROOT_URL . "/views/backend/members/create.php");
    exit;
}

/* ------------------------------------------------------
   2) Saisies & Validations
------------------------------------------------------ */
$nomMemb    = ctrlSaisies($_POST['nomMemb'] ?? '');
$prenomMemb = ctrlSaisies($_POST['prenomMemb'] ?? '');
$pseudoMemb = ctrlSaisies($_POST['pseudoMemb'] ?? '');
$eMailMemb  = ctrlSaisies($_POST['eMailMemb'] ?? '');
$eMailMemb2 = ctrlSaisies($_POST['eMailMemb2'] ?? '');
$passMemb   = ctrlSaisies($_POST['passMemb'] ?? '');
$passMemb2  = ctrlSaisies($_POST['passMemb2'] ?? '');
$numStat    = (int)($_POST['numStat'] ?? 3); 

// Pseudo (6-70 chars)
if (!preg_match('/^.{6,70}$/', $pseudoMemb)) {
    $_SESSION['error_message'] = "Le pseudo doit contenir entre 6 et 70 caractères.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Unicité Pseudo
if (sql_select('membre', 'pseudoMemb', "pseudoMemb = '$pseudoMemb'")) {
    $_SESSION['error_message'] = "Ce pseudo est déjà utilisé.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Champs requis
if (empty($prenomMemb) || empty($nomMemb)) {
    $_SESSION['error_message'] = "Nom et prénom obligatoires.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Emails
if (!filter_var($eMailMemb, FILTER_VALIDATE_EMAIL) || !filter_var($eMailMemb2, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Email invalide.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
if ($eMailMemb !== $eMailMemb2) {
    $_SESSION['error_message'] = "Les emails ne correspondent pas.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Mots de passe
$passwordPattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,15}$/";
if (!preg_match($passwordPattern, $passMemb) || !preg_match($passwordPattern, $passMemb2)) {
    $_SESSION['error_message'] = "Mot de passe non conforme (8-15 car, Maj, min, chiffre).";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
if ($passMemb !== $passMemb2) {
    $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Sécurité Statut (Modo ne peut pas créer d'Admin)
if (!in_array($numStat, [2, 3], true)) {
    $_SESSION['error_message'] = "Statut invalide.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
if ((int)($_SESSION['numStat'] ?? 0) === 2) {
    $numStat = 3;
}

/* ------------------------------------------------------
   3) Insertion
------------------------------------------------------ */
$dtCreaMemb = date("Y-m-d H:i:s");
$accordMemb = 1; 

// Hashage
$hashedPassMemb = password_hash($passMemb, PASSWORD_DEFAULT);

$ok = sql_insert(
    'membre',
    'nomMemb, prenomMemb, pseudoMemb, passMemb, eMailMemb, dtCreaMemb, numStat, accordMemb',
    "'$nomMemb', '$prenomMemb', '$pseudoMemb', '$hashedPassMemb', '$eMailMemb', '$dtCreaMemb', $numStat, $accordMemb"
);

if (!$ok) {
    $_SESSION['error_message'] = "Erreur SQL lors de l'insertion.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

header("Location: ../../views/backend/members/list.php");
exit;
?>