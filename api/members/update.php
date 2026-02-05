<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';


/**
 * Autoriser uniquement admin (1) ou modérateur (2)
 * (sans check_access)
 */
$userStat = (int)($_SESSION['numStat'] ?? 0);
if (!isset($_SESSION['pseudoMemb']) || !in_array($userStat, [1,2], true)) {
    $_SESSION['error_message'] = "Accès refusé : veuillez vous connecter avec un compte admin/modérateur.";
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit();
}

/* =====================================
   1) VÉRIFICATION reCAPTCHA (CdC 2)
===================================== */
if (!isset($_POST['g-recaptcha-response'])) {
    $_SESSION['error_message'] = "Captcha manquant.";
    header("Location: " . ROOT_URL . "/views/backend/members/edit.php?numMemb=" . ($_POST['numMemb'] ?? 0));
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

/*
- score >= 0.5 → humain
- score < 0.5  → robot
*/
if (!$response->success || $response->score < 0.5) {
    $_SESSION['error_message'] = "Échec du CAPTCHA.";
    header("Location: " . ROOT_URL . "/views/backend/members/edit.php?numMemb=" . ($_POST['numMemb'] ?? 0));
    exit;
}

/* =====================================
   2) RÉCUPÉRATION DES DONNÉES
===================================== */
$numMemb    = (int)($_POST['numMemb'] ?? 0);
$prenomMemb = ctrlSaisies($_POST['prenomMemb'] ?? '');
$nomMemb    = ctrlSaisies($_POST['nomMemb'] ?? '');
$eMailMemb  = ctrlSaisies($_POST['eMailMemb'] ?? '');
$eMailMemb2 = ctrlSaisies($_POST['eMailMemb2'] ?? '');
$passMemb   = (string)($_POST['passMemb'] ?? '');
$passMemb2  = (string)($_POST['passMemb2'] ?? '');
$numStat    = (int)($_POST['numStat'] ?? 3);
$dtMajMemb  = date("Y-m-d H:i:s");


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


// Interdire modification du compte admin
if ($targetStat === 1) {
    $_SESSION['error_message'] = "Modification interdite : le compte administrateur ne peut pas être modifié ici.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}


// Si modérateur : ne modifie que membre simple
if ($userStat === 2 && $targetStat !== 3) {
    $_SESSION['error_message'] = "Modification interdite : un modérateur ne peut modifier qu'un membre simple.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}


// Validations
if ($prenomMemb === '') {
    $_SESSION['error_message'] = "Veuillez renseigner un prénom.";
    header("Location: " . ROOT_URL . "/views/backend/members/edit.php?numMemb=" . $numMemb);
    exit();
}


if ($nomMemb === '') {
    $_SESSION['error_message'] = "Veuillez renseigner un nom.";
    header("Location: " . ROOT_URL . "/views/backend/members/edit.php?numMemb=" . $numMemb);
    exit();
}


if (!filter_var($eMailMemb, FILTER_VALIDATE_EMAIL) || !filter_var($eMailMemb2, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Une ou plusieurs adresses email ne sont pas valides.";
    header("Location: " . ROOT_URL . "/views/backend/members/edit.php?numMemb=" . $numMemb);
    exit();
}


if ($eMailMemb !== $eMailMemb2) {
    $_SESSION['error_message'] = "Les deux adresses email ne correspondent pas.";
    header("Location: " . ROOT_URL . "/views/backend/members/edit.php?numMemb=" . $numMemb);
    exit();
}


// Statut : admin peut mettre 2 ou 3. modérateur ne change pas le statut.
if ($userStat === 1) {
    if (!in_array($numStat, [2,3], true)) {
        $_SESSION['error_message'] = "Statut invalide.";
        header("Location: " . ROOT_URL . "/views/backend/members/edit.php?numMemb=" . $numMemb);
        exit();
    }
} else {
    // modo : on force le statut actuel (pas de changement)
    $numStat = $targetStat;
}


// Password (optionnel)
$hashedPassMemb = null;
if ($passMemb !== '' || $passMemb2 !== '') {


    $passwordPattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,15}$/";


    if (!preg_match($passwordPattern, $passMemb) || !preg_match($passwordPattern, $passMemb2)) {
        $_SESSION['error_message'] = "Mot de passe non conforme (8-15 caractères, 1 majuscule, 1 minuscule, 1 chiffre).";
        header("Location: " . ROOT_URL . "/views/backend/members/edit.php?numMemb=" . $numMemb);
        exit();
    }


    if ($passMemb !== $passMemb2) {
        $_SESSION['error_message'] = "Les deux mots de passe ne correspondent pas.";
        header("Location: " . ROOT_URL . "/views/backend/members/edit.php?numMemb=" . $numMemb);
        exit();
    }


    $hashedPassMemb = password_hash($passMemb, PASSWORD_DEFAULT);
}


// Update (idéalement 1 seule requête, mais on garde ton style)
sql_update('membre', "nomMemb = '$nomMemb'", "numMemb = $numMemb");
sql_update('membre', "prenomMemb = '$prenomMemb'", "numMemb = $numMemb");
sql_update('membre', "eMailMemb = '$eMailMemb'", "numMemb = $numMemb");
sql_update('membre', "dtMajMemb = '$dtMajMemb'", "numMemb = $numMemb");
sql_update('membre', "numStat = $numStat", "numMemb = $numMemb");


if ($hashedPassMemb !== null) {
    sql_update('membre', "passMemb = '$hashedPassMemb'", "numMemb = $numMemb");
}


$_SESSION['success_message'] = "Membre mis à jour avec succès.";
header("Location: " . ROOT_URL . "/views/backend/members/list.php");
exit();