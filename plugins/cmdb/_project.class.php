<?php
class projectManager {
	
	// Builder
	function projectManager(){
	}
	
	function getId($name) {
		$q0=get_link()->prepare('SELECT ID AS ID FROM '.get_ini('BDD_PREFIX').'cmdb_projects WHERE NAME = :name AND deleted_date=0'); 
		$q0->execute(array( 'name' => $name ));
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
	
	function create($parentId,$name) {
		$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_projects (id_parent,name,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_parent,:name,:created_date,:created_id,0,0,0,0)'); 
		$q0->execute(array( 'id_parent' => $parentId , 'name' => $name , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
		
		// Create the object
		$obj = new objectManager();
		$obj->create(get_table_id('cmdb_projects'),$this->getId($name));
		
		return $this->getId($name);
	}
	
	function update($id,$parentId,$name) {
		$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_projects set id_parent=:id_parent,name=:name,edited_date=:edited_date,edited_id=:edited_id where id=:id'); 
		$q0->execute(array( 'id' => $id , 'id_parent' => $parentId , 'name' => $name , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
	}
	
	function delete($id=0) {
		if($id>0) {
			$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_projects set deleted_date=:deleted_date,deleted_id=:deleted_id where id=:id AND deleted_date=0'); 
			$q0->execute(array( 'id' => $id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
		}
	}
}


class project extends dbEntry {
	var $id;
	var $parentId;
	var $name;
	
	// Builder
	function project($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare('SELECT 
									p.id AS ID,
									p.id_parent AS ID_PARENT,
									p.name AS NAME,
									p.created_date AS CREATED_DATE,
									p.created_id AS CREATED_ID,
									p.edited_date AS EDITED_DATE,
									p.edited_id AS EDITED_ID,
									p.deleted_date AS DELETED_DATE,
									p.deleted_id AS DELETED_ID
								FROM '.get_ini('BDD_PREFIX').'cmdb_projects p 
								WHERE id=:id AND deleted_date=0'); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->parentId = $r0->ID_PARENT;
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
			echo 'The project don\'t exist.';
			exit(100);
		}
	}
}
?>