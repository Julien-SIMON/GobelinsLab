<?php
switch ($a) {
    case 'create_locale_form':
        echo '
<p>
	<div class="input-group">
		<span class="input-group-addon">Short name</span>
		<input name="shortName" type="text" class="form-control" value="" placeholder="fr_FR">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Long name</span>
		<input name="longName" type="text" class="form-control" value="" placeholder="Français">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Flag path</span>
		<input name="flagPath" type="text" class="form-control" value=""  placeholder="icon-libflags-fr">
	</div>
</p>

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_translation&a=create_locale\',$(\'form#popupForm\').serialize());">
Ajouter
</button>
		';
    break;
    case 'create_locale':
    	if(isset($_GET['shortName'])){$shortName=$_GET['shortName'];}elseif(isset($_POST['shortName'])){$shortName=$_POST['shortName'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['longName'])){$longName=$_GET['longName'];}elseif(isset($_POST['longName'])){$longName=$_POST['longName'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['flagPath'])){$flagPath=$_GET['flagPath'];}elseif(isset($_POST['flagPath'])){$flagPath=$_POST['flagPath'];}else{
    		// TODO ERROR
    	}

		$locM = new localeManager(); 
           
        if($locM->getId($shortName)==0) {
            $locM->create($shortName,$longName,$flagPath);
            
            // TODO
            echo 'La langue vient d\'être ajoutée!';
            
            echo '<script type="text/javascript">localeDataTable.ajax.reload();</script>';
        } else {
        	// TODO
            echo 'Cette langue existe déjà.';
        }
    break;
    case 'update_locale_form':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
        $loc = new local($id); 

		echo '
<p>
	<div class="input-group">
		<span class="input-group-addon">Short name</span>
		<input name="shortName" type="text" class="form-control" value="'.$loc->shortName.'" placeholder="fr_FR">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Long name</span>
		<input name="longName" type="text" class="form-control" value="'.$loc->longName.'" placeholder="Français">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Flag path</span>
		<input name="flagPath" type="text" class="form-control" value="'.$loc->flagPath.'"  placeholder="icon-libflags-fr">
	</div>
</p>
<input type="hidden" name="id" value="'.$id.'">

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_translation&a=update_locale\',$(\'form#popupForm\').serialize());">
Modifier
</button>		
		';
    break;
    case 'update_locale':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['shortName'])){$shortName=$_GET['shortName'];}elseif(isset($_POST['shortName'])){$shortName=$_POST['shortName'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['longName'])){$longName=$_GET['longName'];}elseif(isset($_POST['longName'])){$longName=$_POST['longName'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['flagPath'])){$flagPath=$_GET['flagPath'];}elseif(isset($_POST['flagPath'])){$flagPath=$_POST['flagPath'];}else{
    		// TODO ERROR
    	}
    	
        $locM = new localeManager();
		$loc = new local($id);
		
		if($locM->getId($shortName)==0 || $loc->shortName == $shortName) {
		    $locM->update($id,$shortName,$longName,$flagPath);
		    
		    // TODO confirmation
		    echo 'La langue vient d\'être modifiée!';
            
            echo '<script type="text/javascript">localeDataTable.ajax.reload();</script>';
        } else {
        	// TODO
            echo 'Cette langue existe déjà.';
		}
    break;
    case 'delete_locale_form':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
        $loc = new local($id); 
               
        echo '
Etes vous sûr de vouloir supprimer la langue '.$loc->shortName.' ? <BR>
<input name="id" type="hidden" value="'.$id.'">

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_translation&a=delete_locale\',$(\'form#popupForm\').serialize());">
Supprimer
</button>
		';
    break;
    case 'delete_locale':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}

		$locM = new localeManager(); 
			
		$locM->delete($id);
		// TODO confirmation
		echo 'La langue vient d\'être supprimée!';
        
        echo '<script type="text/javascript">localeDataTable.ajax.reload();</script>';
    break;
    
    case 'create_form':
        echo '
