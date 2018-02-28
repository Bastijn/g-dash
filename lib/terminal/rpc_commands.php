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
  
  //Show the last 50 lines of the Gulden debug log
  public static $showlog_documentation = "Show the last 50 lines of the Gulden debug log";
  public function showlog() {
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
		include('../../config/config.php');
		$logfile = $CONFIG['datadir']."debug.log";
		
		//Check if the terminal is allowed to read the file
		if(is_readable($logfile)) {
			$file = file($logfile);
			$lines = "";
			for ($i = max(0, count($file)-50); $i < count($file); $i++) {
			  $lines .= $file[$i];
			}
		} else {
			$lines = "File is not readable. Change the file permissions of your debug.log file in your terminal. For example: chmod 0644 /opt/gulden/datadir/debug.log";
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
  
  //Put the IP on a public list to request to be added to nodes
  public static $noderequest_documentation = "Request to be added by other nodes in case you have no incoming connections";
  public function noderequest() {
  	
	//Has there been a request recently (in the last 24 hours)?
	$continuerequest = FALSE;
	if(isset($_SESSION['noderequested'])) {
		if($_SESSION['noderequested'] != "") {
		   $timesincereq = time() - $_SESSION['noderequested'];
		   if($timesincereq > (60*60*24)) {
		   	 $continuerequest = TRUE;
		   }
		}
	} else {
		$continuerequest = TRUE;
	}
	
    if($_SESSION['G-DASH-loggedin']==TRUE) {
      if($continuerequest == TRUE) {
	      $nodereqarray = array();
		  $nodereqarray = @json_decode(file_get_contents("https://g-dash.nl/noderequest.php"));
		  $noderesponse = $nodereqarray->status;
		  
		  if($noderesponse == "OK") {
		  	$gresponse = "Your request to be added by nodes has been added successfully.";
		  } elseif($noderesponse == "found") {
		  	$gresponse = "Your IP is already in the database";
		  } elseif($noderesponse == "invalid") {
		  	$gresponse = "Invalid IP address";
		  } else {
		  	$gresponse = "Unknown error";
		  }
	  } else {
	  	$gresponse = "You recently requested to be added. No need to spam.";
	  }
	  
	  $_SESSION['noderequested'] = time();
	  
      return $gresponse;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  public static $help_documentation = "Show available commands";
  public function help() {
    $availablecommands = "help - Show available commands\n";
	$availablecommands .= "getinfo - Get GuldenD info\n";
	$availablecommands .= "showlog - Show the last 50 lines of the Gulden debug log\n";
	$availablecommands .= "addnode - Add a node by IP address (usage: addnode IP)\n";
	$availablecommands .= "noderequest - Add a request to be added by other nodes\n";
	
	return $availablecommands;
  }
}
 
handle_json_rpc(new GuldenConsoleRPC());
session_write_close();
?>