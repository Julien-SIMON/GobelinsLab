<?php
// ------------------------------------------------------------------- //
// Add this statements to all you page. Adapt only the secLvl variable
// ------------------------------------------------------------------- //
$secLvl=100;
if(getAccess(get_object_id('core_plugins',getPluginId($g)))<$secLvl && getAccess(get_object_id('core_pages',getPageId(getPluginId($g),$p)))<$secLvl){include('plugins/core/403.php');exit(403);}
// ------------------------------------------------------------------- //



switch ($a) {
    case 'create_form':
        echo '
Mail<BR>
<input name="mail" type="text" value=""> <BR>
Name<BR>
<input name="name" type="text" value=""> <BR>
Last name<BR>
<input name="lastName" type="text" value=""> <BR>
First name<BR>
<input name="firstName" type="text" value=""> <BR>
Avatar<BR>
<input name="avatar" type="text" value="'.get_ini('DEFAULT_AVATAR').'"> <BR>

<select name="isSendMail" data-role="slider">
	<option value="FALSE">Off</option>
	<option value="TRUE" selected>On</option>
</select> <BR>

<input name="submit" value="envoyer" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_users&a=create\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
		';
    break;
    case 'create':
        if(!isset($_POST['mail'])||$_POST['mail']==''||!mailCheck(strtolower($_POST['mail']))){
        	// Todo error
        	echo 'erreur mail';
        } elseif(!isset($_POST['name'])||$_POST['name']=='') {
        	// Todo error
        	echo 'erreur name';
        } elseif(!isset($_POST['avatar'])||$_POST['avatar']=='') {
        	// Todo error
        	echo 'erreur avatar';
        } elseif(!isset($_POST['firstName'])||$_POST['firstName']=='') {
        	// Todo error
        	echo 'erreur firstName';
        } elseif(!isset($_POST['lastName'])||$_POST['lastName']=='') {
        	// Todo error
        	echo 'erreur lastName';
        } elseif(!isset($_POST['isSendMail'])) {
        	// Todo error
        	echo 'erreur isSendMail';
        } else {
            $userM = new userManager(); 
           
            if($userM->getId($_POST['mail'])==0 & strtolower($_POST['name'])!='guest' && $userM->getIdByName($_POST['name'])==0) {
            	$userM->register('LOCAL',$_POST['name'],'',$_POST['avatar'],$_POST['lastName'],$_POST['firstName'],strtolower($_POST['mail']),$_POST['isSendMail']);
                
                // TODO
                echo 'good!';
                
                echo '<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_users&a=list\');</script>';
            } else {
            	// TODO
                echo 'Cet utilisateur ou cette adresse mail existe déjà dans notre base de données.';
            }
        }
    break;
    case 'update_form':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
        $user = new user($id); 
               
        echo '
Mail<BR>
<input name="mail" type="text" value="'.$user->mail.'"> <BR>
Nom<BR>
<input name="name" type="text" value="'.$user->name.'"> <BR>
Avatar<BR>
<input name="avatar" type="text" value="'.$user->avatar.'"> <BR>
<input name="id" type="hidden" value="'.$user->id.'"> 
<input name="submit" value="Modifier" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_users&a=update\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
		';
    break;
    case 'update':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	} 
    	if(!isset($_POST['mail'])||$_POST['mail']==''||!mailCheck($_POST['mail'])){
        	// Todo error
        	echo 'erreur mail';
        } elseif(!isset($_POST['name'])||$_POST['name']=='') {
        	// Todo error
        	echo 'erreur name';
        } elseif(!isset($_POST['avatar'])||$_POST['avatar']=='') {
        	// Todo error
        	echo 'erreur avatar';
        } else {
            $userM = new userManager(); 
           
            if($userM->getId($_POST['mail'])==0) {
                $userM->update($id,$_POST['name'],$_POST['avatar'],$_POST['mail']);
                
                // TODO
                echo 'good!';
                
                echo '<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_users&a=list\');</script>';
            } else {
            	// TODO
                echo 'Cet utilisateur existe déjà.';
            }
        }
    break;
    case 'delete_form':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
        $user = new user($id); 
               
        echo '
Etes vous sûr de vouloir supprimer l\'utilisateur '.$user->name.' ? <BR>
<input name="id" type="hidden" value="'.$id.'">
<input name="submit" value="Supprimer" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_users&a=delete\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
		';
    break;
    case 'delete':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}

		$userM = new userManager(); 
			
		$userM->delete($id);
		// TODO confirmation
		echo 'good!';
		
		echo '<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_users&a=list\');</script>';
    break;
    // Display the table content
    case 'list':
$q0=get_link()->prepare("SELECT id AS ID,mail AS MAIL, name AS DISPLAYNAME, avatar AS AVATAR FROM ".get_ini('BDD_PREFIX')."core_users WHERE deleted_date=0 ORDER BY name ASC");
$q0->execute();
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{
	list($avatarWidth,$avatarHeight) = getNewSizePicture($r0->AVATAR,"48","48");
    echo '
<tr>
    <td>'.$r0->ID.'</td>
    <td>'.$r0->MAIL.'</td>
    <td>'.$r0->DISPLAYNAME.'</td>
    <td><img src="'.$r0->AVATAR.'" width="'.$avatarWidth.'" height="'.$avatarHeight.'" alt="avatar"></td>
    <td>
        <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_users&a=update_form&id='.$r0->ID.'\');"><span class="iconfa-edit-write"> Modifier</span></a>
        <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_users&a=delete_form&id='.$r0->ID.'\');"><span class="iconfa-minus-line"> Supprimer</span></a>
    </td>
</tr>
	';
}
$q0->closeCursor();
    break;
    // Display Html table container
    default:
		echo '
<table class="pretty-table">
<thead>
<tr>
    <th>Id</th>
    <th>Mail</th>
    <th>Display name</th>
    <th>Avatar</th>
    <th><a href="#" onClick="$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_users&a=list\');"><span class="iconfa-refresh"> Rafraichir</a> <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_users&a=create_form\');"><span class="iconfa-plus-square"> Ajouter</span></a></th>
</tr>
</thead>
<tbody id="tableList">
<tr><td><img src="'.get_ini('LOADER').'"></td></tr>
</tbody>
</table>

<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_users&a=list\');</script>
		';
    break;
}

?>



