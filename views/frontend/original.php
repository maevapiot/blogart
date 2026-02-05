<?php
require_once '../../header.php';
sql_connect();

// 1. CIBLE TON ARTICLE UNIQUE ICI
// Remplace 12 par le numArt de ton article sur Adrien Razza trouvé dans ta base
$idUniqueArticle = 14; 

$articleData = sql_select("ARTICLE", "*", "numArt = " . $idUniqueArticle);

if ($articleData && count($articleData) > 0) {
    $article = $articleData[0];
    $lienImage = '../../src/uploads/' . $article['urlPhotArt'];
?>

<style>
/* --- REPRISE EXACTE DE TA DA "ACTEURS" --- */
@import url('https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Montserrat:wght@300;400;500;600;700;800;900&display=swap');

body { 
    background-color: #f4f7f6; 
    font-family: 'Montserrat', sans-serif !important; 
}

/* Titre de la rubrique */
.titre-section-insolite { 
    font-family: 'Luckiest Guy', cursive; 
    font-size: 4.5rem; 
    text-transform: uppercase; 
    color: #1a1a1a; 
    margin-bottom: 5rem; 
    text-shadow: 2px 2px 0px #e0e0e0; 
    text-align: center;
}

/* Carte Premium - Même style que Acteurs */
.article-card-premium {
    background-color: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    margin-bottom: 5rem;
}

.article-image-premium { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    display: block; 
}

.content-padding { 
    padding: 3.5rem; 
}

/* Typographies Montserrat - Fidèles à ta sortie artistique */
.sous-titre-article-premium { 
    font-family: 'Montserrat', sans-serif; 
    font-weight: 900; 
    font-size: 2.5rem; 
    text-transform: uppercase; 
    color: #111; 
    line-height: 1.1; 
    margin-bottom: 1.5rem; 
}

.texte-chapo-premium { 
    font-family: 'Montserrat', sans-serif; 
    font-size: 1.1rem; 
    font-weight: 700; 
    color: #0056b3; 
    margin-bottom: 2rem; 
}

.texte-accroche-premium { 
    font-family: 'Montserrat', sans-serif; 
    font-size: 1.2rem; 
    font-style: italic; 
    font-weight: 500; 
    border-left: 6px solid #0056b3; 
    padding: 20px 30px; 
    background: #f8fbff; 
    margin: 30px 0;
}

.texte-paragraphe-premium { 
    font-family: 'Montserrat', sans-serif; 
    font-size: 1rem; 
    line-height: 1.8; 
    text-align: justify; 
    margin-bottom: 1.5rem; 
}

.texte-intertitre-premium { 
    font-family: 'Montserrat', sans-serif; 
    font-weight: 800; 
    font-size: 1.6rem; 
    margin-top: 2.5rem; 
    color: #111; 
}
</style>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="titre-section-insolite">INSOLITE</h1>
            </div>
        </div>

        <div class="row article-card-premium g-0 align-items-stretch">
            <div class="col-lg-5 p-0">
                <img src="<?php echo $lienImage; ?>" class="article-image-premium" alt="Image article">
            </div>

            <div class="col-lg-7">
                <div class="content-padding">
                    <h2 class="sous-titre-article-premium"><?php echo htmlspecialchars($article['libTitrArt']); ?></h2>
                    
                    <div class="texte-chapo-premium"><?php echo nl2br(htmlspecialchars($article['libChapoArt'])); ?></div>

                    <?php if(!empty($article['libAccrochArt'])) { ?>
                        <div class="texte-accroche-premium">“<?php echo nl2br(htmlspecialchars($article['libAccrochArt'])); ?>”</div>
                    <?php } ?>

                    <div class="texte-paragraphe-premium"><?php echo nl2br(htmlspecialchars($article['parag1Art'])); ?></div>

                    <?php if(!empty($article['libSsTitr1Art'])) { ?>
                        <h3 class="texte-intertitre-premium"><?php echo htmlspecialchars($article['libSsTitr1Art']); ?></h3>
                    <?php } ?>
                    <div class="texte-paragraphe-premium"><?php echo nl2br(htmlspecialchars($article['parag2Art'])); ?></div>

                    <div class="texte-paragraphe-premium fw-bold mt-4" style="color: #0056b3; font-weight:700;">
                        <?php echo nl2br(htmlspecialchars($article['libConclArt'])); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
} else {
    echo "<div class='container py-5 text-center'><h2>Article non trouvé.</h2><p>Vérifie l'ID dans le code (numArt).</p></div>";
}
require_once '../../footer.php'; 
?>