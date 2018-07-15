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

if($guldenCPU > 0 && $guldenMEM > 0) {
	$ginfo = $gulden->getinfo();
	$gerrors = $ginfo['errors'];	
	
	//Get information on the network regarding witnessing
	$witnessNetwork = $gulden->getwitnessinfo();
	
	//The total number of witness addresses
	$totalWitnesses = $witnessNetwork[0]['number_of_witnesses_total'];
	
	//Get the witness accounts that belong to this wallet
	$witnessaccountsnetwork = $gulden->getwitnessinfo("tip", true);
	
	//Only get the witness address list	of all the witnesses in the whole network
	$witnessaddresslist = $witnessaccountsnetwork[0]['witness_address_list'];
	
	//Get the total amount of Gulden locked in an array
	$addressLockedArray = array();
	foreach ($witnessaddresslist as $networkwitnessaddresses) {
		$addressLockedArray[] = $networkwitnessaddresses['amount'];;
	}
	
	rsort($addressLockedArray);
	$returnarray['addresslocked'] = $addressLockedArray;
	
}

echo json_encode($returnarray);
}
session_write_close();
?>
