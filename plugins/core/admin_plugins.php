<?php
// ------------------------------------------------------------------- //
// Add this statements to all you page. Secure at top level.
// ------------------------------------------------------------------- //
if(!secFile(__FILE__,100)){return;}
// ------------------------------------------------------------------- //



switch ($a) {
    case 'updateForm':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}

		$plugin = new plugin($id); 

		$plugin->updateActivated($value);
		
        $param = new parameter($id); 
               
        echo '
Parameter : <BR>
<input type="text" value="'.$param->name.'" DISABLED> <BR>
Value<BR>
<input name="parameterValue" type="text" value="'.$param->parameterValue.'"> <BR>
Default : <BR>
<input type="text" value="'.$param->defaultValue.'" DISABLED> <BR>
<input name="id" type="hidden" value="'.$param->id.'"> 
<input name="submit" value="Modifier" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_parameters&a=update\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
		';
    break;
/*
    case 'delete_form':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
        $param = new parameter($id); 
               
        echo '
Etes vous sûr de vouloir supprimer le paramêtre '.$param->name.' ? <BR>
<input name="id" type="hidden" value="'.$id.'">
<input name="submit" value="Supprimer" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_parameters&a=delete\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">
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
		
		echo '<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_parameters&a=list\');</script>';
    break;
*/
    case 'setupForm':
    	if(isset($_GET['name'])){$name=$_GET['name'];}elseif(isset($_POST['name'])){$name=$_POST['name'];}else{
    		// TODO ERROR
    		exit();
    	}
    	
    	if(is_file('plugins/'.$name.'/setup.php')) {include('plugins/'.$name.'/setup.php');}
    	
		$pluginM = new pluginManager();
		if($pluginM->getId($name)==0)					{
			$pluginM->create($name);
			$plugin = new plugin($pluginM->getId($name));
			$plugin->updateActivated(1);
		}
    break;
    case 'enableToggle':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	} 
    	if(isset($_GET['value'])){$value=$_GET['value'];}elseif(isset($_POST['value'])){$value=$_POST['value'];}else{
    		// TODO ERROR
    	}
		$plugin = new plugin($id); 

		$plugin->updateActivated($value);
    break;
    // Display the table content
    case 'setupJsonList':
    	$dataArray['data'] = array();
		$q0=get_link()->prepare("SELECT 
									g.id AS ID,
									g.name AS NAME,
									g.activated AS ACTIVATED,
									g.created_date AS CREATED_DATE,
									g.created_id AS CREATED_ID,
									g.edited_date AS EDITED_DATE,
									g.edited_id AS EDITED_ID,
									g.deleted_date AS DELETED_DATE,
									g.deleted_id AS DELETED_ID
								FROM 
								".get_ini('BDD_PREFIX')."core_plugins g
								WHERE 
								g.deleted_date=0
								ORDER BY g.name ASC"); 
		$q0->execute();
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
			array_push(
				$dataArray['data'],
				array( 
					"ID" => $r0->ID ,
					"NAME" => $r0->NAME ,
					"ENABLE" => $r0->ACTIVATED ,
					"ACTION" => '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier le plugin\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_plugins&a=updateForm&id='.$r0->ID.'\');"><span class="iconfa-edit-write"> Modifier</span></a>'
				)
			);
		}
		$q0->closeCursor();
		
		echo json_encode($dataArray);
    break;
    
    case 'setupList':
		$q0=get_link()->prepare("SELECT 
									g.id AS ID,
									g.name AS NAME,
									g.activated AS ACTIVATED,
									g.created_date AS CREATED_DATE,
									g.created_id AS CREATED_ID,
									g.edited_date AS EDITED_DATE,
									g.edited_id AS EDITED_ID,
									g.deleted_date AS DELETED_DATE,
									g.deleted_id AS DELETED_ID
								FROM 
								".get_ini('BDD_PREFIX')."core_plugins g
								WHERE 
								g.deleted_date=0
								ORDER BY g.name ASC"); 
$q0->execute();
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{
    echo '
<tr>
    <td>'.$r0->ID.'</td>
    <td>'.$r0->NAME.'</td>
    <td>
		<select id="selectActivatedFlipSwitch'.$r0->ID.'" class="flipswitch-select" data-role="slider" data-mini="true" onChange="$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_plugins&a=enableToggle&value=\' + $( this ).val() + \'&id='.$r0->ID.'\');"
		';
	if($r0->NAME=='core') {
		echo ' DISABLED>';
	} elseif($r0->ACTIVATED==1) {
		echo '
			>
			<option value="0">Off</option>
			<option value="1" selected>On</option>
			';
	} else {
		echo '
			>
			<option value="0" selected>Off</option>
			<option value="1">On</option>
			';
	}
	echo '
		</select>
	</td>
    <td><a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_plugins&a=updateForm&id='.$r0->ID.'\');"><span class="iconfa-edit-write"> Modifier</span></a></td>
</tr>

	';
}
$q0->closeCursor();
    break;
    case 'availableList':
if ($handle = opendir('plugins')) {
    $blacklist = array('.', '..');
	$pluginM = new pluginManager();
    while (false !== ($file = readdir($handle))) {
        if (!in_array($file, $blacklist)&&$pluginM->getId($file)==0&&!is_file('plugins/'.$file)) {
			echo '
		<tr>
			<td>'.$file.'</td>
			<td>
				<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_plugins&a=setupForm&name='.$file.'\');"><span class="iconfa-edit-write"> Setup</span></a>
			</td>
		</tr>
			';
		}
	}
closedir($handle);
}

{
//<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_parameters&a=delete_form&id='.$r0->ID.'\');"><span class="iconfa-minus-line"> Supprimer</span></a>
}
    break;
    // Display Html table container
    default: //<a href="#" onClick="$( \'#tableSetupList\' ).load(\'index.php?m=a&g=core&p=admin_plugins&a=setupList\');"><span class="iconfa-refresh"> Rafraichir</a>
	echo '
<div class="box">
	<div class="box-header">
		<h3 class="box-title">Liste des plugins installés</h3>
	</div>
	<div class="box-body">
		<table id="setupDataTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Id</th>
					<th>Nom</th>
					<th>Activé</th>
					<th><a href="#" onClick="setupDataTable.ajax.reload();"><span class="iconastic-refresh"> Rafraichir</a> </th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<script>
var setupDataTable = 
$(\'#setupDataTable\').DataTable( {
    "ajax": "index.php?m=a&g=core&p=admin_plugins&a=setupJsonList",
    "columns": [
        { "data": "ID" },
        { "data": "NAME" },
        { "data": "ENABLE" },
        { "data": "ACTION" }
    ]
} );
setupDataTable.order( [ 2, \'asc\' ] ).draw();
</script>
	

<BR><BR>

<table data-role="table" class="ui-responsive table-stroke">
	<thead>
		<tr>
			<th data-priority="1">Name</th>
			<th data-priority="1">Actions</th>
		</tr>
	</thead>
	<tbody id="tableAvailableList">
		<tr>
			<td>
				<img src="'.get_ini('LOADER').'">
			</td>
		</tr>
	</tbody>
</table>

<script type="text/javascript">$( \'#tableAvailableList\' ).load(\'index.php?m=a&g=core&p=admin_plugins&a=availableList\',function(data){$(".table-stroke").table("rebuild");$(".flipswitch-select").slider();});</script>
		'; //<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_parameters&a=create_form\');"><span class="iconfa-plus-square"> Ajouter</span></a>
    break; //<a href="#" onClick="$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_plugins&a=list\');"><span class="iconfa-refresh"> Rafraichir</a>
}


?>