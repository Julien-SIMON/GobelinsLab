<?php
$userM = new userManager(); 
if($_SESSION['USER_ID']>0&&$_SESSION['USER_ID']!=$userM->getIdByName('guest')){
	switch ($a) {
	    case 'logout':
			unset($_SESSION);
			session_destroy();
			
			echo 'A bientôt!';
			
			echo '<script>location.reload();</script>';
	    break;
	    // Display connexion options
	    default:
	    	$user = new user($_SESSION['USER_ID']);
	    
			echo '
<li class="dropdown user user-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<i class="icon iconastic-ios-contact-outline"></i>
		<span class="hidden-xs">'.$user->name.'</span>
	</a>
	<ul class="dropdown-menu">
		<li class="user-header">
			<img src="'.$user->avatar.'" style="background-color: white;" alt="User Image">
			
			<p>
			'.$user->name.' 
			<small>Member since '.date('M Y',$user->createdDate).'</small>
			</p>
		</li>
		<!-- Menu Footer-->
		<li class="user-footer">
			<div class="pull-left">
				<a href="index.php?g=core&p=profile" class="btn btn-default btn-flat">Profile</a>
			</div>
			<div class="pull-right">
				<a href="#" data-toggle="modal" data-target="#popup" class="btn btn-default btn-flat" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Logout\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=logout\');">Sign out</a>
			</div>
		</li>
	</ul>
	</li>
			';
	    break;
	}
}	
else
{
	switch ($a) {
	    case 'localConnexion':
			if(isset($_GET['login'])){$login=$_GET['login'];}elseif(isset($_POST['login'])){$login=$_POST['login'];}else{
				// TODO ERROR
			}
			if(isset($_GET['pass'])){$pass=$_GET['pass'];}elseif(isset($_POST['pass'])){$pass=$_POST['pass'];}else{
				// TODO ERROR
			}
			if($pass==''||$login=='') {
				// TODO ERROR
			}
			else
			{
	        	$userM = new userManager(); 
	        	
	        	$userId = $userM->signIn('LOCAL',$login,$pass);
	        	if($userId>0) {
	        		$_SESSION['USER_ID'] = $userId;
	        		
	        		echo 'Welcome!';
	        		
	        		echo '<script>location.reload();</script>';
	        	}
	        	else
	        	{
	        			//echo hashWithSalt('OVRACI@K');
	        		// TODO error
	        		echo 'error';
	        	}
			}
	    break;
	    case 'resetPassword':
	    	echo '
Please fill your mail adress
<p>
<div class="input-group">
	<span class="input-group-addon"><i class="icon iconastic-android-mail"></i></span>
	<input name="mail" type="email" class="form-control" placeholder="Email">
</div>
</p>
<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=login&a=resetPasswordConfirm\',$(\'form#popupForm\').serialize());">Reset</button>
	    	';
	    break;
	    case 'resetPasswordConfirm':
	        if(!isset($_POST['mail'])||$_POST['mail']==''||!mailCheck($_POST['mail'])){
	        	// Todo error
	        	echo 'erreur mail';
	        } else {
	        	$userM = new userManager();
				$user = new user($userM->getId($_POST['mail']));
				$user->updatePassword('','TRUE');
	        }
	    break;
	    case 'localRegisterForm':
			if(isset($_GET['mail'])){$mail=$_GET['mail'];}elseif(isset($_POST['mail'])){$mail=$_POST['mail'];}else{$mail='';}
			if(isset($_GET['name'])){$name=$_GET['name'];}elseif(isset($_POST['name'])){$name=$_POST['name'];}else{$name='';}
			
	    	echo '
<p>
	<div class="input-group">
		<span class="input-group-addon"><i class="icon iconastic-android-mail"></i></span>
		<input name="mail" type="email" class="form-control" placeholder="mail@domain.eu">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">@</span>
		<input name="name" type="text" class="form-control" placeholder="Display name: Gobelin123" value="'.$name.'">
	</div>
</p>
<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=login&a=localRegister\',$(\'form#popupForm\').serialize());">
Créer
</button>
	    	';
	    break;
	    case 'localRegister':
	        if(!isset($_POST['mail'])||$_POST['mail']==''||!mailCheck(strtolower($_POST['mail']))){
	        	echo 'Merci de compléter le champ "mail" avec une adresse valide.';
	        	
	        	echo '<div id="callBackErrorDiv"></div>';
	        	echo '<script type="text/javascript">$( \'#callBackErrorDiv\' ).load(\'index.php?m=a&g=core&p=login&a=localRegisterForm&mail='.$_POST['mail'].'&name='.$_POST['name'].'\');</script>';
	        } elseif(!isset($_POST['name'])||$_POST['name']==''){
	        	echo 'Veuillez remplir le champ "display name".';
	        	
	        	echo '<div id="callBackErrorDiv"></div>';
	        	echo '<script type="text/javascript">$( \'#callBackErrorDiv\' ).load(\'index.php?m=a&g=core&p=login&a=localRegisterForm&mail='.$_POST['mail'].'&name='.$_POST['name'].'\');</script>';
	        } else {
	            $userM = new userManager(); 
	           
	            if($userM->getId($_POST['mail'])>0) {
	            	echo 'Cette adresse mail est déjà utilisé par l\'un de nos compte. Veuillez utiliser l\'option "mot de passe oublié ?" ou choisir une autre adresse mail.';
	            } elseif($userM->getIdByName($_POST['name'])>0) {
					echo 'Cet identifiant est déjà utilisé par l\'un de nos utilisateurs. Veuillez en choisir un autre.';
	            } else {
	            	$userM->register('LOCAL',$_POST['name'],'',get_ini('DEFAULT_AVATAR'),'','',strtolower($_POST['mail']),'TRUE');
	                
	                echo 'Votre compte vient d\'être créé. Un email contenant votre mot de passe vous a été envoyé';
	            }
	        }
	    break;
	    case 'ldapConnexionForm':
			echo '
<form id="loginForm">
<p>
<div class="input-group">
	<span class="input-group-addon"><i class="icon iconastic-user"></i></span>
	<input name="login" type="text" class="form-control" value="'.get_ini('LDAP_DEFAULT_DOMAIN').'\">
</div>
</p>
<p>
<div class="input-group">
	<span class="input-group-addon"><i class="icon iconastic-lock-combination"></i></span>
	<input name="pass" type="password" class="form-control" placeholder="password">
	<span class="input-group-btn">
	<button type="button" class="btn btn-info btn-flat" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=login&a=ldapConnexion\',$(\'form#loginForm\').serialize());">Go!</button>
	</span>
</div>
</p>
</form>

<a href="#" data-toggle="modal" data-target="#popup" 
onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=ldapResetPassword\');">
<span class="icon iconastic-lightbulb" style="font-size: 0.7em;"> 
Mot de passe oublié ? 
</span>
</a>
<div class="user-header" style="height: 3.3em;">
<a href="#" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Ldap\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=ldapRegisterForm\');">
<span class="icon iconastic-user" style="font-size: 1.1em;"> 
Créer un compte 
</span>
</a>
</div>
';
	    break;
	    case 'ldapConnexion':
			if(isset($_GET['login'])){$login=$_GET['login'];}elseif(isset($_POST['login'])){$login=$_POST['login'];}else{
				// TODO ERROR
			}
			if(isset($_GET['pass'])){$pass=$_GET['pass'];}elseif(isset($_POST['pass'])){$pass=$_POST['pass'];}else{
				// TODO ERROR
			}
			if($pass==''||$login=='') {
				// TODO ERROR
			}
			else
			{
	        	$userM = new userManager(); 
	        	
	        	$userId = $userM->signIn('LDAP',$login,$pass);
	        	if($userId>0) {
	        		$_SESSION['USER_ID'] = $userId;
	        		
	        		echo 'Welcome!';
					
					echo '<script>location.reload();</script>';
	        	}
	        	else
	        	{
	        		// TODO error
	        		echo 'Ce compte n\'est pas référencé sur ce serveur. Veuillez créer un compte ou contacter votre administrateur.';
	        	}
			}
	    break;
	    case 'ldapResetPassword':
	    	echo '
The password is the same of your OS session. If you forget it, please call your desktop support.
	    	';
	    break;
	    case 'ldapRegisterForm':
			if(isset($_GET['mail'])){$mail=$_GET['mail'];}elseif(isset($_POST['mail'])){$mail=$_POST['mail'];}else{$mail='';}
			if(isset($_GET['name'])){$name=$_GET['name'];}elseif(isset($_POST['name'])){$name=$_POST['name'];}else{$name='';}
			if(isset($_GET['password'])){$password=$_GET['password'];}elseif(isset($_POST['password'])){$password=$_POST['password'];}else{$password='';}
			
	    	echo '
<p>
	<div class="input-group">
		<span class="input-group-addon"><i class="icon iconastic-user"></i></span>
		<input name="name" type="text" class="form-control" placeholder="'.get_ini('LDAP_DEFAULT_DOMAIN').'\mylogin" value="'.$name.'">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon"><i class="icon iconastic-lock-combination"></i></span>
		<input name="password" type="password" class="form-control" placeholder="mypassword" value="'.$password.'">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon"><i class="icon iconastic-android-mail"></i></span>
		<input name="mail" type="email" class="form-control" placeholder="mail@domain.eu" value="'.$mail.'">
	</div>
</p>
<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=login&a=ldapRegister\',$(\'form#popupForm\').serialize());">
Créer
</button> 
	    	';
	    break;
	    case 'ldapRegister':
			if(!$ad = ldap_connect(get_ini('LDAP_SERVERS'))){echo "Couldn't connect to ".get_ini('LDAP_SERVERS')."!";}
		
	        if(!isset($_POST['mail'])||$_POST['mail']==''||!mailCheck(strtolower($_POST['mail']))){
	        	echo 'Merci de compléter le champ "mail" avec une adresse valide.';
	        	
	        	echo '<div id="callBackErrorDiv"></div>';
	        	echo '<script type="text/javascript">$( \'#callBackErrorDiv\' ).load(\'index.php?m=a&g=core&p=login&a=ldapRegisterForm&mail='.$_POST['mail'].'&name='.$_POST['name'].'&password='.$_POST['password'].'\');</script>';
	        } elseif(!isset($_POST['name'])||$_POST['name']==''){
	        	echo 'Veuillez remplir le champ "login".';
	        	
	        	echo '<div id="callBackErrorDiv"></div>';
	        	echo '<script type="text/javascript">$( \'#callBackErrorDiv\' ).load(\'index.php?m=a&g=core&p=login&a=ldapRegisterForm&mail='.$_POST['mail'].'&name='.$_POST['name'].'&password='.$_POST['password'].'\');</script>';
			} elseif(!ldap_bind($ad,$_POST['name'],$_POST['password'])){
	        	echo 'Vos identifiants semblent incorrects.';
	        	
	        	echo '<div id="callBackErrorDiv"></div>';
	        	echo '<script type="text/javascript">$( \'#callBackErrorDiv\' ).load(\'index.php?m=a&g=core&p=login&a=ldapRegisterForm&mail='.$_POST['mail'].'&name='.$_POST['name'].'&password='.$_POST['password'].'\');</script>';
			} else {
	            $userM = new userManager(); 
	           
	            if($userM->getId($_POST['mail'])>0) {
	            	echo 'Cette adresse mail est déjà utilisé par l\'un de nos compte. Veuillez utiliser l\'option "mot de passe oublié ?" ou choisir une autre adresse mail.';
	            } elseif($userM->getIdByName($_POST['name'])>0) {
					echo 'Cet identifiant est déjà utilisé par l\'un de nos utilisateurs. Veuillez en choisir un autre.';
	            } else {
	            	$userM->register('LDAP',$_POST['name'],'',get_ini('DEFAULT_AVATAR'),'','',strtolower($_POST['mail']),'FALSE');
	                
	                echo 'Votre compte vient d\'être créé.';
	            }
	        }
	    break;
	    // Display connexion options
	    default:
			echo '
<li class="dropdown user user-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<i class="icon iconastic-ios-contact-outline"></i>
		<span class="hidden-xs">Sign in / Register</span>
	</a>
	<ul class="dropdown-menu">
			';
	    	if(get_ini('LOCAL_CONNEXION')=='true'){ // If local connexion is allowed
	    		echo '
<li class="user-body">
<form id="loginForm">
<p>
<div class="input-group">
	<span class="input-group-addon"><i class="icon iconastic-android-mail"></i></span>
	<input name="login" type="email" class="form-control" placeholder="Email">
</div>
</p>
<p>
<div class="input-group">
	<span class="input-group-addon"><i class="icon iconastic-lock-combination"></i></span>
	<input name="pass" type="password" class="form-control" placeholder="Password">
	<span class="input-group-btn">
	<button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#popup" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=login&a=localConnexion\',$(\'form#loginForm\').serialize());">Go!</button>
	</span>
</div>
</p>
</form>

<a href="#" data-toggle="modal" data-target="#popup" 
onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Reset\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=resetPassword\');">
<span class="icon iconastic-lightbulb" style="font-size: 0.7em;"> 
Mot de passe oublié ? 
</span>
</a>
</li>
<li class="user-header" style="height: 3.3em;">
<a href="#" data-toggle="modal" data-target="#popup"
onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Register\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=localRegisterForm\');">
<span class="icon iconastic-user" style="font-size: 1.1em;color: white;"> 
Créer un compte 
</span>
</a>
</li>

      
      
      

				';
	    	} elseif(get_ini('LDAP_CONNEXION')=='true') { // elseif ldap connexion is allowed
echo '
<li class="user-body">
<form id="loginForm">
<p>
<div class="input-group">
	<span class="input-group-addon"><i class="icon iconastic-user"></i></span>
	<input name="login" type="text" class="form-control" value="'.get_ini('LDAP_DEFAULT_DOMAIN').'\">
</div>
</p>
<p>
<div class="input-group">
	<span class="input-group-addon"><i class="icon iconastic-lock-combination"></i></span>
	<input name="pass" type="password" class="form-control" placeholder="password">
	<span class="input-group-btn">
	<button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#popup" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=login&a=ldapConnexion\',$(\'form#loginForm\').serialize());">Go!</button>
	</span>
</div>
</p>
</form>

<a href="#" data-toggle="modal" data-target="#popup" 
onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=ldapResetPassword\');">
<span class="icon iconastic-lightbulb" style="font-size: 0.7em;"> 
Mot de passe oublié ? 
</span>
</a>
</li>
<li class="user-header" style="height: 3.3em;">
<a href="#" data-toggle="modal" data-target="#popup"
onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Ldap\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=ldapRegisterForm\');">
<span class="icon iconastic-user" style="font-size: 1.1em;color: white;"> 
Créer un compte 
</span>
</a>
</li>
';
	    	}
			
			if((get_ini('LOCAL_CONNEXION')=='true'||get_ini('LDAP_CONNEXION')=='true')
				&& (get_ini('FACEBOOK_CONNEXION')=='true' || get_ini('GOOGLE_CONNEXION')=='true' || get_ini('LDAP_CONNEXION')=='true')
				) {
				echo '
				<li class="user-body">
					<div class="social-auth-links text-center">
    			<p>- OR -</p>
    			';
			}
	    	
			if((get_ini('FACEBOOK_CONNEXION')=='true' || get_ini('GOOGLE_CONNEXION')=='true' || get_ini('LDAP_CONNEXION')=='true')) {
	    	// Other connexion options
				if(get_ini('FACEBOOK_CONNEXION')=='true')
				{
					//echo '<li><a href="index.php?auth_method_try=FACEBOOK" rel="external"><span class="icon iconfa-facebook-square"></span> Facebook</a></li>';
					echo '<a href="index.php?auth_method_try=FACEBOOK" class="btn btn-block btn-social btn-facebook btn-flat btn-media-social"><i class="icon iconastic-facebook"></i> <span>Sign up using Facebook</span> </a>';
				}
				if(get_ini('GOOGLE_CONNEXION')=='true')
				{
					//echo '<li><a href="index.php?auth_method_try=GOOGLE" rel="external"><span class="icon iconfa-google-plus-1"></span> Google</a></li>';
					echo '<a href="index.php?auth_method_try=GOOGLE" class="btn btn-block btn-social btn-google btn-flat btn-media-social"><i class="icon iconastic-google-plus"></i> <span>Sign up using Google+</span> </a>';
				}
	    		//<li><a href="#"><span class="icon iconfa-github-square"></span> Git-Hub</a></li>
				if(get_ini('LDAP_CONNEXION')=='true')
				{
					//echo '<li><a href="#" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=ldapConnexionForm\');"><span class="icon iconfa-group"></span> Ldap</a></li>';
					echo '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Ldap\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=ldapConnexionForm\');" class="btn btn-block btn-social btn-instagram btn-flat btn-media-social"><i class="icon iconastic-group"></i> <span>Sign up using Ldap</span> </a>';
				}
			}
		echo '
		</li>
	</ul>
</li>
		';
	    break;
	}
}
?>
