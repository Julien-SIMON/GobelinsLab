<?php
// Load the process management class
$procM = new processusManager();

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
    		echo 'lancement du job '.$jobName.'.<BR>';
    		
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
				$error='XML file is not valid or process file does not exist.';
				echo $error.'<BR>';
			}
    		
    		echo 'fin du job '.$jobName.'.<BR>';
    		
			rename(get_ini('UPLOAD_FOLDER').'running/'.$jobName,get_ini('UPLOAD_FOLDER').'archived/'.$jobName);

			$procM->update($proc->id,'ended','100');
    		$procM->delete($proc->id);
    		
    		
    		
    		exit(0);
		}
		else 
		{
			echo 'le job '.$jobName.' existe déjà.<BR>';
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

//


/*
if(isset($argv[3])){$processId=$argv[3];}
elseif(isset($_POST['processId'])){$processId=$_POST['processId'];}
elseif(isset($_GET['processId'])){$processId=$_GET['processId'];}
else
{
	echo 'There is no pid to process.';
	exit(1);
}

// Build the proc class for this process
$proc = new process($processId);
	
$proc->update($proc->id,'running',1);

$jobName=$proc->get_cmd();

// Wait 500ms before moving the processing file
usleep(500000);
if(file_exists(get_ini('UPLOAD_FOLDER').'running/'.$jobName)){unlink(get_ini('UPLOAD_FOLDER').'running/'.$jobName);}
rename(get_ini('UPLOAD_FOLDER').'queue/'.$jobName,get_ini('UPLOAD_FOLDER').'running/'.$jobName);

// Valid the xml file with XSD
$domXmlFile = new DOMDocument;
$domXmlFile->preserveWhiteSpace = false;
$domXmlFile->Load(get_ini('UPLOAD_FOLDER').'running/'.$jobName);
if($domXmlFile->schemaValidate('plugins/core/xsd/core.xsd'))
{
	// Get header data
	$item_XML = $domXmlFile->getElementsByTagName('XML')->item(0);
	$item_HEADER = $item_XML->getElementsByTagName('HEADER')->item(0);
	$item_DATA = $item_XML->getElementsByTagName('DATA')->item(0);
	
	// Set timeout both on execution script and in the database
	set_time_limit($item_HEADER->getElementsByTagName('TIMEOUT')->item(0)->nodeValue);
	$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'PROCESSUS SET timeout=:timeout WHERE id=:id');
	$q0->execute(array(	'timeout' => $item_HEADER->getElementsByTagName('TIMEOUT')->item(0)->nodeValue , 'id' => $proc->id ));
	
	// Include the file we need
	$g=$item_HEADER->getElementsByTagName('PLUGIN')->item(0)->nodeValue;
	$p=$item_HEADER->getElementsByTagName('PAGE')->item(0)->nodeValue;
	
	// If the Xsd file exist
	if((((is_file('plugins/'.$g.'/xsd/'.$p.'.xsd'))&&$domXmlFile->schemaValidate('plugins/'.$g.'/xsd/'.$p.'.xsd'))||(!is_file('plugins/'.$g.'/xsd/'.$p.'.xsd')))&&(is_file('plugins/'.$g.'/'.$p.'.php')))
	{
		include_once('plugins/'.$g.'/'.$p.'.php');
	}
	else
	{
		$error='XML file is not valid or process file does not exist.';
	}
}
else
{
	$error='XML file is not valid.';
}

// if ko, archive the file in the failed folder
if(isset($error))
{
	$proc->update($proc->id,'failed','100');
	$proc->delete();
	rename(get_ini('UPLOAD_FOLDER').'running/'.$jobName,get_ini('UPLOAD_FOLDER').'failed/'.time().'-'.$jobName);
}
else
{
	// if ok, archive the file
	rename(get_ini('UPLOAD_FOLDER').'running/'.$jobName,get_ini('UPLOAD_FOLDER').'archived/'.time().'-'.$jobName);
	
	$proc->update($proc->id,'ended','100');
		
	$proc->delete();
}
*/
?>