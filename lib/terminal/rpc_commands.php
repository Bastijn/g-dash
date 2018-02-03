<?php
session_start();
require('json_rpc.php');

class GuldenConsoleRPC {
  //The 'getinfo' command
  public static $getinfo_documentation = "Get GuldenD info";
  public function getinfo() {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->getinfo();
	  $ginfostring = "";
	  foreach ($ginfo as $key => $value) {
		  $ginfostring .= "$key: $value\n";
	  }
      return $ginfostring;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  //Show the last 30 lines of the Gulden debug log
  public static $showlog_documentation = "Show the last 30 lines of the Gulden debug log";
  public function showlog() {
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
		$logfile = $CONFIG['datadir']."debug.log";
	  	$file = file($logfile);
		$lines = "";
		for ($i = max(0, count($file)-50); $i < count($file); $i++) {
		  $lines .= $file[$i];
		}
	  
      return $lines;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  //The 'addnode' command
  public static $addnode_documentation = "Add a node by IP address (usage: addnode IP)";
  public function addnode($ip) {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->addnode($ip, 'add');
	  $gresponse = $gulden->response['error']['message'];
	  if($gresponse=="") {
	  	$gresponse = "Added node $ip";
	  }
	  
      return $gresponse;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  public static $help_documentation = "Show available commands";
  public function help() {
    $availablecommands = "help - Show available commands\n";
	$availablecommands .= "getinfo - Get GuldenD info\n";
	$availablecommands .= "showlog - Show the last 30 lines of the Gulden debug log\n";
	$availablecommands .= "addnode - Add a node by IP address (usage: addnode IP)\n";
	
	return $availablecommands;
  }
}
 
handle_json_rpc(new GuldenConsoleRPC());
session_write_close();
?>