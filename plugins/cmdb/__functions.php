<?php
require('plugins/cmdb/_environment.class.php');
require('plugins/cmdb/_project.class.php');
require('plugins/cmdb/_device.class.php');
require('plugins/cmdb/_server.class.php');
require('plugins/cmdb/_os.class.php');
require('plugins/cmdb/_disk.class.php');
require('plugins/cmdb/_cpu.class.php');
require('plugins/cmdb/_fs.class.php');
require('plugins/cmdb/_service.class.php');
require('plugins/cmdb/_dbInstance.class.php');

/*
class environments {
	
	// Builder
	function environments(){
	}
	
	function get_id($name) {
		$q0=get_link()->prepare("SELECT ID AS ID FROM GL_CMDB_ENVIRONMENTS WHERE NAME = :name AND deleted_date=0"); 
		$q0->execute(array( 'name' => $name	));
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
	
	function create($name,$id_parent=0) {
		$q0=get_link()->prepare("insert into GL_CMDB_ENVIRONMENTS (id_parent,name,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_parent,:name,:created_date,:created_id,0,0,0,0)"); 
		$q0->execute(array( 'id_parent' => $id_parent , 'name' => $name , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID'] ));

		return $this->get_id($name);
	}
	
	function update($id,$id_parent=0) {
		$q0=get_link()->prepare("update GL_CMDB_ENVIRONMENTS set id_parent=:id_parent,edited_date=:edited_date,edited_id=:edited_id,deleted_date=0,deleted_id=0 where id=:id"); 
		$q0->execute(array( 'id_parent' => $id_parent , 'id' => $id , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
	}
	
	function delete($id=0) {
		if($id>0) {
			$q0=get_link()->prepare("update GL_CMDB_ENVIRONMENTS set deleted_date=:deleted_date,deleted_id=:deleted_id where id=:id"); 
			$q0->execute(array( 'id' => $id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
		}
	}
}


class projects {
	
	// Builder
	function projects(){
	}
	
	function get_id($name) {
		$q0=get_link()->prepare("SELECT ID AS ID FROM GL_CMDB_PROJECTS WHERE NAME = :name AND deleted_date=0"); 
		$q0->execute(array( 'name' => $name	));
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
	
	function create($name,$id_parent) {
		$q0=get_link()->prepare("insert into GL_CMDB_PROJECTS (id_parent,name,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_parent,:name,:created_date,:created_id,0,0,0,0)"); 
		$q0->execute(array( 'id_parent' => $id_parent , 'name' => $name , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID'] ));

		return $this->get_id($name);
	}
	
	function update($id,$id_parent) {
		$q0=get_link()->prepare("update GL_CMDB_PROJECTS set id_parent=:id_parent,edited_date=:edited_date,edited_id=:edited_id,deleted_date=0,deleted_id=0 where id=:id"); 
		$q0->execute(array( 'id_parent' => $id_parent , 'id' => $id , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
	}
	
	function delete($id) {
		if($id>0) {
			$q0=get_link()->prepare("update CMDB_PROJECTS set deleted_date=:deleted_date,deleted_id=:deleted_id where name=:name AND deleted_date=0"); 
			$q0->execute(array( 'name' => $name , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
		}
	}
}
*/

