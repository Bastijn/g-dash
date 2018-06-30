<?php
session_start();

//In case the server is very busy, lower the max execution time to 60 seconds
set_time_limit(10);

if($_SESSION['G-DASH-loggedin']==TRUE) {
include('../lib/functions/functions.php');
include('../config/config.php');
require_once('../lib/EasyGulden/easygulden.php');
$gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);

$guldenD = "GuldenD";
$guldenCPU = GetProgCpuUsage($guldenD);
$guldenMEM = GetProgMemUsage($guldenD);
$linuxTemp = GetLinuxTemp();
$returnarray = array();

if($guldenCPU > 0 && $guldenMEM > 0) {
	
	$returnarray['server']['cpu'] = $guldenCPU;
	$returnarray['server']['mem'] = $guldenMEM;
	$returnarray['server']['temperature'] = $linuxTemp;
	
	$guldengetinfo = $gulden->getinfo();
	$guldenprimaryresponsecode = $gulden->response['error']['code'];
	$guldenprimaryresponsemessage = $gulden->response['error']['message'];
	
	if($guldenprimaryresponsecode == "-28") {
		$returnarray['gulden']['version'] = '';
		$returnarray['gulden']['sync'] = '';
		$returnarray['gulden']['uptime'] = '';
		$returnarray['gulden']['protocolversion'] = '';
		$returnarray['node']['connections'] = '';
		$returnarray['node']['inbound'] = '';
		$returnarray['witness'] = '';
		$returnarray['table'] = "<tr><td colspan='4'>GuldenD Upgrading</td></tr>";
		$returnarray['errors'] = "Upgrading block index... This can take some time...";
	} elseif($guldengetinfo=="") {
		$returnarray['gulden']['version'] = '';
		$returnarray['gulden']['sync'] = '';
		$returnarray['gulden']['uptime'] = '';
		$returnarray['gulden']['protocolversion'] = '';
		$returnarray['node']['connections'] = '';
		$returnarray['node']['inbound'] = '';
		$returnarray['witness'] = '';
		$returnarray['table'] = "<tr><td colspan='4'>GuldenD error</td></tr>";
		$returnarray['errors'] = "Error connecting to server";
	} else {
	
		//GuldenD info
		$ginfo = $guldengetinfo;
		$gversion = $ginfo['version'];
		$gblocks = $ginfo['blocks'];
		$gconnections = $ginfo['connections'];
		$gprotocolversion = $ginfo['protocolversion'];
		$gtimeoffset = $ginfo['timeoffset'];
		$gdifficulty = round($ginfo['difficulty'],3);
		$gerrors = $ginfo['errors'];
		
		//Get synced blocks via blockchain api
		//$gallblocksjson = file_get_contents('https://blockchain.gulden.com/api/status?q=getInfo');
		//$array = json_decode($gallblocksjson);
		//$gallblocks = $array->info->blocks;
		
		//Get total headers via GuldenD getpeerinfo
		$peerinfo = $gulden->getpeerinfo();
		
		//Walk through all the peers to get the most updated one and 
		//grab the number of headers from that instance
		$gallblocks = 0;
		foreach ($peerinfo as $peervalue) {
			if($peervalue['synced_headers'] > $gallblocks) {
				$gallblocks = $peervalue['synced_headers'];
			}
		}
		
		//Check if headers are synced and the current number of blocks
		$bcinfo = $gulden->getblockchaininfo();
		$gsyncedblocks = $bcinfo['blocks'];
		$gsyncedheaders = $bcinfo['headers'];
		
		//Check if headers are synced
		if($gsyncedblocks == 0 && $gsyncedheaders > 0) {
			$gerrors = $gerrors."<br>Syncing headers. Please wait";
		}
		
		//Calculate the percentage of synced blocks
		$gblockspercent = floor(($gblocks/$gallblocks)*100)."%";
		
		//Get the uptime of the GuldenD instance
		$guptime = GetTimeAnno(GetProgUpTime("GuldenD"));
		
		//Check if Gulden is running a rescan
		$grescancheck = $gulden->getrescanprogress();
		if($grescancheck!=false) {
	  		$gerrors = $gerrors."<br>Running rescan (".$grescancheck."%). Please wait";
	  	}
		
		//Node info
		$gpeerinfo = $gulden->getpeerinfo();
		$ginboundconnections = 0;
		foreach($gpeerinfo as $innerArray) {
		  if($innerArray['inbound']=="1") { $ginboundconnections++; }
		}
		
		//Block info
		$tablerows = "";
		for ($i=$gallblocks; $i > $gallblocks-10 ; $i--) {
			$blockinfo = $gulden->getblock($gulden->getblockhash($i));
			$age = GetTimeAnno(time() - $blockinfo['time']);
			$transactions = count($blockinfo['tx']);
			$difficulty = round($blockinfo['difficulty'],3);
			
			$tablerows .= "
			<tr>
	          <td>$i</td>
	          <td>$age</td>
	          <td>$transactions</td>
	          <td>$difficulty</td>
	        </tr>
	        ";
		}
		
		//Data array
		$returnarray['gulden']['version'] = $gversion;
		$returnarray['gulden']['sync'] = $gblockspercent;
		$returnarray['gulden']['uptime'] = $guptime;
		$returnarray['gulden']['protocolversion'] = $gprotocolversion;
		$returnarray['gulden']['timeoffset'] = $gtimeoffset;
		$returnarray['gulden']['difficulty'] = $gdifficulty;
		$returnarray['gulden']['blocks'] = $gblocks;
		$returnarray['gulden']['allblocks'] = $gallblocks;
		
		$returnarray['node']['connections'] = $gconnections;
		$returnarray['node']['inbound'] = $ginboundconnections;
		
		$returnarray['witness'] = '';
		
		$returnarray['table'] = $tablerows;
		
	  	$returnarray['errors'] = $gerrors;
		
	}
	
} else {
	$tablerows = "<tr><td colspan='4'>GuldenD is not running</td></tr>";
	
	$returnarray['gulden']['version'] = '';
	$returnarray['gulden']['sync'] = '';
	$returnarray['gulden']['uptime'] = '';
	$returnarray['gulden']['protocolversion'] = '';
	$returnarray['node']['connections'] = '';
	$returnarray['node']['inbound'] = '';
	$returnarray['witness'] = '';
	$returnarray['server']['cpu'] = '';
	$returnarray['server']['mem'] = '';
	$returnarray['server']['temperature'] = '';
	$returnarray['table'] = $tablerows;
	$returnarray['errors'] = '';
}

echo json_encode($returnarray);
}
session_write_close();
?>
