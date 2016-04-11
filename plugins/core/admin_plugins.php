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
    	
    	echo '
<p>
	<div class="input-group">
		<span class="input-group-addon">Activé</span>
		<select name="enable" class="form-control">
			<option value="1" ';if($plugin->activated=='1'){echo 'SELECTED';} echo '>On</option>
			<option value="0" ';if($plugin->activated=='0'){echo 'SELECTED';} echo '>Off</option>
		</select>
	</div>
</p>

<input type="hidden" name="id" value="'.$id.'">
<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_plugins&a=update\',$(\'form#popupForm\').serialize());">
Modifier
</button>
		';
    break;
    case 'update':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['enable'])){$enable=$_GET['enable'];}elseif(isset($_POST['enable'])){$enable=$_POST['enable'];}else{
    		// TODO ERROR
    	}

		$plugin = new plugin($id); 

		$plugin->updateActivated($enable);

        echo 'Le plugin vient d\'être modifié!';
        
        echo '<script type="text/javascript">setupDataTable.ajax.reload();</script>';
    break;
    case 'setupForm':
    	if(isset($_GET['name'])){$name=$_GET['name'];}elseif(isset($_POST['name'])){$name=$_POST['name'];}else{
    		// TODO ERROR
    		exit();
    	}
    	
    	if(is_file('plugins/'.$name.'/setup.php')) {include('plugins/'.$name.'/setup.php');}
    	if(!isset($pluginVersion)){$pluginVersion='1.0';}
		$pluginM = new pluginManager();
		if($pluginM->getId($name)==0)					{
			$pluginM->create($name,$pluginVersion);
			$plugin = new plugin($pluginM->getId($name));
			$plugin->updateActivated(1);
		}
		
        echo 'Le plugin vient d\'être ajoutée!';
        
        echo '<script type="text/javascript">setupDataTable.ajax.reload();availableDataTable.ajax.reload();</script>';
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
									g.version AS VERSION,
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
					"VERSION" => $r0->VERSION ,
					"ENABLE" => $r0->ACTIVATED ,
					"ACTION" => '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier le plugin\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_plugins&a=updateForm&id='.$r0->ID.'\');"><span class="iconfa-edit-write"> Modifier</span></a>'
				)
			);
		}
		$q0->closeCursor();
		
		echo json_encode($dataArray);
    break;
    case 'availableJsonList':
    	$dir='plugins';
    	$dataArray['data'] = array();
		if (is_dir($dir)&&$handle = opendir($dir)) {
		    $blacklist = array('.', '..');
			$pluginM = new pluginManager();
		    while ($file = readdir($handle)) {
		        if (!in_array($file, $blacklist)&&$pluginM->getId($file)==0&&!is_file($dir.'/'.$file)) {
					array_push(
						$dataArray['data'],
						array(
							"NAME" => $file ,
							"ACTION" => '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Ajouter le plugin\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_plugins&a=setupForm&name='.$file.'\');"><span class="iconfa-edit-write"> Setup</span></a>'
						)
					);
				}
			}
			closedir($handle);
		}
		
		echo json_encode($dataArray);
    break;
    
    // Display Html table container
    default: 
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
					<th>Version</th>
					<th>Activé</th>
					<th><a href="#" onClick="setupDataTable.ajax.reload();"><span class="iconastic-refresh"> Rafraichir</a> </th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="box">
	<div class="box-header">
		<h3 class="box-title">Liste des plugins disponible</h3>
	</div>
	<div class="box-body">
		<table id="availableDataTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Nom</th>
					<th><a href="#" onClick="availableDataTable.ajax.reload();"><span class="iconastic-refresh"> Rafraichir</a> </th>
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
        { "data": "VERSION" },
        { "data": "ENABLE" },
        { "data": "ACTION" }
    ]
} );
setupDataTable.order( [ 2, \'asc\' ] ).draw();

var availableDataTable = 
$(\'#availableDataTable\').DataTable( {
    "ajax": "index.php?m=a&g=core&p=admin_plugins&a=availableJsonList",
    "columns": [
        { "data": "NAME" },
        { "data": "ACTION" }
    ]
} );
availableDataTable.order( [ 1, \'asc\' ] ).draw();
</script>

		'; //<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_parameters&a=create_form\');"><span class="iconfa-plus-square"> Ajouter</span></a>
    break; //<a href="#" onClick="$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_plugins&a=list\');"><span class="iconfa-refresh"> Rafraichir</a>
}


?>