<?php
// ------------------------------------------------------------------- //
// Add this statements to all you page. Adapt only the secLvl variable
// ------------------------------------------------------------------- //
if(!secFile(__FILE__,90)){return;}
// ------------------------------------------------------------------- //



switch ($a) {
    case 'create_form':
    	echo '
<div class="alert alert-info" role="alert">
	'._('#core#_#23#').'
</div>

<p>
	<select name="job">
    	';
    	
    	$pluginM = new pluginManager();
    	$jobM = new jobManager();
		foreach($init->pluginsIndex as $plugin) 
		{
			foreach(scandir('plugins/'.$plugin) as $file) 
			{
				if(!is_dir('plugins/'.$plugin.'/'.$file) && preg_match('#^task_.*_loader.php$#',$file) && $jobM->getId($pluginM->getId($plugin),substr($file,5,strlen($file)-16))==0)
				{
					echo '<option value="'.$pluginM->getId($plugin).';'.substr($file,5,strlen($file)-16).'">'.$file.'</option>';
				}
			}
		}
    	
    	
        echo '
	</select>
</p>

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_jobs&a=create\',$(\'form#popupForm\').serialize());">
Ajouter
</button>
		';
    break;
    case 'create':
        if(!isset($_POST['job'])||$_POST['job']==''){
        	// Todo error
        	exit();
        }
        
        $pluginId = substr($_POST['job'],0,strpos($_POST['job'],';'));
        $pageName = substr($_POST['job'],strpos($_POST['job'],';')+1);
        
        $jobM = new jobManager();
        
        if($jobM->getId($pluginId,$pageName)==0) {
        	$jobM->create($pluginId,$pageName);
        	
            // TODO
        	echo 'Le job vient d\'être ajouté!';
        
        	echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
        } else {
        	// TODO
            echo 'Ce job existe déjà dans notre base de données.';
        }
    break;
    case 'update_form':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
        $job = new job($id);
        
        // TODO add datepicker
        if($job->startSchedule != '' && $job->startSchedule > 0){$startSchedule=date('d/m/Y',$job->startSchedule);}else{$startSchedule='';}
        if($job->endSchedule != '' && $job->endSchedule > 0){$endSchedule=date('d/m/Y',$job->endSchedule);}else{$endSchedule='';}
        if($job->startPolling != '' && $job->startPolling > 0){$startPolling=date('d/m/Y',$job->startPolling);}else{$startPolling='';}
        if($job->endPolling != '' && $job->endPolling > 0){$endPolling=date('d/m/Y',$job->endPolling);}else{$endPolling='';}
        echo '
<p>
	<div class="input-group">
		<span class="input-group-addon">Date de première éxcution</span>
		<input name="start_time" type="text" class="form-control" value="'.$startSchedule.'"  placeholder="dd/mm/yyyy">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Date de dernière éxcution</span>
		<input name="end_time" type="text" class="form-control" value="'.$endSchedule.'"  placeholder="dd/mm/yyyy">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Jours de la semaine</span>
		<input name="days" type="text" class="form-control" value="'.$job->daysSchedule.'"  placeholder="1,2,3,4,5">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Heures</span>
		<input name="hours" type="text" class="form-control" value="'.$job->hoursSchedule.'"  placeholder="00,06,12,18">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Minutes</span>
		<input name="minutes" type="text" class="form-control" value="'.$job->minutesSchedule.'"  placeholder="00,15,30,45">
	</div>
</p>
<input name="id" type="hidden" value="'.$job->id.'"> 

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_jobs&a=update\',$(\'form#popupForm\').serialize());">
Modifier
</button>

<p>
-- ou --
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Date de première éxcution</span>
		<input name="start_time" type="text" class="form-control" value="'.$startPolling.'"  placeholder="dd/mm/yyyy">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Date de dernière éxcution</span>
		<input name="end_time" type="text" class="form-control" value="'.$endPolling.'"  placeholder="dd/mm/yyyy">
	</div>
</p>
<p>
	<div class="input-group">
		<span class="input-group-addon">Toutes les X secondes</span>
		<input name="polling" type="text" class="form-control" value="'.$job->polling.'"  placeholder="3600">
	</div>
</p>
<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_jobs&a=updatePolling\',$(\'form#popupForm\').serialize());">
Modifier
</button>
		';
    break;
    case 'update':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['start_time'])){$startTime=$_GET['start_time'];}elseif(isset($_POST['start_time'])){$startTime=$_POST['start_time'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['end_time'])){$endTime=$_GET['end_time'];}elseif(isset($_POST['end_time'])){$endTime=$_POST['end_time'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['days'])){$days=$_GET['days'];}elseif(isset($_POST['days'])){$days=$_POST['days'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['hours'])){$hours=$_GET['hours'];}elseif(isset($_POST['hours'])){$hours=$_POST['hours'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['minutes'])){$minutes=$_GET['minutes'];}elseif(isset($_POST['minutes'])){$minutes=$_POST['minutes'];}else{
    		// TODO ERROR
    	}
    	$startTime = mktime(0,0,0,substr($startTime,3,2),substr($startTime,0,2),substr($startTime,6,4));
    	$endTime = mktime(23,59,59,substr($endTime,3,2),substr($endTime,0,2),substr($endTime,6,4));
    	
        $job = new job($id); 
        
        $job->updateScheduled($startTime,$endTime,$days,$hours,$minutes);
            
        // TODO
        echo 'Le job vient d\'être modifié!';
        
        echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
    break;
    case 'updatePolling':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['polling'])){$polling=$_GET['polling'];}elseif(isset($_POST['polling'])){$polling=$_POST['polling'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['start_time'])){$startTime=$_GET['start_time'];}elseif(isset($_POST['start_time'])){$startTime=$_POST['start_time'];}else{
    		// TODO ERROR
    	}
    	if(isset($_GET['end_time'])){$endTime=$_GET['end_time'];}elseif(isset($_POST['end_time'])){$endTime=$_POST['end_time'];}else{
    		// TODO ERROR
    	}
    	$startTime = mktime(0,0,0,substr($startTime,3,2),substr($startTime,0,2),substr($startTime,6,4));
    	$endTime = mktime(23,59,59,substr($endTime,3,2),substr($endTime,0,2),substr($endTime,6,4));
    	
        $job = new job($id); 
        
        $job->updatePolling($startTime,$endTime,$polling);
            
        // TODO
        echo 'Le job vient d\'être modifié!';
        
        echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
    break;
    case 'delete_form':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}
    	
        $job = new job($id);
               
        echo '
