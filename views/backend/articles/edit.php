<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$article = null; 
$numArt = isset($_GET['numArt']) ? $_GET['numArt'] : null;

$articles = sql_select('ARTICLE', 'numArt, libTitrArt');
$thematiques = sql_select('THEMATIQUE', '*');

if ($numArt) {
    $result = sql_select('ARTICLE', '*', "numArt = '$numArt'");
    if ($result && count($result) > 0) {
        $article = $result[0];
    }
}
?>

<div class="container mt-4">
    <h1>Modifier un article</h1>

    <form method="GET" action="edit.php" class="mb-4">
        <div class="input-group">
            <select class="form-control" name="numArt">
                <option value="">-- Choisir un article --</option>
                <?php foreach ($articles as $art) : ?>
                    <option value="<?= $art['numArt']; ?>" <?= ($numArt == $art['numArt']) ? 'selected' : ''; ?>>
                        <?= $art['libTitrArt']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Charger</button>
        </div>
    </form>

    <?php if ($article) : ?>
        <form action="<?= ROOT_URL . '/api/articles/update.php' ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="numArt" value="<?= $numArt; ?>">

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group mb-3">
                        <label>Titre</label>
                        <input name="libTitrArt" class="form-control" type="text" value="<?= $article['libTitrArt']; ?>" required />
                    </div>

                    <div class="form-group mb-3">
                        <label>Chapeau</label> 
                        <textarea name="libChapoArt" class="form-control" rows="2"><?= $article['libChapoArt']; ?></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label>Accroche</label>
                        <input name="libAccrochArt" class="form-control" type="text" value="<?= $article['libAccrochArt']; ?>" />
                    </div>

                    <hr>

                    <div class="form-group mb-3">
                        <label>Sous-titre 1</label>
                        <input name="libSsTitr1Art" class="form-control" type="text" value="<?= $article['libSsTitr1Art']; ?>" />
                        <label class="mt-2">Paragraphe 1</label>
                        <textarea name="parag1Art" class="form-control" rows="4"><?= $article['parag1Art']; ?></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label>Sous-titre 2</label>
                        <input name="libSsTitr2Art" class="form-control" type="text" value="<?= $article['libSsTitr2Art']; ?>" />
                        <label class="mt-2">Paragraphe 2</label>
                        <textarea name="parag2Art" class="form-control" rows="4"><?= $article['parag2Art']; ?></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label>Paragraphe 3</label>
                        <textarea name="parag3Art" class="form-control" rows="4"><?= $article['parag3Art']; ?></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label>Conclusion</label>
                        <textarea name="libConclArt" class="form-control" rows="2"><?= $article['libConclArt']; ?></textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <label>Thématique</label>
                    <select class="form-select mb-4" name="numThem">
                        <?php foreach ($thematiques as $them) : ?>
                            <option value="<?= $them['numThem']; ?>" <?= ($them['numThem'] == $article['numThem']) ? 'selected' : ''; ?>>
                                <?= $them['libThem']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Mots-clés</label>
                    <div class="card p-3 mb-4" style="max-height: 200px; overflow-y: auto;">
                        <?php
                        $motsCles = sql_select('MOTCLE', '*');
                        $motsClesArticle = sql_select('MOTCLEARTICLE', 'numMotCle', "numArt = '$numArt'");
                        $motsClesAssocies = array_column($motsClesArticle, 'numMotCle');

                        foreach ($motsCles as $mc) :
                            $checked = in_array($mc['numMotCle'], $motsClesAssocies) ? 'checked' : '';
                        ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="numMotCle[]" value="<?= $mc['numMotCle']; ?>" id="mc_<?= $mc['numMotCle']; ?>" <?= $checked; ?>>
                                <label class="form-check-label" for="mc_<?= $mc['numMotCle']; ?>"><?= $mc['libMotCle']; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <label>Image actuelle</label>
                    <div class="mb-3 text-center">
                        <?php if (!empty($article['urlPhotArt'])) : ?>
                            <img src="/src/uploads/<?= $article['urlPhotArt']; ?>" class="img-thumbnail" style="max-height: 150px;">
                        <?php endif; ?>
                    </div>
                    <label>Changer l'image</label>
                    <input type="file" name="urlPhotArt" class="form-control">
                </div>
            </div>

            <div class="mt-5 mb-5 text-center">
                <button type="submit" class="btn btn-warning btn-lg">Enregistrer les modifications</button>
                <a href="list.php" class="btn btn-secondary btn-lg">Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>