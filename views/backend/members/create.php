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
                <!-- Form to create a new motcle -->
                <form action="<?php echo ROOT_URL . '/api/members/create.php' ?>" method="post">
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
                        <input id="passMemb" name="passMemb" class="form-control" type="text" autofocus="autofocus" />
                    </div>
                    <div class="form-group">
                        <label for="passMemb2">Confirmez le Mot de Passe</label>
                        <input id="passMemb2" name="passMemb2" class="form-control" type="text" autofocus="autofocus" />
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
                        <button type="submit" class="btn btn-success">Confirmer create ?</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



