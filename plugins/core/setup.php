<?php

switch ($a) {
    case 'setupUpdateCreateTables':
		sleep(2);
		echo 'ok';
    break;
    case 'setupUpdate':
    	echo '
<script>
function setupGobelinsLab() {
	alert($(\'#setupCreateTables\').html());
	$(\'#setupCreateTables\').html(\'456\');
	insertLoader(\'#setupCreateTables\');
	alert(\'2\');
	$(\'#setupCreateTables\').load(\'index.php?m=a&g=core&p=setup&a=setupUpdateCreateTables\');
	alert(\'3\');
	insertLoader(\'#setupFillTables\');
}

setupGobelinsLab();
</script>
    		';
    	
		// Fill the database
		echo '
<h3>Create tables</h3>
<div id="setupCreateTables">123</div>

<h3>Fill tables</h3>
<div id="setupFillTables">123</div>
			';
		
		echo '
			<table>
				<tr>
			';
		echo '<th></th><td></td>';
		
		echo '
				</tr>
			</table>
			';
		
    break;
    case 'setupCreate':

		// Add tables
		//$tableM = new tableManager();
		//if($tableM->getId('core_plugins')==0)
		//	$tableM->create('core_plugins');
		//if($tableM->getId('core_tables')==0)
		//	$tableM->create('core_tables');
		//if($tableM->getId('core_objects')==0)
		//	$tableM->create('core_objects');
		//if($tableM->getId('core_user_auths')==0)
		//	$tableM->create('core_user_auths');
		//if($tableM->getId('core_user_auth_methods')==0)
		//	$tableM->create('core_user_auth_methods');
		//if($tableM->getId('core_users')==0)
		//	$tableM->create('core_users');
		//if($tableM->getId('core_groups')==0)
		//	$tableM->create('core_groups');
		//if($tableM->getId('core_pages')==0)
		//	$tableM->create('core_pages');
		//if($tableM->getId('core_processus')==0)
		//	$tableM->create('core_processus');
    break;

    default: //<a href="#" onClick="$( \'#tableSetupList\' ).load(\'index.php?m=a&g=core&p=admin_plugins&a=setupList\');"><span class="icon-refresh"> Rafraichir</a>

		echo '
			<h3>Setup</h3>
			<a href="index.php?p=setup&a=setupForm"><span class="icon iconfa-download"> Setup gobelinslab</a>
		';

    break; //<a href="#" onClick="$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_plugins&a=list\');"><span class="icon-refresh"> Rafraichir</a>
}

?>