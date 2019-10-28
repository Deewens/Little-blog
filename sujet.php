<?php

require('pdo/pdo.php');

session_start();

$id_sujet = $_GET['id'];

$reponse = "";
$reponseError = "";
if(isset($_SESSION['connected'])) {
  if($_SERVER['REQUEST_METHOD'] == "POST") {
    if(isset($_POST['reponse'])) {

      $subjectId = test_input($_GET['id']);
      $reponse = test_input($_POST['reponse']);

      if(empty($reponse)) {
        $reponseError =
            "
      <div class=\"alert alert-warning\" role=\"alert\" style=\"margin:10px\">
        <strong>Attention :</strong> vous ne pouvez pas envoyer un commentaire vide.
      </div>
    ";
      } else {
        $insert_stmt = $pdo->prepare("INSERT INTO reponse(IDSujet, IDRedacteur, DateRep, TexteReponse) VALUES(?, ?, CURRENT_TIMESTAMP(), ?)");
        $insert_stmt->bindValue(1, $subjectId, PDO::PARAM_INT);
        $insert_stmt->bindValue(2, $_SESSION['IDRedacteur'], PDO::PARAM_INT);
        $insert_stmt->bindValue(3, $reponse, PDO::PARAM_STR);

        $insert_stmt->execute();
      }

    }
  }
}

// Requête messages en réponse
$rep_stmt = $pdo->
prepare('
  SELECT TexteReponse, Pseudo, DateRep
  FROM reponse 
  INNER JOIN redacteur ON reponse.IDRedacteur = redacteur.IDRedacteur
  WHERE IDSujet = ?
  ORDER BY DateRep ASC
  ');
$rep_stmt->bindValue(1, $id_sujet, PDO::PARAM_INT);
$rep_stmt->execute();

// Requête sujet
$blog_stmt = $pdo->
prepare('
  SELECT IDSujet, Pseudo, TitreSujet, TexteSujet, DateSujet
  FROM sujet 
  INNER JOIN redacteur ON sujet.IDRedacteur = redacteur.IDRedacteur
  WHERE IDSujet = ?
  ');
$blog_stmt->bindValue(1, $id_sujet, PDO::PARAM_INT);
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
        
        $data = $blog_stmt->fetch(); ?>
        
        <div class="card border border-info container mt-5 mb-5" style="max-width: 50rem;">
          <div class="card-header">
              <h2>
                  <?php echo $data['TitreSujet']; ?>
              </h2>
          </div>
          <div class="card-body">
            <blockquote class="blockquote mb-0">
                <p>
                    <?php echo $data['TexteSujet']; ?>
                </p>
            </blockquote>
            <footer class="blockquote-footer" style="margin-bottom: 5px;">
                <?php echo $data['Pseudo'] . ", le " . $data['DateSujet']; ?>
            </footer>

            <?php
            while($rep = $rep_stmt->fetch()) {
                echo "&nbsp&nbsp&nbsp&nbsp&nbsp | " . $rep["TexteReponse"];
                echo "<span style='color: #6c757d; font-size: 80%'>";
                echo " " . $rep['Pseudo'] . " - " . $rep["DateRep"] . "<br>";
                echo "</span>";
            }

            echo $reponseError;
            if(isset($_SESSION['connected'])) {
            ?>
              <button class="btn btn-primary float-right" style="margin-bottom: 25px;" id="btn-rep" onclick="showReponse();">
              Répondre
              </button>

              <form action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $data['IDSujet']; ?>" id="reply-form" method="post" style="display: none;">
                  <input class="form-control" id="reponse" name="reponse" placeholder="Ex : Super sujet !" style="max-width: 50rem; margin-top: 25px;" type="text" oninput="canSend(this)">
                  </input>
                  <input class="btn btn-primary" id="submit" style="margin-top: 20px;" type="submit" value="Ajouter" disabled="true" title="Votre message ne peut être vide !"/>
              </form>

              <!-- Si le Javascript est désactivé, utilisation d'un formulaire affiché par défaut -->
              <noscript>
                  <form action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $data['IDSujet']; ?>" id="reply-form-noscript" method="post">
                  <input class="form-control" id="reponse" name="reponse" placeholder="Ex : Super sujet !" style="max-width: 50rem; margin-top: 25px;" type="text" >
                  </input>
                  <input class="btn btn-primary" id="submit" style="margin-top: 20px;" type="submit" value="Ajouter" />
              </form>
              </noscript>
          </div>
      </div>
      <?php } ?>

        <script type="text/javascript">
        function showReponse() {
        let form = document.getElementById("reply-form");
        let btn = document.getElementById("btn-rep");
        if(form.style.display == "none") {
          form.style.display = "block";
          btn.innerHTML = "Annuler";
        } else {
          form.style.display = "none";
          btn.innerHTML = "Répondre";
        }
      }

      function canSend(field) {
        let button = document.getElementById("submit");
        if(field.value) {
          button.disabled = false;
          button.title = "Ajouter le commentaire"
        }
        else {
          button.disabled = true;
          button.title = "Votre message ne peut être vide !";
        }
      }
      </script>
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
