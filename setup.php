<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

ini_set('session.gc_maxlifetime', 86400);

date_default_timezone_set("Europe/Paris");

session_start();
$_SESSION['USER_ID']=0;

require_once('plugins/core/__functions.php');

$version='0.0.2';



if(!isset($s)){if(isset($_GET['s'])){$s=$_GET['s'];}elseif(isset($_POST['s'])){$s=$_POST['s'];}} // Step

if(isset($s)&&$s>19&&!is_file('conf/config.ini')){ $s=-2; } // If config file does not exist and a poststep is call, cancel
elseif(isset($s)&&$s<=19&&is_file('conf/config.ini')){ $s=-3; } // If config file exist and a prestep is call, cancel

// Define the current step
if(!isset($s)) {
	if(!is_file('conf/config.ini')){
		$s=1;
	} else {
		$s=20;
	}
}




// Check current version versus setup version
if(is_file('conf/version')){if(file_get_contents('conf/version')==$version) { $s=-1; }}

if($s>=20) {
	$init = new initialisation();
	$rijn = new rijn();
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" /> <!-- for IE in intranet -->
	
	<title>GobelinsLabSetup</title>
	
	<!-- Favicon -->
	<link rel="shortcut icon" href="images/favicon.ico">
	
	<!-- Metadata -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    
	<!-- Librairie Jquery -->
	<link rel="stylesheet"  href="lib/jqueryMobile/1.4.5/jquery.mobile-1.4.5.min.css">
	<script src="lib/jquery/jquery-2.1.4.min.js"></script>
	<script type="text/javascript">$(document).bind("mobileinit", function () {$.mobile.ajaxEnabled = false;});</script> <!-- Desactive le data-ajax par défaut pour Jquery-mobile -->
	<script src="lib/jqueryMobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	
	<!-- Librairie awesome fonts -->
	<link rel="stylesheet" href="lib/fontastics/PACK201509/font-awesome.css">
	<!-- <link rel="stylesheet" href="lib/fontastics/fontastics.css"> -->
	
	<!-- Js et Css -->
	<link rel="stylesheet" href="css/_main.css">
	<script src="js/_main.js"></script>
	
	<script type="text/javascript">
function insertLoader(div) {
	$(div).html('<img src="images/loader.gif" alt="loader" height="47" width="48" style="magin: 25 25 25 25;">');
}
	
function popupFormSubmit(url,data) {
	insertLoader('#popupContent');
	$.ajax( {
		type: "POST",
		url: url,
		data: data,
		success: function( response ) {
			$('#popupContent').html( response );
		}
	} );
}
	</script>
</head>
<body>
<div data-role="page" class="ui-page">

	<!-- Popup container -->
	<div data-role="popup" id="popup"  data-overlay-theme="b" data-theme="a" class="ui-corner-all">
	<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>

	<!-- Header container -->
	<form id="popupForm" onsubmit="return false;" autocomplete="off">
	<div id="popupContent" style="padding:10px 20px;"></div>
	</form>
	</div>

	<div data-role="header" id="ui-header">
		<h2>GobelinsLab - Install/Update - v<?php echo $version; ?></h2>
	</div><!-- /header -->

	<div role="main" id="ui-content">
<?php

switch ($s) {
	case -1:
		echo '
<h3>error</h3>
The setup/upgrade process detect that the previous install is already in \'v'.$version.'\'. Please delete the \'conf/version\' file then refresh this web page.
		';
	break;
	case -2:
		echo '
<h3>error</h3>
You need to generate the config file. Please go at the first step of this process.<BR>
<BR>
<a href="setup.php?s=10"><span class="icon iconfa-stackoverflow"> Database configuration </span></a>
		';
	break;
	case -3:
		echo '
<h3>error</h3>
You cannot generate another config file. One are already present. Please go at the "fill database" step of this process.<BR>
<BR>
<a href="setup.php?s=20"><span class="icon iconfa-stackoverflow"> Fill the database </span></a>
		';
	break;
	// Finalize 
	case 100:
		echo '
<h3>Step 6/6 - Installation completed</h3>
	    ';
	    
		file_put_contents('conf/version',$version);
		
		echo 'Please note these informations:<BR>Login: <B>admin</B><BR>Password: <B>gob</B><BR><BR><a href="index.php"><span class="icon iconfa-stackoverflow"> Installation complete</a>';
	break;
	case 23:
		echo '
<h3>Step 5/6 - Fill the auxiliary tables</h3>
	    ';
		
		// Add default table
		echo ' - Fill translation table<BR>';
		$transM = new translationManager();
		$pluginM = new pluginManager();
		$locM = new localeManager();
		
		$pgId=$pluginM->getId('core');
		$langArray=array(
			'fr_FR'=>$locM->getId('fr_FR'),
			'en_US'=>$locM->getId('en_US'),
			'de_DE'=>$locM->getId('de_DE'),
			'it_IT'=>$locM->getId('it_IT')
		);
		
		
		$index = 0;
		$comment = 'core / Visiteur';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Visiteur',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Guest',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'Besucher',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'visitatore',$comment);
		
		$index = 1;
		$comment = 'languages_selection / Choix de la langue';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Choix de la langue',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Language selection',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'Sprache der Wahl',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'Scelta della lingua',$comment);
		
		$index = 2;
		$comment = 'menu / MENU PRINCIPAL';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'MENU PRINCIPAL',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'MAIN MENU',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'HAUPTMENU',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'MENU PRINCIPALE',$comment);
		
		$index = 3;
		$comment = 'menu / Accueil';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Accueil',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Home',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'Home',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'Startseite',$comment);
		
		$index = 4;
		$comment = 'menu / A propos';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'A propos',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'About',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'Etwa',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'Di',$comment);
		
		$index = 5;
		$comment = 'menu / Administration';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Administration',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Administration',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'Verwaltung',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'Amministrazione',$comment);
		
		$index = 6;
		$comment = 'menu / Utilisateurs';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Utilisateurs',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Users',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'TODO',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'TODO',$comment);
		
		$index = 7;
		$comment = 'menu / Groupes';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Groupes',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Groups',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'TODO',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'TODO',$comment);
		
		$index = 8;
		$comment = 'menu / Paramètres';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Paramètres',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Parameters',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'TODO',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'TODO',$comment);
		
		$index = 9;
		$comment = 'menu / Plugins';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Plugins',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Plugins',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'TODO',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'TODO',$comment);
		
		$index = 10;
		$comment = 'menu / Securité';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Securité',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Security',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'TODO',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'TODO',$comment);
		
		$index = 11;
		$comment = 'menu / Traduction';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Traduction',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Translation',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'TODO',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'TODO',$comment);
			
		$index = 12;
		$comment = 'index / Note: Cette page a été entièrement traduite avec un outil automatique';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Note: Cette page a été entièrement traduite avec un outil automatique',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Note: This webpage was completly translate with an automatic tool',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'TODO',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'TODO',$comment);
			
		$index = 13;
		$comment = 'index / Note: Cette page a été partiellement traduite avec un outil automatique';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Note: Cette page a été partiellement traduite avec un outil automatique',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Note: This webpage was partially translate with an automatic tool',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'TODO',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'TODO',$comment);
			
		$index = 14;
		$comment = 'processus / Voir tous les processus';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Voir tous les processus',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'Display all processus',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'TODO',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'TODO',$comment);
			
		$index = 15;
		$comment = 'processus / Vous n\'avez aucun processus en cours';
		if($transM->getId($pgId,$langArray['fr_FR'],$index)==0)
			$transM->create($pgId,$langArray['fr_FR'],$index,'Vous n\'avez aucun processus en cours',$comment);		
		if($transM->getId($pgId,$langArray['en_US'],$index)==0)
			$transM->create($pgId,$langArray['en_US'],$index,'There is no running process',$comment);		
		if($transM->getId($pgId,$langArray['de_DE'],$index)==0)
			$transM->create($pgId,$langArray['de_DE'],$index,'TODO',$comment);		
		if($transM->getId($pgId,$langArray['it_IT'],$index)==0)
			$transM->create($pgId,$langArray['it_IT'],$index,'TODO',$comment);

		echo '<BR><BR><a href="setup.php?s=100"><span class="icon iconfa-stackoverflow"> Finalize setup operation</a>';
	break;
	case 22:
		echo '
