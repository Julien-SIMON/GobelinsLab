<?php
// ------------------------------------------------------------------- //
// Add this statements only on the admin_plugins and setup page. This check if the current user is in the admins group
// ------------------------------------------------------------------- //
//$groupM = new groupManager();
//$user = new user($_SESSION['USER_ID']);
//if(!isset($user->groupIdArray)||!in_array($groupM->getId('admins'),$user->groupIdArray)){include('plugins/core/403.php');exit(403);}
// ------------------------------------------------------------------- //

$pluginVersion='0.0.1';

if(is_file('plugins/'.$name.'/setup/database.php')){
	include('plugins/'.$name.'/setup/database.php');

	foreach($databaseArray[strtoupper(get_ini('BDD_TYPE'))]['create_frame'] as $sql) {
		try {
			$q0=get_link()->prepare(str_replace('<prefix>',get_ini('BDD_PREFIX'),$sql));
	    	$q0->execute();
		} catch (Exception $e) {}
	}
}

// Add default table
//$tableM = new tableManager();
//if($tableM->getId('core_plugins')==0)			{$tableM->create('core_plugins');}
//if($tableM->getId('core_tables')==0)			{$tableM->create('core_tables');}
//if($tableM->getId('core_objects')==0)			{$tableM->create('core_objects');}
//if($tableM->getId('core_user_auths')==0)		{$tableM->create('core_user_auths');}
//if($tableM->getId('core_user_auth_methods')==0)	{$tableM->create('core_user_auth_methods');}
//if($tableM->getId('core_users')==0)				{$tableM->create('core_users');}
//if($tableM->getId('core_groups')==0)			{$tableM->create('core_groups');}
//if($tableM->getId('core_pages')==0)				{$tableM->create('core_pages');}
//if($tableM->getId('core_processus')==0)			{$tableM->create('core_processus');}

// Add plugin
/*
$pluginM = new pluginManager();

if($pluginM->getId('core')==0) {
	$idNewPlugin = $pluginM->create('core');
	
	$plugin = new plugin($idNewPlugin);
	
	$plugin->updateActivated(1);
} else {
	//TODO
}
*/
// 

// Add tables
$tableM = new tableManager();
if($tableM->getId('kb_doc')==0)
	$tableM->create('kb_doc');
if($tableM->getId('kb_doc_version')==0)
	$tableM->create('kb_doc_version');
if($tableM->getId('kb_doc_sources')==0)
	$tableM->create('kb_doc_sources');
if($tableM->getId('kb_doc_files')==0)
	$tableM->create('kb_doc_files');
if($tableM->getId('kb_doc_commit')==0)
	$tableM->create('kb_doc_commit');
if($tableM->getId('kb_doc_authors')==0)
	$tableM->create('kb_doc_authors');
if($tableM->getId('kb_doc_translators')==0)
	$tableM->create('kb_doc_translators');
	
$init = new initialisation();


// TOTO - create default rows
?>