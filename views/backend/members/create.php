<?php
include '../../../header.php';


// Accès réservé admin (1) et modérateur (2)
if (!isset($_SESSION['numStat']) || (int)$_SESSION['numStat'] > 2) {
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit();
}








    if (isset($_SESSION['error_message'])) {
        echo '<div style="
            background-color: #ffdddd;
            color: #d8000c;
            border-left: 5px solid #d8000c;
            padding: 10px;
            margin: 10px 0;
            font-family: Arial, sans-serif;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        ">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']); // Supprimer le message après affichage
    }


    ?>




    <!-- Bootstrap form to create a new motcle -->
    <link rel="stylesheet" href="/../../src/css/style.css">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Création nouveau Membre</h1>
            </div>
            <div class="col-md-12">
                <!-- ===================================
                     Formulaire de création de membre avec reCAPTCHA v3
                     ==================================== -->
                <form id="form-recaptcha" action="<?php echo ROOT_URL . '/api/members/create.php' ?>" method="post">
                    <div class="form-group">
                        <label for="pseudoMemb">Pseudo (non modifiable)</label>
                        <input id="pseudoMemb" name="pseudoMemb" class="form-control" type="text" autofocus="autofocus" />
                    </div>
                    <div class="form-group">
                        <label for="prenomMemb">Prénom</label>
                        <input id="prenomMemb" name="prenomMemb" class="form-control" type="text" autofocus="autofocus" />
                    </div>
                    <div class="form-group">
                        <label for="nomMemb">Nom</label>
                        <input id="nomMemb" name="nomMemb" class="form-control" type="text" autofocus="autofocus" />
                    </div>
                    <div class="form-group">
                        <label for="eMailMemb">Email</label>
                        <input id="eMailMemb" name="eMailMemb" class="form-control" type="text" autofocus="autofocus" />
                    </div>
                    <div class="form-group">
                        <label for="eMailMemb2">Confirmez l'Email</label>
                        <input id="eMailMemb2" name="eMailMemb2" class="form-control" type="text" autofocus="autofocus" />
                    </div>
                    <div class="form-group">
                        <label for="passMemb">Mot de Passe</label>
                        <input id="passMemb" name="passMemb" class="form-control" type="password" autofocus="autofocus" />
                    </div>
                    <div class="form-group">
                        <label for="passMemb2">Confirmez le Mot de Passe</label>
                        <input id="passMemb2" name="passMemb2" class="form-control" type="password" autofocus="autofocus" />
                    </div>
                    <div class="form-group">
                        <label for="numStat">Statut</label>
                        <select id="numStat" name="numStat" class="form-control">
                            <option value="3">Membre</option>
                            <option value="2">Modérateur</option>
                        </select>
                    </div>
                    <br />
                    <div class="form-group mt-2">
                        <a href="list.php" class="btn btn-primary">List</a>
                        <!-- ===================================
                             Bouton reCAPTCHA v3 pour validation
                             ==================================== -->
                        <button
                            class="btn btn-success g-recaptcha"
                            data-sitekey="<?php echo getenv('RECAPTCHA_SITE_KEY'); ?>"
                            data-callback="onSubmit"
                            data-action="submit"
                        >
                            Confirmer create ?
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
