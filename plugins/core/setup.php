<?php
// ------------------------------------------------------------------- //
// Add this statements only on the admin_plugins and setup page. This check if the current user is in the admins group
// ------------------------------------------------------------------- //
$groupM = new groupManager();
$user = new user($_SESSION['USER_ID']);
if(!isset($user->groupIdArray)||!in_array($groupM->getId('admins'),$user->groupIdArray)){include('plugins/core/403.php');exit(403);}
// ------------------------------------------------------------------- //

//$q0 = get_link()->prepare('INSERT INTO GLDEV.gl_core_security ( id, id_source, id_target, secure_level, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '1', '21', '1', '100', 0, 0, 0, 0, 0, 0)');
//$q0->execute();

switch ($a) {
    case 'setupForm':
    	if(isset($_GET['name'])){$name=$_GET['name'];}elseif(isset($_POST['name'])){$name=$_POST['name'];}else{
    		// TODO ERROR
    		exit();
    	} 
    	
    	if(is_file('plugins/'.$name.'/setup.php')) {include('plugins/'.$name.'/setup.php');}
    break;
    default: //<a href="#" onClick="$( \'#tableSetupList\' ).load(\'index.php?m=a&g=core&p=admin_plugins&a=setupList\');"><span class="icon-refresh"> Rafraichir</a>
		
		
		// Add tables
		$tableM = new tableManager();
		if($tableM->getId('core_plugins')==0)
			$tableM->create('core_plugins');
		if($tableM->getId('core_tables')==0)
			$tableM->create('core_tables');
		if($tableM->getId('core_objects')==0)
			$tableM->create('core_objects');
		if($tableM->getId('core_user_auths')==0)
			$tableM->create('core_user_auths');
		if($tableM->getId('core_user_auth_methods')==0)
			$tableM->create('core_user_auth_methods');
		if($tableM->getId('core_users')==0)
			$tableM->create('core_users');
		if($tableM->getId('core_groups')==0)
			$tableM->create('core_groups');
		if($tableM->getId('core_pages')==0)
			$tableM->create('core_pages');
		if($tableM->getId('core_processus')==0)
			$tableM->create('core_processus');
    break; //<a href="#" onClick="$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_plugins&a=list\');"><span class="icon-refresh"> Rafraichir</a>
}

?>