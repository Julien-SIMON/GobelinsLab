<?php
switch ($a) {
	case 'dbInstanceCredsUpdate':
		if(isset($_GET['dbInstanceId'])){$dbInstanceId=$_GET['dbInstanceId'];}elseif(isset($_POST['dbInstanceId'])){$dbInstanceId=$_POST['dbInstanceId'];}else{
			// TODO
			exit(100);
		}
		if(isset($_GET['login'])){$login=$_GET['login'];}elseif(isset($_POST['login'])){$login=$_POST['login'];}else{$login='';}
		if(isset($_GET['password'])){$password=$_GET['password'];}elseif(isset($_POST['password'])){$password=$_POST['password'];}else{$password='';}
		
		$dbInstance = new dbInstance($dbInstanceId);
		
		$dbInstance->updateCredentials($login,$password,'','');
				
		// TODO - add result
		echo '<script>location.reload();</script>';
	break;
	case 'dbInstanceCredsForm':
		if(isset($_GET['dbInstanceId'])){$dbInstanceId=$_GET['dbInstanceId'];}elseif(isset($_POST['dbInstanceId'])){$dbInstanceId=$_POST['dbInstanceId'];}else{
			// TODO
			exit(100);
		}
		
		$dbInstance = new dbInstance($dbInstanceId);
		
		echo '<h3>Update '.$dbInstance->name.'</h3>
			Login: <input name="login" type="text" value="'.$dbInstance->creds['login'].'">
			Password: <input name="password" type="password" value="">
			';
		echo '<input name="dbInstanceId" type="hidden" value="'.$dbInstanceId.'">';
		echo '<input name="submit" value="Submit" onClick="popupFormSubmit(\'index.php?m=a&g=cmdb&p=devices&a=dbInstanceCredsUpdate\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">';
	break;
	case 'dbInstanceDesc':
		if(isset($_GET['dbInstanceId'])){$dbInstanceId=$_GET['dbInstanceId'];}elseif(isset($_POST['dbInstanceId'])){$dbInstanceId=$_POST['dbInstanceId'];}else{
			// TODO
			exit(100);
		}
		
		$dbInst = new dbInstance($dbInstanceId);
		
		echo '<h3> '.$dbInst->name.' </h3>';
		
		echo '
		<div class="ui-grid-b ui-responsive">
		';

//------------ About ------------
		echo '
			<div class="ui-block-a">
				<div class="ui-body ui-body-d block-content">
					<h3>About</h3>
		';
		
		foreach($dev->mdpeIdArray as $mapId){
			//echo '<a href="#">';
			echo '<span class="icon iconfa-tags"></span> Projet / Environement : '.$dev->mdpeArray[$mapId]['PROJECT'].' / '.$dev->mdpeArray[$mapId]['ENVIRONMENT'].'<BR>';
			//echo '</a>';
		}
		echo'
				</div>
			</div>
		';

//------------ ! about ----------

//------------ Comment ----------
		echo '
			<div class="ui-block-b">
				<div class="ui-body ui-body-d block-content">
					<h3>Commentaire</h3>
					<div id="deviceComments">'.nl2br($dev->comments).'</div>
					<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=cmdb&p=devices&a=commentForm&deviceId='.$deviceId.'\');"><span class="iconfa-edit-write"> Modifier</span></a>
				</div>
			</div>
		';


//------------ ! comment ----------


//------------ server -------------
if($dev->typeName == 'server') {
	$server = new server($dev->id);
	
	//------------ Hardware -----------
	echo '
			<div class="ui-block-a">
				<div class="ui-body ui-body-d block-content">
					<h3>Hardware</h3>
		';
	if(count($server->osIndex)>0) {
		foreach($server->osIndex as $osId) {
			$os = new os($osId);
			
			if($os->name != ''){
				echo '
						<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=cmdb&p=devices&a=cpuDesc&deviceId='.$deviceId.'\' );"><span class="icon iconfa-tachometer"></span> Cpu(s) : '.count($server->cpuIndex).'</a><BR>
						<a href="#"><span class="icon iconfa-stackoverflow"></span> Memory : '.round(($os->memorySize/1024/1024)).' Go</a><BR>
						<a href="#popupDetailedInfos" data-rel="popup" data-position-to="window" data-position-to="window" data-transition="pop" onClick="insertLoader(\'#popupDetailedInfosContent\');$( \'#popupDetailedInfosContent\' ).load( \'index.php?m=a&g=cmdb&p=devices_infos_detailed&device_id='.$server->id.'&infos=disks\' );"><span class="icon iconfa-harddrive"></span> Disk(s) : '.count($server->diskIndex).'</a><BR>
						<a href="#popupDetailedInfos" data-rel="popup" data-position-to="window" data-position-to="window" data-transition="pop" onClick="insertLoader(\'#popupDetailedInfosContent\');$( \'#popupDetailedInfosContent\' ).load( \'index.php?m=a&g=cmdb&p=devices_infos_detailed&device_id='.$server->id.'&infos=partitions\' );"><span class="icon iconfa-folder"></span> Partition(s) : '.count($server->fsIndex).'</a><BR>
					';
			}
		}
	} 
	echo '
				</div>		
			</div>
		';
	//------------ ! Hardware ---------
	//------------ Os -----------------
	echo '
			<div class="ui-block-b">
				<div class="ui-body ui-body-d block-content">
					<h3>Os</h3>
		';
	if(count($server->osIndex)>0) {
		foreach($server->osIndex as $osId) {
			$os = new os($osId);
			
			if($os->type=='linux') {$icon='iconfa-linux';} elseif($os->type=='windows') {$icon='iconfa-windows';} else {$icon='iconfa-close-delete';}
			echo '<a href="#"><span class="icon '.$icon.'"></span> '.$os->type.'</a><BR>';
			
			if($os->name == ''){
				echo '<div class="infoKo">The pull process seems to be broken. Check connectivity and <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=cmdb&p=devices&a=osCredsForm&deviceId='.$deviceId.'&osId='.$osId.'\' );">configure</a> credentials.</div>';
			} else {			
				echo '
						<a href="#"><span class="icon iconfa-building"></span> '.$os->architecture.'</a><BR>
						<a href="#"><span class="icon iconfa-tags"></span> Nom complet : '.$os->name.'</a><BR>
						<a href="#"><span class="icon iconfa-certificate"></span> Version : '.$os->version.'</a><BR>
						<a href="#"><span class="icon iconfa-repeat-redo"></span> Last reboot : '.date('d-m-Y H:i:s',$os->lastBootDate).'</a><BR>
					';
				if($os->type=='windows'){echo '<a href="#"><span class="icon iconfa-download"></span> Mise en service : '.date('d-m-Y H:i:s',$os->installDate).'</a><BR>';}
			}
		}
	} else {
		echo '<div class="info">There is no data available for this object. Please <a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=cmdb&p=devices&a=osCreateForm&deviceId='.$deviceId.'\' );">configure</a> the pull process.</div>';
	}
	echo '
				</div>		
			</div>
		';
	//------------ ! Os ---------------
	//------------ Databases --------------
	echo '
		<div class="ui-block-c">
			<div class="ui-body ui-body-d block-content">
				<h3>Databases</h3>
		';

		
	if(count($server->osIndex)>0) {
		foreach($server->osIndex as $osId) {
			$os = new os($osId);
			
			echo '<a href="#popup" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=cmdb&p=devices&a=dbInstanceCreateForm&osId='.$os->id.'\' );" data-rel="popup" data-position-to="window" class="ui-btn ui-shadow"><span class="icon iconfa-plus-square"></span> Ajouter</a>';
			foreach($server->dbInstanceIndex as $dbInstance){ 
				echo '
				<a href="index.php?g=cmdb&p=device_databases&device_id="><span class="icon iconfa-qrcode"></span> '.$server->dbInstanceArray[$dbInstance]['NAME'].' ('.$server->dbInstanceArray[$dbInstance]['STATUS'].')</a> 
				<a href="#popup" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=cmdb&p=devices&a=dbInstanceUpdateForm&dbInstanceId='.$server->dbInstanceArray[$dbInstance]['ID'].'\' );" data-rel="popup" data-position-to="window"><span class="icon iconfa-edit-write"></span></a>
				<a href="#popup" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=cmdb&p=devices&a=dbInstanceCredsForm&dbInstanceId='.$server->dbInstanceArray[$dbInstance]['ID'].'\' );" data-rel="popup" data-position-to="window"><span class="icon iconfa-lock"></span></a>
				<a href="#popup" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=cmdb&p=devices&a=dbInstanceDeleteForm&dbInstanceId='.$server->dbInstanceArray[$dbInstance]['ID'].'\' );" data-rel="popup" data-position-to="window"><span class="icon iconfa-trash-bin"></span></a><BR>
				<a href="index.php?g=cmdb&p=device_databases&device_id="><span class="icon iconfa-rocket"></span> '.$server->dbInstanceArray[$dbInstance]['TYPE'].'</a><BR>
				<a href="index.php?g=cmdb&p=device_databases&device_id="><span class="icon iconfa-random"></span> Port : '.$server->dbInstanceArray[$dbInstance]['PORT'].'</a><BR>
				<a href="index.php?g=cmdb&p=device_databases&device_id="><span class="icon iconfa-certificate"></span> Version : '.$server->dbInstanceArray[$dbInstance]['VERSION'].'</a><BR>
				';
			}
		//foreach($server->databaseArray[$dbInstanceArray]['DATABASE_INDEX'] AS $databaseName){echo '<a href="#"><span class="icon iconfa-barcode"></span> DatabaseName : '.$databaseName.'</a><BR>';}
		}
	}
			
	echo '
			</div>
		</div>
	';
//echo '	
//	<div data-role="popup" id="popupAddDatabaseForm" data-theme="a" data-overlay-theme="b" class="ui-corner-all">
//        <div style="padding:10px 20px;">
//            <h3>Ajouter une instance</h3>
//			Name: <input type="text" name="name" id="nameDatabaseForm" value="">
//			Type: <select name="type" id="typeDatabaseForm"><option value="ORACLE">Oracle</option><option value="MSSQLSERVER">MsSqlServer</option></select>
//			<BR>
//			<BR>
//            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check" onClick="insertLoader(\'#deviceComments\');databaseAdd();">Enregistrer</a>
//        </div>
//	</div>
//
//	';
//------------ ! Databases ------------

}

		echo '
		</div>
		';
	break;
	default:
		$dbInstanceIndex = array();
		$dbInstanceArray = array();
		
		$q0=get_link()->prepare('
		SELECT
			d.id AS ID,
			d.id_parent AS ID_PARENT, 
			d.name AS DEVNAME,
			p.name AS PRONAME,
			e.name AS ENVNAME,
			i.id AS DBID,
			i.db_name AS DBNAME,
			i.db_type AS DBTYPE
		FROM 
			'.get_ini('BDD_PREFIX').'cmdb_devices d,
			'.get_ini('BDD_PREFIX').'cmdb_environments e,
			'.get_ini('BDD_PREFIX').'cmdb_projects p,
			'.get_ini('BDD_PREFIX').'cmdb_dev_pro_env_map m,
			'.get_ini('BDD_PREFIX').'cmdb_dev_os o,
			'.get_ini('BDD_PREFIX').'cmdb_db_instances i
		WHERE
			d.id = m.id_device AND
			p.id = m.id_project AND
			e.id = m.id_environment AND
			o.id_device = d.id AND
			i.id_os = o.id AND
			p.deleted_date=0 AND
			e.deleted_date=0 AND
			m.deleted_date=0 AND
			d.deleted_date=0 AND
			o.deleted_date=0 AND
			i.deleted_date=0
		ORDER BY d.name ASC, i.db_name ASC, e.name ASC, p.name ASC
								'); 
		$q0->execute();
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{	
			if(!in_array($r0->DBID,$dbInstanceIndex)){
				array_push($dbInstanceIndex,$r0->DBID);
				$dbInstanceArray[$r0->DBID]['NAME']=$r0->DEVNAME.'\\'.$r0->DBNAME;
				
				$dbInstanceArray[$r0->DBID]['LINE']=$r0->DEVNAME.'\\'.$r0->DBNAME.' - ';
			}
			
			if($r0->PRONAME != ''){$dbInstanceArray[$r0->DBID]['LINE'].= $r0->PRONAME.' ('.$r0->ENVNAME.') ';}
			elseif($r0->ENVNAME != ''){$dbInstanceArray[$r0->DBID]['LINE'].=$r0->ENVNAME.' ';}
		}
		
		echo '
		<form class="ui-filterable">
			<input id="rich-autocomplete-input" data-type="search" placeholder="Search a database instance">
		</form>
		<ul data-role="listview" data-filter="true" data-inset="true" data-input="#rich-autocomplete-input">
			';
		foreach($dbInstanceIndex as $dbInstanceId) {
			echo '<li data-filtertext="'.$dbInstanceArray[$dbInstanceId]['NAME'].':'.$dbInstanceArray[$dbInstanceId]['LINE'].'"><a href="index.php?g=cmdb&p=databases&a=dbInstanceDesc&dbInstanceId='.$dbInstanceId.'">'.$dbInstanceArray[$dbInstanceId]['LINE'].'</a></li>';
		}
		echo '
		</ul>
			';
    break;
}
?>