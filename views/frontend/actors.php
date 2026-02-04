<?php
// ---------------------------------------------------------
// ÉTAPE 1 : Chargement
// ---------------------------------------------------------
require_once '../../header.php'; 
sql_connect(); 

// ---------------------------------------------------------
// ÉTAPE 2 : Récupération des articles
// ---------------------------------------------------------
$idThematiqueActeurs = 2; 
$tousLesArticles = sql_select("ARTICLE", "*", "numThem = " . $idThematiqueActeurs);
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Inter:wght@400;500;700;900&display=swap');
    
    body { background-color: #f4f7f6; }

    /* Titre principal */
    .titre-section-acteurs { font-family: 'Luckiest Guy', cursive; font-size: 4.5rem; text-transform: uppercase; color: #1a1a1a; margin-bottom: 5rem; text-shadow: 2px 2px 0px #e0e0e0; }

    /* --- DESIGN CARTE PREMIUM --- */
    .article-card-premium {
        background-color: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        margin-bottom: 5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .article-card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.12);
    }

    /* --- CORRECTION IMAGE : PLUS DE ZOOM EXCESSIF --- */
    .image-container-full {
        /* On enlève height: 100% et min-height */
        width: 100%;
        height: auto;
    }

    .article-image-premium {
        width: 100%;
        height: auto; /* La hauteur s'adapte pour ne pas déformer */
        display: block; /* Supprime un petit espace fantôme sous l'image */
        border-right: 1px solid rgba(0,0,0,0.05);
    }
    /* ----------------------------------------------- */

    /* Zone de texte */
    .content-padding { padding: 3.5rem; }
    
    /* Typographie */
    .sous-titre-article-premium { font-family: 'Inter', sans-serif; font-weight: 900; font-size: 2.5rem; text-transform: uppercase; color: #111; line-height: 1.1; letter-spacing: -1px; margin-bottom: 1.5rem; }
    .texte-chapo-premium { font-family: 'Inter', sans-serif; font-size: 1.2rem; font-weight: 700; color: #0056b3; line-height: 1.5; margin-bottom: 2rem; }
    .texte-accroche-premium { font-family: 'Inter', sans-serif; font-size: 1.3rem; font-style: italic; font-weight: 500; color: #333; border-left: 6px solid #0056b3; padding: 20px 30px; margin: 30px 0; background: #f8fbff; border-radius: 0 12px 12px 0; }
    .texte-paragraphe-premium { font-family: 'Inter', sans-serif; font-size: 1.05rem; color: #444; line-height: 1.8; text-align: justify; margin-bottom: 1.5rem; }
    .texte-intertitre-premium { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 1.6rem; margin-top: 2.5rem; margin-bottom: 1rem; color: #111; }

    /* Mobile */
    @media (max-width: 991px) {
        .content-padding { padding: 2rem; }
        .sous-titre-article-premium { font-size: 2rem; }
        .article-image-premium { border-right: none; border-bottom: 1px solid rgba(0,0,0,0.05); }
    }
</style>

<section class="py-5">
    <div class="container">
        
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="titre-section-acteurs">LES ACTEURS</h1>
            </div>
        </div>

        <?php 
        if ($tousLesArticles) {
            foreach($tousLesArticles as $article) { 
                if (stripos($article['libTitrArt'], 'Nicolas') !== false) { continue; }
                $lienImage = '../../src/uploads/' . $article['urlPhotArt'];
        ?>
            
            <div class="row article-card-premium g-0 align-items-center">
                
                <div class="col-lg-5 p-0">
                    <div class="image-container-full">
                        <img src="<?php echo $lienImage; ?>" 
                                class="article-image-premium" 
                                alt="<?php echo htmlspecialchars($article['libTitrArt']); ?>">
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="content-padding">
                        <h2 class="sous-titre-article-premium">
                            <?php echo htmlspecialchars($article['libTitrArt']); ?>
                        </h2>
                        
                        <div class="texte-chapo-premium">
                            <?php echo nl2br(htmlspecialchars($article['libChapoArt'])); ?>
                        </div>

                        <?php if(!empty($article['libAccrochArt'])) { ?>
                            <div class="texte-accroche-premium">
                                “<?php echo nl2br(htmlspecialchars($article['libAccrochArt'])); ?>”
                            </div>
                        <?php } ?>

                        <div class="texte-paragraphe-premium">
                            <?php echo nl2br(htmlspecialchars($article['parag1Art'])); ?>
                        </div>

                        <?php if(!empty($article['libSsTitr1Art'])) { ?>
                            <h3 class="texte-intertitre-premium"><?php echo htmlspecialchars($article['libSsTitr1Art']); ?></h3>
                        <?php } ?>
                        
                        <div class="texte-paragraphe-premium">
                            <?php echo nl2br(htmlspecialchars($article['parag2Art'])); ?>
                        </div>

                        <?php if(!empty($article['libSsTitr2Art'])) { ?>
                            <h3 class="texte-intertitre-premium"><?php echo htmlspecialchars($article['libSsTitr2Art']); ?></h3>
                        <?php } ?>

                        <div class="texte-paragraphe-premium">
                            <?php echo nl2br(htmlspecialchars($article['parag3Art'])); ?>
                        </div>

                        <div class="texte-paragraphe-premium fw-bold mt-4" style="color: #0056b3;">
                            <?php echo nl2br(htmlspecialchars($article['libConclArt'])); ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php 
            } 
        } 
        ?>

    </div>
</section>

<?php require_once '../../footer.php'; ?>