<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';

// Check droits : Admin (1) ou Modo (2)
if (!isset($_SESSION['numStat']) || (int)$_SESSION['numStat'] > 2) {
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit();
}

/**
 * Redirection avec message d'erreur
 */
function redirectBack(string $message): void
{
    $_SESSION['error_message'] = $message;
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? ROOT_URL . '/views/backend/members/create.php'));
    exit;
}

/* ------------------------------------------------------
   1) Saisies & Validations
------------------------------------------------------ */
$nomMemb    = ctrlSaisies($_POST['nomMemb'] ?? '');
$prenomMemb = ctrlSaisies($_POST['prenomMemb'] ?? '');
$pseudoMemb = ctrlSaisies($_POST['pseudoMemb'] ?? '');
$eMailMemb  = ctrlSaisies($_POST['eMailMemb'] ?? '');
$eMailMemb2 = ctrlSaisies($_POST['eMailMemb2'] ?? '');
$passMemb   = $_POST['passMemb'] ?? '';
$passMemb2  = $_POST['passMemb2'] ?? '';
$numStat    = (int)($_POST['numStat'] ?? 3);

// Pseudo (6-70 chars)
if (!preg_match('/^.{6,70}$/', $pseudoMemb)) {
    redirectBack("Le pseudo doit contenir entre 6 et 70 caractères.");
}

// Unicité Pseudo
if (sql_select('membre', 'pseudoMemb', "pseudoMemb = '$pseudoMemb'")) {
    redirectBack("Ce pseudo est déjà utilisé.");
}

// Champs requis
if (empty($prenomMemb) || empty($nomMemb)) {
    redirectBack("Nom et prénom obligatoires.");
}

// Emails
if (!filter_var($eMailMemb, FILTER_VALIDATE_EMAIL) || !filter_var($eMailMemb2, FILTER_VALIDATE_EMAIL)) {
    redirectBack("Email invalide.");
}

if ($eMailMemb !== $eMailMemb2) {
    redirectBack("Les emails ne correspondent pas.");
}

// Mots de passe
$passwordPattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,15}$/";

if (!preg_match($passwordPattern, $passMemb) || !preg_match($passwordPattern, $passMemb2)) {
    redirectBack("Mot de passe non conforme (8-15 car, majuscule, minuscule, chiffre).");
}

if ($passMemb !== $passMemb2) {
    redirectBack("Les mots de passe ne correspondent pas.");
}

// Sécurité Statut (Modo ne peut pas créer d'Admin)
if (!in_array($numStat, [2, 3], true)) {
    redirectBack("Statut invalide.");
}

if ((int)($_SESSION['numStat'] ?? 0) === 2) {
    $numStat = 3;
}

/* ------------------------------------------------------
   2) Insertion
------------------------------------------------------ */
$dtCreaMemb = date("Y-m-d H:i:s");
$accordMemb = 1;

// Hashage du mot de passe
$hashedPassMemb = password_hash($passMemb, PASSWORD_DEFAULT);

$ok = sql_insert(
    'membre',
    'nomMemb, prenomMemb, pseudoMemb, passMemb, eMailMemb, dtCreaMemb, numStat, accordMemb',
    "'$nomMemb', '$prenomMemb', '$pseudoMemb', '$hashedPassMemb', '$eMailMemb', '$dtCreaMemb', $numStat, $accordMemb"
);

if (!$ok) {
    redirectBack("Erreur SQL lors de l'insertion.");
}

header("Location: " . ROOT_URL . "/views/backend/members/list.php");
exit;
?>