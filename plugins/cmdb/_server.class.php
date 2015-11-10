<?php
class server extends device {
	var $osIndex;
	var $cpuIndex;
	var $cpuArray;
	var $diskIndex;
	var $diskArray;
	var $fsIndex;
	var $fsArray;
	var $serviceIndex;
	var $serviceArray;
	var $dbInstanceIndex;
	var $dbInstanceArray;
	
	// Builder
	function server($id){
		$this->id = $id;
		
		// Os
		$this->osIndex=array();
		
		$q0=get_link()->prepare('
SELECT 
o.id AS ID
FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os o WHERE id_device=:id_device AND deleted_date=0
		');
		$q0->execute(array( 'id_device' => $this->id ));
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
			array_push($this->osIndex,$r0->ID);
			
			// Cpu
			$this->cpuIndex=array();
			$this->cpuArray=array();
			
			$q1=get_link()->prepare('
SELECT 
c.id AS ID,
c.logical_id AS LOGICAL_ID,
c.max_clock_speed AS MAX_CLOCK_SPEED,
c.name AS NAME,
c.created_date AS CREATED_DATE,
c.created_id AS CREATED_ID,
c.edited_date AS EDITED_DATE,
c.edited_id AS EDITED_ID,
c.deleted_date AS DELETED_DATE,
c.deleted_id AS DELETED_ID
FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_cpu c WHERE id_os=:id_os AND deleted_date=0
			');
			$q1->execute(array( 'id_os' => $r0->ID ));
			while( $r1 = $q1->fetch(PDO::FETCH_OBJ) )
			{
				array_push($this->cpuIndex, $r1->ID);
				
				$this->cpuArray[$r1->ID]['ID']=$r1->ID;
				$this->cpuArray[$r1->ID]['LOGICAL_ID']=$r1->LOGICAL_ID;
				$this->cpuArray[$r1->ID]['MAX_CLOCK_SPEED']=$r1->MAX_CLOCK_SPEED;
				$this->cpuArray[$r1->ID]['NAME']=$r1->NAME;
	
				$this->cpuArray[$r1->ID]['CREATED_DATE']=$r1->CREATED_DATE;
				$this->cpuArray[$r1->ID]['CREATED_ID']=$r1->CREATED_ID;
				$this->cpuArray[$r1->ID]['EDITED_DATE']=$r1->EDITED_DATE;
				$this->cpuArray[$r1->ID]['EDITED_ID']=$r1->EDITED_ID;
				$this->cpuArray[$r1->ID]['DELETED_DATE']=$r1->DELETED_DATE;
				$this->cpuArray[$r1->ID]['DELETED_ID']=$r1->DELETED_ID;
			}
			// ! Cpu
			
			// Disk
			$this->diskIndex=array();
			$this->diskArray=array();
			
			$q1=get_link()->prepare('
SELECT 
d.id AS ID,
d.name AS NAME,
d.interfacetype AS INTERFACETYPE,
d.disk_size AS DISKSIZE,
d.caption AS CAPTION,
d.firmwarerevision AS FIRMWAREREVISION,
d.manufacturer AS MANUFACTURER,
d.model AS MODEL,
d.serialnumber AS SERIALNUMBER,
d.created_date AS CREATED_DATE,
d.created_id AS CREATED_ID,
d.edited_date AS EDITED_DATE,
d.edited_id AS EDITED_ID,
d.deleted_date AS DELETED_DATE,
d.deleted_id AS DELETED_ID
FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_disks d WHERE id_os=:id_os AND deleted_date=0
			');
			$q1->execute(array( 'id_os' => $r0->ID ));
			while( $r1 = $q1->fetch(PDO::FETCH_OBJ) )
			{
				array_push($this->diskIndex, $r1->NAME);
				
				$this->cpuArray[$r1->NAME]['ID']=$r1->ID;
				$this->cpuArray[$r1->NAME]['NAME']=$r1->NAME;
				$this->cpuArray[$r1->NAME]['INTERFACETYPE']=$r1->INTERFACETYPE;
				$this->cpuArray[$r1->NAME]['DISKSIZE']=$r1->DISKSIZE;
				$this->cpuArray[$r1->NAME]['CAPTION']=$r1->CAPTION;
				$this->cpuArray[$r1->NAME]['FIRMWAREREVISION']=$r1->FIRMWAREREVISION;
				$this->cpuArray[$r1->NAME]['MANUFACTURER']=$r1->MANUFACTURER;
				$this->cpuArray[$r1->NAME]['MODEL']=$r1->MODEL;
				$this->cpuArray[$r1->NAME]['SERIALNUMBER']=$r1->SERIALNUMBER;
	
				$this->cpuArray[$r1->NAME]['CREATED_DATE']=$r1->CREATED_DATE;
				$this->cpuArray[$r1->NAME]['CREATED_ID']=$r1->CREATED_ID;
				$this->cpuArray[$r1->NAME]['EDITED_DATE']=$r1->EDITED_DATE;
				$this->cpuArray[$r1->NAME]['EDITED_ID']=$r1->EDITED_ID;
				$this->cpuArray[$r1->NAME]['DELETED_DATE']=$r1->DELETED_DATE;
				$this->cpuArray[$r1->NAME]['DELETED_ID']=$r1->DELETED_ID;
			}
			// ! Disk
			
			// Fs
			$this->fsIndex=array();
			$this->fsArray=array();
			
			$q1=get_link()->prepare('
SELECT 
f.id AS ID,
f.id_os AS IDOS,
f.name AS NAME,
f.filesystem AS FILESYSTEM,
f.alias AS ALIAS,
u.partition_size AS PARTITIONSIZE,
u.partition_freespace AS PARTITIONFREESPACE,
f.created_date AS CREATED_DATE,
f.created_id AS CREATED_ID,
f.edited_date AS EDITED_DATE,
f.edited_id AS EDITED_ID,
f.deleted_date AS DELETED_DATE,
f.deleted_id AS DELETED_ID
FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_fs f
LEFT JOIN '.get_ini('BDD_PREFIX').'cmdb_dev_os_fs_up u
ON u.id_fs=f.id AND u.deleted_date=0
WHERE f.id_os=:id_os AND f.deleted_date=0
			');
			$q1->execute(array( 'id_os' => $r0->ID ));
			while( $r1 = $q1->fetch(PDO::FETCH_OBJ) )
			{
				array_push($this->fsIndex, $r1->NAME);
				
				$this->fsArray[$r1->NAME]['ID']=$r1->ID;
				$this->fsArray[$r1->NAME]['NAME']=$r1->NAME;
				$this->fsArray[$r1->NAME]['FILESYSTEM']=$r1->FILESYSTEM;
				$this->fsArray[$r1->NAME]['ALIAS']=$r1->ALIAS;
				$this->fsArray[$r1->NAME]['PARTITIONSIZE']=$r1->PARTITIONSIZE;
				$this->fsArray[$r1->NAME]['PARTITIONFREESPACE']=$r1->PARTITIONFREESPACE;
	
				$this->fsArray[$r1->NAME]['CREATED_DATE']=$r1->CREATED_DATE;
				$this->fsArray[$r1->NAME]['CREATED_ID']=$r1->CREATED_ID;
				$this->fsArray[$r1->NAME]['EDITED_DATE']=$r1->EDITED_DATE;
				$this->fsArray[$r1->NAME]['EDITED_ID']=$r1->EDITED_ID;
				$this->fsArray[$r1->NAME]['DELETED_DATE']=$r1->DELETED_DATE;
				$this->fsArray[$r1->NAME]['DELETED_ID']=$r1->DELETED_ID;
			}
			// ! Fs
			
			// Service
			$this->serviceIndex=array();
			$this->serviceArray=array();
			
			$q1=get_link()->prepare('
SELECT 
s.id AS ID,
s.name AS NAME,
s.created_date AS CREATED_DATE,
s.created_id AS CREATED_ID,
s.edited_date AS EDITED_DATE,
s.edited_id AS EDITED_ID,
s.deleted_date AS DELETED_DATE,
s.deleted_id AS DELETED_ID
FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_services s
WHERE s.id_os=:id_os AND s.deleted_date=0
			');
			$q1->execute(array( 'id_os' => $r0->ID ));
			while( $r1 = $q1->fetch(PDO::FETCH_OBJ) )
			{
				array_push($this->serviceIndex, $r1->NAME);
				
				$this->serviceArray[$r1->NAME]['ID']=$r1->ID;
	
				$this->serviceArray[$r1->NAME]['CREATED_DATE']=$r1->CREATED_DATE;
				$this->serviceArray[$r1->NAME]['CREATED_ID']=$r1->CREATED_ID;
				$this->serviceArray[$r1->NAME]['EDITED_DATE']=$r1->EDITED_DATE;
				$this->serviceArray[$r1->NAME]['EDITED_ID']=$r1->EDITED_ID;
				$this->serviceArray[$r1->NAME]['DELETED_DATE']=$r1->DELETED_DATE;
				$this->serviceArray[$r1->NAME]['DELETED_ID']=$r1->DELETED_ID;
			}
			// ! Service
			
			// dbInstance
			$this->dbInstanceIndex=array();
			$this->dbInstanceArray=array();
			
			$q1=get_link()->prepare('
SELECT 
i.id AS ID,
i.name AS NAME,
i.db_type AS DBTYPE,
i.db_version AS DBVERSION,
i.db_port AS DBPORT,
i.db_bin_path AS DBPATH,
i.created_date AS CREATED_DATE,
i.created_id AS CREATED_ID,
i.edited_date AS EDITED_DATE,
i.edited_id AS EDITED_ID,
i.deleted_date AS DELETED_DATE,
i.deleted_id AS DELETED_ID
FROM '.get_ini('BDD_PREFIX').'cmdb_db_instances i
WHERE i.id_os=:id_os AND i.deleted_date=0
			');
			$q1->execute(array( 'id_os' => $r0->ID ));
			while( $r1 = $q1->fetch(PDO::FETCH_OBJ) )
			{
				array_push($this->dbInstanceIndex, $r1->NAME);
				
				$this->dbInstanceArray[$r1->NAME]['ID']=$r1->ID;
				$this->dbInstanceArray[$r1->NAME]['NAME']=$r1->NAME;
				$this->dbInstanceArray[$r1->NAME]['TYPE']=$r1->DBTYPE;
				$this->dbInstanceArray[$r1->NAME]['VERSION']=$r1->DBVERSION;
				$this->dbInstanceArray[$r1->NAME]['PORT']=$r1->DBPORT;
				$this->dbInstanceArray[$r1->NAME]['PATH']=$r1->DBPATH;
				
				//TODO -> Get status
				$this->dbInstanceArray[$r1->NAME]['STATUS']='';
	
				$this->dbInstanceArray[$r1->NAME]['CREATED_DATE']=$r1->CREATED_DATE;
				$this->dbInstanceArray[$r1->NAME]['CREATED_ID']=$r1->CREATED_ID;
				$this->dbInstanceArray[$r1->NAME]['EDITED_DATE']=$r1->EDITED_DATE;
				$this->dbInstanceArray[$r1->NAME]['EDITED_ID']=$r1->EDITED_ID;
				$this->dbInstanceArray[$r1->NAME]['DELETED_DATE']=$r1->DELETED_DATE;
				$this->dbInstanceArray[$r1->NAME]['DELETED_ID']=$r1->DELETED_ID;
			}
			// ! dbInstance
		}
		

	}
	// ! Builder
	

	
}
?>