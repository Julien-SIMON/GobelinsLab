<?php
class userManager {
	// Builder
	function userManager(){
	}
	
	function getId($mail) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_users WHERE mail=:mail AND deleted_date=0");
		$q0->execute(array( "mail" => strtolower($mail) ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}
	
	function getIdByName($name) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_users WHERE name=:name AND deleted_date=0");
		$q0->execute(array( "name" => strtolower($name) )); 
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}
	
	function signIn($method,$login,$password) {
		$authM = new authManager();
		$authMethodM = new authMethodManager();
		
    	switch ($method) {
    	    case 'LOCAL':
				$q0=get_link()->prepare("SELECT a.user_id AS ID FROM ".get_ini('BDD_PREFIX')."core_user_auths a,".get_ini('BDD_PREFIX')."core_users u WHERE a.auth_id=:auth_id AND a.mail=:mail AND a.password=:password AND a.user_id=u.id AND a.deleted_date=0 AND u.deleted_date=0");
				$q0->execute(array( "auth_id" => $authMethodM->getId('LOCAL') , 'mail' => strtolower($login) , 'password' => hashWithSalt($password) ));
				$r0 = $q0->fetch(PDO::FETCH_OBJ);
				
				if(isset($r0->ID)) {
					return $r0->ID;
				}
    	    break;
    	    case 'LDAP':
				if(!$ad = ldap_connect(get_ini('LDAP_SERVERS'))){echo "Couldn't connect to ".get_ini('LDAP_SERVERS')."!";}
				if(!ldap_bind($ad,$login,$password)){echo "Couldn't bind the user ".$login."!";}
    	    	else{return $this->getIdByName(strtolower($login));}
    	    break;
    	    case 'FACEBOOK':
				$q0=get_link()->prepare("SELECT a.user_id AS ID FROM ".get_ini('BDD_PREFIX')."core_user_auths a,".get_ini('BDD_PREFIX')."core_users u WHERE a.auth_id=:auth_id AND a.password=:password AND a.user_id=u.id AND a.deleted_date=0  AND u.deleted_date=0");
				$q0->execute(array( "auth_id" => $authMethodM->getId('FACEBOOK') , 'password' => hashWithSalt($password) ));
				$r0 = $q0->fetch(PDO::FETCH_OBJ);
				
				if(isset($r0->ID)) {
					return $r0->ID;
				}
    	    break;
    	    case 'GOOGLE':
    	    	$q0=get_link()->prepare("SELECT a.user_id AS ID FROM ".get_ini('BDD_PREFIX')."core_user_auths a,".get_ini('BDD_PREFIX')."core_users u WHERE a.auth_id=:auth_id AND a.password=:password AND a.user_id=u.id AND a.deleted_date=0  AND u.deleted_date=0");
				$q0->execute(array( "auth_id" => $authMethodM->getId('GOOGLE') , 'password' => hashWithSalt($password) ));
				$r0 = $q0->fetch(PDO::FETCH_OBJ);
				
				if(isset($r0->ID)) {
					return $r0->ID;
				}
    	    break;
    	}
    	
    	return -1;
	}
	
	function create($name,$avatar,$mail) {
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_users (name,avatar,mail,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:name,:avatar,:mail,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'name' => strtolower($name),
							'avatar' => $avatar,
							'mail' => strtolower($mail),
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));

		// Create the object
		$obj = new objectManager();
		$obj->create(get_table_id('core_users'),$this->getId($mail));
		
