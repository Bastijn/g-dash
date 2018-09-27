<?php
//Only allow this script to run from PHP CLI, not from HTTP
if (php_sapi_name() == "cli") {
	require_once(__DIR__.'/../../config/config.php');
	require_once(__DIR__.'/../../lib/settings/settings.php');
	require_once(__DIR__.'/../../lib/functions/functions.php');
	require_once(__DIR__.'/../../lib/EasyGulden/easygulden.php');
	
	//Connect to Gulden
	$gulden = new Gulden(KeyGet($CONFIG, '', 'rpcuser'),KeyGet($CONFIG, '', 'rpcpass'),KeyGet($CONFIG, '127.0.0.1', 'rpchost'),KeyGet($CONFIG, '9232', 'rpcport'));
	
	//Get the internal IP address
	$internalip = trim(shell_exec("ip addr | grep 'state UP' -A2 | tail -n1 | awk '{print $2}' | cut -f1 -d'/'"));
	
	//Are we currently connected to a requested IP address
	if(KeyGet($CONFIG, '', 'noderequest','node')!='') {
		//Has the 24 hours passed?
		$nodereqtime = time() - KeyGet($CONFIG, '', 'noderequest','time');
		if($nodereqtime > (60*60*24)) {
			//Yes, remove the IP from the node list and reset the config
			$ginfo = $gulden->addnode(KeyGet($CONFIG, '', 'noderequest','node'), 'remove');
			KeySet($CONFIG, '', 'noderequest','node');
			KeyGet($CONFIG, '', 'noderequest','time');
		} //If not, keep connected and do nothing
	} else {
		//Check if an IP has requested to be added by other nodes
		$checkrequests = @json_decode(file_get_contents("https://g-dash.nl/noderequestlist.php?ip=".$internalip));
		
		//If this returns zero there are no requests pending
		if($checkrequests->status != "ZERO") {
			//Check if the status returns OK
			if($checkrequests->status == "OK") {
				//Get the IP and add it
				$reqiptoadd = $checkrequests->nodereq;
				$ginfo = $gulden->addnode($reqiptoadd, 'add');
				
				//Set the config variables
				KeySet($CONFIG, $reqiptoadd, 'noderequest','node');
				KeySet($CONFIG, time(), 'noderequest','time');
			}
		}
	}
	
	//Finally, update the config file
	file_put_contents(__DIR__.'/../../config/config.php', '<?php $CONFIG = '.var_export($CONFIG, true).'; ?>');
	
}
?>
