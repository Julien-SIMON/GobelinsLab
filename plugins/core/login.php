<?php
if($_SESSION['USER_ID']>0){
	switch ($a) {
	    case 'logout':
			unset($_SESSION);
			session_destroy();
			
			echo 'A bientôt!';
			
			echo '<script>location.reload();</script>';
	    break;
	    // Display connexion options
	    default:
	    	echo '
	<div class="block-content" style="text-align: center;">

	<a href="#" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=logout\');"><span class="icon iconfa-log-out"> Logout </span></a>
	
	</div>
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
	        		// TODO error
	        		echo 'error';
	        	}
			}
	    break;
	    case 'resetPassword':
	    	echo '
	Please enter your mail adress
	<input name="mail" type="text" placeholder="mail@domain.eu" value="">
	<ul data-role="listview" data-inset="true" data-icon="false"><li><a href="#" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=login&a=resetPasswordConfirm\',$(\'form#popupForm\').serialize());"><span class="icon iconfa-refresh"></span> Réinitialiser </a></li></ul>
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
	Mail * <BR>
	<input name="mail" type="text" placeholder="mail@domain.eu" value="'.$mail.'">
	Display name * <BR>
	<input name="name" type="text" placeholder="Gobelin123" value="'.$name.'"> <BR>
	
	<input name="submit" value="Créer" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=login&a=localRegister\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
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
	<div class="block-content" style="text-align: center;">
	<input name="login" type="text" value="'.get_ini('LDAP_DEFAULT_DOMAIN').'\">
	<input name="pass" type="password" placeholder="password">
	<a href="#" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=ldapResetPassword\');"><span class="icon iconfa-lightbulb" style="font-size: 0.7em;"> Mot de passe oublié ? </span></a>
	<ul data-role="listview" data-inset="true"><li><a href="#" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=login&a=ldapConnexion\',$(\'form#popupForm\').serialize());"><span class="icon iconfa-log-in"></span> Connexion </a></li></ul>
	<div style="height: 0.6em;"></div>
	<a href="#" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=ldapRegisterForm\');"><span class="icon iconfa-user" style="font-size: 1.1em;"> Créer un compte </span></a>
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
	Login * <BR>
	<input name="name" type="text" placeholder="'.get_ini('LDAP_DEFAULT_DOMAIN').'\mylogin" value="'.$name.'"> <BR>
	Password * <BR>
	<input name="password" type="password" placeholder="mypassword" value="'.$password.'"> <BR>
	Mail * <BR>
	<input name="mail" type="text" placeholder="mail@domain.eu" value="'.$mail.'">
	
	<input name="submit" value="Créer" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=login&a=ldapRegister\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
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
	    	if(get_ini('LOCAL_CONNEXION')=='true'){ // If local connexion is allowed
	    		echo '
	<div class="block-content" style="text-align: center;">
	<input name="login" type="text" placeholder="mail@domain.eu">
	<input name="pass" type="password" placeholder="password">
	<a href="#" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=resetPassword\');"><span class="icon iconfa-lightbulb" style="font-size: 0.7em;"> Mot de passe oublié ? </span></a>
	<ul data-role="listview" data-inset="true" data-icon="false"><li><a href="#" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=login&a=localConnexion\',$(\'form#popupForm\').serialize());"><span class="icon iconfa-log-in"></span> Connexion </a></li></ul>
	<div style="height: 0.6em;"></div>
	<a href="#" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=localRegisterForm\');"><span class="icon iconfa-user" style="font-size: 1.1em;"> Créer un compte </span></a>
	</div>
				';
	    	} elseif(get_ini('LDAP_CONNEXION')=='true') { // elseif ldap connexion is allowed
	    		$a='ldapConnexionForm';
				include('plugins/core/login.php');
	    	}
			
			if((get_ini('LOCAL_CONNEXION')=='true'||get_ini('LDAP_CONNEXION')=='true')
				&& (get_ini('FACEBOOK_CONNEXION')=='true' || get_ini('GOOGLE_CONNEXION')=='true' || get_ini('LDAP_CONNEXION')=='true')
				) {
				echo '<div style="text-align: center;">ou</div>';
			}
	    	
			if((get_ini('FACEBOOK_CONNEXION')=='true' || get_ini('GOOGLE_CONNEXION')=='true' || get_ini('LDAP_CONNEXION')=='true')) {
	    	// Other connexion options
				echo '
	<div class="block-content">
		<ul id="loginList" data-role="listview" data-inset="true" data-icon="false">
				';
				if(get_ini('FACEBOOK_CONNEXION')=='true')
					echo '<li><a href="index.php?auth_method_try=FACEBOOK" rel="external"><span class="icon iconfa-facebook-square"></span> Facebook</a></li>';
				if(get_ini('GOOGLE_CONNEXION')=='true')
					echo '<li><a href="index.php?auth_method_try=GOOGLE" rel="external"><span class="icon iconfa-google-plus-1"></span> Google</a></li>';
	    		//<li><a href="#"><span class="icon iconfa-github-square"></span> Git-Hub</a></li>
				if(get_ini('LDAP_CONNEXION')=='true')
					echo '<li><a href="#" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=login&a=ldapConnexionForm\');"><span class="icon iconfa-group"></span> Ldap</a></li>';
				echo '
		</ul>
	</div>
				';
			}
	    break;
	}
}
?>
