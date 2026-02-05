<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

// Récupérer + sécuriser
$libCom  = ctrlSaisies($_POST['libCom'] ?? '');
$numArt  = (int)($_POST['numArt'] ?? 0);
$numMemb = (int)($_POST['numMemb'] ?? 0);

// Vérifs
if (empty($libCom)) {
    $_SESSION['error_message'] = "Veuillez saisir un commentaire.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if ($numArt <= 0) {
    $_SESSION['error_message'] = "Article invalide.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if ($numMemb <= 0) {
    $_SESSION['error_message'] = "Membre invalide.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// (Optionnel mais propre) vérifier que l'article existe
if (!sql_select('article', 'numArt', "numArt = '$numArt'")) {
    $_SESSION['error_message'] = "Cet article n'existe pas.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// (Optionnel) vérifier que le membre existe
if (!sql_select('membre', 'numMemb', "numMemb = '$numMemb'")) {
    $_SESSION['error_message'] = "Ce membre n'existe pas.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Insertion (en attente de modération)
sql_insert(
    'comment',
    'dtCreaCom, libCom, attModOK, delLogiq, numArt, numMemb',
    "NOW(), '$libCom', 0, 0, '$numArt', '$numMemb'"
);

// Redirection back
header('Location: ../../views/backend/comments/list.php');
exit;

