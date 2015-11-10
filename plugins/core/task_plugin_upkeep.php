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

$pageM = new pageManager();

// Populate core_pages table in database
echo '<U>Add new Php pages : </U><BR>';
foreach($init->pluginsIndex as $plugin) {
	if ($handle = opendir('plugins/'.$plugin)) {
    	while (false !== ($file = readdir($handle)))
    	{
    	    if($file != "." && $file != ".." && $file != "" && strtolower(substr($file, strrpos($file, '.') + 1)) == 'php') {
    	    	$pageName=strtolower(substr($file, 0, strrpos($file, '.')));
				if($pageM->getId(getPluginId($plugin),$pageName)==0){
					$pageM->create(getPluginId($plugin),$pageName);
					echo 'Page '.$plugin.'/'.$pageName.' added<BR>';
				}
    	    }
    	}
    	closedir($handle);
	}
}
echo '<BR>';

?>