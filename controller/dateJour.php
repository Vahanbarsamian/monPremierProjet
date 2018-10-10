<?php 

function afficheJour($moment){
	$jours = date("w",$moment);
	//On place dans un tableau les jours de semaines.
	//Comme $jours nous renvoie un index de 0 a 6 on l'utilise pour récuperer le jour dans le tableau
	$joursArray = array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi');
	$jours = $joursArray[$jours];
	return $jours;
}

function afficheMois($moment){
	$mois = date("n",$moment);
	//On place dans un tableau les noms des mois.
	//Comme $mois nous renvoie un index de 1 a 12 on l'utilise pour récuperer le mois dans le tableau
	$moisArray = array(1=>'janvier','février','mars','avril','mai','juin','juillet','aout','septembre','octobre','novembre','décembre');
	$mois = $moisArray[$mois];
	return $mois;
}

	//Changement d'heures été et hiver se font le dernier dimanche de mars (+1) et le dernier dimanche d'octobre (-1)

function afficheDate($moment){

 	//On defini que le decalage horaire est de plus ou moins 1 heure
	$decalageHoraire = 1;

 	//On verifie si un paramètre $moment a été passé si oui date = date passée sinon date = date actuelle via timestamp
	if (!$moment){
 		$time = time(); //time() renvoie le timestamp
 	}else{
 		$time = $moment;
 	}

 	//Puisque le changement d'heure se fait le dernier dimanche de mars, on cherche le jour du 31 et on deduit le nombre de jour qui le sépare du dimanche. gmdate() tout comme date() renvoie une date formatée mais en GMT en plus pour gmdate.

 	$jourSemaineMars = gmdate("w",mktime(1,0,0,3,31,gmdate("Y")));//valeur au 31 mars renvoie un chiffre de 0 a 6 soit le jour de la semaine en passant année en paramètre ou par defaut l'année courante.

 	$joursSemaineOctobre = gmdate("w",mktime(1,0,0,10,31,gmdate("Y")));//meme chose pour octobre

 	//On recupere en timestamp la date du mois de mars et celle du mois d'octobre
 	// Valeur formatée em jour ,mois et année : var_dump(gmdate("l d M Y H:i:s",(mktime(1,0,0,3,31-gmdate("w",mktime(1,0,0,3,31,gmdate("Y"))),gmdate("Y")))));
 	$heureEte = mktime(1,0,0,3,31-$jourSemaineMars,gmdate("Y"));
 	$heureHiver = mktime(1,0,0,10,31-$joursSemaineOctobre,gmdate("Y"));

 	//On à plus qu'a comparer que $time est inferieure ou supérieure a ces dates pour savoir si on ajoute ou supprime 1 a l'heure actuelle

 	if ($time > $heureEte && $time < $heureHiver){
 		$decalage = $decalageHoraire + 1;
 	}else {
 		$decalage = $decalageHoraire;
 	}

 	//Il faut maintenant construire le timestamp en tenant compte de l'heure d'hiver ou de l'heure d'été

 	$moment = mktime(gmdate("G")+$decalage,gmdate("i"),0,gmdate('n'),gmdate("j"),gmdate("Y"));

 	//On affiche la date
 	$maDate = afficheJour($moment)." ".date('j',$moment)." ".afficheMois($moment)." ".date("Y",$moment);
 	/*$heure = date("G",$moment);
 	$minute = date("i",$moment);
 	$maDate.=' ';
 	$maDate .= $heure;
 	$maDate .= " h ";
 	$maDate .= $minute;*/
 	return $maDate;
 }


 ?>