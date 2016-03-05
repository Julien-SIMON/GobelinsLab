<?php
class localeManager {
	
	// Builder
	function localeManager($idPlugin=1){
	}
	
	function getId($shortName) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_locale WHERE short_name=:shortName AND deleted_date=0");
		$q0->execute(array( "shortName" => $shortName ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}
	
	function create($shortName,$longName,$flagPath) {
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_locale (short_name,long_name,flag_path,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:short_name,:long_name,:flag_path,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'short_name' => $shortName,
                            'long_name' => $longName,
                            'flag_path' => $flagPath,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
		
		return $this->getId($shortName);
	}

	function update($id,$shortName,$longName,$flagPath) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_locale SET short_name=:short_name, long_name=:long_name, flag_path=:flag_path, edited_id=:edited_id, edited_date=:edited_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'short_name' => $shortName,
								'long_name' => $longName,
								'flag_path' => $flagPath,
								'edited_id' => $_SESSION['USER_ID'],
								'edited_date' => time()
						));
		}
	}
	
	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_locale SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}


class local extends dbEntry {
	var $shortName;
	var $longName;
	var $flagPath;
	
	// Builder
	function local($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare("SELECT 
									loc.id AS ID,
									loc.short_name AS SHORT_NAME,
									loc.long_name AS LONG_NAME,
									loc.flag_path AS FLAG_PATH,
									loc.created_date AS CREATED_DATE,
									loc.created_id AS CREATED_ID,
									loc.edited_date AS EDITED_DATE,
									loc.edited_id AS EDITED_ID,
									loc.deleted_date AS DELETED_DATE,
									loc.deleted_id AS DELETED_ID
								FROM 
								".get_ini('BDD_PREFIX')."core_locale loc
								WHERE 
								loc.id = :id
								"); 
		$q0->execute(array( 'id' => $id ));  
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->shortName = $r0->SHORT_NAME;
			$this->longName = $r0->LONG_NAME;
			$this->flagPath = $r0->FLAG_PATH;
			$this->createdDate = $r0->CREATED_DATE;
			$this->createdID = $r0->CREATED_ID;
			$this->editedDate = $r0->EDITED_DATE;
			$this->editedId = $r0->EDITED_ID;
			$this->deletedDate = $r0->DELETED_DATE;
			$this->deletedId = $r0->DELETED_ID;
		}
		else
		{
			// TODO add log management
			echo 'The locale don\'t exist.';
			exit(100);
		}
	}
}
?>