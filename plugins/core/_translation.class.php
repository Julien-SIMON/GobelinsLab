<?php
class translationManager {
	
	// Builder
	function translationManager($idPlugin=1){
	}
	
	function getId($idPlugin,$idLocale,$indexTranslation) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_translation WHERE id_plugin=:idPlugin AND id_locale=:idLocale AND index_translation=:indexTranslation AND  deleted_date=0");
		$q0->execute(array( "indexTranslation" => $indexTranslation , "idLocale" => $idLocale , "idPlugin" => $idPlugin ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}
	
	function create($idPlugin,$idLocale,$indexTranslation,$translation,$comment) {
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_translation (id_plugin,id_locale,index_translation,translation,comment,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:id_plugin,:id_locale,:index_translation,:translation,:comment,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'id_plugin' => $idPlugin,
                            'id_locale' => $idLocale,
                            'index_translation' => $indexTranslation,
                            'translation' => $translation,
                            'comment' => $comment,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
		
		return $this->getId($idPlugin,$idLocale,$indexTranslation);
	}

	function update($id,$translation,$comment) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_translation SET translation=:translation, comment=:comment, edited_id=:edited_id, edited_date=:edited_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'translation' => $translation,
								'comment' => $comment,
								'edited_id' => $_SESSION['USER_ID'],
								'edited_date' => time()
						));
		}
	}
	
	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_translation SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}


class translation extends dbEntry {
	var $idPlugin;
	var $idLocale;
	var $indexTranslation;
	var $translation;
	
	// Builder
	function parameter($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare("SELECT 
									trans.id AS ID,
									trans.id_plugin AS ID_PLUGIN,
									trans.id_locale AS ID_LOCALE,
									trans.index_translation AS INDEX_TRANSLATION,
									trans.translation AS TRANSLATION,
									trans.created_date AS CREATED_DATE,
									trans.created_id AS CREATED_ID,
									trans.edited_date AS EDITED_DATE,
									trans.edited_id AS EDITED_ID,
									trans.deleted_date AS DELETED_DATE,
									trans.deleted_id AS DELETED_ID
								FROM ".get_ini('BDD_PREFIX')."core_translation trans 
								WHERE trans.id=:id"); 
		$q0->execute(array( 'id' => $id ));  
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->idPlugin = $r0->ID_PLUGIN;
			$this->idLocale = $r0->ID_LOCALE;
			$this->indexTranslation = $r0->INDEX_TRANSLATION;
			$this->translation = $r0->TRANSLATION;
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
			echo 'The translation don\'t exist.';
			exit(100);
		}
	}
}
?>