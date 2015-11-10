<?php
class pluginManager {
	// Builder
	function pluginManager(){
	}
	
	function getId($name) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_plugins WHERE name=:name AND deleted_date=0");
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
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_plugins (name,activated,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:name,0,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'name' => $name,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
					
		// Create the object
		$obj=new objectManager();
		$obj->create(get_table_id('core_plugins'),$this->getId($name));
		
		// Return the id of this new page
		return $this->getId($name);
	}

	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_plugins SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}


class plugin extends dbEntry {	
	var $name;
	var $activated;
	
	// Builder
	function plugin($id){
		$this->id = $id;
		
		// Get the group informations
		$q0=get_link()->prepare("SELECT 
									p.id AS ID,
									p.name AS NAME,
									p.activated AS ACTIVATED,
									p.created_date AS CREATED_DATE,
									p.created_id AS CREATED_ID,
									p.edited_date AS EDITED_DATE,
									p.edited_id AS EDITED_ID,
									p.deleted_date AS DELETED_DATE,
									p.deleted_id AS DELETED_ID
								FROM ".get_ini('BDD_PREFIX')."core_plugins p 
								WHERE p.id=:id"); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->name = $r0->NAME;
			$this->activated = $r0->ACTIVATED;
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
			echo 'The plugin don\'t exist.';
			exit(100);
		}
	}
	
	function updateActivated($activated) {
		$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_plugins SET activated=:activated, edited_id=:edited_id, edited_date=:edited_date WHERE id=:id');
		$q0->execute(array(	'id' => $this->id,
							'activated' => $activated,
							'edited_id' => $_SESSION['USER_ID'],
							'edited_date' => time()
					));
	}
}

?>