<p>
'._('#core#_#24#').' '.$job->page.' ? <BR>
</p>
<input name="id" type="hidden" value="'.$id.'">

<button type="button" class="btn btn-primary" onClick="popupFormSubmit(\'index.php?m=a&g=core&p=admin_jobs&a=delete\',$(\'form#popupForm\').serialize());">
Supprimer
</button>
		';
    break;
    case 'delete':
    	if(isset($_GET['id'])){$id=$_GET['id'];}elseif(isset($_POST['id'])){$id=$_POST['id'];}else{
    		// TODO ERROR
    	}

		$jobM = new jobManager();
			
		$jobM->delete($id);
		// TODO confirmation
        echo 'Le job vient d\'être supprimé!';
        
        echo '<script type="text/javascript">dataTable.ajax.reload();</script>';
    break;
    // Display the table content
    case 'jsonList':
    	$dataArray['data'] = array();
		$q0=get_link()->prepare("
SELECT 
	job.id AS ID
FROM 
	".get_ini('BDD_PREFIX')."core_jobs job 
LEFT JOIN
	".get_ini('BDD_PREFIX')."core_plugins p
ON
	job.id_plugin = p.id
AND p.deleted_date = 0
where 
	job.deleted_date = 0
ORDER BY 
	p.name, job.page
		");
		$q0->execute();
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
			$job = new job($r0->ID); 
			
			$lastRunTime = '-';
			if($job->lastRun != '' && $job->lastRun > 0){$lastRunTime = date('d/m/Y H:i:s',$job->lastRun);}
			
			$schedule = '-';
			if($job->daysSchedule != ''){$schedule = $job->daysSchedule;}
			if($job->polling != ''){$schedule .= ' '.$job->polling;}
			
			array_push(
				$dataArray['data'],
				array( 
					"ID" => $job->id ,
					"PLUGIN" => $job->pluginName ,
					"PAGE" => $job->page ,
					"LASTRUN" => $lastRunTime ,
					"SCHEDULE" => $schedule ,
					"ACTION" => '<a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Modifier un travail\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_jobs&a=update_form&id='.$job->id.'\');"><span class="iconastic-edit-write"> Modifier </span></a> <a href="#" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Supprimer un travail\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_jobs&a=delete_form&id='.$job->id.'\');"><span class="iconastic-minus-line"> Supprimer</span></a>'
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
		<h3 class="box-title">Liste des travaux</h3>
		<a href="#popup" data-toggle="modal" data-target="#popup" onClick="insertLoader(\'#popupContent\');setPopupTitle(\'Ajouter un travail\');$(\'#popupContent\').load(\'index.php?m=a&g=core&p=admin_jobs&a=create_form\');"><span class="iconastic-plus-square"> Ajouter</span></a>
	</div>
	<div class="box-body">
		<table id="dataTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Id</th>
					<th>Plugin</th>
					<th>Page</th>
					<th>Last run</th>
					<th>Schedule</th>
					<th><a href="#" onClick="dataTable.ajax.reload();"><span class="iconastic-refresh"> Rafraichir</a> </th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<script>
var dataTable = 
$(\'#dataTable\').DataTable( {
    "ajax": "index.php?m=a&g=core&p=admin_jobs&a=jsonList",
    "columns": [
        { "data": "ID" },
        { "data": "PLUGIN" },
        { "data": "PAGE" },
        { "data": "LASTRUN" },
        { "data": "SCHEDULE" },
        { "data": "ACTION" }
    ]
} );
dataTable.order( [ 3, \'asc\' ] ).draw();
</script>
		';
    break;
}

?>



