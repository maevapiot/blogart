<?php
include '../../../header.php';


if(isset($_GET['numThem'])){
    $numThem = $_GET['numThem'];
    $libThem = sql_select("THEMATIQUE", "libThem", "numThem = $numThem")[0]['libThem'];
}
?>


<!-- Bootstrap form to create a new thematique -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Modification thematique</h1>
        </div>
        <div class="col-md-12">
            <!-- Form to create a new thematique -->
            <form action="<?php echo ROOT_URL . '/api/thematiques/update.php' ?>" method="post">
                <div class="form-group">
                    <label for="libThem">Nom de la thematique</label>
                    <input id="numThem" name="numThem" class="form-control" style="display: none" type="text" value="<?php                 <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">List</a>
                    <button type="submit" class="btn btn-danger">Confirmer modification ?</button>
                </div>
            </form>
        </div>
    </div>
</div>


echo($numThem); ?>" readonly="readonly" />
                    <input id="libThem" name="libThem" class="form-control" type="text" value="<?php echo($libThem); ?>" />
                </div>
                <br />

