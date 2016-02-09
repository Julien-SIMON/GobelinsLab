<?php
echo '
<ul data-role="listview" id="ui-navmenu-list" class="ui-alt-icon ui-nodisc-icon">
	<li data-icon="home"><a href="index.php" data-ajax="false">Home</a></li>
	<li><a href="index.php?g=core&p=about" data-ajax="false">About</a></li>

';

// Administration options
if(getAccess(get_object_id('core_plugins',getPluginId('core')))>=10) {
	echo '
<li data-role="collapsible" data-collapsed-icon="carat-d" data-expanded-icon="carat-u" data-iconpos="right" data-inset="false">
<h3>Administration</h3>
<ul data-role="listview">';
	echo '<li><a href="index.php?g=core&p=admin_users" data-ajax="false">Utilisateurs</a></li>';
	echo '<li><a href="index.php?g=core&p=admin_groups" data-ajax="false">Groupes</a></li>';
	echo '<li><a href="index.php?g=core&p=admin_parameters" data-ajax="false">Paramètres</a></li>';
	echo '<li><a href="index.php?g=core&p=admin_plugins" data-ajax="false">Plugins</a></li>';
	echo '<li><a href="index.php?g=core&p=admin_access" data-ajax="false">Securité</a></li>';
	echo '
</ul>
	';
}




// Include plugins menu if exist
foreach($init->pluginsIndex as $plugin) {
	if($plugin != 'core') {
		echo '
<li data-role="collapsible" data-collapsed-icon="carat-d" data-expanded-icon="carat-u" data-iconpos="right" data-inset="false">
<h3>'.$plugin.'</h3>
<ul data-role="listview">';
		include('plugins/'.$plugin.'/menu.php');
		echo '
</ul>
		';
	}
}

echo '
	</li>
</ul>
';
//if(secure(get_object_id($table_name,$id_ext),get_object_id('core',$id_ext))){
?>