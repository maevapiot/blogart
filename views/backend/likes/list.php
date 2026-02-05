<?php
include '../../../header.php';
startSession();

// Vérifier les droits d'accès
if (!isset($_SESSION['user_id']) || $_SESSION['user_statut'] > 2) {
    setFlashMessage('error', 'Accès non autorisé.');
    header('Location: ' . ROOT_URL . '/views/backend/security/login.php');
    exit;
}

// Charger tous les likes avec articles et membres
$likes = sql_select(
    "LIKEART l LEFT JOIN ARTICLE a ON l.numArt = a.numArt LEFT JOIN MEMBRE m ON l.numMemb = m.numMemb",
    "l.*, a.libTitrArt, m.pseudoMemb",
    null,
    null,
    "a.numArt ASC, m.pseudoMemb ASC"
);

// Statistiques par article
$statsParArticle = sql_select(
    "ARTICLE a LEFT JOIN LIKEART l ON a.numArt = l.numArt AND l.likeA = 1",
    "a.numArt, a.libTitrArt, COUNT(l.numMemb) as nbLikes",
    null,
    "a.numArt",
    "nbLikes DESC"
);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1>Gestion des Likes</h1>
            <hr>
            
            <?php displayFlashMessage(); ?>
            
            <div class="mb-3">
                <a href="../dashboard.php" class="btn btn-secondary">Retour Dashboard</a>
            </div>
            
            <!-- Statistiques par article -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Statistiques par article</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Nombre de likes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($statsParArticle as $stat): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($stat['libTitrArt']); ?></td>
                                    <td>
                                        <span class="badge bg-success"><?php echo $stat['nbLikes']; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Liste détaillée des likes -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Détail des likes</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Article</th>
                                <th>Membre</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($likes)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Aucun like trouvé</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($likes as $like): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(substr($like['libTitrArt'] ?? 'N/A', 0, 40)); ?>...</td>
                                        <td><?php echo htmlspecialchars($like['pseudoMemb'] ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if ($like['likeA']): ?>
                                                <span class="badge bg-success">Like</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Unlike</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="edit.php?numArt=<?php echo $like['numArt']; ?>&numMemb=<?php echo $like['numMemb']; ?>" class="btn btn-primary btn-sm">Voir</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../../footer.php'; ?>