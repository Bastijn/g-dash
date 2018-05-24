<?php
function GetProgCpuUsage($program)
{
    if(!$program) return -1;
    
    $c_pid = exec("ps aux | grep ".$program." | grep -v grep | grep -v su | awk {'print $3'}");
    return trim($c_pid);
}

function GetProgMemUsage($program)
{
    if(!$program) return -1;
    
    $c_pid = exec("ps aux | grep ".$program." | grep -v grep | grep -v su | awk {'print $4'}");
    return trim($c_pid);
}

function GetProgUpTime($program) 
{
	if(!$program) return -1;
	$c_pid = exec("ps -eo pid,comm,etimes | grep $program | awk {'print $3'}");
	return trim($c_pid);
}

function GetLinuxTemp() 
{
	$l_temp = exec("cat /sys/class/thermal/thermal_zone0/temp");
	$l_temp = round(trim($l_temp) / 1000, 0);
	return $l_temp;
}

function checkOpenPort($address, $port) {
	$connection = @fsockopen($address, $port);
	if (is_resource($connection)) { return TRUE; } else { return FALSE;}
}

function GetTimeAnno($timestamp) 
{
    $how_long_ago = '';
    $seconds = $timestamp; 
    $minutes = (int)($seconds / 60);
    $hours = (int)($minutes / 60);
    $days = (int)($hours / 24);
	$daysplushours = (int)($hours - ($days*24));
	
    if ($days >= 1) {
      $how_long_ago = $days . ' day' . ($days != 1 ? 's' : '');
	  $how_long_ago .= " and " . $daysplushours . ' hour' . ($daysplushours != 1 ? 's' : '');
    } else if ($hours >= 1) {
      $how_long_ago = $hours . ' hour' . ($hours != 1 ? 's' : '');
    } else if ($minutes >= 1) {
      $how_long_ago = $minutes . ' minute' . ($minutes != 1 ? 's' : '');
    } else {
      $how_long_ago = $seconds . ' second' . ($seconds != 1 ? 's' : '');
    }
    return $how_long_ago;
}

function AddTrailingSlash($string)
{
	$string = rtrim($string, '/') . '/';
	return $string;
}

function readGuldenConf($file) {
	$contentarray = array();
	$handle = fopen($file, "r");
	if ($handle) {
	    while (($line = fgets($handle)) !== false) {
	        $splittedline = explode("=", $line);
			$var = trim($splittedline[0]);
			$val = trim($splittedline[1]);
			$contentarray[$var] = $val;
	    }
	    fclose($handle);
	} else {
	    $contentarray['error'] = "error";
	}
	
	return $contentarray;
}

function GEOonline($ip) {
	$result = array();
	
	$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
	if($ip_data && $ip_data->geoplugin_countryName != null){
	    $result['country'] = $ip_data->geoplugin_countryName;
	} elseif($ip_data->geoplugin_countryName == null) {
		$result['country'] = "unknown";
	}
	
	if($ip_data && $ip_data->geoplugin_city != null){
	    $result['city'] = $ip_data->geoplugin_city;
	} elseif($ip_data->geoplugin_city == null) {
		$result['city'] = "unknown";
	}
	
	return $result;
}

//TODO: This function can be removed at the 1.0 release
function isPasswordUpdated() {
	include('config/config.php');
	if($CONFIG['bcrypt']=="1") {
		return TRUE;
	} else {
		return FALSE;
	}
}

function LoginCheck($guldenConf, $out=FALSE, $disabled="0")
{
	include('config/config.php');
	$returnvalue = FALSE;
	
	//If the dashboard hasn't been configured yet, go directly to the settings page
	if($CONFIG['configured']!="1") {
		$disabled="1";
	}
	
	if(isset($_POST['login'])) {
		$usernameposted = strtolower($_POST['rpcuser']);
		$passwordposted = $_POST['rpcpassword'];
		
		//TODO: Remove the MD5 function and update check at 1.0 release
		if(isPasswordUpdated()==FALSE) {
			$passwordposted = md5(sha1($passwordposted));
		}
		
		//TODO: Remove this part at the 1.0 release
		//This method will fetch the username and password from the config.php file
		if($usernameposted==strtolower($CONFIG['gdashuser']) && $passwordposted==$CONFIG['gdashpassword'] && $CONFIG['bcrypt']!="1") {
			$_SESSION['G-DASH-loggedin']=TRUE;
			$returnvalue = TRUE;
		} elseif($usernameposted==strtolower($CONFIG['gdashuser']) && password_verify($passwordposted, $CONFIG['gdashpassword']) && $CONFIG['bcrypt']=="1") {
			$_SESSION['G-DASH-loggedin']=TRUE;
			$returnvalue = TRUE;			
		} else {
			echo "<div class='alert alert-danger'>
				   <strong>Error:</strong><br>
				   This combination of username/password is incorrect.
				  </div>";
		}
	} elseif($out==TRUE) {
		$returnvalue = FALSE;
		$_SESSION['G-DASH-loggedin']=FALSE;
		session_destroy();
	} elseif($disabled=="1") {
		$returnvalue = TRUE;
		$_SESSION['G-DASH-loggedin'] = TRUE;
	} elseif($_SESSION['G-DASH-loggedin']==TRUE) {
		$returnvalue = TRUE;
	}
	return $returnvalue;
}

