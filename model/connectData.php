	<?php 
	function connection($email){
		try{
			require'../model/bddConnexion.php';
			$bdd;
			$query = $bdd->prepare('Select * FROM user WHERE email=?');
			$query->execute(array($email)); 
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res){
				echo json_encode(['result'=>false]);
				die();
			} else {
				$firstname = utf8_encode(ucfirst(strtolower($_POST['firstName'])));
				$lastname = strtoupper($_POST['lastName']);
				$email = strtolower($_POST['email']);
				$pass = crypt($_POST['pass'],'$2y$14$wHhBmAgOMZEld9iJtV./aq');
				$bdd;
				$query = $bdd->prepare('INSERT INTO `user`(`firstname`, `lastname`, `email`, `password`) VALUES (?,?,?,?)');
				$query->execute(array($firstname,$lastname,$email,$pass));
				echo json_encode(['result'=>true]);
				die();
			}
			// Création de la base de donnée si inexistante
		} catch(Exception $e){
			$email=$_POST['email'];

			$pdo = new PDO("$DB_TYPE:host=$DB_HOST", $DB_USER, $DB_PASS);

			$requete = "CREATE DATABASE IF NOT EXISTS `myfirstproject` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
			$pdo->prepare($requete)->execute();
			$requete = null;

			// Création de la table user pour cette même table

			$connexion = new PDO("$DB_TYPE:host=$DB_HOST;dbname=$DB_NAME;charset=utf8", $DB_USER, $DB_PASS);
			$req = "CREATE TABLE IF NOT EXISTS $DB_NAME.`user` (
			`iduser`  SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`firstname` VARCHAR(30) NOT NULL,
			`lastname` VARCHAR(30) NOT NULL,
			`email` VARCHAR(40) NOT NULL,
			`password` VARCHAR(60) NOT NULL,
			`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP	
			)
			ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci" ;
			$connexion->prepare($req)->execute();
			connection($email);
		}
	}

	if(array_key_exists('email',$_POST)){
		$email = htmlspecialchars($_POST['email']);
		connection($email);
	}else{
		$email = false;
		echo json_encode($result['email']=$email);
		die();
	}










	?>