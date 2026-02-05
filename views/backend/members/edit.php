<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';


/**
 * Autoriser uniquement admin (1) ou modérateur (2)
 * (on ne dépend pas de check_access car il refuse l'admin chez toi)
 */
$userStat = (int)($_SESSION['numStat'] ?? 0);
if (!isset($_SESSION['pseudoMemb']) || !in_array($userStat, [1,2], true)) {
    $_SESSION['error_message'] = "Accès refusé.";
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit();
}


// Récupérer l'id du membre
$numMemb = isset($_GET['numMemb']) ? (int)$_GET['numMemb'] : 0;
if ($numMemb <= 0) {
    $_SESSION['error_message'] = "ID membre invalide.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}


// Récupérer le membre en 1 seule fois
$res = sql_select("membre", "*", "numMemb = $numMemb");
if (!$res || !isset($res[0]['numMemb'])) {
    $_SESSION['error_message'] = "Membre introuvable.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}
$m = $res[0];


// Si la cible est admin, on interdit toute modif de rôle (optionnel mais conseillé)
if ((int)$m['numStat'] === 1) {
    $_SESSION['error_message'] = "Modification protégée : le compte administrateur ne doit pas être modifié ici.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}


// Si l'utilisateur connecté est modo, il ne peut modifier que les membres simples
if ($userStat === 2 && (int)$m['numStat'] !== 3) {
    $_SESSION['error_message'] = "Modification interdite : un modérateur ne peut modifier qu'un membre simple.";
    header("Location: " . ROOT_URL . "/views/backend/members/list.php");
    exit();
}
?>


<link rel="stylesheet" href="/src/css/style.css">


<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1>Modification membre</h1>


      <?php if (!empty($_SESSION['error_message'])) : ?>
        <div class="alert alert-danger mt-3">
          <?php echo htmlspecialchars($_SESSION['error_message']); ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>


      <?php if (!empty($_SESSION['success_message'])) : ?>
        <div class="alert alert-success mt-3">
          <?php echo htmlspecialchars($_SESSION['success_message']); ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
      <?php endif; ?>
    </div>


    <div class="col-md-12 mt-3">
      <!-- IMPORTANT : action RELATIVE pour garder la session -->
      <!-- ===================================
           Formulaire de modification de membre avec reCAPTCHA v3
           ==================================== -->
      <form id="form-recaptcha" action="/api/members/update.php" method="post">
        <input type="hidden" name="numMemb" value="<?php echo (int)$m['numMemb']; ?>">


        <div class="form-group">
          <label for="prenomMemb">Prénom</label>
          <input id="prenomMemb" name="prenomMemb" class="form-control" type="text"
                 value="<?php echo htmlspecialchars($m['prenomMemb'] ?? ''); ?>" required>
        </div>


        <div class="form-group">
          <label for="nomMemb">Nom</label>
          <input id="nomMemb" name="nomMemb" class="form-control" type="text"
                 value="<?php echo htmlspecialchars($m['nomMemb'] ?? ''); ?>" required>
        </div>


        <div class="form-group">
          <label>Pseudo (non modifiable)</label>
          <input class="form-control" type="text"
                 value="<?php echo htmlspecialchars($m['pseudoMemb'] ?? ''); ?>" disabled>
        </div>


        <div class="form-group">
          <label for="eMailMemb">Email</label>
          <input id="eMailMemb" name="eMailMemb" class="form-control" type="email"
                 value="<?php echo htmlspecialchars($m['eMailMemb'] ?? ''); ?>" required>
        </div>


        <div class="form-group">
          <label for="eMailMemb2">Confirmer l’email</label>
          <input id="eMailMemb2" name="eMailMemb2" class="form-control" type="email"
                 value="<?php echo htmlspecialchars($m['eMailMemb'] ?? ''); ?>" required>
        </div>


        <hr>


        <div class="form-group">
          <label for="passMemb">Nouveau mot de passe (optionnel)</label>
          <input id="passMemb" name="passMemb" class="form-control" type="password"
                 placeholder="Laisse vide si tu ne changes pas">
        </div>


        <div class="form-group">
          <label for="passMemb2">Confirmer le mot de passe</label>
          <input id="passMemb2" name="passMemb2" class="form-control" type="password"
                 placeholder="Confirme le nouveau mot de passe">
        </div>


        <div class="form-group">
          <label>Date de création</label>
          <input class="form-control" type="text"
                 value="<?php echo htmlspecialchars($m['dtCreaMemb'] ?? ''); ?>" disabled>
        </div>


        <div class="form-group">
          <label for="numStat">Statut</label>


          <?php if ($userStat === 1) : ?>
            <!-- Admin peut choisir 2 ou 3 (jamais 1) -->
            <select id="numStat" name="numStat" class="form-control">
              <option value="2" <?php echo ((int)$m['numStat'] === 2 ? 'selected' : ''); ?>>Modérateur</option>
              <option value="3" <?php echo ((int)$m['numStat'] === 3 ? 'selected' : ''); ?>>Membre</option>
            </select>
          <?php else : ?>
            <!-- Modérateur : pas de changement de statut -->
            <input class="form-control" type="text"
                   value="<?php echo ((int)$m['numStat'] === 2 ? 'Modérateur' : 'Membre'); ?>" disabled>
            <input type="hidden" name="numStat" value="<?php echo (int)$m['numStat']; ?>">
          <?php endif; ?>
        </div>


        <br>


        <div class="form-group mt-2">
          <a href="<?php echo ROOT_URL . '/views/backend/members/list.php'; ?>" class="btn btn-primary">Liste</a>
          <!-- ===================================
               Bouton reCAPTCHA v3 pour validation
               ==================================== -->
          <button
            class="btn btn-success g-recaptcha"
            data-sitekey="<?php echo getenv('RECAPTCHA_SITE_KEY'); ?>"
            data-callback="onSubmit"
            data-action="submit"
          >
            Confirmer la modification ?
          </button>
        </div>


      </form>
    </div>
  </div>
</div>

<!-- ===================================
     Callback JavaScript reCAPTCHA v3
     Appelé automatiquement après validation
     Soumet le formulaire avec le token
     Affiche le formulaire dans la console pour débug
     ==================================== -->
<script>
function onSubmit(token) {
    // Récupérer et soumettre le formulaire
    document.getElementById("form-recaptcha").submit();
    // Afficher le formulaire dans la console (débug)
    console.log(document.getElementById("form-recaptcha"));
}
</script>
