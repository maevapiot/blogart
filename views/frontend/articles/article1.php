<?php
require_once '../../../header.php';
sql_connect();

if (isset($_GET['idArt'])) {
$idArt = $_GET['idArt'];
$article = sql_select("article", "*", "numArt = $idArt");

if ($article) {
$art = $article[0];
$lienImage = "../../../src/uploads/" . $art['urlPhotArt'];
?>

<style>
/* --- DIRECTION ARTISTIQUE PREMIUM --- */
@import url('https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Montserrat:wght@300;400;500;600;700;800;900&display=swap');

body {
background-color: #f4f7f6;
font-family: 'Montserrat', sans-serif !important;
}

.container-detail { padding-top: 5rem; padding-bottom: 5rem; }

.article-card-premium {
background-color: #ffffff;
border-radius: 16px;
overflow: hidden;
box-shadow: 0 20px 40px rgba(0,0,0,0.08);
display: flex;
flex-wrap: wrap;
align-items: stretch;
}

/* Colonne image avec fond neutre pour éviter les vides si l'image n'est pas zoomée */
.image-col {
flex: 0 0 41.666%;
max-width: 41.666%;
background-color: #ebebeb;
display: flex;
align-items: center;
justify-content: center;
}

.content-col { flex: 0 0 58.333%; max-width: 58.333%; }

/* CORRECTION DU ZOOM ICI */
.article-image-premium {
width: 100%;
height: 100%;
object-fit: contain; /* L'image s'affiche en entier sans être rognée */
display: block;
}

.content-padding { padding: 3.5rem; }

.titre-article-premium {
font-family: 'Montserrat', sans-serif;
font-weight: 900;
font-size: 2.5rem;
text-transform: uppercase;
color: #111;
line-height: 1.1;
margin-bottom: 1rem;
}

.date-article {
font-family: 'Montserrat', sans-serif;
font-size: 0.9rem;
font-weight: 600;
color: #999;
margin-bottom: 1.5rem;
display: block;
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

@media (max-width: 992px) {
.image-col, .content-col { flex: 0 0 100%; max-width: 100%; }
.article-image-premium { height: auto; max-height: 400px; }
}
.commentaire-form textarea {
    padding: 12px 16px;
    border-radius: 12px;
    border: 1px solid #ddd;
    font-size: 1rem;
    font-family: 'Montserrat', sans-serif;
    resize: vertical;
    min-height: 80px;
    transition: border 0.2s, box-shadow 0.2s;
}
.commentaire-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 20px;
}
.commentaire-form button {
    align-self: flex-end;
    padding: 10px 22px;
    border: none;
    border-radius: 12px;
    background-color: #e63946;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.2s, transform 0.2s;
}
</style>

<section class="container container-detail">
<div class="article-card-premium shadow">
<div class="image-col">
<img src="<?php echo $lienImage; ?>" class="article-image-premium" alt="Illustration">
</div>

<div class="content-col">
<div class="content-padding">
<span class="date-article">Publié le <?php echo date('d/m/Y', strtotime($art['dtCreaArt'])); ?></span>
<h1 class="titre-article-premium"><?php echo htmlspecialchars($art['libTitrArt']); ?></h1>
<div class="texte-chapo-premium">
<?php echo nl2br(htmlspecialchars($art['libChapoArt'])); ?>
</div>

<?php if(!empty($art['libAccrochArt'])) { ?>
<div class="texte-accroche-premium">
“<?php echo nl2br(htmlspecialchars($art['libAccrochArt'])); ?>”
</div>
<?php } ?>

<div class="texte-paragraphe-premium">
<?php echo nl2br(htmlspecialchars($art['parag1Art'])); ?>
</div>

<?php if(!empty($art['libSsTitr1Art'])) { ?>
<h3 class="texte-intertitre-premium"><?php echo htmlspecialchars($art['libSsTitr1Art']); ?></h3>
<div class="texte-paragraphe-premium">
<?php echo nl2br(htmlspecialchars($art['parag2Art'])); ?>
</div>
<?php } ?>

<?php if(!empty($art['libSsTitr2Art'])) { ?>
<h3 class="texte-intertitre-premium"><?php echo htmlspecialchars($art['libSsTitr2Art']); ?></h3>
<div class="texte-paragraphe-premium">
<?php echo nl2br(htmlspecialchars($art['parag3Art'])); ?>
</div>
<?php } ?>

<div class="texte-paragraphe-premium fw-bold mt-4" style="color: #0056b3; font-weight:700; border-top: 1px solid #eee; padding-top: 2rem;">
<?php echo nl2br(htmlspecialchars($art['libConclArt'])); ?>
</div>
</div>
</div>
        <section class="Interactions" style= "display: flex">
            <div class="container-central" style= "display :flex; flex-direction: column; padding: 20px;">
                <div class="commentaires">
                    <div class="commentaire-form">
                        <form class="commentaire-form" action="../../../api/comments/create.php" method="post">
                            <textarea name="libCom" placeholder="commentaires"></textarea>
                            <input type="hidden" name="numArt" value="<?php echo $art['numArt']; ?>">
                            <input type="hidden" name="numMemb" value="<?php echo $_SESSION['numMemb'] ?? 0; ?>">
                            <input type="submit">
                        </form>
                        <div class="commentaires-historique">
                            <?php $comments = sql_select('COMMENT', '*', "numArt = {$art['numArt']}"); ?>
                                <h3>Commentaires</h3>
                            <?php if (!empty($comments)): ?>
                                <?php foreach ($comments as $com): ?>
                                    <div class="comment">
                                        <p><?php echo nl2br(htmlspecialchars($com['libCom'])); ?></p>
                                    </div>
                                    <hr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Aucun commentaire pour cet article.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="likes">
                        <div class="likes">
    <?php if (!isset($_SESSION['numMemb'])): ?>
        
        <p class="avertissement mt-3">
            Vous devez être connecté pour liker cet article.
        </p>

    <?php else: ?>

        <?php
            $numMemb = $_SESSION['numMemb'];
            $LikeActif = sql_select("LIKEART", "*", "numArt = {$art['numArt']} AND numMemb = {$numMemb}");
            $liked = (!empty($LikeActif) && $LikeActif[0]['likeA'] == 1);
        ?>

        <form action="../../../api/likes/create.php" method="post" class="like-form">
            <input type="hidden" name="numArt" value="<?php echo $art['numArt']; ?>">
            <input type="hidden" name="numMemb" value="<?php echo $numMemb; ?>"> 

            <div class="d-flex align-items-center">
                <h4 class="me-3 mb-0">Cet article vous a plu ?</h4>

                <div class="form-check form-switch">
                    <input class="form-check-input" 
                        type="checkbox" 
                        id="likeSwitch"
                        name="likeA"
                        style="width: 3.5em; height: 1.75em; cursor: pointer;"
                        onchange="this.form.submit()"
                        <?php echo ($liked ? 'checked' : ''); ?>>
                        
                    <label class="form-check-label ms-2 mt-1 fw-bold" for="likeSwitch">
                        <?php echo ($liked ? "J'aime déjà !" : "J'aime"); ?>
                    </label>
                </div>
            </div>
        </form>

    <?php endif; ?>
</div>
                    </div>  
            </div>
    </section>

</div>

</section>


<?php
}
} else {
echo "<div class='container mt-5 alert alert-danger'>Erreur : aucun id d'article fourni.</div>";
exit;
}

require_once '../../../footer.php';
?>