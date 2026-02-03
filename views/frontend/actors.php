<?php
// On remonte à la racine pour charger les outils nécessaires
$root = $_SERVER['DOCUMENT_ROOT']; 
require_once $root . '/header.php'; // Charge le header et les fonctions
sql_connect(); // Connecte la base de données

// Code pour récupérer les acteurs (si ce n'est pas déjà fait plus bas)
$allActors = sql_select("THEMATIQUE", "*"); // Exemple, adapte selon ta table
?>
<?php 
// Pas besoin de refaire les require si ce fichier est inclus dans index.php
// On s'assure juste de récupérer les articles de la thématique "Acteurs" (numThem = 2 par exemple)
// Vérifie bien que le numéro correspond à celui dans ta BDD.
$article = sql_select("ARTICLE", "*", "numThem = 2");
?>

<style>
    /* Styles spécifiques pour cette section */
    @import url('https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Inter:wght@400;500;700;800;900&display=swap');
    
    .section-title-graphic {
        font-family: 'Luckiest Guy', cursive;
        font-size: 4rem;
        text-transform: uppercase;
        color: #000;
    }
    .actors-headline {
        font-family: 'Inter', sans-serif; 
        font-weight: 800; 
        font-size: 1.4rem; 
        text-transform: uppercase;
        line-height: 1.2;
        color: #000;
    }
    .actors-text {
        font-family: 'Inter', sans-serif; 
        font-size: 1.1rem; 
        color: #333;
        line-height: 1.5;
    }
</style>

<section class="actors-section py-5">
    <div class="container">
        
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title-graphic">LES ACTEURS</h2>
            </div>
        </div>

        <?php foreach($article as $articles) { if (isset($articles)) { ?>
            
            <div class="row align-items-center mb-5">
                
                <div class="col-lg-7">
                    <a href="/views/frontend/articles/article.php?id=<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>&numArt=<?php echo $articles['numArt']; ?>&like=0">
                        <img src="<?php echo ROOT_URL . '/src/uploads/' . htmlspecialchars($articles['urlPhotArt']); ?>" 
                             class="img-fluid w-100" 
                             alt="<?php echo htmlspecialchars($articles['libTitrArt']); ?>" 
                             style="object-fit: cover; border-radius: 5px;">
                    </a>
                </div>

                <div class="col-lg-5 ps-lg-5 mt-4 mt-lg-0">
                    
                    <h3 class="actors-headline">
                        <?php echo htmlspecialchars($articles['libTitrArt']); ?>
                    </h3>
                    
                    <p class="actors-text mt-3">
                        <?php echo htmlspecialchars($articles['libChapoArt']); ?>
                    </p>

                    <div class="mt-3">
                        <a href="/views/frontend/articles/article.php?id=<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>&numArt=<?php echo $articles['numArt']; ?>&like=0" 
                           class="text-dark fw-bold text-decoration-underline">
                           Lire l'interview &rarr;
                        </a>
                    </div>
                </div>

            </div>
            
        <?php }} ?>

    </div>
</section>
<?php include 'views/frontend/actors.php'; ?>