<?php


session_start();


require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';


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