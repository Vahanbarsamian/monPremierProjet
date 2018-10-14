<?php 
include'../model/bddConnexion.php';
try {
	$bdd;
	$req="CREATE TABLE IF NOT EXISTS $DB_NAME.`mycarrierslist` (
	`idlist` smallint(6) NOT NULL,
	`carriername` VARCHAR(50) NULL,
	`type` VARCHAR(30) NULL,
	`filesize` INT NULL,
	`gazoletaxe` FLOAT NULL,
	`date` TIMESTAMP NULL,
	`fileassosciate` VARCHAR(45) NULL,
	CONSTRAINT `idlist`
	FOREIGN KEY (idlist)
	REFERENCES `user`(iduser)
	ON DELETE CASCADE
	ON UPDATE CASCADE)
	ENGINE = InnoDB";
	$exe=$bdd->prepare($req)->execute();
	$requete='INSERT INTO `mycarrierslist`(`idlist`, `carriername`, `type`, `filesize`, `date`, `fileassosciate`) VALUES (?,?,?,?,?,?)';
	$execute = $bdd->prepare($requete)->execute(array($tab['id'],$tab['carrierName'],$tab['type'],$tab['size'],$tab['date'],$uploadName));
	$result=true;
}
catch (Exception $e) {
	$result=false;
}







?>