<p>
	<div class="input-group">
		<span class="input-group-addon">Plugin</span>
		<select name="plugin" class="form-control">
				';
				$q0=get_link()->prepare("SELECT 
											pg.id AS ID,
											pg.name AS NAME
										FROM 
										".get_ini('BDD_PREFIX')."core_plugins pg
										WHERE 
										pg.deleted_date = 0
										ORDER BY pg.name ASC"); 
				$q0->execute();
				while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
				{
					echo '<option value="'.$r0->ID.'">'.$r0->NAME.'</option>';
				}
				echo '
		</select>
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Langue</span>
		<select name="locale" class="form-control">
				';
				$q0=get_link()->prepare("SELECT 
											loc.id AS ID,
											loc.short_name AS SHORT_NAME,
											loc.long_name AS LONG_NAME,
											loc.created_date AS CREATED_DATE,
											loc.created_id AS CREATED_ID,
											loc.edited_date AS EDITED_DATE,
											loc.edited_id AS EDITED_ID,
											loc.deleted_date AS DELETED_DATE,
											loc.deleted_id AS DELETED_ID
										FROM 
										".get_ini('BDD_PREFIX')."core_locale loc
										WHERE 
										loc.deleted_date = 0
										ORDER BY loc.long_name ASC"); 
				$q0->execute();
				while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
				{
					echo '<option value="'.$r0->ID.'">'.$r0->LONG_NAME.'</option>';
				}
				echo '
		</select>
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Index</span>
		<input name="index_translation" type="text" class="form-control" value=""  placeholder="123">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Traduction</span>
		<textarea name="translation" class="form-control"></textarea>
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Commentaire</span>
		<textarea name="comment" class="form-control"></textarea>
	</div>
</p>

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_translation&a=create\',$(\'form#popupForm\').serialize());">
Ajouter
</button>
		';
    break;
    case 'create':
    	if(isset($_GET['plugin'])){$idPlugin=$_GET['plugin'];}elseif(isset($_POST['plugin'])){$idPlugin=$_POST['plugin'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['locale'])){$idLocale=$_GET['locale'];}elseif(isset($_POST['locale'])){$idLocale=$_POST['locale'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['index_translation'])){$indexTranslation=$_GET['index_translation'];}elseif(isset($_POST['index_translation'])){$indexTranslation=$_POST['index_translation'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['translation'])){$translation=$_GET['translation'];}elseif(isset($_POST['translation'])){$translation=$_POST['translation'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['comment'])){$comment=$_GET['comment'];}elseif(isset($_POST['comment'])){$comment=$_POST['comment'];}else{
    		// TODO ERROR
    	}

		$transM = new translationManager(); 
           
        if($transM->getId($idPlugin,$idLocale,$indexTranslation)==0) {
            $transM->create($idPlugin,$idLocale,$indexTranslation,$translation,$comment);
            
            // TODO
            echo 'La traduction vient d\'être ajoutée!';
            
            echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
        } else {
        	// TODO
            echo 'Cette traduction existe déjà.';
        }
    break;

    // Display the table content
    case 'localeJsonList':
    	$dataArray['data'] = array();
		$q0=get_link()->prepare("SELECT 
									loc.id AS ID,
									loc.short_name AS SHORT_NAME,
									loc.long_name AS LONG_NAME,
									loc.flag_path AS FLAG_PATH,
									loc.created_date AS CREATED_DATE,
									loc.created_id AS CREATED_ID,
									loc.edited_date AS EDITED_DATE,
									loc.edited_id AS EDITED_ID,
									loc.deleted_date AS DELETED_DATE,
									loc.deleted_id AS DELETED_ID
								FROM 
								".get_ini('BDD_PREFIX')."core_locale loc
								WHERE 
								loc.deleted_date=0
								ORDER BY loc.long_name ASC"); 
$q0->execute();
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{
	array_push(
		$dataArray['data'],
		array( 
			"ID" => $r0->ID ,
			"SHORTNAME" => $r0->SHORT_NAME ,
			"LONGNAME" => $r0->LONG_NAME ,
			"FLAGPATH" => $r0->FLAG_PATH ,
			"ACTION" => '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier la langue\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_translation&a=update_locale_form&id='.$r0->ID.'\');"><span class="iconastic-edit-write"> Modifier </span></a> <a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Supprimer la langue\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_translation&a=delete_locale_form&id='.$r0->ID.'\');"><span class="iconastic-minus-line"> Supprimer</span></a>'
		)
	);
}
$q0->closeCursor();

echo json_encode($dataArray);

    break;
    case 'jsonList':
    	$dataArray['data'] = array();
		$q0=get_link()->prepare("SELECT 
									trans.id AS ID,
									trans.id_plugin AS ID_PLUGIN,
									trans.id_locale AS ID_LOCALE,
									pg.name AS PLUGINNAME,
									loc.short_name AS SHORT_NAME,
									trans.index_translation AS INDEX_TRANSLATION,
									trans.translation AS TRANSLATION,
									trans.comment AS COMMENT,
									trans.created_date AS CREATED_DATE,
									trans.created_id AS CREATED_ID,
									trans.edited_date AS EDITED_DATE,
									trans.edited_id AS EDITED_ID,
									trans.deleted_date AS DELETED_DATE,
									trans.deleted_id AS DELETED_ID
								FROM 
								".get_ini('BDD_PREFIX')."core_translation trans ,
								".get_ini('BDD_PREFIX')."core_plugins pg ,
								".get_ini('BDD_PREFIX')."core_locale loc
								WHERE 
								trans.id_plugin=pg.id AND
								trans.id_locale=loc.id AND
								loc.deleted_date=0 AND
								pg.deleted_date=0 AND
								trans.deleted_date=0
								ORDER BY pg.name ASC,trans.index_translation ASC"); 
$q0->execute();
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{
	array_push(
		$dataArray['data'],
		array( 
			"ID" => $r0->ID ,
			"PLUGINNAME" => $r0->PLUGINNAME ,
			"SHORTNAME" => $r0->SHORT_NAME ,
			"INDEXTRANSLATION" => $r0->INDEX_TRANSLATION ,
			"TRANSLATION" => $r0->TRANSLATION ,
			"COMMENT" => $r0->COMMENT ,
			"ACTION" => ''
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
		<h3 class="box-title">Liste des languages</h3>
		<a href="#popup" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Ajouter une langue\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_translation&a=create_locale_form\');"><span class="iconastic-plus-square"> Ajouter</span></a>
	</div>
	<div class="box-body">
		<table id="localeDataTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Id</th>
					<th>Short</th>
					<th>Long</th>
					<th>Flag</th>
					<th><a href="#" onClick="localeDataTable.ajax.reload();"><span class="iconastic-refresh"> Rafraichir</a> </th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="box">
	<div class="box-header">
		<h3 class="box-title">Liste des traductions</h3>
		<a href="#popup" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Ajouter une traduction\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_translation&a=create_form\');"><span class="iconastic-plus-square"> Ajouter</span></a>
	</div>
	<div class="box-body">
		<table id="dataTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Id</th>
					<th>Plugin</th>
					<th>Locale</th>
					<th>Index</th>
					<th>Traduction</th>
					<th>Comment</th>
					<th><a href="#" onClick="dataTable.ajax.reload();"><span class="iconastic-refresh"> Rafraichir</a> </th>
				</tr>
			</thead>
		</table>
	</div>
</div>
		'; //<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_parameters&a=create_form\');"><span class="iconfa-plus-square"> Ajouter</span></a>

echo '
<script>
var localeDataTable = 
$(\'#localeDataTable\').DataTable( {
    "ajax": "index.php?m=a&g=core&p=admin_translation&a=localeJsonList",
    "columns": [
        { "data": "ID" },
        { "data": "SHORTNAME" },
        { "data": "LONGNAME" },
        { "data": "FLAGPATH" },
        { "data": "ACTION" }
    ]
} );

localeDataTable.order( [ 2, \'asc\' ] ).draw();


var dataTable = 
$(\'#dataTable\').DataTable( {
    "ajax": "index.php?m=a&g=core&p=admin_translation&a=jsonList",
    "columns": [
        { "data": "ID" },
        { "data": "PLUGINNAME" },
        { "data": "SHORTNAME" },
        { "data": "INDEXTRANSLATION" },
        { "data": "TRANSLATION" },
        { "data": "COMMENT" },
        { "data": "ACTION" }
    ]
} );
dataTable.order( [ 2, \'asc\' ] ).draw();
</script>
';
    break;
}

?>