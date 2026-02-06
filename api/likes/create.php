<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo('Vous devez être connecté pour liker un article.');
    header('Location: ' . ROOT_URL . '/views/backend/security/login.php');
    exit;
}

// Récupérer les données (GET ou POST)
$numArt = isset($_GET['numArt']) ? intval($_GET['numArt']) : (isset($_POST['numArt']) ? intval($_POST['numArt']) : 0);
$numMemb = $_SESSION['user_id'];
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : (isset($_POST['redirect']) ? $_POST['redirect'] : ROOT_URL . '/index.php');

// Validation
if ($numArt <= 0) {
    echo('Article non spécifié.');
    header('Location: ' . $redirect);
    exit;
}

// Vérifier que l'article existe
$article = sql_select("ARTICLE", "*", "numArt = $numArt");
if (empty($article)) { // empty c'est pour vérifier si la variable est vide
    echo('Article introuvable.');
    header('Location: ' . $redirect); 
    exit;
}

// Vérifier si un like existe déjà
$existingLike = sql_select("LIKEART", "*", "numArt = $numArt AND numMemb = $numMemb"); 

if (!empty($existingLike)) { 
    // Si le like existe, le mettre à jour (toggle)
    $newLikeValue = $existingLike[0]['likeA'] ? 0 : 1; 
    
    try {
        $result = sql_update('LIKEART', "likeA = $newLikeValue", "numArt = $numArt AND numMemb = $numMemb");
        
        if ($result) {
            if ($newLikeValue) {
                setFlashMessage('success', 'Vous avez liké cet article.');
            } else {
                setFlashMessage('success', 'Vous avez retiré votre like.');
            }
        }
    } catch (Exception $e) {
        echo('Erreur lors de la mise à jour du like.');
    }
} else {
    // Créer un nouveau like
    try {
        $result = sql_insert('LIKEART', 'numMemb, numArt, likeA', "$numMemb, $numArt, 1");
        
        if ($result) {
            echo('Vous avez liké cet article.');
        } else {
            echo('Erreur lors du like.');
        }
    } catch (Exception $e) {
        echo('Erreur lors du like : ' . $e->getMessage());
    }
}

header('Location: ' . $redirect);
exit;
?>