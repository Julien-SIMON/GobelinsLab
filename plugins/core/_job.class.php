<?php
class jobManager {
	// Builder
	function jobManager(){
	}
	
	function getId($idPlugin,$page) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_job WHERE id_plugin=:id_plugin AND page=:page AND deleted_date=0");
		$q0->execute(array( "id_plugin" => $idPlugin , "page" => $page ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}

	function create($idPlugin,$page) {
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_job (id_plugin,page,last_run_pid,last_run,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:id_plugin,:page,0,0,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'id_plugin' => $idPlugin,
							'page' => $page,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
					
		// Create the object
		$obj=new objectManager();
		$obj->create(get_table_id('core_job'),$this->getId($idPlugin,$page));
		
		// Return the id of this new group
		return $this->getId($idPlugin,$page);
	}
	
	function update($id,$idPlugin,$page) {
		$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_job SET id_plugin=:id_plugin, page=:page, edited_id=:edited_id, edited_date=:edited_date WHERE id=:id');
		$q0->execute(array(	'id' => $id,
							'id_plugin' => $idPlugin,
							'page' => $page,
							'edited_id' => $_SESSION['USER_ID'],
							'edited_date' => time()
					));
	}

	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_job SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}


class job extends dbEntry {
	var $idPlugin;
	var $page;
	
	// Builder
	function job($id){
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
			echo 'The job don\'t exist.';
			exit(100);
		}
	}
	
	function addScheduled($groupId,$userId) {
		// On ajoute un lien entre utilisateur et groupe
        $q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_groups_users_map (group_id,user_id,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:group_id,:user_id,:created_id,:created_date,0,0,0,0)');
        $q0->execute(array(	'group_id' => $groupId,
                            'user_id' => $userId,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
				    ));
	}
	
	function deleteScheduled($groupId,$userId) {
		// On ajoute un lien entre utilisateur et groupe
        $q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_groups_users_map SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE user_id=:user_id AND group_id=:group_id AND deleted_date=0');
        $q0->execute(array(	'group_id' => $groupId,
                            'user_id' => $userId,
							'deleted_id' => $_SESSION['USER_ID'],
							'deleted_date' => time()
				    ));
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