//class devices {
//	
//	var $infos;
//	// Builder
//	function devices(){
//	}
//	
//	function get_id($name) {
//		$q0=get_link()->prepare("SELECT ID AS ID FROM GL_CMDB_DEVICES WHERE NAME = :name"); 
//		$q0->execute(array( 'name' => $name	));
//		$r0 = $q0->fetch(PDO::FETCH_OBJ);
//		if(isset($r0->ID))
//		{
//			return $r0->ID;
//		}
//		else
//		{
//			return 0;
//		}
//	}
//	
//	/*
//	function get_infos($id) {
//		$this->infos=array();
//		
//		$select=$this->connexion->prepare("
//SELECT 
//d.id AS ID,
//d.name AS NAME,
//d.typename AS TYPENAME,
//d.audited AS AUDITED,
//d.checked AS CHECKED,
//d.comments AS COMMENTS,
//d.created_date AS CREATED_DATE,
//d.created_id AS CREATED_ID,
//d.edited_date AS EDITED_DATE,
//d.edited_id AS EDITED_ID,
//d.deleted_date AS DELETED_DATE,
//d.deleted_id AS DELETED_ID
//FROM CMDB_DEVICES D WHERE ID = :id
//		"); 
//		$select->execute(array( 'id' => $id	));
//		$select->bindColumn(6, $commentsClob, PDO::PARAM_LOB); // la colonne comments est un Clob 
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->NAME))
//		{
//			$this->infos['ID']=$result->ID;
//			$this->infos['NAME']=$result->NAME;
//			$this->infos['TYPENAME']=$result->TYPENAME;
//			$this->infos['AUDITED']=$result->AUDITED;
//			$this->infos['CHECKED']=$result->CHECKED;
//			if($commentsClob==''){$this->infos['COMMENTS']='';}else{$this->infos['COMMENTS']=stream_get_contents($commentsClob);}
//			
//			$this->infos['CREATED_DATE']=$result->CREATED_DATE;
//			$this->infos['CREATED_ID']=$result->CREATED_ID;
//			$this->infos['EDITED_DATE']=$result->EDITED_DATE;
//			$this->infos['EDITED_ID']=$result->EDITED_ID;
//			$this->infos['DELETED_DATE']=$result->DELETED_DATE;
//			$this->infos['DELETED_ID']=$result->DELETED_ID;
//			
//			
//			$this->infos['PRO_ENV_MAP_INDEX']=array(); // Tableaux listant les projets/environements attachés à l'équipement
//			$this->infos['PRO_ENV_MAP']=array();
//		}
//		
//		$select=$this->connexion->prepare("
//SELECT
//map.id AS MAP_ID,
//env.name AS ENVIRONMENT_NAME,
//pro.name AS PROJECT_NAME,
//map.created_date AS CREATED_DATE,
//map.created_id AS CREATED_ID,
//map.edited_date AS EDITED_DATE,
//map.edited_id AS EDITED_ID,
//map.deleted_date AS DELETED_DATE,
//map.deleted_id AS DELETED_ID
//FROM CMDB_DEVICES_PRO_ENV_MAP MAP, CMDB_PROJECTS PRO, CMDB_ENVIRONMENTS ENV 
//WHERE map.ID_DEVICE = :id AND pro.ID = map.ID_PROJECT AND env.ID = map.ID_ENVIRONMENT
//		"); 
//		$select->execute(array( 'id' => $id	));
//		while( $result = $select->fetch(PDO::FETCH_OBJ) )
//		{
//			if(isset($result->MAP_ID))
//			{
//				if(!in_array($result->MAP_ID,$this->infos['PRO_ENV_MAP_INDEX'])){
//					array_push($this->infos['PRO_ENV_MAP_INDEX'], $result->MAP_ID);
//					$this->infos['PRO_ENV_MAP'][$result->MAP_ID]['ENVIRONMENT_NAME']=$result->ENVIRONMENT_NAME;
//					$this->infos['PRO_ENV_MAP'][$result->MAP_ID]['PROJECT_NAME']=$result->PROJECT_NAME;
//				}
//			}
//		}
//	}
//	
//	function is_audited($id) {
//		$select=$this->connexion->prepare("SELECT audited AS AUDITED FROM CMDB_DEVICES WHERE ID = :id"); 
//		$select->execute(array( 'id' => $id	));
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->AUDITED))
//		{
//			return $result->AUDITED;
//		}
//		else
//		{
//			return '';
//		}
//	}
//	
//	function is_checked($id) {
//		$select=$this->connexion->prepare("SELECT checked AS CHECKED FROM CMDB_DEVICES WHERE ID = :id"); 
//		$select->execute(array( 'id' => $id	));
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->CHECKED))
//		{
//			return $result->CHECKED;
//		}
//		else
//		{
//			return '';
//		}
//	}
//	*/
//	function create($name,$id_parent) {
//		$q0=get_link()->prepare("insert into GL_CMDB_DEVICES (id_parent,name,typename,audited,checked,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_parent,:name,:typename,:audited,:checked,:created_date,:created_id,0,0,0,0)"); 
//		$q0->execute(array( 'id_parent' => $id_parent , 'name' => $name , 'typename' => 'server' , 'audited' => '-1' , 'checked' => '-1' , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID'] ));
//
//		return $this->get_id($name);
//	}
//	
//	function update($id,$id_parent) {
//		$q0=get_link()->prepare("update GL_CMDB_DEVICES set id_parent=:id_parent,edited_date=:edited_date,edited_id=:edited_id,deleted_date=0,deleted_id=0 where id=:id"); 
//		$q0->execute(array( 'id_parent' => $id_parent , 'id' => $id , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
//	}
//	
//	function delete($id=0) {
//		if($id>0) {
//			$q0=get_link()->prepare("update GL_CMDB_DEVICES set deleted_date=:deleted_date,deleted_id=:deleted_id where id=:id AND deleted_date=0"); 
//			$q0->execute(array( 'id' => $id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
//		}
//	}
//	
//	//
//	// MDPE = Map device project environment
//	function get_id_mdpe($deviceId,$projectId,$environmentId) { // Check if a mapping exist between device/project/environment
//		$q0=get_link()->prepare("SELECT ID AS ID FROM GL_CMDB_DEV_PRO_ENV_MAP WHERE id_device = :id_device AND id_project = :id_project AND id_environment = :id_environment AND deleted_date=0"); 
//		$q0->execute(array( 'id_device' => $deviceId , 'id_project' => $projectId , 'id_environment' => $environmentId ));
//		$r0 = $q0->fetch(PDO::FETCH_OBJ);
//		if(isset($r0->ID))
//		{
//			return $r0->ID;
//		}
//		else
//		{
//			return 0;
//		}
//	}
//	
//	function create_mdpe($deviceId,$projectId,$environmentId) {
//		$q0=get_link()->prepare("insert into GL_CMDB_DEV_PRO_ENV_MAP (id_device,id_project,id_environment,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_device,:id_project,:id_environment,:created_date,:created_id,0,0,0,0)"); 
//		$q0->execute(array( 'id_device' => $deviceId , 'id_project' => $projectId , 'id_environment' => $environmentId , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID'] ));
//		
//		return $this->get_id_mdpe($deviceId,$projectId,$environmentId);
//	}
//	/*
//	function update_mdpe($deviceId,$environmentId,$projectId) {
//		$q0=get_link()->prepare("update GL_CMDB_DEV_PRO_ENV_MAP set edited_date=:edited_date,edited_id=:edited_id,deleted_date=0,deleted_id=0 where id_device = :id_device AND id_project = :id_project AND id_environment = :id_environment"); 
//		$q0->execute(array( 'id_device' => $deviceId , 'id_project' => $projectId , 'id_environment' => $environmentId , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
//	}
//	*/
//	function delete_mdpe($id=0) {
//		if($id>0) {
//			$q0=get_link()->prepare("update GL_CMDB_DEV_PRO_ENV_MAP set deleted_date=:deleted_date,deleted_id=:deleted_id where id = :id"); 
//			$q0->execute(array( 'id' => $id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
//		}
//	}
//	//
//}


