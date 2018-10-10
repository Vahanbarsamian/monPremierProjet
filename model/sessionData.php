<?php 
function isSubscribe($email,$pass){
	require'../model/bddConnexion.php';
	$bdd; 
	$req = ('Select `iduser`,`firstname`,`lastname`,`email`,`created` FROM user WHERE email=? AND password=?');
	$query = $bdd->prepare($req);
	$query->execute([$email,$pass]);
	$request = $query->fetch(PDO::FETCH_ASSOC);
	if ($request){
		return $request;
	} else{
		return false;
	}
}



?>