<?php
/*
 * login_with_google.php
 */

	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->server = 'Google';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].'/lib/OAuth/login_with_google.php';//.dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_google.php';

	$client->client_id = '1081459850401-gk9r0cvles7a8tf5rgsolt5unsqer352.apps.googleusercontent.com'; $application_line = __LINE__;
	$client->client_secret = 'H5PJ1fm1Wd0sY7S2n3d7skq3';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Google APIs console page '.
			'http://code.google.com/apis/console in the API access tab, '.
			'create a new client ID, and in the line '.$application_line.
			' set the client_id to Client ID and client_secret with Client Secret. '.
			'The callback URL must be '.$client->redirect_uri.' but make sure '.
			'the domain is valid and can be resolved by a public DNS.');

	/* API permissions
	 */
	$client->scope = 'https://www.googleapis.com/auth/userinfo.email '.
		'https://www.googleapis.com/auth/userinfo.profile';
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->authorization_error))
			{
				$client->error = $client->authorization_error;
				$success = false;
			}
			elseif(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://www.googleapis.com/oauth2/v1/userinfo',
					'GET', array(), array('FailOnAccessError'=>true), $_SESSION['auth_infos']);
					
				$_SESSION['auth_info_method']='GOOGLE';
                $_SESSION['auth_info_login']=$_SESSION['auth_infos']->id;
                $_SESSION['auth_info_password']='';
                $_SESSION['auth_info_mail']=$_SESSION['auth_infos']->email;
                $_SESSION['auth_info_avatar_store']=$_SESSION['auth_infos']->picture;
                $_SESSION['auth_info_name']=$_SESSION['auth_infos']->given_name.' '.$_SESSION['auth_infos']->family_name;
                $_SESSION['auth_info_lastname']=$_SESSION['auth_infos']->family_name;
                $_SESSION['auth_info_firstname']=$_SESSION['auth_infos']->given_name;
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Google OAuth client results</title>
<?php
echo '<meta http-equiv="refresh" content="1;URL=http://'.$_SERVER['HTTP_HOST'].'">';
?>
</head>
<body>
<?php
	echo HtmlSpecialChars($_SESSION['auth_infos']->name),
		' you have logged in successfully with Google!',
		' You will be redirected in 1 seconds. If not, click on the following link : <a href="http://'.$_SERVER['HTTP_HOST'].'">http://'.$_SERVER['HTTP_HOST'].'</a>';
	//echo '<pre>', HtmlSpecialChars(print_r($_SESSION['auth_infos'], 1)), '</pre>';
?>
</body>
</html>
<?php
	}
	else
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OAuth client error</title>
</head>
<body>
<h1>OAuth client error</h1>
<pre>Error: <?php echo HtmlSpecialChars($client->error); ?></pre>
</body>
</html>
<?php
	}

?>