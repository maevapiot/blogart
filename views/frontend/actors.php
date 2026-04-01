<?php
// ---------------------------------------------------------
// ÉTAPE 1 : Chargement & Debug
// ---------------------------------------------------------
// Supprime les 3 lignes ci-dessous une fois que ça marche
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../header.php';
sql_connect();

// ---------------------------------------------------------
// ÉTAPE 2 : Récupération des articles
// ---------------------------------------------------------
$idThematiqueActeurs = 2;
$tousLesArticles = sql_select("article", "*", "numThem = " . $idThematiqueActeurs);
?>

<style>
/* Import des polices */
@import url('https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Montserrat:wght@300;400;500;600;700;800;900&display=swap');

/* Reset global */
body { 
    background-color: #f4f7f6; 
    font-family: 'Montserrat', sans-serif !important; 
}

/* Titre section */
.titre-section-acteurs { 
    font-family: 'Luckiest Guy', cursive; 
    font-size: 4.5rem; 
    text-transform: uppercase; 
    color: #1a1a1a; 
    margin-bottom: 5rem; 
    text-shadow: 2px 2px 0px #e0e0e0; 
}

/* Carte Premium */
.article-card-premium {
    background-color: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    margin-bottom: 5rem;
    transition: transform 0.3s ease;
}

.image-container-full { width: 100%; height: auto; }
.article-image-premium { width: 100%; height: auto; display: block; border-right: 1px solid rgba(0,0,0,0.05); }
.content-padding { padding: 3.5rem; }

/* Typographies Montserrat */
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
            <div class="col-12 text-center">
                <h1 class="titre-section-acteurs">LES ACTEURS</h1>
            </div>
        </div>

        <?php
        if ($tousLesArticles) {
            foreach($tousLesArticles as $article) {
                // On saute l'article s'il contient "Nicolas" dans le titre (selon ton code d'origine)
                if (stripos($article['libTitrArt'], 'Nicolas') !== false) { continue; }
                
                $lienImage = '../../src/uploads/' . $article['urlPhotArt'];
        ?>
        <a href="/views/frontend/articles/article1.php?idArt=<?php echo $article['numArt']; ?>" style="text-decoration: none; color: inherit;">
        <div class="row article-card-premium g-0 align-items-center">
            <div class="col-lg-5 p-0">
                <div class="image-container-full">
                    <img src="<?php echo $lienImage; ?>" class="article-image-premium" alt="Image article">
                </div>
            </div>

            <div class="col-lg-7">
                <div class="content-padding">
                    <h2 class="sous-titre-article-premium"><?php echo htmlspecialchars($article['libTitrArt']); ?></h2>
                    
                    <div class="texte-chapo-premium"><?php echo nl2br(htmlspecialchars($article['libChapoArt'])); ?></div>
                    <p class="texte-paragraphe-premium"><?php echo date("d/m/Y", strtotime($article['dtCreaArt'])); ?></p>
                </div>
            </div>
        </div>
                    </a>

        <?php
            }
        } else {
            echo "<p class='text-center'>Aucun article trouvé dans cette thématique.</p>";
        }
        ?>
    </div>
</section>

<?php require_once '../../footer.php'; ?>