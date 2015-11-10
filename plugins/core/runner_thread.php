<?php
if(isset($argv[3])){$processId=$argv[3];}
elseif(isset($_POST['processId'])){$processId=$_POST['processId'];}
elseif(isset($_GET['processId'])){$processId=$_GET['processId'];}
else
{
	echo 'There is no pid to process.';
	exit(1);
}

// Build the proc class for this process
$procM = new processusManager();
$proc = new processus($processId);
	
$procM->update($proc->id,'running',1);

// Wait 500ms before moving the processing file
usleep(500000);
if(file_exists(get_ini('UPLOAD_FOLDER').'running/'.$proc->cmd)){unlink(get_ini('UPLOAD_FOLDER').'running/'.$proc->cmd);}
rename(get_ini('UPLOAD_FOLDER').'queue/'.$proc->cmd,get_ini('UPLOAD_FOLDER').'running/'.$proc->cmd);

// Valid the xml file with XSD
$domXmlFile = new DOMDocument;
$domXmlFile->preserveWhiteSpace = false;
$domXmlFile->Load(get_ini('UPLOAD_FOLDER').'running/'.$proc->cmd);
if($domXmlFile->schemaValidate('plugins/core/xsd/core.xsd'))
{
	// Get header data
	$item_XML = $domXmlFile->getElementsByTagName('XML')->item(0);
	$item_HEADER = $item_XML->getElementsByTagName('HEADER')->item(0);
	$item_DATA = $item_XML->getElementsByTagName('DATA')->item(0);
	
	// Set timeout both on execution script and in the database
	set_time_limit($item_HEADER->getElementsByTagName('TIMEOUT')->item(0)->nodeValue);
	$q0 = get_link()->prepare('UPDATE '.get_ini('BDD_PREFIX').'core_processus SET timeout=:timeout WHERE id=:id');
	$q0->execute(array(	'timeout' => time() + $item_HEADER->getElementsByTagName('TIMEOUT')->item(0)->nodeValue , 'id' => $proc->id ));
	
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
	}
}
else
{
	$error='XML file is not valid.';
}

// if ko, archive the file in the failed folder
if(isset($error))
{
	$procM->update($proc->id,'failed','100');
	$procM->delete();
	rename(get_ini('UPLOAD_FOLDER').'running/'.$proc->cmd,get_ini('UPLOAD_FOLDER').'failed/'.time().'-'.$proc->cmd);
}
else
{
	// if ok, archive the file
	rename(get_ini('UPLOAD_FOLDER').'running/'.$proc->cmd,get_ini('UPLOAD_FOLDER').'archived/'.time().'-'.$proc->cmd);
	
	$procM->update($proc->id,'ended','100');
		
	$procM->delete($proc->id);
}
?>