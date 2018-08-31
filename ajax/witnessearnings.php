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

if($guldenCPU > 0 && $guldenMEM > 0) {
	//Get the local timezone
	$localdate = new DateTime();
	$localtz = $localdate->getTimezone();
	
	//Get the general Gulden info
	$ginfo = $gulden->getinfo();
	
	//Get the current block height
	$currentblock = $ginfo['blocks'];
	
	//Get the witness account
	$witnessuuid = KeyGet($_GET, '', 'uuid');
	
	//List all accounts, don't show deleted accounts 
	$accountlist = $gulden->listaccounts("*", "Normal");
	
	//Get the info for only this witness account
	$thiswitnessaccount = selectElementWithValue($accountlist, "UUID", $witnessuuid);
	
	//Change multidimensional array to single array
	$thiswitnessaccount = $thiswitnessaccount[0];
	
	//Get the label of this account
	$witnesslabel = $thiswitnessaccount['label'];
	
	//Get the witness accounts that belong to this wallet
	$mywitnessaccountsnetwork = $gulden->getwitnessinfo("tip", true, true);
	
	//Get the adjusted total network weight
	$totalNetworkWeightAdjusted = $mywitnessaccountsnetwork[0]['total_witness_weight_eligible_adjusted'];
	
	//Only get the witness address list of the witness accounts that belong to this wallet
	$mywitnessaddresslist = $mywitnessaccountsnetwork[0]['witness_address_list'];
	
	//Get the witness account data from the list of all witness accounts
	$witnessdata = selectElementWithValue($mywitnessaddresslist, "ismine_accountname", $witnesslabel);
	
	//Change multidimensional array to single array
	$witnessdata = $witnessdata[0];
	
	//Get the time frame of the lock period
	//$witnessdetailsarray['lock_from_date'] = time() - (($currentblock - $witnessdata['lock_from_block']) / (576 / (24 * 60 * 60)));
	$lockfrom_blockhash = $gulden->getblockhash($witnessdata['lock_from_block']);
	$lockfrom_getblock = $gulden->getblock($lockfrom_blockhash);
	$lockfrom_blocktime = $lockfrom_getblock['time'];
	$lockfrom_dt = new DateTime("@$lockfrom_blocktime", new DateTimeZone('GMT'));
	$lockfrom_dt->setTimezone(new DateTimeZone($localtz->getName()));
	$lock_from_date = $lockfrom_dt->format('d/m/Y H:i:s');
	$witnessdetailsarray['lock_from_date'] = $lockfrom_blocktime;
	$witnessdetailsarray['lock_until_date'] = time() + (($witnessdata['lock_until_block'] - $currentblock) / (576 / (24 * 60 * 60)));
	
	//Get the transactions of this witness account
	$witnesstransactions = $gulden->listtransactions($witnesslabel, 999999);
	
	//Get the witness transactions details
	//$witnesstransactions = getWitnessTransactions($witnesstransactions);
	$witnesstransactions = selectElementWithValue($witnesstransactions, "category", "generate");
	
	//Immature witness transactions
	//$witnesstransactions_immature = selectElementWithValue($witnesstransactions, "category", "immature");
	
	//Put the earnings in an array with a timestamp
	//Also sum all the witness earnings
	$totalwitnessearnings = array();
	//$sumearnings = 0;
	foreach ($witnesstransactions as $witnesstxearnings) {
		//$sumearnings = $sumearnings + $witnesstxearnings['amount'];
		$totalwitnessearnings[$witnesstxearnings['time']] = $witnesstxearnings['amount'];
	}
	
	//Put all the earnings in the return array
	$witnessdetailsarray['earnings'] = $totalwitnessearnings;
	
	//Calculate the expected earnings
	//$expectedearnings = round(((($witnessdata['lock_until_block'] - $currentblock) / $witnessdata['estimated_witness_period']) * 20) + $sumearnings);
	$expectedearnings_prop = (((576/(1/($witnessdata['adjusted_weight']/$totalNetworkWeightAdjusted) +100))*($witnessdata['lock_period'] / 576))*20)/$witnessdata['amount'];
	$expectedearnings = round($witnessdata['amount'] * $expectedearnings_prop);
	
	//Put the earnings in the return array
	$witnessdetailsarray['expectedearnings'] = $expectedearnings;
}

//Return the array in JSON format
echo json_encode($witnessdetailsarray);
}
session_write_close();
?>
