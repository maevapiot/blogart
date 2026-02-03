<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';


if (!isset($_POST['numThem']) || !is_numeric($_POST['numThem'])) {
    header('Location: ../../views/backend/thematiques/list.php');
    exit;
}


$numThem = (int) ctrlSaisies($_POST['numThem']);


// Vérifier si des articles utilisent cette thématique
$nbArticles = sql_select("ARTICLE", "COUNT(*) AS nb", "numThem = $numThem")[0]['nb'];


if ($nbArticles > 0) {
    header("Location: ../../views/backend/thematiques/delete.php?numThem=$numThem&error=linked");
    exit;
}
// Sinon suppression
sql_delete('THEMATIQUE', "numThem = $numThem");


header('Location: ../../views/backend/thematiques/list.php?success=deleted');
exit;


