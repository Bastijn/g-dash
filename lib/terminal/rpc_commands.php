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
  
  //The 'getcheckpoint' command
  public static $getcheckpoint_documentation = "Print the currently set autocheckpoint";
  public function getcheckpoint() {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->getcheckpoint();
      return $ginfo;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  //The 'getlastblocks' command
  public static $getlastblocks_documentation = "Print the last blocks at the tip of our local chain";
  public function getlastblocks() {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->getlastblocks();
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

  //The walletunlock command that calls the 'walletpassphrase' command
  public static $walletunlock_documentation = "Unlock wallet for 5 minutes (usage: walletunlock 'walletpassword')";
  public function walletunlock($walletpassword) {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->walletpassphrase($walletpassword, 300);
	  $gresponse = $gulden->response['error']['message'];
	  if($gresponse=="") {
	  	$gresponse = "Wallet unlocked for 5 minutes";
	  }
	  
      return $gresponse;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  //The rescan command to re-check the blockchain
  public static $rescan_documentation = "Rescan the blockchain for transactions (usage: rescan)";
  public function rescan() {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->rescan();
	  $gresponse = $gulden->response['error']['message'];
	  if($gresponse=="") {
	  	$gresponse = "Running rescan. Progress: ".$gulden->getrescanprogress()."%";
	  }
	  
      return $gresponse;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  //The getrescanprogress command to check the progress
  public static $getrescanprogress_documentation = "Rescan progess in percentage (usage: getrescanprogress)";
  public function getrescanprogress() {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->getrescanprogress();
	  $gresponse = $gulden->response['error']['message'];
	  if($gresponse=="") {
	  	if($ginfo==false) {
	  		$gresponse = "Rescan finished";
	  	} else {
	  		$gresponse = "Running rescan. Progress: ".$ginfo."%";
	  	}
	  }
	  
      return $gresponse;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  //The guldenstop command to shut down GuldenD
  public static $guldenstop_documentation = "Stop GuldenD graciously";
  public function guldenstop() {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->stop();
	  $gresponse = $gulden->response['error']['message'];
	  if($gresponse=="") {
	  	$gresponse = $ginfo;
	  }
	  
      return $gresponse;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  //The resetdatadirpartial command
  public static $resetdatadirpartial_documentation = "Selectively wipe parts of the data directory and exit GuldenD";
  public function resetdatadirpartial() {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->resetdatadirpartial();
	  $gresponse = $gulden->response['error']['message'];
	  if($gresponse=="") {
	  	$gresponse = $ginfo;
	  }
	  
      return $gresponse;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  //The resetdatadirfull command
  public static $resetdatadirfull_documentation = "Wipe all blockchain data from the the data directory and exit GuldenD";
  public function resetdatadirfull() {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->resetdatadirfull();
	  $gresponse = $gulden->response['error']['message'];
	  if($gresponse=="") {
	  	$gresponse = $ginfo;
	  }
	  
      return $gresponse;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  //The resetconfig command
  public static $resetconfig_documentation = "Erase all custom settings from the config file, with the exception of RPC connection settings";
  public function resetconfig() {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->resetconfig();
	  $gresponse = $gulden->response['error']['message'];
	  if($gresponse=="") {
	  	$gresponse = $ginfo;
	  }
	  
      return $gresponse;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  //The resetconfig_pi_lowmem command
  public static $resetconfig_pi_lowmem_documentation = "Erase all custom settings from the config file, with the exception of RPC connection settings. Set some defaults that are suitable for a highly memory constrained device.";
  public function resetconfig_pi_lowmem() {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->resetconfig_pi_lowmem();
	  $gresponse = $gulden->response['error']['message'];
	  if($gresponse=="") {
	  	$gresponse = $ginfo;
	  }
	  
      return $gresponse;
    } else {
      throw new Exception("Access Denied");
    }
  }
  
  //The resetconfig_pi_medmem command
  public static $resetconfig_pi_medmem_documentation = "Erase all custom settings from the config file, with the exception of RPC connection settings. Set some defaults that are suitable for a memory constrained device.";
  public function resetconfig_pi_medmem() {	
    if ($_SESSION['G-DASH-loggedin']==TRUE) {
      include('../../config/config.php');
	  require_once('../../lib/EasyGulden/easygulden.php');
	  $gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
      $ginfo = $gulden->resetconfig_pi_medmem();
	  $gresponse = $gulden->response['error']['message'];
	  if($gresponse=="") {
	  	$gresponse = $ginfo;
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
	$availablecommands .= "getcheckpoint - Print the currently set autocheckpoint, useful for debugging issues\n";
	$availablecommands .= "getlastblocks - Print the last blocks at the tip of our local chain, useful for debugging issues\n";
	$availablecommands .= "showlog - Show the last 50 lines of the Gulden debug log\n";
	$availablecommands .= "addnode - Add a node by IP address (usage: addnode IP)\n";
	$availablecommands .= "noderequest - Add a request to be added by other nodes\n";
	$availablecommands .= "walletunlock - Temporary unlock the wallet for Gulden services\n";
	$availablecommands .= "rescan - Rescan the blockchain for transactions\n";
	$availablecommands .= "getrescanprogress - Rescan progess in percentage\n";
	$availablecommands .= "guldenstop - Stop GuldenD graciously (i.e. before a reboot)\n";
	$availablecommands .= "resetdatadirpartial - Selectively wipe parts of the data directory and exit GuldenD (Can be useful if stuck on a specific block after an upgrade)\n";
	$availablecommands .= "resetdatadirfull - Wipe all blockchain data from the the data directory and exit GuldenD. Will cause a full resync to occur which may take some time.\n";
	$availablecommands .= "resetconfig - Erase all custom settings from the config file, with the exception of RPC connection settings\n";
	$availablecommands .= "resetconfig_pi_lowmem - Erase all custom settings from the config file, with the exception of RPC connection settings. Set some defaults that are suitable for a highly memory constrained device.\n";
	$availablecommands .= "resetconfig_pi_medmem - Erase all custom settings from the config file, with the exception of RPC connection settings. Set some defaults that are suitable for a memory constrained device.\n";
	
	return $availablecommands;
  }
}
 
handle_json_rpc(new GuldenConsoleRPC());
session_write_close();
?>
