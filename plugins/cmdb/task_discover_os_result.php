<?php
// Parse the data
$itemCount=0;
while(is_object($items = $item_DATA->getElementsByTagName("SERVERS")->item($itemCount)))
{
	foreach($items->childNodes as $item)
	{
		$hostname=$item->getElementsByTagName("NAME")->item(0)->nodeValue;
		
		$serverStatus=$item->getElementsByTagName("CONNECT_STATUS")->item(0)->nodeValue;
		$serverCode=$item->getElementsByTagName("CONNECT_CODE")->item(0)->nodeValue;
		
		//
		$devM = new deviceManager();
		
		$dev = new device($devM->getId('server',$hostname));
		
		$server = new server($dev->id);
		
		$osM = new osManager($dev->id);
		
		
		$osItemCount=0;
		while(is_object($osItems = $item->getElementsByTagName("OSES")->item($osItemCount)))
		{
			foreach($osItems->childNodes as $osItem)
			{
				$osName=$osItem->getElementsByTagName("NAME")->item(0)->nodeValue;
				$osPort=$osItem->getElementsByTagName("PORT")->item(0)->nodeValue;
				
				$osStatus=$osItem->getElementsByTagName("CONNECT_STATUS")->item(0)->nodeValue;
				$osCode=$osItem->getElementsByTagName("CONNECT_CODE")->item(0)->nodeValue;
				
				if($osM->getId($osName,$osPort)==0) {
					$osM->create($osName,$osPort);
				}
			}
			$osItemCount++;
		}
	}
	$itemCount++;
}
?>