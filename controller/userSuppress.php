<?php 
if(isset($_SESSION)){
	if($_SESSION['flag'] !== true){
		$template='connexion';
		header('Location:../controller/connexion.php');
	}
}

//On charge le tableau des users enregistrés
include'../model/userSuppress.php';
$mytab = suppress();

//Si on est en mode post on procede a la suppresion des utilisateurs enredistrés
if(isset($_POST) && !empty($_POST)){
	$tabsup = $_POST['val'];
	$count = count($tabsup);

	//Destruction des données dans la base de données
	$val = destroyData($tabsup);

//Destruction des dossiers associés
	$filepath = $_SERVER['DOCUMENT_ROOT'].'/fichiers/userLog'.$_SESSION['iduser'];
	$filename = $_SERVER['DOCUMENT_ROOT'].'/fichiers/userLog'.$_SESSION['iduser'].'/listeDesTransporteurs.csv';
	$fileassoc=$_SERVER['DOCUMENT_ROOT'].'/fichiers/Datacarrier/user';

//Suppression du csv user log
	if(file_exists($filename)){
		unlink($filename);
	}
	rmdir($filepath);

//Suppression des fichiers user associés
	foreach ($tabsup as $value) {
		$dossier = $fileassoc.$value[0];
		if(file_exists($dossier)){
			$files = array_diff(scandir($dossier), array('.','..'));
			foreach ($files as $file) {
				(is_dir("$dossier/$file")) ? delTree("$dossier/$file") : unlink("$dossier/$file");
			}
			rmdir($dossier);
		}
	} 

	//Si le compte sur lequel on se trouve à été supprimé retour a l'index
	if($count = $val ){
		$myid = $_SESSION['iduser'];
		if(verify($myid)){
			$_SESSION=[];
			session_destroy();
			$template='index';
			echo json_encode(['result'=>'reload','message'=>'--Votre compte à été supprimé...Vous allez être rediriger vers la page d\'accueil']);
			exit();
		}
		echo json_encode(['result'=>true,'count'=>$val,'message'=>'--Suppession de compte réussi !!!']);
		exit();
	}else{
		echo json_encode(['result'=>false,'count'=>$val,'message'=>'--Echec suppression nule ou partielle...Réessayez']);
		exit();
	}
}



$template = 'userSuppress';
include'../view/layout.phtml';
exit();
?>