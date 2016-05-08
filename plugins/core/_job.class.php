<?php
class jobManager {
	// Builder
	function jobManager(){
	}
	
	function getId($idPlugin,$page) {
		$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_jobs WHERE id_plugin=:id_plugin AND page=:page AND deleted_date=0");
		$q0->execute(array( "id_plugin" => $idPlugin , "page" => $page ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		
		if(isset($r0->ID)) {
			return $r0->ID;
		}
		else {
			return 0;
		}
	}

	function create($idPlugin,$page) {
		$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_jobs (id_plugin,page,last_run_pid,last_run,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:id_plugin,:page,0,0,:created_id,:created_date,0,0,0,0)');
		$q0->execute(array(	'id_plugin' => $idPlugin,
							'page' => $page,
							'created_id' => $_SESSION['USER_ID'],
							'created_date' => time()
					));
					
		// Create the object
		$obj=new objectManager();
		$obj->create(get_table_id('core_job'),$this->getId($idPlugin,$page));
		
		// Return the id of this new group
		return $this->getId($idPlugin,$page);
	}
	
	function update($id,$idPlugin,$page) {
		$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_jobs SET id_plugin=:id_plugin, page=:page, edited_id=:edited_id, edited_date=:edited_date WHERE id=:id');
		$q0->execute(array(	'id' => $id,
							'id_plugin' => $idPlugin,
							'page' => $page,
							'edited_id' => $_SESSION['USER_ID'],
							'edited_date' => time()
					));
	}

	function delete($id=0) {
		if($id>0) {
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_jobs SET deleted_id=:deleted_id, deleted_date=:deleted_date WHERE id=:id');
			$q0->execute(array(	'id' => $id,
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
		}
	}
}


class job extends dbEntry {
	var $idPlugin;
	var $pluginName;
	var $page;
	var $lastRun;
	var $lastRunPid;
	var $startPolling;
	var $endPolling;
	var $polling;
	var $startSchedule;
	var $endSchedule;
	var $daysSchedule;
	var $hoursSchedule;
	var $minutesSchedule;
	
	// Builder
	function job($id){
		$this->id = $id;
		
		// Get the group informations
		$q0=get_link()->prepare("SELECT 
									job.id AS ID,
									job.id_plugin AS IDPLUGIN,
									plug.name AS PLUGINNAME,
									job.page AS PAGE,
									job.last_run AS LASTRUN,
									poll.start_time AS POLLINGSTARTTIME,
									poll.end_time AS POLLINGENDTIME,
									poll.polling_time AS POLLINGTIME,
									sched.start_time AS SCHEDSTARTTIME,
									sched.end_time AS SCHEDENDTIME,
									sched.days AS SCHEDDAYS,
									sched.hours AS SCHEDHOURS,
									sched.minutes AS SCHEDMINUTES,
									job.created_date AS CREATED_DATE,
									job.created_id AS CREATED_ID,
									job.edited_date AS EDITED_DATE,
									job.edited_id AS EDITED_ID,
									job.deleted_date AS DELETED_DATE,
									job.deleted_id AS DELETED_ID
								FROM 
									".get_ini('BDD_PREFIX')."core_jobs job 
								LEFT JOIN 
									".get_ini('BDD_PREFIX')."core_plugins plug
								ON
									job.id_plugin = plug.id
								AND plug.deleted_date = 0
								LEFT JOIN 
									".get_ini('BDD_PREFIX')."core_job_scheduled sched
								ON
									job.id = sched.id_job
								AND sched.deleted_date = 0
								LEFT JOIN
									".get_ini('BDD_PREFIX')."core_job_polling poll
								ON
									job.id = poll.id_job
								AND poll.deleted_date = 0
								WHERE 
									job.id=:id"); 
		$q0->execute(array( 'id' => $id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->ID))
		{
			$this->idPlugin = $r0->IDPLUGIN;
			$this->pluginName = $r0->PLUGINNAME;
			$this->page = $r0->PAGE;
			$this->lastRun = $r0->LASTRUN;
			$this->startPolling = $r0->POLLINGSTARTTIME;
			$this->endPolling = $r0->POLLINGENDTIME;
			$this->polling = $r0->POLLINGTIME;
			$this->startSchedule = $r0->SCHEDSTARTTIME;
			$this->endSchedule = $r0->SCHEDENDTIME;
			$this->daysSchedule = $r0->SCHEDDAYS;
			$this->hoursSchedule = $r0->SCHEDHOURS;
			$this->minutesSchedule = $r0->SCHEDMINUTES;
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
			echo 'The job does not exist.';
			exit(100);
		}
	}
	
	function updateScheduled($startTime,$endTime,$days,$hours,$minutes) {
		if($this->daysSchedule == '') {
        	$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_job_scheduled (id_job,start_time,end_time,days,hours,minutes,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:id_job,:start_time,:end_time,:days,:hours,:minutes,:created_id,:created_date,0,0,0,0)');
        	$q0->execute(array(	'id_job' => $this->id,
                    			'start_time' => $startTime,
                    			'end_time' => $endTime,
                    			'days' => $days,
                    			'hours' => $hours,
                    			'minutes' => $minutes,
								'created_id' => $_SESSION['USER_ID'],
								'created_date' => time()
					    ));
		}
		else
		{
        	$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_job_scheduled SET start_time=:start_time, end_time=:end_time, days=:days, hours=:hours, minutes=:minutes, edited_date=:edited_date, edited_id=:edited_id WHERE id_job=:id_job AND deleted_date=0');
        	$q0->execute(array(	'id_job' => $this->id,
                    			'start_time' => $startTime,
                    			'end_time' => $endTime,
                    			'days' => $days,
                    			'hours' => $hours,
                    			'minutes' => $minutes,
								'edited_id' => $_SESSION['USER_ID'],
								'edited_date' => time()
					    ));
		}
		$this->startSchedule = $startTime;
		$this->endSchedule = $endTime;
		$this->daysSchedule = $days;
		$this->hoursSchedule = $hours;
		$this->minutesSchedule = $minutes;
	}
	
	function updatePolling($startTime,$endTime,$polling) {
		if($this->polling == '') {
        	$q0 = get_link()->prepare('INSERT INTO '.get_ini('BDD_PREFIX').'core_job_polling (id_job,start_time,end_time,polling_time,created_id,created_date,edited_id,edited_date,deleted_id,deleted_date) VALUES (:id_job,:start_time,:end_time,:polling_time,:created_id,:created_date,0,0,0,0)');
        	$q0->execute(array(	'id_job' => $this->id,
                    			'start_time' => $startTime,
                    			'end_time' => $endTime,
                    			'polling_time' => $polling,
								'created_id' => $_SESSION['USER_ID'],
								'created_date' => time()
					    ));
		}
		else
		{
        	$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_job_polling SET start_time=:start_time, end_time=:end_time, polling_time=:polling_time, edited_date=:edited_date, edited_id=:edited_id WHERE id_job=:id_job AND deleted_date=0');
        	$q0->execute(array(	'id_job' => $this->id,
                    			'start_time' => $startTime,
                    			'end_time' => $endTime,
                    			'polling_time' => $polling,
								'edited_id' => $_SESSION['USER_ID'],
								'edited_date' => time()
					    ));
		}
		$this->startPolling = $startTime;
		$this->endPolling = $endTime;
		$this->polling = $polling;
	}
	
	function flagRunning() {
		$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_jobs SET last_run_pid=:last_run_pid,last_run=:last_run WHERE id=:id');
		$q0->execute(array(	'last_run_pid' => $_SESSION['USER_ID'],
							'last_run' => time(),
							'id' => $this->id
					));
	}
}

?>