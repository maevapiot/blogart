<?php
include '../../../header.php';
startSession();

// Vérifier les droits d'accès
if (!isset($_SESSION['user_id']) || $_SESSION['user_statut'] > 2) {
    setFlashMessage('error', 'Accès non autorisé.');
    header('Location: ' . ROOT_URL . '/views/backend/security/login.php');
    exit;
}

// Vérifier si les IDs sont passés
if (!isset($_GET['numArt']) || !isset($_GET['numMemb'])) {
    setFlashMessage('error', 'Like non spécifié.');
    header('Location: list.php');
    exit;
}

$numArt = intval($_GET['numArt']);
$numMemb = intval($_GET['numMemb']);

$like = sql_select(
    "LIKEART l LEFT JOIN ARTICLE a ON l.numArt = a.numArt LEFT JOIN MEMBRE m ON l.numMemb = m.numMemb",
    "l.*, a.libTitrArt, m.pseudoMemb",
    "l.numArt = $numArt AND l.numMemb = $numMemb"
);

if (empty($like)) {
    setFlashMessage('error', 'Like introuvable.');
    header('Location: list.php');
    exit;
}

$like = $like[0];
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Détail du Like</h1>
            <hr>
            
            <?php displayFlashMessage(); ?>
            
            <div class="card">
                <div class="card-header">
                    <strong>Informations du like</strong>
                </div>
                <div class="card-body">
                    <p><strong>Article :</strong> <?php echo htmlspecialchars($like['libTitrArt']); ?></p>
                    <p><strong>Membre :</strong> <?php echo htmlspecialchars($like['pseudoMemb']); ?></p>
                    <p><strong>Statut :</strong> 
                        <?php if ($like['likeA']): ?>
                            <span class="badge bg-success">Like</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Unlike</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="list.php" class="btn btn-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
</div>

<?php include '../../../footer.php'; ?>