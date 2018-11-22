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
$iduser=ceil($_SESSION['iduser']);
$dossier = "../fichiers/Datacarrier/user".$iduser;

if (!empty($dep) && !empty($colis) && !empty($poids)){
	include_once '../class/file.openClass.php';
	if($listdirectory = opendir($_SERVER['DOCUMENT_ROOT'].'/fichiers/Datacarrier/user'.$_SESSION['iduser'])){
		while(false !== ($fichier = readdir($listdirectory))){

		}
		$fichier = array_diff(scandir($dossier), array('.','..'));
		$filename = $_SERVER['DOCUMENT_ROOT'].'/fichiers/Datacarrier/user'.$_SESSION['iduser'].'/listeDesTransporteurs.csv';
		var_dump($fichier,count($fichier),$filename);
		$open = new FileOpenClass($filename); 
		$mesFichiers = 
		// Vérification des données
		
	}

}
echo json_encode(['result'=>true]);




?>