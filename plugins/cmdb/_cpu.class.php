<?php
class cpuManager {
	var $idOs;
	
	// Builder
	function cpuManager($idOs){
		$this->idOs=$idOs;
	}
	
	function getId($logicalId) {
		$q0=get_link()->prepare('SELECT id AS ID FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_cpu WHERE id_os=:id_os AND logical_id=:logical_id AND deleted_date=0'); 
		$q0->execute(array( 'id_os' => $this->idOs , 'logical_id' => $logicalId ));
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
	
	function create($logicalId,$name,$maxClockSpeed) {
		$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_dev_os_cpu (id_os,logical_id,max_clock_speed,name,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_os,:logical_id,:max_clock_speed,:name,:created_date,:created_id,0,0,0,0)'); 
		$q0->execute(array( 'id_os' => $this->idOs , 'logical_id' => $logicalId , 'max_clock_speed' => $maxClockSpeed , 'name' => $name , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
		
		return $this->getId($logicalId);
	}
	
	function delete($id=0) {
		if($id>0) {
			$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_dev_os_cpu set deleted_date=:deleted_date,deleted_id=:deleted_id where id=:id AND deleted_date=0'); 
			$q0->execute(array( 'id' => $id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
		}
	}
}


class cpu extends dbEntry {
	var $id;
	var $logicalId;
	var $maxClockSpeed;
	var $name;
	
	// Builder
	function cpu($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare('SELECT 
									c.id AS ID,
									c.logical_id AS LOGICALID,
									c.max_clock_speed AS MAXCLOCKSPEED,
									c.name AS NAME,
									c.created_date AS CREATED_DATE,
									c.created_id AS CREATED_ID,
									c.edited_date AS EDITED_DATE,
									c.edited_id AS EDITED_ID,
									c.deleted_date AS DELETED_DATE,
									c.deleted_id AS DELETED_ID
								FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_cpu c 
								WHERE id=:id AND deleted_date=0'); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->logicalId = $r0->LOGICALID;
			$this->maxClockSpeed = $r0->MAXCLOCKSPEED;
			$this->name = $r0->NAME;
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
			echo 'The cpu does not exist.';
			exit(100);
		}
	}
}
?>