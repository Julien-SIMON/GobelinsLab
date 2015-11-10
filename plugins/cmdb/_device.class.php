<?php
class deviceManager {
	
	// Builder
	function deviceManager(){
	}
	
	function getId($typeName,$name) {
		$q0=get_link()->prepare('SELECT ID AS ID FROM '.get_ini('BDD_PREFIX').'cmdb_devices WHERE NAME = :name AND typename = :typename AND deleted_date=0'); 
		$q0->execute(array( 'name' => $name , 'typename' => $typeName ));
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
	
	function create($parentId,$name,$typeName,$comments) {
		$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_devices (id_parent,name,typename,comments,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_parent,:name,:typename,:comments,:created_date,:created_id,0,0,0,0)'); 
		$q0->execute(array( 'id_parent' => $parentId , 'name' => $name , 'typename' => $typeName , 'comments' => $comments , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
		
		// Create the object
		$obj = new objectManager();
		$obj->create(get_table_id('cmdb_devices'),$this->getId('server',$name));
		
		return $this->getId($typeName,$name);
	}
	
	function update($id,$parentId,$name,$typeName,$comments) {
		$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_devices set id_parent=:id_parent,name=:name,typename=:typename,comments=:comments,edited_date=:edited_date,edited_id=:edited_id where id=:id'); 
		$q0->execute(array( 'id' => $id , 'id_parent' => $parentId , 'name' => $name , 'typename' => $typeName , 'comments' => $comments , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
	}
	
	function delete($id=0) {
		if($id>0) {
			$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_devices set deleted_date=:deleted_date,deleted_id=:deleted_id where id=:id AND deleted_date=0'); 
			$q0->execute(array( 'id' => $id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
		}
	}
	
	// MDPE = Map device project environment
	function getIdMdpe($deviceId,$projectId,$environmentId) { // Check if a mapping exist between device/project/environment
		$q0=get_link()->prepare('SELECT ID AS ID FROM '.get_ini('BDD_PREFIX').'cmdb_dev_pro_env_map WHERE id_device = :id_device AND id_project = :id_project AND id_environment = :id_environment AND deleted_date=0'); 
		$q0->execute(array( 'id_device' => $deviceId , 'id_project' => $projectId , 'id_environment' => $environmentId ));
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

	function createMdpe($deviceId,$projectId,$environmentId) {
		$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_dev_pro_env_map (id_device,id_project,id_environment,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_device,:id_project,:id_environment,:created_date,:created_id,0,0,0,0)'); 
		$q0->execute(array( 'id_device' => $deviceId , 'id_project' => $projectId , 'id_environment' => $environmentId , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID'] ));
		
		return $this->getIdMdpe($deviceId,$projectId,$environmentId);
	}
	
	function deleteMdpe($id=0) {
		if($id>0) {
			$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_dev_pro_env_map set deleted_date=:deleted_date,deleted_id=:deleted_id where id=:id AND deleted_date=0'); 
			$q0->execute(array( 'id' => $id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
		}
	}
	
	function updateStatus($deviceId,$status,$code,$createdDate) {
		$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_dev_status set deleted_date=:deleted_date,deleted_id=:deleted_id where deleted_date=0'); 
		$q0->execute(array( 'id' => $id , 'deleted_date' => toTime($createdDate) , 'deleted_id' => $_SESSION['USER_ID'] ));
		
		$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_dev_status (id_device,status,code,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_device,:status,:code,:created_date,:created_id,0,0,0,0)'); 
		$q0->execute(array( 'id_device' => $deviceId , 'status' => $status , 'code' => $code , 'created_date' => toTime($createdDate) , 'created_id' => $_SESSION['USER_ID'] ));
	}
}


class device extends dbEntry {
	var $id;
	var $parentId;
	var $name;
	var $typeName;
	var $comments;
	var $status;
	var $code;
	var $mdpeIdArray;
	var $mdpeArray;
	
	// Builder
	function device($id){
		$this->id = $id;
		
		// Get the informations
		$q0=get_link()->prepare('SELECT 
									d.id AS ID,
									d.id_parent AS ID_PARENT,
									d.name AS NAME,
									d.typename AS TYPENAME,
									d.comments AS COMMENTS,
									d.created_date AS CREATED_DATE,
									d.created_id AS CREATED_ID,
									d.edited_date AS EDITED_DATE,
									d.edited_id AS EDITED_ID,
									d.deleted_date AS DELETED_DATE,
									d.deleted_id AS DELETED_ID
								FROM '.get_ini('BDD_PREFIX').'cmdb_devices d 
								WHERE id=:id AND deleted_date=0'); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->parentId = $r0->ID_PARENT;
			$this->name = $r0->NAME;
			$this->typeName = $r0->TYPENAME;
			$this->comments = $r0->COMMENTS;
			$this->createdDate = $r0->CREATED_DATE;
			$this->createdID = $r0->CREATED_ID;
			$this->editedDate = $r0->EDITED_DATE;
			$this->editedId = $r0->EDITED_ID;
			$this->deletedDate = $r0->DELETED_DATE;
			$this->deltedId = $r0->DELETED_ID;
			
			// Get comments (CLOB) for Oracle DB
			if(strtoupper(get_ini('BDD_TYPE'))=='ORACLE'){
				$q2=get_link()->prepare('SELECT 
											d.comments AS COMMENTS
										FROM '.get_ini('BDD_PREFIX').'cmdb_devices d 
										WHERE id=:id AND deleted_date=0'); 
				$q2->execute(array( 'id' => $id ));
				$q2->bindColumn('COMMENTS', $this->comments, PDO::PARAM_STR, 2048);
				$q2->fetch(PDO::FETCH_BOUND);
			}
			
			// Get status of the last connection check
			$q0=get_link()->prepare('SELECT 
										d.id AS ID,
										d.status AS STATUS,
										d.code AS CODE,
										d.created_date AS CREATED_DATE,
										d.created_id AS CREATED_ID,
										d.edited_date AS EDITED_DATE,
										d.edited_id AS EDITED_ID,
										d.deleted_date AS DELETED_DATE,
										d.deleted_id AS DELETED_ID
									FROM '.get_ini('BDD_PREFIX').'cmdb_dev_status d 
									WHERE id_device=:id_device AND deleted_date=0'); 
			$q0->execute(array( 'id_device' => $this->id ));
			$r0 = $q0->fetch(PDO::FETCH_OBJ);
			if(isset($r0->ID))
			{
				$this->status = $r0->STATUS;
				$this->code = $r0->CODE;
			}
			
			// MDPE = Map device project environment
			$this->mdpeIdArray = array();
			
			$q1=get_link()->prepare("
									SELECT
										map.id AS MAP_ID,
										env.name AS ENVIRONMENT_NAME,
										pro.name AS PROJECT_NAME,
										map.created_date AS CREATED_DATE,
										map.created_id AS CREATED_ID,
										map.edited_date AS EDITED_DATE,
										map.edited_id AS EDITED_ID,
										map.deleted_date AS DELETED_DATE,
										map.deleted_id AS DELETED_ID
									FROM 
										".get_ini('BDD_PREFIX')."cmdb_dev_pro_env_map map, 
										".get_ini('BDD_PREFIX')."cmdb_projects pro, 
										".get_ini('BDD_PREFIX')."cmdb_environments env 
									WHERE 
										map.id_device = :id AND
										map.id_environment = env.id AND
										map.id_project = pro.id AND
										map.deleted_date = 0 AND
										pro.deleted_date = 0 AND
										env.deleted_date = 0
									");
			$q1->execute(array( 'id' => $this->id ));
			while( $r1 = $q1->fetch(PDO::FETCH_OBJ) )
			{
				array_push($this->mdpeIdArray,$r1->MAP_ID);
				$this->mdpeArray[$r1->MAP_ID]['PROJECT']=$r1->PROJECT_NAME;
				$this->mdpeArray[$r1->MAP_ID]['ENVIRONMENT']=$r1->ENVIRONMENT_NAME;
			}
			$q1->closeCursor();
		}
		else
		{
			// TODO add log management
			echo 'The device does not exist.';
			exit(100);
		}
	}
	
	function updateComments($comments) {
		$comments=trim($comments);
		
		$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_devices set comments=:comments,edited_date=:edited_date,edited_id=:edited_id where id=:id'); 
		$q0->execute(array( 'id' => $this->id , 'comments' => $comments , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
		
		$this->comments=$comments;
	}
}
?>