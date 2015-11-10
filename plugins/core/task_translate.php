<?php
// ------------------------------------------------------------------- //
// Add this statements only on the admin_plugins and setup page. This check if the current user is in the admins group
// ------------------------------------------------------------------- //
$groupM = new groupManager();
// TODO - $user = new user($_SESSION['USER_ID']);
// TODO - if(!isset($user->groupIdArray)||!in_array($groupM->getId('admins'),$user->groupIdArray)){if($m!='j'){include('plugins/core/403.php');exit(403);}}
// ------------------------------------------------------------------- //

include('lib/getTextCompiler/php-mo.php');

foreach (glob("locale/*/*/*.po") as $poFileName) {
	phpmo_convert( $poFileName, str_replace('.po','.mo',$poFileName) );
}
?>