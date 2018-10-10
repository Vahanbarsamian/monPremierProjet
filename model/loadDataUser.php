<?php 

require'../model/bddConnexion.php';
$bdd;
$request = 'SELECT `iduser` FROM `user` WHERE email = ?';
$value = $bdd->prepare($request);
$value->execute(array($email));
$id = $value->fetch(PDO::FETCH_ASSOC);
$req = 'SELECT * FROM `mycarrierslist` WHERE `idlist`='.intval($id['iduser']);
$sth = $bdd->prepare($req);
$sth->execute();
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

?>