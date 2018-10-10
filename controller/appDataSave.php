<?php 
session_start();
if(isset($_SESSION)){
	if($_SESSION['flag'] !== true){
		$template='connexion';
		header('Location:../controller/connexion.php');
	}
}

require'../class/file.openClass.php';
$filename = $_SERVER['DOCUMENT_ROOT'].'/fichiers/listeDesTransporteurs.csv';
$open = new FileOpenClass($filename); 
$tab = $open->fileToArray();
$gazole = $_POST;
try {
	if(count($tab) > 0 || count($gazole) > 0){ 
		$id = intval($_SESSION['iduser']);
		foreach ($tab as $index=>$value){
			if($id==$value[0]){
				$tab[$index][4]=$gazole['val'][$index];
			}
		}
		$open->saveArrayToFile($tab);
		require'../model/save.php';
		echo saveData($tab,$id);

	}else{
		echo json_encode(['result'=>false,'message'=>'Aucune modification à apporter<br>Sauvegarde annulée !!.']);
		exit();
	}
}
catch (Exception $e){
	echo json_encode(['result'=>false,'message'=>'Une erreur inconnue est survenue<br>La sauvegarde à été annulée !!']);
	exit();
}


?>