function checkRequiredPackages() {
	//Are all required packages installed?
	$installedPackages = array();
	$installedPackages = explode(PHP_EOL, shell_exec("dpkg --get-selections | grep -v deinstall | awk {'print $1'}"));
	$requiredPackages = array("apache2", "php5", "php5-curl", "curl", "php5-json", "libapache2-mod-php5");
	$checkInstalled = "";
	
	if(in_array("php5", $installedPackages)) {
		
		foreach ($requiredPackages as $packvalue) {
			if(in_array($packvalue, $installedPackages)) {
				$checkInstalled .= "<font color='green'>$packvalue - Passed</font><br>";
			} else {
				$checkInstalled .= "<font color='red'>$packvalue - Not found!</font><br>";
			}
		}
		
	} elseif(in_array("php7.0", $installedPackages)) {
		
		$requiredPackages = array("apache2", "php7.0", "php7.0-curl", "curl", "php7.0-json", "libapache2-mod-php7.0");
		foreach ($requiredPackages as $packvalue) {
			if(in_array($packvalue, $installedPackages)) {
				$checkInstalled .= "<font color='green'>$packvalue - Passed</font><br>";
			} else {
				$checkInstalled .= "<font color='red'>$packvalue - Not found!</font><br>";
			}
		}
	} else {
		$checkInstalled = "Check PHP packages!";
	}
	
	return $checkInstalled;
}

function getGuldenServices() {
	//Check the Gulden listening services
	$guldenListeningServices = explode(PHP_EOL, shell_exec("netstat -antl | grep LISTEN | grep -v tcp6 | awk {'print $4'}"));
	$runningService = "";
	
	foreach ($guldenListeningServices as $service) {
		$runningService .= $service."<br>";
	}
	
	return $runningService;
}

function getFilePermissions($file) {	
	//Array with results of the function
	$resultArray = array();	
	
	//Does the file exist?
	if(file_exists($file)) {
		
		//Yes, exists
		$resultArray['exists'] = TRUE;
	
		//Raw permissions
		$perms = fileperms($file);
		
		//The owner of the file
		$resultArray['owner'] = posix_getpwuid(fileowner($file));
	
		//Type of file
		switch ($perms & 0xF000) {
		    case 0xC000: // socket
		        $type = 's';
		        break;
		    case 0xA000: // symbolic link
		        $type = 'l';
		        break;
		    case 0x8000: // regular
		        $type = 'r';
		        break;
		    case 0x6000: // block special
		        $type = 'b';
		        break;
		    case 0x4000: // directory
		        $type = 'd';
		        break;
		    case 0x2000: // character special
		        $type = 'c';
		        break;
		    case 0x1000: // FIFO pipe
		        $type = 'p';
		        break;
		    default: // unknown
		        $type = 'u';
		}
		
		$resultArray['type'] = $type;
		
		// Owner
		$info = (($perms & 0x0100) ? 'r' : '-');
		$info .= (($perms & 0x0080) ? 'w' : '-');
		$info .= (($perms & 0x0040) ?
		            (($perms & 0x0800) ? 's' : 'x' ) :
		            (($perms & 0x0800) ? 'S' : '-'));
		
		// Group
		$info .= (($perms & 0x0020) ? 'r' : '-');
		$info .= (($perms & 0x0010) ? 'w' : '-');
		$info .= (($perms & 0x0008) ?
		            (($perms & 0x0400) ? 's' : 'x' ) :
		            (($perms & 0x0400) ? 'S' : '-'));
		
		// World
		$info .= (($perms & 0x0004) ? 'r' : '-');
		$info .= (($perms & 0x0002) ? 'w' : '-');
		$info .= (($perms & 0x0001) ?
		            (($perms & 0x0200) ? 't' : 'x' ) :
		            (($perms & 0x0200) ? 'T' : '-'));
		
		$resultArray['permissions'] = $info;
		
		//Writable, readable and executable
		$resultArray['writable'] = is_writable($file);
		$resultArray['readable'] = is_readable($file);
		$resultArray['executable'] = is_executable($file);
	} else {
		//File does not exist
		$resultArray['exists'] = FALSE;
	}
	
	return $resultArray;
}

