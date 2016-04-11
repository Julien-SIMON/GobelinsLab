<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

ini_set('session.gc_maxlifetime', 86400);
ini_set('session.gc_maxlifetime',84600);

date_default_timezone_set("Europe/Paris");

session_start();

if(!isset($p)){if(isset($_GET['p'])){$p=$_GET['p'];}elseif(isset($_POST['p'])){$p=$_POST['p'];}else{$p='';}} // page
if(!isset($g)){if(isset($_GET['g'])){$g=$_GET['g'];}elseif(isset($_POST['g'])){$g=$_POST['g'];}else{$g='core';}} // plugins
if(!isset($m)){if(isset($argv[0])){$m='t';$userM = new userManager();$_SESSION['USER_ID']=$userM->getIdByName('process');}elseif(isset($_GET['m'])){$m=$_GET['m'];}elseif(isset($_POST['m'])){$m=$_POST['m'];}else{$m='';}} // method ("a"=ajax, "t"=thread, "j"=job, ""=default)
if(!isset($a)){if(isset($_GET['a'])){$a=$_GET['a'];}elseif(isset($_POST['a'])){$a=$_POST['a'];}else{$a='';}} // action

// Call functions file
require_once('plugins/core/__functions.php');
$init = new initialisation();
$rijn = new rijn();

// Set USER_ID for anonymous connexion
if(!isset($_SESSION['USER_ID']))
{
	$userM = new userManager(); 
	$_SESSION['USER_ID']=$userM->getIdByName('#core#_#0#');
}

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

// If user env is not already defined
$user = new user($_SESSION['USER_ID']); 
$_SESSION['USER_NAME']=$user->name;


// On définit les variables php
//ini_set("SMTP", "smtp.squarebrain.eu:587");

// Set the timezone
date_default_timezone_set(get_ini('TIMEZONE'));

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

		//echo '<!-- Reload jquery theme -->';
		//echo '<script>$( \'#popup\' ).trigger(\'create\');</script>';	
		//echo '<!-- On repositionne le pop up en cas de chargement Ajax -->';
		//echo '<script>$( \'#popup\' ).popup( \'reposition\', \'positionTo: window\' );</script>';
	
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
    
	<!-- Library Jquery and Bootstrap -->
	<?php
	if (getBrowserAgentVersion() <= 8 ) {// IE ... , 7 and 8 case
		//echo '	<link rel="stylesheet"  href="lib/jqueryMobile/1.4.0/themes/default/jquery.mobile-1.4.0.min.css">'."\n";
		echo '	<script src="lib/jquery/jquery-1.11.3.min.js"></script>'."\n";
		echo '  <script type="text/javascript">$(document).bind("mobileinit", function () {$.mobile.ajaxEnabled = false;});</script> <!-- Desactive le data-ajax par défaut pour Jquery-mobile -->';
		//echo '	<script src="lib/jqueryMobile/1.4.0/jquery.mobile-1.4.0.min.js"></script>'."\n";
	} else { 
		//echo '	<link rel="stylesheet"  href="lib/jqueryMobile/1.4.5/jquery.mobile-1.4.5.min.css">'."\n";
		echo '	<script src="lib/jquery/jquery-2.1.4.min.js"></script>'."\n";
		echo '  <script type="text/javascript">$(document).bind("mobileinit", function () {$.mobile.ajaxEnabled = false;});</script> <!-- Desactive le data-ajax par défaut pour Jquery-mobile -->';
		//echo '	<script src="lib/jqueryMobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>'."\n";
	}
	?>
	
	<!-- Library awesome and ion fonts -->
	<link rel="stylesheet" href="lib/fontastics/PACK201602/fontastics.css">
	
	<!-- Library flags -->
	<link rel="stylesheet" href="lib/flags/libflags.css">
	
	<!-- Library bootstrap -->
	<link rel="stylesheet"  href="lib/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="lib/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	
	<!-- Library summerNote -->
	<link rel="stylesheet"  href="lib/summerNote/summernote.css">
	<script src="lib/summerNote/summernote.min.js"></script>
	
	<!-- Library adminLteTheme -->
	<link rel="stylesheet"  href="lib/adminLteTheme/css/AdminLTE.min.css">
	<link rel="stylesheet"  href="lib/adminLteTheme/css/skins/_all-skins.min.css">
	<script src="lib/adminLteTheme/js/app.min.js"></script>
	
	<!-- Library datatables -->
	<script src="lib/adminLteTheme/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="lib/adminLteTheme/plugins/datatables/dataTables.bootstrap.min.js"></script>
	
	<!-- Js et Css -->
	<link rel="stylesheet" href="css/_main.css">
	<script src="js/_main.js"></script>
	
	<script type="text/javascript">
