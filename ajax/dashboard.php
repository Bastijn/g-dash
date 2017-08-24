<?php
session_start();
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
	
	$returnarray['server']['cpu'] = $guldenCPU;
	$returnarray['server']['mem'] = $guldenMEM;
	
	if($gulden->getinfo()=="") {
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
		$ginfo = $gulden->getinfo();
		$gversion = $ginfo['version'];
		$gblocks = $ginfo['blocks'];
		$gconnections = $ginfo['connections'];
		$gprotocolversion = $ginfo['protocolversion'];
		$gtimeoffset = $ginfo['timeoffset'];
		$gdifficulty = $ginfo['difficulty'];
		$gerrors = $ginfo['errors'];
		
		$gallblocksjson = file_get_contents('https://blockchain.gulden.com/api/status?q=getInfo');
		$array = json_decode($gallblocksjson);
		$gallblocks = $array->info->blocks;
		$gblockspercent = floor(($gblocks/$gallblocks)*100)."%";
		$guptime = GetTimeAnno(GetProgUpTime("GuldenD"));
		
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
			$difficulty = $blockinfo['difficulty'];
			
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
	$returnarray['table'] = $tablerows;
	$returnarray['errors'] = '';
}

echo json_encode($returnarray);
}
session_write_close();
?>