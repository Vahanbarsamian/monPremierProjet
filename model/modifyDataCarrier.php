<?php 
function delete($id,$name){ 
	include'../model/bddConnexion.php';
	$bdd;
	$req="UPDATE `mycarrierslist` SET `fileassosciate`='' WHERE `idlist`=? and `fileassosciate`=?";
	$req=$bdd->prepare($req);
	$resultat = $req->execute([$id,$name]);
	return $resultat;
}

function deleteAll(){ 
	include'../model/bddConnexion.php';
	$bdd;
	$req="TRUNCATE `mycarrierslist` ";
	$req=$bdd->prepare($req);
	$resultat = $req->execute();
	return $resultat;
}

function insert($id,$name,$type,$size,$taxe,$date,$files){ 
	include'../model/bddConnexion.php';
	$bdd;
	$req="INSERT INTO `mycarrierslist`(`idlist`, `carriername`, `type`, `filesize`, `gazoletaxe`, `date`, `fileassosciate`) VALUES (?,?,?,?,?,?,?)";
	$req=$bdd->prepare($req);
	$resultat = $req->execute([$id,$name,$type,$size,$taxe,$date,$files]);
	return $resultat;
}





?>