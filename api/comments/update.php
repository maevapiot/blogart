<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

$numCom   = (int)($_POST['numCom'] ?? 0);
$attModOK = (int)($_POST['attModOK'] ?? 0);
$notif    = ctrlSaisies($_POST['notifComKOAff'] ?? '');

// vérifs
if ($numCom <= 0) {
    $_SESSION['error_message'] = "Commentaire invalide.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if ($attModOK !== 0 && $attModOK !== 1) {
    $_SESSION['error_message'] = "Valeur de validation incorrecte.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// vérifier existence commentaire
if (!sql_select('comment', 'numCom', "numCom = '$numCom'")) {
    $_SESSION['error_message'] = "Commentaire introuvable.";
    header('Location: ../../views/backend/comments/list.php');
    exit;
}

// Si validé : notif = NULL sinon notif = texte (ou NULL si vide)
if ($attModOK === 1) {
    $notifSQL = "NULL";
} else {
    $notifSQL = (!empty($notif)) ? "'$notif'" : "NULL";
}

// update
sql_update(
    'comment',
    "attModOK = '$attModOK', notifComKOAff = $notifSQL, dtModCom = NOW()",
    "numCom = '$numCom'"
);

header('Location: ../../views/backend/comments/list.php');
exit;


