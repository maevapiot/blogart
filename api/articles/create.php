<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

// ON SÉCURISE LES ENTRÉES AVEC ADDSLASHES
$libTitrArt    = addslashes($_POST['libTitrArt']);
$libChapoArt   = addslashes($_POST['libChapoArt']);
$libAccrochArt = addslashes($_POST['libAccrochArt']);
$parag1Art     = addslashes($_POST['parag1Art']);
$libSsTitr1Art = addslashes($_POST['libSsTitr1Art']);
$parag2Art     = addslashes($_POST['parag2Art']);
$libSsTitr2Art = addslashes($_POST['libSsTitr2Art']);
$parag3Art     = addslashes($_POST['parag3Art']);
$libConclArt   = addslashes($_POST['libConclArt']);

$dtCreaArt = date("Y-m-d H:i:s");
$numThem   = $_POST['numThem'];

// --- GESTION DE L'IMAGE ---
if (!isset($_FILES["image"])) {
    die("Aucun fichier reçu");
}

$name     = $_FILES["image"]["name"];
$type     = $_FILES["image"]["type"];
$tmp_name = $_FILES["image"]["tmp_name"];
$error    = $_FILES["image"]["error"];
$size     = $_FILES["image"]["size"];

if ($error !== 0) {
    die("Erreur lors de l'upload");
}

$typesAutorises = ["image/png", "image/jpeg", "image/jpg", "image/gif"];
if (!in_array($type, $typesAutorises)) {
    die("Type de fichier non autorisé");
}

if ($size > 10000000) {
    die("Fichier trop volumineux");
}

$extension = pathinfo($name, PATHINFO_EXTENSION);
$nomImage  = uniqid() . "." . $extension; // Plus propre de garder juste l'ID unique

// J'ai rajouté le "/" manquant après uploads
$chemin = "../../src/uploads/" . $nomImage;

if (!move_uploaded_file($tmp_name, $chemin)) {
    die("Erreur déplacement fichier");
}

// --- INSERTION SQL ---
sql_insert(
   'ARTICLE',
   'libTitrArt, dtCreaArt, libChapoArt, libAccrochArt,
   parag1Art, libSsTitr1Art, parag2Art, libSsTitr2Art,
   parag3Art, libConclArt, numThem, urlPhotArt',
   "'$libTitrArt', '$dtCreaArt', '$libChapoArt', '$libAccrochArt',
   '$parag1Art', '$libSsTitr1Art', '$parag2Art', '$libSsTitr2Art',
   '$parag3Art', '$libConclArt', '$numThem', '$nomImage'"
);

$_SESSION['success'] = "Article créé ! ";

header('Location: ../../views/backend/articles/list.php');