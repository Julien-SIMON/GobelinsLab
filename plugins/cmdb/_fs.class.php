<?php
class fsManager {
	var $idOs;
	
	// Builder
	function fsManager($idOs){
		$this->idOs=$idOs;
	}
	
	function getId($name) {
		$q0=get_link()->prepare('SELECT ID AS ID FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_fs WHERE id_os=:id_os AND name=:name AND deleted_date=0'); 
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
	
	function create($name,$fileSystem,$alias) {
		$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_dev_os_fs (id_os,name,filesystem,alias,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_os,:name,:filesystem,:alias,:created_date,:created_id,0,0,0,0)'); 
		$q0->execute(array( 'id_os' => $this->idOs , 'name' => $name , 'filesystem' => $fileSystem , 'alias' => $alias , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
		
		return $this->getId($name);
	}
	
	function delete($id=0) {
		if($id>0) {
			$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_dev_os_fs set deleted_date=:deleted_date,deleted_id=:deleted_id where id=:id AND deleted_date=0'); 
			$q0->execute(array( 'id' => $id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
		}
	}
	
	function getSpaceUpdate($date,$idFs) {
		$q0=get_link()->prepare('SELECT ID AS ID FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_fs_up WHERE id_fs=:id_fs AND created_date=:created_date AND deleted_date=0'); 
		$q0->execute(array( 'id_fs' => $idFs , 'created_date' => $date ));
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
}


class fs extends dbEntry {
	var $id;
	var $name;
	var $fileSystem;
	var $alias;
	var $partitionSize;
	var $partitionFreeSpace;
	
	var $upCreatedDate;
	var $upCreatedID;
	
	// Builder
	function fs($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare('SELECT 
									f.id AS ID,
									f.name AS NAME,
									f.filesystem AS FILESYSTEM,
									f.alias AS ALIAS,
									u.partition_size AS PARTITIONSIZE,
									u.partition_freespace AS PARTITIONFREESPACE,
									u.created_date AS UP_CREATED_DATE,
									u.created_id AS UP_CREATED_ID,
									f.created_date AS CREATED_DATE,
									f.created_id AS CREATED_ID,
									f.edited_date AS EDITED_DATE,
									f.edited_id AS EDITED_ID,
									f.deleted_date AS DELETED_DATE,
									f.deleted_id AS DELETED_ID
								FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_fs f 
								LEFT JOIN '.get_ini('BDD_PREFIX').'cmdb_dev_os_fs_up u
								ON f.id=u.id_fs AND u.deleted_date=0
								WHERE f.id=:id AND f.deleted_date=0'); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->name = $r0->NAME;
			$this->fileSystem = $r0->FILESYSTEM;
			$this->alias = $r0->ALIAS;
			$this->partitionSize = $r0->PARTITIONSIZE;
			$this->partitionFreeSpace = $r0->PARTITIONFREESPACE;
			
			$this->upCreatedDate = $r0->UP_CREATED_DATE;
			$this->upCreatedID = $r0->UP_CREATED_ID;
			
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
			echo 'The fs does not exist.';
			exit(100);
		}
	}
	
	function updateSpaceStatus($date,$size,$free) {
		$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_dev_os_fs_up set deleted_date=:deleted_date,deleted_id=:deleted_id where id_fs=:id_fs AND deleted_date=0'); 
		$q0->execute(array( 'id_fs' => $this->id , 'deleted_date' => $date , 'deleted_id' => $_SESSION['USER_ID'] ));	
		
		$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_dev_os_fs_up (id_fs,partition_size,partition_freespace,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_fs,:partition_size,:partition_freespace,:created_date,:created_id,0,0,0,0)'); 
		$q0->execute(array( 'id_fs' => $this->id , 'partition_size' => $size , 'partition_freespace' => $free , 'created_date' => $date , 'created_id' => $_SESSION['USER_ID']  ));
	}
}
?>