function insertLoader(div) {
	$(div).html('<img src="<?php if(get_ini('LOADER')==''){echo get_ini('LOADER');}else{echo 'images/loader.gif';} ?>" alt="loader" height="47" width="48" style="magin: 25 25 25 25;">');
}

function setPopupTitle(content) {
	$('#popupTitle').html( content );
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
list($logoWidth,$logoHeight) = getNewSizePicture(get_ini('LOGO'),"32","16");
echo '@media (max-width: 60em) {#mainLogo {height: '.$logoHeight.'px;width: '.$logoWidth.'px;}}';
?>
	</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
	<header class="main-header">

		<!-- Logo -->
		<a href="index.php" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"><b>G</b>LAB</span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><b>Gobelins</b>LAB</span>
		</a>

		<!-- Header Navbar: style can be found in header.less -->
		<nav class="navbar navbar-static-top" role="navigation">

			<!-- Sidebar toggle button-->
			<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
			</a>
		
			<!-- Navbar Right Menu -->
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
				

					<!-- processus -->
<?php if($_SESSION['USER_NAME']!='#core#_#0#'){include_once('plugins/core/messages.php');} ?>
					<!-- end processus -->   

					<!-- events -->
<?php if($_SESSION['USER_NAME']!='#core#_#0#'){include_once('plugins/core/events.php');} ?>
					<!-- end events -->  

					<!-- processus -->
<?php if($_SESSION['USER_NAME']!='#core#_#0#'){include_once('plugins/core/processus.php');} ?>
					<!-- end processus -->   
    
					<!-- language selection -->
<?php include_once('plugins/core/languages_selection.php'); ?>
					<!-- end language selection -->
			
					<!-- login form -->
<?php include_once('plugins/core/login.php'); ?>
					<!-- end login form -->
					
					<!--
					<li>
						<a href="#" data-toggle="control-sidebar"><i class="icon iconastic-gears-setting"></i></a>
					</li>
					-->
				</ul>
			</div>
	
		</nav>
	</header>


	<!-- Left side column. contains the logo and sidebar -->
	<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
			<!-- Sidebar user panel -->
<?php require('./plugins/core/menu.php'); ?>
		</section>
		<!-- /.sidebar -->
	</aside>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
			...
			<small>Version <?php echo get_ini('VERSION'); ?></small>
			</h1>
			<ol class="breadcrumb">
				<li><i class="icon iconastic-sitemap"></i> <?php echo $g; ?></li>
				<li class="active"><?php echo $p; ?></li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php 
			if(get_ini('LANG_'.$_SESSION['LANG'])!='TOTAL'){
				echo '<div class="alert alert-warning" role="alert">';
				if(get_ini('LANG_'.$_SESSION['LANG'])=='PARTIAL'){echo _('#core#_#13#');}else{echo _('#core#_#12#');}
				echo '</div>';
			}
    		?>
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
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->

	<!-- footer -->
<?php include_once('plugins/core/footer.php'); ?>
	<!-- end footer -->

	<!-- Control Sidebar -->
	<aside class="control-sidebar control-sidebar-dark">
		<!-- settings tab -->
<?php include_once('plugins/core/settings.php'); ?>
		<!-- end settings tab -->
	</aside>
	<!-- /.control-sidebar -->
	<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
	<div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->


<!-- Modal -->
<div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 id="popupTitle" class="modal-title"></h4>
			</div>
			<form id="popupForm" onsubmit="return false;" autocomplete="off">
				<div id="popupContent" class="modal-body"></div>
			</form>
			<!--
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
			-->
		</div>
	</div>
</div>
<!-- end Modal -->


</body>
</html>

<?php
	break;
}
?>