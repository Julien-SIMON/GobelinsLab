<?php
// For all devices wich have OS, get informations they need
$deviceArray = array();

$q0=get_link()->prepare('
SELECT
	o.id AS OSID,
	o.os_type AS OSTYPE,
	o.os_port AS OSPORT,
	d.id AS DEVICEID,
	d.name AS HOSTNAME
FROM 	
	'.get_ini('BDD_PREFIX').'cmdb_dev_os o
LEFT JOIN
	'.get_ini('BDD_PREFIX').'cmdb_devices d
ON
	d.deleted_date=0 AND
	d.id = o.id_device
WHERE
	(o.created_date<:created_date AND o.edited_date<:edited_date) AND
	o.deleted_date=0
					'); 
					
// TODO 17h30 => 7h30
$q0->execute(array( 'created_date' => mktime(17,30,0,date('m'),date('d'),date('Y')) , 'edited_date' => mktime(17,30,0,date('m'),date('d'),date('Y')) ));
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{	
	if(trim($r0->HOSTNAME) && !in_array($r0->DEVICEID,$deviceArray)) {
		array_push($deviceArray,$r0->DEVICEID);
	}
}

foreach($deviceArray as $deviceId) {
	$dev = new device($deviceId);
	$server = new server($deviceId);
		
	$procM = new processusManager();
			
	if($procM->isTaskProcess('task_audit_os_process_'.$dev->name) == 0) { // If no process exist for this hostname
		
		$xmlFileContent='';
		$xmlFileContent.=
		'<?xml version="1.0" encoding="UTF-8"?>
		<XML>
		<HEADER>
		<PPID>1</PPID>
		<DATE></DATE>
		<TIMEOUT>120</TIMEOUT>
		<PLUGIN>cmdb</PLUGIN>
		<PAGE>task_audit_os_process</PAGE>
		<COMMENT><![CDATA[]]></COMMENT>
		</HEADER>
		<DATA>
			<SERVERS>
				<SERVER>
					<NAME>'.$dev->name.'</NAME>
					<OSES>
		';
	
		foreach($server->osIndex as $osId) {
			$os = new os($osId);

			$xmlFileContent.=
			'
			<OS>
			<TYPE>'.$os->type.'</TYPE>
			<PORT>'.$os->port.'</PORT>
			</OS>
			';
		}
		
		$xmlFileContent.=
		'
					</OSES>
				</SERVER>
			</SERVERS>
		</DATA>
		</XML>
		';
		
		file_put_contents('upload/queue/task_audit_os_process_'.$dev->name.'_'.date('Ymd').'.xml', $xmlFileContent);
	}
}
?>