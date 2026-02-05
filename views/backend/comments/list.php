<?php
include '../../../header.php'; // contient config + ctrlSaisies

// IMPORTANT : la table dans phpMyAdmin s'appelle "comment" (minuscule)
$comments = sql_select("comment", "*");
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>comments</h1>

            <?php if (empty($comments)) { ?>
                <div class="alert alert-warning">Aucun commentaire trouvé.</div>
            <?php } else { ?>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Date</th>
                            <th>Commentaire</th>
                            <th>OK</th>
                            <th>Article</th>
                            <th>Membre</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($comments as $comment) { ?>
                            <?php if ((int)$comment['delLogiq'] === 0) { ?>
                                <tr>
                                    <td><?php echo $comment['numCom']; ?></td>
                                    <td><?php echo $comment['dtCreaCom']; ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($comment['libCom'])); ?></td>
                                    <td><?php echo ((int)$comment['attModOK'] === 1) ? "Oui" : "Non"; ?></td>
                                    <td><?php echo $comment['numArt']; ?></td>
                                    <td><?php echo $comment['numMemb']; ?></td>
                                    <td>
                                        <a href="edit.php?numCom=<?php echo $comment['numCom']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="delete.php?numCom=<?php echo $comment['numCom']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>

                    </tbody>
                </table>

            <?php } ?>

            <a href="create.php" class="btn btn-success">Create</a>
        </div>
    </div>
</div>

<?php
include '../../../footer.php';

