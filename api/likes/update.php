<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
startSession();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    setFlashMessage('error', 'Vous devez être connecté pour modifier un like.');
    header('Location: ' . ROOT_URL . '/views/backend/security/login.php');
    exit;
}

// Récupérer les données
$numArt = isset($_POST['numArt']) ? intval($_POST['numArt']) : 0;
$numMemb = $_SESSION['user_id'];
$likeA = isset($_POST['likeA']) ? intval($_POST['likeA']) : 0;
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : ROOT_URL . '/index.php';

// Validation
if ($numArt <= 0) {
    setFlashMessage('error', 'Article non spécifié.');
    header('Location: ' . $redirect);
    exit;
}

// Vérifier que le like existe
$existingLike = sql_select("LIKEART", "*", "numArt = $numArt AND numMemb = $numMemb");

if (empty($existingLike)) {
    setFlashMessage('error', 'Like introuvable.');
    header('Location: ' . $redirect);
    exit;
}

// Mise à jour
try {
    $result = sql_update('LIKEART', "likeA = $likeA", "numArt = $numArt AND numMemb = $numMemb");
    
    if ($result) {
        if ($likeA) {
            setFlashMessage('success', 'Vous avez liké cet article.');
        } else {
            setFlashMessage('success', 'Vous avez retiré votre like.');
        }
    }
} catch (Exception $e) {
    setFlashMessage('error', 'Erreur lors de la mise à jour du like.');
}

header('Location: ' . $redirect);
exit;
?>