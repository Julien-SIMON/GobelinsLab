<?php
// Require the SSH class for Linux/Unix connection
$include_path=get_include_path();
set_include_path($include_path . PATH_SEPARATOR . 'lib/phpSecLib/1.0.0');
require('lib/phpSecLib/1.0.0/Net/SSH2.php');
//set_include_path($include_path);
define('NET_SSH2_LOGGING', NET_SSH2_LOG_COMPLEX);

// Parse the data in array
$itemCount=0;
while(is_object($items = $item_DATA->getElementsByTagName("SERVERS")->item($itemCount)))
{
	foreach($items->childNodes as $item)
	{
		$hostname=$item->getElementsByTagName("NAME")->item(0)->nodeValue;
		
		$devM = new deviceManager();
		
		$dev = new device($devM->getId('server',$hostname));
		
		$server = new server($dev->id);
		
		$xmlFileContent='';
		$xmlFileContent.=
		'<?xml version="1.0" encoding="UTF-8"?>
		<XML>
		<HEADER>
		<PPID>1</PPID>
		<DATE></DATE>
		<TIMEOUT>120</TIMEOUT>
		<PLUGIN>cmdb</PLUGIN>
		<PAGE>task_audit_os_result</PAGE>
		<COMMENT><![CDATA[]]></COMMENT>
		</HEADER>
		<DATA>
			<SERVERS>
				<SERVER>
					<NAME>'.$dev->name.'</NAME>
					<OSES>
		';
		
		foreach($server->osIndex AS $osId) {
			$os = new os($osId);
			
			if($os->type=='linux') { // Open the SSH connection
				$ssh = new Net_SSH2($dev->name);
				
				if (!$ssh->login($os->creds['login'], $os->creds['password'])) {
					// TODO => Log an error
					unset($ssh);
				}
			}
			
			$xmlFileContent.=
			'
			<OS>
			<TYPE>'.$os->type.'</TYPE>
			<PORT>'.$os->port.'</PORT>
			';

//------------ NAME -------------
			// Windows case
			if($os->type == 'windows') {
				if($os->creds['login']=='' || $os->creds['password']=='') {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem).Caption"';
				} else {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem -Credential (New-Object System.Management.Automation.PSCredential -ArgumentList '.$os->creds['login'].', (convertto-securestring '.$os->creds['password'].' -asplaintext -force))).Caption"';
				}
				$output=getWmiObject($cmd);
				// TODO => if = ERROR => Log an error => Stop treatment => Do not create result file
				$xmlFileContent.='<NAME><![CDATA['.$output[0].']]></NAME>';
			} else { // Linux case
				if (!isset($ssh)) {
					$xmlFileContent.='<NAME>ERROR</NAME>';
				} else {
					$xmlFileContent.='<NAME><![CDATA['.trim($ssh->exec('cat /etc/redhat-release')).']]></NAME>';
				}
			}
//------------ !NAME ------------			
//------------ VERSION -------------
			// Windows case
			if($os->type == 'windows') {
				if($os->creds['login']=='' || $os->creds['password']=='') {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem).Version"';
				} else {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem -Credential (New-Object System.Management.Automation.PSCredential -ArgumentList '.$os->creds['login'].', (convertto-securestring '.$os->creds['password'].' -asplaintext -force))).Version"';
				}
				$output=getWmiObject($cmd);
				
				$xmlFileContent.='<VERSION><![CDATA['.trim($output[0]).']]></VERSION>';
			} else { // Linux case
				if (!isset($ssh)) {
					$xmlFileContent.='<VERSION>ERROR</VERSION>';
				} else {
					$xmlFileContent.='<VERSION><![CDATA['.trim($ssh->exec('uname -r')).']]></VERSION>';
				}
			}
