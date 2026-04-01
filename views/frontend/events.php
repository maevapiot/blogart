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
        <span id="scroll-text"><p style="font-family: 'montserrat'; color: white; font-size: 40px">XX ÉVÉNEMENTS XX</p></span>
    </div>
<style>
    /* Style du bandeau */
    .bandeau {
    background-color: #7999D9;
    padding: 55px;
    color: white;
    text-align: left;
    font-size: 24px;
    font-weight: bold;
    height: 110px;
    overflow: hidden;
    position: relative;
    display: flex; 
    align-items: center;
    }
    #scroll-text {
    display: inline-block; 
    white-space: nowrap;    
    will-change: transform; 
    padding-left: 20px;
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
    font-weight: bold;
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
</style>
<body>
    <div class="container-général" style="padding: 20px;">
        <?php
            $evenements = sql_select("article", "*", "numThem = 1"); // Supposons que les événements ont numThem = 1
            foreach ($evenements as $evenement) : ?>
                <a href="/views/frontend/articles/article1.php?idArt=<?php echo $evenement['numArt']; ?>" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 mt-3" style="padding: 30px; border-radius: 25px; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border: none;">
                        <div class="row g-3">
                            <div class="col-md-4" style="display: flex; justify-content: center; align-items: center;">
                                <img src="../../src/uploads/<?php echo $evenement['urlPhotArt']; ?>" class="img-fluid rounded-start" alt="photo de l'article">
                            </div>
                                <div class="col-md-8">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo $evenement['libTitrArt']; ?></h2>
                        <p class="card-text" style="color: #0056b3; font-size: 1.1rem;"><?php echo $evenement['libChapoArt']; ?></p>
                        <p class="card-text"><small class="text-body-secondary"><?php echo $evenement['dtCreaArt']; ?></small></p>
                    </div>
                    </div>
                </div>
</div>
    <?php endforeach; ?>
    </div>
    <p></p>
</body>
<script>
    window.addEventListener('scroll', function() {
        // On sélectionne le texte
        var texte = document.getElementById('scroll-text');
        
        // On récupère la position du scroll vertical de la page
        var scrollPosition = window.scrollY;
        
        // On calcule le déplacement. 
        // 0.5 est la vitesse. Augmente ce chiffre pour aller plus vite.
        // Mets un signe moins (-0.5) pour aller vers la gauche.
        var deplacement = scrollPosition * 0.8; 
        
        // On applique le mouvement horizontal (translateX)
        texte.style.transform = "translateX(" + deplacement + "px)";
    });
</script>
</html>
<?php require_once '../../footer.php'; ?>