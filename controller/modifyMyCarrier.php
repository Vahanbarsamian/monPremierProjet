<?php 
session_start();

include'../class/file.openClass.php';
include'../model/modifyDataCarrier.php';

// Si GET : définition des vriables d'affichage
if($_GET){
	$userlog = $_SERVER['DOCUMENT_ROOT'].'/fichiers/userLog'.$_SESSION['iduser'].'/listeDesTransporteurs.csv';
	$name = htmlspecialchars($_GET['name']);
	$files = htmlspecialchars($_GET['files']);
	$type = htmlspecialchars($_GET['type']);
	$open = new FileOpenClass($userlog);
	$myvalue = $open->fileToArray();
	foreach ($myvalue as $key=>$value) {
		if(in_array($name,$value)) $index = $key;
	}
	$_SESSION['index']=$index;
	$_SESSION['files']=$files;
}

if($_POST){

	//Définition des variables POST
	$id = $_SESSION['iduser'];
	$name = htmlspecialchars($_POST['nom']);
	switch($_POST['type']) {
		case '1':
		$type ="Messagerie";
		break;
		case '2':
		$type = "Frêt";
		break;
		case '3':
		$type = "Affrètement";
		break;
	}
	$files=htmlspecialchars($_FILES['file']['name']);
	if(empty($files)) $files=$_SESSION['files'];

// Definition des chemin des dossiers cibles
	$userlog = $_SERVER['DOCUMENT_ROOT'].'/fichiers/userLog'.$_SESSION['iduser'].'/listeDesTransporteurs.csv';
	$pathDirectory = $_SERVER['DOCUMENT_ROOT'].'/fichiers/Datacarrier/user'.$_SESSION['iduser'].'/'.$files;

// Mise à jour des valeurs du CSV liste des transporteurs
	$open = new FileOpenClass($userlog);
	$myvalue = $open->fileToArray();
	foreach ($myvalue as $key=>$value) {
		if(in_array($name,$value)){
			$index = $key;
		}else{
			$index = $_SESSION['index'];
		}
	}
	
	if(!file_exists($pathDirectory) && $files=htmlspecialchars($_FILES['file']['name'])){
		if($_SESSION['files'] && !$_SESSION['files']='aucun...'){
			var_dump($_SERVER['DOCUMENT_ROOT'].'/fichiers/Datacarrier/user'.$_SESSION['iduser'].'/'.$_SESSION['files']);
			unlink($_SERVER['DOCUMENT_ROOT'].'/fichiers/Datacarrier/user'.$_SESSION['iduser'].'/'.$_SESSION['files']);
		}
		if (move_uploaded_file($_FILES['file']['tmp_name'], $pathDirectory)) {
			$myvalue[$index][1]=$name;
			$myvalue[$index][2]=$type;
			$myvalue[$index][6]=$files;
			$open->saveArrayToFile($myvalue);
			deleteAll();
			foreach ($myvalue as $value) {
				if(empty($value[4])) $value[4] = 0;
				insert($value[0],$value[1],$value[2],$value[3],$value[4],$value[5],$value[6]);
			}
			echo json_encode(['result'=>true,'message'=>"Le fichier ".$files." a été uploadé avec succès et la mise à jour effectuée"]);
			exit();
		}else{
			echo json_encode(['result'=>false,'message'=>"Echec... le fichier" .$files. " n'a été uploadé...et la mise a jour à échouée."]);
			exit();
		}
	}else{
		$myvalue[$index][1]=$name;
		$myvalue[$index][2]=$type;
		if(!$files){
			$myvalue[$index][6]=$_SESSION['files'];
		}else{
			$myvalue[$index][6]=$files;
		}
	//Réécriture des valeurs dans le CSV liste des transporteurs
		$open->saveArrayToFile($myvalue);
		deleteAll();
		foreach ($myvalue as $value) {
			if(empty($value[4])) $value[4] = 0;
			insert($value[0],$value[1],$value[2],$value[3],$value[4],$value[5],$value[6]);
		}
		echo json_encode(['result'=>'partial','message'=>"Mise à jour effectuée avec success mais le fichier ".$files." n'a pas été modifié<br>Afin de le modifier veuillez effacer le fichier actuel avant d'en associer un nouveau"]);
		exit();
	}

}else{
	$template="modifyMyCarrier";
	include'../view/layout.phtml';
}
?>