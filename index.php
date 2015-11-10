<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

ini_set('session.gc_maxlifetime', 86400);

session_start();

// Set USER_ID for anonymous connexion
if(!isset($_SESSION['USER_ID'])){$_SESSION['USER_ID']=-1;}

// Call functions file
require_once('plugins/core/__functions.php');

// On ajoute les fichiers de connexion externe (Facebook, Google, ...)
// On positionne une variable de session en cas de demande de connexion OAuth
if(isset($_GET['auth_method_try']))
{
    switch ($_GET['auth_method_try']) {
        case 'FACEBOOK':
            $_SESSION['auth_method_try']='FACEBOOK';
        break;
        case 'GOOGLE':
            $_SESSION['auth_method_try']='GOOGLE';
        break;
    }
}

// On éxécute des procédures OAuth si la variable de session est positionner
if(isset($_SESSION['auth_method_try']))
{
    switch ($_SESSION['auth_method_try']) {
        case 'FACEBOOK':
            unset($_SESSION['auth_method_try']);
            require('lib/OAuth/login_with_facebook.php');
        break;
        case 'GOOGLE':
            unset($_SESSION['auth_method_try']);
            require('lib/OAuth/login_with_google.php');
        break;
    }
    unset($_SESSION['auth_method_try']);
}

// Si une authentification externe a eu lieu, appel des méthodes : register/signin 
if(isset($_SESSION['auth_info_method'])&&isset($_SESSION['auth_info_login'])&&$_SESSION['auth_info_login']>0) 
{
    $userM=new userManager();
		
	$userM->register($_SESSION['auth_info_method'],$_SESSION['auth_info_name'],$_SESSION['auth_info_login'],$_SESSION['auth_info_avatar_store'],$_SESSION['auth_info_lastname'],$_SESSION['auth_info_firstname'],$_SESSION['auth_info_mail']);

	$_SESSION['USER_ID'] = $userM->signIn($_SESSION['auth_info_method'],'',$_SESSION['auth_info_login']);
        
    unset($_SESSION['auth_info_method']);
    unset($_SESSION['OAUTH_ACCESS_TOKEN']);
    unset($_SESSION['auth_infos']);
}



// On définit les variables php
//ini_set("SMTP", "smtp.squarebrain.eu:587");

// Set the timezone
date_default_timezone_set(get_ini('TIMEZONE'));

if(!isset($p)){if(isset($_GET['p'])){$p=$_GET['p'];}elseif(isset($_POST['p'])){$p=$_POST['p'];}else{$p='';}} // page
if(!isset($g)){if(isset($_GET['g'])){$g=$_GET['g'];}elseif(isset($_POST['g'])){$g=$_POST['g'];}else{$g='core';}} // plugins
if(!isset($m)){if(isset($argv[0])){$m='t';$_SESSION['USER_ID']=0;}elseif(isset($_GET['m'])){$m=$_GET['m'];}elseif(isset($_POST['m'])){$m=$_POST['m'];}else{$m='';}} // method ("a"=ajax, "t"=thread, "j"=job, ""=default)
if(!isset($a)){if(isset($_GET['a'])){$a=$_GET['a'];}elseif(isset($_POST['a'])){$a=$_POST['a'];}else{$a='';}} // action

// Include plugin function file
if($g!='core'&&is_file('plugins/'.$g.'/__functions.php')){require('plugins/'.$g.'/__functions.php');}

