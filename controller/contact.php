<?php

# fonctions

if ($_POST){
//Fonction qui nettoie les entrées clients
	function cleanInput($var){
		$var = trim($var, " \t\n\r\0\x0B");
		$var = stripslashes($var);
		$var = htmlspecialchars($var);
		return $var;
	}

//On verifie le mail
	function isEmail($val){
		return (empty($val)|| !preg_match ( " /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ " , $val ));
	}

//On verifie le n° téléphone (vide ou 0 ou +33 et 9 chiffres)
	function isPhone($value){
		return (!preg_match("/(^$)|(^($|0|\+33){1}+([0-9]{9}$))/",$value));
	}


# Variables
//Variable destinataire du mail du form contact
	$sendTo = "vahanbarsamian@free.fr";

//Variable subject du formulaire
	$subject = "Nouveau mail de mon site :transporteurs";

//Variable qui va contenir le contenu de mon mail
	$emailText ="";
	$headers = "";

// On initialise les variables input et message erreur a vide a l'entrée du formulaire
	$array = array("firstName" => "", "lastName" => "", "email" => "", "phone" => "", "message" => "",
		"firstNameError" => "", "lastNameError" => "", "emailError" => "", "phoneError" => "", "messageError" => "", "isSuccess" => false);

#execution du code

//On appelle la fonction de nettoyage sur le $_POST

	$array['firstName'] = cleanInput($_POST['firstName']);
	$array['lastName'] = cleanInput($_POST['lastName']);
	$array['email'] = strtolower (cleanInput($_POST['email']));
	$array['phone'] = cleanInput($_POST['phone']);
	$array['message'] = cleanInput($_POST['message']);
	$array['isSuccess'] = true;


// On verifie côté serveur que les champs soient remplis
	if(empty($array['firstName']) || is_numeric($array['firstName'])){ $array['firstNameError'] = "Merci d'inscrire votre prénom : (";
	$array['isSuccess'] = false;
}else{
	$emailText .= "First name : {$array['firstName']}\n";
}

if(empty($array['lastName'])|| is_numeric($array['lastName'])){
	$array['lastNameError'] = "Merci d'inscrire votre nom : (";
	$array['isSuccess'] = false;
}else{
	$emailText .= "Name : {$array['lastName']}\n";
}

if(isEmail($array['email'])){ 
	$array['emailError'] = "Merci d'inscrire un mail valide : (";
	$array['isSuccess'] = false;
}else{
	$emailText .= "Email : {$array['email']}\n";
}

if(isPhone($array['phone'])){ $array['phoneError'] = "Merci d'inscrire un vrai n° de téléphone : (";
$array['isSuccess'] = false;
}else{
	$emailText .="Téléphone : {$array['phone']}\n";
}

if(empty($array['message'])){ $array['messageError'] = "Merci de placer un commentaire";
$array['isSuccess'] = false;
}else{
	$emailText.="Message : {$array['message']}\n";
}

if($array['isSuccess']==true){
	$email=$array['email'];
	ini_set('sendmail_from', '$email');
	$headers  = 'MIME-Version: 1.0' . "\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
	$headers .= "Reply-to: ".$array['firstName']." ".$array['lastName']."<".$email.">\n";
	$headers .= "From: ".$array['firstName']." ".$array['lastName']."<".$email.">\r\n"; 
	$emailText.= $headers."\n";
	$emailText.= "Subject : ".$subject."\n";
	$emailText .= "Send-to ".$sendTo."\n";
	mail($sendTo,$subject,$emailText,$headers);
}

//Envoi de mail dans un fichier test hors connexion
/*//define('LOGFILE','../fichiers/fakesendmail.log');
$log = fopen (LOGFILE,'a+');
fwrite($log,"\n".$emailText).
" called on : ".date('Y-m-d H:i:s')."\n";
fwrite($log,file_get_contents("php://stdin"));
fwrite($log,
	"\n===========================================================\n");
	fclose($log);*/

	$return = json_encode($array);
	echo $return;

}else{
	$template = 'contact';
	include '../view/layout.phtml';
}



?>