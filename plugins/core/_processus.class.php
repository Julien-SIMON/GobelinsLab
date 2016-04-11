<?php
class processusManager {
	var $processManagerUserId;
	
	// Builder
	function processusManager($userId=0){
		if($userId==0)
		{
			$userM = new userManager(); 
			$this->processManagerUserId = $userM->getIdByName('process');
		}
		else
		{
			$this->processManagerUserId = $userId;
		}
	}
	
	function getId($cmd) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_processus WHERE cmd=:cmd AND deleted_date=0");
		$q0->execute(array( "cmd" => $cmd ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}
	
	function isTaskProcess($task) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_processus WHERE cmd LIKE :task AND deleted_date=0");
		$q0->execute(array( "task" => $task.'_%.xml' ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}
	
	function getCount($cmd) {
		$q0=get_link()->prepare("SELECT count(*) AS COUNTCONCURENTPROCESS FROM ".get_ini('BDD_PREFIX')."core_processus WHERE cmd=:cmd AND deleted_date=0");
		$q0->execute(array( "cmd" => $cmd ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->COUNTCONCURENTPROCESS)) {
			return $r0->COUNTCONCURENTPROCESS;
		}
		else {
			return 0;
		}
	}
	
	function getRunningCount() {
		$q0=get_link()->prepare("SELECT count(*) AS COUNTRUNNINGPROCESS FROM ".get_ini('BDD_PREFIX')."core_processus WHERE status=:status AND deleted_date=0");
		$q0->execute(array( "status" => 'running' ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->COUNTRUNNINGPROCESS)) {
			return $r0->COUNTRUNNINGPROCESS;
		}
		else {
			return 0;
		}
	}

	function create($id_parent,$cmd,$args,$timeout) {
		if(strtoupper(get_ini('BDD_TYPE'))=='ORACLE'){
			$s0 = get_link()->prepare('SELECT '.get_ini('BDD_PREFIX').'CORE_PROCESSUS_ID_SEQ.NEXTVAL AS ID FROM DUAL');
			$s0->execute();
			$id = $s0->fetchColumn(0);
		} else {
			$id='';
		}
		
		$q0=get_link()->prepare("insert into ".get_ini('BDD_PREFIX')."core_processus (id,id_parent,cmd,args,status,percent,comments,timeout,created_date,created_id,edited_date,edited_id,deleted_date,deleted_id) VALUES(:id,:id_parent,:cmd,:args,:status,0,:comments,:timeout,:created_date,:created_id,0,0,0,0)"); 
		$q0->execute(array( 'id' => $id ,
							'id_parent' => $id_parent , 
							'cmd' => $cmd , 
							'args' => $args , 
							'status' => 'starting' , 
							'comments' => '' ,
							'timeout' => time()+$timeout , 
							'created_date' => time() , 
							'created_id' => $this->processManagerUserId
						));
		
		if(strtoupper(get_ini('BDD_TYPE'))!='ORACLE'){
			$id=get_link()->lastInsertId();
		}
		
		return $id;
	}
	
	function update($id,$status,$percent) {
		//if($id==0){$id=$this->id;} // If ID is not defined, update the current process
		
		$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_processus SET status=:status, percent=:percent, edited_date=:edited_date, edited_id=:edited_id WHERE id=:id');
		$q0->execute(array(	'id' => $id,
							'status' => $status,
							'percent' => $percent,
							'edited_id' => $this->processManagerUserId,
							'edited_date' => time()
					));						
	}

	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_processus SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $this->processManagerUserId,
								'deleted_date' => time()
						));
		}
	}
}


class processus extends dbEntry {
	var $idParent;
	var $cmd;
	var $args;
	var $status;
	var $percent;
	var $comments;
	var $timeout;
	
	// Builder
	function processus($id){
		$this->id = $id;
		
		// Get the disk informations
		$q0=get_link()->prepare("SELECT 
									pcs.id AS ID,
									pcs.id_parent AS ID_PARENT,
									pcs.cmd AS CMD,
									pcs.args AS ARGS,
									pcs.status AS STATUS,
									pcs.percent AS PERCENT,
									pcs.comments AS COMMENTS,
									pcs.timeout AS TIMEOUT,
									pcs.created_date AS CREATED_DATE,
									pcs.created_id AS CREATED_ID,
									pcs.edited_date AS EDITED_DATE,
									pcs.edited_id AS EDITED_ID,
									pcs.deleted_date AS DELETED_DATE,
									pcs.deleted_id AS DELETED_ID
								FROM ".get_ini('BDD_PREFIX')."core_processus pcs 
								WHERE pcs.id=:id"); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->idParent = $r0->ID_PARENT;
			$this->cmd = $r0->CMD;
			$this->args = $r0->ARGS;
			$this->status = $r0->STATUS;
			$this->percent = $r0->PERCENT;
			$this->comments = $r0->COMMENTS;
			$this->timeout = $r0->TIMEOUT;
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
			echo 'The processus does not exist.';
			exit(100);
		}
	}
}

/*
class processus {
	
	function get_cmd($id=0) {
		if($id==0){$id=$this->id;} // If ID is not defined, get the current process command
		$q0=get_link()->prepare("SELECT cmd AS CMD FROM ".get_ini('BDD_PREFIX')."PROCESSUS WHERE id = :id"); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->CMD))
		{
			return $r0->CMD;
		}
		else
		{
			return '';
		}
	}
	function runningCount() {
		$q0=get_link()->prepare("SELECT count(*) AS RUNNINGCOUNT FROM ".get_ini('BDD_PREFIX')."PROCESSUS WHERE status = :status AND (deleted_date = 0)"); 
		$q0->execute(array( 'status' => 'running' ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->RUNNINGCOUNT))
		{
			return $r0->RUNNINGCOUNT;
		}
		else
		{
			return 0;
		}
	}
}
*/
?>