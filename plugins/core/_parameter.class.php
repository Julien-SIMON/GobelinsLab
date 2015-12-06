<?php
class parameterManager {
	
	// Builder
	function parameterManager($idPlugin=1){
	}
	
	function getId($idPlugin,$name) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_parameters WHERE id_plugin=:idPlugin AND name=:name AND deleted_date=0");
		$q0->execute(array( "name" => $name , "idPlugin" => $idPlugin ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}
	
	function create($idPlugin,$name,$parameterValue,$defaultValue) {
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_parameters (id_plugin,name,parameter_value,default_value,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:id_plugin,:name,:parameter_value,:default_value,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'id_plugin' => $idPlugin,
                            'name' => $name,
                            'parameter_value' => $parameterValue,
                            'default_value' => $defaultValue,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
		
		return $this->getId($idPlugin,$name);
	}

	function update($id,$value) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_parameters SET parameter_value=:parameter_value, edited_id=:edited_id, edited_date=:edited_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'parameter_value' => $value,
								'edited_id' => $_SESSION['USER_ID'],
								'edited_date' => time()
						));
		}
	}
	
	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_parameters SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}


class parameter extends dbEntry {
	var $idPlugin;
	var $name;
	var $parameterValue;
	var $defaultValue;
	
	// Builder
	function parameter($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare("SELECT 
									param.id AS ID,
									param.id_plugin AS ID_PLUGIN,
									param.name AS NAME,
									param.parameter_value AS PARAMETER_VALUE,
									param.default_value AS DEFAULT_VALUE,
									param.created_date AS CREATED_DATE,
									param.created_id AS CREATED_ID,
									param.edited_date AS EDITED_DATE,
									param.edited_id AS EDITED_ID,
									param.deleted_date AS DELETED_DATE,
									param.deleted_id AS DELETED_ID
								FROM ".get_ini('BDD_PREFIX')."core_parameters param 
								WHERE param.id=:id"); 
		$q0->execute(array( 'id' => $id ));  
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->idPlugin = $r0->ID_PLUGIN;
			$this->name = $r0->NAME;
			$this->parameterValue = $r0->PARAMETER_VALUE;
			$this->defaultValue = $r0->DEFAULT_VALUE;
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
			echo 'The parameter don\'t exist.';
			exit(100);
		}
	}
}
?>