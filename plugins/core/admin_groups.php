<?php
// ------------------------------------------------------------------- //
// Add this statements to all you page. Secure at top level.
// ------------------------------------------------------------------- //
if(!secFile(__FILE__,80)){return;}
// ------------------------------------------------------------------- //

switch ($a) {
    case 'create_form':
        echo '
<p>
	<div class="input-group">
		<span class="input-group-addon"><i class="icon iconastic-edit-write"></i></span>
		<input name="name" type="text" class="form-control" value=""  placeholder="Nom du groupe">
	</div>
</p>

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_groups&a=create\',$(\'form#popupForm\').serialize());">
Ajouter
</button>	
		';
    break;
    case 'create':
        if(isset($_POST['name'])&&$_POST['name']!=''){
            $groupM = new groupManager(); 
           
            if($groupM->getId($_POST['name'])==0) {
                $groupM->create($_POST['name']);
                
                // TODO
		    	echo 'Le groupe vient d\'être ajouté!';
            
            	echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
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
<p>
	<div class="input-group">
		<span class="input-group-addon"><i class="icon iconastic-edit-write"></i></span>
		<input name="name" type="text" class="form-control" value="'.$group->name.'"  placeholder="Nom du groupe">
	</div>
</p>
<input name="id" type="hidden" value="'.$id.'">

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_groups&a=update\',$(\'form#popupForm\').serialize());">
Modifier
</button>
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
		    	echo 'Le groupe vient d\'être modifié!';
            
            	echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
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
Etes vous sûr de vouloir supprimer le groupe '.$group->name.' ? <BR>
<input name="id" type="hidden" value="'.$id.'">
<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_groups&a=delete\',$(\'form#popupForm\').serialize());">
Supprimer
</button>
		';
    break;
    case 'delete':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}

		$groupM = new groupManager(); 
			
		$groupM->delete($id);
		// TODO confirmation
		echo 'Le groupe vient d\'être supprimé!';
        
        echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
    break;
    case 'group_user_map':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
    	echo '<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=group_user_map_create_form&id='.$id.'\');"><span class="iconastic-plus-square"> Ajouter</span></a><BR>';
    	
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
			<img src="'.$r0->AVATAR.'" width="'.$avatarWidth.'" height="'.$avatarHeight.'"> '.$r0->NAME.' <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=group_user_map_delete&id='.$r0->GROUPID.'&userId='.$r0->USERID.'\');"><span class="iconastic-trash-bin"></span></a><BR>
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
		
		echo 'Le membre vient d\'être supprimé!';
		
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
			list($avatarWidth,$avatarHeight) = getNewSizePicture($r0->AVATAR,"48","48");
    		echo '
			<img src="'.$r0->AVATAR.'" width="'.$avatarWidth.'" height="'.$avatarHeight.'"> '.$r0->NAME.' <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=group_user_map_create&id='.$id.'&userId='.$r0->ID.'\');"><span class="iconastic-plus-square"></span></a><BR>
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
            echo 'Le membre vient d\'être ajouté!';
            
            echo '<script type="text/javascript">$( \'#popupContent\' ).load(\'index.php?m=a&g=core&p=admin_groups&a=group_user_map&id='.$id.'\');</script>';
        } else {
          	// TODO
            echo 'Ce membre existe déjà.';
        }
    break;
    // Display the table content
    case 'jsonList':
    	$dataArray['data'] = array();
		$q0=get_link()->prepare("SELECT id AS ID,name AS NAME FROM ".get_ini('BDD_PREFIX')."core_groups WHERE deleted_date=0 ORDER BY name ASC"); 
		$q0->execute();
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
			array_push(
				$dataArray['data'],
				array( 
					"ID" => $r0->ID ,
					"NAME" => $r0->NAME ,
					"ACTION" => '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier le groupe\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=group_user_map&id='.$r0->ID.'\');"><span class="iconastic-group"> Membres </span></a> <a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier le groupe\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=update_form&id='.$r0->ID.'\');"><span class="iconastic-edit-write"> Modifier </span></a> <a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Supprimer le groupe\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=delete_form&id='.$r0->ID.'\');"><span class="iconastic-minus-line"> Supprimer</span></a>'
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
		<h3 class="box-title">Liste des groupes</h3>
		<a href="#popup" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Ajouter un groupe\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=create_form\');"><span class="iconastic-plus-square"> Ajouter</span></a>
	</div>
	<div class="box-body">
		<table id="dataTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Id</th>
					<th>Nom</th>
					<th><a href="#" onClick="dataTable.ajax.reload();"><span class="iconastic-refresh"> Rafraichir</a> </th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<script>
var dataTable = 
$(\'#dataTable\').DataTable( {
    "ajax": "index.php?m=a&g=core&p=admin_groups&a=jsonList",
    "columns": [
        { "data": "ID" },
        { "data": "NAME" },
        { "data": "ACTION" }
    ]
} );
dataTable.order( [ 2, \'asc\' ] ).draw();
</script>
		';
    break;
}

?>



