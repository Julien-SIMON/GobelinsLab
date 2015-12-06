<?php
class authManager {
	// Builder
	function authManager(){
	}
	
	function getId($userId,$methodId) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_user_auths WHERE user_id=:user_id AND auth_id=:auth_id AND deleted_date=0");
		$q0->execute(array( "user_id" => $userId , "auth_id" => $methodId ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}

	function create($userId,$methodId,$name,$password,$avatar,$lastName,$firstName,$mail) {
		if(strtoupper(get_ini('BDD_TYPE'))=='ORACLE'){
			$s0 = get_link()->prepare('SELECT '.get_ini('BDD_PREFIX').'CORE_USER_AUTHS_ID_SEQ.NEXTVAL AS ID FROM DUAL');
			$s0->execute();
			$id = $s0->fetchColumn(0);
		} else {
			$id='';
		}
		
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_user_auths ( id, auth_id, user_id, password, avatar, lastname, firstname, mail, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES 
		                                                                            ( :id, :auth_id, :user_id, :password, :avatar, :lastname, :firstname, :mail, :created_id,:created_date, 0, 0, 0, 0)');
		$q0->execute(array(	'id' => $id,
					'auth_id' => $methodId,
					'user_id' => $userId,
					'password' => $password,
					'avatar' => $avatar,
					'lastname' => $lastName,
					'firstname' => $firstName,
					'mail' => strtolower($mail),
					'created_id' => $_SESSION['USER_ID'],
					'created_date' => time()
		        	));
	
		if(strtoupper(get_ini('BDD_TYPE'))!='ORACLE'){
			$id=get_link()->lastInsertId();
		}
		
		return $id;
	}

	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_user_auths SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}


class auth extends dbEntry {
	var $name;
	
	// Builder
	function group($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare("SELECT 
									ath.id AS ID,
									ath.name AS NAME,
									ath.created_date AS CREATED_DATE,
									ath.created_id AS CREATED_ID,
									ath.edited_date AS EDITED_DATE,
									ath.edited_id AS EDITED_ID,
									ath.deleted_date AS DELETED_DATE,
									ath.deleted_id AS DELETED_ID
								FROM ".get_ini('BDD_PREFIX')."core_user_auths ath 
								WHERE ath.id=:id"); 
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