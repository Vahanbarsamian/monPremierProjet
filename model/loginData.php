	<?php 
	function connection($email,$pass){
		try{
			require'../model/bddConnexion.php';
			$bdd;
			$query = $bdd->prepare('Select iduser FROM user WHERE email=? AND password=?');
			$query->execute(array($email,$pass)); 
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res){
				echo json_encode(['result'=>true]);
				die();
			}else{
				echo json_encode(['result'=>'Mot de passe ou email incorrect']);
				die();
			}

		} catch(Exception $e){
			echo json_encode(['result'=>'Impossible de se connecter a la base de données']);
			
		}
	}

	if(array_key_exists('email',$_POST)){
		$email = htmlspecialchars($_POST['email']);
		$pass = crypt(htmlspecialchars($_POST['pass']),'$2y$14$wHhBmAgOMZEld9iJtV./aq');
		connection($email,$pass);
	}else{
		echo json_encode(["result"=>'Email incorrect']);
		die();
	}

	?>