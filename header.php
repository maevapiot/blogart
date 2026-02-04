<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   


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
?>


<body>


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
            <a href="#">INSOLITE</a>
            <a href="/views/frontend/actors.php">ACTEURS</a>
             <a href="#">A PROPOS</a>
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