<?php
include '../../../header.php';


if(isset($_GET['numArt'])){
   $numArt = $_GET['numArt'];
   $article = sql_select("ARTICLE", "*", "numArt = $numArt")[0];
}
?>


<!-- Bootstrap form to create a new statut -->
<div class="container">
   <div class="row">
       <div class="col-md-12">
           <h1>Suppression article</h1>
       </div>
       <div class="col-md-12">
           <!-- Form to create a new article -->
           <form action="<?php echo ROOT_URL . '/api/articles/delete.php' ?>" method="post">
               <div class="form-group">
                   <label for="libArt">Nom de l'article</label>
                   <input id="numArt" name="numArt" class="form-control" style="display: none" type="text" value="<?php echo($article['numArt']); ?>" readonly="readonly" />
                   <input id="libTitrArt" name="libTitrArt" class="form-control" type="text" value="<?php echo($article['libTitrArt']); ?>" readonly="readonly" disabled />
               </div>
               <br />
               <div class="form-group mt-2">
                   <a href="list.php" class="btn btn-primary">List</a>
                   <button type="submit" class="btn btn-danger">Confirmer suppression ?</button>
               </div>
           </form>
       </div>
   </div>
</div>

