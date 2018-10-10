<?php 

	$DB_TYPE = 'mysql'; //Type of database<br>
	$DB_HOST = 'localhost'; //Host name<br>
	$DB_USER = 'root'; //Host Username<br>
	$DB_PASS = ''; //Host Password<br>
	$DB_NAME = 'myfirstproject'; //Database name<br><br>
	
	$bdd = new PDO("$DB_TYPE:host=$DB_HOST; dbname=$DB_NAME;charset=utf8", $DB_USER, $DB_PASS);
	
	?>