//class server extends devices {
//	var $connexion; // Le lien de connexion
//	
//	var $hostname;
//	var $id_device;
//	
//	var $os_infos;
//	
//	var $cpus_infos;
//	var $cpus_index;
//	
//	var $disks_infos;
//	var $disks_index;
//	
//	var $partitions_infos;
//	var $partitions_index;
//	
//	var $services_infos;
//	var $services_index;
//	
//	var $databases_infos;
//	var $databases_index;
//	
//	// Constructeur
//	function server($connexion,$hostname){
//		$this->connexion=$connexion;
//		
//		$this->hostname=$hostname;
//		
//		$this->id_device=$this->exist($this->hostname);	
//	}
//	// Fin constructeur
//	
//	function exist($name) {
//		$select=$this->connexion->prepare("SELECT ID AS ID FROM CMDB_DEVICES WHERE NAME = :name"); 
//		$select->execute(array( 'name' => $name	));
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->ID))
//		{
//			return $result->ID;
//		}
//		else
//		{
//			return 0;
//		}
//	}
//	
//
//	
//	function cpus_exist($logical_id) {
//		$select=$this->connexion->prepare("SELECT ID AS ID FROM CMDB_DEVICES_CPUS WHERE ID_DEVICE = :id_device AND LOGICAL_ID = :logical_id"); 
//		$select->execute(array( 'id_device' => $this->id_device , 'logical_id' => $logical_id ));
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->ID))
//		{
//			return $result->ID;
//		}
//		else
//		{
//			return 0;
//		}
//	}
//	
//	function get_cpus_infos() {
//		$this->cpus_infos=array();
//		$this->cpus_index=array();
//
//		$select=$this->connexion->prepare("
//SELECT 
//c.id AS ID,
//c.logical_id AS LOGICAL_ID,
//c.max_clock_speed AS MAX_CLOCK_SPEED,
//c.name AS NAME,
//c.created_date AS CREATED_DATE,
//c.created_id AS CREATED_ID,
//c.edited_date AS EDITED_DATE,
//c.edited_id AS EDITED_ID,
//c.deleted_date AS DELETED_DATE,
//c.deleted_id AS DELETED_ID
//FROM CMDB_DEVICES_CPUS C WHERE id_device=:id_device AND deleted_date=0
//		"); 
//		$select->execute(array( 'id_device' => $this->id_device ));
//		while( $result = $select->fetch(PDO::FETCH_OBJ) )
//		{
//			if(isset($result->ID))
//			{
//				array_push($this->cpus_index, $result->LOGICAL_ID);
//			
//				$this->cpus_infos[$result->LOGICAL_ID]['ID']=$result->ID;
//				$this->cpus_infos[$result->LOGICAL_ID]['LOGICAL_ID']=$result->LOGICAL_ID;
//				$this->cpus_infos[$result->LOGICAL_ID]['MAX_CLOCK_SPEED']=$result->MAX_CLOCK_SPEED;
//				$this->cpus_infos[$result->LOGICAL_ID]['NAME']=$result->NAME;
//
//				$this->cpus_infos[$result->LOGICAL_ID]['CREATED_DATE']=$result->CREATED_DATE;
//				$this->cpus_infos[$result->LOGICAL_ID]['CREATED_ID']=$result->CREATED_ID;
//				$this->cpus_infos[$result->LOGICAL_ID]['EDITED_DATE']=$result->EDITED_DATE;
//				$this->cpus_infos[$result->LOGICAL_ID]['EDITED_ID']=$result->EDITED_ID;
//				$this->cpus_infos[$result->LOGICAL_ID]['DELETED_DATE']=$result->DELETED_DATE;
//				$this->cpus_infos[$result->LOGICAL_ID]['DELETED_ID']=$result->DELETED_ID;
//			}
//		}
//	}
//	
//	function add_cpus($logical_id) {
//		$add=$this->connexion->prepare("insert into CMDB_DEVICES_CPUS (id_device,logical_id,max_clock_speed,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_device,:logical_id,0,:created_date,:created_id,0,0,0,0)"); 
//		$add->execute(array( 'id_device' => $this->id_device , 'logical_id' => $logical_id , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
//
//		return 0;
//	}
//
//	function update_cpus($logical_id,$max_clock_speed,$name) {
//		$update=$this->connexion->prepare("update CMDB_DEVICES_CPUS set max_clock_speed=:max_clock_speed,name=:name,edited_date=:edited_date,edited_id=:edited_id,deleted_date=:deleted_date,deleted_id=:deleted_id where id_device=:id_device AND logical_id=:logical_id"); 
//		$update->execute(array( 'id_device' => $this->id_device , 'logical_id' => $logical_id , 'max_clock_speed' => $max_clock_speed , 'name' => $name , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] , 'deleted_date' => '0' , 'deleted_id' => '0' ));
//		
//		return 0;
//	}
//	
//	function delete_cpus($logical_id) {
//		$delete=$this->connexion->prepare("update CMDB_DEVICES_CPUS set deleted_date=:deleted_date,deleted_id=:deleted_id where id_device = :id_device AND logical_id = :logical_id AND deleted_date=0"); 
//		$delete->execute(array( 'id_device' => $this->id_device , 'logical_id' => $logical_id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
//
//		return 0;
//	}
//	
//	function disk_exist($name) {
//		$select=$this->connexion->prepare("SELECT ID AS ID FROM CMDB_DEVICE_DISKS WHERE ID_DEVICE = :id_device AND NAME = :name"); 
//		$select->execute(array( 'id_device' => $this->id_device , 'name' => $name ));
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->ID))
//		{
//			return $result->ID;
//		}
//		else
//		{
//			return 0;
//		}
//	}
//
//	
//	function partition_exist($name) {
//		$select=$this->connexion->prepare("SELECT ID AS ID FROM CMDB_DEVICE_PARTITIONS WHERE ID_DEVICE = :id_device AND NAME = :name"); 
//		$select->execute(array( 'id_device' => $this->id_device , 'name' => $name ));
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->ID))
//		{
//			return $result->ID;
//		}
//		else
//		{
//			return 0;
//		}
//	}
//	
//	function get_partitions_infos() {
//		$this->partitions_infos=array();
//		$this->partitions_index=array();
//
//		$select=$this->connexion->prepare("
//SELECT 
//p.id AS ID,
//p.name AS NAME,
//p.filesystem AS FILESYSTEM,
//p.partition_freespace AS PARTITION_FREESPACE,
//p.partition_size AS PARTITION_SIZE,
//p.alias AS ALIAS,
//p.created_date AS CREATED_DATE,
//p.created_id AS CREATED_ID,
//p.edited_date AS EDITED_DATE,
//p.edited_id AS EDITED_ID,
//p.deleted_date AS DELETED_DATE,
//p.deleted_id AS DELETED_ID
//FROM CMDB_DEVICE_PARTITIONS P WHERE id_device=:id_device AND deleted_date=0
//		"); 
//		$select->execute(array( 'id_device' => $this->id_device ));
//		while( $result = $select->fetch(PDO::FETCH_OBJ) )
//		{
//			if(isset($result->ID))
//			{
//				array_push($this->partitions_index, $result->NAME);
//			
//				$this->partitions_infos[$result->NAME]['ID']=$result->ID;
//				$this->partitions_infos[$result->NAME]['NAME']=$result->NAME;
//				$this->partitions_infos[$result->NAME]['FILESYSTEM']=$result->FILESYSTEM;
//				$this->partitions_infos[$result->NAME]['PARTITION_FREESPACE']=$result->PARTITION_FREESPACE;
//				$this->partitions_infos[$result->NAME]['PARTITION_SIZE']=$result->PARTITION_SIZE;
//				$this->partitions_infos[$result->NAME]['ALIAS']=$result->ALIAS;
//                                 
//				$this->partitions_infos[$result->NAME]['CREATED_DATE']=$result->CREATED_DATE;
//				$this->partitions_infos[$result->NAME]['CREATED_ID']=$result->CREATED_ID;
//				$this->partitions_infos[$result->NAME]['EDITED_DATE']=$result->EDITED_DATE;
//				$this->partitions_infos[$result->NAME]['EDITED_ID']=$result->EDITED_ID;
//				$this->partitions_infos[$result->NAME]['DELETED_DATE']=$result->DELETED_DATE;
//				$this->partitions_infos[$result->NAME]['DELETED_ID']=$result->DELETED_ID;
//			}
//		}
//	}
//	
//	function add_partition($name) {
//		$add=$this->connexion->prepare("insert into CMDB_DEVICE_PARTITIONS (id_device,name,partition_freespace,partition_size,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_device,:name,0,0,:created_date,:created_id,0,0,0,0)"); 
//		$add->execute(array( 'id_device' => $this->id_device , 'name' => $name , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
//
//		return 0;
//	}
//
//	function update_partition($name,$filesystem,$partition_freespace,$partition_size,$alias) {
//		$update=$this->connexion->prepare("update CMDB_DEVICE_PARTITIONS set filesystem=:filesystem,partition_freespace=:partition_freespace,partition_size=:partition_size,alias=:alias,edited_date=:edited_date,edited_id=:edited_id,deleted_date=:deleted_date,deleted_id=:deleted_id where id_device=:id_device AND name=:name"); 
//		$update->execute(array( 'id_device' => $this->id_device , 'name' => $name , 'filesystem' => $filesystem , 'partition_freespace' => $partition_freespace , 'partition_size' => $partition_size , 'alias' => $alias , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] , 'deleted_date' => '0' , 'deleted_id' => '0' ));
//		
//		return 0;
//	}
//	
//	function delete_partition($name) {
//		$delete=$this->connexion->prepare("update CMDB_DEVICE_PARTITIONS set deleted_date=:deleted_date,deleted_id=:deleted_id where id_device = :id_device AND name = :name AND deleted_date=0"); 
//		$delete->execute(array( 'id_device' => $this->id_device , 'name' => $name , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
//
//		return 0;
//	}
//	
//	function services_exist($name) {
//		$select=$this->connexion->prepare("SELECT ID AS ID FROM CMDB_DEVICES_SERVICES WHERE ID_DEVICE = :id_device AND NAME = :name"); 
//		$select->execute(array( 'id_device' => $this->id_device , 'name' => $name ));
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->ID))
//		{
//			return $result->ID;
//		}
//		else
//		{
//			return 0;
//		}
//	}
//	
//	function get_services_infos() {
//		$this->services_infos=array();
//		$this->services_index=array();
//
//		$select=$this->connexion->prepare("
//SELECT 
//s.id AS ID,
//s.name AS NAME,
//s.path_name AS PATH_NAME,
//s.start_mode AS START_MODE,
//s.state AS STATE,
//s.owner AS OWNER,
//s.created_date AS CREATED_DATE,
//s.created_id AS CREATED_ID,
//s.edited_date AS EDITED_DATE,
//s.edited_id AS EDITED_ID,
//s.deleted_date AS DELETED_DATE,
//s.deleted_id AS DELETED_ID
//FROM CMDB_DEVICES_SERVICES S WHERE id_device=:id_device AND deleted_date=0
//		"); 
//		$select->execute(array( 'id_device' => $this->id_device ));
//		while( $result = $select->fetch(PDO::FETCH_OBJ) )
//		{
//			if(isset($result->ID))
//			{
//				array_push($this->services_index, $result->NAME);
//			
//				$this->services_infos[$result->NAME]['ID']=$result->ID;
//				$this->services_infos[$result->NAME]['NAME']=$result->NAME;
//				$this->services_infos[$result->NAME]['PATH_NAME']=$result->PATH_NAME;
//				$this->services_infos[$result->NAME]['START_MODE']=$result->START_MODE;
//				$this->services_infos[$result->NAME]['STATE']=$result->STATE;
//				$this->services_infos[$result->NAME]['OWNER']=$result->OWNER;
//                                               
//				$this->services_infos[$result->NAME]['CREATED_DATE']=$result->CREATED_DATE;
//				$this->services_infos[$result->NAME]['CREATED_ID']=$result->CREATED_ID;
//				$this->services_infos[$result->NAME]['EDITED_DATE']=$result->EDITED_DATE;
//				$this->services_infos[$result->NAME]['EDITED_ID']=$result->EDITED_ID;
//				$this->services_infos[$result->NAME]['DELETED_DATE']=$result->DELETED_DATE;
//				$this->services_infos[$result->NAME]['DELETED_ID']=$result->DELETED_ID;
//			}
//		}
//	}
//	
//	function add_services($name) {
//		$add=$this->connexion->prepare("insert into CMDB_DEVICES_SERVICES (id_device,name,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_device,:name,:created_date,:created_id,0,0,0,0)"); 
//		$add->execute(array( 'id_device' => $this->id_device , 'name' => $name , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
//
//		return 0;
//	}
//
//	function update_services($name,$path_name,$start_mode,$state,$owner) {
//		$update=$this->connexion->prepare("update CMDB_DEVICES_SERVICES set path_name=:path_name,start_mode=:start_mode,state=:state,owner=:owner,edited_date=:edited_date,edited_id=:edited_id,deleted_date=:deleted_date,deleted_id=:deleted_id where id_device=:id_device AND name=:name"); 
//		$update->execute(array( 'id_device' => $this->id_device , 'name' => $name , 'path_name' => $path_name , 'start_mode' => $start_mode , 'state' => $state , 'owner' => $owner , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] , 'deleted_date' => '0' , 'deleted_id' => '0' ));
//		
//		return 0;
//	}
//	
//	function delete_services($name) {
//		$delete=$this->connexion->prepare("update CMDB_DEVICES_SERVICES set deleted_date=:deleted_date,deleted_id=:deleted_id where id_device = :id_device AND name = :name AND deleted_date=0"); 
//		$delete->execute(array( 'id_device' => $this->id_device , 'name' => $name , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
//
//		return 0;
//	}
//	
//	function database_instances_exist($name,$type) {
//		$select=$this->connexion->prepare("SELECT ID AS ID FROM CMDB_DATABASE_INSTANCES WHERE ID_DEVICE = :id_device AND NAME = :name AND TYPE = :type AND deleted_date=0"); 
//		$select->execute(array( 'id_device' => $this->id_device , 'name' => $name , 'type' => $type ));
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->ID))
//		{
//			return $result->ID;
//		}
//		else
//		{
//			return 0;
//		}
//	}
//	
//	function get_databases_infos($scope = 'ALL') {
//		$this->database_instances_infos=array();
//		$this->database_instances_index=array();
//
//		$select=$this->connexion->prepare("
//SELECT 
//i.id AS ID,
//i.id_device AS ID_DEVICE,
//i.name AS NAME,
//i.type AS TYPE,
//i.version AS VERSION,
//i.port AS PORT,
//i.path AS PATH,
//i.created_date AS CREATED_DATE,
//i.created_id AS CREATED_ID,
//i.edited_date AS EDITED_DATE,
//i.edited_id AS EDITED_ID,
//i.deleted_date AS DELETED_DATE,
//i.deleted_id AS DELETED_ID
//FROM CMDB_DATABASE_INSTANCES I WHERE id_device=:id_device AND deleted_date=0
//		"); 
//		$select->execute(array( 'id_device' => $this->id_device ));
//		while( $result = $select->fetch(PDO::FETCH_OBJ) )
//		{
//			if(isset($result->ID))
//			{
//				array_push($this->database_instances_index, $result->ID);
//			
//				$this->database_instances_infos[$result->ID]['ID']=$result->ID;
//				$this->database_instances_infos[$result->ID]['NAME']=$result->NAME;
//				$this->database_instances_infos[$result->ID]['TYPE']=$result->TYPE;
//				$this->database_instances_infos[$result->ID]['VERSION']=$result->VERSION;
//				$this->database_instances_infos[$result->ID]['PORT']=$result->PORT;
//				$this->database_instances_infos[$result->ID]['PATH']=$result->PATH;
//
//				$this->database_instances_infos[$result->ID]['CREATED_DATE']=$result->CREATED_DATE;
//				$this->database_instances_infos[$result->ID]['CREATED_ID']=$result->CREATED_ID;
//				$this->database_instances_infos[$result->ID]['EDITED_DATE']=$result->EDITED_DATE;
//				$this->database_instances_infos[$result->ID]['EDITED_ID']=$result->EDITED_ID;
//				$this->database_instances_infos[$result->ID]['DELETED_DATE']=$result->DELETED_DATE;
//				$this->database_instances_infos[$result->ID]['DELETED_ID']=$result->DELETED_ID;
//				
//				$this->database_instances_infos[$result->ID]['DATABASE_INDEX']=array();
//				$this->database_instances_infos[$result->ID]['DATABASE_INFOS']=array();
//				
//				// On récupère les informations des bases de données
//				$selectDB=$this->connexion->prepare("
//SELECT 
//d.id AS ID,
//d.id_instance AS ID_INSTANCE,
//d.name AS NAME,
//d.status AS STATUS,
//d.archiver AS ARCHIVER,
//d.health_checked AS HEALTH_CHECKED,
//d.space_checked AS SPACE_CHECKED,
//d.backup_checked AS BACKUP_CHECKED,
//d.created_date AS CREATED_DATE,
//d.created_id AS CREATED_ID,
//d.edited_date AS EDITED_DATE,
//d.edited_id AS EDITED_ID,
//d.deleted_date AS DELETED_DATE,
//d.deleted_id AS DELETED_ID
//FROM CMDB_DATABASES D WHERE id_instance=:id_instance AND deleted_date=0
//				"); 
//				$selectDB->execute(array( 'id_instance' => $this->database_instances_infos[$result->ID]['ID'] ));
//				while( $resultDB = $selectDB->fetch(PDO::FETCH_OBJ) )
//				{
//					if(isset($resultDB->ID))
//					{
//						array_push($this->database_instances_infos[$result->ID]['DATABASE_INDEX'], $resultDB->NAME);
//						$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['ID']=$resultDB->ID;
//						$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['STATUS']=$resultDB->STATUS;
//						$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['ARCHIVER']=$resultDB->ARCHIVER;
//						
//						$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['HEALTH_CHECKED']=$resultDB->HEALTH_CHECKED;
//						$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['SPACE_CHECKED']=$resultDB->SPACE_CHECKED;
//						$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_CHECKED']=$resultDB->BACKUP_CHECKED;
//						
//						$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INDEX']=array();
//						$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INFOS']=array();
//						
//						$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_FULL_LAST_DATE']='0';
//						$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INCR_LAST_DATE']='0';
//						
//						$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['FILEGROUP_INDEX']=array();
//						$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['FILEGROUP_INFOS']=array();
//						
//						if($scope=='ALL') {
//							$selectBCK=$this->connexion->prepare("
//SELECT 
//b.id AS ID,
//b.id_database AS ID_DATABASE,
//b.backup_format AS BACKUP_FORMAT,
//b.backup_type AS BACKUP_TYPE,
//b.backup_completion AS BACKUP_COMPLETION,
//b.backup_status AS BACKUP_STATUS,
//b.backup_size AS BACKUP_SIZE,
//b.backup_file_path AS BACKUP_FILE_PATH,
//b.created_date AS CREATED_DATE,
//b.created_id AS CREATED_ID,
//b.edited_date AS EDITED_DATE,
//b.edited_id AS EDITED_ID,
//b.deleted_date AS DELETED_DATE,
//b.deleted_id AS DELETED_ID
//FROM CMDB_DATABASE_BACKUPS B WHERE id_database=:id_database AND (deleted_date=0 OR BACKUP_COMPLETION>:scope_start)
//ORDER BY b.BACKUP_COMPLETION DESC
//						"); 
//							$selectBCK->execute(array( 'id_database' => $resultDB->ID , 'scope_start' => time()-86400*15 ));
//							while( $resultBCK = $selectBCK->fetch(PDO::FETCH_OBJ) )
//							{
//								if(isset($resultBCK->ID))
//								{
//									array_push($this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INDEX'], $resultDB->NAME.$resultBCK->BACKUP_TYPE.$resultBCK->BACKUP_COMPLETION);
//								
//									// On note la FULL et l'incrémentale les plus récentes
//									if($this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_FULL_LAST_DATE']=='0' && $resultBCK->BACKUP_TYPE=='FULL'){$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_FULL_LAST_DATE']=$resultBCK->BACKUP_COMPLETION;}
//									elseif($this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INCR_LAST_DATE']=='0' && ($resultBCK->BACKUP_TYPE=='INCR' || $resultBCK->BACKUP_TYPE=='DIFF')){$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INCR_LAST_DATE']=$resultBCK->BACKUP_COMPLETION;}
//									//if($resultDB->NAME == 'GestAMI_30'){file_put_contents('log.txt',$resultBCK->ID_DATABASE.'/'.$resultBCK->BACKUP_TYPE.'/'.date('Y-m-d H:i:s',$resultBCK->BACKUP_COMPLETION)."\r\n", FILE_APPEND);}
//									$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INFOS'][$resultDB->NAME.$resultBCK->BACKUP_TYPE.$resultBCK->BACKUP_COMPLETION]['BACKUP_FORMAT']=$resultBCK->BACKUP_FORMAT;
//									$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INFOS'][$resultDB->NAME.$resultBCK->BACKUP_TYPE.$resultBCK->BACKUP_COMPLETION]['BACKUP_TYPE']=$resultBCK->BACKUP_TYPE;
//									$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INFOS'][$resultDB->NAME.$resultBCK->BACKUP_TYPE.$resultBCK->BACKUP_COMPLETION]['BACKUP_COMPLETION']=$resultBCK->BACKUP_COMPLETION;
//									$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INFOS'][$resultDB->NAME.$resultBCK->BACKUP_TYPE.$resultBCK->BACKUP_COMPLETION]['BACKUP_STATUS']=$resultBCK->BACKUP_STATUS;
//									$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INFOS'][$resultDB->NAME.$resultBCK->BACKUP_TYPE.$resultBCK->BACKUP_COMPLETION]['BACKUP_SIZE']=$resultBCK->BACKUP_SIZE;
//									$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INFOS'][$resultDB->NAME.$resultBCK->BACKUP_TYPE.$resultBCK->BACKUP_COMPLETION]['BACKUP_FILE_PATH']=$resultBCK->BACKUP_FILE_PATH;
//								}	
//							}
//						}
//						elseif($scope=='MIN') {
//							$selectBCK=$this->connexion->prepare("
//SELECT 
//b.id AS ID,
//b.id_database AS ID_DATABASE,
//b.backup_format AS BACKUP_FORMAT,
//b.backup_type AS BACKUP_TYPE,
//b.backup_completion AS BACKUP_COMPLETION,
//b.backup_status AS BACKUP_STATUS,
//b.backup_size AS BACKUP_SIZE,
//b.backup_file_path AS BACKUP_FILE_PATH,
//b.created_date AS CREATED_DATE,
//b.created_id AS CREATED_ID,
//b.edited_date AS EDITED_DATE,
//b.edited_id AS EDITED_ID,
//b.deleted_date AS DELETED_DATE,
//b.deleted_id AS DELETED_ID
//FROM CMDB_DATABASE_BACKUPS B WHERE id_database=:id_database AND (deleted_date=0 OR BACKUP_COMPLETION>:scope_start) AND BACKUP_TYPE='FULL' AND 
//backup_completion = (SELECT max(backup_completion) from CMDB_DATABASE_BACKUPS WHERE id_database=:id_database AND backup_type='FULL' AND (deleted_date=0 OR BACKUP_COMPLETION>:scope_start))
//						"); 
//							$selectBCK->execute(array( 'id_database' => $resultDB->ID , 'scope_start' => time()-86400*15 ));
//							$resultBCK = $selectBCK->fetch(PDO::FETCH_OBJ);
//							
//							if(isset($resultBCK->ID))
//							{
//								$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_FULL_LAST_DATE']=$resultBCK->BACKUP_COMPLETION;
//							}
//							
//							$selectBCK=$this->connexion->prepare("
//SELECT 
//b.id AS ID,
//b.id_database AS ID_DATABASE,
//b.backup_format AS BACKUP_FORMAT,
//b.backup_type AS BACKUP_TYPE,
//b.backup_completion AS BACKUP_COMPLETION,
//b.backup_status AS BACKUP_STATUS,
//b.backup_size AS BACKUP_SIZE,
//b.backup_file_path AS BACKUP_FILE_PATH,
//b.created_date AS CREATED_DATE,
//b.created_id AS CREATED_ID,
//b.edited_date AS EDITED_DATE,
//b.edited_id AS EDITED_ID,
//b.deleted_date AS DELETED_DATE,
//b.deleted_id AS DELETED_ID
//FROM CMDB_DATABASE_BACKUPS B WHERE id_database=:id_database AND (deleted_date=0 OR BACKUP_COMPLETION>:scope_start) AND (backup_type='INCR' OR backup_type='DIFF') AND 
//backup_completion = (SELECT max(backup_completion) from CMDB_DATABASE_BACKUPS WHERE id_database=:id_database AND (backup_type='INCR' OR backup_type='DIFF') AND (deleted_date=0 OR BACKUP_COMPLETION>:scope_start))
//						"); 
//							$selectBCK->execute(array( 'id_database' => $resultDB->ID , 'scope_start' => time()-86400*15 ));
//							$resultBCK = $selectBCK->fetch(PDO::FETCH_OBJ);
//							
//							if(isset($resultBCK->ID))
//							{
//								$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['BACKUP_INCR_LAST_DATE']=$resultBCK->BACKUP_COMPLETION;
//							}
//						}
//						
//						// On récupère les informations sur les filegroups
//						$selectFilegroups=$this->connexion->prepare("
//SELECT 
//f.id AS ID,
//f.id_database AS ID_DATABASE,
//f.name AS FILEGROUPNAME,
//f.created_date AS CREATED_DATE,
//f.created_id AS CREATED_ID,
//f.edited_date AS EDITED_DATE,
//f.edited_id AS EDITED_ID,
//f.deleted_date AS DELETED_DATE,
//f.deleted_id AS DELETED_ID
//FROM CMDB_DATABASE_FILEGROUPS F 
//WHERE F.id_database=:id_database AND F.deleted_date=0
//						"); 
//						$selectFilegroups->execute(array( 'id_database' => $resultDB->ID ));
//						while($resultFilegroups = $selectFilegroups->fetch(PDO::FETCH_OBJ))
//						{
//							array_push($this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['FILEGROUP_INDEX'], $resultFilegroups->FILEGROUPNAME);
//							$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['FILEGROUP_INFOS'][$resultFilegroups->FILEGROUPNAME]['ID']=$resultFilegroups->ID;
//							$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['FILEGROUP_INFOS'][$resultFilegroups->FILEGROUPNAME]['SIZE']=0;
//							$this->database_instances_infos[$result->ID]['DATABASE_INFOS'][$resultDB->NAME]['FILEGROUP_INFOS'][$resultFilegroups->FILEGROUPNAME]['FREESPACE']=0;
//						}
//					}
//				}
//				// On récupère les états de l'instance
//				$selectDB=$this->connexion->prepare("
//SELECT 
//s.id AS ID,
//s.id_instance AS ID_INSTANCE,
//s.status AS STATUS,
//s.code AS CODE,
//s.created_date AS CREATED_DATE,
//s.created_id AS CREATED_ID,
//s.edited_date AS EDITED_DATE,
//s.edited_id AS EDITED_ID,
//s.deleted_date AS DELETED_DATE,
//s.deleted_id AS DELETED_ID
//FROM CMDB_DATABASE_INSTANCE_STATUS S WHERE id_instance=:id_instance AND deleted_date=0 AND created_date > :valid_date
//				"); 
//				$selectDB->execute(array( 'id_instance' => $this->database_instances_infos[$result->ID]['ID'] , 'valid_date' => time()-5400 ));
//				$resultDB = $selectDB->fetch(PDO::FETCH_OBJ);
//				if(isset($resultDB->ID))
//				{
//					$this->database_instances_infos[$result->ID]['STATUS']=$resultDB->STATUS;
//					$this->database_instances_infos[$result->ID]['CODE']=$resultDB->CODE;
//				}
//				else {
//					$this->database_instances_infos[$result->ID]['STATUS']='-';
//					$this->database_instances_infos[$result->ID]['CODE']='-';
//				}
//			}
//		}
//	}
//	
//	function add_database_instances($name,$type) {
//		$add=$this->connexion->prepare("insert into CMDB_DATABASE_INSTANCES (id_device,name,type,port,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_device,:name,:type,0,:created_date,:created_id,0,0,0,0)"); 
//		$add->execute(array( 'id_device' => $this->id_device , 'name' => $name , 'type' => strtoupper($type) , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
//		
//		if($type=='MSSQLSERVER'){
//			$this->add_database('master',$this->database_instances_exist($name,$type));
//		} elseif($type=='ORACLE'){
//			$this->add_database($name,$this->database_instances_exist($name,$type));
//		}
//		
//		return 0;
//	}
//
//	function update_database_instances_path($name,$type,$path) {
//		$update=$this->connexion->prepare("update CMDB_DATABASE_INSTANCES set path=:path,edited_date=:edited_date,edited_id=:edited_id,deleted_date=:deleted_date,deleted_id=:deleted_id where id_device=:id_device AND name=:name AND type=:type AND deleted_date=0"); 
//		$update->execute(array( 'id_device' => $this->id_device , 'name' => $name , 'type' => strtoupper($type) , 'path' => $path , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] , 'deleted_date' => '0' , 'deleted_id' => '0' ));
//		
//		return 0;
//	}
//	
//	function update_database_instances_port($name,$type,$port) {
//		$update=$this->connexion->prepare("update CMDB_DATABASE_INSTANCES set port=:port,edited_date=:edited_date,edited_id=:edited_id where id_device=:id_device AND name=:name AND type=:type AND deleted_date=0"); 
//		$update->execute(array( 'id_device' => $this->id_device , 'name' => $name , 'type' => strtoupper($type) , 'port' => $port , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
//		
//		return 0;
//	}
//	
//	function update_database_instances_version($name,$type,$version) {
//		$update=$this->connexion->prepare("update CMDB_DATABASE_INSTANCES set version=:version,edited_date=:edited_date,edited_id=:edited_id where id_device=:id_device AND name=:name AND type=:type AND deleted_date=0"); 
//		$update->execute(array( 'id_device' => $this->id_device , 'name' => $name , 'type' => strtoupper($type) , 'version' => $version , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID'] ));
//		
//		return 0;
//	}
//	
//	function delete_database_instances($name,$type) {
//		$delete=$this->connexion->prepare("update CMDB_DATABASE_INSTANCES set deleted_date=:deleted_date,deleted_id=:deleted_id where id_device = :id_device AND name = :name AND type=:type AND deleted_date=0"); 
//		$delete->execute(array( 'id_device' => $this->id_device , 'name' => $name , 'type' => strtoupper($type) , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
//
//		return 0;
//	}
//	
//	function add_database($name,$id_instance) {
//		$add=$this->connexion->prepare("insert into CMDB_DATABASES (id_instance,name,health_checked,space_checked,backup_checked,startup_time,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_instance,:name,1,1,1,0,:created_date,:created_id,0,0,0,0)"); 
//		$add->execute(array( 'id_instance' => $id_instance , 'name' => $name , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
//	
//		return 0;
//	}
//	
//	function update_database($name,$status,$archiver,$startup_time,$id_instance) {
//		$add=$this->connexion->prepare("update CMDB_DATABASES set status=:status,archiver=:archiver,startup_time=:startup_time,edited_date=:edited_date,edited_id=:edited_id where name = :name AND id_instance = :id_instance AND deleted_date = 0"); 
//		$add->execute(array( 'id_instance' => $id_instance , 'name' => $name , 'status' => $status , 'archiver' => $archiver , 'startup_time' => $startup_time , 'edited_date' => time() , 'edited_id' => $_SESSION['USER_ID']  ));
//	
//		return 0;
//	}
//	
//	function delete_database($name,$id_instance) {
//		$delete=$this->connexion->prepare("update CMDB_DATABASES set deleted_date=:deleted_date,deleted_id=:deleted_id where name = :name AND id_instance=:id_instance AND deleted_date=0"); 
//		$delete->execute(array( 'name' => $name , 'id_instance' => $id_instance , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
//
//		return 0;
//	}
//	
//	function add_instance_status($id_instance,$status,$code) {
//		$add=$this->connexion->prepare("insert into CMDB_DATABASE_INSTANCE_STATUS (id_instance,status,code,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_instance,:status,:code,:created_date,:created_id,0,0,0,0)"); 
//		$add->execute(array( 'id_instance' => $id_instance , 'status' => $status , 'code' => $code , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
//	
//		return 0;
//	}
//	
//	function delete_instance_status($id_instance) {
//		$delete=$this->connexion->prepare("update CMDB_DATABASE_INSTANCE_STATUS set deleted_date=:deleted_date,deleted_id=:deleted_id where id_instance = :id_instance AND deleted_date=0"); 
//		$delete->execute(array( 'id_instance' => $id_instance , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID']  ));
//	
//		return 0;
//	}
//
//	function delete_database_backup($fileName,$idDatabase) {
//		$delete=$this->connexion->prepare("update CMDB_DATABASE_BACKUPS set deleted_date=:deleted_date,deleted_id=:deleted_id where id_database = :id_database AND BACKUP_FILE_PATH=:BACKUP_FILE_PATH AND deleted_date=0"); 
//		$delete->execute(array( 'id_database' => $idDatabase , 'BACKUP_FILE_PATH' => $fileName , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID']  ));
//	
//		return 0;
//	}
//	
//	function add_database_backup($fileName,$idDatabase,$type,$completionTime,$elapsedTime,$status,$fileSize) {
//		$add=$this->connexion->prepare("insert into CMDB_DATABASE_BACKUPS (id_database,backup_type,backup_completion,backup_status,backup_size,backup_file_path,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_database,:backup_type,:backup_completion,:backup_status,:backup_size,:backup_file_path,:created_date,:created_id,0,0,0,0)"); 
//		$add->execute(array( 'id_database' => $idDatabase , 'backup_type' => $type , 'backup_completion' => $completionTime , 'backup_status' => $status , 'backup_size' => $fileSize , 'backup_file_path' => $fileName , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
//		
//		return 0;
//	}
//
//	function add_database_filegroup($name,$id_database) {
//		$add=$this->connexion->prepare("insert into CMDB_DATABASE_FILEGROUPS (id_database,name,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_database,:name,:created_date,:created_id,0,0,0,0)"); 
//		$add->execute(array( 'id_database' => $id_database , 'name' => $name , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
//	
//		return 0;
//	}
//	
//	/*
//	function update_database_filegroup($name,$status,$archiver,$startup_time,$id_instance) {
//		$add=$this->connexion->prepare("update CMDB_DATABASE_FILEGROUPS set status=:status,archiver=:archiver,startup_time=:startup_time,edited_date=:edited_date,edited_id=:edited_id where name = :name AND id_instance = :id_instance AND deleted_date = 0"); 
//		$add->execute(array( 'id_instance' => $id_instance , 'name' => $name , 'status' => $status , 'archiver' => $archiver , 'startup_time' => $startup_time , 'edited_date' => mktime() , 'edited_id' => $_SESSION['USER_ID']  ));
//	
//		return 0;
//	}
//	*/
//
//	function delete_database_filegroup($name,$id_database) {
//		$delete=$this->connexion->prepare("update CMDB_DATABASE_FILEGROUPS set deleted_date=:deleted_date,deleted_id=:deleted_id where name = :name AND id_database=:id_database AND deleted_date=0"); 
//		$delete->execute(array( 'name' => $name , 'id_database' => $id_database , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
//
//		return 0;
//	}
//	
//	function database_filegroups_exist($name,$id_database) {
//		$select=$this->connexion->prepare("SELECT ID AS ID FROM CMDB_DATABASE_FILEGROUPS WHERE NAME = :name AND ID_DATABASE = :id_database AND deleted_date=0"); 
//		$select->execute(array( 'id_database' => $id_database , 'name' => $name ));
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->ID))
//		{
//			return $result->ID;
//		}
//		else
//		{
//			return 0;
//		}
//	}
//	
//	function add_database_filegroup_status($id_filegroup,$space_size,$free_space) {
//		$add=$this->connexion->prepare("insert into CMDB_DATABASE_FILEGROUP_STATUS (id_filegroup,space_size,free_space,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_filegroup,:space_size,:free_space,:created_date,:created_id,0,0,0,0)"); 
//		$add->execute(array( 'id_filegroup' => $id_filegroup , 'space_size' => $space_size , 'free_space' => $free_space , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
//	
//		return 0;
//	}
//	
//	function delete_database_filegroup_status($id_filegroup) {
//		$delete=$this->connexion->prepare("update CMDB_DATABASE_FILEGROUP_STATUS set deleted_date=:deleted_date,deleted_id=:deleted_id where id_filegroup = :id_filegroup AND deleted_date=0"); 
//		$delete->execute(array( 'id_filegroup' => $id_filegroup , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID']  ));
//	
//		return 0;
//	}
//		
//	function is_health_checked($idDatabase) {
//		$select=$this->connexion->prepare("SELECT health_checked AS HEALTH_CHECKED FROM CMDB_DATABASES WHERE ID = :id"); 
//		$select->execute(array( 'id' => $idDatabase	));
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->HEALTH_CHECKED))
//		{
//			return $result->HEALTH_CHECKED;
//		}
//		else
//		{
//			return '';
//		}
//	}
//	
//	function is_space_checked($idDatabase) {
//		$select=$this->connexion->prepare("SELECT space_checked AS SPACE_CHECKED FROM CMDB_DATABASES WHERE ID = :id"); 
//		$select->execute(array( 'id' => $idDatabase	));
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->SPACE_CHECKED))
//		{
//			return $result->SPACE_CHECKED;
//		}
//		else
//		{
//			return '';
//		}
//	}
//	
//	function is_backup_checked($idDatabase) {
//		$select=$this->connexion->prepare("SELECT backup_checked AS BACKUP_CHECKED FROM CMDB_DATABASES WHERE ID = :id"); 
//		$select->execute(array( 'id' => $idDatabase	));
//		$result = $select->fetch(PDO::FETCH_OBJ);
//		if(isset($result->BACKUP_CHECKED))
//		{
//			return $result->BACKUP_CHECKED;
//		}
//		else
//		{
//			return '';
//		}
//	}
//}



function getWmiObject($cmd) {
	exec($cmd,$output);
	
	if(!isset($output[0]) || substr($output[0],0,13)=='Get-WmiObject' || substr($output[0],0,7)=='Missing') { // En erreur
		return array('ERROR');
	}
	else{
		return $output;
	}
}
?>