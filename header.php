<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
        <script src="https://www.google.com/recaptcha/api.js"></script>




    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">




    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="src/images/article1.png" />




    <!-- CSS HEADER -->
    <style>
        body {
            margin: 0;
        }




        .header {
            background: linear-gradient(90deg, #ff77d9, #9bbcff);
            padding: 12px 30px;
        }




        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
:root {
    --blue: #9bbcff;
}
/* MENU */
.main-nav a {
    position: relative;
    transition: color 0.25s ease;
}




/* Texte au hover */
.main-nav a:hover {
    color: var(--blue);
}




/* Soulignement animé bleu */
.main-nav a::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 0;
    height: 2px;
    background: var(--blue);
    transition: width 0.3s ease;
}




.main-nav a:hover::after {
    width: 100%;
}




/* Icône INSOLITE */
.insolite i {
    transition: transform 0.3s ease, color 0.3s ease;
}




.insolite:hover i {
    transform: scale(1.15);
    color: var(--blue);
}




        /* LOGO */
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }




        .logo img {
            width: 45px;
        }




        .logo span {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 2px;
            color: #000;
        }
.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
}




.logo span {
    color: #000;
}
.logo:hover {
    opacity: 0.85;
}




        /* MENU */
        .main-nav {
            display: flex;
            gap: 40px;
        }




        .main-nav a {
            text-decoration: none;
            font-weight: 600;
            color: #000;
        }




        .main-nav a:hover {
            opacity: 0.7;
        }




        /* ICON */
      .header-icon img {
    width: 38px;
    height: auto;
    display: block;
}
.header-icon img:hover {
    opacity: 0.7;
    cursor: pointer;
}








        /* LOGIN */
        .auth a {
            text-decoration: none;
            font-weight: 600;
            color: #000;
        }
        .login-btn {
    text-decoration: none;
    font-weight: 600;
    color: #000;
    transition: color 0.25s ease;
}
















        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
            }




            .main-nav {
                gap: 20px;
            }
        }
    </style>
</head>




<?php
require_once 'config.php';






/* AJOUT : on vérifie si l'utilisateur a déjà donné son consentement cookies
   - null => pas de réponse encore => on devra bloquer la navigation avec la pop-up
   - "accept" ou "reject" => il a répondu => pas de pop-up */
$cookieConsent = $_COOKIE['cookie_consent'] ?? null;
?>




<body>


<?php if ($cookieConsent === null): ?>
  <!-- AJOUT : overlay bloquant (l’utilisateur doit choisir avant de continuer) -->
  <style>
    /* AJOUT : fond sombre qui bloque les clics sur le site */
    #cookie-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.55);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 999999;
    }
    /* AJOUT : boîte centrale */
    #cookie-box {
      background: #fff;
      max-width: 560px;
      width: calc(100% - 24px);
      padding: 18px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,.25);
    }
  </style>


  <div id="cookie-overlay" role="dialog" aria-modal="true" aria-labelledby="cookie-title">
    <div id="cookie-box" class="bg-white">
      <h5 id="cookie-title" class="mb-2">Gestion des cookies</h5>


      <p class="mb-3">
        Nous utilisons des cookies nécessaires au fonctionnement du site.
        Avec votre accord, nous pouvons aussi utiliser des cookies optionnels (mesure d’audience, etc.).
        Vous devez choisir avant de continuer.
      </p>


      <div class="d-flex gap-2 justify-content-end">
        <button type="button" class="btn btn-outline-secondary" id="cookie-refuse">
          Refuser
        </button>
        <button type="button" class="btn btn-primary" id="cookie-accept">
          Accepter
        </button>
      </div>
    </div>
  </div>


  <script>
    /* AJOUT : bloque le scroll tant que l'utilisateur n'a pas choisi */
    document.documentElement.style.overflow = 'hidden';


    /* AJOUT : crée le cookie de consentement puis recharge la page
       - Path=/ => valable sur tout le site
       - Max-Age => durée de conservation du choix (ici 6 mois)
       - SameSite=Lax => bonne pratique de base */
    function setCookieConsent(value) {
      const maxAge = 60 * 60 * 24 * 180; // 180 jours
      document.cookie = "cookie_consent=" + value + "; Max-Age=" + maxAge + "; Path=/; SameSite=Lax";


      // AJOUT : on réactive le scroll et on recharge pour appliquer le choix
      document.documentElement.style.overflow = '';
      window.location.reload();
    }


    document.getElementById('cookie-accept').addEventListener('click', function() {
      setCookieConsent('accept');
    });


    document.getElementById('cookie-refuse').addEventListener('click', function() {
      setCookieConsent('reject');
    });
  </script>
<?php endif; ?>




<header class="header">
    <div class="header-container">




        <!-- Logo -->
      <a href="/index.php" class="logo">
    <img src="/src/images/logo-swag.png" alt="Logo SWAG">
    <span>SWAG</span>
</a>




        <!-- Menu -->
        <nav class="main-nav">
    <a href="/views/frontend/events.php">ÉVÉNEMENTS</a>
    <a href="/views/frontend/original.php">INSOLITE</a>
    <a href="/views/frontend/actors.php">ACTEURS</a>


    <?php if (isset($_SESSION['numStat']) && $_SESSION['numStat'] !== 3): ?>
        <!-- AJOUT : affiche ADMIN seulement si connecté ET pas membre (numStat=3) -->
        <a href="/views/backend/dashboard.php">ADMIN</a>
    <?php endif; ?>
</nav>






        <!-- Icône -->
        <div class="header-icon">
    <a href="/BLOGART26" class="header-icon">
    <img src="/src/images/icon-star.png" alt="Recherche">
</a>




</div>








        <!-- Login -->
       <a href="/views/backend/security/login.php" class="login-btn">
    LOGIN
</a>








    </div>
</header>
</body>
</html>