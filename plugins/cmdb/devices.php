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
	case 'dbInstanceUpdateUpdate':
		if(isset($_GET['dbInstanceId'])){$dbInstanceId=$_GET['dbInstanceId'];}elseif(isset($_POST['dbInstanceId'])){$dbInstanceId=$_POST['dbInstanceId'];}else{
			// TODO
			exit(100);
		}
		if(isset($_GET['name'])){$name=$_GET['name'];}elseif(isset($_POST['name'])){$name=$_POST['name'];}else{
			// TODO
			exit(100);
		}
		if(isset($_GET['port'])){$port=$_GET['port'];}elseif(isset($_POST['port'])){$port=$_POST['port'];}else{$port='';}
		//if(isset($_GET['login'])){$login=$_GET['login'];}elseif(isset($_POST['login'])){$login=$_POST['login'];}else{$login='';}
		//if(isset($_GET['password'])){$password=$_GET['password'];}elseif(isset($_POST['password'])){$password=$_POST['password'];}else{$password='';}
		
		$dbInstance = new dbInstance($dbInstanceId);
		$dbInstanceM = new dbInstanceManager($dbInstance->idOs);
		
		$dbInstanceM->update($dbInstanceId,$name,$dbInstance->type,$dbInstance->version,$port,$dbInstance->path);
		//updateCredentials($login,$password,$subLogin,$subPassword)
				
		// TODO - add result
		echo '<script>location.reload();</script>';
	break;
	case 'dbInstanceUpdateForm':
		if(isset($_GET['dbInstanceId'])){$dbInstanceId=$_GET['dbInstanceId'];}elseif(isset($_POST['dbInstanceId'])){$dbInstanceId=$_POST['dbInstanceId'];}else{
			// TODO
			exit(100);
		}
		
		$dbInstance = new dbInstance($dbInstanceId);
		
		echo '<h3>Update '.$dbInstance->name.'</h3>
			Instance name: <input name="name" type="text" value="'.$dbInstance->name.'">
			Type: <input type="text" value="'.$dbInstance->type.'" disabled>
			Port: <input name="port" type="text" value="'.$dbInstance->port.'">
			';
		echo '<input name="dbInstanceId" type="hidden" value="'.$dbInstanceId.'">';
		echo '<input name="submit" value="Submit" onClick="popupFormSubmit(\'index.php?m=a&g=cmdb&p=devices&a=dbInstanceUpdateUpdate\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">';
	break;
	case 'dbInstanceDeleteUpdate':
		if(isset($_GET['dbInstanceId'])){$dbInstanceId=$_GET['dbInstanceId'];}elseif(isset($_POST['dbInstanceId'])){$dbInstanceId=$_POST['dbInstanceId'];}else{
			// TODO
			exit(100);
		}
		
		$dbInstance = new dbInstance($dbInstanceId);
		$dbInstanceM = new dbInstanceManager($dbInstance->idOs);
		
		$dbInstanceM->delete($dbInstanceId);
				
		// TODO - add result
		echo '<script>location.reload();</script>';
	break;
	case 'dbInstanceDeleteForm':
		if(isset($_GET['dbInstanceId'])){$dbInstanceId=$_GET['dbInstanceId'];}elseif(isset($_POST['dbInstanceId'])){$dbInstanceId=$_POST['dbInstanceId'];}else{
			// TODO
			exit(100);
		}
		
		$dbInstance = new dbInstance($dbInstanceId);
		
		echo '<h3>Delete '.$dbInstance->name.'</h3>
			Are you sure ?<BR>
			The history of this instance will be deleted too.
			';
		echo '<input name="dbInstanceId" type="hidden" value="'.$dbInstanceId.'">';
		echo '<input name="submit" value="Delete this instance" onClick="popupFormSubmit(\'index.php?m=a&g=cmdb&p=devices&a=dbInstanceDeleteUpdate\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">';
	break;	
	case 'dbInstanceCreateUpdate':
		if(isset($_GET['osId'])){$osId=$_GET['osId'];}elseif(isset($_POST['osId'])){$osId=$_POST['osId'];}else{
			// TODO
			exit(100);
		}
		if(isset($_GET['name'])){$name=$_GET['name'];}elseif(isset($_POST['name'])){$name=$_POST['name'];}else{
			// TODO
			exit(100);
		}
		if(isset($_GET['type'])){$type=$_GET['type'];}elseif(isset($_POST['type'])){$type=$_POST['type'];}else{
			// TODO
			exit(100);
		}
		if(isset($_GET['port'])){$port=$_GET['port'];}elseif(isset($_POST['port'])){$port=$_POST['port'];}else{
			// TODO
			exit(100);
		}
				
		$os = new os($osId);
		$dbInstanceM = new dbInstanceManager($osId);
		
		if($dbInstanceM->getId($name)==0) {
			$dbInstanceM->create($name,$type,'',$port,'');
		}
		
		// TODO - add result
		echo '<script>location.reload();</script>';
	break;
	case 'dbInstanceCreateForm':
		if(isset($_GET['osId'])){$osId=$_GET['osId'];}elseif(isset($_POST['osId'])){$osId=$_POST['osId'];}else{
			// TODO
			exit(100);
		}
		
		$os = new os($osId);
		
		echo '<h3>DB instance</h3>
			Instance name: <input name="name" type="text" value="" placeholder="EMOP0001"><BR>
			Type: <select name="type"><option value="oracle">Oracle</option><option value="mssqlserver">Microsoft SQL server</option><option value="mysql">MySql</option><option value="cassandradb">Cassandra DB</option></select>
			Port: <input name="port" type="text" value=""><BR>
			<i>
			
			Default port:<BR>
			Oracle - 1521<BR>
			Microsoft SQL server - 1433<BR>
			MySql - 3306<BR>
			Cassandra - 7199<BR>
			(Empty the port input value to use automatic discovery)
			</i>
			';
		echo '<input name="osId" type="hidden" value="'.$osId.'">';
		echo '<input name="submit" value="Submit" onClick="popupFormSubmit(\'index.php?m=a&g=cmdb&p=devices&a=dbInstanceCreateUpdate\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">';
	break;
	case 'osCreateUpdate':
		if(isset($_GET['deviceId'])){$deviceId=$_GET['deviceId'];}elseif(isset($_POST['deviceId'])){$deviceId=$_POST['deviceId'];}else{
			// TODO
			exit(100);
		}
		if(isset($_GET['type'])){$type=$_GET['type'];}elseif(isset($_POST['type'])){$type=$_POST['type'];}else{
			// TODO
			exit(100);
		}
		if(isset($_GET['port'])){$port=$_GET['port'];}elseif(isset($_POST['port'])){$port=$_POST['port'];}else{
			// TODO
			exit(100);
		}
		
		$server = new server($deviceId);
		
		$osM = new osManager($deviceId);
		
		if($osM->getId($type,$port)==0) {
			$osM->create($type,$port);
		}
		
		// TODO - add result
		echo '<script>location.reload();</script>';
	break;
	case 'osCreateForm':
		if(isset($_GET['deviceId'])){$deviceId=$_GET['deviceId'];}elseif(isset($_POST['deviceId'])){$deviceId=$_POST['deviceId'];}else{
			// TODO
			exit(100);
		}
		
		$dev = new device($deviceId);
		$server = new server($deviceId);
		
		echo '<h3>Os</h3>
			Name: '.$dev->name.'<BR>
			<BR>
			<BR>
			Type: <select name="type"><option value="windows">Windows</option><option value="linux">Linux</option></select>
			Port: <input name="port" type="text" value="0"><BR>
			';
		echo '<input name="deviceId" type="hidden" value="'.$deviceId.'">';
		echo '<input name="submit" value="Submit" onClick="popupFormSubmit(\'index.php?m=a&g=cmdb&p=devices&a=osCreateUpdate\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">';
	break;
	case 'osCredsUpdate':
		if(isset($_GET['osId'])){$osId=$_GET['osId'];}elseif(isset($_POST['osId'])){$osId=$_POST['osId'];}else{
			// TODO
			exit(100);
		}
		if(isset($_GET['login'])){$login=$_GET['login'];}elseif(isset($_POST['login'])){$login=$_POST['login'];}else{$login='';}
		if(isset($_GET['password'])){$password=$_GET['password'];}elseif(isset($_POST['password'])){$password=$_POST['password'];}else{$password='';}
		if(isset($_GET['subLogin'])){$subLogin=$_GET['subLogin'];}elseif(isset($_POST['subLogin'])){$subLogin=$_POST['subLogin'];}else{$subLogin='';}
		if(isset($_GET['subPassword'])){$subPassword=$_GET['subPassword'];}elseif(isset($_POST['subPassword'])){$subPassword=$_POST['subPassword'];}else{$subPassword='';}
		
		$os = new os($osId);
		
		$os->updateCredentials($login,$password,$subLogin,$subPassword);
		
		// TODO - add result
		echo '<script>location.reload();</script>';
	break;
	case 'osCredsForm':
		if(isset($_GET['deviceId'])){$deviceId=$_GET['deviceId'];}elseif(isset($_POST['deviceId'])){$deviceId=$_POST['deviceId'];}else{
			// TODO
			exit(100);
		}
		if(isset($_GET['osId'])){$osId=$_GET['osId'];}elseif(isset($_POST['osId'])){$osId=$_POST['osId'];}else{
			// TODO
			exit(100);
		}
		
		$dev = new device($deviceId);
		$server = new server($deviceId);
		
		echo '<h3>Os pull process</h3>
			Name: '.$dev->name.'<BR>
			<BR>
			<BR>
			
			If no login is provided, the process use the parameter file.<BR>
			DefaultWindowsLogin='.get_ini('CMDB_WINDOWS_DEFAULT_LOGIN').'<BR>
			DefaultLinuxLogin='.get_ini('CMDB_LINUX_DEFAULT_LOGIN').'<BR>
			<BR>
			Login: <input name="login" type="text" value=""><BR>
			Password: <input name="password" type="password" value=""><BR>
			
			<fieldset>
			For Linux only:<BR> 
			If needed, specify the login for a substitute authentification.<BR> 
			If no password is provided, the sudo su - <i>login</i> command will be use.<BR>
			SubLogin: <input name="subLogin" type="text" value=""><BR>
			SubPassword: <input name="subPassword" type="password" value=""><BR>
			</fieldset>
			';
		echo '<input name="osId" type="hidden" value="'.$osId.'">';
		echo '<input name="submit" value="Submit" onClick="popupFormSubmit(\'index.php?m=a&g=cmdb&p=devices&a=osCredsUpdate\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">';
	break;
	case 'cpuDesc':
		if(isset($_GET['deviceId'])){$deviceId=$_GET['deviceId'];}elseif(isset($_POST['deviceId'])){$deviceId=$_POST['deviceId'];}else{
			// TODO
			exit(100);
		}
		
		$server = new server($deviceId);
				
		echo '<h3>Cpus</h3>';
		
		foreach($server->cpuIndex as $cpuId){
			echo 'Id: '.$server->cpuArray[$cpuId]['LOGICAL_ID'].'<BR>';
			echo 'Fréquence: '.$server->cpuArray[$cpuId]['MAX_CLOCK_SPEED'].' Mhz<BR>';
			echo 'Nom: '.$server->cpuArray[$cpuId]['NAME'].'<BR>';
			echo '<BR>';
		}
		
		echo '<a href="#" data-rel="back" class="ui-btn ui-shadow">Close</a>';
	break;
	case 'commentUpdate':
		if(isset($_GET['deviceId'])){$deviceId=$_GET['deviceId'];}elseif(isset($_POST['deviceId'])){$deviceId=$_POST['deviceId'];}else{
			// TODO
			exit(100);
		}
		if(isset($_GET['comments'])){$comments=$_GET['comments'];}elseif(isset($_POST['comments'])){$comments=$_POST['comments'];}else{
			// TODO
			exit(100);
		}
		
		$dev = new device($deviceId);
		
		$dev->updateComments($comments);
		
		// TODO - add result
		echo '<script>location.reload();</script>';
	break;
	case 'commentForm':
		if(isset($_GET['deviceId'])){$deviceId=$_GET['deviceId'];}elseif(isset($_POST['deviceId'])){$deviceId=$_POST['deviceId'];}else{
			// TODO
			exit(100);
		}
		
		$dev = new device($deviceId);
		
		echo '<textarea name="comments" id="deviceCommentsTextarea" data-mini="true">'.$dev->comments.'</textarea>';
		echo '<input name="deviceId" type="hidden" value="'.$deviceId.'">';
		echo '<input name="submit" value="Modifier" onClick="popupFormSubmit(\'index.php?m=a&g=cmdb&p=devices&a=commentUpdate\',$(\'form#popupForm\').serialize());" type="button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b">';
	break;
	case 'deviceDesc':
		if(isset($_GET['deviceId'])){$deviceId=$_GET['deviceId'];}elseif(isset($_POST['deviceId'])){$deviceId=$_POST['deviceId'];}else{
			// TODO
			exit(100);
		}
		
		$dev = new device($deviceId);
		
		echo '<h3> '.$dev->name.' </h3>';
		
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
					<h3>Comments</h3>
					<div id="deviceComments">'.nl2br($dev->comments).'</div>
					<a href="#popup" data-rel="popup" data-position-to="window" onClick="insertLoader(\'#popupContent\');$(\'#popupContent\').load(\'index.php?m=a&g=cmdb&p=devices&a=commentForm&deviceId='.$deviceId.'\');"><span class="iconfa-edit-write"> Modifier</span></a>
				</div>
			</div>
		';


