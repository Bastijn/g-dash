<?php
//Only allow this script to run from PHP CLI, not from HTTP
if (php_sapi_name() == "cli") {
	require_once(__DIR__.'/../../config/config.php');
	require_once(__DIR__.'/../../lib/settings/settings.php');
	require_once(__DIR__.'/../../lib/functions/functions.php');
	require_once(__DIR__.'/../../lib/EasyGulden/easygulden.php');
	
	//Connect to Gulden
	$gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
	
	//Get the latest version info for G-DASH and Gulden
	$latestversionsarray = array();
	$latestversionsarray = @json_decode(file_get_contents($GDASH['updatecheck']));
	
	//Check if Gulden server is running
	if($CONFIG['pushbulletgulden']['active']=="1") {
		
		//Get the info (last message and current message)
		$lastmessage = $CONFIG['pushbulletgulden']['lastmes'];
		$currentmessage = "";
		if($gulden->getinfo()=="") {
			$currentmessage = "Gulden server is not running!";
		} else {
			$currentmessage = "Gulden server is up and running!";
		}
		
		//Check the last message that was pushed to prevent multiple pushes of the same message
		if($lastmessage!=$currentmessage) {
			
			//The message is different, send a push notification
			$sendpush = shell_exec("curl --header 'Authorization: Bearer ".$CONFIG['pushbullet']."' -X POST https://api.pushbullet.com/v2/pushes --header 'Content-Type: application/json' --data-binary '{\"type\": \"note\", \"title\": \"Gulden Server\", \"body\": \"".$currentmessage."\"}'");
			
			//Set the current message as the last message in the config file
			$CONFIG['pushbulletgulden']['lastmes'] = $currentmessage;
			
			//Update the config file
			file_put_contents(__DIR__.'/../../config/config.php', '<?php $CONFIG = '.var_export($CONFIG, true).'; ?>');
		}
	}

	//Check if there is a newer version of Gulden in the repository
	if($CONFIG['pushbulletguldenupdate']['active']=="1") {
		
		//Get the info (last message and current message)
		$lastmessage = $CONFIG['pushbulletguldenupdate']['lastmes'];
		$currentmessage = "";
		$ginfo = $gulden->getinfo();
		$guldenversion = $latestversionsarray->gulden;
		if($ginfo !="") {
			$currentguldenversion = $ginfo['version'];
			if($currentguldenversion < $guldenversion) {
				$currentmessage = "A new version of Gulden is available ($guldenversion)";
			}
		}
		
		//Check the last message that was pushed to prevent multiple pushes of the same message
		if($lastmessage!=$currentmessage && $currentmessage!="") {
			
			//The message is different, send a push notification
			$sendpush = shell_exec("curl --header 'Authorization: Bearer ".$CONFIG['pushbullet']."' -X POST https://api.pushbullet.com/v2/pushes --header 'Content-Type: application/json' --data-binary '{\"type\": \"note\", \"title\": \"Gulden Update\", \"body\": \"".$currentmessage."\"}'");
			
			//Set the current message as the last message in the config file
			$CONFIG['pushbulletguldenupdate']['lastmes'] = $currentmessage;
			
			//Update the config file
			file_put_contents(__DIR__.'/../../config/config.php', '<?php $CONFIG = '.var_export($CONFIG, true).'; ?>');
		}
	}

	//Check if there is a new version of G-DASH available
	if($CONFIG['pushbulletgdash']['active']=="1") {
		
		//Get the info (last message and current message)
		$lastmessage = $CONFIG['pushbulletgdash']['lastmes'];
		$currentmessage = "";
		
		//What is the current version of G-DASH
		$currentversion = $GDASH['currentversion'];
		
		//Check which version is the latest version of G-DASH	  
		if($CONFIG['updatechannel']=="1") {
			$getlatestversion = $latestversionsarray->beta;
		} else {
			$getlatestversion = $latestversionsarray->stable;
		}
		
		//Set the message
		$currentmessage = "Latest version is ".$getlatestversion.". You are currently running ".$currentversion;
		
		//Check the last message that was pushed to prevent multiple pushes of the same message
		if($getlatestversion > $currentversion) {
			
			//The message is different, send a push notification
			$sendpush = shell_exec("curl --header 'Authorization: Bearer ".$CONFIG['pushbullet']."' -X POST https://api.pushbullet.com/v2/pushes --header 'Content-Type: application/json' --data-binary '{\"type\": \"note\", \"title\": \"G-DASH update available\", \"body\": \"".$currentmessage."\"}'");
			
			//Set the current message as the last message in the config file
			$CONFIG['pushbulletgdash']['lastmes'] = $currentmessage;
			
			//Update the config file
			file_put_contents(__DIR__.'/../../config/config.php', '<?php $CONFIG = '.var_export($CONFIG, true).'; ?>');
		}
	}

	//Notification if there is a new incoming transaction
	if($CONFIG['pushbullettx']['active']=="1") {
		
		//Create a list of addresses belonging to this wallet
		$addresslistrpc = $gulden->listreceivedbyaddress(0, true);
		$addresslist = array_column($addresslistrpc, "address");
		
		//Get the latest transaction for all accounts
		$accounttoshowtx = "*";
		$numoftransactionstoshow = 1;
		$accounttransactions = $gulden->listtransactions($accounttoshowtx, $numoftransactionstoshow);
		
		//Get the raw transaction details
		$transactiondetails = getTransactionDetails($accounttransactions, $numoftransactionstoshow, $addresslist);
		
		//Get only the first item from the function as there is only one to possibly push
		$transactiondetailsitem = $transactiondetails[0];
		
		//Get the amount of Gulden sent/received
		$transactionamount = $transactiondetailsitem['transactionamount'];
		
		//Get the senders address
		$txfromaddress = $transactiondetailsitem['txfromaddress'];
		
		//Get the date and time of the transaction
		$transactiondate = $transactiondetailsitem['transactiondate'];
		
		//Only push a message if it is an incoming transaction
		if($transactionamount > 0) {
			//Get the info (last message and current message)
			$lastmessage = $CONFIG['pushbullettx']['lastmes'];
			$currentmessage = $transactiondate.": $transactionamount Gulden received from $txfromaddress";
			
			//Check the last message that was pushed to prevent multiple pushes of the same message
			if($lastmessage!=$currentmessage) {
				
				//The message is different, send a push notification
				$sendpush = shell_exec("curl --header 'Authorization: Bearer ".$CONFIG['pushbullet']."' -X POST https://api.pushbullet.com/v2/pushes --header 'Content-Type: application/json' --data-binary '{\"type\": \"note\", \"title\": \"Gulden Transaction\", \"body\": \"".$currentmessage."\"}'");
				
				//Set the current message as the last message in the config file
				$CONFIG['pushbullettx']['lastmes'] = $currentmessage;
				
				//Update the config file
				file_put_contents(__DIR__.'/../../config/config.php', '<?php $CONFIG = '.var_export($CONFIG, true).'; ?>');
			}
		}
	}
}
?>