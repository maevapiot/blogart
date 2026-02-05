<?php
include '../../../header.php'; // config + fonctions déjà chargés

$numCom = (int)($_GET['numCom'] ?? 0);
if ($numCom <= 0) {
    header('Location: list.php');
    exit;
}

// Charger tous les commentaires puis trouver celui demandé (comme on n'a pas sql_select_where)
$comments = sql_select('comment', '*');

$comment = null;
foreach ($comments as $c) {
    if ((int)$c['numCom'] === $numCom) {
        $comment = $c;
        break;
    }
}

if (!$comment) {
    echo "<div class='container'><div class='alert alert-danger'>Commentaire introuvable.</div></div>";
    include '../../../footer.php';
    exit;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Modération commentaire #<?php echo $comment['numCom']; ?></h1>
        </div>

        <div class="col-md-12">
            <div class="alert alert-secondary">
                <p><b>Date création :</b> <?php echo htmlspecialchars($comment['dtCreaCom']); ?></p>
                <p><b>Article :</b> <?php echo (int)$comment['numArt']; ?> | <b>Membre :</b> <?php echo (int)$comment['numMemb']; ?></p>
                <hr>
                <p><?php echo nl2br(htmlspecialchars($comment['libCom'])); ?></p>
            </div>
        </div>

        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/comments/update.php'; ?>" method="post">

                <input type="hidden" name="numCom" value="<?php echo (int)$comment['numCom']; ?>">

                <div class="form-group">
                    <label for="attModOK">Validation</label>
                    <select id="attModOK" name="attModOK" class="form-control">
                        <option value="0" <?php echo ((int)$comment['attModOK'] === 0 ? 'selected' : ''); ?>>Refuser / en attente</option>
                        <option value="1" <?php echo ((int)$comment['attModOK'] === 1 ? 'selected' : ''); ?>>Valider</option>
                    </select>
                </div>

                <br>

                <div class="form-group">
                    <label for="notifComKOAff">Message si refus (optionnel)</label>
                    <textarea id="notifComKOAff" name="notifComKOAff" class="form-control" rows="3"><?php
                        echo htmlspecialchars((string)($comment['notifComKOAff'] ?? ''));
                    ?></textarea>
                </div>

                <br>

                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">List</a>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
include '../../../footer.php';

