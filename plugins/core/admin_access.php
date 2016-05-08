<?php
// ------------------------------------------------------------------- //
// Add this statements to all you page. Secure at top level.
// ------------------------------------------------------------------- //
if(!secFile(__FILE__,100)){return;}
// ------------------------------------------------------------------- //

switch ($a) {
    case 'createForm':
    	if(isset($_GET['targetId'])){$targetId=$_GET['targetId'];}elseif(isset($_POST['targetId'])){$targetId=$_POST['targetId'];}else{
    		// TODO ERROR
    		exit();
    	}
    	if(isset($_GET['sourceId'])){$sourceId=$_GET['sourceId'];}elseif(isset($_POST['sourceId'])){$sourceId=$_POST['sourceId'];}else{
    		// TODO ERROR
    		exit();
    	}
    	
        $accessM = new accessManager();

        echo '
Entrez le niveau d\'accréditation pour ce nouvel accès: <BR>
<p>
	<div class="input-group">
		<span class="input-group-addon"><i class="icon iconastic-android-lock"></i></span>
		<input name="secureLevel" type="text" class="form-control" value=""  placeholder="0, 10, ..., 100">
	</div>
</p>
<input name="targetId" type="hidden" value="'.$targetId.'">
<input name="sourceId" type="hidden" value="'.$sourceId.'">

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_access&a=create\',$(\'form#popupForm\').serialize());">
Ajouter
</button>	
		';
    break;
    case 'create':
    	if(isset($_GET['targetId'])){$targetId=$_GET['targetId'];}elseif(isset($_POST['targetId'])){$targetId=$_POST['targetId'];}else{
    		// TODO ERROR
    		exit();
    	}
    	if(isset($_GET['sourceId'])){$sourceId=$_GET['sourceId'];}elseif(isset($_POST['sourceId'])){$sourceId=$_POST['sourceId'];}else{
    		// TODO ERROR
    		exit();
    	}
    	if(isset($_GET['secureLevel'])){$secureLevel=$_GET['secureLevel'];}elseif(isset($_POST['secureLevel'])){$secureLevel=$_POST['secureLevel'];}else{
    		// TODO ERROR
    		exit();
    	}
    	
    	$accessM = new accessManager();
    	
    	if($accessM->getId($sourceId,$targetId)>0){
    		// TODO ERROR
    		exit();
    	}
		
		if($secureLevel>0) {
			$accessM->create($sourceId,$targetId,$secureLevel);
		}
		
		echo 'good!';
			    
		echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
    break;
    case 'updateForm':
    	if(isset($_GET['targetId'])){$targetId=$_GET['targetId'];}elseif(isset($_POST['targetId'])){$targetId=$_POST['targetId'];}else{
    		// TODO ERROR
    		exit();
    	}
    	if(isset($_GET['sourceId'])){$sourceId=$_GET['sourceId'];}elseif(isset($_POST['sourceId'])){$sourceId=$_POST['sourceId'];}else{
    		// TODO ERROR
    		exit();
    	}
    	
        $accessM = new accessManager(); 
        $access = new access($accessM->getId($sourceId,$targetId)); 

        echo '
Entrez le nouveau niveau d\'accréditation pour cet accès: <BR>
<p>
	<div class="input-group">
		<span class="input-group-addon"><i class="icon iconastic-android-lock"></i></span>
		<input name="secureLevel" type="text" class="form-control" value="'.$access->secureLevel.'" placeholder="0, 10, ..., 100">
	</div>
</p>
<input name="targetId" type="hidden" value="'.$targetId.'">
<input name="sourceId" type="hidden" value="'.$sourceId.'">

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_access&a=update\',$(\'form#popupForm\').serialize());">
Modifier
</button>
		';
    break;
    case 'update':
    	if(isset($_GET['targetId'])){$targetId=$_GET['targetId'];}elseif(isset($_POST['targetId'])){$targetId=$_POST['targetId'];}else{
    		// TODO ERROR
    		exit();
    	}
    	if(isset($_GET['sourceId'])){$sourceId=$_GET['sourceId'];}elseif(isset($_POST['sourceId'])){$sourceId=$_POST['sourceId'];}else{
    		// TODO ERROR
    		exit();
    	}
    	if(isset($_GET['secureLevel'])){$secureLevel=$_GET['secureLevel'];}elseif(isset($_POST['secureLevel'])){$secureLevel=$_POST['secureLevel'];}else{
    		// TODO ERROR
    		exit();
    	}
    	
		$accessM = new accessManager(); 
			
		$accessM->delete($accessM->getId($sourceId,$targetId));
		
		if($secureLevel>0) {
			$accessM->create($sourceId,$targetId,$secureLevel);
		}
		
		echo 'good!';
			    
		echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
    break;
    case 'jsonList':
    	if(isset($_GET['targetId'])){$targetId=$_GET['targetId'];}elseif(isset($_POST['targetId'])){$targetId=$_POST['targetId'];}else{
    		// TODO ERROR
    		exit();
    	}
    	
    	$dataArray['data'] = array();
    	
    	// List groups and users with access
		$q0=get_link()->prepare("SELECT 
									o.id AS SOURCEID,
									a.secure_level AS SECURELEVEL,
									g.name AS NAME
								FROM 
									".get_ini('BDD_PREFIX')."core_groups g,
									".get_ini('BDD_PREFIX')."core_access a,
									".get_ini('BDD_PREFIX')."core_tables t,
									".get_ini('BDD_PREFIX')."core_objects o
								WHERE  
									t.name='core_groups' AND
									t.id = o.id_table AND
									o.id_ext = g.id AND
									a.id_target=:id_target AND
									a.id_source=o.id AND
									g.deleted_date=0 AND
									a.deleted_date=0 AND
									t.deleted_date=0 AND
									o.deleted_date=0
								ORDER BY 
									g.name ASC");
		$q0->execute(array( "id_target" => $targetId ));
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
			array_push(
				$dataArray['data'],
				array( 
					"NAME" => $r0->NAME ,
					"ACCESS" => '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier les accès\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_access&a=updateForm&targetId='.$targetId.'&sourceId='.$r0->SOURCEID.'\');">'.$r0->SECURELEVEL.'</a>' ,
					"ACTION" => ''  //<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier le groupe\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_access&a=createForm&targetId='.$targetId.'&sourceId='.$r0->SOURCEID.'\');"><span class="iconastic-group"> Membres </span></a> <a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier le groupe\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=update_form&id='.$r0->ID.'\');"><span class="iconastic-edit-write"> Modifier </span></a> <a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Supprimer le groupe\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_groups&a=delete_form&id='.$r0->ID.'\');"><span class="iconastic-minus-line"> Supprimer</span></a>
									//<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier les accès\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_access&a=updateForm&targetId='.$targetId.'&sourceId='.$r0->SOURCEID.'\');">'.$r0->SECURELEVEL.'</a>
				)
			);
		}
		$q0->closeCursor();
		
		$q0=get_link()->prepare("SELECT 
									o.id AS SOURCEID,
									a.secure_level AS SECURELEVEL,
									u.name AS NAME
								FROM 
									".get_ini('BDD_PREFIX')."core_users u,
									".get_ini('BDD_PREFIX')."core_access a,
									".get_ini('BDD_PREFIX')."core_tables t,
									".get_ini('BDD_PREFIX')."core_objects o
								WHERE  
									t.name='core_users' AND
									t.id = o.id_table AND
									o.id_ext = u.id AND
									a.id_target=:id_target AND
									a.id_source=o.id AND
									u.deleted_date=0 AND
									a.deleted_date=0 AND
									t.deleted_date=0 AND
									o.deleted_date=0
								ORDER BY 
									u.name ASC");
		$q0->execute(array( "id_target" => $targetId ));
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
			array_push(
				$dataArray['data'],
				array( 
					"NAME" => _($r0->NAME) ,
					"ACCESS" => '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier les accès\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_access&a=updateForm&targetId='.$targetId.'&sourceId='.$r0->SOURCEID.'\');">'.$r0->SECURELEVEL.'</a>' ,
					"ACTION" => ''
				)
			);
		}
		$q0->closeCursor();
		
		$q0=get_link()->prepare("SELECT 
									o.id AS SOURCEID,
									'0' AS SECURELEVEL,
									g.name AS NAME
								FROM 
									".get_ini('BDD_PREFIX')."core_groups g,
									".get_ini('BDD_PREFIX')."core_tables t,
									".get_ini('BDD_PREFIX')."core_objects o
								WHERE  
									t.name='core_groups' AND
									t.id = o.id_table AND
									o.id_ext = g.id AND
									o.id NOT IN (SELECT ID_SOURCE FROM ".get_ini('BDD_PREFIX')."core_access WHERE id_target=:id_target AND deleted_date=0) AND
									g.deleted_date=0 AND
									t.deleted_date=0 AND
									o.deleted_date=0
								ORDER BY 
									g.name ASC");
		$q0->execute(array( "id_target" => $targetId ));
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
			array_push(
				$dataArray['data'],
				array( 
					"NAME" => $r0->NAME ,
					"ACCESS" => '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier les accès\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_access&a=createForm&targetId='.$targetId.'&sourceId='.$r0->SOURCEID.'\');">'.$r0->SECURELEVEL.'</a>' ,
					"ACTION" => ''
				)
			);
		}
		$q0->closeCursor();
		
		$q0=get_link()->prepare("SELECT 
									o.id AS SOURCEID,
									'0' AS SECURELEVEL,
									u.name AS NAME
								FROM 
									".get_ini('BDD_PREFIX')."core_users u,
									".get_ini('BDD_PREFIX')."core_tables t,
									".get_ini('BDD_PREFIX')."core_objects o
								WHERE  
									t.name='core_users' AND
									t.id = o.id_table AND
									o.id_ext = u.id AND
									o.id NOT IN (SELECT ID_SOURCE FROM ".get_ini('BDD_PREFIX')."core_access WHERE id_target=:id_target AND deleted_date=0) AND
									u.deleted_date=0 AND
									t.deleted_date=0 AND
									o.deleted_date=0
								ORDER BY 
									u.name ASC");
		$q0->execute(array( "id_target" => $targetId ));
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
			array_push(
				$dataArray['data'],
				array( 
					"NAME" => _($r0->NAME) ,
					"ACCESS" => '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier les accès\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_access&a=createForm&targetId='.$targetId.'&sourceId='.$r0->SOURCEID.'\');">'.$r0->SECURELEVEL.'</a>' ,
					"ACTION" => ''
				)
			);
		}
		$q0->closeCursor();

		echo json_encode($dataArray);
    break;
    // Display Html table container and select input
    default:
		echo '
<div class="box">
	<div class="box-header">
		<h3 class="box-title">Liste des accès</h3>
	</div>
	<div class="box-body">

    	<p>
    		<select id="targetAccessSelectInput" name="targetId" class="form-control" onChange="dataTable.ajax.url(\'index.php?m=a&g=core&p=admin_access&a=jsonList&targetId=\' + $(this).val()).load();">';
		$firstTargetId=0;
		$q0=get_link()->prepare("SELECT 
									o.id AS ID,
									g.id AS PLUGINID,
									g.name AS NAME,
									count(a.id) AS ACCESSSUM
								FROM 
									".get_ini('BDD_PREFIX')."core_plugins g,
									".get_ini('BDD_PREFIX')."core_tables t,
									".get_ini('BDD_PREFIX')."core_objects o
									LEFT JOIN
										".get_ini('BDD_PREFIX')."core_access a
									ON 
										a.id_target=o.id AND 
										a.deleted_date=0
								WHERE 
									t.name='core_plugins' AND
									t.id=o.id_table AND
									o.id_ext=g.id AND
									g.deleted_date=0 AND
									t.deleted_date=0 AND
									o.deleted_date=0
								GROUP BY
									o.id, g.id, g.name
								ORDER BY 
									g.name ASC");
		$q0->execute();
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
		    echo '<option value="'.$r0->ID.'">Plugin : '.$r0->NAME.' ('.$r0->ACCESSSUM.')</option>';
		    if($firstTargetId==0){$firstTargetId=$r0->ID;}
			$q1=get_link()->prepare("SELECT 
										o.id AS ID,
										p.name AS NAME,
										count(a.id) AS ACCESSSUM
									FROM 
										".get_ini('BDD_PREFIX')."core_pages p,
										".get_ini('BDD_PREFIX')."core_tables t,
										".get_ini('BDD_PREFIX')."core_objects o
									LEFT JOIN
										".get_ini('BDD_PREFIX')."core_access a
									ON 
										a.id_target=o.id AND 
										a.deleted_date=0
									WHERE 
										t.name='core_pages' AND
										t.id=o.id_table AND
										p.plugin_id=:plugin_id AND
										o.id_ext=p.id AND
										p.deleted_date=0 AND
										t.deleted_date=0 AND
										o.deleted_date=0
									GROUP BY
										o.id, p.name
									ORDER BY 
										p.name ASC");
			$q1->execute(array( "plugin_id" => $r0->PLUGINID ));
			while( $r1 = $q1->fetch(PDO::FETCH_OBJ) )
			{
			    echo '<option value="'.$r1->ID.'"> - '.$r1->NAME.' ('.$r1->ACCESSSUM.')</option>';
			}
		}
		echo '
			</select>
		</p>
			
	
		<table id="dataTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Users/Groups</th>
					<th>Access</th>
					<th><a href="#" onClick="dataTable.ajax.reload();"><span class="iconastic-refresh"> Rafraichir</a> </th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<script>
var dataTable = 
$(\'#dataTable\').DataTable( {
    "ajax": "index.php?m=a&g=core&p=admin_access&a=jsonList&targetId='.$firstTargetId.'",
    "columns": [
        { "data": "NAME" },
        { "data": "ACCESS" },
        { "data": "ACTION" }
    ]
} );
dataTable.order( [ 1, \'asc\' ] ).draw();
</script>

		';
    break;
}
?>



