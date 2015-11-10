<?php
class tableManager {
	// Builder
	function tableManager(){
	}
	
	function getId($name) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_tables WHERE name=:name AND deleted_date=0");
		$q0->execute(array( "name" => $name ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}

	function create($name) {
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_tables (name,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:name,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'name' => $name,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
		
		return $this->getId($name);
	}

	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_tables SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}


class table extends dbEntry {
	var $name;
	
	// Builder
	function table($id){
		$this->id = $id;
		
		// Get the group informations
		$q0=get_link()->prepare("SELECT 
									t.id AS ID,
									t.name AS NAME,
									t.created_date AS CREATED_DATE,
									t.created_id AS CREATED_ID,
									t.edited_date AS EDITED_DATE,
									t.edited_id AS EDITED_ID,
									t.deleted_date AS DELETED_DATE,
									t.deleted_id AS DELETED_ID
								FROM ".get_ini('BDD_PREFIX')."core_tables t 
								WHERE t.id=:id"); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
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
			echo 'The table does not exist.';
			exit(100);
		}
	}
}

?>