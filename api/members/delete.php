<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';



$userStat = (int)($_SESSION['numStat'] ?? 0);
if (!isset($_SESSION['pseudoMemb']) || !in_array($userStat, [1, 2], true)) {
    $_SESSION['error_message'] = "Accès refusé : veuillez vous connecter avec un compte admin/modérateur.";
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit();
}

$numMemb = isset($_POST['numMemb']) ? (int)$_POST['numMemb'] : 0;

if (!isset($_POST['g-recaptcha-response'])) {
    $_SESSION['error_message'] = "Captcha manquant.";
   
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
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
    $_SESSION['error_message'] = "Échec du CAPTCHA (Score insuffisant).";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit;
}


if ($numMemb <= 0) {
    $_SESSION['error_message'] = "ID membre invalide.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}

// Vérifier existence + statut cible
$res = sql_select('membre', 'numMemb, numStat', "numMemb = $numMemb");
if (!$res || !isset($res[0]['numMemb'])) {
    $_SESSION['error_message'] = "Membre introuvable.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}

$targetStat = (int)$res[0]['numStat'];

// Règle 1 : Personne ne touche à un Admin (1)
if ($targetStat === 1) {
    $_SESSION['error_message'] = "Suppression interdite : le compte administrateur est protégé.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}

// Règle 2 : Un Modérateur (2) ne touche pas à un autre Modérateur (2)
if ($userStat === 2 && $targetStat === 2) {
    $_SESSION['error_message'] = "Suppression interdite : un modérateur ne peut pas supprimer un collègue modérateur.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}

// Règle 3 : Un Modérateur (2) ne touche qu'aux Membres (3)
if ($userStat === 2 && $targetStat !== 3) {
    $_SESSION['error_message'] = "Suppression interdite : droits insuffisants.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}



sql_delete('comment', "numMemb = $numMemb");
sql_delete('likeart', "numMemb = $numMemb");


// On supprime le membre
$ok = sql_delete('membre', "numMemb = $numMemb");

if ($ok) {
    $_SESSION['success_message'] = "Membre et ses données associées supprimés avec succès.";
} else {
    $_SESSION['error_message'] = "Erreur SQL : Impossible de supprimer le membre (Vérifiez s'il a écrit des articles ?).";
}

header("Location: " . ROOT_URL . "/views/backend/members/list.php");
exit();
?>