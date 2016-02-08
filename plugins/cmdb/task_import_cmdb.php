<?php
$environmentsIndex=array();
$environmentsArray=array();

$projectsIndex=array();
$projectsArray=array();

$serversIndex=array();
$serversArray=array();

// Parse the data in array
$itemCount=0;
while(is_object($items = $item_DATA->getElementsByTagName("ENVIRONMENTS")->item($itemCount)))
{
	foreach($items->childNodes as $item)
	{
		$itemNameValue=$item->getElementsByTagName("NAME")->item(0)->nodeValue;
		$itemParentValue=$item->getElementsByTagName("PARENT")->item(0)->nodeValue;
		if($itemNameValue == ''){$itemNameValue=' ';} // To avoid "null string" (for Oracle compatibility)
		if($itemParentValue == ''){$itemParentValue=' ';} // To avoid "null string" (for Oracle compatibility)

		if(!in_array($itemParentValue, $environmentsIndex)){array_push($environmentsIndex, $itemParentValue);$environmentsArray[$itemParentValue]['PARENT']=' ';}	
		if(!in_array($itemNameValue, $environmentsIndex)){array_push($environmentsIndex, $itemNameValue);}
		$environmentsArray[$itemNameValue]['PARENT']=$itemParentValue;
	}
	$itemCount++;
}


$itemCount=0;
while(is_object($items = $item_DATA->getElementsByTagName("PROJECTS")->item($itemCount)))
{
	foreach($items->childNodes as $item)
	{
		$itemNameValue=$item->getElementsByTagName("NAME")->item(0)->nodeValue;
		$itemParentValue=$item->getElementsByTagName("PARENT")->item(0)->nodeValue;
		if($itemNameValue == ''){$itemNameValue=' ';} // To avoid "null string" (for Oracle compatibility)
		if($itemParentValue == ''){$itemParentValue=' ';} // To avoid "null string" (for Oracle compatibility)

		if(!in_array($itemParentValue, $projectsIndex)){array_push($projectsIndex, $itemParentValue);$projectsArray[$itemParentValue]['PARENT']=' ';}	
		if(!in_array($itemNameValue, $projectsIndex)){array_push($projectsIndex, $itemNameValue);}
		$projectsArray[$itemNameValue]['PARENT']=$itemParentValue;
	}
	$itemCount++;
}

$itemCount=0;
while(is_object($items = $item_DATA->getElementsByTagName("SERVERS")->item($itemCount)))
{
	foreach($items->childNodes as $item)
	{
		$itemNameValue=$item->getElementsByTagName("NAME")->item(0)->nodeValue;
		$itemParentValue=$item->getElementsByTagName("PARENT")->item(0)->nodeValue;
		if($itemNameValue == ''){$itemNameValue=' ';} // To avoid "null string" (for Oracle compatibility)
		if($itemParentValue == ''){$itemParentValue=' ';} // To avoid "null string" (for Oracle compatibility)
			
		if(!in_array($itemParentValue, $serversIndex)){array_push($serversIndex, $itemParentValue);$serversArray[$itemParentValue]['PARENT']=' ';$serversArray[$itemParentValue]['ENVIRONMENTS']['environmentsIndex']=array();}	
		if(!in_array($itemNameValue, $serversIndex)){array_push($serversIndex, $itemNameValue);}
		$serversArray[$itemNameValue]['PARENT']=$itemParentValue;
		
		$serversArray[$itemNameValue]['ENVIRONMENTS']['environmentsIndex']=array();
			
		$subItemCount=0;
		while(is_object($subItems = $item->getElementsByTagName("ASSIGNMENTS")->item($subItemCount)))
		{
			foreach($subItems->childNodes as $subItem)
			{
				$subItemEnvNameValue=$subItem->getElementsByTagName("ENVIRONMENT")->item(0)->nodeValue;
				$subItemProNameValue=$subItem->getElementsByTagName("PROJECT")->item(0)->nodeValue;
				if($subItemEnvNameValue == ''){$subItemEnvNameValue=' ';} // To avoid "null string" (for Oracle compatibility)
				if($subItemProNameValue == ''){$subItemProNameValue=' ';} // To avoid "null string" (for Oracle compatibility)
				
				if(!in_array($subItemEnvNameValue, $serversArray[$itemNameValue]['ENVIRONMENTS']['environmentsIndex']))
				{
					array_push($serversArray[$itemNameValue]['ENVIRONMENTS']['environmentsIndex'], $subItemEnvNameValue);

					$serversArray[$itemNameValue]['ENVIRONMENTS'][$subItemEnvNameValue]=array();
				}
				if(!in_array($subItemProNameValue, $serversArray[$itemNameValue]['ENVIRONMENTS'][$subItemEnvNameValue]))
				{
					array_push($serversArray[$itemNameValue]['ENVIRONMENTS'][$subItemEnvNameValue], $subItemProNameValue);
				}
			}
			$subItemCount++;
		}
	}
	$itemCount++;
}

