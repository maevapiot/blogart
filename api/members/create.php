<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

// Accès : admin (1) ou modérateur (2) seulement
if (!isset($_SESSION['numStat']) || (int)$_SESSION['numStat'] > 2) {
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit();
}

/*
   1) VÉRIFICATION reCAPTCHA v3
   Vérifie que le token reCAPTCHA est présent, Envoie le token à Google pour validation,
   Vérifie le score de confiance (>= 0.5 = humain)
  */

// Vérifier que le token reCAPTCHA est présent
if (!isset($_POST['g-recaptcha-response'])) {
    $_SESSION['error_message'] = "Captcha manquant.";
    header("Location: " . ROOT_URL . "/views/backend/members/create.php");
    exit;
}

// Récupérer le token du formulaire
$token = $_POST['g-recaptcha-response'];

// Préparer la requête de vérification auprès de Google
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = [
    'secret'   => getenv('RECAPTCHA_SECRET_KEY'),
    'response' => $token
];

// Options pour la requête HTTP POST
$options = [
    'http' => [
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    ]
];

// Envoyer la requête à Google et récupérer la réponse
$context  = stream_context_create($options);
$result   = file_get_contents($url, false, $context);
$response = json_decode($result);

// Vérifier le résultat de reCAPTCHA v3
// Score entre 0.0 (bot) et 1.0 (humain)
// Google recommande d'utiliser 0.5 comme seuil
if (!$response->success || $response->score < 0.5) {
    $_SESSION['error_message'] = "Échec du CAPTCHA - Score insuffisant.";
    header("Location: " . ROOT_URL . "/views/backend/members/create.php");
    exit;
}

/* 2) RÉCUPÉRATION DES DONNÉES */



// Récupérer des données du formulaire (protection notices)
$nomMemb    = ctrlSaisies($_POST['nomMemb'] ?? '');
$prenomMemb = ctrlSaisies($_POST['prenomMemb'] ?? '');
$pseudoMemb = ctrlSaisies($_POST['pseudoMemb'] ?? '');
$eMailMemb  = ctrlSaisies($_POST['eMailMemb'] ?? '');
$eMailMemb2 = ctrlSaisies($_POST['eMailMemb2'] ?? '');
$passMemb   = ctrlSaisies($_POST['passMemb'] ?? '');
$passMemb2  = ctrlSaisies($_POST['passMemb2'] ?? '');
$numStat    = (int) ($_POST['numStat'] ?? 3); // par défaut 3 si absent




// Vérifier le pseudo (6 à 70 caractères)
if (!preg_match('/^.{6,70}$/', $pseudoMemb)) {
    $_SESSION['error_message'] = "Le pseudo doit contenir entre 6 et 70 caractères.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}




// Vérifier si le pseudo existe déjà en base de données
if (sql_select('membre', 'pseudoMemb', "pseudoMemb = '$pseudoMemb'")) {
    $_SESSION['error_message'] = "Ce pseudo est déjà utilisé. Veuillez en choisir un autre.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}




// Vérifier prénom / nom
if (empty($prenomMemb)) {
    $_SESSION['error_message'] = "Veuillez renseigner un prénom.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
if (empty($nomMemb)) {
    $_SESSION['error_message'] = "Veuillez renseigner un nom.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}




// Vérifier la validité des emails et qu'ils correspondent
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




// Password rules (CDC) : 8-15, 1 maj, 1 min, 1 chiffre (spéciaux acceptés)
$passwordPattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,15}$/";
if (!preg_match($passwordPattern, $passMemb) || !preg_match($passwordPattern, $passMemb2)) {
    $_SESSION['error_message'] = "Mot de passe non conforme (8-15 caractères, 1 majuscule, 1 minuscule, 1 chiffre).";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
if ($passMemb !== $passMemb2) {
    $_SESSION['error_message'] = "Les deux mots de passe ne correspondent pas.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}




// Sécurisation statut : seuls 2 (modo) ou 3 (membre) sont acceptés
if (!in_array($numStat, [2, 3], true)) {
    $_SESSION['error_message'] = "Statut invalide.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
// Si le user connecté est modérateur, il ne peut créer que membre
if ((int)($_SESSION['numStat'] ?? 0) === 2) {
    $numStat = 3;
}




$dtCreaMemb = date("Y-m-d H:i:s");




// Hashage du mot de passe (fortement recommandé)
// $hashedPassMemb = password_hash($passMemb, PASSWORD_DEFAULT);
// Pour le développement temporaire, tu peux laisser en clair (mais change-le avant remise)
$hashedPassMemb = $passMemb;




$accordMemb = 1; // Back-office : on force l'accord (RGPD/consentement)




// ==== INSÉRER ET CONTRÔLER LE RETOUR ====
$ok = sql_insert(
    'membre',
    'nomMemb, prenomMemb, pseudoMemb, passMemb, eMailMemb, dtCreaMemb, numStat, accordMemb',
    "'$nomMemb', '$prenomMemb', '$pseudoMemb', '$hashedPassMemb', '$eMailMemb', '$dtCreaMemb', $numStat, $accordMemb"
);






if (!$ok) {
    $_SESSION['error_message'] = "Erreur : l'insertion a échoué (sql_insert a retourné false).";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}




header("Location: ../../views/backend/members/list.php");
exit;
