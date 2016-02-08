<?php
// ------------------------------------------------------------------- //
// Add this statements only on the admin_plugins and setup page. This check if the current user is in the admins group
// ------------------------------------------------------------------- //
//$groupM = new groupManager();
//$user = new user($_SESSION['USER_ID']);
//if(!isset($user->groupIdArray)||!in_array($groupM->getId('admins'),$user->groupIdArray)){include('plugins/core/403.php');exit(403);}
// ------------------------------------------------------------------- //


if(is_file('plugins/'.$name.'/setup/database.php')){
	include('plugins/'.$name.'/setup/database.php');

	foreach($databaseArray['MYSQL']['create_frame'] as $sql) {
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
if($tableM->getId('cmdb_environments')==0)
	$tableM->create('cmdb_environments');
if($tableM->getId('cmdb_projects')==0)
	$tableM->create('cmdb_projects');
if($tableM->getId('cmdb_devices')==0)
	$tableM->create('cmdb_devices');
if($tableM->getId('cmdb_dev_pro_env_map')==0)
	$tableM->create('cmdb_dev_pro_env_map');
if($tableM->getId('cmdb_dev_status')==0)
	$tableM->create('cmdb_dev_status');
if($tableM->getId('cmdb_dev_os')==0)
	$tableM->create('cmdb_dev_os');
if($tableM->getId('cmdb_dev_os_cpu')==0)
	$tableM->create('cmdb_dev_os_cpu');
if($tableM->getId('cmdb_dev_os_disks')==0)
	$tableM->create('cmdb_dev_os_disks');
if($tableM->getId('cmdb_dev_os_fs')==0)
	$tableM->create('cmdb_dev_os_fs');
if($tableM->getId('cmdb_dev_os_fs_up')==0)
	$tableM->create('cmdb_dev_os_fs_up');
if($tableM->getId('cmdb_dev_os_services')==0)
	$tableM->create('cmdb_dev_os_services');
if($tableM->getId('cmdb_dev_os_creds')==0)
	$tableM->create('cmdb_dev_os_creds');
if($tableM->getId('cmdb_db_instances')==0)
	$tableM->create('cmdb_db_instances');
if($tableM->getId('cmdb_db_instance_up')==0)
	$tableM->create('cmdb_db_instance_up');
if($tableM->getId('cmdb_db_instance_creds')==0)
	$tableM->create('cmdb_db_instance_creds');
if($tableM->getId('cmdb_databases')==0)
	$tableM->create('cmdb_databases');
if($tableM->getId('cmdb_db_backups')==0)
	$tableM->create('cmdb_db_backups');
if($tableM->getId('cmdb_db_filegroups')==0)
	$tableM->create('cmdb_db_filegroups');
if($tableM->getId('cmdb_db_filegroup_status')==0)
	$tableM->create('cmdb_db_filegroup_status');
	
$init = new initialisation();


// TOTO - create default rows
?>