$procM->update($proc->id,'running','40');

//
// Update the database with the data
//

// Environments
//
$envM = new environmentManager();

// Delete environment store in database and not in the data array
$q0=get_link()->prepare('SELECT name AS NAME FROM '.get_ini('BDD_PREFIX').'cmdb_environments WHERE deleted_date=0'); 
$q0->execute(array( 'deleted_date' => time() ));
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{
	if(!in_array($r0->NAME, $environmentsIndex)){$envM->delete($envM->getId($r0->NAME));}
}

// Add new environments and update existing
foreach ($environmentsIndex as $environment) 
{
	if($envM->getId($environmentsArray[$environment]['PARENT'])>0){$parentId=$envM->getId($environmentsArray[$environment]['PARENT']);}else{$parentId=0;}
		
	if($envM->getId($environment)>0)
	{
		//$envM->update($envM->get_id($environment),$parentId);
		//echo 'Mise à jour de l\'environement: '.$environment.'<BR>';
		$envM->update($envM->getId($environment),$parentId,$environment);
	}
	else
	{
		$envM->create($parentId,$environment);
		//echo 'Ajout de l\'environement: '.$environment.'<BR>';
	}
}

$procM->update($proc->id,'running','50');

// Projects
//
$projM = new projectManager();

// Delete project store in database and not in the data array
$q0=get_link()->prepare('SELECT name AS NAME FROM '.get_ini('BDD_PREFIX').'cmdb_projects WHERE deleted_date=0 OR deleted_date>:deleted_date'); 
$q0->execute(array( 'deleted_date' => time() ));
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) ) 
{
	if(!in_array($r0->NAME, $projectsIndex)){$projM->delete($projM->getId($r0->NAME));}
}

foreach ($projectsIndex as $project) 
{
	if($projM->getId($projectsArray[$project]['PARENT'])>0){$parentId=$projM->getId($projectsArray[$project]['PARENT']);}else{$parentId=0;}
		
	if($projM->getId($project)>0)
	{
		$projM->update($projM->getId($project),$parentId,$project);
	}
	else
	{
		$projM->create($parentId,$project);
	}
}

$procM->update($proc->id,'running','60');

// Devices
//
$devM = new deviceManager();

// Delete device store in database and not in the data array
$q0=get_link()->prepare('SELECT name AS NAME FROM '.get_ini('BDD_PREFIX').'cmdb_devices WHERE deleted_date=0 OR deleted_date>:deleted_date'); 
$q0->execute(array( 'deleted_date' => time() ));
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) ) 
{
	if(!in_array($r0->NAME, $serversIndex)){$devM->delete($devM->getId('server',$r0->NAME));}
}

foreach ($serversIndex as $server) 
{
	if($devM->getId('server',$serversArray[$server]['PARENT'])>0){$parentId=$devM->getId('server',$serversArray[$server]['PARENT']);}else{$parentId=0;}

	if($devM->getId('server',$server)>0)
	{
		$devM->update($devM->getId('server',$server),$parentId,$server,'server','');
	}
	else
	{
		$devM->create($parentId,$server,'server','');
	}
	
	$dev = new device($devM->getId('server',$server));
	
	foreach($dev->mdpeIdArray as $mdpeId) {
		if(!in_array($dev->mdpeArray[$mdpeId]['ENVIRONMENT'], $serversArray[$server]['ENVIRONMENTS']['environmentsIndex'])){$devM->deleteMdpe($mdpeId);}
	}
	
	foreach ($serversArray[$server]['ENVIRONMENTS']['environmentsIndex'] as $serverEnv) 
	{
		foreach ($serversArray[$server]['ENVIRONMENTS'][$serverEnv] as $serverEnvProj) 
		{
			if($envM->getId($serverEnv)>0 && $projM->getId($serverEnvProj)>0) {
				if($devM->getIdMdpe($dev->id,$projM->getId($serverEnvProj),$envM->getId($serverEnv))==0) {

					$devM->createMdpe($devM->getId('server',$server),$projM->getId($serverEnvProj),$envM->getId($serverEnv));
				}
			}
		}
		// Delete device when they use an environment "stop,deprecated,etc."
		// TODO if(in_array($serverEnv,explode(',',get_ini('DELETED_ENVIRONMENT_NAMES')))){$devs->delete($devs->get_id($server));}
	}
}

$procM->update($proc->id,'running','99');
?>