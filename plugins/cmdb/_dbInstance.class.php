<?php
class dbInstanceManager {
	var $idOs;
	
	// Builder
	function dbInstanceManager($idOs){
		$this->idOs=$idOs;
	}
	
	function getId($name) {
		$q0=get_link()->prepare('SELECT id AS ID FROM '.get_ini('BDD_PREFIX').'cmdb_db_instances WHERE id_os=:id_os AND name=:name AND deleted_date=0'); 
		$q0->execute(array( 'id_os' => $this->idOs , 'name' => $name ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			return $r0->ID;
		}
		else
		{
			return 0;
		}
	}
	
	function create($name,$type,$version,$port,$path) {
		$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_db_instances (id_os,name,db_type,db_version,db_port,db_bin_path,audited,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_os,:name,:db_type,:db_version,:db_port,:db_bin_path,:audited,:created_date,:created_id,0,0,0,0)'); 
		$q0->execute(array( 'id_os' => $this->idOs , 'name' => $name , 'db_type' => $type , 'db_version' => $version , 'db_port' => $port , 'db_bin_path' => $path , 'audited' => '-1' , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
		
		// Create the object
		$obj=new objectManager();
		$obj->create(get_table_id('cmdb_db_instances'),$this->getId($name));
		
		return $this->getId($name);
	}
	
	function update($id,$name,$type,$version,$port,$path) {
		$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_db_instances set name=:name,db_type=:db_type,db_version=:db_version,db_port=:db_port,db_bin_path=:db_bin_path,edited_date=:edited_date,edited_id=:edited_id where id=:id'); 
		$q0->execute(array( 'id' => $id , 'name' => $name , 'db_type' => $type , 'db_version' => $version , 'db_port' => $port , 'db_bin_path' => $path , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
	}
	
	function delete($id=0) {
		if($id>0) {
			$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_db_instances set deleted_date=:deleted_date,deleted_id=:deleted_id where id=:id AND deleted_date=0'); 
			$q0->execute(array( 'id' => $id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
		}
	}
}


class dbInstance extends dbEntry {
	var $id;
	var $idOs;
	var $name;
	var $type;
	var $version;
	var $port;
	var $path;
	var $audited;
	
	var $creds;
	
	// Builder
	function dbInstance($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare('SELECT  
									i.id AS ID,
									i.id_os AS IDOS,
									i.name AS NAME,
									i.db_type AS DBTYPE,
									i.db_version AS DBVERSION,
									i.db_port AS DBPORT,
									i.db_bin_path AS DBBINPATH,
									i.audited AS AUDITED,
									i.created_date AS CREATED_DATE,
									i.created_id AS CREATED_ID,
									i.edited_date AS EDITED_DATE,
									i.edited_id AS EDITED_ID,
									i.deleted_date AS DELETED_DATE,
									i.deleted_id AS DELETED_ID
								FROM '.get_ini('BDD_PREFIX').'cmdb_db_instances i 
								WHERE i.id=:id AND i.deleted_date=0'); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->idOs = $r0->IDOS;
			$this->name = $r0->NAME;
			$this->type = $r0->DBTYPE;
			$this->version = $r0->DBVERSION;
			$this->port = $r0->DBPORT;
			$this->path = $r0->DBBINPATH;
			$this->audited = $r0->AUDITED;
			$this->createdDate = $r0->CREATED_DATE;
			$this->createdID = $r0->CREATED_ID;
			$this->editedDate = $r0->EDITED_DATE;
			$this->editedId = $r0->EDITED_ID;
			$this->deletedDate = $r0->DELETED_DATE;
			$this->deltedId = $r0->DELETED_ID;
			
			// Os credentials
			$this->creds = array();
			
			$q0=get_link()->prepare('SELECT ID AS ID, user_name AS USERNAME, pass_word AS PASSWORD, SUBSTITUTE_USER_NAME AS SUBUSERNAME, SUBSTITUTE_PASS_WORD AS SUBPASSWORD FROM '.get_ini('BDD_PREFIX').'cmdb_db_instance_creds WHERE id_instance = :id_instance AND deleted_date=0'); 
			$q0->execute(array( 'id_instance' => $this->id ));
			$r0 = $q0->fetch(PDO::FETCH_OBJ);
			if(isset($r0->ID)) {
				$this->creds['id']=$r0->ID;
				$this->creds['login']=rijn::decrypt($r0->USERNAME);
				$this->creds['password']=rijn::decrypt($r0->PASSWORD);
				$this->creds['subLogin']=rijn::decrypt($r0->SUBUSERNAME);
				$this->creds['subPassword']=rijn::decrypt($r0->SUBPASSWORD);
			//} elseif($this->type == 'oracle') {
			//	$this->creds['login']=get_ini('CMDB_WINDOWS_DEFAULT_LOGIN');
			//	$this->creds['password']=get_ini('CMDB_WINDOWS_DEFAULT_PASSWORD');
			//	$this->creds['subLogin']='';
			//	$this->creds['subPassword']='';
			//} elseif($this->type == 'mssqlserver') {
			//	$this->creds['login']=get_ini('CMDB_LINUX_DEFAULT_LOGIN');
			//	$this->creds['password']=get_ini('CMDB_LINUX_DEFAULT_PASSWORD');
			//	$this->creds['subLogin']=get_ini('CMDB_LINUX_DEFAULT_SUB_LOGIN');
			//	$this->creds['subPassword']=get_ini('CMDB_LINUX_DEFAULT_SUB_PASSWORD');
			//} elseif($this->type == 'mysql') {
			//	$this->creds['login']=get_ini('CMDB_LINUX_DEFAULT_LOGIN');
			//	$this->creds['password']=get_ini('CMDB_LINUX_DEFAULT_PASSWORD');
			//	$this->creds['subLogin']=get_ini('CMDB_LINUX_DEFAULT_SUB_LOGIN');
			//	$this->creds['subPassword']=get_ini('CMDB_LINUX_DEFAULT_SUB_PASSWORD');
			} else {
				$this->creds['login']='';
				$this->creds['password']='';
				$this->creds['subLogin']='';
				$this->creds['subPassword']='';
			}
		}
		else
		{
			// TODO add log management
			echo 'The database instance does not exist.';
			exit(100);
		}
	}
	// !builder
	
	function updateCredentials($login,$password,$subLogin,$subPassword) {
		if(isset($this->creds['id'])) {
			$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_db_instance_creds set user_name=:user_name,pass_word=:pass_word,substitute_user_name=:substitute_user_name,substitute_pass_word=:substitute_pass_word,edited_date=:edited_date,edited_id=:edited_id where id=:id'); 
			$q0->execute(array( 'id' => $this->creds['id'] , 'user_name' => rijn::crypt($login) , 'pass_word' => rijn::crypt($password) , 'substitute_user_name' => rijn::crypt($subLogin) , 'substitute_pass_word' => rijn::crypt($subPassword) , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
		} else {
			$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_db_instance_creds (id_instance,user_name,pass_word,substitute_user_name,substitute_pass_word,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_instance,:user_name,:pass_word,:substitute_user_name,:substitute_pass_word,:created_date,:created_id,0,0,0,0)'); 
			$q0->execute(array( 'id_instance' => $this->id , 'user_name' => rijn::crypt($login) , 'pass_word' => rijn::crypt($password) , 'substitute_user_name' => rijn::crypt($subLogin) , 'substitute_pass_word' => rijn::crypt($subPassword) , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID'] ));
			
			$q0=get_link()->prepare('SELECT ID AS ID FROM '.get_ini('BDD_PREFIX').'cmdb_db_instance_creds WHERE id_instance = :id_instance AND deleted_date=0'); 
			$q0->execute(array( 'id_instance' => $this->id ));
			$r0 = $q0->fetch(PDO::FETCH_OBJ);
			if(isset($r0->ID))
			{
				$this->creds['id']=$r0->ID;
			}
			else
			{
				// TODO
				exit(100);
			}
			
		}
		
		$this->creds['login']=$login;
		$this->creds['password']=$password;
		$this->creds['subLogin']=$subLogin;
		$this->creds['subPassword']=$subPassword;
	}
}
?>