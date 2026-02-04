<?php
require_once 'header.php';
sql_connect(); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

// Récupération des 3 derniers articles
// Utilisation de 1=1 pour assurer la compatibilité avec ta fonction sql_select
$latestArticles = sql_select("ARTICLE", "*", "1=1 ORDER BY dtCreaArt DESC LIMIT 3");
?>

<div class="hero-container">
    <img src="src/uploads/1F201C21-D9E0-487A-A48A-F553E8FCD741_1_105_c.jpeg" class="hero-img" alt="Skate Culture">
    <div class="hero-content">
        <h1 class="hero-title">FESTIVAL CONNECT : <span class="pink-fade-text">EXPLOREZ</span> LA VILLE À TRAVERS LA CULTURE DU <span class="pink-fade-text">SKATE.</span></h1>
        <p class="hero-subtitle">Festival Connect est un événement où le skate devient un véritable langage urbain. Entre performances, rencontres et explorations de l'espace public, il invite à redécouvrir la ville autrement.</p>
        <p class="hero-date">16 au 19 Oct 2025 • Événement</p>
    </div>
</div>

<section class="news-transition">
    <div class="container-fluid py-5">
        <div class="row align-items-center">
            <div class="col-4 ps-5 text-start">
                <div class="btn-container">
                    <a href="#" class="btn-read-more">Lire l'article &rarr;</a>
                </div>
                <span class="triple-arrows">↓ ↓ ↓</span>
            </div>
            
            <div class="col-4 text-center">
                <div class="badge-wrapper" id="rotating-badge">
                    <img src="src/autocollants-holographiques-differentes-formes 4.png" class="img-badge-actus" alt="Badge">
                    <span class="badge-text">Dernières actus</span>
                </div>
            </div>
            
            <div class="col-4 pe-5 text-end">
                <span class="triple-arrows">↓ ↓ ↓</span>
            </div>
        </div>
    </div>
</section>

