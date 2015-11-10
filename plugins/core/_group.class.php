<?php
class groupManager {
	// Builder
	function groupManager(){
	}
	
	function getId($name) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_groups WHERE name=:name AND deleted_date=0");
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
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_groups (name,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:name,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'name' => $name,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
					
		// Create the object
		$obj=new objectManager();
		$obj->create(get_table_id('core_groups'),$this->getId($name));
		
		// Return the id of this new group
		return $this->getId($name);
	}
	
	function update($id,$name) {
		$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_groups SET name=:name, edited_id=:edited_id, edited_date=:edited_date WHERE id=:id');
		$q0->execute(array(	'id' => $id,
                            'name' => $name,
							'edited_id' => $_SESSION['USER_ID'],
							'edited_date' => time()
					));
	}

	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_groups SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
	
	function addGroupUserMap($groupId,$userId) {
		// On ajoute un lien entre utilisateur et groupe
        $q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_groups_users_map (group_id,user_id,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:group_id,:user_id,:created_id,:created_date,0,0,0,0)');
        $q0->execute(array(	'group_id' => $groupId,
                            'user_id' => $userId,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
				    ));
	}
	
	function deleteGroupUserMap($groupId,$userId) {
		// On ajoute un lien entre utilisateur et groupe
        $q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_groups_users_map SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE user_id=:user_id AND group_id=:group_id AND deleted_date=0');
        $q0->execute(array(	'group_id' => $groupId,
                            'user_id' => $userId,
							'deleted_id' => $_SESSION['USER_ID'],
							'deleted_date' => time()
				    ));
	}
	
	function getGroupUserMap($groupId,$userId) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_groups_users_map WHERE user_id=:user_id AND group_id=:group_id AND deleted_date=0");
		$q0->execute(array( "user_id" => $userId , "group_id" => $groupId ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}
}


class group extends dbEntry {
	var $name;
	
	// Builder
	function group($id){
		$this->id = $id;
		
		// Get the group informations
		$q0=get_link()->prepare("SELECT 
									grp.id AS ID,
									grp.name AS NAME,
									grp.created_date AS CREATED_DATE,
									grp.created_id AS CREATED_ID,
									grp.edited_date AS EDITED_DATE,
									grp.edited_id AS EDITED_ID,
									grp.deleted_date AS DELETED_DATE,
									grp.deleted_id AS DELETED_ID
								FROM ".get_ini('BDD_PREFIX')."core_groups grp 
								WHERE grp.id=:id"); 
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
			echo 'The group don\'t exist.';
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



}
*/
?>