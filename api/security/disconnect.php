<?php
/* AJOUT : démarre la session pour pouvoir la détruire */
session_start();


/* AJOUT : vide toutes les variables de session (pseudo, numMemb, numStat, etc.) */
$_SESSION = [];


/* AJOUT : supprime le cookie de session côté navigateur (souvent PHPSESSID)
   -> le cookie identifie le visiteur et permet de retrouver la session côté serveur */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),        // nom du cookie de session
        '',                    // valeur vide
        time() - 42000,        // expiration passée = suppression
        $params['path'],
        $params['domain'] ?? '',
        $params['secure'],
        $params['httponly']
    );
}


/* AJOUT : détruit la session côté serveur */
session_destroy();


/* AJOUT : redirection après déconnexion (comportement attendu) */
header("Location: " . ROOT_URL . "/views/backend/security/login.php");
exit;