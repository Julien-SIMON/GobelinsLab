<?php
class objectManager {
	// Builder
	function objectManager(){
	}
	
	function getId($idTable,$idExt) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_objects WHERE id_table=:id_table AND id_ext=:id_ext AND deleted_date=0");
		$q0->execute(array( "id_table" => $idTable , "id_ext" => $idExt ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}
	
	function create($idTable,$idExt) {
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_objects (id_table,id_ext,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:id_table,:id_ext,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'id_table' => $idTable,
                            'id_ext' => $idExt,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
		
		return $this->getId($idTable,$idExt);
	}

	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_objects SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}


class object extends dbEntry {
	// Builder
	function objects($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare("SELECT 
									obj.id AS ID,
									obj.created_date AS CREATED_DATE,
									obj.created_id AS CREATED_ID,
									obj.edited_date AS EDITED_DATE,
									obj.edited_id AS EDITED_ID,
									obj.deleted_date AS DELETED_DATE,
									obj.deleted_id AS DELETED_ID
								FROM ".get_ini('BDD_PREFIX')."core_objects obj 
								WHERE obj.id=:id"); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->createdDate = $r0->CREATED_DATE;
			$this->createdID = $r0->CREATED_ID;
			$this->editedDate = $r0->EDITED_DATE;
			$this->editedId = $r0->EDITED_ID;
			$this->deletedDate = $r0->DELETED_DATE;
			$this->deltedId = $r0->DELETED_ID;
		}
		else
		{
			// TODO add log management
			echo 'The disk don\'t exist.';
			exit(100);
		}
	}
}

/*
class object {
	var $id; // id de l'objet 0=non définie
	
	var $tabName;
	var $idOut;
	
	
	function object($id=0) {
	    $this->id=$id;
	    
	    if($id>0) {
	    	$this->getInfo();
		}
	}
	
	function setId($id) {
	    $this->id=$id;
	    
	    if($id>0) {
	    	$this->getInfo();
		}
	}
	
	function getId($id_table,$id_ext) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_objects WHERE id_table=:id_table AND id_ext=:id_ext AND deleted_date=0");
		$q0->execute(array( "id_table" => $id_table , "id_ext" => $id_ext ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}
	
	function getInfo() {
		$q0=get_link()->prepare("SELECT obj.id_ext AS ID_EXT, tab.name AS NAME FROM ".get_ini('BDD_PREFIX')."core_objects obj, ".get_ini('BDD_PREFIX')."tables tab WHERE obj.id=:id AND tab.id=obj.id_table AND tab.deleted_date=0 AND obj.deleted_date=0");
		$q0->execute(array( "id" => $this->id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID_EXT)&&isset($r0->NAME)) {
			$this->tabName=$r0->NAME;
			$this->idOut=$r0->ID_EXT;
		}
		else {
			$this->tabName=0;
			$this->idOut=0;
		}
	}
	
	function create($id_table,$id_ext) {
		// On créé l'objet
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_objects (id_table,id_ext,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:id_table,:id_ext,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'id_table' => $id_table,
                            'id_ext' => $id_ext,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
		
		$this->id = get_link()->lastInsertId();
	}
	
	function delete() {
		// On supprime l'objet
		$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_objects SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
		$q0->execute(array(	'id' => $this->id,
							'deleted_id' => $_SESSION['USER_ID'],
							'deleted_date' => time()
					));
	}
}
*/
?>