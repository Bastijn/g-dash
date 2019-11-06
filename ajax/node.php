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

session_write_close();

if($guldenCPU > 0 && $guldenMEM > 0) {
	//GuldenD info
	$ginfo = $gulden->getinfo();
	$gversion = $ginfo['version'];
	$gconnections = $ginfo['connections'];
	$gtimeoffset = $ginfo['timeoffset'];
	$gerrors = $ginfo['errors'];
	$guptime = GetTimeAnno(GetProgUpTime("GuldenD"));
	
	//Node info
	$gpeerinfo = $gulden->getpeerinfo();
	$ginboundconnections = 0;
	$subversarray = array();
	$countryarray = array();
	$cityarray = array();
	
	foreach($gpeerinfo as $innerArray) {
		if($innerArray['inbound']=="1") {
			$ginboundconnections++;
		}
		
		$subvers = str_replace("/", "", $innerArray['subver']);
		$subversarray[$subvers]++;
		$inboundipport = $innerArray['addr'];
		$inboundip = explode(":", $inboundipport)[0];
		$ipinfo = GEOonline($inboundip);
		$ipinfo_countryname = $ipinfo['country'];
		$ipinfo_cityname = $ipinfo['city'];
		$countryarray[$ipinfo_countryname]++;
		$cityarray[$ipinfo_cityname]++;
	}
	
	//Location info
	$tablelocationrows = "<tr><td colspan='2'><b>Countries</b></td></tr>";
	if($ginboundconnections>0) {
		foreach ($countryarray as $country => $countrycount) {
			$tablelocationrows .= "<tr><td>$country</td><td>$countrycount</td></tr>";
		}
		
		$tablelocationrows .= "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
		
		$tablelocationrows .= "<tr><td colspan='2'><b>Cities</b></td></tr>";
		foreach ($cityarray as $city => $citycount) {
			$tablelocationrows .= "<tr><td>$city</td><td>$citycount</td></tr>";
		}
	}
	
	//Version info	
	$tableversionrows = "";
	if($ginboundconnections>0) {
		foreach ($subversarray as $version => $versioncount) {
			if($version=="") { $version="Unknown"; }
			$tableversionrows .= "<tr><td>$version</td><td>$versioncount</td></tr>";
		}
	}
	
	//Data array
	$returnarray['location'] = $tablelocationrows;
	$returnarray['version'] = $tableversionrows;
	$returnarray['server']['cpu'] = $guldenCPU;
	$returnarray['server']['mem'] = $guldenMEM;
	$returnarray['gulden']['version'] = $gversion;
	$returnarray['gulden']['uptime'] = $guptime;
	$returnarray['gulden']['timeoffset'] = $gtimeoffset;
	$returnarray['node']['connections'] = $gconnections;
	$returnarray['node']['inbound'] = $ginboundconnections;
	$returnarray['errors'] = $gerrors;
	
	
} else {
	$returnarray['location'] = "";
	$returnarray['version'] = "";
	$returnarray['server']['cpu'] = "";
	$returnarray['server']['mem'] = "";
	$returnarray['gulden']['version'] = "";
	$returnarray['gulden']['uptime'] = "";
	$returnarray['gulden']['timeoffset'] = "";
	$returnarray['node']['connections'] = "";
	$returnarray['node']['inbound'] = "";
	$returnarray['errors'] = $gerrors;
}

echo json_encode($returnarray);
}
?>