<?php 
session_start();

include'../class/file.openClass.php'; 
$filename = $_SERVER['DOCUMENT_ROOT'].'/fichiers/listeDesTransporteurs.csv';


//Si on arrive sur la page via le formulaire de connexion

if(!isset($_SESSION)  || isset($_POST['log'])){ 
	include'../model/sessionData.php';
	$_SESSION['flag'] = true;
	$email = htmlspecialchars($_POST['email']);
	$pass = crypt(htmlspecialchars($_POST['pass']),'$2y$14$wHhBmAgOMZEld9iJtV./aq');
	$val = isSubscribe($email,$pass);

	foreach ($val as $key=>$value){
		$_SESSION[$key]=$value;
	}
	
	include'../model/loadDataUser.php';
	$fileassoc = $_SERVER['DOCUMENT_ROOT'].'/fichiers/Datacarrier/user'.$id['iduser'];
	if(file_exists($filename)){
		unlink($filename);
		$open = new FileOpenClass($filename);
		$open->saveArrayToFile($result);
	}else{
		$open = new FileOpenClass($filename);
		$open->createFile();
		if(file_exists($fileassoc)){
			unlink($fileassoc);
		}
	}
	$template='application';
	include'../view/layout.phtml';
	exit();

//Si on revient sur la page via la session deja ouverte

} else if(isset($_SESSION) && !isset($_POST['log'])){
	if(isset($_SESSION['email'])){ 
		$template='application';
		include'../view/layout.phtml';
		exit();
	} else {
		$template='connexion';
		header('Location:../controller/connexion.php');
		exit();
	}

} else {
	$template='connexion';
	header('Location:../controller/connexion.php');
	exit();
}	
?>