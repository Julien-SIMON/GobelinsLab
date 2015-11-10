<?php
class pageManager {
	// Builder
	function pageManager(){
	}
	
	function getId($pluginId,$name) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_pages WHERE name=:name AND plugin_id=:plugin_id AND deleted_date=0");
		$q0->execute(array( "name" => $name , 'plugin_id' => $pluginId ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}

	function create($pluginId,$name) {
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_pages (plugin_id,name,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:plugin_id,:name,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'name' => $name,
							'plugin_id' => $pluginId,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
					
		// Create the object
		$obj=new objectManager();
		$obj->create(get_table_id('core_pages'),$this->getId($pluginId,$name));
		
		// Return the id of this new page
		return $this->getId($pluginId,$name);
	}

	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_pages SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}


class page extends dbEntry {
	var $pluginId;
	var $name;
	
	// Builder
	function page($id){
		$this->id = $id;
		
		// Get the group informations
		$q0=get_link()->prepare("SELECT 
									p.id AS ID,
									p.plugin_id AS PLUGINID,
									p.name AS NAME,
									p.created_date AS CREATED_DATE,
									p.created_id AS CREATED_ID,
									p.edited_date AS EDITED_DATE,
									p.edited_id AS EDITED_ID,
									p.deleted_date AS DELETED_DATE,
									p.deleted_id AS DELETED_ID
								FROM ".get_ini('BDD_PREFIX')."core_pages p 
								WHERE p.id=:id"); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->pluginId = $r0->PLUGINID;
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
			echo 'The page don\'t exist.';
			exit(100);
		}
	}
}

?>