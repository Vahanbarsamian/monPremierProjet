<?php 
session_start();
if(isset($_SESSION)){
	if($_SESSION['flag'] !== true){
		$template='connexion';
		header('Location:../controller/connexion.php');
	}
}

$iduser=ceil($_SESSION['iduser']);
// Adresse du chemin temporaire du fichier
$filedirectory = $_FILES["file"]["tmp_name"];

// Nom de référence créé par file avant upload
/*$name = basename($_FILES["file"]["tmp_name"]);*/

// Nom du fichier tel qu'il apparaît dans input file (la valeur name du input html doit etre le meme que celle de $_FILES['name'][...] )
$name = $_FILES["file"]["name"];

//Chemin du dossier
$path = "../fichiers/Datacarrier/user".$iduser;
if(!file_exists($path)) mkdir($path, 0755, true);

// Adresse de destination + nom du fichier
$location = $path . "/" . $name;

// Nom du fichier a traiter dans le fichier csv;
$files = str_replace("_", " ", key($_POST));

//On vérifie que le fichier n'exite pas déjà...Sinon on efface le csv associé.
require'../class/file.openClass.php';
if(file_exists($location)){
	$tab=$_FILES;
	$tab['result']=false;
	$tab['fileError'] = "Le fichier à uploader existe déja dans le dossier de reception...Echec de l'upload !...Chosissez un autre fichier merci.";
	$filename = $_SERVER['DOCUMENT_ROOT'].'/fichiers/userLog'.$_SESSION['iduser'].'/listeDesTransporteurs.csv';
	$open = new FileOpenClass($filename);
	$mytab = $open->fileToArray();
	foreach($mytab  as $numero => $arr){ 
		if ($arr[1] == $files){
			unset($mytab[$numero]);
			break;
		}
	}
	$open->saveArrayToFile($mytab);
} else {

// Upload du fichier
	if(move_uploaded_file($filedirectory,$location)){
		$tab = $_FILES;
		$tab['result']=true;
		$tab['fileError'] = "Le fichier a été uploader avec succès...";
		$filename = $_SERVER['DOCUMENT_ROOT'].'/fichiers/userLog'.$_SESSION['iduser'].'/listeDesTransporteurs.csv';
		$open = new FileOpenClass($filename);
		$mytab = $open->fileToArray();
		$name = $tab['file']['name'];
		foreach($mytab  as $numero => $arr){ 
			if ($arr[1] == $files){
				array_push($mytab[$numero],$name);
				break;
			}
		}
		$open->saveArrayToFile($mytab);

	} else {
		$tab['result']=false;
		$tab['fileError'] = "Echec de l'upload !...un problème est survenu pendant l'upload...veuillez réessayer merci.";
		$filename = $_SERVER['DOCUMENT_ROOT'].'/fichiers/userLog'.$_SESSION['iduser'].'/listeDesTransporteurs.csv';
		$open = new FileOpenClass($filename);
		$mytab = $open->fileToArray();
		foreach($mytab  as $numero => $arr){ 
			if ($arr[1] == $files){
				unset($mytab[$numero]);
				break;
			}
		}
		$open->saveArrayToFile($mytab);

	}
}

echo json_encode($tab);

?>