	<?php 
	header("Content-Type: text/html; charset=utf-8");
	// Fonction nettoyage des inputs
	function cleanform($var){
		$var = trim($var, " \t\n\r\0\x0B");
		$var = stripslashes($var);
		$var = htmlspecialchars($var);
		return $var;

	}

	// Fonction de vérication du mail
	function isEmail($val){
		return (empty($val)|| !preg_match ( " /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ " , $val ));
	}

	// Initialisation du tableau
	$tab = array("firstName"=>"","lastName"=>"","email"=>"","pass"=>"","firstnameError"=>"","lastnameError"=>"","emailError"=>"","passwordError"=>"","isSuccess"=>false);

	// On determine quel formulaire a été rempli et envoyé et on traite les données.
	if(isset($_POST['newUser'])){
		if(!empty($_POST)){
			$tab['firstName'] = utf8_encode(ucfirst(strtolower(cleanform($_POST['firstName']))));
			$tab['lastName'] = strtoupper(cleanform($_POST['lastName']));
			$tab['email'] = strtolower(cleanform($_POST['email']));
			$tab['pass'] = (!empty($_POST['pass'])?crypt(cleanform($_POST['pass']),'$2y$14$wHhBmAgOMZEld9iJtV./aq'):"");
			$tab['passConfirm'] = (!empty($_POST['passConfirm'])?crypt(cleanform($_POST['passConfirm']),'$2y$14$wHhBmAgOMZEld9iJtV./aq'):"");
			$tab['isSuccess'] = true;

			if($tab['pass'] !== $tab['passConfirm']){
				$tab['passwordError'] = 'Le mot de passe de confirmation ne correspond  pas au mot de passe saisi. Merci de corriger';
				$tab['isSuccess'] = false;
			}elseif(empty($tab['pass']) || empty($tab['passConfirm'])){ 
				$tab['passwordError'] = 'Les champs mots de passe et vérification mot de passe doivent être renseignés !!';
				$tab['isSuccess'] = false;
			}

			if(isEmail($tab['email']) && !empty($tab['email'])){
				$tab['emailError'] = 'Le mail entré n\'est pas valide ! Merci de corriger : (';
				$tab['isSuccess'] = false;
			}elseif(empty($tab['email'])){
				$tab['emailError'] = 'Le champ email est vide ! Merci de mettre un email valide';
				$tab['isSuccess'] = false;
			}


			if(is_numeric($tab['firstName']) || empty($tab['firstName'])){
				if(is_numeric($tab['firstName'])){
					$tab['firstnameError'] = 'Mauvais format de saisie du prénom: que des lettres : (';
					$tab['isSuccess'] = false;
				} elseif(empty($tab['firstName'])){
					$tab['firstnameError'] = 'Veuillez saisir un prénom. Merci';
					$tab['isSuccess'] = false;
				}
			}

			if(is_numeric($tab['lastName']) || empty($tab['lastName'])){
				if(is_numeric($tab['lastName'])){
					$tab['lastnameError'] = 'Mauvais format de saisie du nom: que des lettres : (';
					$tab['isSuccess'] = false;
				} elseif(empty($tab['lastName'])){
					$tab['lastnameError'] = 'Veuillez saisir un nom. Merci';
					$tab['isSuccess'] = false;
				}
			}
			echo json_encode($tab);
			exit();

		} else {
			$template = 'connexion';
			include '../view/layout.phtml';
			exit();
		}
	}

#  Sinon connexion au formulaire login

	elseif (isset($_POST['log'])){
		if(!empty($_POST)){
			$tab['email'] = strtolower(cleanform($_POST['email']));
			$tab['pass'] = (!empty($_POST['pass'])?crypt(cleanform($_POST['pass']),'$2y$14$wHhBmAgOMZEld9iJtV./aq'):"");
			$tab['isSuccess'] = true;
			if(isEmail($tab['email']) && !empty($tab['email'])){
				$tab['emailError'] = 'Le mail entré n\'est pas valide ! Merci de corriger : (';
				$tab['isSuccess'] = false;

			}elseif(empty($tab['email'])){
				$tab['emailError'] = 'Le champ email est vide ! Merci de mettre un email valide';
				$tab['isSuccess'] = false;
			}

			if(empty($tab['pass'])){ 
				$tab['passwordError'] = 'Le champ mot de passe doit être renseigné. Merci.';
				$tab['isSuccess'] = false;
			}

			if($tab['isSuccess']==true){ 
				echo json_encode($tab);
				exit();
			} else {
				echo json_encode($tab);
				exit();
			}	
		}
	} else {
		$template = 'connexion';
		include '../view/layout.phtml';
		exit();
	} 

	?>