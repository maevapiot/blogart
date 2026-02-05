<?php




if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';


/**
 * Autoriser uniquement admin (1) ou modérateur (2)
 */
$userStat = (int)($_SESSION['numStat'] ?? 0);
if (!isset($_SESSION['pseudoMemb']) || !in_array($userStat, [1, 2], true)) {
    $_SESSION['error_message'] = "Accès refusé : veuillez vous connecter avec un compte admin/modérateur.";
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit();
}


// ID membre à supprimer (POST)
$numMemb = isset($_POST['numMemb']) ? (int)$_POST['numMemb'] : 0;
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


// Interdire suppression admin cible
if ($targetStat === 1) {
    $_SESSION['error_message'] = "Suppression interdite : le compte administrateur ne peut pas être supprimé.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}


/* AJOUT : message spécifique si un MODÉRATEUR (2) tente de supprimer un autre MODÉRATEUR (2)
   Pourquoi : tu veux un message plus clair que le message générique "membre simple". */
if ($userStat === 2 && $targetStat === 2) {
    $_SESSION['error_message'] = "Suppression interdite : un modérateur ne peut pas supprimer un autre modérateur.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}


// Si modo -> ne supprime que membres simples
if ($userStat === 2 && $targetStat !== 3) {
    $_SESSION['error_message'] = "Suppression interdite : un modérateur ne peut supprimer qu'un membre simple.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}


// Suppression dépendances puis membre
sql_delete('comment', "numMemb = $numMemb");
sql_delete('likeart', "numMemb = $numMemb");


$ok = sql_delete('membre', "numMemb = $numMemb");


if ($ok) {
    $_SESSION['success_message'] = "Membre supprimé avec succès.";
} else {
    $_SESSION['error_message'] = "Erreur : suppression échouée (contrainte SQL ?).";
}


header("Location: " . ROOT_URL . "/views/backend/members/list.php");
exit();