//------------ !VERSION ------------
//------------ ARCHITECTURE -------------
			// Windows case
			if($os->type == 'windows') {
				if($os->creds['login']=='' || $os->creds['password']=='') {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem).OSArchitecture"';
				} else {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem -Credential (New-Object System.Management.Automation.PSCredential -ArgumentList '.$os->creds['login'].', (convertto-securestring '.$os->creds['password'].' -asplaintext -force))).OSArchitecture"';
				}
				$output=getWmiObject($cmd);
				
				$xmlFileContent.='<ARCHITECTURE><![CDATA['.trim($output[0]).']]></ARCHITECTURE>';
			} else { // Linux case
				if (!isset($ssh)) {
					$xmlFileContent.='<ARCHITECTURE>ERROR</ARCHITECTURE>';
				} else {
					$xmlFileContent.='<ARCHITECTURE><![CDATA['.trim($ssh->exec('uname -i')).']]></ARCHITECTURE>';
				}
			}
//------------ !ARCHITECTURE ------------
//------------ SERIAL NUMBER -------------
			// Windows case
			if($os->type == 'windows') {
				if($os->creds['login']=='' || $os->creds['password']=='') {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem).SerialNumber"';
				} else {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem -Credential (New-Object System.Management.Automation.PSCredential -ArgumentList '.$os->creds['login'].', (convertto-securestring '.$os->creds['password'].' -asplaintext -force))).SerialNumber"';
				}
				$output=getWmiObject($cmd);
				
				$xmlFileContent.='<SERIALNUMBER><![CDATA['.trim($output[0]).']]></SERIALNUMBER>';
			} 
//------------ !SERIAL NUMBER ------------
//------------ INSTALLDATE -------------
			// Windows case
			if($os->type == 'windows') {
				if($os->creds['login']=='' || $os->creds['password']=='') {
					$cmd = 'powershell "([System.DateTime]::ParseExact((Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem).InstallDate.split(\'.\')[0],\'yyyyMMddHHmmss\',$null)).ToString(\'dd/MM/yyyy HH:mm:ss\')"';
				} else {
					$cmd = 'powershell "([System.DateTime]::ParseExact((Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem -Credential (New-Object System.Management.Automation.PSCredential -ArgumentList '.$os->creds['login'].', (convertto-securestring '.$os->creds['password'].' -asplaintext -force))).InstallDate.split(\'.\')[0],\'yyyyMMddHHmmss\',$null)).ToString(\'dd/MM/yyyy HH:mm:ss\')"';
				}
				$output=getWmiObject($cmd);
				
				$xmlFileContent.='<INSTALLDATE><![CDATA['.trim($output[0]).']]></INSTALLDATE>';
			}
//------------ !INSTALLDATE ------------
//------------ LASTBOOTDATE -------------
			// Windows case
			if($os->type == 'windows') {
				if($os->creds['login']=='' || $os->creds['password']=='') {
					$cmd = 'powershell "([System.DateTime]::ParseExact((Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem).LastBootUpTime.split(\'.\')[0],\'yyyyMMddHHmmss\',$null)).ToString(\'dd/MM/yyyy HH:mm:ss\')"';
				} else {
					$cmd = 'powershell "([System.DateTime]::ParseExact((Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem -Credential (New-Object System.Management.Automation.PSCredential -ArgumentList '.$os->creds['login'].', (convertto-securestring '.$os->creds['password'].' -asplaintext -force))).LastBootUpTime.split(\'.\')[0],\'yyyyMMddHHmmss\',$null)).ToString(\'dd/MM/yyyy HH:mm:ss\')"';
				}
				$output=getWmiObject($cmd);
				
				$xmlFileContent.='<LASTBOOTDATE><![CDATA['.trim($output[0]).']]></LASTBOOTDATE>';
			} else { // Linux case
				if (!isset($ssh)) {
					$xmlFileContent.='<LASTBOOTDATE>ERROR</LASTBOOTDATE>';
				} else {
					$xmlFileContent.='<LASTBOOTDATE><![CDATA['.trim($ssh->exec('who -b | awk \'{print $3" "$4":00";}\'')).']]></LASTBOOTDATE>';
				}
			}
