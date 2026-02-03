<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

$numStat = ($_POST['numStat']);
$libStat = ($_POST['libStat']);
sql_update('STATUT', "libStat = '$libStat'", "numStat = $numStat");


header('Location: ../../views/backend/statuts/list.php');
