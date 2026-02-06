<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

/* =====================================
   1) VÉRIFICATION reCAPTCHA (Fortement recommandé pour une inscription publique)
   ==================================== */
// Si tu as mis le widget reCAPTCHA dans ton formulaire HTML, décommente ce bloc :
/*
if (!isset($_POST['g-recaptcha-response'])) {
    $_SESSION['error_message'] = "Captcha manquant.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
$token = $_POST['g-recaptcha-response'];
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = ['secret' => getenv('RECAPTCHA_SECRET_KEY'), 'response' => $token];
$options = ['http' => ['header' => "Content-Type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($data)]];
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$response = json_decode($result);
if (!$response->success || $response->score < 0.5) {
    $_SESSION['error_message'] = "Échec du CAPTCHA (Score insuffisant).";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
*/

/* =====================================
   2) RÉCUPÉRATION DES DONNÉES
   ==================================== */
$nomMemb     = ctrlSaisies($_POST['nomMemb'] ?? '');
$prenomMemb  = ctrlSaisies($_POST['prenomMemb'] ?? '');
$pseudoMemb  = ctrlSaisies($_POST['pseudoMemb'] ?? '');
$rgpdConsent = $_POST['rgpd'] ?? ''; // Pas besoin de ctrlSaisies pour une checkbox/radio

// Pseudo : 6 à 70 caractères
if (!preg_match('/^.{6,70}$/', $pseudoMemb)) {
    $_SESSION['error_message'] = "Le pseudo doit contenir entre 6 et 70 caractères.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Vérification de l'accord RGPD
if ($rgpdConsent !== 'oui') {
    $_SESSION['error_message'] = "Vous devez accepter la conservation de vos données personnelles (RGPD) pour vous inscrire.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Vérifier si le pseudo existe déjà
// (Note : sql_select renvoie un tableau, donc s'il n'est pas vide, le pseudo existe)
$pseudoExist = sql_select('membre', 'pseudoMemb', "pseudoMemb = '$pseudoMemb'");
if (!empty($pseudoExist)) {
    $_SESSION['error_message'] = "Ce pseudo est déjà utilisé. Veuillez en choisir un autre.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Vérifier prénom et nom
if (empty($prenomMemb) || empty($nomMemb)) {
    $_SESSION['error_message'] = "Veuillez renseigner votre nom et prénom.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Vérifier emails
$eMailMemb  = ctrlSaisies($_POST['eMailMemb'] ?? '');
$eMailMemb2 = ctrlSaisies($_POST['eMailMemb2'] ?? '');

if (!filter_var($eMailMemb, FILTER_VALIDATE_EMAIL) || !filter_var($eMailMemb2, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Une ou plusieurs adresses email ne sont pas valides.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if ($eMailMemb !== $eMailMemb2) {
    $_SESSION['error_message'] = "Les deux adresses email ne correspondent pas.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Récupération des mots de passe (SANS ctrlSaisies pour éviter d'altérer les caractères spéciaux)
$passMemb  = (string)($_POST['passMemb'] ?? '');
$passMemb2 = (string)($_POST['passMemb2'] ?? '');

// Password : 8 à 15, 1 maj, 1 min, 1 chiffre
$passwordPattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,15}$/";

if (!preg_match($passwordPattern, $passMemb)) {
    $_SESSION['error_message'] = "Mot de passe invalide (8-15 car., 1 Maj, 1 min, 1 chiffre).";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if ($passMemb !== $passMemb2) {
    $_SESSION['error_message'] = "Les deux mots de passe ne correspondent pas.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

/* =====================================
   3) INSERTION EN BASE
   ==================================== */

// Statut (3 = membre classique)
$numStat = 3;
$dtCreaMemb = date("Y-m-d H:i:s");

// HASHAGE DU MOT DE PASSE (Activé !)
$hashedPassMemb = password_hash($passMemb, PASSWORD_DEFAULT);

// Insertion
sql_insert(
    'membre',
    'nomMemb, prenomMemb, pseudoMemb, passMemb, eMailMemb, dtCreaMemb, numStat',
    "'$nomMemb', '$prenomMemb', '$pseudoMemb', '$hashedPassMemb', '$eMailMemb', '$dtCreaMemb', '$numStat'"
);

// Succès + Redirection vers Login
$_SESSION['success_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
header("Location: " . ROOT_URL . "/views/backend/security/login.php");
exit;
?>