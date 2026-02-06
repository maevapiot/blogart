<?php
include '../../../header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Création d'un commentaire</h1>
        </div>

        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/comments/create.php' ?>" method="post">

                <div class="form-group">
                    <label for="numArt">Numéro article</label>
                    <input id="numArt" name="numArt" class="form-control" type="number" required />
                </div>

                <br />

                <div class="form-group">
                    <label for="numMemb">Numéro membre</label>
                    <input id="numMemb" name="numMemb" class="form-control" type="number" required />
                </div>

                <br />

                <div class="form-group">
                    <label for="likeArticle">Like</label>
                    <textarea id="likeArticle" name="likeArticle" class="form-control" rows="4" required></textarea>
                </div>

                <br />

                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">List</a>
                    <button type="submit" class="btn btn-success">Confirmer create ?</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
include '../../../footer.php';
