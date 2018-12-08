<?php 
session_start();

// On recupère les données du Post sous forme de string
$str = $_POST['param'];

// On transforme le string en tableau
parse_str($str,$param);

// Initialisation du tableau qui servira à récuperer les filtres 
$result=[];

// Constanate qui défini le coeff multiplicateur pour chaque colis superieur a 30 kg en messagerie
const colisSupp = 1.15;
// Constante qui défini le poids max autorisé en Frêt ( à definir avec les prestataires )
const poidsMaxFret = 500;

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

// Si les champs ne sont pas remplis on ne lance pas la recherche.Sinon...
if (!empty($dep) && !empty($colis) && !empty($poids)){
	include_once '../class/file.openClass.php';

	// On récupère la liste des dossiers associés 
	if($listdirectory = opendir($_SERVER['DOCUMENT_ROOT'].'/fichiers/Datacarrier/user'.$_SESSION['iduser'])){
		while(false !== ($fichier = readdir($listdirectory))){

		}
	}

	// On crée un tableau en éliminant les données inutiles
	$fichier = array_diff(scandir($dossier), array('.','..'));
	$filename = $_SERVER['DOCUMENT_ROOT'].'/fichiers/userLog'.$_SESSION['iduser'].'/listeDesTransporteurs.csv';
	$open = new FileOpenClass($filename); 
	$mesFichiers = $open->fileToArray($filename);
	// Vérification des données et choix des transporteurs en fonctions des données...

	// Si le poids divisé par le nombre de colis est > 30 kg alors on exclue la messagerie car 30 kg max/colis
	if (!is_numeric($colis)) {$colis=5;}
	if (($poids / $colis)>30 || $type == 1){
		$messagerie = false;
	} 

	// Boucle pour récuperer les valeurs des fichiers associés
	$tableauDeValeurs = [];
	foreach($mesFichiers as $value){
		foreach($fichier as $name){
			if(in_array($name,$value)){
				// Si poids > 30Kg on exclue la messagerie
				if(($messagerie == false) && ($value[2] == "Messagerie")){
					continue;
				}else{
					$val= array_merge($open->fileToArray( $_SERVER['DOCUMENT_ROOT']."/fichiers/Datacarrier/user".$_SESSION['iduser']."/".$value[6]));
					
					// Création du tableau qui regroupe l'ensemble des données avant tri
					for ($i=4;$i<(count($val)-3);$i++){
						if($val[$i][0]==$dep){
							$val['dep'] = $val[$i];
							$val['dep'][0]='Prix';
						}
					}
					if(utf8_decode("Frêt")==$val[0][1] || utf8_decode("Affrêtement")==$val[0][1]){
						array_push($tableauDeValeurs,array("Name"=>$value[1],$val[0][0]=>$val[0][1],$val[1][0]=>$val[1][1],$val[2],$val[3][0]=>$dep,$val['dep']));
					}else{
						array_push($tableauDeValeurs,array("Name"=>$value[1],$val[0][0]=>$val[0][1],$val[1][0]=>$val[1][1],$val[2],$val[3]));
					}
				}
			}
		}
	}
	
	// Tri des données pour trouver le transporteur le moins cher
	
		// On récupère l'indice de valeur du poids correspondant
	$arrayIndex = $indexLst = [];
	foreach ($tableauDeValeurs as $key=>$monpoids) {
		if($poids <= poidsMaxFret){
			if ($monpoids['Type'] == "Messagerie" && ($poids/$colis) <= 30){
				if($poids >= 30){
					for($i=1;$i<count($monpoids[0]);$i++){
						if($monpoids[0][$i] == 30){
							$indexLst=[];
							array_push($indexLst,$i);
							break;
						} else {
							if($monpoids[0][$i] >=$poids){
								$indexLst=[];
								array_push($indexLst,$i);
								break;
							}
						}
					}
				}
			}else{
				for($i=1;$i<count($monpoids[0]);$i++){
					if($monpoids[0][$i]>=$poids){
						$indexLst=[];
						array_push($indexLst,$i);
						break;
					}
				}
			}
		}else{
			if ($monpoids['Type'] == utf8_decode("Affrêtement")){
				for($i=1;$i<count($monpoids[0]);$i++){
					if($monpoids[0][$i] >= $poids){
						$indexLst=[];
						array_push($indexLst,$i);
						break;
					}
				}
			}
		}
		array_push($arrayIndex,$indexLst);
	}
		// On récupère le prix grace a l'index récuperé precedemment
	$price = $newarray = [];
	foreach ($tableauDeValeurs as $cle=>$valeur) {
		if(isset($arrayIndex) && isset($arrayIndex[$cle][0])){
			$val = $arrayIndex[$cle][0];
			array_push($price,str_replace(",",".",$valeur[1][$val]));
			array_push($newarray,$valeur);
		}
	}
	$tableauDeValeurs = $newarray;
	// On recompose un tableau qui synthetise tous les filtres poids, delai, départements...
	$result = $prixFinal = array();
	foreach ($tableauDeValeurs as $key=>$associate) {
		// Si le nombre de palette < 5 ou si moins de 3 colis ... en respectant les délais
		$final = [];
		if ((($type == 1 && $colis < 5) || ($type == 2 && $colis <= 3)) && $poids <= poidsMaxFret){
			if ($delai>=$associate[utf8_decode('Délai')]){
				$taxeValue = $param[$associate['Name']];
					// si type == Messagerie ...et > 30 kg on applique le prix max + coef multiplicateur / colis supp
				if($associate['Type']=="Messagerie" && $poids > 30){
					$taxePrice = number_format(($price[$key] * (1 + ($taxeValue/100)) + (($colis-1)*colisSupp)),2);
					array_push($final,"<p><span>".$associate['Name']."</span>",$associate['Type'],utf8_decode("délai: ").$associate[utf8_decode('Délai')]."h","prix: ".$price[$key]." &euro;","taxe: ".$taxeValue." %"," soit<span> ".$taxePrice." &euro; </span></p>");
					array_push($result,$final);
					array_push($prixFinal,$taxePrice);
				}else{
					// Sinon
					$taxePrice = number_format($price[$key] * (1 + ($taxeValue/100)),2);
					array_push($final,"<p><span>".$associate['Name']."</span>",$associate['Type'],utf8_decode("délai: ").$associate[utf8_decode('Délai')]."h","prix: ".$price[$key]." &euro;","taxe: ".$taxeValue." %"," soit<span> ".$taxePrice." &euro; </span></p>");
					array_push($result,$final);
					array_push($prixFinal,$taxePrice);
				}
			}
			// Si le nombre de palette < 5 ou plus de 3 colis mais <= poids max du frêt... en respectant les délais

		}elseif ((($type == 1 && $colis < 5)||($type == 2 && $colis > 3)) && $poids <= poidsMaxFret) {
			if($associate['Type'] == utf8_decode("Frêt") && ($delai>=$associate[utf8_decode('Délai')])){
				$taxeValue = $param[$associate['Name']];
				$taxePrice = number_format($price[$key] * (1 + ($taxeValue/100)),2);
				array_push($final,"<p><span>".$associate['Name']."</span>",$associate['Type'],utf8_decode("délai: ").$associate[utf8_decode('Délai')]."h","prix: ".$price[$key]." &euro;","taxe: ".$taxeValue." %"," soit<span> ".$taxePrice." &euro; </span></p>");
				array_push($result,$final);
				array_push($prixFinal,$taxePrice);
			}
			// Si le nombre de palette > 5 ou le poids superieur au maxi du Frêt... alors peu importe le délai.
		}else{
			if($associate['Type'] == utf8_decode("Affrêtement")){
				$taxeValue = $param[$associate['Name']];
				$name = $associate['Name'];
				if (empty($result)){
					if(isset($price[$key])){
						$taxePrice = number_format($price[$key] * (1 + ($taxeValue/100)),2);
						array_push($final,"<p><span>".$associate['Name']."</span>",$associate['Type'],utf8_decode("délai: ").$associate[utf8_decode('Délai')]."h","prix: ".$price[$key]." &euro;","taxe: ".$taxeValue." %"," soit<span> ".$taxePrice." &euro; </span></p>");
						array_push($result,$final);
						array_push($prixFinal,$taxePrice);
					}
				}else{
					foreach ($result as $valeur) {
						if(in_array($name,$valeur)){
							continue;
						}else{
							$taxePrice = number_format($price[$key] * (1 + ($taxeValue/100)),2);
							array_push($final,"<p><span>".$associate['Name']."</span>",$associate['Type'],utf8_decode("délai: ").$associate[utf8_decode('Délai')]."h","prix: ".$price[$key]." &euro;","taxe: ".$taxeValue." %"," soit<span> ".$taxePrice." &euro; </span></p>");
							array_push($result,$final);
							array_push($prixFinal,$taxePrice);
						}
					}
				}
			}
		}

	}


	if (count($result) > 0){
		// Tri du tableau a partir de la dernière colonne ordre croissant : prix + taxe
		$tritableau =  array();
		foreach ($prixFinal as $key=>$tri) {
			$tritableau[$key]=$tri;
			asort($tritableau);

		}
		$arra = array();
		// Adaptation du tableau trié pour le passage au format json
		for($x=0;$x<count($result);$x++){
			array_push($arra,utf8_encode(implode(" , ",$result[key($tritableau)])));
			next($tritableau);
		}
		echo json_encode(["response"=>true,"tableau"=>$arra]);
		exit();
	}else{
		echo json_encode(["response"=>"false","message"=>"Aucune valeur trouvée pour votre recherche"]);
		exit();
	}
}
echo json_encode(["response"=>"false","message"=>"Il manque des paramètres de recherche... Merci de compléter et réessayer !..."]);
?>