//------------ !LASTBOOTDATE ------------
//------------ MEMORYSIZE -------------
			// Windows case
			if($os->type == 'windows') {
				if($os->creds['login']=='' || $os->creds['password']=='') {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem).TotalVisibleMemorySize"';
				} else {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_OperatingSystem -Credential (New-Object System.Management.Automation.PSCredential -ArgumentList '.$os->creds['login'].', (convertto-securestring '.$os->creds['password'].' -asplaintext -force))).TotalVisibleMemorySize"';
				}
				$output=getWmiObject($cmd);
				
				$xmlFileContent.='<MEMORYSIZE><![CDATA['.trim($output[0]).']]></MEMORYSIZE>';
			} else { // Linux case
				if (!isset($ssh)) {
					$xmlFileContent.='<MEMORYSIZE>ERROR</MEMORYSIZE>';
				} else {
					$xmlFileContent.='<MEMORYSIZE><![CDATA['.trim($ssh->exec('free | grep Mem: | awk \'{print $2;}\'')).']]></MEMORYSIZE>';
				}
			}
//------------ !MEMORYSIZE ------------
//------------ CPU -------------
		
			// Windows case
			if($os->type == 'windows') {
				if($os->creds['login']=='' || $os->creds['password']=='') {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_Processor | select DEVICEID,MAXCLOCKSPEED,Name | FORMAT-LIST)"';
				} else {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_Processor -Credential (New-Object System.Management.Automation.PSCredential -ArgumentList '.$os->creds['login'].', (convertto-securestring '.$os->creds['password'].' -asplaintext -force))| select DEVICEID,MAXCLOCKSPEED,Name | FORMAT-LIST)"';
				}
				$output=getWmiObject($cmd);
				
				$xmlFileContent.=
				'
				<CPUS>
				';
				
				foreach($output as $line) {
					if(preg_match('# : #',$line)) {
						$temp=explode(' : ',$line);
							
						if(strtoupper(substr($temp[0],0,8))=='DEVICEID') {
							$xmlFileContent.=
							'
					<CPU>
						<DEVICEID>'.$temp[1].'</DEVICEID>
							';
						}
						elseif(strtoupper(substr($temp[0],0,13))=='MAXCLOCKSPEED') {
							$xmlFileContent.=
							'
						<MAXCLOCKSPEED>'.$temp[1].'</MAXCLOCKSPEED>
							';
						}
						elseif(strtoupper(substr($temp[0],0,4))=='NAME') {
							$xmlFileContent.=
							'
						<NAME><![CDATA['.$temp[1].']]></NAME>
					</CPU>
							';
						}
					}
				}
				
				$xmlFileContent.=
				'
				</CPUS>
				';
			} else { // Linux case
				if (isset($ssh)) {
					$output = trim($ssh->exec('cat /proc/cpuinfo | egrep "^(processor|cpu MHz|model name)"'));
					
					$output = explode("\n",$output);
					
					$xmlFileContent.=
					'
					<CPUS>
					';
					
					foreach($output as $line) {
						
						if(preg_match('#:#',$line)) {
							$temp=explode(':',$line);
							
							if(strtoupper(substr(trim($temp[0]),0,9))=='PROCESSOR') {
								$xmlFileContent.=
								'
						<CPU>
							<DEVICEID>'.trim($temp[1]).'</DEVICEID>
								';
							}
							elseif(strtoupper(substr(trim($temp[0]),0,7))=='CPU MHZ') {
								$xmlFileContent.=
								'
							<MAXCLOCKSPEED>'.trim($temp[1]).'</MAXCLOCKSPEED>
						</CPU>
								';
							}
							elseif(strtoupper(substr(trim($temp[0]),0,10))=='MODEL NAME') {
								$xmlFileContent.=
								'
							<NAME><![CDATA['.trim($temp[1]).']]></NAME>
								';
							}
						}
					}
					
					$xmlFileContent.=
					'
					</CPUS>
					';
				}
			}
