<?php 
class FileOpenClass {
	private $_type;
	private $_path;
	private $_separate = ";";
private $_list = array(); //Array


		// Constructeur
public function __construct($_filepath, $_type=null, $_separate=null){
	if(!isset($_type)){
		$this->_type = "a+";
	} else {
		$this->_type =  $_type;
	}
	$this->_path = $_filepath;
	($_separate!=null)? $this->_separate = $_separate : $this->_separate = ";";
}

		// Vérifie si le fichier existe sinon le crée.
public function createFile($_otherpath=null, $_type=null){
	if($_type == null){
		$this->_type = 'a+';
	} else {
		$this->_type = $_type;
	}
	if($_otherpath != null){
		$this->_path = $_otherpath;
	}
	$_fileOpen = fopen($this->_path,$this->_type);
	fclose($_fileOpen);
	
}

		// Vérifie si le fichier est vide
public function isEmpty($_path=null){
	if($_path!=null){
		$this->_path = $_path;
	}
	$s_fileData = file_get_contents($this->_path);
	
	if (!$s_fileData || strlen($s_fileData) == 0){

		return true;

	} elseif(strlen($s_fileData) > 0) {

		return false;
	}

}

		// Remplir un tableau avec les données du fichier CSV
public function fileToArray($_path=null){ 
	if($_path!=null){
		$this->_path = $_path;
	} 
	$_fileOpen = fopen($this->_path, 'r');
	$this->_list = [];
	while(true){
		$_rowdata = fgetcsv($_fileOpen,1000,$this->_separate);
		if ($_rowdata == false){
			fclose($_fileOpen);
			return $this->_list;
		}
		
		array_push($this->_list,$_rowdata);
		
	}
	
}

		// Sauvegarder le tableau dans le fichier CSV
public function saveArrayToFile(array $_data){
	$_fileOpen = fopen($this->_path,"w");
	if(is_array($_data)){ 
		foreach ($_data as $_value) {
			fputcsv($_fileOpen, $_value,$this->_separate);
		}
	}
	fclose($_fileOpen);
}

		// Sauvegarder une donnée dans le fichier CSV
public function saveOneToFile($_data){
	$_fileOpen = fopen($this->_path,"a");
	fputcsv($_fileOpen, $_data,$this->_separate);
	fclose($_fileOpen);
}


}


?>