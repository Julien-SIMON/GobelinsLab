<?php
class eventLogManager {
	// Builder
	function eventLogManager(){
	}
	
	function create($idPlugin,$logStatement) {
		if(strtoupper(get_ini('BDD_TYPE'))=='ORACLE'){
			$s0 = get_link()->prepare('SELECT '.get_ini('BDD_PREFIX').'CORE_EVENT_LOGS_ID_SEQ.NEXTVAL AS ID FROM DUAL');
			$s0->execute();
			$id = $s0->fetchColumn(0);
		} else {
			$id='';
		}
		
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_event_logs (id,id_plugin,log_statement,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:id,:id_plugin,:log_statement,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'id' => $id,
							'id_plugin' => $idPlugin,
                            'log_statement' => $logStatement,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
		
		if(strtoupper(get_ini('BDD_TYPE'))!='ORACLE'){
			$id=get_link()->lastInsertId();
		}
		
		return $id;
	}

	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_event_logs SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}


class eventLog extends dbEntry {
	var $logStatement;
	var $plugin;
	
	// Builder
	function eventLog($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare("SELECT 
									ev.id AS ID,
									ev.log_statement AS LOGSTATEMENT,
									pg.name AS PLUGIN,
									ev.created_date AS CREATED_DATE,
									ev.created_id AS CREATED_ID,
									ev.edited_date AS EDITED_DATE,
									ev.edited_id AS EDITED_ID,
									ev.deleted_date AS DELETED_DATE,
									ev.deleted_id AS DELETED_ID
								FROM 
								".get_ini('BDD_PREFIX')."core_event_logs ev,
								".get_ini('BDD_PREFIX')."core_plugins pg
								WHERE 
								ev.id_plugin=pg.id AND
								ev.id=:id"); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->logStatement = $r0->LOGSTATEMENT;
			$this->plugin = $r0->PLUGIN;
			
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