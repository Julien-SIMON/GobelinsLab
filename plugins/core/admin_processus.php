<?php
// ------------------------------------------------------------------- //
// Add this statements to all you page. Secure at top level.
// ------------------------------------------------------------------- //
if(!secFile(__FILE__,10)){return;}
// ------------------------------------------------------------------- //

switch ($a) {
    // Display the table content
    case 'jsonList':
    	$dataArray['data'] = array();
		$q0=get_link()->prepare("
SELECT 
	proc.id AS ID, 
	proc.cmd AS CMD, 
	proc.args AS ARGS, 
	proc.status AS STATUS, 
	proc.percent AS PERCENT,
	proc.created_date AS CREATIONDATE,
	proc.edited_date AS EDITIONDATE,
	proc.deleted_date AS DELETIONDATE,
	usr.name AS USERNAME
FROM 
	".get_ini('BDD_PREFIX')."core_processus proc,
	".get_ini('BDD_PREFIX')."core_users usr
WHERE 
	proc.created_id = usr.id
AND	(proc.deleted_date >= :deleted_date 
OR	proc.deleted_date = 0)
ORDER BY 
	proc.created_date DESC
		");
		$q0->execute( array( 'deleted_date' => (time()-3600) ) );
		while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
		{
			array_push(
				$dataArray['data'],
				array( 
					"USER" => $r0->USERNAME ,
					"ID" => $r0->ID ,
				    "CMD" => $r0->CMD.' '.$r0->ARGS ,
					"STATUS" => $r0->STATUS ,
					"PERCENT" => '<span class="pull-right">'.$r0->PERCENT.'%</span><div class="progress xs"><div class="progress-bar progress-bar-aqua" style="width: '.$r0->PERCENT.'%" role="progressbar" aria-valuenow="'.$r0->PERCENT.'" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">'.$r0->PERCENT.'% Complete</span></div></div>' 
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
		<h3 class="box-title">Liste de vos processus</h3>
	</div>
	<div class="box-body">
		<table id="dataTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>User</th>
					<th>Id</th>
					<th>Command</th>
					<th>Progress</th>
					<th>Status</th>
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
    "ajax": "index.php?m=a&g=core&p=admin_processus&a=jsonList",
    "columns": [
        { "data": "USER" },
        { "data": "ID" },
        { "data": "CMD" },
        { "data": "PERCENT" },
        { "data": "STATUS" }
    ]
} );
dataTable.order( [ 2, \'asc\' ] ).draw();
</script>
';
    break;
}

?>

