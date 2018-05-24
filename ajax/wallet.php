<?php
session_start();
if($_SESSION['G-DASH-loggedin']==TRUE) {
include('../lib/functions/functions.php');
include('../lib/settings/settings.php');
include('../config/config.php');
require_once('../lib/EasyGulden/easygulden.php');
$gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);

$guldenD = "GuldenD";
$guldenCPU = GetProgCpuUsage($guldenD);
$guldenMEM = GetProgMemUsage($guldenD);
$returnarray = array();

$nodeconfig = readGuldenConf($CONFIG['datadir']."/Gulden.conf");

if($guldenCPU > 0 && $guldenMEM > 0) {
	//GuldenD info
	$ginfo = $gulden->getinfo();
	$gerrors = $ginfo['errors'];
	
	if($nodeconfig['disablewallet']!="1") {
		//Check if the wallet is locked/unlocked
		//Error -15: Not encrypted
		//Error -1: Encrypted & locked
		//Error -17: Encrypted & unlocked
		$gulden->walletpassphrase('', '1');
		$encryptioncheck = $gulden->response['error']['code'];
		if($encryptioncheck != "-15") {
			$gulden->getwalletinfo();
			$checkunlock = $gulden->response['result']['unlocked_until'];
			if($checkunlock > 0) {
				$encryptioncheck = "-17";
			} elseif ($checkunlock == 0) {
				$encryptioncheck = "-1";
			}
		}
			
		//List all accounts
		$accountlist = $gulden->listaccounts();
		
		//If the users selected an account from the menu
		if(isset($_GET['account'])) { $selectedaccount = $_GET['account']; } else { $selectedaccount = ""; }
		
		if($selectedaccount!="") {
			$selectedaccount = $_GET['account'];
		} else {
			//Select the first account
			$selectedaccount = $accountlist[0]['UUID'];
		}
		
		//Get all addresses from this account
		$addresslist = $gulden->getaddressesbyaccount($selectedaccount);
		
		//If there is no address for this account, create a new one
		if(count($addresslist)==0) {
			$gulden->getnewaddress($selectedaccount);
		}
		
		//Check the address list again and get the latest address
		//This does not work as it is listed in alphabetic order...
		//DONE: Create a workaround
		$addresslist = $gulden->getaddressesbyaccount($selectedaccount);
		$latestaddress = $addresslist[(count($addresslist)-1)];
		
		//Get the total balance of single account
		$confirmedbalance = round($gulden->getbalance($selectedaccount),2);
		$unconfirmedbalance = $gulden->getunconfirmedbalance($selectedaccount);
		if($unconfirmedbalance == "") { $unconfirmedbalance = '0'; }
		
		//Get the balance of all accounts
		$totalbalance = round($gulden->getbalance(),2);
		
		//Use GuldenTrader API as the current euro price
		//$fetcheuroprice = @json_decode(file_get_contents("https://guldentrader.com/api/ticker"));
		//$europrice = $fetcheuroprice->buy;
		
		//Use Nocks API as the current euro price
		//$fetcheuroprice = @json_decode(file_get_contents("https://api.nocks.com/api/v2/trade-market/NLG-EUR"));
		//$europrice = $fetcheuroprice->data->buy->amount;
		
		//Use the user defined exchange to check the conversion rate for NLG
		$nlgprices = $GDASH['nlgrate'];
		$currentnlgprovider = $CONFIG['nlgprovider'];
		if($currentnlgprovider == "") { $currentnlgprovider = "0"; }
		$fetchURL = $nlgprices[$currentnlgprovider]['market'];
		$pricesymbol = $nlgprices[$currentnlgprovider]['symbol'];
		$fetchmarket = @json_decode(file_get_contents($fetchURL));
		$nlgpricelink = $nlgprices[$currentnlgprovider]['link'];
		if(strpos($nlgpricelink, "->")!==false) {
			$nlgpricelinkexpl = explode("->", $nlgpricelink);
			foreach ($nlgpricelinkexpl as $addtolink) {
				$fetchmarket = $fetchmarket->$addtolink;
			}
			$currentprice = $fetchmarket;
		} else {
			$currentprice = $fetchmarket->$nlgpricelink;
		}
		$currencyrounding = $nlgprices[$currentnlgprovider]['rounding'];
		
		
		//TODO: Create a good function for showing a new unused address
		//Temporary solution until a better way is found
		//Get the 30 latest transactions of this single account
		$numoftransactions = 30;
		$accounttransactions = $gulden->listtransactions($selectedaccount, $numoftransactions);
		
		//Get the addresses of this specific account
		$listreceivedbyaddress = $gulden->listreceivedbyaddress(0, false);
		if(count($accounttransactions)>0) {
			$transactionaddresslist = array();
			foreach ($listreceivedbyaddress as $tavalue) {
				if($tavalue['account']==$selectedaccount) {
					$transactionaddresslist[] = $tavalue['address'];
				}
			}
			
			//Find the differences between the current address list and the transaction list
			//If there are no differences, create a new address
			//If there are differences, use the address that is different
			$diffaddresses = array_values(array_diff($addresslist, $transactionaddresslist));
			if(count($diffaddresses)>0) {
				$latestaddress = $diffaddresses[0];
			} else {
				$gulden->getnewaddress($selectedaccount);
				$addresslist = $gulden->getaddressesbyaccount($selectedaccount);
				$diffaddresses = array_values(array_diff($addresslist, $transactionaddresslist));
				if(count($diffaddresses)>0) {
					$latestaddress = $diffaddresses[0];
				}
			}
		} else {
			$latestaddress = $addresslist[(count($addresslist)-1)];
		}
		
		$returnarray['selectedaccount'] = $selectedaccount;
		$returnarray['accountlist'] = $accountlist;
		$returnarray['address'] = $latestaddress;
  		$returnarray['balance'] = $confirmedbalance;
		$returnarray['totalbalance'] = $totalbalance;
		$returnarray['otherbalance'] = round($confirmedbalance * $currentprice, $currencyrounding);
		$returnarray['othertotalbalance'] = round($totalbalance * $currentprice, $currencyrounding);
		$returnarray['otherbalancesymbol'] = $pricesymbol;
		$returnarray['uncbalance'] = $unconfirmedbalance;
		$returnarray['disablewallet'] = "0";
		$returnarray['encryption'] = $encryptioncheck;
	} else {
		$returnarray['selectedaccount'] = "";
		$returnarray['accountlist'] = "";
		$returnarray['address'] = "";
  		$returnarray['balance'] = "";
		$returnarray['totalbalance'] = "";
		$returnarray['uncbalance'] = "";
		$returnarray['disablewallet'] = "1";
		$returnarray['encryption'] = "";
	}
	$returnarray['errors'] = $gerrors;
	$returnarray['server']['cpu'] = $guldenCPU;
	$returnarray['server']['mem'] = $guldenMEM;
} else {
	$returnarray['selectedaccount'] = "";
	$returnarray['accountlist'] = "";
	$returnarray['address'] = "";
	$returnarray['accounttransactions'] = "";
	$returnarray['accounttransactionsdetails'] = "";
  	$returnarray['balance'] = "";
	$returnarray['totalbalance'] = "";
	$returnarray['uncbalance'] = "";
	$returnarray['encryption'] = "";
	
	$returnarray['errors'] = "";
	$returnarray['server']['cpu'] = "";
	$returnarray['server']['mem'] = "";
	
}

echo json_encode($returnarray);
}
session_write_close();
?>