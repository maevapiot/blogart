<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numArt'])) {
    $numArt = ctrlSaisies($_POST['numArt']);
    
    // On récupère libChapoArt 
    $libTitrArt    = addslashes(ctrlSaisies($_POST['libTitrArt']));
    $libChapoArt   = addslashes(ctrlSaisies($_POST['libChapoArt'])); 
    $libAccrochArt = addslashes(ctrlSaisies($_POST['libAccrochArt']));
    $libSsTitr1Art = addslashes(ctrlSaisies($_POST['libSsTitr1Art']));
    $parag1Art     = addslashes(ctrlSaisies($_POST['parag1Art']));
    $libSsTitr2Art = addslashes(ctrlSaisies($_POST['libSsTitr2Art']));
    $parag2Art     = addslashes(ctrlSaisies($_POST['parag2Art']));
    $parag3Art     = addslashes(ctrlSaisies($_POST['parag3Art']));
    $libConclArt   = addslashes(ctrlSaisies($_POST['libConclArt']));
    $numThem       = ctrlSaisies($_POST['numThem']);

    $updateImageQuery = "";
    if (isset($_FILES['urlPhotArt']) && $_FILES['urlPhotArt']['error'] === 0) {
        $nomImage = $_FILES['urlPhotArt']['name'];
        move_uploaded_file($_FILES['urlPhotArt']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $nomImage);
        $updateImageQuery = ", urlPhotArt = '$nomImage'";
    }

    sql_update('ARTICLE', 
        "libTitrArt = '$libTitrArt', 
        libChapoArt = '$libChapoArt', 
        libAccrochArt = '$libAccrochArt', 
        libSsTitr1Art = '$libSsTitr1Art', 
        parag1Art = '$parag1Art', 
        libSsTitr2Art = '$libSsTitr2Art', 
        parag2Art = '$parag2Art', 
        parag3Art = '$parag3Art', 
        libConclArt = '$libConclArt', 
        numThem = '$numThem' 
        $updateImageQuery", 
        "numArt = '$numArt'"
    );

    sql_delete('MOTCLEARTICLE', "numArt = '$numArt'");
    if (isset($_POST['numMotCle'])) {
        foreach ($_POST['numMotCle'] as $idMot) {
            sql_insert('MOTCLEARTICLE', 'numArt, numMotCle', "'$numArt', '$idMot'");
        }
    }

    header('Location: ../../views/backend/articles/list.php');
    exit();
}