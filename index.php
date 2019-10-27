<?php

require('pdo/pdo.php');

session_start();

// Récupération des sujets
$blog_stmt = $pdo->
prepare('
  SELECT IDSujet, Pseudo, TitreSujet, TexteSujet, DateSujet
  FROM sujet 
  INNER JOIN redacteur ON sujet.IDRedacteur = redacteur.IDRedacteur
  ORDER BY DateSujet DESC 
  ');
$blog_stmt->execute();

?>


<!DOCTYPE html>
<html>
  <head>
    <title>
        Blog
    </title>
    <link crossorigin="anonymous" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" rel="stylesheet">
        <script crossorigin="anonymous" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js">
        </script>
    </link>
  </head>
  <body>
    <nav class="d-flex justify-content-around navbar navbar-expand-lg bg-dark text-white">
      <div class="navbar-brand">
          Bienvenue sur le blog <?php if(!empty($_SESSION['pseudo'])) {echo " - " . $_SESSION['pseudo'];} ?>
      </div>
      <div id="navbarNavAltMarkup">
          <div class="nav navbar-nav">
              <a class="nav-item nav-link active text-white" href="index.php">
                  Accueil
              </a>
              <?php
              if(empty($_SESSION['pseudo'])) {
              ?>
              <a class="nav-item nav-link text-white" href="./account/connexion.php">
                  Connexion
              </a>
              <a class="nav-item nav-link text-white" href="./account/inscription.php">
                  Inscription
              </a>
              <?php } else { ?>
              <a class="nav-item nav-link text-white" href="./ajout_article.php">
                  Nouveau sujet
              </a>
              <a class="nav-item nav-link text-white" href="./account/deconnexion.php" onclick="return confirm('Etes vous sûr de vouloir vous déconnecter ?');">
                  Déconnexion
              </a>
              <?php } ?>
          </div>
        </div>
    </nav>
    
    <?php
    while($data = $blog_stmt->fetch()) { 
    ?>
    <div class="card border border-info container mt-5 mb-5" style="max-width: 50rem;">
        <div class="card-header">
            <h2>
                <?php echo $data['TitreSujet']; ?>
            </h2>
        </div>
        <div class="card-body">
            <blockquote class="blockquote mb-0">
                <p>
                    <?php echo substr($data['TexteSujet'], 0, 250) . '[...]' ?>
                </p>
            </blockquote>
            <footer class="blockquote-footer" style="margin-bottom: 5px;">
                <?php echo $data['Pseudo'] . ", le " . $data['DateSujet']; ?>
            </footer>

            <a class="btn btn-primary float-right" style="margin-bottom: 25px;" id="btn-read-more" href="sujet.php?id=<?php echo $data['IDSujet']?>">
                    Lire la suite
            </a>
        </div>
    </div>
    <?php
    }
    ?>
  </body>
</html>

<?php 

// PHP Functions

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
