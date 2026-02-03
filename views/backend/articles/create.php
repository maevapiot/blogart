<?php
include '../../../header.php';
?>




<!-- Bootstrap form to create a new statut -->
<div class="container">
   <div class="row">
       <div class="col-md-12">
           <h1>Création nouvel article</h1>
       </div>
       <div class="col-md-12">
           <?php $thematiques = sql_select("THEMATIQUE", "*"); ?>
           <!-- Form to create a new article -->
           <form action="<?php echo ROOT_URL . '/api/articles/create.php' ?>" method="post" enctype="multipart/form-data"><!-- envoie données formulaire -->
                   <div class="form-group">
                       <label for="libTitrArt">Titre</label>
                       <textarea id="libTitrArt" name="libTitrArt" class="form-control" type="text" autofocus="autofocus" maxlength="100" required placeholder="100 caractères"></textarea>
                       <label for="libChapoArt">Chapeau</label>
                       <textarea id="libChapoArt" name="libChapoArt" class="form-control" type="text" autofocus="autofocus" maxlength="500"  required placeholder="500 caractères" rows="8"></textarea>
                       <label for="libAccrochArt">Accroche</label>
                       <textarea id="libAccrochArt" name="libAccrochArt" class="form-control" type="text" autofocus="autofocus" maxlength="100" required placeholder="100 caractères"></textarea>
                       <label for="parag1Art">Paragraphe 1</label>
                       <textarea id="parag1Art" name="parag1Art" class="form-control" type="text" autofocus="autofocus" maxlength="1200" required placeholder="1200 caractères" rows="8"></textarea>
                       <label for="libSsTitr1Art">Sous-titre 1</label>
                       <textarea id="libSsTitr1Art" name="libSsTitr1Art" class="form-control" type="text" autofocus="autofocus" maxlength="100" required placeholder="100 caractères"></textarea>
                       <label for="parag2Art">Paragraphe 2</label>
                       <textarea id="parag2Art" name="parag2Art" class="form-control" type="text" autofocus="autofocus" maxlength="1200" required placeholder="1200 caractères" rows="8"></textarea>
                       <label for="libSsTitr2Art">Sous-titre 2</label>
                       <textarea id="libSsTitr2Art" name="libSsTitr2Art" class="form-control" type="text" autofocus="autofocus" maxlength="100" required placeholder="100 caractères"></textarea>
                       <label for="parag3Art">Paragraphe 3</label>
                       <textarea id="parag3Art" name="parag3Art" class="form-control" type="text" autofocus="autofocus" maxlength="1200" required placeholder="1200 caractères" rows="8"></textarea>
                       <label for="libConclArt">Conclusion</label>
                       <textarea id="libConclArt" name="libConclArt" class="form-control" type="text" autofocus="autofocus" maxlength="800" required placeholder="800 caractères"></textarea>
                       <label for="numThem">Thématique</label>
                       <select id="numThem" name="numThem" class="form-control" type="text" autofocus="autofocus">
                           <?php foreach($thematiques as $thematique) { ?>
                               <option value="<?php echo($thematique['numThem']); ?>"><?php echo($thematique['libThem']); ?></option>
                           <?php } ?>
                       </select>
                       <input type="file" name="image" accept="image/png, image/jpeg, image/jpg, image/gif" required>
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
