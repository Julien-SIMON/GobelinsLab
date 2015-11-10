<?php
// ------------------------------------------------------------------- //
// Add this statements only on the admin_plugins and setup page. This check if the current user is in the admins group
// ------------------------------------------------------------------- //
//$groupM = new groupManager();
//$user = new user($_SESSION['USER_ID']);
//if(!isset($user->groupIdArray)||!in_array($groupM->getId('admins'),$user->groupIdArray)){include('plugins/core/403.php');exit(403);}
// ------------------------------------------------------------------- //



switch ($a) {
    case 'setupUpdate':
    	if(isset($_GET['name'])){$name=$_GET['name'];}elseif(isset($_POST['name'])){$name=$_POST['name'];}else{
    		// TODO ERROR
    		exit();
    	} 
    	
    	// Add plugin
		$pluginM = new pluginManager();
		
		if($pluginM->getId('core')==0) {
			$idNewPlugin = $pluginM->create('core');
			
			$plugin = new plugin($idNewPlugin);
			
			$plugin->updateActivated(1);
		
			// TODO : add access to the admin group
		} else {
			//TODO
		}
		
		// 
		
		// Add tables
		$tableM = new tableManager();
		if($tableM->getId('cmdb_environments')==0)
			$tableM->create('cmdb_environments');
		if($tableM->getId('cmdb_projects')==0)
			$tableM->create('cmdb_projects');
		if($tableM->getId('cmdb_devices')==0)
			$tableM->create('cmdb_devices');
		if($tableM->getId('cmdb_dev_os')==0)
			$tableM->create('cmdb_dev_os');
		if($tableM->getId('cmdb_db_instances')==0)
			$tableM->create('cmdb_db_instances');
		if($tableM->getId('cmdb_databases')==0)
			$tableM->create('cmdb_databases');
    break;
    default:
		//if(!is_file('conf/config.ini')){
			// If there is no implementation of the config.ini, display the form
		//}
    break; //<a href="#" onClick="$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_plugins&a=list\');"><span class="icon-refresh"> Rafraichir</a>
}
?>