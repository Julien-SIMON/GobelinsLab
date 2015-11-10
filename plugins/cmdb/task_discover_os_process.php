<?php
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
		
		$osM = new osManager($dev->id);
		
		// Test ssh and tse port
 		$os='';
 		$osCode='';
 		$osStatus='';
		$socket = fsockopen($hostname, 22);
		if($socket) {
			$os='linux';
			$port='22';
		}
		$socket = fsockopen($hostname, 3389);
		if($socket) {
			$os='windows';
			$port='3389';
		}
		
		if($os=='') {
 			$osCode='1001';
 			$osStatus='No process listen on 22 or 3389 port.';
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
		<PAGE>task_discover_os_result</PAGE>
		<COMMENT><![CDATA[]]></COMMENT>
		</HEADER>
		<DATA>
			<SERVERS>
				<SERVER>
		';
		$xmlFileContent.=
		'<NAME>'.$hostname.'</NAME>
		<CONNECT_STATUS>'.$osStatus.'</CONNECT_STATUS>
		<CONNECT_CODE>'.$osCode.'</CONNECT_CODE>
		
		
		';
		if($os!='') {
			$xmlFileContent.=
			'<OSES>
			<OS>
			<NAME>'.$os.'</NAME>
			<PORT>'.$port.'</PORT>
			<CONNECT_STATUS></CONNECT_STATUS>
			<CONNECT_CODE></CONNECT_CODE>
			</OS>
			</OSES>
			';
		}
		$xmlFileContent.=
		'
		
				</SERVER>
			</SERVERS>
		</DATA>
		</XML>
		';
		
		file_put_contents('upload/queue/task_discover_os_'.$hostname.'_result.xml', $xmlFileContent);
	}
	$itemCount++;
}
?>