		return $this->getId($mail);
	}

	function update($id,$name,$avatar,$mail,$lastName,$firstName) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_users SET name=:name,mail=:mail,avatar=:avatar,lastname=:lastname,firstname=:firstname,edited_id=:edited_id, edited_date=:edited_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'name' => $name,
								'mail' => strtolower($mail),
								'avatar' => $avatar,
								'firstname' => $firstName,
								'lastname' => $lastName,
								'edited_id' => $_SESSION['USER_ID'],
								'edited_date' => time()
						));
		}
	}

	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_users SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
	
	function register($method,$name,$password,$avatar,$lastName,$firstName,$mail,$isSendMail='TRUE') {
		$authM = new authManager();
		$authMethodM = new authMethodManager();
		$groupM = new groupManager();
		
		// Check if the user already exist
		if($mail!='' && mailCheck($mail) && $this->getId($mail) == 0) { // $authM->getId($userId,$methodId) && ($method,$mail,$password) == 0
		    // Generate a password
		    $generatedPassword=stringGenerate();
		    
		    if($isSendMail=='TRUE'){
				// On envoie le mail de confirmation avec le mot de passe		
				$sujet = 'Création d\'un compte '.get_ini('APPLICATION_NAME');
            	$message = '
            	            Bonjour,<br />
            	            <br />
            	            Voici vos identifiants pour l\'application '.get_ini('APPLICATION_NAME').'<br />
            	            Login: <strong>'.$mail.'</strong><br />
            	            Password: <strong>'.$generatedPassword.'</strong><br />
            	            <br />
            	            Merci!<br />
            	            <br />
            	            '.get_ini('ADMIN_MAIL').'
            	            ';
            	$destinataire = strtolower($mail);
            	$headers = "From: ".get_ini('ADMIN_MAIL')."\n";
            	$headers .= "Reply-To: ".get_ini('ADMIN_MAIL')."\n";
            	$headers .= "Content-Type: text/html; charset=\"UTF-8\"";
            	
            	if(!mail($destinataire,$sujet,$message,$headers)){
            	    echo "Une erreur c'est produite lors de l'envois de l'email.";
            	}
		    }
		    
		    // On créé l'utilisateur
			$q0_last_insert = $this->create($name,$avatar,$mail);
			
			// On ajoute la méthode de connexion locale
			$authM->create($q0_last_insert,$authMethodM->getId('LOCAL'),$name,$generatedPassword,$avatar,$lastName,$firstName,$mail);
			
			// On ajoute le groupe par défaut si il y en a un
            if( get_ini('default_group')!='' ) {
                $groupM->addGroupUserMap($groupM->getId(get_ini('default_group')),$q0_last_insert);
            }
			
			// On ajoute d'éventuelle autre méthode d'authentification (Facebook, google, ...)
    		switch ($method) {
    		    case 'LDAP':
    		        $authM->create($q0_last_insert,$authMethodM->getId('LDAP'),strtolower($name),'',get_ini('DEFAULT_AVATAR'),'','',$mail,'FALSE');
    		    break;
    		    case 'FACEBOOK':
    		        $authM->create($q0_last_insert,$authMethodM->getId('FACEBOOK'),$name,$password,$avatar,$lastName,$firstName,$mail);
    		    break;
    		    case 'GOOGLE':
    		        $authM->create($q0_last_insert,$authMethodM->getId('GOOGLE'),$name,$password,$avatar,$lastName,$firstName,$mail);
    		    break;
    		}
		} else {
			// Todo error
		}
	}
}


class user extends dbEntry {
	var $name;
	var $avatar;
	var $mail;
	var $objectId;
	var $groupIdArray;
	var $groupObjectIdArray;
	
