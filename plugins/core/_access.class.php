<?php
class accessManager {
	// Builder
	function accessManager(){
	}
	
	function getLevel($targetObjectId) {
		$user=new user($_SESSION['USER_ID']);
		
		$groupObjectIdList = "'".implode("','", $user->groupObjectIdArray)."'";
		//echo '<BR>'.$groupObjectIdList.'<BR><BR>';
		$q0=get_link()->prepare("SELECT MAX(secure_level) AS SECURELEVEL FROM ".get_ini('BDD_PREFIX')."core_access WHERE id_target=:id_target AND (id_source=:id_source OR id_source IN (".$groupObjectIdList.")) AND deleted_date=0");
		$q0->execute(array( "id_target" => $targetObjectId , "id_source" => $user->objectId ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->SECURELEVEL)) {
			return $r0->SECURELEVEL;
		}
		else {
			return -1;
		}
	}

	function getId($sourceId,$targetId) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_access WHERE id_target=:id_target AND id_source=:id_source AND deleted_date=0");
		$q0->execute(array( "id_target" => $targetId , "id_source" => $sourceId ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}

	function create($sourceId,$targetId,$secureLevel) {
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_access (id_target,id_source,secure_level,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:id_target,:id_source,:secure_level,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'id_target' => $targetId,
							'id_source' => $sourceId,
							'secure_level' => $secureLevel,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
		
		// Return the id of this new secure level
		return $this->getId($sourceId,$targetId);
	}
/*
	function update($id,$name,$icon) {
		$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_user_auth_methods SET name=:name, icon=:icon, edited_id=:edited_id, edited_date=:edited_date WHERE id=:id');
		$q0->execute(array(	'id' => $id,
                            'name' => $name,
                            'icon' => $icon,
							'edited_id' => $_SESSION['USER_ID'],
							'edited_date' => time()
					));
	}
*/
	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_access SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}

class access extends dbEntry {
	var $secureLevel;
	
	// Builder
	function access($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare("SELECT 
									sec.id AS ID,
									sec.id_source AS IDSOURCE,
									sec.id_target AS IDTARGET,
									sec.secure_level AS SECURELEVEL,
									sec.created_date AS CREATED_DATE,
									sec.created_id AS CREATED_ID,
									sec.edited_date AS EDITED_DATE,
									sec.edited_id AS EDITED_ID,
									sec.deleted_date AS DELETED_DATE,
									sec.deleted_id AS DELETED_ID
								FROM ".get_ini('BDD_PREFIX')."core_access sec 
								WHERE sec.id=:id"); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->secureLevel = $r0->SECURELEVEL;
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
			echo 'The access don\'t exist.';
			exit(100);
		}
	}
}
?>