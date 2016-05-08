<?php
// Load the process management class
$procM = new processusManager();

// Check scheduled to plan jobs
$q0=get_link()->prepare("
SELECT 
	job.id AS ID
FROM 
	".get_ini('BDD_PREFIX')."core_jobs job 
LEFT JOIN
	".get_ini('BDD_PREFIX')."core_plugins p
ON
	job.id_plugin = p.id
AND p.deleted_date = 0
where 
	job.deleted_date = 0
ORDER BY 
	p.name, job.page
		");
$q0->execute();
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{
	$job = new job($r0->ID);
	
	if($job->polling!=0 && $job->lastRun<time()-$job->polling) {
		if(!is_file(get_ini('UPLOAD_FOLDER').'queue/'.$job->pluginName.'_'.$job->page.'.xml') && !is_file(get_ini('UPLOAD_FOLDER').'running/'.$job->pluginName.'_'.$job->page.'.xml'))
		{
			$job->flagRunning();
			include('plugins/'.$job->pluginName.'/task_'.$job->page.'_loader.php');
		}
	}
}


// List all job to do in the queue folder
foreach (glob(get_ini('UPLOAD_FOLDER')."queue/*.xml") as $jobName) {
	$fileFullPath=str_replace('\\','/',$jobName);
	$jobName=substr($jobName,strrpos($jobName,'/')+1,strlen($jobName)-strrpos($jobName,'/')-1);
	
	// Valid the xml file with XSD
	$domXmlFile = new DOMDocument;
	$domXmlFile->preserveWhiteSpace = false;
	$domXmlFile->Load(get_ini('UPLOAD_FOLDER').'queue/'.$jobName);
	if($domXmlFile->schemaValidate('plugins/core/xsd/core.xsd'))
	{
		if($procM->getId($jobName)==0){
			$childProcId=$procM->create(0,$jobName,'',10);
    		usleep(500);
    		if($procM->getCount($jobName)>1) {
    			// Wait some millisecond to avoid concurent write
    			usleep(mt_rand(100,20000));
    			if($procM->getCount($jobName)>1) {
    				$procM->delete($childProcId);
    				exit(0);
    			}
    		}
    		echo _('#core#_#18#').' '.$jobName.'.<BR>';
    		
    		$proc = new processus($childProcId);
    		$procM->update($proc->id,'running','2');
			
    		if(file_exists(get_ini('UPLOAD_FOLDER').'running/'.$jobName)){unlink(get_ini('UPLOAD_FOLDER').'running/'.$jobName);}
			rename(get_ini('UPLOAD_FOLDER').'queue/'.$jobName,get_ini('UPLOAD_FOLDER').'running/'.$jobName);
    		
			// Get header data
			$item_XML = $domXmlFile->getElementsByTagName('XML')->item(0);
			$item_HEADER = $item_XML->getElementsByTagName('HEADER')->item(0);
			$item_DATA = $item_XML->getElementsByTagName('DATA')->item(0);
			
			// Set timeout both on execution script and in the database
			set_time_limit($item_HEADER->getElementsByTagName('TIMEOUT')->item(0)->nodeValue);
			$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_processus SET timeout=:timeout WHERE id=:id');
			$q0->execute(array(	'timeout' => $item_HEADER->getElementsByTagName('TIMEOUT')->item(0)->nodeValue , 'id' => $proc->id ));
			
			// Include the file we need
			$g=$item_HEADER->getElementsByTagName('PLUGIN')->item(0)->nodeValue;
			$p=$item_HEADER->getElementsByTagName('PAGE')->item(0)->nodeValue;
			
			// Get the date
			$xmlDate=toTime($item_HEADER->getElementsByTagName('DATE')->item(0)->nodeValue);
			
			// If the Xsd file exist
			if((is_file('plugins/'.$g.'/xsd/'.$p.'.xsd')&&$domXmlFile->schemaValidate('plugins/'.$g.'/xsd/'.$p.'.xsd')&&is_file('plugins/'.$g.'/'.$p.'.php'))
				||(!is_file('plugins/'.$g.'/xsd/'.$p.'.xsd')&&is_file('plugins/'.$g.'/'.$p.'.php')))
			{
				if($g!='core'&&is_file('plugins/'.$g.'/__functions.php')){require_once('plugins/'.$g.'/__functions.php');}
				include('plugins/'.$g.'/'.$p.'.php');
			}
			else
			{
				$error=_('#core#_#19#');
				echo $error.'<BR>';
			}
    		
    		echo _('#core#_#20#').' '.$jobName.'.<BR>';
    		
			rename(get_ini('UPLOAD_FOLDER').'running/'.$jobName,get_ini('UPLOAD_FOLDER').'archived/'.$jobName);

			$procM->update($proc->id,'ended','100');
    		$procM->delete($proc->id);
    		
    		exit(0);
		}
		else 
		{
			echo _('#core#_#21#').' '.$jobName.' '._('#core#_#22#').'.<BR>';
		}
	}
	else
	{	// If at the grace time, the file is not compliant with the XSD, put in the trash failed folder
		if(get_ini('PROCESS_GRACE_PERIODE')<(time()-filemtime($fileFullPath))) {
			rename($fileFullPath,get_ini('UPLOAD_FOLDER').'failed/'.$jobName);
		}
	}
	unset($domXmlFile);
}

?>