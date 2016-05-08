<?php
switch ($a) {
    case 'list':
		$processIndex = array();
		$processArray = array();
		
		$q0=get_link()->prepare("SELECT id AS ID, cmd AS CMD, status AS STATUS, percent AS PERCENT FROM ".get_ini('BDD_PREFIX')."core_processus WHERE deleted_date >= :deleted_date AND created_id = :created_id ORDER BY created_date DESC");
		$q0->execute( array( 'deleted_date' => (time()-3600) , 'created_id' => $_SESSION['USER_ID'] ) );
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
			if(!in_array($r0->ID,$processIndex)){array_push($processIndex,$r0->ID);}
			
			$processArray[$r0->ID]['CMD'] = $r0->CMD;
			$processArray[$r0->ID]['STATUS'] = $r0->STATUS;
			$processArray[$r0->ID]['PERCENT'] = $r0->PERCENT;
		}
		$q0->closeCursor();
		
    	echo '
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
	<i class="icon iconastic-tasks"></i>
		';
		if(count($processIndex)>0){echo '<span class="label label-danger">'.count($processIndex).'</span>';}
		echo '
</a>
<ul class="dropdown-menu">
<li>
		';

		if(count($processIndex) == 0)
		{
			echo '
	<li class="header">
		'._('#core#_#15#').'
	</li>
			';
		}
		else
		{
			foreach($processIndex as $processId)
			{
				echo '
	<!-- Task item -->
	<li>
		<a href="#">
		<h3>
			'.$processArray[$processId]['CMD'].' - '.$processArray[$processId]['STATUS'].'
			<small class="pull-right">'.$processArray[$processId]['PERCENT'].'%</small>
		</h3>
	    <div class="progress xs">
	    	<div class="progress-bar progress-bar-aqua" style="width: '.$processArray[$processId]['PERCENT'].'%" role="progressbar" aria-valuenow="'.$processArray[$processId]['PERCENT'].'" aria-valuemin="0" aria-valuemax="100">
	        	<span class="sr-only">'.$processArray[$processId]['PERCENT'].'% Complete</span>
	    	</div>
	    </div>
		</a>
	</li>
	<!-- end task item -->
				';
			}
		}

		echo '
</li>
<li class="footer">
<a href="index.php?g=core&p=admin_processus">'._('#core#_#14#').'</a>
</li>
</ul>
  		';
    break;    

    // Display Html table container
    default:
		echo '
<li class="dropdown tasks-menu" id="processManager'.$mtRand.'">
		';
		$aOld = $a; $a='list'; include('processus.php'); $a = $aOld; unset($aOld);
		echo '
</li>

<script>
function taskHeaderRefresh() {
	if($(\'#processManager'.$mtRand.'\').length) {
		$(\'#processManager'.$mtRand.'\').delay(5000).queue(function( nxt ) {
    		$(this).load(\'index.php?m=a&g=core&p=processus&a=list\',taskHeaderRefresh());
			nxt();
		});
	}
}

$(document).ready(function() { taskHeaderRefresh(); });
</script>
		';
    break;
}
?>