<section class="articles-section">
    <div class="container pb-4">
        <div class="row g-4">
            
            <?php 
            if ($latestArticles) {
                foreach($latestArticles as $article) { 
            ?>
            <div class="col-md-4">
                <div class="card h-100 article-card">
                    <img src="<?php echo ROOT_URL . '/src/uploads/' . htmlspecialchars($article['urlPhotArt']); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($article['libTitrArt']); ?>">
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($article['libTitrArt']); ?></h5>
                        <p class="card-text">
                            <?php echo substr(htmlspecialchars($article['libChapoArt']), 0, 100) . '...'; ?>
                        </p>
                        
                        <div class="article-meta mt-auto pt-3 d-flex justify-content-between align-items-center">
                            <span class="article-date">Posté le <?php echo date("d/m/Y", strtotime($article['dtCreaArt'])); ?></span>
                            <div class="article-icons">
                                <a href="views/frontend/articles/article1.php" class="text-dark">
                                    <i class="bi bi-arrow-right-circle-fill fs-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                } // Fin du foreach
            } else {
                echo "<div class='col-12 text-center'><p>Aucun article trouvé.</p></div>";
            }
            ?>

        </div>

        <div class="row mt-5 mb-5">
            <div class="col-12 text-center">
                <a href="views/frontend/articles/articles.php" class="btn-all-articles">Tous les articles</a>
            </div>
        </div>
    </div>
</section>

<section class="featured-block">
    <div class="container-fluid p-0">
        <div class="feature-card-large">
            <img src="src/Capture d’écran 2026-02-03 à 12.47.52.png" class="feature-bg-img" alt="Adrien Raza">
            
            <div class="feature-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <h2 class="feature-quote">
                                « Le <span class="pink-highlight">miroir d'eau</span>, c'était le <span class="pink-highlight">spot parfait</span> », confie Adrien Raza, quadruple champion de skimboard
                            </h2>
                            <p class="feature-desc">
                                Le skateur Adrien Raza partage son expérience et ses impressions sur le célèbre miroir d'eau à Bordeaux, un lieu devenu incontournable pour les skateurs.
                            </p>
                        </div>
                        <div class="col-lg-4 d-flex align-items-end justify-content-lg-end mt-4 mt-lg-0">
                            <div class="feature-btn-wrapper">
                                <a href="#" class="btn-read-feature">Lire plus &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="community-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0 text-center">
                <img src="src/Group 21.png" class="img-fluid community-img" alt="Montage Communauté">
            </div>

            <div class="col-lg-6 ps-lg-5">
                <h2 class="community-title">Deviens membre de la <span class="pink-text-dark">communauté</span></h2>
                <p class="community-text mt-3">
                    Tu souhaites participer aux concours, liker des posts et les commenter ? 
                    N'attends pas une seconde de plus pour créer ton compte et rentrer dans l'univers du skate entre passionnés !
                </p>
                <div class="mt-4">
                    <a href="api/security/signup.php" class="btn-inscription">Inscription</a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Inter:wght@400;500;700;800;900&display=swap');
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");

    /* HERO */
    .hero-container { position: relative; height: 85vh; background: #000; border-radius: 0 0 50px 50px; overflow: hidden; display: flex; align-items: flex-end; }
    .hero-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.7; z-index: 1; }
    .hero-content { position: relative; z-index: 2; padding: 0 5% 15% 5%; width: 100%; }
    .hero-title { font-family: 'Luckiest Guy', cursive; font-size: 75px; line-height: 1.1; color: white; text-transform: uppercase; text-shadow: 4px 4px 0px rgba(0,0,0,0.2); }
    .pink-fade-text { color: #fce4f4; } 
    .hero-subtitle { max-width: 800px; font-size: 1.2rem; color: #fff; margin-top: 25px; font-family: 'Inter', sans-serif; }
    .hero-date { font-weight: bold; font-size: 1.3rem; color: #fff; margin-top: 15px; font-family: 'Inter', sans-serif; }
    
    /* TRANSITION */
    .news-transition { background-color: #fce4f4; margin-top: -60px; border-radius: 50px 50px 0 0; position: relative; z-index: 10; }
    .btn-container { height: 0; width: 100%; }
    .btn-read-more { background-color: #f778f2; color: #000; font-family: 'Inter', sans-serif; font-weight: 700; padding: 15px 35px; border-radius: 20px; text-decoration: none; display: inline-block; transition: transform 0.2s; position: relative; top: -165px; box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
    .btn-read-more:hover { transform: scale(1.05); }
    
    .badge-wrapper { position: relative; display: inline-block; will-change: transform; }
    .img-badge-actus { width: 320px; height: auto; display: block; filter: drop-shadow(0 10px 10px rgba(0,0,0,0.15)); }
    .badge-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-6deg) scaleX(1.4) skewX(-10deg); font-family: 'Inter', sans-serif; font-weight: 900; font-size: 1.5rem; color: #000; white-space: nowrap; pointer-events: none; letter-spacing: -1px; opacity: 0.85; mix-blend-mode: multiply; }
    .triple-arrows { font-size: 2.5rem; font-weight: 900; color: #000; display: inline-block; }

    /* SECTIONS */
    .articles-section, .featured-block, .community-section { background-color: #fce4f4; }
    .articles-section { padding-top: 20px; }

    /* CARDS */
    .article-card { background-color: #eeeeef; border: none; border-radius: 25px; overflow: hidden; transition: transform 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .article-card:hover { transform: translateY(-5px); }
    .article-card .card-img-top { height: 220px; object-fit: cover; filter: grayscale(100%); }
    .article-card .card-body { padding: 1.5rem; }
    .article-card .card-title { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 1.2rem; margin-bottom: 0.75rem; color: #000; line-height: 1.3; }
    .article-card .card-text { font-family: 'Inter', sans-serif; font-size: 0.95rem; color: #333; line-height: 1.5; }
    .article-date { font-size: 0.8rem; font-weight: 600; color: #555; }
    
    .btn-all-articles { background-color: #ff85f1; color: #000; font-family: 'Inter', sans-serif; font-weight: 700; padding: 12px 40px; border-radius: 30px; text-decoration: none; display: inline-block; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: background 0.3s; }
    .btn-all-articles:hover { background-color: #ff60ea; }

    /* FEATURED */
    .feature-card-large { position: relative; border-radius: 0; overflow: hidden; height: 80vh; min-height: 600px; display: flex; align-items: flex-end; }
    .feature-bg-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; object-position: top center; filter: grayscale(100%) brightness(0.6); z-index: 1; }
    .feature-content { position: relative; z-index: 2; padding-bottom: 4rem; padding-top: 4rem; width: 100%; color: white; background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 60%, transparent 100%); }
    .feature-quote { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 2.5rem; line-height: 1.1; margin-bottom: 1.5rem; }
    .pink-highlight { color: #ffb7e6; }
    .feature-desc { font-family: 'Inter', sans-serif; font-size: 1.2rem; max-width: 650px; margin-bottom: 0; opacity: 0.9; }
    .btn-read-feature { background-color: #ffc4ec; color: #000; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 1.1rem; padding: 15px 40px; border-radius: 15px; text-decoration: none; transition: transform 0.2s; display: inline-block; }
    .btn-read-feature:hover { transform: scale(1.05); background-color: #ff9ee0; color: #000; }

    /* COMMUNITY */
    .community-section { background: #fff; }
    .community-img { max-width: 100%; height: auto; }
    .community-title { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 2.5rem; color: #000; line-height: 1.2; }
    .community-text { font-family: 'Inter', sans-serif; font-size: 1.1rem; color: #333; line-height: 1.6; max-width: 500px; }
    .btn-inscription { background-color: #fdeef9; color: #000; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 1.1rem; padding: 12px 40px; border-radius: 30px; border: 2px solid #fecded; text-decoration: none; display: inline-block; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: all 0.3s ease; }
    .btn-inscription:hover { background-color: #fecded; transform: translateY(-2px); color: #000; }

    @media (max-width: 768px) {
        .feature-card-large { height: auto; min-height: 500px; }
        .feature-quote { font-size: 1.8rem; }
        .community-title { font-size: 2rem; margin-top: 20px; }
    }
</style>

<script>
    const badge = document.getElementById('rotating-badge');
    window.addEventListener('scroll', () => {
        const rotation = window.scrollY / 4;
        badge.style.transform = `rotate(${rotation}deg)`;
    });
</script>

<?php require_once 'footer.php'; ?>