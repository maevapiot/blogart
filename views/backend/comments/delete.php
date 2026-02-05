<?php
include '../../../header.php';

$numCom = (int)($_GET['numCom'] ?? 0);
if ($numCom <= 0) {
    header('Location: list.php');
    exit;
}
?>

    <div class="container">
    <div class="row">
        <div class="col-md-12">
        <h1>Suppression commentaire</h1>
        <p>Confirmer la suppression logique du commentaire #<?php echo $numCom; ?> ?</p>

        <a href="list.php" class="btn btn-primary">Annuler</a>
        <a href="<?php echo ROOT_URL . '/api/comments/delete.php?numCom=' . $numCom; ?>" class="btn btn-danger">
            Confirmer delete ?
        </a>
        </div>
    </div>
    </div>

<?php
include '../../../footer.php';