<h3>Step 4/6 - Fill the primary tables</h3>
	    ';


		
		// Add default table
		echo ' - Fill tables table<BR>';
		$tableM = new tableManager();
		if($tableM->getId('core_plugins')==0)			{$tableM->create('core_plugins');}
		if($tableM->getId('core_tables')==0)			{$tableM->create('core_tables');}
		if($tableM->getId('core_objects')==0)			{$tableM->create('core_objects');}
		if($tableM->getId('core_user_auths')==0)		{$tableM->create('core_user_auths');}
		if($tableM->getId('core_user_auth_methods')==0)	{$tableM->create('core_user_auth_methods');}
		if($tableM->getId('core_users')==0)				{$tableM->create('core_users');}
		if($tableM->getId('core_groups')==0)			{$tableM->create('core_groups');}
		if($tableM->getId('core_pages')==0)				{$tableM->create('core_pages');}
		if($tableM->getId('core_processus')==0)			{$tableM->create('core_processus');}
		if($tableM->getId('core_parameters')==0)		{$tableM->create('core_parameters');}
		if($tableM->getId('core_pages')==0)				{$tableM->create('core_pages');}

		$init = new initialisation();
		
		// Add default plugin
		echo ' - Fill plugin table<BR>';
		$pluginM = new pluginManager();
		if($pluginM->getId('core')==0)					{
			$pluginM->create('core',$version);
			$plugin = new plugin($pluginM->getId('core'));
			$plugin->updateActivated(1);
		}

		$init = new initialisation();
		
		// Add locale 
		echo ' - Fill locale table<BR>';
		$locM = new localeManager(); 
        if($locM->getId('fr_FR')==0) 					{$locM->create('fr_FR','Français','icon-libflags-fr');}
		if($locM->getId('en_US')==0) 					{$locM->create('en_US','American','icon-libflags-us');}
		if($locM->getId('de_DE')==0) 					{$locM->create('de_DE','Deutsch','icon-libflags-de');}
		if($locM->getId('it_IT')==0) 					{$locM->create('it_IT','Italiano','icon-libflags-it');}
		
		// Add default auth method
		echo ' - Fill auth methods table<BR>';
		$authMethodM = new authMethodManager();
		if($authMethodM->getId('LOCAL')==0)				{$authMethodM->create('LOCAL','iconfa-log-in');}
		if($authMethodM->getId('LDAP')==0)				{$authMethodM->create('LDAP','iconfa-sitemap');}
		if($authMethodM->getId('FACEBOOK')==0)			{$authMethodM->create('FACEBOOK','iconfa-facebook-square');}
		if($authMethodM->getId('GOOGLE')==0)			{$authMethodM->create('GOOGLE','iconfa-google-plus-1');}

		// Add default group
		echo ' - Fill groups table<BR>';
		$groupM = new groupManager();
		if($groupM->getId('admins')==0)					{$groupM->create('admins');}
		if($groupM->getId('members')==0)				{$groupM->create('members');}
		if($groupM->getId('guests')==0)					{$groupM->create('guests');}
		
		// Add default user
		echo ' - Fill users table<BR>';
		$userM = new userManager();
		if($userM->getIdByName('admin')==0)				{$userM->create('admin', 'lib/avatars/brain.jpg', '');}
		if($userM->getIdByName('#core#_#0#')==0)		{$userM->create('#core#_#0#', 'lib/avatars/brain.jpg', '');} // Guest user
		if($userM->getIdByName('process')==0)			{$userM->create('process', 'lib/avatars/brain.jpg', '');}
		
		// Add default user group mapping
		echo ' - Fill user group maps table<BR>';
		$groupM = new groupManager();
		$userM = new userManager();
		if($groupM->getGroupUserMap($groupM->getId('admins'),$userM->getIdByName('admin'))==0){$groupM->addGroupUserMap($groupM->getId('admins'),$userM->getIdByName('admin'));}
		if($groupM->getGroupUserMap($groupM->getId('admins'),$userM->getIdByName('process'))==0){$groupM->addGroupUserMap($groupM->getId('admins'),$userM->getIdByName('process'));}
		if($groupM->getGroupUserMap($groupM->getId('guests'),$userM->getIdByName('#core#_#0#'))==0){$groupM->addGroupUserMap($groupM->getId('guests'),$userM->getIdByName('#core#_#0#'));}
		
		// Add default auth 
		echo ' - Fill auths table<BR>';
		$authM = new authManager();
		$authMethodM = new authMethodManager();
		$userM = new userManager();
		if($authM->getId($userM->getIdByName('admin'),$authMethodM->getId('LOCAL'))==0)			{$authM->create($userM->getIdByName('admin'),$authMethodM->getId('LOCAL'),'admin','gob','lib/avatars/brain.jpg','','','admin');}
		
		// Add default access
		echo ' - Fill access table<BR>';
		$accessM = new accessManager();
		$objectM = new objectManager();
		$groupM = new groupManager();
		$pluginM = new pluginManager();
		if($accessM->getId($objectM->getId(getTableId('core_groups'),$groupM->getId('admins')),$objectM->getId(getTableId('core_plugins'),$pluginM->getId('core')))==0)	{
			$accessM->create($objectM->getId(getTableId('core_groups'),$groupM->getId('admins')),$objectM->getId(getTableId('core_plugins'),$pluginM->getId('core')),100);
		}
		if($accessM->getId($objectM->getId(getTableId('core_groups'),$groupM->getId('members')),$objectM->getId(getTableId('core_plugins'),$pluginM->getId('core')))==0)	{
			$accessM->create($objectM->getId(getTableId('core_groups'),$groupM->getId('members')),$objectM->getId(getTableId('core_plugins'),$pluginM->getId('core')),10);
		}
		if($accessM->getId($objectM->getId(getTableId('core_groups'),$groupM->getId('guests')),$objectM->getId(getTableId('core_plugins'),$pluginM->getId('core')))==0)	{
			$accessM->create($objectM->getId(getTableId('core_groups'),$groupM->getId('guests')),$objectM->getId(getTableId('core_plugins'),$pluginM->getId('core')),0);
		}
		
		// Add default parameter
		echo ' - Fill parameters table<BR>';
		$parameterM = new parameterManager();
		if($parameterM->getId(getPluginId('core'),'DEFAULT_GROUP')==0)		{$parameterM->create(getPluginId('core'),'DEFAULT_GROUP','members','members');}
		if($parameterM->getId(getPluginId('core'),'LOCAL_REGISTER')==0)		{$parameterM->create(getPluginId('core'),'LOCAL_REGISTER','true','true');}
		if($parameterM->getId(getPluginId('core'),'LOCAL_CONNEXION')==0)	{$parameterM->create(getPluginId('core'),'LOCAL_CONNEXION','true','true');}
		if($parameterM->getId(getPluginId('core'),'LDAP_REGISTER')==0)		{$parameterM->create(getPluginId('core'),'LDAP_REGISTER','false','false');}
		if($parameterM->getId(getPluginId('core'),'LDAP_CONNEXION')==0)		{$parameterM->create(getPluginId('core'),'LDAP_CONNEXION','false','false');}
		if($parameterM->getId(getPluginId('core'),'FACEBOOK_REGISTER')==0)	{$parameterM->create(getPluginId('core'),'FACEBOOK_REGISTER','false','false');}
		if($parameterM->getId(getPluginId('core'),'FACEBOOK_CONNEXION')==0)	{$parameterM->create(getPluginId('core'),'FACEBOOK_CONNEXION','false','false');}
		if($parameterM->getId(getPluginId('core'),'GOOGLE_REGISTER')==0)	{$parameterM->create(getPluginId('core'),'GOOGLE_REGISTER','false','false');}
		if($parameterM->getId(getPluginId('core'),'GOOGLE_CONNEXION')==0)	{$parameterM->create(getPluginId('core'),'GOOGLE_CONNEXION','false','false');}
		if($parameterM->getId(getPluginId('core'),'LDAP_DEFAULT_DOMAIN')==0){$parameterM->create(getPluginId('core'),'LDAP_DEFAULT_DOMAIN','FR','FR');}
		if($parameterM->getId(getPluginId('core'),'LDAP_SERVERS')==0)		{$parameterM->create(getPluginId('core'),'LDAP_SERVERS','ldap://','ldap://');}
		
		if($parameterM->getId(getPluginId('core'),'APPLICATION_NAME')==0)	{$parameterM->create(getPluginId('core'),'APPLICATION_NAME','GobelinsLab','GobelinsLab');}
		if($parameterM->getId(getPluginId('core'),'TIMEZONE')==0)			{$parameterM->create(getPluginId('core'),'TIMEZONE','Europe/Paris','Europe/Paris');}
		if($parameterM->getId(getPluginId('core'),'LOGO')==0)				{$parameterM->create(getPluginId('core'),'LOGO','images/logo.png','images/logo.png');}
		if($parameterM->getId(getPluginId('core'),'LOADER')==0)				{$parameterM->create(getPluginId('core'),'LOADER','images/loader.gif','images/loader.gif');}
		if($parameterM->getId(getPluginId('core'),'ADMIN_MAIL')==0)			{$parameterM->create(getPluginId('core'),'ADMIN_MAIL','','xxxxxx@xxxxxx.xxx');}
		if($parameterM->getId(getPluginId('core'),'BACKUP_FOLDER')==0)		{$parameterM->create(getPluginId('core'),'BACKUP_FOLDER','../backup/','../backup/');}
		if($parameterM->getId(getPluginId('core'),'TMP_FOLDER')==0)			{$parameterM->create(getPluginId('core'),'TMP_FOLDER','tmp/','tmp/');}
		if($parameterM->getId(getPluginId('core'),'LANGUAGE_FOLDER')==0)	{$parameterM->create(getPluginId('core'),'LANGUAGE_FOLDER','locale/','locale/');}
		if($parameterM->getId(getPluginId('core'),'DEFAULT_LANGUAGE')==0)	{$parameterM->create(getPluginId('core'),'DEFAULT_LANGUAGE','fr_FR','fr_FR');}
		if($parameterM->getId(getPluginId('core'),'PHP_BIN_FOLDER')==0)		{$parameterM->create(getPluginId('core'),'PHP_BIN_FOLDER','D:\\APP\\PHP_NTS\\','D:\\APP\\PHP_NTS\\');}
		if($parameterM->getId(getPluginId('core'),'PROCESS_LIFE_TIME')==0)	{$parameterM->create(getPluginId('core'),'PROCESS_LIFE_TIME','30','30');}
		if($parameterM->getId(getPluginId('core'),'PROCESS_NAME')==0)		{$parameterM->create(getPluginId('core'),'PROCESS_NAME','runner','runner');}
		if($parameterM->getId(getPluginId('core'),'PROCESS_POLLING')==0)	{$parameterM->create(getPluginId('core'),'PROCESS_POLLING','20000','20000');}
		if($parameterM->getId(getPluginId('core'),'PROCESS_GRACE_PERIODE')==0)	{$parameterM->create(getPluginId('core'),'PROCESS_GRACE_PERIODE','120','120');}
		if($parameterM->getId(getPluginId('core'),'UPLOAD_FOLDER')==0)		{$parameterM->create(getPluginId('core'),'UPLOAD_FOLDER','upload/','upload/');}
		if($parameterM->getId(getPluginId('core'),'MAX_PROCESS')==0)		{$parameterM->create(getPluginId('core'),'MAX_PROCESS','50','50');}
		if($parameterM->getId(getPluginId('core'),'COLLECTOR_POLLING')==0)	{$parameterM->create(getPluginId('core'),'COLLECTOR_POLLING','5','5');}
		if($parameterM->getId(getPluginId('core'),'UPLOAD_RUNNING_FILE_LIFE')==0)	{$parameterM->create(getPluginId('core'),'UPLOAD_RUNNING_FILE_LIFE','86400','86400');}
		if($parameterM->getId(getPluginId('core'),'UPLOAD_FAILED_FILE_LIFE')==0)	{$parameterM->create(getPluginId('core'),'UPLOAD_FAILED_FILE_LIFE','604800','604800');}
		if($parameterM->getId(getPluginId('core'),'UPLOAD_ARCHIVED_FILE_LIFE')==0)	{$parameterM->create(getPluginId('core'),'UPLOAD_ARCHIVED_FILE_LIFE','86400','86400');}
		if($parameterM->getId(getPluginId('core'),'DELETED_PROCESS_LIFE_TIME')==0)	{$parameterM->create(getPluginId('core'),'DELETED_PROCESS_LIFE_TIME','172800','172800');}
		
		if($parameterM->getId(getPluginId('core'),'FACEBOOK_APP_ID')==0)	{$parameterM->create(getPluginId('core'),'FACEBOOK_APP_ID','','');}
		if($parameterM->getId(getPluginId('core'),'FACEBOOK_APP_SECRET')==0)	{$parameterM->create(getPluginId('core'),'FACEBOOK_APP_SECRET','','');}
		if($parameterM->getId(getPluginId('core'),'GOOGLE_CLIENT_ID')==0)	{$parameterM->create(getPluginId('core'),'GOOGLE_CLIENT_ID','','');}
		if($parameterM->getId(getPluginId('core'),'GOOGLE_CLIENT_SECRET')==0)	{$parameterM->create(getPluginId('core'),'GOOGLE_CLIENT_SECRET','','');}
		if($parameterM->getId(getPluginId('core'),'GOOGLE_REDIRECT_URL')==0)	{$parameterM->create(getPluginId('core'),'GOOGLE_REDIRECT_URL','','http://www.xxxxxxxx.com/');}
		if($parameterM->getId(getPluginId('core'),'GOOGLE_APPLICATION_NAME')==0)	{$parameterM->create(getPluginId('core'),'GOOGLE_APPLICATION_NAME','','');}
		
		if($parameterM->getId(getPluginId('core'),'DEFAULT_AVATAR')==0)		{$parameterM->create(getPluginId('core'),'DEFAULT_AVATAR','lib/avatars/brain.jpg','lib/avatars/brain.jpg');}

		if($parameterM->getId(getPluginId('core'),'DEFAULT_HOME_PAGE')==0)	{$parameterM->create(getPluginId('core'),'DEFAULT_HOME_PAGE','all','all');}

		if($parameterM->getId(getPluginId('core'),'VERSION')==0)			{$parameterM->create(getPluginId('core'),'VERSION',$version,'');}

		if($parameterM->getId(getPluginId('core'),'LANG_fr_FR')==0)			{$parameterM->create(getPluginId('core'),'LANG_fr_FR','TOTAL','NONE');}
		if($parameterM->getId(getPluginId('core'),'LANG_en_US')==0)			{$parameterM->create(getPluginId('core'),'LANG_en_US','PARTIAL','NONE');}
		if($parameterM->getId(getPluginId('core'),'LANG_de_DE')==0)			{$parameterM->create(getPluginId('core'),'LANG_de_DE','NONE','NONE');}
		if($parameterM->getId(getPluginId('core'),'LANG_it_IT')==0)			{$parameterM->create(getPluginId('core'),'LANG_it_IT','NONE','NONE');}

		echo '<BR><BR><a href="setup.php?s=23"><span class="icon iconfa-stackoverflow"> Fill the auxiliary tables</a>';
		
	break;
	case 20:
		echo '
