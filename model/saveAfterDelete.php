<?php 

function saveData($tab,$id){
	$result = "";
	try {
		include'../model/bddConnexion.php';
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
		$request="DELETE FROM `mycarrierslist` WHERE `idlist`=$id";
		$act=$bdd->prepare($request)->execute();
		foreach ($tab as $key => $value) {
			if(empty($value[4])) $value[4]=0;
			$requete='INSERT INTO `mycarrierslist`(`idlist`, `carriername`, `type`, `filesize`, `gazoletaxe`, `date`, `fileassosciate`) VALUES (?,?,?,?,?,?,?)';
			$execute = $bdd->prepare($requete)->execute(array($value[0],$value[1],$value[2],$value[3],$value[4],$value[5],$value[6]));
			if ($execute) $result = $execute;
		}
		if(isset($result)){
			return true;
		}else{
			return false;
		}
	} catch (Exception $e) {
		return false;
	}
}
?>