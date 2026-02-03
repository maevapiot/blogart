<?php
include '../../../header.php'; // contains the header and call to config.php


session_start();
if (isset($_SESSION['success'])) {
    echo "<p style='color:green'>" . $_SESSION['success'] . "</p>";
    unset($_SESSION['success']); // message affiché une seule fois
}


//Load all articles
$articles = sql_select("ARTICLE", "*");// va chercher toute les infos dans la bdd (select * from ARTICLE) et la met dans une matrice($articles)
?>




<!-- Bootstrap default layout to display all articles in foreach -->
<div class="container">
   <div class="row">
       <div class="col-md-12">
           <h1>Articles</h1>
           <table class="table table-striped">
               <thead>
                   <tr>
                       <th>Id</th>
                       <th>Nom des articles</th>
                       <th>Actions</th>
                   </tr>
               </thead>
               <tbody>
                   <?php foreach($articles as $article){ ?> <!-- lit chaque ligne de la matrice et affiche numArt et libArt -->
                       <tr>
                           <td><?php echo($article['numArt']); ?></td>
                           <td><?php echo($article['libTitrArt']); ?></td>
                           <td>
                               <a href="edit.php?numArt=<?php echo($article['numArt']); ?>" class="btn btn-primary">Edit</a><!-- lien cliquable-->
                               <a href="delete.php?numArt=<?php echo($article['numArt']); ?>" class="btn btn-danger">Delete</a>
                           </td>
                       </tr>
                   <?php } ?>
               </tbody>
           </table>
           <a href="create.php" class="btn btn-success">Create</a>
       </div>
   </div>
</div>
<?php
include '../../../footer.php'; // contains the footer

