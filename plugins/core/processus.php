<?php
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
?>

<li class="dropdown tasks-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="icon iconastic-tasks"></i>
    <?php if(count($processIndex) > 0) { echo '<span class="label label-danger">'.count($processIndex).'</span>'; } ?>
  </a>
  <ul class="dropdown-menu">
    <!-- <li class="header">You have 9 tasks</li> -->
    <li>
      <!-- inner menu: contains the actual data -->
      <ul class="menu">
<?php
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
?>
      </ul>
    </li>
    <li class="footer">
      <a href="index.php?g=core&p=admin_processus"><?php echo _('#core#_#14#'); ?></a>
    </li>
  </ul>
</li>