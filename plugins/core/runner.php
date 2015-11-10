<?php
// Set the runner timeout
set_time_limit(get_ini('PROCESS_LIFE_TIME'));
$beginTime = time();
$endTime=$beginTime+get_ini('PROCESS_LIFE_TIME')-5; // The process auto kill 5 secondes before it life_time parameter

// If the process limit is to small, stop the runner load
if(get_ini('MAX_PROCESS')<2){
	echo date('Y-m-d H:i:s').' - The parameter MAX_PROCESS is to low.'."\n";
	exit(20);
}

// Stop runner process if there is some which are not updated
$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_processus SET status=:status, deleted_date=:deleted_date, deleted_id=:deleted_id WHERE cmd=:cmd AND edited_date<:min_date AND deleted_date = 0');
$q0->execute(array(	'status' => 'failed',
					'cmd' => get_ini('PROCESS_NAME'),
					'min_date' => (time()-120),
					'deleted_id' => $_SESSION['USER_ID'],
					'deleted_date' => time()
			));

// Check if other runner already exist
$q0=get_link()->prepare("SELECT id AS ID FROM ".get_ini('BDD_PREFIX')."core_processus WHERE cmd = :cmd AND (deleted_date = 0 AND timeout>:current_date)"); 
$q0->execute(array( 'cmd' => get_ini('PROCESS_NAME') , 'current_date' => time() ));
$r0 = $q0->fetch(PDO::FETCH_OBJ);
if(isset($r0->ID))
{
	echo date('Y-m-d H:i:s').' - There is already a loaded runner.'."\n";
	exit(0);
}
// If not load the process class
else
{
	$procM = new processusManager();
	
	$procId=$procM->create(0,get_ini('PROCESS_NAME'),'',time()+get_ini('PROCESS_LIFE_TIME'));
	
	$proc = new processus($procId);
	
	echo date('Y-m-d H:i:s').' - Runner loaded - process id: '.$proc->id."\n";
	
	$lastCollectorTime=0;
	while($endTime>time())
	{		
		// Check if another process don't kill this process
		$q0=get_link()->prepare("SELECT status AS PROCESSSTATUS FROM ".get_ini('BDD_PREFIX')."core_processus WHERE id = :id AND status = 'failed'"); 
		$q0->execute(array( 'id' => $proc->id ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		if(isset($r0->PROCESSSTATUS))
		{
			echo date('Y-m-d H:i:s').' - Another process kill me.'."\n";
			exit(0);
		}

		// Update process status
		$procM->update($proc->id,'running',floor((time()-$beginTime)*100/($endTime-$beginTime)));
		
		// Collector task / Limit run to avoid to much I/O
		if($lastCollectorTime<(time()-get_ini('COLLECTOR_POLLING'))){
			echo date('Y-m-d H:i:s').' - Runner load the collector'."\n";
			
			// Delete old not ended process
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_processus SET status=:status, deleted_date=:deleted_date, deleted_id=:deleted_id WHERE timeout < :current_date AND deleted_date = 0');
			$q0->execute(array(	'status' => 'failed',
								'current_date' => time(),
								'deleted_id' => $_SESSION['USER_ID'],
								'deleted_date' => time()
						));
			// TODO - select => while => $procM->delete à la place du bloc ci dessus
		
			// Delete old process
			$q0 = get_link()->prepare('DELETE FROM '.get_ini('BDD_PREFIX').'core_processus WHERE deleted_date < :delete_date AND deleted_date > 0');
			$q0->execute(array(	'delete_date' => time()-get_ini('DELETED_PROCESS_LIFE_TIME')));	
						
			// Delete old file
			$folder = new DirectoryIterator(get_ini('UPLOAD_FOLDER').'running/');
			foreach($folder as $file)
				if($file->isFile() && !$file->isDot() && (time() - $file->getMTime() > get_ini('UPLOAD_RUNNING_FILE_LIFE')))
					unlink($file->getPathname());
			$folder = new DirectoryIterator(get_ini('UPLOAD_FOLDER').'failed/');
			foreach($folder as $file)
				if($file->isFile() && !$file->isDot() && (time() - $file->getMTime() > get_ini('UPLOAD_FAILED_FILE_LIFE')))
					unlink($file->getPathname());
			$folder = new DirectoryIterator(get_ini('UPLOAD_FOLDER').'archived/');
			foreach($folder as $file)
				if($file->isFile() && !$file->isDot() && (time() - $file->getMTime() > get_ini('UPLOAD_ARCHIVED_FILE_LIFE')))
					unlink($file->getPathname());	
			
			$lastCollectorTime=time();
		}
		
		//echo 'There is '.$proc->runningCount().' process available.'."\n";
		
		// Load process if some are available
		$q0=get_link()->prepare("SELECT count(*) AS PENDINGCOUNT FROM ".get_ini('BDD_PREFIX')."core_processus WHERE status = 'pending' AND (deleted_date = 0 AND TIMEOUT>:current_date)"); 
		$q0->execute(array( 'current_date' => time() ));
		$r0 = $q0->fetch(PDO::FETCH_OBJ);
		$pendingProc=$r0->PENDINGCOUNT;
		while($procM->getRunningCount()<=get_ini('MAX_PROCESS') && $pendingProc>0)
		{ 	
			$pendingProc--;
			// Process result file first
			
			$q0=get_link()->prepare("SELECT MIN(id) AS ID FROM ".get_ini('BDD_PREFIX')."core_processus WHERE status = 'pending' AND cmd LIKE '%result%' AND (deleted_date = 0 AND TIMEOUT>:current_date)"); 
			$q0->execute(array( 'current_date' => time()	));
			$r0 = $q0->fetch(PDO::FETCH_OBJ);
			if(isset($r0->ID))
			{
				$currentProc=$r0->ID;
			}
			else // Else, process other cmd
			{
				$q0=get_link()->prepare("SELECT MIN(id) AS ID FROM ".get_ini('BDD_PREFIX')."core_processus WHERE status = 'pending' AND (deleted_date = 0 AND TIMEOUT>:current_date)"); 
				$q0->execute(array( 'current_date' => time()	));
				$r0 = $q0->fetch(PDO::FETCH_OBJ);
				if(isset($r0->ID))
				{
					$currentProc=$r0->ID;
				}
				else
				{
					$currentProc=0;
				}
			}
			
			// If there is some cmd to process
			if($currentProc>0){
				$procM->update($currentProc,'starting',0);
				
				if (strpos(strtoupper(php_uname('s')), 'WIN')!==false) { // Win 
					pclose(popen('start /B '.get_ini('PHP_BIN_FOLDER').'\php -f "index.php" --  core runner_thread "'.$currentProc.'"', "r")); // >> '.$childProcessId.'.log 2>&1
				} else { // Other 
					system(get_ini('PHP_BIN_FOLDER').'php index.php core runner_thread "'.$currentProc.'" &');
				}
			}		
		}
				
		// Check queue folder for new file
	    $dir = get_ini('UPLOAD_FOLDER').'queue/';
		$currentDir = opendir($dir);
		while ($fileName = readdir($currentDir)) 
		{
			// Process only the xml files
			if((is_file($dir.$fileName))&&(strtolower(substr(strrchr($fileName,'.'),1,strlen($fileName)-1))=='xml'))
			{
				$domXmlFile = new DOMDocument;
				$domXmlFile->preserveWhiteSpace = false;
				$domXmlFile->Load(get_ini('UPLOAD_FOLDER').'queue/'.$fileName);
				if($domXmlFile->schemaValidate('plugins/core/xsd/core.xsd'))
				{
					if($procM->getId($fileName)==0){
						$childProcId=$procM->create($procId,$fileName,'',time()+3600);
						$procM->update($childProcId,'pending',0);
						
						echo date('Y-m-d H:i:s').' - Runner add new proc - process id: '.$childProcId.' - command: '.$fileName."\n";
					}
				}
				else
				{	// If at the grace time, the file is not compliant with the XSD, put in the trash failed folder
					if(get_ini('PROCESS_GRACE_PERIODE')<(time()-filemtime(get_ini('UPLOAD_FOLDER').'queue/'.$fileName))) {
						rename(get_ini('UPLOAD_FOLDER').'queue/'.$fileName,get_ini('UPLOAD_FOLDER').'failed/'.$fileName);
					}
				}
				unset($domXmlFile);
			}
		}
		closedir($currentDir);
		
		// Wait to avoid CPU over usage
		usleep(50000);
	}
	
	$procM->update($proc->id,'ended','100');
	
	$procM->delete($proc->id);
	
	echo date('Y-m-d H:i:s').' - Runner ended - process id: '.$proc->id."\n";
}
?>