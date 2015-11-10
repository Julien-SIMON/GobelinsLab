<?php
switch ($a) {
    case 'update_form':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
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
    case 'update':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	} 
    	if(!isset($_POST['parameterValue'])||$_POST['parameterValue']==''){
        	// Todo error
        	echo 'erreur value';
        } else {
            $paramM = new parameterManager(); 
           
            $paramM->update($id,$_POST['parameterValue']);
            
            // TODO
            echo 'good!';
            
            echo '<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_parameters&a=list\');</script>';
        }
    break;
    // Display the table content
    case 'list':
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
    echo '
<tr>
    <td>'.$r0->ID.'</td>
    <td>'.$r0->PLUGINNAME.'</td>
    <td>'.$r0->NAME.'</td>
    <td>'.$r0->PARAMETER_VALUE.'</td>
    <td>'.$r0->DEFAULT_VALUE.'</td>
    <td>
        <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_parameters&a=update_form&id='.$r0->ID.'\');"><span class="iconfa-edit-write"> Modifier</span></a>
        
    </td>
</tr>
	';//<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_parameters&a=delete_form&id='.$r0->ID.'\');"><span class="iconfa-minus-line"> Supprimer</span></a>
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
    <th>Plugin</th>
    <th>Name</th>
    <th>Value</th>
    <th>Default</th>
    <th><a href="#" onClick="$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_parameters&a=list\');"><span class="iconfa-refresh"> Rafraichir</a> </th>
</tr>
</thead>
<tbody id="tableList">
<tr><td><img src="'.get_ini('LOADER').'"></td></tr>
</tbody>
</table>

<script type="text/javascript">$( \'#tableList\' ).load(\'index.php?m=a&g=core&p=admin_parameters&a=list\');</script>
		'; //<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_parameters&a=create_form\');"><span class="iconfa-plus-square"> Ajouter</span></a>
    break;
}

?>



