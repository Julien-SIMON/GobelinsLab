<?php
// Parse the data
$itemCount=0;
while(is_object($items = $item_DATA->getElementsByTagName("SERVERS")->item($itemCount)))
{
	foreach($items->childNodes as $item)
	{
		$hostname=$item->getElementsByTagName("NAME")->item(0)->nodeValue;

		$devM = new deviceManager();
		
		$dev = new device($devM->getId('server',$hostname));
		
		$server = new server($dev->id);
		
		$osM = new osManager($dev->id);
		
		$osItemCount=0;
		while(is_object($osItems = $item->getElementsByTagName("OSES")->item($osItemCount)))
		{
			foreach($osItems->childNodes as $osItem)
			{
				$osType=$osItem->getElementsByTagName("TYPE")->item(0)->nodeValue;
				$osPort=$osItem->getElementsByTagName("PORT")->item(0)->nodeValue;
				
				if($osM->getId($osType,$osPort)==0) {
					$osM->create($osType,$osPort);
				}
				$os = new os($osM->getId($osType,$osPort));
				
				$cpuM = new cpuManager($os->id);
				$diskM = new diskManager($os->id);
				$fsM = new fsManager($os->id);
				$serviceM = new serviceManager($os->id);
				$dbInstanceM = new dbInstanceManager($os->id);
				
				if(isset($osItem->getElementsByTagName("NAME")->item(0)->nodeValue)){$osName=$osItem->getElementsByTagName("NAME")->item(0)->nodeValue;}else{$osName='';}
				if(isset($osItem->getElementsByTagName("VERSION")->item(0)->nodeValue)){$osVersion=$osItem->getElementsByTagName("VERSION")->item(0)->nodeValue;}else{$osVersion='';}
				if(isset($osItem->getElementsByTagName("ARCHITECTURE")->item(0)->nodeValue)){$osArch=$osItem->getElementsByTagName("ARCHITECTURE")->item(0)->nodeValue;}else{$osArch='';}if($osName!='ERROR'&&$osName!=''&&$osArch=='ERROR'){$osArch='32-bit';}
				if(isset($osItem->getElementsByTagName("SERIALNUMBER")->item(0)->nodeValue)){$osSerial=$osItem->getElementsByTagName("SERIALNUMBER")->item(0)->nodeValue;}else{$osSerial='';}
				if(isset($osItem->getElementsByTagName("INSTALLDATE")->item(0)->nodeValue)){$osInstallDate=toTime($osItem->getElementsByTagName("INSTALLDATE")->item(0)->nodeValue);}else{$osInstallDate='0';}
				if(isset($osItem->getElementsByTagName("LASTBOOTDATE")->item(0)->nodeValue)){$osLastBootDate=toTime($osItem->getElementsByTagName("LASTBOOTDATE")->item(0)->nodeValue);}else{$osLastBootDate='0';}
				if(isset($osItem->getElementsByTagName("MEMORYSIZE")->item(0)->nodeValue)){$osMemorySize=$osItem->getElementsByTagName("MEMORYSIZE")->item(0)->nodeValue;}else{$osMemorySize='0';}
				
				if($osName!='ERROR') {
					$osM->update($os->id,$osName,$osVersion,$osType,$osArch,$osSerial,$osInstallDate,$osLastBootDate,$osMemorySize);
				
				
					// Cpus
					$cpuIndex = array();
					$cpuArray = array();
					$cpuItemCount=0;
					while(is_object($cpuItems = $item->getElementsByTagName("CPUS")->item($cpuItemCount)))
					{
						foreach($cpuItems->childNodes as $cpuItem)
						{
							$cpuDeviceId=$cpuItem->getElementsByTagName("DEVICEID")->item(0)->nodeValue;
							array_push($cpuIndex,$cpuDeviceId);
							$cpuArray[$cpuDeviceId]['maxClockSpeed']=$cpuItem->getElementsByTagName("MAXCLOCKSPEED")->item(0)->nodeValue;
							$cpuArray[$cpuDeviceId]['name']=$cpuItem->getElementsByTagName("NAME")->item(0)->nodeValue;
						}
						$cpuItemCount++;
						
						if(count($cpuIndex)>0){
							foreach($server->cpuIndex as $cpuId) {
								if(!in_array($cpuId,$cpuIndex)) {$cpuM->delete($cpuId);}
							}
						}
						
						foreach($cpuIndex as $cpuId) {
							if($cpuM->getId($cpuId)==0) {
								$cpuM->create($cpuId,$cpuArray[$cpuId]['name'],$cpuArray[$cpuId]['maxClockSpeed']);
							} else {
								$cpu = new cpu($cpuM->getId($cpuId));
								
								if($cpu->logicalId!=$cpuId || $cpu->maxClockSpeed!=$cpuArray[$cpuId]['maxClockSpeed'] || $cpu->name!=$cpuArray[$cpuId]['name']) {
									$cpuM->delete($cpu->id);
									$cpuM->create($cpuId,$cpuArray[$cpuId]['name'],$cpuArray[$cpuId]['maxClockSpeed']);
								}
							}
						}
					}
					unset($cpuIndex);
					unset($cpuArray);
					// !Cpus
					
					// Disks
					$diskIndex = array();
					$diskArray = array();
					$diskItemCount=0;
					while(is_object($diskItems = $item->getElementsByTagName("DISKS")->item($diskItemCount)))
					{
						foreach($diskItems->childNodes as $diskItem)
						{
							$diskName=$diskItem->getElementsByTagName("NAME")->item(0)->nodeValue;
							array_push($diskIndex,$diskName);
							$diskArray[$diskName]['interfaceType']=$diskItem->getElementsByTagName("INTERFACETYPE")->item(0)->nodeValue;
							$diskArray[$diskName]['size']=$diskItem->getElementsByTagName("SIZE")->item(0)->nodeValue;
							$diskArray[$diskName]['description']=$diskItem->getElementsByTagName("DESCRIPTION")->item(0)->nodeValue;
							$diskArray[$diskName]['firmwareRevision']=$diskItem->getElementsByTagName("FIRMWAREREVISION")->item(0)->nodeValue;
							$diskArray[$diskName]['manufacturer']=$diskItem->getElementsByTagName("MANUFACTURER")->item(0)->nodeValue;
							$diskArray[$diskName]['model']=$diskItem->getElementsByTagName("MODEL")->item(0)->nodeValue;
							$diskArray[$diskName]['serialNumber']=$diskItem->getElementsByTagName("SERIALNUMBER")->item(0)->nodeValue;
						}
						$diskItemCount++;
						
						if(count($diskIndex)>0){
							foreach($server->diskIndex as $diskName) {
								if(!in_array($diskName,$diskIndex)) {$diskM->delete($diskM->getId($diskName));}
							}
						}
	
						foreach($diskIndex as $diskName) {
							if($diskM->getId($diskName)==0) {
								$diskM->create($diskName,$diskArray[$diskName]['interfaceType'],$diskArray[$diskName]['size'],$diskArray[$diskName]['description'],$diskArray[$diskName]['firmwareRevision'],$diskArray[$diskName]['manufacturer'],$diskArray[$diskName]['model'],$diskArray[$diskName]['serialNumber']);
							} else {
								$disk = new disk($diskM->getId($diskName));
	
								if(	$disk->name!=$diskName || 
									$disk->interfaceType!=$diskArray[$diskName]['interfaceType'] || 
									$disk->totalSize!=$diskArray[$diskName]['size'] || 
									$disk->caption!=$diskArray[$diskName]['description'] || 
									$disk->firmwareRevision!=$diskArray[$diskName]['firmwareRevision'] || 
									$disk->manufacturer!=$diskArray[$diskName]['manufacturer'] || 
									$disk->model!=$diskArray[$diskName]['model'] || 
									$disk->serialNumber!=$diskArray[$diskName]['serialNumber']
								) {
									$diskM->delete($diskM->getId($diskName));
									$diskM->create($diskName,$diskArray[$diskName]['interfaceType'],$diskArray[$diskName]['size'],$diskArray[$diskName]['description'],$diskArray[$diskName]['firmwareRevision'],$diskArray[$diskName]['manufacturer'],$diskArray[$diskName]['model'],$diskArray[$diskName]['serialNumber']);
								}
							}
						}
					}
					unset($diskIndex);
					unset($diskArray);
					// !Disks
					
					// Fs
					$fsIndex = array();
					$fsArray = array();
					$fsItemCount=0;
					while(is_object($fsItems = $item->getElementsByTagName("FSS")->item($fsItemCount)))
					{
						foreach($fsItems->childNodes as $fsItem)
						{
							$fsName=$fsItem->getElementsByTagName("NAME")->item(0)->nodeValue;
							array_push($fsIndex,$fsName);
							$fsArray[$fsName]['name']=$fsItem->getElementsByTagName("NAME")->item(0)->nodeValue;
							$fsArray[$fsName]['fileSystem']=$fsItem->getElementsByTagName("FILESYSTEM")->item(0)->nodeValue;
							$fsArray[$fsName]['freeSpace']=$fsItem->getElementsByTagName("FREESPACE")->item(0)->nodeValue;
							$fsArray[$fsName]['size']=$fsItem->getElementsByTagName("SIZE")->item(0)->nodeValue;
							$fsArray[$fsName]['alias']=$fsItem->getElementsByTagName("ALIAS")->item(0)->nodeValue;
						}
						$fsItemCount++;
						
						if(count($fsIndex)>0){
							foreach($server->fsIndex as $fsName) {
								if(!in_array($fsName,$fsIndex)) {$fsM->delete($fsM->getId($fsName));}
							}
						}
	
						foreach($fsIndex as $fsName) {
							if($fsM->getId($fsName)==0) {
								$idFs=$fsM->create($fsName,$fsArray[$fsName]['fileSystem'],$fsArray[$fsName]['alias']);
							} else {
								$fs = new fs($fsM->getId($fsName));
	
								if(	$fs->name!=$fsName || 
									$fs->fileSystem!=$fsArray[$fsName]['fileSystem'] || 
									$fs->alias!=$fsArray[$fsName]['alias']
								) {
									$fsM->delete($fs->id);
									$idFs=$fsM->create($fsName,$fsArray[$fsName]['fileSystem'],$fsArray[$fsName]['alias']);
								}
							}
							$fs = new fs($fsM->getId($fsName));
							
							if($fsM->getSpaceUpdate($xmlDate,$fs->id)==0) {
								$fs->updateSpaceStatus($xmlDate,$fsArray[$fsName]['size'],$fsArray[$fsName]['freeSpace']);
							}
						}
					}
					unset($fsIndex);
					unset($fsArray);
					// !Fs
					
					// Services
					$serviceIndex = array();
					$serviceArray = array();
					$serviceItemCount=0;
					while(is_object($serviceItems = $item->getElementsByTagName("SERVICES")->item($serviceItemCount)))
					{
						foreach($serviceItems->childNodes as $serviceItem)
						{
							$serviceName=$serviceItem->getElementsByTagName("NAME")->item(0)->nodeValue;
							array_push($serviceIndex,$serviceName);
							$serviceArray[$serviceName]['name']=$serviceItem->getElementsByTagName("NAME")->item(0)->nodeValue;
							$serviceArray[$serviceName]['pathName']=$serviceItem->getElementsByTagName("PATHNAME")->item(0)->nodeValue;
							$serviceArray[$serviceName]['startMode']=$serviceItem->getElementsByTagName("STARTMODE")->item(0)->nodeValue;
							$serviceArray[$serviceName]['state']=$serviceItem->getElementsByTagName("STATE")->item(0)->nodeValue;
							$serviceArray[$serviceName]['startName']=$serviceItem->getElementsByTagName("STARTNAME")->item(0)->nodeValue;
						}
						$serviceItemCount++;
						
						if(count($serviceIndex)>0){
							foreach($server->serviceIndex as $serviceName) {
								if(!in_array($serviceName,$serviceIndex)) {$serviceM->delete($serviceM->getId($serviceName));}
							}
						}
	
						foreach($serviceIndex as $serviceName) {
							if($serviceM->getId($serviceName)==0) {
								$idService=$serviceM->create($xmlDate,$serviceName,$serviceArray[$serviceName]['pathName'],$serviceArray[$serviceName]['startMode'],$serviceArray[$serviceName]['state'],$serviceArray[$serviceName]['startName']);
							} else {
								$service = new service($serviceM->getId($serviceName));
	
								if(	$service->name!=$serviceName || 
									$service->pathName!=$serviceArray[$serviceName]['pathName'] || 
									$service->startMode!=$serviceArray[$serviceName]['startMode'] || 
									$service->serviceState!=$serviceArray[$serviceName]['state'] || 
									$service->owner!=$serviceArray[$serviceName]['startName']
								) {
									$serviceM->delete($service->id);
									$idService=$serviceM->create($xmlDate,$serviceName,$serviceArray[$serviceName]['pathName'],$serviceArray[$serviceName]['startMode'],$serviceArray[$serviceName]['state'],$serviceArray[$serviceName]['startName']);
								}
							}
						}
					}
					unset($serviceIndex);
					unset($serviceArray);
					// !Services
					
					// DB instances
					$dbInstanceIndex = array();
					$dbInstanceArray = array();
					
					// - Windows case
					$serviceItemCount=0;
					while(is_object($serviceItems = $item->getElementsByTagName("SERVICES")->item($serviceItemCount)))
					{
						foreach($serviceItems->childNodes as $serviceItem)
						{
							$serviceName=$serviceItem->getElementsByTagName("NAME")->item(0)->nodeValue;
							if(preg_match('#^OracleService#',$serviceName)&&strlen($serviceName)>13&&$serviceItem->getElementsByTagName("STARTMODE")->item(0)->nodeValue!='Disabled') {
								$dbName=substr($serviceName,13,strlen($serviceName)-13);
								if(!in_array($dbName,$dbInstanceIndex)) {
									array_push($dbInstanceIndex,$dbName);
									$dbInstanceArray[$dbName]['type']='oracle';
									$dbInstanceArray[$dbName]['path']=$serviceItem->getElementsByTagName("PATHNAME")->item(0)->nodeValue;
								}
							}
							//elseif(preg_match('#^OracleService#',$serviceName)&&strlen($serviceName)>13){array_push($mssqlDbInstanceIndex,$serviceName);}
						}
						$serviceItemCount++;
					}
					// - Linux case
					if(isset($osItem->getElementsByTagName("ETC_ORATAB")->item(0)->nodeValue)){
						$etcOratabArray=explode("\n",$osItem->getElementsByTagName("ETC_ORATAB")->item(0)->nodeValue);
						
						foreach($etcOratabArray as $line){
							$line=trim($line);
							if($line!='' && !preg_match('/^#/',$line)) {
								$dbName=substr($line,0,strpos($line,':'));
								
								if(!in_array($dbName,$dbInstanceIndex)) {
									array_push($dbInstanceIndex,$dbName);
									$dbInstanceArray[$dbName]['type']='oracle';
									$dbInstanceArray[$dbName]['path']=substr($line,strpos($line,':')+1,strpos($line,':',strlen($dbName)+1));
								}
							}
						}
					}
					
					// Delete old db instance
					//if(count($diskIndex)>0){
					//	foreach($server->diskIndex as $diskName) {
					//		if(!in_array($diskName,$diskIndex)) {$diskM->delete($diskM->getId($diskName));}
					//	}
					//}
					
					// Create the db instance in the DB
					foreach($dbInstanceIndex as $dbInstanceName){
						if($dbInstanceM->getId($dbInstanceName)==0) {
							$dbInstanceM->create($dbInstanceName,$dbInstanceArray[$dbInstanceName]['type'],'','',$dbInstanceArray[$dbInstanceName]['path']);
						}
					}
					
					unset($dbInstanceIndex);
					unset($dbInstanceArray);
					//$databaseInstancesIndex=array();
					//$databaseInstancesArray=array();
					//foreach($servicesIndex as $service){if(substr($service,0,13)=='OracleService' && strlen($service)>13 && $servicesArray[$service]['STARTMODE'] != 'Disabled'){array_push($databaseInstancesIndex, substr($service,13,strlen($service)-13));}}
			
					//foreach($server->database_instances_index as $databaseInstance){if(!in_array($databaseInstance,$databaseInstancesIndex)){$server->delete_database_instances($databaseInstance);}}
					//foreach($databaseInstancesIndex as $databaseInstance){if($server->database_instances_exist($databaseInstance)==0){$server->add_database_instances($databaseInstance);}}
					// !DB instances
				} else {
					// TODO
					// LOG ERROR
				}
			}
			$osItemCount++;
		}
	}
	$itemCount++;
}
?>