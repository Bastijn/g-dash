<?php
session_start();

//In case the server is very busy, lower the max execution time to 60 seconds
set_time_limit(60);

if($_SESSION['G-DASH-loggedin']==TRUE) {
include('../lib/functions/functions.php');
include('../config/config.php');
require_once('../lib/EasyGulden/easygulden.php');
$gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);

$guldenD = "GuldenD";
$guldenCPU = GetProgCpuUsage($guldenD);
$guldenMEM = GetProgMemUsage($guldenD);
$returnarray = array();

$nodeconfig = readGuldenConf($CONFIG['datadir']."/Gulden.conf");

session_write_close();

//Check if GuldenD is running
if($guldenCPU > 0 && $guldenMEM > 0) {
	//No need to check for transactions if the wallet is disabled
	if($nodeconfig['disablewallet']!="1") {
		
		//List all accounts
		$accountlist = $gulden->listaccounts();
		
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