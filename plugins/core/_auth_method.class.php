<?php
class authMethodManager {
	// Builder
	function authMethodManager(){
	}
	
	function getId($name) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_user_auth_methods WHERE name=:name AND deleted_date=0");
		$q0->execute(array( "name" => $name ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}

	function create($name,$icon) {
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_user_auth_methods (name,icon,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:name,:icon,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'name' => $name,
							'icon' => $icon,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
					
		// Create the object
		$obj=new objectManager();
		$obj->create(get_table_id('core_user_auth_methods'),$this->getId($name));
		
		// Return the id of this new group
		return $this->getId($name);
	}
	
	function update($id,$name,$icon) {
		$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_user_auth_methods SET name=:name, icon=:icon, edited_id=:edited_id, edited_date=:edited_date WHERE id=:id');
		$q0->execute(array(	'id' => $id,
                            'name' => $name,
                            'icon' => $icon,
							'edited_id' => $_SESSION['USER_ID'],
							'edited_date' => time()
					));
	}

	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_user_auth_methods SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}


class authMethod extends dbEntry {
	var $name;
	var $icon;
	
	// Builder
	function authMethod($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare("SELECT 
									uam.id AS ID,
									uam.name AS NAME,
									uam.icon AS ICON,
									uam.created_date AS CREATED_DATE,
									uam.created_id AS CREATED_ID,
									uam.edited_date AS EDITED_DATE,
									uam.edited_id AS EDITED_ID,
									uam.deleted_date AS DELETED_DATE,
									uam.deleted_id AS DELETED_ID
								FROM ".get_ini('BDD_PREFIX')."core_user_auth_methods uam 
								WHERE uam.id=:id"); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->name = $r0->NAME;
			$this->icon = $r0->ICON;
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
class group {

	function getName() {
		$qGroups=get_link()->prepare("SELECT name AS NAME FROM ".get_ini('BDD_PREFIX')."core_groups WHERE id=:id AND deleted_date=0");
		$qGroups->execute(array( "id" => $this->id ));
		$rGroups = $qGroups->fetch(PDO::FETCH_OBJ);
		
		if(isset($rGroups->NAME)) {
			return $rGroups->NAME;
		}
		else {
			return '';
		}
	}

	
	function add_group_user_map($user_id) {
		// On ajoute un lien entre utilisateur et groupe
        $q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_groups_users_map (group_id,user_id,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:group_id,:user_id,:created_id,:created_date,0,0,0,0)');
        $q0->execute(array(	'group_id' => $this->id,
                            'user_id' => $user_id,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
				    ));
	}
	
	function delete_group_user_map($user_id) {
		// On ajoute un lien entre utilisateur et groupe
        $q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_groups_users_map SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE user_id=:user_id AND group_id=:group_id AND deleted_date=0');
        $q0->execute(array(	'group_id' => $this->id,
                            'user_id' => $user_id,
							'deleted_id' => $_SESSION['USER_ID'],
							'deleted_date' => time()
				    ));
	}
	
	function get_group_user_map($user_id) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_groups_users_map WHERE user_id=:user_id AND group_id=:group_id AND deleted_date=0");
		$q0->execute(array( "user_id" => $user_id , "group_id" => $this->id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}
}
*/
?>