//------------ !CPU ------------
//------------ DISK -------------

			
			// Windows case
			if($os->type == 'windows') {
				if($os->creds['login']=='' || $os->creds['password']=='') {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_DiskDrive | Select-Object DeviceID,InterfaceType,Size,Caption,FirmwareRevision,Manufacturer,Model,SerialNumber | format-list)"';
				} else {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_DiskDrive -Credential (New-Object System.Management.Automation.PSCredential -ArgumentList '.$os->creds['login'].', (convertto-securestring '.$os->creds['password'].' -asplaintext -force))| Select-Object DeviceID,InterfaceType,Size,Caption,FirmwareRevision,Manufacturer,Model,SerialNumber | format-list)"';
				}
				$output=getWmiObject($cmd);
				
				$xmlFileContent.=
				'
				<DISKS>
				';
				
				foreach($output as $line) {
					if(preg_match('# :#',$line)) {
						$temp=explode(' :',$line);
					
						if(strtoupper(substr($temp[0],0,8))=='DEVICEID') {
							$xmlFileContent.=
							'
					<DISK>
						<NAME><![CDATA['.utf8_encode(trim($temp[1])).']]></NAME>
							';
						}
						elseif(strtoupper(substr($temp[0],0,13))=='INTERFACETYPE') {
							$xmlFileContent.=
							'
						<INTERFACETYPE>'.utf8_encode(trim($temp[1])).'</INTERFACETYPE>
							';
						}
						elseif(strtoupper(substr($temp[0],0,4))=='SIZE') {
							$xmlFileContent.=
							'
						<SIZE>'.utf8_encode(trim($temp[1])).'</SIZE>
							';
						}
						elseif(strtoupper(substr($temp[0],0,7))=='CAPTION') {
							$xmlFileContent.=
							'
						<DESCRIPTION><![CDATA['.utf8_encode(trim($temp[1])).']]></DESCRIPTION>
							';
						}
						elseif(strtoupper(substr($temp[0],0,16))=='FIRMWAREREVISION') {
							$xmlFileContent.=
							'
						<FIRMWAREREVISION>'.utf8_encode(trim($temp[1])).'</FIRMWAREREVISION>
							';
						}
						elseif(strtoupper(substr($temp[0],0,12))=='MANUFACTURER') {
							$xmlFileContent.=
							'
						<MANUFACTURER><![CDATA['.utf8_encode(trim($temp[1])).']]></MANUFACTURER>
							';
						}
						elseif(strtoupper(substr($temp[0],0,5))=='MODEL') {
							$xmlFileContent.=
							'
						<MODEL>'.utf8_encode(trim($temp[1])).'</MODEL>
							';
						}
						elseif(strtoupper(substr($temp[0],0,12))=='SERIALNUMBER') {
							$xmlFileContent.=
							'
						<SERIALNUMBER>'.utf8_encode(trim($temp[1])).'</SERIALNUMBER>
					</DISK>
							';
						}
					}
				}
				
				$xmlFileContent.=
				'
				</DISKS>
				';
			}/* else { // Linux case
				if (isset($ssh)) {
					$output = trim($ssh->exec('cat /proc/scsi/scsi'));
					
					$output = explode("\n",$output);
					
					$xmlFileContent.=
					'
						<DISK>
					';
					
					foreach($output as $line) {
						if(preg_match('#:#',$line)) {
							$temp=explode(':',$line);
						
							if(strtoupper(substr($temp[0],0,8))=='DEVICEID') {
								$xmlFileContent.=
								'
						<DISK>
							<NAME><![CDATA['.utf8_encode(trim($temp[1])).']]></NAME>
								';
							}
							elseif(strtoupper(substr($temp[0],0,13))=='INTERFACETYPE') {
								$xmlFileContent.=
								'
							<INTERFACETYPE>'.utf8_encode(trim($temp[1])).'</INTERFACETYPE>
								';
							}
							elseif(strtoupper(substr($temp[0],0,4))=='SIZE') {
								$xmlFileContent.=
								'
							<SIZE>'.utf8_encode(trim($temp[1])).'</SIZE>
								';
							}
							elseif(strtoupper(substr($temp[0],0,7))=='CAPTION') {
								$xmlFileContent.=
								'
							<DESCRIPTION><![CDATA['.utf8_encode(trim($temp[1])).']]></DESCRIPTION>
								';
							}
							elseif(strtoupper(substr($temp[0],0,16))=='FIRMWAREREVISION') {
								$xmlFileContent.=
								'
							<FIRMWAREREVISION>'.utf8_encode(trim($temp[1])).'</FIRMWAREREVISION>
								';
							}
							elseif(strtoupper(substr($temp[0],0,12))=='MANUFACTURER') {
								$xmlFileContent.=
								'
							<MANUFACTURER><![CDATA['.utf8_encode(trim($temp[1])).']]></MANUFACTURER>
								';
							}
							elseif(strtoupper(substr($temp[0],0,5))=='MODEL') {
								$xmlFileContent.=
								'
							<MODEL>'.utf8_encode(trim($temp[1])).'</MODEL>
								';
							}
							elseif(strtoupper(substr($temp[0],0,12))=='SERIALNUMBER') {
								$xmlFileContent.=
								'
							<SERIALNUMBER></SERIALNUMBER>
						</DISK>
								';
							}
						}
					}
					$xmlFileContent.=
					'
						</DISK>
					';
				}
			}*/
			

