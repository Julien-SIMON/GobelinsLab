<?php
// ------------------------------------------------------------------- //
// Add this statements only on the admin_plugins and setup page. This check if the current user is in the admins group
// ------------------------------------------------------------------- //
$groupM = new groupManager();
// TODO - $user = new user($_SESSION['USER_ID']);
// TODO - if(!isset($user->groupIdArray)||!in_array($groupM->getId('admins'),$user->groupIdArray)){if($m!='j'){include('plugins/core/403.php');exit(403);}}
// ------------------------------------------------------------------- //

function listFilesInDirectories($dir) {
	$filesArray = array();
	foreach(scandir($dir) as $file) {
		if($file != '.' && $file != '..') {
			array_push($filesArray, $dir.'/'.$file);
			
			if(is_dir($dir.'/'.$file)) {
				foreach(listFilesInDirectories($dir.'/'.$file) as $subFile) {
					array_push($filesArray, $subFile);
				}
			}
		}
	}
	
	return $filesArray;
}


$zip = new ZipArchive();
  
if(is_dir('../backup/'))
{
    if($zip->open('backup_'.date('Y-m-d_His').'.zip', ZipArchive::CREATE) == TRUE)
	{
		foreach(listFilesInDirectories('.') as $file) {
			if(is_dir($file)) {
				$zip->addEmptyDir($file);
			} else {
				$zip->addFile($file, $file);
			}
		}
		
  		$zip->close();
	}
}

?>