<?php
include '../../../header.php';


if (!isset($_GET['numThem']) || !is_numeric($_GET['numThem'])) {
    header('Location: list.php');
    exit;
}


$numThem = (int) $_GET['numThem'];
$libThem = sql_select("THEMATIQUE", "libThem", "numThem = $numThem")[0]['libThem'];
?>




<!-- Bootstrap form to create a new Thematic -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Suppression thématique</h1>


<?php if (isset($_GET['error']) && $_GET['error'] === 'linked') : ?>
    <div class="alert alert-danger">
        Cette thématique ne peut pas être supprimée car elle est encore rattachée à des articles.
    </div>
<?php endif; ?>




        </div>
        <div class="col-md-12">
            <!-- Form to create a new Thematic -->
            <form action="<?php echo ROOT_URL . '/api/thematiques/delete.php' ?>" method="post">
                <div class="form-group">
                    <label for="libThem">Nom de la thématique </label>
                    <input type="hidden" id="numThem" name="numThem" value="<?php echo $numThem; ?>" />
                    <input id="libThem" name="libThem" class="form-control" type="text" value="<?php echo($libThem); ?>" readonly="readonly" disabled />
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">List</a>
                    <button type="submit" class="btn btn-danger">Confirmer delete ?</button>
                </div>
            </form>
        </div>
    </div>
</div>