//------------ !DISK ------------
//------------ PARTITIONS -------------

			
			// Windows case
			if($os->type == 'windows') {
				if($os->creds['login']=='' || $os->creds['password']=='') {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class win32_logicaldisk | Where-Object { $_.DriveType -eq 3 } | Select-Object Name,FileSystem,FreeSpace,Size,VolumeName | format-list)"';
				} else {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class win32_logicaldisk -Credential (New-Object System.Management.Automation.PSCredential -ArgumentList '.$os->creds['login'].', (convertto-securestring '.$os->creds['password'].' -asplaintext -force))| Where-Object { $_.DriveType -eq 3 } | Select-Object Name,FileSystem,FreeSpace,Size,VolumeName | format-list)"';
				}
				$output=getWmiObject($cmd);
				
				$xmlFileContent.=
				'
				<FSS>
				';
				
				foreach($output as $line) {
					if(preg_match('# :#',$line)) {
						$temp=explode(' :',$line);
					
						if(strtoupper(substr($temp[0],0,4))=='NAME') {
							$xmlFileContent.=
							'
					<FS>
						<NAME><![CDATA['.trim($temp[1]).']]></NAME>
							';
						}
						elseif(strtoupper(substr($temp[0],0,10))=='FILESYSTEM') {
							$xmlFileContent.=
							'
						<FILESYSTEM>'.trim($temp[1]).'</FILESYSTEM>
							';
						}
						elseif(strtoupper(substr($temp[0],0,9))=='FREESPACE') {
							$xmlFileContent.=
							'
						<FREESPACE>'.trim($temp[1]).'</FREESPACE>
							';
						}
						elseif(strtoupper(substr($temp[0],0,4))=='SIZE') {
							$xmlFileContent.=
							'
						<SIZE>'.trim($temp[1]).'</SIZE>
							';
						}
						elseif(strtoupper(substr($temp[0],0,10))=='VOLUMENAME') {
							$xmlFileContent.=
							'
						<ALIAS><![CDATA['.utf8_encode(trim($temp[1])).']]></ALIAS>
					</FS>
							';
						}
					}
				}
				
				$xmlFileContent.=
				'
				</FSS>
				';
			}/* else { // Linux case
				if (isset($ssh)) {
					$output = trim($ssh->exec('cat /proc/scsi/scsi'));
					
					$output = explode("\n",$output);
					
					$xmlFileContent.=
					'
						<PARTITION>
					';
					
					foreach($output as $line) {
						if(preg_match('#:#',$line)) {
							$temp=explode(':',$line);
						
							if(strtoupper(substr($temp[0],0,8))=='DEVICEID') {
								$xmlFileContent.=
								'
						<PARTITION>
							<NAME><![CDATA['.utf8_encode(trim($temp[1])).']]></NAME>
								';
							}
							elseif(strtoupper(substr($temp[0],0,13))=='INTERFACETYPE') {
								$xmlFileContent.=
								'
							<INTERFACETYPE>'.utf8_encode(trim($temp[1])).'</INTERFACETYPE>
								';
							}
							elseif(strtoupper(substr($temp[0],0,4))=='SIZE') {
								$xmlFileContent.=
								'
							<SIZE>'.utf8_encode(trim($temp[1])).'</SIZE>
								';
							}
							elseif(strtoupper(substr($temp[0],0,7))=='CAPTION') {
								$xmlFileContent.=
								'
							<DESCRIPTION><![CDATA['.utf8_encode(trim($temp[1])).']]></DESCRIPTION>
								';
							}
							elseif(strtoupper(substr($temp[0],0,16))=='FIRMWAREREVISION') {
								$xmlFileContent.=
								'
							<FIRMWAREREVISION>'.utf8_encode(trim($temp[1])).'</FIRMWAREREVISION>
								';
							}
							elseif(strtoupper(substr($temp[0],0,12))=='MANUFACTURER') {
								$xmlFileContent.=
								'
							<MANUFACTURER><![CDATA['.utf8_encode(trim($temp[1])).']]></MANUFACTURER>
								';
							}
							elseif(strtoupper(substr($temp[0],0,5))=='MODEL') {
								$xmlFileContent.=
								'
							<MODEL>'.utf8_encode(trim($temp[1])).'</MODEL>
								';
							}
							elseif(strtoupper(substr($temp[0],0,12))=='SERIALNUMBER') {
								$xmlFileContent.=
								'
							<SERIALNUMBER></SERIALNUMBER>
						</DISK>
								';
							}
						}
					}
					$xmlFileContent.=
					'
						</PARTITION>
					';
				}
			}*/
			