switch ($m) {
	// Job call
	case 'j':
		include('plugins/core/task.php');
	break;
	// Thread call
	case 't':
		if(isset($argv[1])){$g=$argv[1];}
		if(isset($argv[2])){$p=$argv[2];}

		if(is_dir('plugins/'.$g)&&file_exists('plugins/'.$g.'/'.$p.'.php')){include('plugins/'.$g.'/'.$p.'.php');}
	break;
	// Ajax call
	case 'a':
    	if(is_dir('plugins/'.$g)&&file_exists('plugins/'.$g.'/'.$p.'.php')) {
	    	include('plugins/'.$g.'/'.$p.'.php');
    	} else {
			include('plugins/core/404.php');
    	}

		echo '<!-- Reload jquery theme -->';
		echo '<script>$( \'#popup\' ).trigger(\'create\');</script>';	
		echo '<!-- On repositionne le pop up en cas de chargement Ajax -->';
		echo '<script>$( \'#popup\' ).popup( \'reposition\', \'positionTo: window\' );</script>';
	
	break;
	// Default case
    default:
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" /> <!-- for IE in intranet -->
	
	<title><?php echo get_ini('APPLICATION_NAME').' - '.get_ini('VERSION'); ?></title>
	
	<!-- Favicon -->
	<link rel="shortcut icon" href="images/favicon.ico">
	
	<!-- Metadata -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    
	<!-- Librairie Jquery -->
	<?php
	if (getBrowserAgentVersion() <= 8 ) {// IE ... , 7 and 8 case
		echo '	<link rel="stylesheet"  href="lib/jqueryMobile/1.4.0/themes/default/jquery.mobile-1.4.0.min.css">'."\n";
		echo '	<script src="lib/jquery/jquery-1.11.3.min.js"></script>'."\n";
		echo '  <script type="text/javascript">$(document).bind("mobileinit", function () {$.mobile.ajaxEnabled = false;});</script> <!-- Desactive le data-ajax par défaut pour Jquery-mobile -->';
		echo '	<script src="lib/jqueryMobile/1.4.0/jquery.mobile-1.4.0.min.js"></script>'."\n";
	} else { 
		echo '	<link rel="stylesheet"  href="lib/jqueryMobile/1.4.5/jquery.mobile-1.4.5.min.css">'."\n";
		echo '	<script src="lib/jquery/jquery-2.1.4.min.js"></script>'."\n";
		echo '  <script type="text/javascript">$(document).bind("mobileinit", function () {$.mobile.ajaxEnabled = false;});</script> <!-- Desactive le data-ajax par défaut pour Jquery-mobile -->';
		echo '	<script src="lib/jqueryMobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>'."\n";
	}
	?>
	
	
	
	
	<!-- Librairie awesome fonts -->
	<link rel="stylesheet" href="lib/fontastics/PACK201509/font-awesome.css">
	<!-- <link rel="stylesheet" href="lib/fontastics/fontastics.css"> -->
	
	<!-- Js et Css -->
	<link rel="stylesheet" href="css/_main.css">
	<script src="js/_main.js"></script>
	
	<script type="text/javascript">
function insertLoader(div) {
	$(div).html('<img src="<?php echo get_ini('LOADER'); ?>" alt="loader" height="47" width="48" style="magin: 25 25 25 25;">');
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
	
	<style>
<?php
// Display the logo to the right height/width for mobile device
list($logoWidth,$logoHeight) = getNewSizePicture(get_ini('LOGO'),"128","64");
echo '@media (max-width: 60em) {#mainLogo {height: '.$logoHeight.'px;width: '.$logoWidth.'px;}}';
?>
	</style>
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
		<h2><img id="mainLogo" src="<?php echo get_ini('LOGO'); ?>" alt="SquareBrain"></h2>
		<!-- <p>v<?php echo get_ini('VERSION'); ?></p> -->
		<a href="#ui-navmenu" id="ui-navmenu-pannel-link" data-icon="bars" data-iconpos="notext" class="=ui-btn ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
		<a href="#popup" data-rel="popup" id="ui-infos-pannel-link" data-position-to="window" data-icon="user" data-iconpos="notext" class="=ui-btn ui-nodisc-icon ui-alt-icon ui-btn-right" onClick="insertLoader('#popupContent');$('#popupContent').load('index.php?m=a&g=core&p=login&step=1');">Infos</a>
	</div><!-- /header -->

	<div role="main" id="ui-content">

<?php
if($g==''||$p=='')
{
	include('plugins/core/home.php');
}
elseif(is_dir('plugins/'.$g)&&is_file('plugins/'.$g.'/'.$p.'.php'))
{
	include('plugins/'.$g.'/'.$p.'.php');
}
// Case for home display
else
{
	include('plugins/core/404.php');
}
?>
		
	</div> <!-- /content -->
	
	<!-- Panel container -->
	<div data-role="panel" id="ui-navmenu" class="" data-position="left" data-display="overlay" data-dismissible="true" data-theme="a">
		<?php require('./plugins/core/menu.php'); ?>
	</div> 

	<div class="css-clear"></div>
	
	<div data-role="footer" id="ui-footer">
		<p>Julien SIMON / 2015</p>
		<p>Version <?php echo get_ini('VERSION'); ?></p>
		<p>Powered by SquareBrain</p>
		<div class="css-clear"></div>
	</div><!-- /footer -->
	


</div><!-- /page -->

</body>
</html>

<?php
	break;
}
?>