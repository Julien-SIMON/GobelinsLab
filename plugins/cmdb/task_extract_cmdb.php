<?php
$environmentsIndex=array();
$environmentsArray=array();

$projectsIndex=array();
$projectsArray=array();

$serversIndex=array();
$serversArray=array();

// Fisheye connexion
class cmdbSource {
    private static $instance;
 
	private function __construct() {
		try
		{
			self::$instance = new PDO('oci:dbname=FISHEYE.WORLD;charset=UTF8', 'FISHEYECNS', 'FISHEYECNS');
			self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
  
		catch(Exception $e)
		{
			echo 'Erreur : '.$e->getMessage().'<br />';
			echo 'N° : '.$e->getCode();
  
			// phpinfo();
		}
	}
 
	public static function getDB() {
		if(!isset(self::$instance) || self::$instance == null) {
			new cmdbSource();
		}
		return self::$instance; 
	}
}

// Function to use DB Link
// Use: $query=getDB()->prepare($sql); // It's a PDO connexion
function getCmdbSource() {
	return cmdbSource::getDB();
}

// Get from fisheye
$q0=getCmdbSource()->prepare("
select
    s.idsvr AS SERVERID, 
    s.nomdns AS SERVERNAME,
    s.lib AS SERVERDESCRIPTION,
    mpe.ENVIRONMENTNAME AS ENVIRONMENTNAME,
    mse.ENVIRONMENTNAME AS ENVIRONMENTNAMESERVER,
    mpe.PROJECTNAME AS PROJECTNAME
from
    FISHEYE.SVR s
left join	
(	
select 
    a2.idsvr AS SERVERID,
    a0.nom AS PROJECTNAME,
    e.nom AS ENVIRONMENTNAME
from
    FISHEYE.APP a0,
    FISHEYE.SOUSAPP a1,
    FISHEYE.SOUSAPPENV a2,
    FISHEYE.ENV e,
    FISHEYE.APPENV ae
where
    a0.idapp = a1.idapp AND
    a1.idsousapp = a2.idsousapp AND
    a2.idappenv = ae.idappenv AND
    ae.idenv = e.idenv
) mpe
on mpe.SERVERID = s.idsvr
left join	
(	
select 
    se.idsvr AS SERVERID,
    '' AS PROJECTNAME,
    e.nom AS ENVIRONMENTNAME
from
    FISHEYE.SVRENV se,
    FISHEYE.ENV e
where
    se.idenv = e.idenv
) mse
on mse.SERVERID = s.idsvr
where
s.datendlife IS NOT NULL
							"); 
$q0->execute();
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{
	// Environment
	if(!in_array($r0->ENVIRONMENTNAME, $environmentsIndex))
	{
		array_push($environmentsIndex, $r0->ENVIRONMENTNAME);
		$environmentsArray[$r0->ENVIRONMENTNAME]['PARENT']='';
	}
	if(!in_array($r0->ENVIRONMENTNAMESERVER, $environmentsIndex))
	{
		array_push($environmentsIndex, $r0->ENVIRONMENTNAMESERVER);
		$environmentsArray[$r0->ENVIRONMENTNAMESERVER]['PARENT']='';
	}
	// Project
	if(!in_array($r0->PROJECTNAME, $projectsIndex))
	{
		array_push($projectsIndex, $r0->PROJECTNAME);
		$projectsArray[$r0->PROJECTNAME]['PARENT']='';
	}
	// Server
	if(!in_array(utf8_encode($r0->SERVERNAME), $serversIndex))
	{
		array_push($serversIndex, $r0->SERVERNAME);
		
		$serversArray[$r0->SERVERNAME]['PROJECT']=array();
		$serversArray[$r0->SERVERNAME]['ENVIRONMENT']=array();
	}
	
	// Map server with environment and project
	array_push($serversArray[$r0->SERVERNAME]['PROJECT'],$r0->PROJECTNAME);
	if($r0->ENVIRONMENTNAME != ''){
		array_push($serversArray[$r0->SERVERNAME]['ENVIRONMENT'],$r0->ENVIRONMENTNAME);
	} else {
		array_push($serversArray[$r0->SERVERNAME]['ENVIRONMENT'],$r0->ENVIRONMENTNAMESERVER);
	}
}



$xmlFileContent='';

$xmlFileContent.=
'<?xml version="1.0" encoding="UTF-8"?>
<XML>
<HEADER>
<PPID>1</PPID>
<DATE></DATE>
<TIMEOUT>120</TIMEOUT>
<PLUGIN>cmdb</PLUGIN>
<PAGE>task_import_cmdb</PAGE>
<COMMENT><![CDATA[Import de la CMDB depuis une extraction du serveur Fisheye. Cette extraction contient des informations relatives aux environements, projets et serveurs.]]></COMMENT>
</HEADER>
<DATA>
';


$xmlFileContent.=
'
<ENVIRONMENTS>
';
foreach($environmentsIndex as $environment)
{
	$xmlFileContent.=
'	<ENVIRONMENT>
		<NAME><![CDATA['.$environment.']]></NAME>
		<PARENT><![CDATA['.$environmentsArray[$environment]['PARENT'].']]></PARENT>
	</ENVIRONMENT>
';
}
$xmlFileContent.=
'
</ENVIRONMENTS>
';

$xmlFileContent.=
'
<PROJECTS>
';
foreach($projectsIndex as $project)
{
	$xmlFileContent.=
'	<PROJECT>
		<NAME><![CDATA['.$project.']]></NAME>
		<PARENT><![CDATA['.$projectsArray[$project]['PARENT'].']]></PARENT>
	</PROJECT>
';
}
$xmlFileContent.=
'
</PROJECTS>
';

$xmlFileContent.=
'
<SERVERS>
';
foreach($serversIndex as $server)
{
	$xmlFileContent.= 
'	<SERVER>
		<NAME><![CDATA['.$server.']]></NAME>
		<PARENT><![CDATA[]]></PARENT>
		<ASSIGNMENTS>
	';
	if(count($serversArray[$server]['ENVIRONMENT'])>0) {
		for($i=0;$i<count($serversArray[$server]['ENVIRONMENT']);$i++) {
			$xmlFileContent.= '
			<ASSIGNMENT>
				<ENVIRONMENT><![CDATA['.$serversArray[$server]['ENVIRONMENT'][$i].']]></ENVIRONMENT>
				<PROJECT><![CDATA['.$serversArray[$server]['PROJECT'][$i].']]></PROJECT>
			</ASSIGNMENT>
			';
		}
	}
	$xmlFileContent.= '
		</ASSIGNMENTS>
	</SERVER>
';
}
$xmlFileContent.=
'
</SERVERS>
';

$xmlFileContent.=
'
</DATA>
</XML>
';

file_put_contents('upload/queue/cmdb_import_'.date('Ymd').'.xml', $xmlFileContent);
?>