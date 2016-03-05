<?php
switch ($a) {
    case 'update_form':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
        $param = new parameter($id); 
               
        echo '
<p>
	<div class="input-group">
		<span class="input-group-addon"><i class="icon iconastic-quote"></i></span>
		<input type="text" class="form-control" value="'.$param->name.'" DISABLED>
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon"><i class="icon iconastic-edit-write"></i></span>
		<input name="parameterValue" type="text" class="form-control" value="'.$param->parameterValue.'">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon"><i class="icon iconastic-ios-pricetags"></i></span>
		<input type="text" class="form-control" value="'.$param->defaultValue.'" DISABLED>
	</div>
</p>
<input name="id" type="hidden" value="'.$param->id.'"> 
<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_parameters&a=update\',$(\'form#popupForm\').serialize());">
Modifier
</button>
		';
    break;
    case 'update':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	} 
    	if(!isset($_POST['parameterValue'])){
        	// Todo error
        	echo 'erreur value';
        } else {
            $paramM = new parameterManager(); 
           
            $paramM->update($id,$_POST['parameterValue']);
            
            // TODO
            echo 'Le paramètre vient d\'être modifié!';
            
            echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
        }
    break;
    // Display the table content
    case 'jsonList':
    	$dataArray['data'] = array();
		$q0=get_link()->prepare("SELECT 
									param.id AS ID,
									param.id_plugin AS ID_PLUGIN,
									param.name AS NAME,
									pg.name AS PLUGINNAME,
									param.parameter_value AS PARAMETER_VALUE,
									param.default_value AS DEFAULT_VALUE,
									param.created_date AS CREATED_DATE,
									param.created_id AS CREATED_ID,
									param.edited_date AS EDITED_DATE,
									param.edited_id AS EDITED_ID,
									param.deleted_date AS DELETED_DATE,
									param.deleted_id AS DELETED_ID
								FROM 
								".get_ini('BDD_PREFIX')."core_parameters param ,
								".get_ini('BDD_PREFIX')."core_plugins pg
								WHERE 
								param.id_plugin=pg.id
								ORDER BY pg.name ASC,param.name ASC"); 
$q0->execute();
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{
	array_push(
		$dataArray['data'],
		array( 
			"ID" => $r0->ID ,
			"PLUGINNAME" => $r0->PLUGINNAME ,
			"NAME" => $r0->NAME ,
			"PARAMETER_VALUE" => $r0->PARAMETER_VALUE ,
			"DEFAULT_VALUE" => $r0->DEFAULT_VALUE ,
			"ACTION" => '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier le paramètre\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_parameters&a=update_form&id='.$r0->ID.'\');"><span class="iconastic-edit-write"> Modifier </span></a>'
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
		<h3 class="box-title">Liste des paramètres</h3>
	</div>
	<div class="box-body">
		<table id="dataTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Id</th>
					<th>Plugin</th>
					<th>Name</th>
					<th>Value</th>
					<th>Default</th>
					<th><a href="#" onClick="dataTable.ajax.reload();"><span class="iconastic-refresh"> Rafraichir</a> </th>
				</tr>
			</thead>
		</table>
	</div>
</div>
		'; //<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_parameters&a=create_form\');"><span class="iconfa-plus-square"> Ajouter</span></a>

echo '
<script>
var dataTable = 
$(\'#dataTable\').DataTable( {
    "ajax": "index.php?m=a&g=core&p=admin_parameters&a=jsonList",
    "columns": [
        { "data": "ID" },
        { "data": "PLUGINNAME" },
        { "data": "NAME" },
        { "data": "PARAMETER_VALUE" },
        { "data": "DEFAULT_VALUE" },
        { "data": "ACTION" }
    ]
} );
dataTable.order( [ 2, \'asc\' ] ).draw();
</script>
';
    break;
}

?>

