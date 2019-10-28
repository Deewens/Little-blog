<?php
	
	include("pdo/pdo.php");

	session_start();

	if(!isset($_SESSION["connected"])) {
		header("Location: index.php");
		exit;
	}

	$titreError = $sujetError = "";
	$titre = $sujet = "";

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST['titre']) AND isset($_POST['sujet'])) {
			$validForm = true;

			$titre = test_input($_POST['titre']);
			$sujet = test_input($_POST['sujet']);
			if(empty($titre)) {
				$validForm = false;
				$titreError = "Vous devez mettre un titre.";
			}

			if(empty($sujet)) {
				$validForm = false;
				$sujetError = "Vous ne pouvez pas laisser le sujet vide.";
			}


			$titredouble = $pdo->prepare("SELECT TitreSujet FROM sujet WHERE TitreSujet = ?");
			$titredouble->bindValue(1, $titre, PDO::PARAM_STR);
			$titredouble->execute();

			$result = $titredouble->fetchAll();

			if(!empty($result)) {
				$validForm = false;
				$titreError = "Le titre de ce sujet existe déjà.";
			}

			if($validForm) {
				$insert_stmt = $pdo->prepare("INSERT INTO sujet(IDRedacteur, TitreSujet, TexteSujet, DateSujet) VALUES(?, ?, ?, CURRENT_TIMESTAMP())");
				$insert_stmt->bindValue(1, $_SESSION['IDRedacteur'], PDO::PARAM_INT);
				$insert_stmt->bindValue(2, $titre, PDO::PARAM_STR);
				$insert_stmt->bindValue(3, $sujet, PDO::PARAM_STR);

				$insert_stmt->execute();
				header("Location: index.php");
			}
		}
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Ajouter un article</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="/style/style.css"> 
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

		<div class="container space">
			<h1> Ajouter un article </h1>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
					<div class="form-group">
		    			<label>Titre</label>
		    			<input type="text" class="form-control" placeholder="Ex : Le Tour de France" name="titre" aria-describedby="titreError" value="<?php echo $titre; ?>">
		    			<small id="titreError" class="form-text text-danger" role="alert"><?php echo $titreError; ?></small>
			  		</div>	
			  		<div class="form-group">
						<label>Sujet</label>
		    			<textarea class="form-control" rows="4" name="sujet" aria-describedby="sujetError"><?php echo $sujet; ?></textarea>
		    			<small id="sujetError" class="form-text text-danger" role="alert"><?php echo $sujetError; ?></small>
			  		</div>
			  			<button type="submit" class="btn btn-primary">Ajouter</button>
			  			<a href="./index.php" class="btn btn-primary" style="float: right; color: #FFF">Annuler</a>
				</form>
		</div>
	</body>

<?php 

// PHP Functions

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>