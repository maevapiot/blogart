<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';

/* =====================================
    1) VÉRIFICATION reCAPTCHA v3
    - Vérifie que le token reCAPTCHA est présent
    - Envoie le token à Google pour validation
    - Vérifie le score de confiance (>= 0.5 = humain)
   ==================================== */

// Vérifier que le token reCAPTCHA est présent
if (!isset($_POST['g-recaptcha-response'])) {
    $_SESSION['error_message'] = "Captcha manquant.";
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
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
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit;
}

/* =====================================
   2) VÉRIFICATION IDENTIFIANTS
===================================== */
$pseudoMemb = ctrlSaisies($_POST['pseudoMemb'] ?? '');
$passMemb   = ($_POST['passMemb'] ?? '');


// 3) SELECT : vérifier existence membre
$res = sql_select('membre', '*', "pseudoMemb = '$pseudoMemb'");


if (!$res || count($res) === 0) {
    $_SESSION['error_message'] = "Pseudo ou mot de passe incorrect.";
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit;
}


$membre  = $res[0];
$passBDD = $membre['passMemb'] ?? '';


// 4) Vérifier le mot de passe
$ok = false;


// Cas 1 : mot de passe hashé en BDD
if (password_verify($passMemb, $passBDD)) {
    $ok = true;
}


// Cas 2 : mot de passe en clair en BDD (temporaire)
if (!$ok && $passMemb === $passBDD) {
    $ok = true;
}


// Si aucun des deux ne correspond
if (!$ok) {
    $_SESSION['error_message'] = "Pseudo ou mot de passe incorrect.";
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit;
}


/* AJOUT : sécurité session (niveau intermédiaire)
   Régénère l'ID de session après login réussi pour éviter la "fixation de session".
   -> Ne change pas ton fonctionnement (toujours connecté), mais rend la session plus sûre. */
session_regenerate_id(true);


/* AJOUT : info utile/pédagogique
   Permet de savoir quand la session a été créée (preuve que ça persiste entre pages). */
$_SESSION['logged_at'] = time();


// 5) Session
$_SESSION['numMemb']    = (int)$membre['numMemb'];
$_SESSION['pseudoMemb'] = $membre['pseudoMemb'];
$_SESSION['numStat']    = (int)$membre['numStat'];


// 6) Redirection selon statut
// 1 admin -> back / 2 modo -> back / 3 membre -> front
if ($_SESSION['numStat'] === 1 || $_SESSION['numStat'] === 2) {
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit;
}


header("Location: " . ROOT_URL . "/index.php");
exit;
