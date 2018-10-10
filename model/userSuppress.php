<?php 
session_start();
$id=$_SESSION['iduser'];
function suppress(){
	include'../model/bddConnexion.php';
	$bdd;
	$rest = $bdd->query('SELECT * FROM `user`');
	$rest->execute();
	$arr = $rest->fetchAll(PDO::FETCH_ASSOC);
	return $arr;
}

function destroyData($array){
	include'../model/bddConnexion.php';
	$bdd;
	$i = 0;
	foreach($array as $key=>$value){
		$a = $value[0];
		$req = $bdd->query('DELETE FROM `user` WHERE `iduser`='.$a);
		$val = $req->execute();
		if($val){
			$i++;
		}
	}

	return $i;
}
function verify($myid){
	include'../model/bddConnexion.php';
	$bdd;
	$res = $bdd->query('SELECT * FROM `user` WHERE `iduser`='.$myid);
	$result = $res->execute();
	$result = $res->fetch(PDO::FETCH_ASSOC);
	$tab=array();
	if($tab==$result){ 
		return true;
	}
}

?>
