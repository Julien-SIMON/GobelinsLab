<?php
if(get_ini('DEFAULT_HOME_PAGE')=='all') {
	foreach($init->pluginsIndex as $plugin){
		if(is_file('plugins/'.$plugin.'/home.php')){
			include_once('plugins/'.$plugin.'/home.php');
			echo '<BR>';
		}
	}	
} else {
	if(is_file('plugins/'.get_ini('DEFAULT_HOME_PAGE').'/home.php')){
		include_once('plugins/'.get_ini('DEFAULT_HOME_PAGE').'/home.php');
	}
}
?>