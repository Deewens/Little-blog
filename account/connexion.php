<?php

include("../pdo/pdo.php");

session_start();

$pseudoError = $passwordError = "";
$pseudo = "";
$connectionError = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isset($_POST['pseudo']) AND isset($_POST['password'])) {
		$validForm = true;

		$pseudo = test_input($_POST['pseudo']);
		$password = test_input($_POST['password']);

		if(empty($pseudo)) {
			$validForm = false;
			$pseudoError = "Vous devez entrez entrez un pseudo.";
		}

		if(empty($password)) {
			$validForm = false;
			$passwordError = "Vous devez entrez un mot de passe.";
		}

		if($validForm) {
			$verif = $pdo->prepare("SELECT IDRedacteur, Pseudo, MotDePasse FROM redacteur WHERE pseudo = ? OR AdresseMail = ?");
			$verif->bindValue(1, $pseudo, PDO::PARAM_STR);
			$verif->bindValue(2, $pseudo, PDO::PARAM_STR);
			$verif->execute();

			$result = $verif->fetch();

			if(empty($result)) {
				$connectionError =
				"
				<div class=\"alert alert-danger\" role=\"alert\">
					<strong>ERREUR :</strong> ce pseudo n'existe pas.
				</div>
				";
			}
			else {
				if(password_verify($password, $result['MotDePasse'])) {
					session_start();
					$_SESSION['connected'] = true;
					$_SESSION['IDRedacteur'] = $result['IDRedacteur'];
					$_SESSION['pseudo'] = $result['Pseudo'];
					header('Location: ../index.php');
				}
				else {
					$connectionError =
					"
					<div class=\"alert alert-danger\" role=\"alert\">
						<strong>ERREUR :</strong> le mot de passe est incorrect.
					</div>
					";
				}
			}
		}
	}
}


?>



<!DOCTYPE html>
<html>
<head>
	<title>Connexion</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="../styles/style.css"> 
</head>
<body>
<nav class="d-flex justify-content-around navbar navbar-expand-lg bg-dark text-white">
      <div class="navbar-brand">
          Bienvenue sur le blog <?php if(!empty($_SESSION['pseudo'])) {echo " - " . $_SESSION['pseudo'];} ?>
      </div>
      <div id="navbarNavAltMarkup">
          <div class="nav navbar-nav">
              <a class="nav-item nav-link active text-white" href="../index.php">
                  Accueil
              </a>
              <?php
              if(empty($_SESSION['pseudo'])) {
              ?>
              <a class="nav-item nav-link text-white" href="connexion.php">
                  Connexion
              </a>
              <a class="nav-item nav-link text-white" href="inscription.php">
                  Inscription
              </a>
              <?php } else { ?>
              <a class="nav-item nav-link text-white" href="../ajout_article.php">
                  Nouveau sujet
              </a>
              <a class="nav-item nav-link text-white" href="deconnexion.php" onclick="return confirm('Etes vous sûr de vouloir vous déconnecter ?');">
                  Déconnexion
              </a>
              <?php } ?>
          </div>
        </div>
    </nav>


	<div class="container space">
		<h1>Connexion</h1>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
				<?php echo $connectionError; ?>
				<div class="form-group">
			    	<label>Identifiant</label>
			    	<input type="text" class="form-control" placeholder="Pseudo ou adresse mail" name="pseudo" aria-describedby="pseudoError" value="<?php echo $pseudo; ?>">
			    	<small id="pseudoError" class="form-text text-danger" role="alert"><?php echo $pseudoError; ?></small>
			  	</div>	
			  	<div class="form-group">
			    	<label> Mot de passe </label>
			    	<input type="password" class="form-control" placeholder="Entrer votre mot de passe" name="password" aria-describedby="passwordError">
			    	<small id="passwordError" class="form-text text-danger" role="alert"><?php echo $passwordError; ?></small>
			  	</div>
			  		<button type="submit" class="btn btn-primary">Connexion</button>
			</form>
	</div>
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