//------------ ! comment ----------

//------------ server -------------
if($dev->typeName == 'server') {
	$server = new server($dev->id);

//------------ Settings ----------
	echo '
		<div class="ui-block-c">
			<div class="ui-body ui-body-d block-content">
				<h3>Settings</h3>
		';
	if(count($server->osIndex)>0) {
		foreach($server->osIndex as $osId) {
			$os = new os($osId);
			echo '
				<div class="ui-field-contain">
				<label style="width: 75%;">Automatic OS pull :</label>
				<select id="selectActivatedFlipSwitch'.$os->id.'" class="flipswitch-select" data-role="slider" data-mini="true" onChange="$(\'#popupContent\').load(\'index.php?m=a&g=cmdb&p=devices&a=enableToggle&value=\' + $( this ).val() + \'&id='.$os->id.'\');">
				';
			if($os->audited==1) {
				echo '
			<option value="0">Off</option>
			<option value="1" selected>On</option>
					';
			} else {
				echo '
			<option value="0" selected>Off</option>
			<option value="1">On</option>
				';
			}
			
			echo '
				</select>
				</div>
				<div class="ui-field-contain">
				<label style="width: 75%;">OS pull :</label>
				<a href="#popup" onClick="$(\'#popupContent\').load(\'index.php?m=a&g=cmdb&p=devices&a=dbInstanceCreateForm&osId='.$os->id.'\' );" data-rel="popup" data-position-to="window" class="ui-btn ui-shadow ui-btn-inline ui-mini"><span class="icon iconfa-retweet"></span> Run </a>
				</div>';
		}
	}
	echo '
			</div>
		</div>
		';
//------------ ! Settings ----------
	
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
		$deviceIndex = array();
		$deviceArray = array();
		
		$q0=get_link()->prepare('
		SELECT
			d.id AS ID,
			d.id_parent AS ID_PARENT, 
			d.name AS DEVNAME,
			p.name AS PRONAME,
			e.name AS ENVNAME
		FROM 
			'.get_ini('BDD_PREFIX').'cmdb_devices d,
			'.get_ini('BDD_PREFIX').'cmdb_environments e,
			'.get_ini('BDD_PREFIX').'cmdb_projects p,
			'.get_ini('BDD_PREFIX').'cmdb_dev_pro_env_map m
		WHERE
			d.id = m.id_device AND
			p.id = m.id_project AND
			e.id = m.id_environment AND
			p.deleted_date=0 AND
			e.deleted_date=0 AND
			m.deleted_date=0 AND
			d.deleted_date=0
		ORDER BY d.name ASC, e.name ASC, p.name ASC
								'); 
		$q0->execute();
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{	
			if(!in_array($r0->ID,$deviceIndex)){
				array_push($deviceIndex,$r0->ID);
				$deviceArray[$r0->ID]['NAME']=$r0->DEVNAME;
				
				$deviceArray[$r0->ID]['LINE']=$r0->DEVNAME.' - ';
			}
			
			if($r0->PRONAME != ''){$deviceArray[$r0->ID]['LINE'].= $r0->PRONAME.' ('.$r0->ENVNAME.') ';}
			elseif($r0->ENVNAME != ''){$deviceArray[$r0->ID]['LINE'].=$r0->ENVNAME.' ';}
		}
		
		echo '
		<form class="ui-filterable">
			<input id="rich-autocomplete-input" data-type="search" placeholder="Search a device">
		</form>
		<ul data-role="listview" data-filter="true" data-inset="true" data-input="#rich-autocomplete-input">
			';
		foreach($deviceIndex as $deviceId) {
			echo '<li data-filtertext="'.$deviceArray[$deviceId]['NAME'].':'.$deviceArray[$deviceId]['LINE'].'"><a href="index.php?g=cmdb&p=devices&a=deviceDesc&deviceId='.$deviceId.'">'.$deviceArray[$deviceId]['LINE'].'</a></li>';
		}
		echo '
		</ul>
			';
    break;
}
?>