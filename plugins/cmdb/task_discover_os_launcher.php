<?php
$q0=get_link()->prepare('
SELECT
	d.name AS HOSTNAME,
	o.os_port AS PORT,
	o.os_type AS TYPE
FROM 
	'.get_ini('BDD_PREFIX').'cmdb_devices d
LEFT JOIN
	'.get_ini('BDD_PREFIX').'cmdb_dev_os o
ON
	o.deleted_date=0 AND
	d.id = o.id_device
WHERE
	d.name IN (\'CBSW2601\',\'CBSW2271\',\'EMSL0070\',\'EMSL0033\') AND
	d.deleted_date=0
					'); // TODO - delete d.name filter
$q0->execute();
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{	
	if(trim($r0->HOSTNAME) != '' && $r0->PORT == 0) {
		$xmlFileContent='';
		
		$xmlFileContent.=
		'<?xml version="1.0" encoding="UTF-8"?>
		<XML>
		<HEADER>
		<PPID>1</PPID>
		<DATE></DATE>
		<TIMEOUT>120</TIMEOUT>
		<PLUGIN>cmdb</PLUGIN>
		<PAGE>task_discover_os_process</PAGE>
		<COMMENT><![CDATA[]]></COMMENT>
		</HEADER>
		<DATA>
			<SERVERS>
				<SERVER>
					<NAME>'.$r0->HOSTNAME.'</NAME>
				</SERVER>
			</SERVERS>
		</DATA>
		</XML>
		';
		
		file_put_contents('upload/queue/task_discover_os_process_'.$r0->HOSTNAME.'.xml', $xmlFileContent);	
	}
}
?>