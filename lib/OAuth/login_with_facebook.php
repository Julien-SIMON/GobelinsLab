 <?php
/*
 * login_with_facebook.php
 */
	
    require('http.php');
    require('oauth_client.php');
	
	session_start();
	
    $client = new oauth_client_class;
    $client->server = 'Facebook';
    //$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].'/www';
    $client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
        '/lib/OAuth/login_with_facebook.php';

    $client->client_id = '984325931628417'; $application_line = __LINE__;
    $client->client_secret = 'e795dd0274fc644215429244751d1e3d';

    if(strlen($client->client_id) == 0
    || strlen($client->client_secret) == 0)
        die('Please go to Facebook Apps page https://developers.facebook.com/apps , '.
            'create an application, and in the line '.$application_line.
            ' set the client_id to App ID/API Key and client_secret with App Secret');

    /* API permissions
     */
    $client->scope = 'email';
    if(($success = $client->Initialize()))
    {
        if(($success = $client->Process()))
        {
            if(strlen($client->access_token))
            {
				$success = $client->CallAPI(
                  'https://graph.facebook.com/me?fields=email,last_name,first_name,picture.type(small)',
                  'GET', array(), array('FailOnAccessError'=>true), $_SESSION['auth_infos']);
					
                $_SESSION['auth_info_method']='FACEBOOK';
                $_SESSION['auth_info_login']=$_SESSION['auth_infos']->id;
                $_SESSION['auth_info_password']='';
                $_SESSION['auth_info_mail']=$_SESSION['auth_infos']->email;
                $_SESSION['auth_info_avatar_store']=$_SESSION['auth_infos']->picture->data->url;
                $_SESSION['auth_info_name']=$_SESSION['auth_infos']->first_name.' '.$_SESSION['auth_infos']->last_name;
                $_SESSION['auth_info_lastname']=$_SESSION['auth_infos']->last_name;
                $_SESSION['auth_info_firstname']=$_SESSION['auth_infos']->first_name;
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
<title></title>
<?php
echo '<meta http-equiv="refresh" content="1;URL=http://'.$_SERVER['HTTP_HOST'].'">';
?>
</head>
<body>
<?php
        echo ' you have logged in successfully with Facebook!';
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