<?php
session_start();


require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';


// Récupérer des données du formulaire
$nomMemb     = ctrlSaisies($_POST['nomMemb'] ?? '');
$prenomMemb  = ctrlSaisies($_POST['prenomMemb'] ?? '');
$pseudoMemb  = ctrlSaisies($_POST['pseudoMemb'] ?? '');
$rgpdConsent = ctrlSaisies($_POST['rgpd'] ?? '');


// 1) Pseudo : 6 à 70 caractères
if (!preg_match('/^.{6,70}$/', $pseudoMemb)) {
    $_SESSION['error_message'] = "Le pseudo doit contenir entre 6 et 70 caractères.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


// Vérification de l'accord RGPD
if (!isset($_POST['rgpd']) || $_POST['rgpd'] !== 'oui') {
    $_SESSION['error_message'] = "Vous devez accepter la conservation de vos données personnelles (RGPD) pour vous inscrire.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


// Vérifier si le pseudo existe déjà en base de données
if (sql_select('membre', 'pseudoMemb', "pseudoMemb = '$pseudoMemb'")) {
    $_SESSION['error_message'] = "Ce pseudo est déjà utilisé. Veuillez en choisir un autre.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


// Vérifier qu'il y ai un prénom
if (empty($prenomMemb)) {
    $_SESSION['error_message'] = "Veuillez renseigner un prénom.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


// Vérifier qu'il y ai un nom
if (empty($nomMemb)) {
    $_SESSION['error_message'] = "Veuillez renseigner un nom.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


// Vérifier la validité des 2 emails
$eMailMemb  = ctrlSaisies($_POST['eMailMemb'] ?? '');
$eMailMemb2 = ctrlSaisies($_POST['eMailMemb2'] ?? '');


if (!filter_var($eMailMemb, FILTER_VALIDATE_EMAIL) || !filter_var($eMailMemb2, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Une ou plusieurs adresses email ne sont pas valides.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


// Vérifier qu'ils sont identiques
if ($eMailMemb !== $eMailMemb2) {
    $_SESSION['error_message'] = "Les deux adresses email ne correspondent pas.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


// Récupération des deux mots de passe
$passMemb  = ctrlSaisies($_POST['passMemb'] ?? '');
$passMemb2 = ctrlSaisies($_POST['passMemb2'] ?? '');


// 2) Password : 8 à 15, 1 maj, 1 min, 1 chiffre (caractères spéciaux acceptés)
$passwordPattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,15}$/";


// Vérifier la validité des DEUX passwords
if (!preg_match($passwordPattern, $passMemb) || !preg_match($passwordPattern, $passMemb2)) {
    $_SESSION['error_message'] = "Mot de passe invalide (8-15 caractères, 1 majuscule, 1 minuscule, 1 chiffre).";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


// Vérifier qu'ils sont identiques
if ($passMemb !== $passMemb2) {
    $_SESSION['error_message'] = "Les deux mots de passe ne correspondent pas.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


// Statut (signup : membre forcé)
$numStat = 3;


$dtCreaMemb = date("Y-m-d H:i:s");


// Hashage du mot de passe (à activer quand tu es prêt)
// $hashedPassMemb = password_hash($passMemb, PASSWORD_DEFAULT);
$hashedPassMemb = $passMemb; // provisoire : mot de passe NON crypté


// Insertion en base de données
sql_insert(
    'membre',
    'nomMemb, prenomMemb, pseudoMemb, passMemb, eMailMemb, dtCreaMemb, numStat',
    "'$nomMemb', '$prenomMemb', '$pseudoMemb', '$hashedPassMemb', '$eMailMemb', '$dtCreaMemb', '$numStat'"
);


// Redirection vers la page de connexion
header("Location: " . ROOT_URL . "/views/backend/security/login.php");
exit;
