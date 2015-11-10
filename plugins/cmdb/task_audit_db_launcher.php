<?php
$q0=get_link()->prepare('
SELECT
	d.name AS HOSTNAME,
	i.name AS DBINSTNAME,
	i.db_type AS DBINSTTYPE
FROM 
	'.get_ini('BDD_PREFIX').'cmdb_db_instances i,
	'.get_ini('BDD_PREFIX').'cmdb_devices d,
	'.get_ini('BDD_PREFIX').'cmdb_dev_os o
WHERE
	i.audited=\'1\' AND
	d.id = o.id_device AND
	i.id_os = o.id AND
	i.deleted_date=0 AND
	d.deleted_date=0 AND
	o.deleted_date=0
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
		<PAGE>task_audit_db_process</PAGE>
		<COMMENT><![CDATA[]]></COMMENT>
		</HEADER>
		<DATA>
			<DBINSTANCES>
				<DBINSTANCE>
					<HOSTNAME>'.$r0->HOSTNAME.'</HOSTNAME>
					<DBNAME>'.$r0->DBINSTNAME.'</DBNAME>
					<DBTYPE>'.$r0->DBINSTTYPE.'</DBTYPE>
				</DBINSTANCE>
			</DBINSTANCES>
		</DATA>
		</XML>
		';
		
		file_put_contents('upload/queue/task_audit_db_process_'.$r0->DBINSTTYPE.'.'.$r0->HOSTNAME.'.'.$r0->DBINSTNAME.'.xml', $xmlFileContent);	
	}
}
?>