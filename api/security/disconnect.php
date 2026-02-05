<?php
// Charge la configuration (définit ROOT_URL)
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Démarre la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vide toutes les variables de session
session_unset();

// Détruit la session côté serveur
session_destroy();

// Redirection après déconnexion
header("Location: " . ROOT_URL . "/index.php");
exit;

