<?php
class osManager {
	var $idDevice;
	
	// Builder
	function osManager($idDevice){
		$this->idDevice=$idDevice;
	}
	
	function getId($type,$port) {
		$q0=get_link()->prepare('SELECT ID AS ID FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os WHERE id_device=:id_device AND os_type=:type AND os_port=:port AND deleted_date=0'); 
		$q0->execute(array( 'id_device' => $this->idDevice , 'type' => $type , 'port' => $port ));
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
	
	function create($type,$port) {
		$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_dev_os (id_device,os_type,os_name,os_port,os_install_date,os_last_boot_time,os_memory_size,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_device,:type,\'\',:port,0,0,0,:created_date,:created_id,0,0,0,0)'); 
		$q0->execute(array( 'id_device' => $this->idDevice , 'type' => $type , 'port' => $port , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
		
		// Create the object
		$obj=new objectManager();
		$obj->create(get_table_id('cmdb_dev_os'),$this->getId($type,$port));
		
		return $this->getId($type,$port);
	}
	
	function update($id,$name,$version,$type,$arch,$serial,$installDate,$lastBootTime,$memorySize) {
		$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_dev_os set os_type=:os_type,os_name=:os_name,os_version=:os_version,os_architecture=:os_architecture,os_serial_number=:os_serial_number,os_install_date=:os_install_date,os_last_boot_time=:os_last_boot_time,os_memory_size=:os_memory_size,edited_date=:edited_date,edited_id=:edited_id where id=:id'); 
		$q0->execute(array( 'id' => $id , 'os_type' => $type , 'os_name' => $name , 'os_version' => $version , 'os_architecture' => $arch , 'os_serial_number' => $serial , 'os_install_date' => $installDate , 'os_last_boot_time' => $lastBootTime , 'os_memory_size' => $memorySize , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
	}
	
	function delete($id=0) {
		if($id>0) {
			$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_dev_os set deleted_date=:deleted_date,deleted_id=:deleted_id where id=:id AND deleted_date=0'); 
			$q0->execute(array( 'id' => $id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
		}
	}
}


class os extends dbEntry {
	var $id;
	var $idDevice;
	var $name;
	var $type;
	var $port;
	var $memorySize;
	var $version;
	var $architecture;
	var $serialNumber;
	var $installDate;
	var $lastBootDate;
	var $audited;
	
	var $creds;
	
	// Builder
	function os($id){
		$this->id = $id;
		
		// Get the os informations
		$q0=get_link()->prepare('SELECT 
									o.id AS ID,
									o.id_device AS IDDEVICE,
									o.os_name AS OS_NAME,
									o.os_type AS OS_TYPE,
									o.os_port AS OS_PORT,
									o.os_version AS OS_VERSION,
									o.os_architecture AS OS_ARCHITECTURE,
									o.os_serial_number AS OS_SERIAL_NUMBER, 
									o.os_install_date AS OS_INSTALL_DATE,
									o.os_last_boot_time AS LAST_BOOT_TIME,
									o.os_memory_size AS MEMORY_SIZE,
									o.audited AS AUDITED,
									o.created_date AS CREATED_DATE,
									o.created_id AS CREATED_ID,
									o.edited_date AS EDITED_DATE,
									o.edited_id AS EDITED_ID,
									o.deleted_date AS DELETED_DATE,
									o.deleted_id AS DELETED_ID
								FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os o WHERE id=:id'); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->idDevice = $r0->IDDEVICE;
			$this->name = $r0->OS_NAME;
			$this->type = $r0->OS_TYPE;
			$this->port = $r0->OS_PORT;
			$this->memorySize = $r0->MEMORY_SIZE;if($this->memorySize == ''){$this->memorySize=0;}
			$this->version = $r0->OS_VERSION;
			$this->architecture = $r0->OS_ARCHITECTURE;
			$this->serialNumber = $r0->OS_SERIAL_NUMBER;
			$this->installDate = $r0->OS_INSTALL_DATE;
			$this->lastBootDate = $r0->LAST_BOOT_TIME;
			$this->audited = $r0->AUDITED;
			$this->createdDate = $r0->CREATED_DATE;
			$this->createdID = $r0->CREATED_ID;
			$this->editedDate = $r0->EDITED_DATE;
			$this->editedId = $r0->EDITED_ID;
			$this->deletedDate = $r0->DELETED_DATE;
			$this->deltedId = $r0->DELETED_ID;
			
			// Os credentials
			$this->creds = array();
			
			$q0=get_link()->prepare('SELECT ID AS ID, user_name AS USERNAME, pass_word AS PASSWORD, SUBSTITUTE_USER_NAME AS SUBUSERNAME, SUBSTITUTE_PASS_WORD AS SUBPASSWORD FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_creds WHERE id_os = :id_os AND deleted_date=0'); 
			$q0->execute(array( 'id_os' => $this->id ));
			$r0 = $q0->fetch(PDO::FETCH_OBJ);
			if(isset($r0->ID)) {
				$this->creds['id']=$r0->ID;
				$this->creds['login']=rijn::decrypt($r0->USERNAME);
				$this->creds['password']=rijn::decrypt($r0->PASSWORD);
				$this->creds['subLogin']=rijn::decrypt($r0->SUBUSERNAME);
				$this->creds['subPassword']=rijn::decrypt($r0->SUBPASSWORD);
			} elseif($this->type == 'windows') {
				$this->creds['login']=get_ini('CMDB_WINDOWS_DEFAULT_LOGIN');
				$this->creds['password']=get_ini('CMDB_WINDOWS_DEFAULT_PASSWORD');
				$this->creds['subLogin']='';
				$this->creds['subPassword']='';
			} elseif($this->type == 'linux') {
				$this->creds['login']=get_ini('CMDB_LINUX_DEFAULT_LOGIN');
				$this->creds['password']=get_ini('CMDB_LINUX_DEFAULT_PASSWORD');
				$this->creds['subLogin']=get_ini('CMDB_LINUX_DEFAULT_SUB_LOGIN');
				$this->creds['subPassword']=get_ini('CMDB_LINUX_DEFAULT_SUB_PASSWORD');
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
			echo 'The os does not exist.';
			exit(100);
		}
	}
	
	
	function updateCredentials($login,$password,$subLogin,$subPassword) {
		if(isset($this->creds['id'])) {
			$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_dev_os_creds set user_name=:user_name,pass_word=:pass_word,substitute_user_name=:substitute_user_name,substitute_pass_word=:substitute_pass_word,edited_date=:edited_date,edited_id=:edited_id where id=:id'); 
			$q0->execute(array( 'id' => $this->creds['id'] , 'user_name' => rijn::crypt($login) , 'pass_word' => rijn::crypt($password) , 'substitute_user_name' => rijn::crypt($subLogin) , 'substitute_pass_word' => rijn::crypt($subPassword) , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
		} else {
			$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_dev_os_creds (id_os,user_name,pass_word,substitute_user_name,substitute_pass_word,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_os,:user_name,:pass_word,:substitute_user_name,:substitute_pass_word,:created_date,:created_id,0,0,0,0)'); 
			$q0->execute(array( 'id_os' => $this->id , 'user_name' => rijn::crypt($login) , 'pass_word' => rijn::crypt($password) , 'substitute_user_name' => rijn::crypt($subLogin) , 'substitute_pass_word' => rijn::crypt($subPassword) , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID'] ));
			
			$q0=get_link()->prepare('SELECT ID AS ID FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_creds WHERE id_os = :id_os AND deleted_date=0'); 
			$q0->execute(array( 'id_os' => $this->id ));
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