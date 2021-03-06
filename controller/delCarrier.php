<?php 
session_start();
if(isset($_SESSION)){
	if($_SESSION['flag'] !== true){
		$template='connexion';
		header('Location:../controller/connexion.php');
	}
}
include'../class/file.openClass.php'; 
include'../model/saveAfterDelete.php';

$filename = $_SERVER['DOCUMENT_ROOT'].'/fichiers/userLog'.$_SESSION['iduser'].'/listeDesTransporteurs.csv';
$open = new FileOpenClass($filename); 
$collection = $open->fileToArray(); // tableau des éléments affichés
if(isset($_POST['val'])){
	$tabdelete = ($_POST['val'][0]); // tableau des éléments à supprimer
	if (!empty($_POST['val'])){ 
		try {
			$tab=[];
			$id = ceil($_SESSION['iduser']);
			foreach ($tabdelete as $key=>$items){
				foreach ($collection as $index=>$value){
					$fileassoc = $value[6];
					if(in_array($value[6],$items)){
						unset($collection[$index]);
						if (file_exists($_SERVER['DOCUMENT_ROOT'].'/fichiers/Datacarrier/user'.$id.'/'.$fileassoc)){
							unlink($_SERVER['DOCUMENT_ROOT'].'/fichiers/Datacarrier/user'.$id.'/'.$fileassoc);
						}
						$open->saveArrayToFile($collection);
						$tab = $open->fileToArray();
					}
				}
			}

			if(saveData($tab,$id)){
				echo json_encode(["result"=>true,"message"=>"Suppression effectuée avec succès...et modifications enregistrées"]);
				exit();
			}else{
				echo json_encode(["result"=>true,"message"=>"Suppression effectuée avec succès...mais les modifications n'ont pas été enregistrées"]);
				exit();
			}
			
		} catch (Exception $e) {
			echo json_encode(["result"=>false,"message"=>"La suppression a échouée !!!"]);
			exit();
		}
	}
}

$template = 'delCarrier';
include '../view/layout.phtml';
exit();


?>