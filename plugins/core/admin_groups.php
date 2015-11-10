<?php
switch ($a) {
    case 'create_form':
        echo '
Entrez le nom du nouveau groupe:<BR>
<input name="name" type="text" value=""> <BR>
<input name="submit" value="envoyer" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_groups&a=create\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
		';
    break;
    case 'create':
        if(isset($_POST['name'])&&$_POST['name']!=''){
            $groupM = new groupManager(); 
           
            if($groupM->getId($_POST['name'])==0) {
                $groupM->create($_POST['name']);
                
                // TODO
                echo 'good!';
                
                echo '<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_groups&a=list\');</script>';
            } else {
            	// TODO
                echo 'Ce groupe existe déjà.';
            }
        }
    break;
    case 'update_form':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
        $group = new group($id); 
               
        echo '
Entrez le nouveau nom:<BR>
<input name="name" type="text" value="'.$group->name.'"> <BR>
<input name="id" type="hidden" value="'.$id.'">
<input name="submit" value="Modifier" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_groups&a=update\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
		';
    break;
    case 'update':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
        if(isset($_POST['name'])&&$_POST['name']!=''&&isset($id)){
			$groupM = new groupManager(); 
			
			if($groupM->getId($_POST['name'])==0) {
			    $groupM->update($id,$_POST['name']);
			    
			    // TODO confirmation
			    echo 'good!';
			    
			    echo '<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_groups&a=list\');</script>';
			} else {
				// TODO
			    echo 'Ce groupe existe déjà.';
			}
        }
    break;
    case 'delete_form':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
        $group = new group($id); 
               
        echo '
Etes vous sûr de vouloir supprimer l\'utilisateur '.$group->name.' ? <BR>
<input name="id" type="hidden" value="'.$id.'">
<input name="submit" value="Supprimer" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_groups&a=delete\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
		';
    break;
    case 'delete':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}

		$groupM = new groupManager(); 
			
		$groupM->delete($id);
		// TODO confirmation
		echo 'good!';
		
		echo '<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_groups&a=list\');</script>';
    break;
    case 'group_user_map':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
    	echo '<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=group_user_map_create_form&id='.$id.'\');"><span class="iconfa-plus-square"> Ajouter</span></a><BR>';
    	
		$q0=get_link()->prepare("
SELECT 	m.user_id AS USERID,
		m.group_id AS GROUPID,
		u.name AS NAME,
		u.avatar AS AVATAR, 
		m.id AS MAP_ID
FROM 
".get_ini('BDD_PREFIX')."core_users u,
".get_ini('BDD_PREFIX')."core_groups_users_map m
WHERE 
m.group_id=:group_id AND 
m.user_id=u.id AND
u.deleted_date=0 AND
m.deleted_date=0
ORDER BY u.name ASC"); //deleted_date=0 ORDER BY name
		$q0->execute(array('group_id' => $id));
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
			list($avatarWidth,$avatarHeight) = getNewSizePicture($r0->AVATAR,"48","48");
    		echo '
			<img src="'.$r0->AVATAR.'" width="'.$avatarWidth.'" height="'.$avatarHeight.'"> '.$r0->NAME.' <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=group_user_map_delete&id='.$r0->GROUPID.'&userId='.$r0->USERID.'\');"><span class="iconfa-trash-bin"></span></a><BR>
    		';
		}
		$q0->closeCursor();
    break;
    case 'group_user_map_delete':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['userId'])){$userId=$_GET['userId'];}elseif(isset($_POST['userId'])){$userId=$_POST['userId'];}else{
    		// TODO ERROR
    	}
		$groupM = new groupManager(); 
			
		$groupM->deleteGroupUserMap($id,$userId);
		
		echo '<script type="text/javascript">$( \'#popupContent\' ).load(\'index.php?m=a&g=core&p=admin_groups&a=group_user_map&id='.$id.'\');</script>';
    break;
    case 'group_user_map_create_form':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
		$q0=get_link()->prepare("
SELECT 	u.id AS ID,
		u.name AS NAME,
		u.avatar AS AVATAR
FROM 
".get_ini('BDD_PREFIX')."core_users u
WHERE 
u.deleted_date=0
ORDER BY u.name ASC");
		$q0->execute(array());
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
    		echo '
			<img src="'.$r0->AVATAR.'"> '.$r0->NAME.' <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=group_user_map_create&id='.$id.'&userId='.$r0->ID.'\');"><span class="iconfa-plus-square"></span></a><BR>
    		';
		}
		$q0->closeCursor();
    break;
    case 'group_user_map_create':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['userId'])){$userId=$_GET['userId'];}elseif(isset($_POST['userId'])){$userId=$_POST['userId'];}else{
    		// TODO ERROR
    	}
    	
        $groupM = new groupManager(); 
           
        if($groupM->getGroupUserMap($id,$userId)==0) {
            $groupM->addGroupUserMap($id,$userId);
                
            // TODO
            echo 'good!';
            
            echo '<script type="text/javascript">$( \'#popupContent\' ).load(\'index.php?m=a&g=core&p=admin_groups&a=group_user_map&id='.$id.'\');</script>';
        } else {
          	// TODO
            echo 'Ce groupe existe déjà.';
        }
    break;
    // Display the table content
    case 'list':
$q0=get_link()->prepare("SELECT id AS ID,name AS NAME FROM ".get_ini('BDD_PREFIX')."core_groups WHERE deleted_date=0 ORDER BY name ASC");
$q0->execute();
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{
    echo '
<tr>
    <td>'.$r0->ID.'</td>
    <td>'.$r0->NAME.'</td>
    <td>
        <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=group_user_map&id='.$r0->ID.'\');"><span class="iconfa-group"> Membres</span></a>
        ';
	if($r0->NAME != 'admins'){ // Admins group cannot be modified or deleted (use for plugins add)
		echo '
		<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=update_form&id='.$r0->ID.'\');"><span class="iconfa-edit-write"> Modifier</span></a>
        <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=delete_form&id='.$r0->ID.'\');"><span class="iconfa-minus-line"> Supprimer</span></a>
		';
	}
	echo '
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
    <th>Name</th>
    <th><a href="#" onClick="$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_groups&a=list\');"><span class="iconfa-refresh"> Rafraichir</a> <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=create_form\');"><span class="iconfa-plus-square"> Ajouter</span></a></th>
</tr>
</thead>
<tbody id="tableList">
<tr><td><img src="'.get_ini('LOADER').'"></td></tr>
</tbody>
</table>

<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_groups&a=list\');</script>
		';
    break;
}

?>