function array_search_multidimensional($array, $field, $value)
{
   foreach($array as $key => $item)
   {
      if ( $item[$field] === $value )
         return $key;
   }
   return false;
}

function selectElementWithValue($array, $field, $value){
	$newArray = array();
	
	foreach($array as $subKey => $subArray){
		if($subArray[$field] == $value){
			$newArray[] = $array[$subKey];
		}
	}
	return $newArray;
}

function getTransactionDetails($accounttransactions, $numoftransactionstoshow, $addresslist) {
	$currenttxshown = 1;
	$returntx = array();
	
	for ($i=count($accounttransactions)-1; $i >= 0 ; $i--) {
		$transactiondetails = $accounttransactions[$i];
		if($transactiondetails['txid'] != $transactiontxid) {
			//Stop showing transactions if the limit is reached
			if($numoftransactionstoshow == $currenttxshown) { $i = 0; }
			
			//Fetch the transaction ID
			$transactiontxid = $transactiondetails['txid'];
			
			//Get the raw transaction details from the Gulden blockchain Insight API
			$txrawdetails = @json_decode(file_get_contents("https://blockchain.gulden.com/api/tx/".$transactiontxid));
			
			$txfromaddress = $txrawdetails->vin[0]->addr;
			$txtime = $txrawdetails->time;
			$txconfirmations = $txrawdetails->confirmations;
			$txfee = $txrawdetails->fees;
			
			$fromme = FALSE;
			if(in_array($txfromaddress, $addresslist)==TRUE) {
				$fromme = TRUE;
				$foundFromMe = FALSE;
			}
			
			for ($x=0; $x<count($txrawdetails->vout); $x++) {
				//From other, to me
				if($fromme==FALSE && in_array($txrawdetails->vout[$x]->scriptPubKey->addresses[0], $addresslist)==TRUE) {
					$txtoaddress = $txrawdetails->vout[$x]->scriptPubKey->addresses[0];
					$txvalue = $txrawdetails->vout[$x]->value;
					$transactionamount = round($txvalue,2);
				}
				
				//From me, to other
				if($fromme==TRUE && in_array($txrawdetails->vout[$x]->scriptPubKey->addresses[0], $addresslist)==FALSE) {
					
					//If this is a "sendmany" transaction
					if($foundFromMe == TRUE) {
						$txtoaddress = $txrawdetails->vout[$x]->scriptPubKey->addresses[0];
						$txvalue = $txrawdetails->vout[$x]->value;
						$transactionamount = $transactionamount + -round($txvalue,2);
					} else {
						$txtoaddress = $txrawdetails->vout[$x]->scriptPubKey->addresses[0];
						$txvalue = $txrawdetails->vout[$x]->value;
						$transactionamount = -round($txvalue,2);
						
						//We found a first transaction; set to TRUE in case this is a sendmany transaction
						$foundFromMe = TRUE;
					}
				}
			}
			
			if($txtime=="") {
				//IGNORED: Fix the number of confirmations for unconfirmed transactions
				//$transactiondate = "Unconfirmed ($txconfirmations confirmations)";
				$transactiondate = "Unconfirmed";
			} else {
				$transactiondate = date('d/m/Y H:i', $txtime);
			}
			$transactionid = "<a href='https://blockchain.gulden.com/tx/".$transactiontxid."' target='_blank' title='".$transactiontxid."'>".substr($transactiontxid, 0, 7)."...</a>";
			
			$currenttxshown++;
			
			$currenttx['txfromaddress'] = $txfromaddress;
			$currenttx['txtime'] = $txtime;
			$currenttx['txconfirmations'] = $txconfirmations;
			$currenttx['txfee'] = $txfee;
			$currenttx['txtoaddress'] = $txtoaddress;
			$currenttx['transactionamount'] = $transactionamount;
			$currenttx['transactiondate'] = $transactiondate;
			$currenttx['transactionid'] = $transactionid;
			
			$returntx[] = $currenttx;
		}
	}

	return $returntx;
}

