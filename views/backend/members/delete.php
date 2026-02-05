<?php



require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';


/**
 * Autoriser uniquement admin (1) ou modérateur (2)
 */
$userStat = (int)($_SESSION['numStat'] ?? 0);
if (!isset($_SESSION['pseudoMemb']) || !in_array($userStat, [1, 2], true)) {
    $_SESSION['error_message'] = "Accès refusé.";
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit();
}


// ID membre à supprimer (GET)
$numMemb = isset($_GET['numMemb']) ? (int)$_GET['numMemb'] : 0;
if ($numMemb <= 0) {
    $_SESSION['error_message'] = "ID membre invalide.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}


// Récupérer le membre (une seule requête)
$res = sql_select("membre", "*", "numMemb = $numMemb");
if (!$res || !isset($res[0]['numMemb'])) {
    $_SESSION['error_message'] = "Membre introuvable.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}
$m = $res[0];


// Interdire suppression admin cible (numStat=1)
if ((int)$m['numStat'] === 1) {
    $_SESSION['error_message'] = "Suppression interdite : le compte administrateur ne peut pas être supprimé.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}


// Si l'utilisateur connecté est modo (2) -> il ne supprime que les membres simples (3)
if ($userStat === 2 && (int)$m['numStat'] !== 3) {
    $_SESSION['error_message'] = "Suppression interdite : un modérateur ne peut supprimer qu'un membre simple.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}
?>


<link rel="stylesheet" href="/src/css/style.css">


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Suppression du compte</h1>
        </div>


        <div class="col-md-12">
            <!-- IMPORTANT : action RELATIVE pour garder la session -->
            <form action="/api/members/delete.php" method="post">
                <input type="hidden" name="numMemb" value="<?php echo (int)$m['numMemb']; ?>">


                <div class="form-group">
                    <label>Nom</label>
                    <input class="form-control" type="text" value="<?php echo htmlspecialchars($m['nomMemb'] ?? ''); ?>" readonly disabled>
                </div>


                <div class="form-group">
                    <label>Prénom</label>
                    <input class="form-control" type="text" value="<?php echo htmlspecialchars($m['prenomMemb'] ?? ''); ?>" readonly disabled>
                </div>


                <div class="form-group">
                    <label>Pseudo</label>
                    <input class="form-control" type="text" value="<?php echo htmlspecialchars($m['pseudoMemb'] ?? ''); ?>" readonly disabled>
                </div>


                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" type="text" value="<?php echo htmlspecialchars($m['eMailMemb'] ?? ''); ?>" readonly disabled>
                </div>


                <br />


                <div class="form-group mt-2">
                    <a href="<?php echo ROOT_URL . '/views/backend/members/list.php'; ?>" class="btn btn-primary">Liste</a>
                    <button type="submit" class="btn btn-danger">Confirmer delete ?</button>
                </div>
            </form>
        </div>
    </div>
</div>


