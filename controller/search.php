<?php 
session_start();
// On recupère les données du Post sous forme de string
$str = $_POST['param'];

// On transforme le string en tableau
parse_str($str,$param);

// Stockage des valeurs du tableau dans des variables
$dep = htmlspecialchars($param['dep']);
$colis = htmlspecialchars($param['colis']);
$type = htmlspecialchars($param['type']);
$poids = htmlspecialchars($param['poids']);
$delai = htmlspecialchars($param['delai']);

// Valeur de l'id et du dossier des fichiers
$messagerie = true;
$iduser=ceil($_SESSION['iduser']);
$dossier = "../fichiers/Datacarrier/user".$iduser;

if (!empty($dep) && !empty($colis) && !empty($poids)){
	include_once '../class/file.openClass.php';
	if($listdirectory = opendir($_SERVER['DOCUMENT_ROOT'].'/fichiers/Datacarrier/user'.$_SESSION['iduser'])){
		while(false !== ($fichier = readdir($listdirectory))){

		}
	}
	$fichier = array_diff(scandir($dossier), array('.','..'));
	$filename = $_SERVER['DOCUMENT_ROOT'].'/fichiers/userLog'.$_SESSION['iduser'].'/listeDesTransporteurs.csv';
	$open = new FileOpenClass($filename); 
	$mesFichiers = $open->fileToArray($filename);

		// Vérification des données et choix des transporteurs en fonctions des données
	if (($poids / $colis)>30 || $type == 1){
		$messagerie = false;
	} 
	foreach($mesFichiers as $value){
		foreach($fichier as $name){
			$tableauDeValeurs = [];
			if(in_array($name,$value)){
				if(($messagerie == false) && ($value[2] == "Messagerie")){
					continue;
				}else{
					var_dump($value,$messagerie,$value[2]);	
					
				}
			}
		}
	}
}
echo json_encode(['result'=>true]);




?>