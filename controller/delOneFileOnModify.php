<?php 
session_start();
if(isset($_SESSION)){
	if($_SESSION['flag'] !== true){
		$template='connexion';
		header('Location:../controller/connexion.php');
	}
}

if(isset($_POST) && !empty($_POST)){
	$iduser=htmlspecialchars($_SESSION['iduser']);
	$file=$_POST['value'];
	$pathFile = "../fichiers/Datacarrier/user$iduser/$file";
	$pathUser = "../fichiers/userLog$iduser/listeDesTransporteurs.csv";
	include'../model/modifyDataCarrier.php';
	if(file_exists($pathFile)){
		$result = delete($iduser,$file);
		if($result){
			include'../class/file.openClass.php';
			$open=new FileOpenClass($pathUser);
			$tab = $open->fileToArray($pathUser);
			foreach($tab as $key=>$value){
				if($file==$value[6]){ 
					$value[6]="";
					$tab[$key] = $value;
				}
			}
			$open->saveArrayToFile($tab);
			if(!$file) {json_encode(['result'=>false]);exit();}
			echo unlink($pathFile)?  json_encode(['result'=>true]) : json_encode(['result'=>false]);
			exit();
		}
	}else {
		echo json_encode(['result'=>false]);
		exit();
	}
}

?>