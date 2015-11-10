<?php
class serviceManager {
	var $idOs;
	
	// Builder
	function serviceManager($idOs){
		$this->idOs=$idOs;
	}
	
	function getId($name) {
		$q0=get_link()->prepare('SELECT ID AS ID FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_services WHERE id_os=:id_os AND name=:name AND deleted_date=0'); 
		$q0->execute(array( 'id_os' => $this->idOs , 'name' => $name ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			return $r0->ID;
		}
		else
		{
			return 0;
		}
	}
	
	function create($date,$name,$pathName,$startMode,$serviceState,$owner) {
		$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_dev_os_services (id_os,name,path_name,start_mode,service_state,owner,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_os,:name,:path_name,:start_mode,:service_state,:owner,:created_date,:created_id,0,0,0,0)'); 
		$q0->execute(array( 'id_os' => $this->idOs , 'name' => $name , 'path_name' => $pathName , 'start_mode' => $startMode , 'service_state' => $serviceState , 'owner' => $owner , 'created_date' => $date , 'created_id' => $_SESSION['USER_ID']  ));
		
		return $this->getId($name);
	}
	
	function delete($id=0) {
		if($id>0) {
			$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_dev_os_services set deleted_date=:deleted_date,deleted_id=:deleted_id where id=:id AND deleted_date=0'); 
			$q0->execute(array( 'id' => $id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
		}
	}
}


class service extends dbEntry {
	var $id;
	var $name;
	var $pathName;
	var $startMode;
	var $serviceState;
	var $owner;
	
	// Builder
	function service($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare('SELECT 
									s.id AS ID,
									s.name AS NAME,
									s.path_name AS PATHNAME,
									s.start_mode AS STARTMODE,
									s.service_state AS SERVICESTATE,
									s.owner AS OWNER,
									s.created_date AS UP_CREATED_DATE,
									s.created_id AS UP_CREATED_ID,
									s.created_date AS CREATED_DATE,
									s.created_id AS CREATED_ID,
									s.edited_date AS EDITED_DATE,
									s.edited_id AS EDITED_ID,
									s.deleted_date AS DELETED_DATE,
									s.deleted_id AS DELETED_ID
								FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_services s
								WHERE s.id=:id AND s.deleted_date=0'); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->name = $r0->NAME;
			$this->pathName = $r0->PATHNAME;
			$this->startMode = $r0->STARTMODE;
			$this->serviceState = $r0->SERVICESTATE;
			$this->owner = $r0->OWNER;
			
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
			echo 'The service does not exist.';
			exit(100);
		}
	}
}
?>