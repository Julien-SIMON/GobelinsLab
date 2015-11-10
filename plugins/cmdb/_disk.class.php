<?php
class diskManager {
	var $idOs;
	
	// Builder
	function diskManager($idOs){
		$this->idOs=$idOs;
	}
	
	function getId($name) {
		$q0=get_link()->prepare('SELECT ID AS ID FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_disks WHERE id_os=:id_os AND name=:name AND deleted_date=0'); 
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
	
	function create($name,$interfaceType,$size,$description,$firmwareRevision,$manufacturer,$model,$serialNumber) {
		$q0=get_link()->prepare('insert into '.get_ini('BDD_PREFIX').'cmdb_dev_os_disks (id_os,name,interfacetype,disk_size,caption,firmwarerevision,manufacturer,model,serialnumber,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES (:id_os,:name,:interfacetype,:disk_size,:caption,:firmwarerevision,:manufacturer,:model,:serialnumber,:created_date,:created_id,0,0,0,0)'); 
		$q0->execute(array( 'id_os' => $this->idOs , 'name' => $name , 'interfacetype' => $interfaceType , 'disk_size' => $size , 'caption' => $description , 'firmwarerevision' => $firmwareRevision , 'manufacturer' => $manufacturer , 'model' => $model , 'serialnumber' => $serialNumber , 'created_date' => time() , 'created_id' => $_SESSION['USER_ID']  ));
		
		return $this->getId($name);
	}
	
	function delete($id=0) {
		if($id>0) {
			$q0=get_link()->prepare('update '.get_ini('BDD_PREFIX').'cmdb_dev_os_disks set deleted_date=:deleted_date,deleted_id=:deleted_id where id=:id AND deleted_date=0'); 
			$q0->execute(array( 'id' => $id , 'deleted_date' => time() , 'deleted_id' => $_SESSION['USER_ID'] ));
		}
	}
}


class disk extends dbEntry {
	var $id;
	var $name;
	var $interfaceType;
	var $totalSize;
	var $caption;
	var $firmwareRevision;
	var $manufacturer;
	var $model;
	var $serialNumber;
	
	// Builder
	function disk($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare('SELECT 
									d.id AS ID,
									d.name AS NAME,
									d.interfacetype AS INTERFACETYPE,
									d.disk_size AS TOTALSIZE,
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
								FROM '.get_ini('BDD_PREFIX').'cmdb_dev_os_disks d 
								WHERE id=:id AND deleted_date=0'); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->name = $r0->NAME;
			$this->interfaceType = $r0->INTERFACETYPE;
			$this->totalSize = $r0->TOTALSIZE;
			$this->caption = $r0->CAPTION;
			$this->firmwareRevision = $r0->FIRMWAREREVISION;
			$this->manufacturer = $r0->MANUFACTURER;
			$this->model = $r0->MODEL;
			$this->serialNumber = $r0->SERIALNUMBER;
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
			echo 'The disk does not exist.';
			exit(100);
		}
	}
}
?>