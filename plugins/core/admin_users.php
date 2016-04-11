<?php
// ------------------------------------------------------------------- //
// Add this statements to all you page. Adapt only the secLvl variable
// ------------------------------------------------------------------- //
//$secLvl=100;
//if(getAccess(get_object_id('core_plugins',getPluginId($g)))<$secLvl && getAccess(get_object_id('core_pages',getPageId(getPluginId($g),$p)))<$secLvl){include('plugins/core/403.php');exit(403);}
// ------------------------------------------------------------------- //



switch ($a) {
    case 'create_form':
        echo '
<p>
	<div class="input-group">
		<span class="input-group-addon">Email</span>
		<input name="mail" type="text" class="form-control" value=""  placeholder="xxxxxx@xxxx.xxx">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Pseudo</i></span>
		<input name="name" type="text" class="form-control" value=""  placeholder="Pseudo">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Nom</span>
		<input name="lastName" type="text" class="form-control" value=""  placeholder="Nom">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Prénom</span>
		<input name="firstName" type="text" class="form-control" value=""  placeholder="Prénom">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Avatar</span>
		<input name="avatar" type="text" class="form-control" value="'.get_ini('DEFAULT_AVATAR').'"  placeholder="Lien vers l\'avatar">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Envoi d\'un mail?</i></span>
		<select name="isSendMail" class="form-control" data-role="slider">
			<option value="FALSE">Off</option>
			<option value="TRUE" selected>On</option>
		</select>
	</div>
</p>

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_users&a=create\',$(\'form#popupForm\').serialize());">
Ajouter
</button>
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
        } elseif(preg_match('/^#.*#_#.*#$/',$_POST['name'])) {
        	// Todo error
        	echo 'erreur name match with translate pattern';
        } else {
            $userM = new userManager(); 
           
            if($userM->getId($_POST['mail'])==0 & strtolower($_POST['name'])!='guest' && $userM->getIdByName($_POST['name'])==0) {
            	$userM->register('LOCAL',$_POST['name'],'',$_POST['avatar'],$_POST['lastName'],$_POST['firstName'],strtolower($_POST['mail']),$_POST['isSendMail']);
                
                // TODO
            	echo 'L\'utilisateur vient d\'être ajouté!';
            
            	echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
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
<p>
	<div class="input-group">
		<span class="input-group-addon">Email</span>
		<input name="mail" type="text" class="form-control" value="'.$user->mail.'"  placeholder="xxxxxx@xxxx.xxx">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Pseudo</i></span>
		<input name="name" type="text" class="form-control" value="'.$user->name.'"  placeholder="Pseudo">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Avatar</span>
		<input name="avatar" type="text" class="form-control" value="'.$user->avatar.'"  placeholder="Lien vers l\'avatar">
	</div>
</p>
<input name="id" type="hidden" value="'.$user->id.'"> 

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_users&a=update\',$(\'form#popupForm\').serialize());">
Modifier
</button>
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
            	echo 'L\'utilisateur vient d\'être modifié!';
            
            	echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
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

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_users&a=delete\',$(\'form#popupForm\').serialize());">
Supprimer
</button>
		';
    break;
    case 'delete':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}

		$userM = new userManager(); 
			
		$userM->delete($id);
		// TODO confirmation
        echo 'L\'utilisateur vient d\'être supprimé!';
        
        echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
    break;
    // Display the table content
    case 'jsonList':
    	$dataArray['data'] = array();
		$q0=get_link()->prepare("SELECT id AS ID,mail AS MAIL, name AS DISPLAYNAME, avatar AS AVATAR FROM ".get_ini('BDD_PREFIX')."core_users WHERE deleted_date=0 ORDER BY name ASC");
		$q0->execute();
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
			list($avatarWidth,$avatarHeight) = getNewSizePicture($r0->AVATAR,"48","48");
			array_push(
				$dataArray['data'],
				array( 
					"ID" => $r0->ID ,
					"MAIL" => $r0->MAIL ,
					"DISPLAYNAME" => $r0->DISPLAYNAME ,
					"AVATAR" => '<img src="'.$r0->AVATAR.'" width="'.$avatarWidth.'" height="'.$avatarHeight.'">' ,
					"ACTION" => '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier un utilisateur\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_users&a=update_form&id='.$r0->ID.'\');"><span class="iconastic-edit-write"> Modifier </span></a> <a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Supprimer un utilisateur\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_users&a=delete_form&id='.$r0->ID.'\');"><span class="iconastic-minus-line"> Supprimer</span></a>'
				)
			);
		}
		$q0->closeCursor();

		echo json_encode($dataArray);
    break;    

    // Display Html table container
    default:
		echo '
<div class="box">
	<div class="box-header">
		<h3 class="box-title">Liste des utilisateurs</h3>
		<a href="#popup" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Ajouter un utilisateur\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_users&a=create_form\');"><span class="iconastic-plus-square"> Ajouter</span></a>
	</div>
	<div class="box-body">
		<table id="dataTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Id</th>
					<th>Mail</th>
					<th>Display name</th>
					<th>Avatar</th>
					<th><a href="#" onClick="dataTable.ajax.reload();"><span class="iconastic-refresh"> Rafraichir</a> </th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<script>
var dataTable = 
$(\'#dataTable\').DataTable( {
    "ajax": "index.php?m=a&g=core&p=admin_users&a=jsonList",
    "columns": [
        { "data": "ID" },
        { "data": "MAIL" },
        { "data": "DISPLAYNAME" },
        { "data": "AVATAR" },
        { "data": "ACTION" }
    ]
} );
dataTable.order( [ 2, \'asc\' ] ).draw();
</script>
		';
    break;
}

?>



