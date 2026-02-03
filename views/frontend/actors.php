<?php 
require_once '../../header.php';
sql_connect();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Récupérer les articles "Acteurs" (numThem = 2)
$article = sql_select("ARTICLE", "*", "numThem = 2");
?>

<style>
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
    /* Optionnel : fond papier froissé si tu l'as */
    /* body { background-image: url('chemin/vers/ton/papier.jpg'); } */
</style>

<body>
    <main class="container py-5">
        
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title-graphic">LES ACTEURS</h2>
            </div>
        </div>

        <?php foreach($article as $articles) { if (isset($articles)) { ?>
            
            <div class="row align-items-center mb-5"> <div class="col-lg-7">
                    <a href="/views/frontend/articles/article.php?id=<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>&numArt=<?php echo $articles['numArt']; ?>&like=0">
                        <img src="<?php echo ROOT_URL . '/src/uploads/' . htmlspecialchars($articles['urlPhotArt']); ?>" 
                             class="img-fluid w-100" 
                             alt="<?php echo htmlspecialchars($articles['libTitrArt']); ?>" 
                             style="object-fit: cover; max-height: 500px;"> </a>
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

    </main>
</body>

<?php require_once '../../footer.php'; ?>