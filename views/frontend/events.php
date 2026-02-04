<?php 
require_once '../../header.php';
sql_connect();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Événements</title>
    <head>
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Climate+Crisis:YEAR@1979&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="top"><img src="/../../src/images/oli.jpg" alt="oli" style="width:500px; height:auto; margin-right: 50px;margin-left:100px;">
        <div class="contenu-texte" style="max-width: 600px; margin: 50px;">
            <h1 class="titre">Les événements</h1>
            <p>Sorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc vulputate libero et velit interdum, ac aliquet odio mattis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur tempus urna at turpis condimentum lobortis. Ut commodo efficitur neque. Ut diam quam, semper iaculis condimentum ac, vestibulum eu nisl.</p>
        </div>
    </div>
</body>
<title>Bandeau avec texte</title>
    <div class="bandeau">
        XX ÉVÉNEMENTS XX
    </div>
<style>
    /* Style du bandeau */
    .bandeau {
      background-color: #7999D9; /* couleur de fond */
      padding: 55px;              /* espace autour du texte */
      color: white;              /* couleur du texte */
      text-align: center;        /* centrer le texte */
      font-size: 24px;           /* taille du texte */
      font-weight: bold;         /* texte en gras */
    height: 110px;

    display: flex;                /* active flexbox */
    justify-content: center;      /* centre horizontalement */
    align-items: center;
    }
    .top{
        display: flex;
        align-items: flex-start;
        gap: 20px;
    }
    .img-gallerie{
        max-width: 50%;
        height: auto;
        margin-bottom: 20px;
    }
    .gallerie{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 15px;
    }
    .titre {
        color: #1d1d1dff;
        display: inline-block;          /* important pour que transform fonctionne */
    transition: transform 0.25s ease;
    }
    .titre-lien:hover .titre {
    transform: rotate(-3deg) scale(1.05);
    color: #FB79EF;
    }
    .titre{
        margin-top: 50px;
    }
    .image-lien{
        display: inline-block;          /* important pour que transform fonctionne */
    transition: transform 0.25s ease;
    }
    .image-lien:hover{
    transform: scale(1.01);
    }
    body{
        background-image: url('../../src/images/background.png');
    }
    .gallerie-text {
    text-align: center;   /* centre titre + date */
    width: 100%;          /* évite que le texte soit serré */
    }  
    .image-lien{
    display: flex;
    justify-content: center;  /* centre l’image */
    width: 100%;
    }
    .img-gallerie {
    display: block;
    margin: 0 auto;
    }
/* gestion des polices */
    h1{
        font-family: 'luckiest guy', cursive;
        font-size: 70px;
        font-weight: "regular";
        color: #212121;
    }
    p{
    font-size: 22px;
    font-family: montserrat, sans-serif;
    font-weight: medium;
    color: #212121;
    }
    h2{
    font-family: 'montserrat', sans-serif;
    font-size: 40px;
    font-weight: bold;
    color: #212121;
    }
    h4{
    font-family: 'montserrat', sans-serif;
    font-size: 16px;
    font-weight: regular;
    color: #6f6f6fff;
    }
    .retour{
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #7999D9;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
        font-family: 'montserrat', sans-serif;
    }
</style>
<body>
    <div class="test">
        <?php
            $evenements = sql_select("ARTICLE", "*", "numThem = 1"); // Supposons que les événements ont numThem = 1
            foreach ($evenements as $evenement) : ?>
        <div class="gallerie">
            <div class="gallerie-text">
                <a href="articles/article1.php" class="titre-lien"><h2 class="titre"><?php echo $evenement['libTitrArt']; ?></h2></a>
                <p><?php echo $evenement['libChapoArt']; ?></p>
                <h4><?php echo $evenement['dtCreaArt']; ?></h4>
            </div>
            <a href="articles/article1.php" class="image-lien"><img src="../../src/uploads/<?php echo $evenement['urlPhotArt']; ?>" class="img-gallerie" alt="article"></a>
        </div>

    <?php endforeach; ?>
    </div>
    <a href="#" class="retour">retour en haut</a>
    <p></p>
</body>
</html>
