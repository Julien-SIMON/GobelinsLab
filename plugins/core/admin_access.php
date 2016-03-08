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
<input name="secureLevel" type="text" value="0"> <BR>
<input name="targetId" type="hidden" value="'.$targetId.'">
<input name="sourceId" type="hidden" value="'.$sourceId.'">
<input name="submit" value="Modifier" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_access&a=create\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
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
			    
		echo '<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_access&a=list&targetId=\' + $(\'#targetAccessSelectInput\').val());</script>';
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
<input name="secureLevel" type="text" value="'.$access->secureLevel.'"> <BR>
<input name="targetId" type="hidden" value="'.$targetId.'">
<input name="sourceId" type="hidden" value="'.$sourceId.'">
<input name="submit" value="Modifier" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_access&a=update\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
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
			    
		echo '<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_access&a=list&targetId=\' + $(\'#targetAccessSelectInput\').val());</script>';
    break;
    // Display the table content
    case 'list':
    	if(isset($_GET['targetId'])){$targetId=$_GET['targetId'];}elseif(isset($_POST['targetId'])){$targetId=$_POST['targetId'];}else{
    		// TODO ERROR
    		exit();
    	}
    	
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
		    echo '
		<tr>
		    <td><span class="iconfa-group"> '.$r0->NAME.'</span></td>
		    <td>
		        <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_access&a=updateForm&targetId='.$targetId.'&sourceId='.$r0->SOURCEID.'\');">'.$r0->SECURELEVEL.'</a>
		    </td>
		</tr>
			';
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
		    echo '
		<tr>
		    <td><span class="iconfa-user"> '.$r0->NAME.'</span></td>
		    <td>
		        <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_access&a=updateForm&targetId='.$targetId.'&sourceId='.$r0->SOURCEID.'\');">'.$r0->SECURELEVEL.'</a>
		    </td>
		</tr>
			';
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
		    echo '
		<tr>
		    <td><span class="iconfa-group"> '.$r0->NAME.'</span></td>
		    <td>
		        <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_access&a=createForm&targetId='.$targetId.'&sourceId='.$r0->SOURCEID.'\');">0</a>
		    </td>
		</tr>
			';
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
		    echo '
		<tr>
		    <td><span class="iconfa-user"> '.$r0->NAME.'</span></td>
		    <td>
		        <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_access&a=createForm&targetId='.$targetId.'&sourceId='.$r0->SOURCEID.'\');">0</a>
		    </td>
		</tr>
			'; 
		}
		$q0->closeCursor();
    break;
    // Display Html table container and select input
    default:
    	echo '<select id="targetAccessSelectInput" name="targetId" onChange="$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_access&a=list&targetId=\' + $(this).val() );">';
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
		echo '</select>';
    	
    	
		echo '
<table class="pretty-table">
<thead>
<tr>
    <th>Object</th>
    <th><span class="iconfa-lock"></span></th>
</tr>
</thead>
<tbody id="tableList">
<tr><td><img src="'.get_ini('LOADER').'"></td></tr>
</tbody>
</table>

<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_access&a=list&targetId=\' + $(\'#targetAccessSelectInput\').val());</script>
		';
    break;
}
?>



