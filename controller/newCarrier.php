<?php 
session_start();
if(isset($_SESSION)){
	if($_SESSION['flag'] !== true){
		$template='connexion';
		header('Location:../controller/connexion.php');
	}
}

// On initialise le tableau en vidant ses données
$tab = array('id'=>'','lastname'=>'','firstname'=>'','email'=>'','carrierName'=>'','type'=>'','file'=>'','pathInfo'=>'','ext'=>'','fileError'=>'','result'=>false);

// Si on envoi le formulaire on vérifie ses données.

if (isset($_POST['newCarrier'])){
	if(!empty($_POST)){
		if($_POST['newCarrier']=='first'){
			$tab['id'] = htmlspecialchars($_SESSION['iduser']);
			$tab['lastname'] = htmlspecialchars($_SESSION['lastname']);
			$tab['firstname'] = htmlspecialchars($_SESSION['firstname']);
			$tab['email'] = htmlspecialchars($_SESSION['email']);
			$tab['carrierName'] = $_POST['nom'];
			$tab['type'] = ceil(htmlspecialchars($_POST['type']));
			$tab['file'] = htmlspecialchars($_POST['file']);
			$tab['size'] = htmlspecialchars($_POST['size']);
			$tab['pathInfo'] = pathinfo($_POST['file']);
			$tab['ext'] = $tab['file']? strtolower($tab['pathInfo']['extension']) : "";
			$tab['fileError'] = '';
			$tab['date'] = '';
			$tab['gazole']='';
			$tab['result'] = true;
			if(empty($tab['carrierName'])){
				$tab['carrierName'] = "DNC".date("Y-m-d H:i:s");
				$tab['date'] = date("Y-m-d H:i:s");
			} else {
				$tab['date'] = date("Y-m-d H:i:s");
			}

			switch ($tab['type']) {
				case 0:
				case 2:
				$tab['type']='Frêt';
				break;
				case 1:
				$tab['type']='Messagerie';
				break;
				case 3:
				$tab['type']='Affrètement';
				break;
			}

			if ($tab['pathInfo']['extension']==''){
				$tab['fileError'] = "Vous devez inclure un fichier .CSV afin d'importer des données";
				$tab['result'] = false;
			} elseif($tab['pathInfo']['extension'] != "csv"){
				$tab['fileError'] = "L'extension du fichier n'est pas au format 'csv'";
				$tab['result'] = false;
			}

			if($tab['pathInfo']['extension'] =="csv" && !$tab['size'] > 0){ 
				$tab['fileError'] = "Le fichier est vide !... intégration des données impossible";
				$tab['result'] = false;
			}

// Si toutes les conditions sont remplies on upload le fichier csv et on rajoute le transporteur a la liste en cours
			require'../class/file.openClass.php'; 
			$filename = $_SERVER['DOCUMENT_ROOT'].'/fichiers/userLog'.$_SESSION['iduser'].'/listeDesTransporteurs.csv';
			$open = new FileOpenClass($filename); 
			$myarray = $open->fileToArray();
			$myvalue = $tab['carrierName'];
			foreach($myarray as $array){
				if(is_array($array)){
					if(in_array($myvalue,$array)){
						$tab['result']=false;
						$tab['fileError'] = "Le nom du transporteur existe déja !... veuillez le modifier svp.";
						break;
					} 
				}
			}
		}
		if($tab['result'] == true){ 
			$newcarrier = array($tab['id'],$tab['carrierName'],$tab['type'],$tab['size'],$tab['gazole'],$tab['date']);
			$uploadName = basename($tab['file']);
			$open->saveOneToFile($newcarrier);
			include'../model/newCarrierDataSave.php';
		}

	}

	echo json_encode($tab);
	exit();

} else {
	$template = 'newCarrier';
	include '../view/layout.phtml';
	exit();
}


?>