	// Builder
	function user($id){
		$this->id = $id;
		
		if($_SESSION['USER_ID']>0) {
			// Get the user informations
			$q0=get_link()->prepare("SELECT 
										usr.id AS ID,
										usr.name AS NAME,
										usr.avatar AS AVATAR,
										usr.mail AS MAIL,
										obj.id AS OBJECTID,
										usr.created_date AS CREATED_DATE,
										usr.created_id AS CREATED_ID,
										usr.edited_date AS EDITED_DATE,
										usr.edited_id AS EDITED_ID,
										usr.deleted_date AS DELETED_DATE,
										usr.deleted_id AS DELETED_ID
									FROM 
									".get_ini('BDD_PREFIX')."core_users usr,
									".get_ini('BDD_PREFIX')."core_objects obj
									WHERE 
										usr.id=obj.id_ext AND
										obj.id_table=:id_table AND
										obj.deleted_date=0 AND
										usr.id=:id"); 
			$q0->execute(array( 'id' => $id , 'id_table' => get_table_id('core_users') ));
			$r0 = $q0->fetch(PDO::FETCH_OBJ);
			if(isset($r0->ID))
			{
				$this->name = $r0->NAME;
				$this->avatar = $r0->AVATAR;
				$this->mail = $r0->MAIL;
				$this->objectId = $r0->OBJECTID;
				$this->createdDate = $r0->CREATED_DATE;
				$this->createdID = $r0->CREATED_ID;
				$this->editedDate = $r0->EDITED_DATE;
				$this->editedId = $r0->EDITED_ID;
				$this->deletedDate = $r0->DELETED_DATE;
				$this->deltedId = $r0->DELETED_ID;
				
				// Map user groups and get the object id
				$this->groupIdArray = array();
				$this->groupObjectIdArray = array();
				$q1=get_link()->prepare("SELECT 
											obj.id AS OBJECTID,
											map.group_id AS GROUPID
										FROM 
											".get_ini('BDD_PREFIX')."core_groups_users_map map,
											".get_ini('BDD_PREFIX')."core_objects obj
										WHERE
											obj.deleted_date=0 AND
											map.deleted_date=0 AND
											map.user_id = :id AND
											map.group_id = obj.id_ext AND
											obj.id_table = :id_table
										");
				$q1->execute(array( 'id' => $this->id , 'id_table' => get_table_id('core_groups') ));
				$q1->setFetchMode(PDO::FETCH_OBJ);
				while( $r1 = $q1->fetch(PDO::FETCH_OBJ) )
				{
					array_push($this->groupIdArray,$r1->GROUPID);
				    array_push($this->groupObjectIdArray,$r1->OBJECTID);
				}
				$q1->closeCursor();
			}
			else
			{
				// TODO add log management
				echo 'The user don\'t exist.';
				exit(100);
			}
			$q0->closeCursor();
		} else { // If user is not login
			$groupM = new groupManager();
		
			$this->name = 'Guest';
			$this->avatar = get_ini('DEFAULT_AVATAR');
			$this->mail = '';
			$this->objectId = -1;
			$this->createdDate = 0;
			$this->createdID = 0;
			$this->editedDate = 0;
			$this->editedId = 0;
			$this->deletedDate = 0;
			$this->deltedId = 0;
			$this->groupObjectIdArray = array($groupM->getId('guests'));
		}
	}
	
	function updatePassword($password,$isSendMail='TRUE') {
		$authMethodM = new authMethodManager();
		
		// Generate a password
		$generatedPassword=stringGenerate();
		
		if($isSendMail=='TRUE'){
			// On envoie le mail de confirmation avec le mot de passe		
			$sujet = 'Modification du mot de passe - '.get_ini('APPLICATION_NAME');
        	$message = '
        	            Bonjour,<br />
        	            <br />
        	            Une réinitialisation de votre mot de passe a été demandée depuis l\'application '.get_ini('APPLICATION_NAME').'<br />
        	            Login: <strong>'.$this->mail.'</strong><br />
        	            Password: <strong>'.$generatedPassword.'</strong><br />
        	            <br />
        	            Merci!<br />
        	            <br />
        	            '.get_ini('ADMIN_MAIL').'
        	            ';
        	$destinataire = $this->mail;
        	$headers = "From: ".get_ini('ADMIN_MAIL')."\n";
        	$headers .= "Reply-To: ".get_ini('ADMIN_MAIL')."\n";
        	$headers .= "Content-Type: text/html; charset=\"UTF-8\"";
        	
        	if(mail($destinataire,$sujet,$message,$headers)){
        	    echo "L'email a bien été envoyé. Votre mot de passe s'y trouve.";
        	}
        	else {
        	    echo "Une erreur c'est produite lors de l'envois de l'email.";
        	}
		}
		    
		
		$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_user_auths SET password=:password,edited_id=:edited_id, edited_date=:edited_date WHERE user_id=:id AND auth_id=:auth_id');
		$q0->execute(array(	'id' => $this->id,
							'password' => hashWithSalt($generatedPassword),
							'auth_id' => $authMethodM->getId('LOCAL'),
							'edited_id' => $_SESSION['USER_ID'],
							'edited_date' => time()
					));
	}
}

/*
class user {
	var $id; // id du user 0=non définie
	
	
	function user($id=0) {
	    $this->id=$id;
	    
	    // On catch les méthodes d'authentification disponibles
	    get_auth_methods();
	}
	
	function signIn($method,$name,$password) {
	    
	}
	

	

	
	function getName() {
		$q0=get_link()->prepare("SELECT name AS NAME FROM ".get_ini('BDD_PREFIX')."users WHERE id=:id AND deleted_date=0");
		$q0->execute(array( "id" => $this->id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->NAME)) {
			return $r0->NAME;
		}
		else {
			return '';
		}
	}
	

	
	function delete() {
		// On supprime l'utilisateur
		$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'users SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
		$q0->execute(array(	'id' => $this->id,
							'deleted_id' => $_SESSION['USER_ID'],
							'deleted_date' => time()
					));
	}

	function update_name($name) {
		// On met à jour le nom de l'utilisateur
		$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'users SET name=:name, edited_id=:edited_id, edited_date=:edited_date WHERE id=:id');
		$q0->execute(array(	'id' => $this->id,
                            'name' => $name,
							'edited_id' => $_SESSION['USER_ID'],
							'edited_date' => time()
					));
	}
}
*/
?>