<h3>Step 3/6 - Fill the database</h3>
	    ';
	    
		if(is_file('plugins/core/setup/database.php')){
			include('plugins/core/setup/database.php');
		
			foreach($databaseArray['MYSQL']['create_frame'] as $sql) {
				try {
					$q0=get_link()->prepare(str_replace('<prefix>',get_ini('BDD_PREFIX'),$sql));
        	    	$q0->execute();
				} catch (Exception $e) {}
			}
		}
		
		echo '<BR><BR><a href="setup.php?s=22"><span class="icon iconfa-download"> Fill tables</a>';
		
	break;
	case 10:
		echo '
<form action="setup.php?s=12" method="post">
<h3>Step 2/6 - Databases</h3>

Type * <BR>
<select name="db_type"><option value="oracle">Oracle</option><option value="mysql">MySql</option></select><BR>
<BR>
Host * <BR>
<input name="db_host" type="text" placeholder="localhost" value=""><BR>
<BR>
Port * <BR>
<input name="db_port" type="text" placeholder="1521,3306,..." value=""><BR>
<BR>
Sid / Database name * <BR>
<input name="db_name" type="text" placeholder="myDbName" value=""><BR>
<BR>
Database prefix * <BR>
<input name="db_prefix" type="text" placeholder="gl_" value=""><BR>
<BR>
Username * <BR>
<input name="db_user" type="text" placeholder="myUserName" value=""><BR>
<BR>
Password * <BR>
<input name="db_pass" type="text" placeholder="myPassWord" value=""><BR>
<BR>
<input type="submit" value="Generate configuration file" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
</form>
	    ';
	break;
	case 12:
    	if(isset($_GET['db_type'])){$db_type=$_GET['db_type'];}elseif(isset($_POST['db_type'])){$db_type=$_POST['db_type'];}else{
    		// TODO ERROR
    		exit();
    	} 
    	if(isset($_GET['db_host'])){$db_host=$_GET['db_host'];}elseif(isset($_POST['db_host'])){$db_host=$_POST['db_host'];}else{
    		// TODO ERROR
    		exit();
    	} 
       	if(isset($_GET['db_port'])){$db_port=$_GET['db_port'];}elseif(isset($_POST['db_port'])){$db_port=$_POST['db_port'];}else{
    		// TODO ERROR
    		exit();
    	} 
       	if(isset($_GET['db_name'])){$db_name=$_GET['db_name'];}elseif(isset($_POST['db_name'])){$db_name=$_POST['db_name'];}else{
    		// TODO ERROR
    		exit();
    	} 
       	if(isset($_GET['db_prefix'])){$db_prefix=$_GET['db_prefix'];}elseif(isset($_POST['db_prefix'])){$db_prefix=$_POST['db_prefix'];}else{
    		// TODO ERROR
    		exit();
    	} 
       	if(isset($_GET['db_user'])){$db_user=$_GET['db_user'];}elseif(isset($_POST['db_user'])){$db_user=$_POST['db_user'];}else{
    		// TODO ERROR
    		exit();
    	} 
       	if(isset($_GET['db_pass'])){$db_pass=$_GET['db_pass'];}elseif(isset($_POST['db_pass'])){$db_pass=$_POST['db_pass'];}else{
    		// TODO ERROR
    		exit();
    	}
    	// Test if connexion is OK
    	echo '<h3>Database connexion</h3>';
		try {
			if(strtoupper($db_type)=='MYSQL'){
				$connexion = new PDO('mysql:host='.$db_host.';port='.$db_port.';dbname='.$db_name, $db_user, $db_pass);
				echo 'MySQL ok!';
			}
			elseif(strtoupper($db_type)=='ORACLE'){
				$connexion = new PDO('oci:dbname=//'.$db_host.':'.$db_port.'/'.$db_name.';charset=UTF8', $db_user, $db_pass);
				echo 'Oracle ok!';
			}
			$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(Exception $e)
		{
			echo 'Erreur : '.$e->getMessage().'<br />';
			echo 'N° : '.$e->getCode();
		
			exit();
		}

    	// Try to create the config file
    	echo '<h3>Configuration file creation</h3>';
    	$configContent='
[BDD]
BDD_TYPE="'.strtoupper($db_type).'"
BDD_HOTE="'.$db_host.'"
BDD_PORT="'.$db_port.'"
BDD_BASENAME="'.$db_name.'"
BDD_LOGIN="'.$db_user.'"
BDD_PASSWORD="'.$db_pass.'"
BDD_PREFIX="'.$db_prefix.'"

BDD_CRYPT_PASS="'.stringGenerate(48).'"

[PASSWORD]
PASSWORD_SALT="'.stringGenerate(8).'"
HASH_METHOD="sha512"
    	';
    
    	file_put_contents('conf/config.ini', $configContent);
	
    	if(is_file('conf/config.ini')) {
    		echo 'Configuration file created!<BR>';
    	} else {
    		echo 'Error in creating the configuration file. Check if "conf" folder exist and you are allowed to write in.<BR>';
    		exit();
    	}
    
    	echo '<BR><BR><a href="setup.php?s=20"><span class="icon iconfa-download"> Create database structure</a>';
		
	break;
	// Default case
    default:
		echo '
<h3>Step 1/6</h3>

Please follow instructions to complete the setup process. You can resume it at all moment within the last step.<BR><BR>

<a href="setup.php?s=10"><span class="icon iconfa-stackoverflow"> Database configuration </span></a>
		';
	break;
}

//if(!is_file('conf/config.ini'))
//{
//	include('plugins/core/setup.php');
//	exit();
//}
//else
//{
//	// Call functions file
//	require_once('plugins/core/__functions.php');
//}
?>


	</div> <!-- /content -->

	<div class="css-clear"></div>
	
	<div data-role="footer" id="ui-footer">
		<p>Julien SIMON / 2015</p>
		<p>Version <?php echo $version; ?></p>
		<p>Powered by GobelinsLab</p>
		<div class="css-clear"></div>
	</div><!-- /footer -->
	


</div><!-- /page -->

</body>
</html>

