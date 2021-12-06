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

$localdate = new DateTime();
$localtz = $localdate->getTimezone();

session_write_close();

if($guldenCPU > 0 && $guldenMEM > 0) {
	$ginfo = $gulden->getinfo();
	$gerrors = $ginfo['errors'];	
	
	//Get information on the network regarding witnessing
	$witnessNetwork = $gulden->getwitnessinfo();
	
	$totalWitnesses = $witnessNetwork[0]['number_of_witnesses_total'];
	$totalNetworkWeight = $witnessNetwork[0]['total_witness_weight_raw'];
	$totalNetworkWeightAdjusted = $witnessNetwork[0]['total_witness_weight_eligible_adjusted'];
	$currentPhase = $witnessNetwork[0]['pow2_phase'];
	
	//TODO: This can be removed after Phase 3 is activated
	//Check if the AdjustedWeight is more than zero, otherwise set to raw weight
	if($totalNetworkWeightAdjusted == 0) { $totalNetworkWeightAdjusted = $totalNetworkWeight; }
	
	//Get the current block height
	$currentblock = $ginfo['blocks'];
	
	//List all accounts, don't show deleted accounts 
	$accountlist = $gulden->listaccounts("*", "Normal");
	
	//List all accounts, including deleted accounts (comment above, uncomment this one)
	//$accountlist = $gulden->listaccounts();
	
	//Only select regular accounts
	$accountlist_regular = selectElementWithValue($accountlist, "type", "Desktop");
	
	//Only select witness-only accounts
	$accountlistwitnessonly = selectElementWithValue($accountlist, "type", "Witness-only witness");
	
	//Only select witness accounts
	$accountlist = selectElementWithValue($accountlist, "type", "Witness");
	
	//Merge the witness arrays
	$accountlist = array_merge($accountlist, $accountlistwitnessonly);
	
	//Get all witness accounts on the network
	$witnessaccountsnetwork = $gulden->getwitnessinfo("tip", true);
	
	//Get the witness accounts that belong to this wallet
	$mywitnessaccountsnetwork = $gulden->getwitnessinfo("tip", true, true);
	
	//Only get the witness address list	of all the witnesses in the whole network
	$witnessaddresslist = $witnessaccountsnetwork[0]['witness_address_list'];
	
	//Only get the witness address list of the witness accounts that belong to this wallet
	$mywitnessaddresslist = $mywitnessaccountsnetwork[0]['witness_address_list'];
	
	//Get the total amount of Gulden locked
	$totalGuldenLocked = 0;
	foreach ($witnessaddresslist as $networkwitnessaddresses) {
		$totalGuldenLocked = $totalGuldenLocked + $networkwitnessaddresses['amount'];
	}
	
	//For each witness account get the required information
	//TODO: Remove this if statement in a later version after Phase 3 has been activated
	if($currentPhase > 1) {
		foreach ($accountlist as $singlewitnessaccount) {
			//Get the label from this account
			$witnessname = $singlewitnessaccount['label'];
			
			//Create a temp array for all the witness information
			$witnessdetailsarray = array();
			
			//Get the witness account data from the list of all witness accounts
			$witnessdata = selectElementWithValue($mywitnessaddresslist, "ismine_accountname", $witnessname);
			
			//Check the unconfirmed balance of this witness account
			$witnessunconfirmedbalance = $gulden->getbalance($witnessname, 0);
			
			//Check if the array is not empty
			if(count($witnessdata) == 1) {
				
				//Change multidimensional array to single array
				$witnessdata = $witnessdata[0];
				
				//Get the name (label) of the current account
				$currentwitnessaccountname = $witnessdata['ismine_accountname'];
				
				//Get the current balance of this witness account
				$witnessbalance = $gulden->getbalance($currentwitnessaccountname);
				
				//Get the immature witness balance
				$witnessimmature = $gulden->getimmaturebalance($currentwitnessaccountname);
				
				//Check if this account is a witness-only account
				$checkWOarray = selectElementWithValue($accountlistwitnessonly, "label", $currentwitnessaccountname);
				if(count($checkWOarray)>0) {
					$witnessdetailsarray['witnessonly'] = TRUE;
				} else {
					$witnessdetailsarray['witnessonly'] = FALSE;
					
					//Substract the orginal funding amount from the immature balance, if immature balance > 0
					//This is only needed for accounts created on this wallet, as it doesn't hold the amount funded
					//Also check if the immature balance is equal or more than the initial funding as this
					//can also be set to immature right after witnessing a block
					if($witnessimmature > 0) {
						if($witnessimmature >= $witnessdata['amount']) {
							$witnessimmature = $witnessimmature - $witnessdata['amount'];
						}
					}
				}
			
				//Get all the information from this single witness account
				$witnessdetailsarray['name'] = $currentwitnessaccountname;
				$witnessdetailsarray['address'] = $witnessdata['address'];
				$witnessdetailsarray['age'] = $witnessdata['age'];
				$witnessdetailsarray['amount'] = $witnessdata['amount'];
				$witnessdetailsarray['earningsavailable'] = $witnessbalance - $witnessdata['amount'];
				$witnessdetailsarray['earningsimmature'] = $witnessimmature;
				$witnessdetailsarray['weight'] = $witnessdata['weight'];
				$witnessdetailsarray['raw_weight'] = $witnessdata['raw_weight'];
				$witnessdetailsarray['adjusted_weight'] = $witnessdata['adjusted_weight'];
				$witnessdetailsarray['weight_percentage_raw'] = round(($witnessdata['raw_weight'] / $totalNetworkWeight) * 100, 2)."%";
				$witnessdetailsarray['weight_percentage_adj'] = round(($witnessdata['adjusted_weight'] / $totalNetworkWeight) * 100, 2)."%";
				$witnessdetailsarray['expected_witness_period'] = $witnessdata['expected_witness_period'];
				$witnessdetailsarray['estimated_witness_period'] = round($witnessdata['estimated_witness_period'] / 576, 2);
				$witnessdetailsarray['last_active_block'] = $witnessdata['last_active_block'];
				
				$lastactive_blockhash = $gulden->getblockhash($witnessdata['last_active_block']);
				$lastactive_getblock = $gulden->getblock($lastactive_blockhash);
				$lastactive_blocktime = $lastactive_getblock['time'];
				$lastactive_dt = new DateTime("@$lastactive_blocktime", new DateTimeZone('GMT'));
				$lastactive_dt->setTimezone(new DateTimeZone($localtz->getName()));
				$last_active_date = $lastactive_dt->format('d/m/Y H:i:s');
				$witnessdetailsarray['last_active_date'] = $last_active_date;
				
				//$witnessdetailsarray['last_active_date'] = date("d/m/Y H:i:s", time() - (($currentblock - $witnessdata['last_active_block']) / (576 / (24 * 60 * 60))));
				$witnessdetailsarray['lock_from_block'] = $witnessdata['lock_from_block'];
				
				$lockfrom_blockhash = $gulden->getblockhash($witnessdata['lock_from_block']);
				$lockfrom_getblock = $gulden->getblock($lockfrom_blockhash);
				$lockfrom_blocktime = $lockfrom_getblock['time'];
				$lockfrom_dt = new DateTime("@$lockfrom_blocktime", new DateTimeZone('GMT'));
				$lockfrom_dt->setTimezone(new DateTimeZone($localtz->getName()));
				$lock_from_date = $lockfrom_dt->format('d/m/Y H:i:s');
				$witnessdetailsarray['lock_from_date'] = $lock_from_date;
				
				//$witnessdetailsarray['lock_from_date'] = date("d/m/Y H:i:s", time() - (($currentblock - $witnessdata['lock_from_block']) / (576 / (24 * 60 * 60))));
				$witnessdetailsarray['lock_until_block'] = $witnessdata['lock_until_block'];
				$witnessdetailsarray['lock_until_date'] = date("d/m/Y H:i:s", time() + (($witnessdata['lock_until_block'] - $currentblock) / (576 / (24 * 60 * 60))));
				$witnessdetailsarray['lock_period'] = $witnessdata['lock_period'];
				
				$witnesslockperiod = round(($witnessdata['lock_period'] / 17280), 2);
				if($witnesslockperiod == 1) { $witnesslockperiodstring = "Month"; } else { $witnesslockperiodstring = "Months"; }
				
				$witnessdetailsarray['lock_period_time'] = $witnesslockperiod." $witnesslockperiodstring";
				$witnessdetailsarray['lock_period_expired'] = $witnessdata['lock_period_expired'];
				$witnessdetailsarray['eligible_to_witness'] = $witnessdata['eligible_to_witness'];
				$witnessdetailsarray['expired_from_inactivity'] = $witnessdata['expired_from_inactivity'];
				
				//Get the transactions of this witness account
				$witnesstransactions = $gulden->listtransactions($currentwitnessaccountname, 999999);
				
				//Get the witness transactions details
				//$witnesstransactions = getWitnessTransactions($witnesstransactions);
				$witnesstransactions = selectElementWithValue($witnesstransactions, "category", "generate");
				
				//Immature witness transactions
				$witnesstransactions_immature = selectElementWithValue($witnesstransactions, "category", "immature");
				
				//Sum the earnings
				$totalwitnessearnings = 0;
				foreach ($witnesstransactions as $witnesstxearnings) {
					if ($witnesstxearnings['vout'] != 0) $totalwitnessearnings = $totalwitnessearnings + $witnesstxearnings['amount'];
				}
				
				//Count the witness cycles
				$countwitnessearnings = count($witnesstransactions);
				
				//Calculate the expected earnings
				//$expectedearnings = round(((($witnessdata['lock_until_block'] - $currentblock) / $witnessdata['estimated_witness_period']) * 20) + $totalwitnessearnings);
				$expectedearnings_prop = (((576/(1/($witnessdata['adjusted_weight']/$totalNetworkWeightAdjusted) +100))*($witnessdata['lock_period'] / 576))*20)/$witnessdata['amount'];
				$expectedearnings = round($witnessdata['amount'] * $expectedearnings_prop);
				
				//Put the earnings in the return array
				$witnessdetailsarray['totalearnings'] = $totalwitnessearnings;
				$witnessdetailsarray['totalcycles'] = $countwitnessearnings;
				$witnessdetailsarray['expectedearnings'] = $expectedearnings;
				$witnessdetailsarray['expectedearningspercentage'] = round($expectedearnings_prop * 100, 2);
				
				//Calculate the total age in blocks of the witness account
				$totalWitnessAge = $currentblock - $witnessdata['lock_from_block'];
				
				//Check the status of the current witness account (Finished/Expired/Witnessing)
				if($witnessdata['lock_period_expired']==true) {
					$witnessdetailsarray['status'] = "<i class='glyphicon glyphicon-flag'></i> Finished";
					$witnessdetailsarray['status_long'] = "Finished witness account. Please withdraw earnings.";
				} elseif($witnessdata['expired_from_inactivity']==true) {
					$witnessdetailsarray['status'] = "<i class='glyphicon glyphicon-exclamation-sign'></i> Expired";
					$witnessdetailsarray['status_long'] = "Expired witness account due to inactivity. Please renew your witness account.";
				} elseif($witnessdata['eligible_to_witness']==true) {
					$witnessdetailsarray['status'] = "<i class='glyphicon glyphicon-hourglass'></i> Ready to witness";
					$witnessdetailsarray['status_long'] = "Waiting to be picked to witness a block.";
				} elseif($witnessdata['eligible_to_witness']==false && $totalWitnessAge <= 101) {
					//TODO: Can be removed after Phase 3 activated
					if($currentPhase == "2") {
						$witnessdetailsarray['status'] = "<i class='glyphicon glyphicon-link'></i> Waiting for Phase 3";
						$witnessdetailsarray['status_long'] = "Waiting for Phase 3 to activate.";
					} else {
						$witnessdetailsarray['status'] = "<i class='glyphicon glyphicon-link'></i> Confirming";
						$witnessdetailsarray['status_long'] = "Confirming initial funding (".$witnessdata['age']." / 100 blocks).";
					}
				} elseif($witnessdata['eligible_to_witness']==false && $witnessdata['expired_from_inactivity']==false && $witnessdata['lock_period_expired']==false && $witnessdata['age'] < 101) {
					$witnessdetailsarray['status'] = "<i class='glyphicon glyphicon-refresh'></i> Cooldown";
					$witnessdetailsarray['status_long'] = "Cooldown period after last witness action (".$witnessdata['age']." / 100 blocks).";
				} else {
					$witnessdetailsarray['status'] = "<i class='glyphicon glyphicon-minus-sign'></i> Unknown status";
					$witnessdetailsarray['status_long'] = "G-DASH can't determine the status of this account.";
				}
				
			} elseif(count($witnessdata) > 1) {
				$witnessdetailsarray['status'] = "<i class='glyphicon glyphicon-minus-sign'></i> Mutliple entries found!";
				$witnessdetailsarray['status_long'] = "This account was funded multiple times. No reliable stats available.";
				//Debug if multiple times funded
				//$witnessdetailsarray['status_long'] = $witnessdata;
			} elseif($witnessunconfirmedbalance > 0) {
				//Array is empty. Witness account created and recently funded.
				$witnessdetailsarray['status'] = "Recently funded";
				$witnessdetailsarray['status_long'] = "Recently funded, waiting for confirmation.";
			} else {
				//Array is empty. Witness account created but not yet funded.
				$witnessdetailsarray['status'] = "Not funded";
			}
			
			//Put everything in the return array
			$returnarray['witnessaccountdetails'][$witnessname] = $witnessdetailsarray;
		}
	} else {
		//If phase 3 is not activated yet
		$returnarray['witnessaccountdetails'][$witnessname]['status'] = "PoW<sub>2</sub> Phase 3 not activated yet.";
		$returnarray['witnessaccountdetails'][$witnessname]['status_long'] = "PoW<sub>2</sub> Phase 3 not activated yet.";
	}
	
	$returnarray['witness']['totalwitnesses'] = $totalWitnesses;
	$returnarray['witness']['totalNetworkWeight'] = $totalNetworkWeight;
	$returnarray['witness']['currentPhase'] = $currentPhase;
	$returnarray['witness']['totalGuldenLocked'] = $totalGuldenLocked;
	
	$returnarray['accountlist'] = $accountlist;
	$returnarray['regaccountlist'] = $accountlist_regular;
	
	$returnarray['server']['cpu'] = $guldenCPU;
	$returnarray['server']['mem'] = $guldenMEM;
	$returnarray['errors'] = $gerrors;
	
} else {
	$tablerows = "<tr><td colspan='4'>GuldenD is not running</td></tr>";
	
	
	$returnarray['witness']['totalwitnesses'] = '';
	$returnarray['witness']['totalNetworkWeight'] = '';
	$returnarray['witness']['currentPhase'] = '';
	$returnarray['server']['cpu'] = '';
	$returnarray['server']['mem'] = '';
	$returnarray['errors'] = '';
}

echo json_encode($returnarray);
}
?>
