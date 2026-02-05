<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

$numCom = (int)($_GET['numCom'] ?? 0);

if ($numCom <= 0) {
    $_SESSION['error_message'] = "Commentaire invalide.";
    header('Location: ../../views/backend/comments/list.php');
    exit;
}

// vérifier existence
if (!sql_select('comment', 'numCom', "numCom = '$numCom'")) {
    $_SESSION['error_message'] = "Commentaire introuvable.";
    header('Location: ../../views/backend/comments/list.php');
    exit;
}

// suppression logique
sql_update(
    'comment',
    "delLogiq = 1, dtDelLogCom = NOW()",
    "numCom = '$numCom'"
);

header('Location: ../../views/backend/comments/list.php');
exit;

