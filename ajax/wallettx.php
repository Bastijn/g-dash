<?php
session_start();

//In case the server is very busy, lower the max execution time to 60 seconds
set_time_limit(60);

if(KeyGet($_SESSION, FALSE, 'G-DASH-loggedin')==TRUE) {
include('../lib/functions/functions.php');
include('../config/config.php');
require_once('../lib/EasyGulden/easygulden.php');
$gulden = new Gulden(KeyGet($CONFIG, '', 'rpcuser'),KeyGet($CONFIG, '', 'rpcpass'),KeyGet($CONFIG, '127.0.0.1', 'rpchost'),KeyGet($CONFIG, '9232', 'rpcport'));

$guldenD = "GuldenD";
$guldenCPU = GetProgCpuUsage($guldenD);
$guldenMEM = GetProgMemUsage($guldenD);
$returnarray = array();

$nodeconfig = readGuldenConf(KeyGet($CONFIG, '', 'datadir')."/Gulden.conf");

session_write_close();

//Check if GuldenD is running
if($guldenCPU > 0 && $guldenMEM > 0) {
	//No need to check for transactions if the wallet is disabled
	if($nodeconfig['disablewallet']!="1") {
		
		//List all accounts, don't show deleted accounts 
		$accountlist = $gulden->listaccounts("*", "Normal");
		
		//List all accounts, including deleted accounts (comment above, uncomment this one)
		//$accountlist = $gulden->listaccounts();
		
		//Only list wallet accounts
		$accountlist = selectElementWithValue($accountlist, "type", "Desktop");
		
		//If the users selected an account from the menu
		if(isset($_GET['account'])) { $selectedaccount = $_GET['account']; } else { $selectedaccount = ""; }
		
		//Get the UUID of this account
		if($selectedaccount!="") {
			$selectedaccount = $_GET['account'];
		} else {
			//Select the first account
			$selectedaccount = $accountlist[0]['UUID'];
		}
		
		//Get all addresses for this account
		$addresslist = $gulden->getaddressesbyaccount($selectedaccount);
		
		//Get the 30 latest transactions of this single account
		$numoftransactions = 50;
		$numoftransactionstoshow = 30;
		$accounttransactions = $gulden->listtransactions($selectedaccount, $numoftransactions);
		
		//Create a list of transactions based on the TXID
		$tablerows = "";
		
		//Get the raw transaction details
		$transactiondetails = getLiveTransactionDetails($accounttransactions, $numoftransactionstoshow, $addresslist, $gulden);
				
		//Loop through the transactions
		foreach ($transactiondetails as $transactiondetailsitem) {
			$transactionamount = $transactiondetailsitem['transactionamount'];
			if($transactionamount > 0) {
				$transactionamount = "<font color='#2F900B'>+ ".abs($transactionamount)."</font>"; 
			} else {
				$transactionamount = "<font color='#C80000'>- ".abs($transactionamount)."</font>"; 
			}
			
			//Put the details in a table row
			$tablerows .= "<tr><td title=\"".$transactiondetailsitem['txconfirmations']." confirmations\">".$transactiondetailsitem['transactiondate']."</td><td>$transactionamount</td><td>".$transactiondetailsitem['transactionid']."</td></tr>";
		}
		
		$returnarray['accounttransactionsdetails'] = $tablerows;
		$returnarray['disablewallet'] = "0";
	} else {
		$returnarray['accounttransactionsdetails'] = "<tr><td colspan='4'>GuldenD is not running</td></tr>";
		$returnarray['disablewallet'] = "1";
	}
	$returnarray['errors'] = $gerrors;
	$returnarray['server']['cpu'] = $guldenCPU;
	$returnarray['server']['mem'] = $guldenMEM;
} else {
	$returnarray['accounttransactionsdetails'] = "";	
}

echo json_encode($returnarray);
}
?>