//Function to fetch the txdetails live from the wallet
function getLiveTransactionDetails($accounttransactions, $numoftransactionstoshow, $addresslist, $gulden) {
	$currenttxshown = 1;
	$returntx = array();
	
	for ($i=count($accounttransactions)-1; $i >= 0 ; $i--) {
		$transactiondetails = $accounttransactions[$i];
		if($transactiondetails['txid'] != $transactiontxid) {
			//Stop showing transactions if the limit is reached
			if($numoftransactionstoshow == $currenttxshown) { $i = 0; }
			
			//Fetch the transaction ID
			$transactiontxid = $transactiondetails['txid'];
			
			//Get the raw transaction details from the GuldenD for this transaction
			$txrawdetails = $gulden->getrawtransaction($transactiontxid, 1);
						
			//Get the first vout txID and N (sender)
			$txfromdetailstxid = $txrawdetails['vin'][0]['txid'];
			$txfromdetailsvout = $txrawdetails['vin'][0]['vout'];
			
			//Get the raw transaction details from GuldenD for the senders' transaction
			$txrawdetailssender = $gulden->getrawtransaction($txfromdetailstxid, 1);
			
			//Get the first address of the sender
			$txfromaddress = $txrawdetailssender['vout'][$txfromdetailsvout]['scriptPubKey']['addresses'][0];
			
			//Time, # of confirmations and transaction fee
			$txtime = $txrawdetails['time'];
			$txconfirmations = $txrawdetails['confirmations'];
			$txfee = 0; //TODO
			
			$fromme = FALSE;
			if(in_array($txfromaddress, $addresslist)==TRUE) {
				$fromme = TRUE;
				$foundFromMe = FALSE;
			}
			
			for ($x=0; $x<count($txrawdetails['vout']); $x++) {
				//From other, to me
				if($fromme==FALSE && in_array($txrawdetails['vout'][$x]['scriptPubKey']['addresses'][0], $addresslist)==TRUE) {
					$txtoaddress = $txrawdetails['vout'][$x]['scriptPubKey']['addresses'][0];
					$txvalue = $txrawdetails['vout'][$x]['value'];
					$transactionamount = round($txvalue,2);
				}
				
				//From me, to other
				if($fromme==TRUE && in_array($txrawdetails['vout'][$x]['scriptPubKey']['addresses'][0], $addresslist)==FALSE) {
					//If this is a "sendmany" transaction
					if($foundFromMe == TRUE) {
						$txtoaddress = $txrawdetails['vout'][$x]['scriptPubKey']['addresses'][0];
						$txvalue = $txrawdetails['vout'][$x]['value'];
						$transactionamount = $transactionamount + -round($txvalue,2);
					} else {
						$txtoaddress = $txrawdetails['vout'][$x]['scriptPubKey']['addresses'][0];
						$txvalue = $txrawdetails['vout'][$x]['value'];
						$transactionamount = -round($txvalue,2);
						
						//We found a first transaction; set to TRUE in case this is a sendmany transaction
						$foundFromMe = TRUE;
					}
				}
			}
			
			//IGNORED: unconfirmed transactions, don't show them in the list
			/*
			if($txconfirmations<6) {
				$transactiondate = "Unconfirmed (".$txconfirmations.") (".date('d/m/Y H:i', $txtime).")";
			}
			*/
			
			$transactiondate = date('d/m/Y H:i', $txtime);
			$transactionid = "<a href='https://blockchain.gulden.com/tx/".$transactiontxid."' target='_blank' title='".$transactiontxid."'>".substr($transactiontxid, 0, 7)."...</a>";
			$currenttxshown++;
			
			$currenttx['txfromaddress'] = $txfromaddress;
			$currenttx['txtime'] = $txtime;
			$currenttx['txconfirmations'] = $txconfirmations;
			$currenttx['txfee'] = $txfee;
			$currenttx['txtoaddress'] = $txtoaddress;
			$currenttx['transactionamount'] = $transactionamount;
			$currenttx['transactiondate'] = $transactiondate;
			$currenttx['transactionid'] = $transactionid;
			
			$returntx[] = $currenttx;
		}
	}

	return $returntx;
}
?>