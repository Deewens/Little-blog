<?php

include("../pdo/pdo.php");

session_start();

$emptyFieldsAlert = "";
$errorFieldNotSetAlert = "";
$nom = $prenom = $pseudo = $email = "";
$pseudoError = $emailError = $passwordError = $confirmPasswordError = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if(isset($_POST['nom'], $_POST['prenom'], $_POST['pseudo'], $_POST['email'], $_POST['password'], $_POST['confirm-password'])) {
		$passwordMatches = true;
		$validForm = true;

		$nom = test_input($_POST['nom']);
		$prenom = test_input($_POST['prenom']);
		$pseudo = test_input($_POST['pseudo']);
		$email = test_input($_POST['email']);
		$password = test_input($_POST['password']);

		if(empty($nom) OR empty($prenom) OR empty($pseudo) OR empty($email) OR empty($password)) {
			$validForm = false;
			$emptyFieldsAlert = 
			"
			<div class=\"alert alert-warning\" role=\"alert\">
				<strong>Attention :</strong> tout les champs n'ont pas été remplit. Vous devez remplir tout les champs du formulaire pour vous inscrire.
			</div>
			";
		}

		if($_POST['password'] != $_POST['confirm-password']) {
			$passwordMatches = false;
			$confirmPasswordError = "Les deux mots de passes ne correspondent pas.";
		}

		$emaildouble = $pdo->prepare("SELECT COUNT(*) FROM Redacteur WHERE AdresseMail = ?");
		$emaildouble->bindValue(1, $email, PDO::PARAM_STR);
		$emaildouble->execute();

		if($emaildouble->fetchColumn() > 0) {
			$validForm = false;
			$emailError = "Un utilisateur est déjà inscrit avec cette adresse e-mail. Merci d'en choisir une autre.";
		}

		$pseudodouble = $pdo->prepare("SELECT COUNT(*) FROM Redacteur WHERE Pseudo = ?");
		$pseudodouble->bindValue(1, $pseudo, PDO::PARAM_STR);
		$pseudodouble->execute();

		if($pseudodouble->fetchColumn() > 0) {
			$validForm = false;
			$pseudoError = "Ce pseudo existe déjà. Merci d'en choisir un autre.";
		}

		$emaildouble->closeCursor();
		$pseudodouble->closeCursor();

		if ($validForm AND $passwordMatches) {
			$password = password_hash($password, PASSWORD_DEFAULT);

			$insert_stmt = $pdo->prepare("INSERT INTO redacteur(Nom, Prenom, AdresseMail, MotDePasse, Pseudo) VALUES(?, ?, ?, ?, ?)");

			$insert_stmt->bindValue(1, $nom, PDO::PARAM_STR);
			$insert_stmt->bindValue(2, $prenom, PDO::PARAM_STR);
			$insert_stmt->bindValue(3, $email, PDO::PARAM_STR);
			$insert_stmt->bindValue(4, $password, PDO::PARAM_STR);
			$insert_stmt->bindValue(5, $pseudo, PDO::PARAM_STR);

			$insert_stmt->execute();

			header('Location: ../index.php');
			exit();
		}
	}
	else {
		$errorFieldNotSetAlert =
		"
		<div class=\"alert alert-danger\" role=\"alert\">
			<strong>ERREUR :</strong> un ou plusieurs champ(s) a/ont eu un problème lors du traitement du formulaire. Merci de réessayer.
		</div>
		";
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Inscription</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="../style/style.css"> 
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
		<h1>Inscription</h1>
			<?php 
			echo $emptyFieldsAlert; 
			echo $errorFieldNotSetAlert;
			?>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="create-account-form">
				<div class="form-row">
					<div class="form-group col-md-6">
			    		<label for="nom">Nom</label>
			    		<input type="text" class="form-control" placeholder="Entrez votre nom" name="nom" id="nom" aria-describedby="nomError" value="<?php echo $nom; ?>">
			    		<small id="nomError" class="form-text text-danger" role="alert"></small>
			  		</div>
			  		<div class="form-group col-md-6">
			    		<label for="prenom">Prénom</label>
			    		<input type="text" class="form-control" placeholder="Entrez votre prénom" name="prenom" id="prenom" aria-describedby="prenomError" value="<?php echo $prenom; ?>">
			    		<small id="prenomError" class="form-text text-danger" role="alert"></small>
			  		</div>
			  	</div>

			  	<div class="form-group">
			    	<label for="pseudo">Pseudo</label>
			    	<input type="text" class="form-control" placeholder="Entrez votre pseudo" name="pseudo" id="pseudo" aria-describedby="pseudoError" value="<?php echo $pseudo; ?>" oninput="testPseudo(this)">
			    	<small id="pseudoError" class="form-text text-danger" role="alert"><?php echo $pseudoError; ?></small>
			  	</div>
			  	<div class="form-group">
			    	<label for="email">E-mail</label>
			    	<input type="email" class="form-control" placeholder="Entrez votre adresse e-mail" name="email" id="email" aria-describedby="emailError" value="<?php echo $email; ?>">
			    	<small id="emailError" class="form-text text-danger" role="alert"><?php echo $emailError; ?></small>
			  	</div>
			  	<div class="form-group">
			    	<label for="password">Mot de passe</label>
			    	<input type="password" class="form-control" placeholder="Entrez votre mot de passe" name="password" id="password" aria-describedby="passwordHelpBlock passwordError">
			    	<small id="passwordHelpBlock" class="form-text text-muted">Votre mot de passe doit comporter 8 caractères au minimum.</small>
			    	<small id="passwordError" class="form-text text-danger"></small>
			  	</div>
			  	<div class="form-group">
			    	<label for="confirm-password">Confirmer le mot de passe</label>
			    	<input type="password" class="form-control" placeholder="Confirmez votre mot de passe" name="confirm-password" id="confirm-password" aria-describedby="confirmPasswordError">
			    	<small id="confirmPasswordError" class="form-text text-danger"><?php echo $confirmPasswordError ?></small>
			  	</div>
			  		<button type="submit" class="btn btn-primary">Inscription</button>
			</form>
		</div>

		<script type="text/javascript">
			let httpRequest = new XMLHttpRequest();
			function testPseudo(field) {
				if(!httpRequest) {
					alert('Erreur : impossible de créer une instance XMLHTTP.');
					return false;
				}

				httpRequest.onreadystatechange = displayContents;
			    httpRequest.open('POST', 'inscription_ajax.php');
			    httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			    httpRequest.send('pseudo=' + encodeURIComponent(field.value));
			}

			function displayContents() {
			    if (httpRequest.readyState === XMLHttpRequest.DONE) {
			    	if (httpRequest.status === 200) {
			      		var response = JSON.parse(httpRequest.responseText);
			      		if(response.pseudoExist) {
			      			document.getElementById('pseudoError').innerHTML = "Ce pseudo existe déjà. Vous devez en choisir un autre.";
			      		}
			      		else {
			      			document.getElementById('pseudoError').innerHTML = "";
			      		}
			    	} 
			    	else {
			      		alert('Un problème est survenu avec la requête.');
			    	}
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