//------------ !PARTITIONS ------------
//------------ SERVICES -------------
			
			// Windows case
			if($os->type == 'windows') {
				if($os->creds['login']=='' || $os->creds['password']=='') {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_service | Select-Object Name,PathName,StartMode,State,StartName)"';
				} else {
					$cmd = 'powershell "(Get-WmiObject -ComputerName '.$dev->name.' -class Win32_service -Credential (New-Object System.Management.Automation.PSCredential -ArgumentList '.$os->creds['login'].', (convertto-securestring '.$os->creds['password'].' -asplaintext -force))| Select-Object Name,PathName,StartMode,State,StartName)"';
				}
				$output=getWmiObject($cmd);
				
				$xmlFileContent.=
				'
				<SERVICES>
				';
			
				foreach($output as $line) {
					if(preg_match('# : #',$line)) {
						$temp=explode(' : ',$line);
					
						if(strtoupper(substr($temp[0],0,4))=='NAME') {
							$xmlFileContent.=
							'
					<SERVICE>
						<NAME><![CDATA['.utf8_encode(trim($temp[1])).']]></NAME>
							';
						}
						elseif(strtoupper(substr($temp[0],0,8))=='PATHNAME') {
							$xmlFileContent.=
							'
						<PATHNAME><![CDATA['.utf8_encode(trim($temp[1])).']]></PATHNAME>
							';
						}
						elseif(strtoupper(substr($temp[0],0,9))=='STARTMODE') {
							$xmlFileContent.=
							'
						<STARTMODE>'.utf8_encode(trim($temp[1])).'</STARTMODE>
							';
						}
						elseif(strtoupper(substr($temp[0],0,5))=='STATE') {
							$xmlFileContent.=
							'
						<STATE>'.utf8_encode(trim($temp[1])).'</STATE>
							';
						}
						elseif(strtoupper(substr($temp[0],0,9))=='STARTNAME') {
							$xmlFileContent.=
							'
						<STARTNAME><![CDATA['.utf8_encode(trim($temp[1])).']]></STARTNAME>
					</SERVICE>
							';
						}
					}
				}
				
				$xmlFileContent.=
				'
				</SERVICES>
				';
			
			}
//------------ !SERVICES ------------
//------------ /etc/oratab -------------
			
			// Linux case
			if($os->type == 'linux' && isset($ssh)) {
				$output = trim($ssh->exec('if [ -f /etc/oratab ];then cat /etc/oratab;fi'));
			
				$xmlFileContent.=
				'
				<ETC_ORATAB><![CDATA[
				'.$output.'
				]]></ETC_ORATAB>
				';
			}
//------------ !/etc/oratab ------------
			$xmlFileContent.=
			'
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
		
		file_put_contents('upload/queue/task_audit_os_result_'.$dev->name.'_'.date('Ymd').'.xml', $xmlFileContent);
	}
	$itemCount++;
}
?>