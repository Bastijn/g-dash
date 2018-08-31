<?php
//Only allow this script to run from PHP CLI, not from HTTP
if (php_sapi_name() == "cli") {
	require_once(__DIR__.'/config/config.php');
	require_once(__DIR__.'/lib/settings/settings.php');
	require_once(__DIR__.'/lib/EasyGulden/easygulden.php');
	require_once(__DIR__.'/lib/functions/functions.php');
	
	$gdv = $GDASH['currentversion'];
	$currentUser = posix_getpwuid((posix_geteuid()))['name'];
	
	//Check if there are arguments passed to the script, otherwise just return the version info
	if(count($argv)>1) {
		
		//Script must run as root user
		if($currentUser == "root") {
		
			//If argument is to reset 2FA
			if($argv[1]=="reset_2fa") {
				$CONFIG['otp']="0";
				
				if(is_writable(__DIR__.'/config/config.php')) {
					if(file_put_contents(__DIR__.'/config/config.php', '<?php $CONFIG = '.var_export($CONFIG, true).'; ?>')) {
						echo "Two Factor Authentication reset." . PHP_EOL . PHP_EOL;
					} else {
						echo "Could not write config file." . PHP_EOL . PHP_EOL;
					}
				} else {
					echo "Config file is not writable. Did you run it as the web user?." . PHP_EOL . PHP_EOL;
				}
			
			//If argument is to reset password
			} elseif($argv[1]=="reset_login") {
				$CONFIG['disablelogin']="1";
				$CONFIG['otp']="0";
				
				if(is_writable(__DIR__.'/config/config.php')) {
					if(file_put_contents(__DIR__.'/config/config.php', '<?php $CONFIG = '.var_export($CONFIG, true).'; ?>')) {
						echo "Login and 2FA disabled. Choose a new password and re-enable login." . PHP_EOL . PHP_EOL;
					} else {
						echo "Could not write config file." . PHP_EOL . PHP_EOL;
					}
				} else {
					echo "Config file is not writable. Did you run it as the web user?." . PHP_EOL . PHP_EOL;
				}
			
			//If argument is to reset the Gulden chain
			} elseif($argv[1]=="reset_blockchain") {
				
				//Connect to GuldenD
				$gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);
				
				//Stop GuldenD
				$ginfo = $gulden->stop();
				
				//Check for errors
				$gresponse = $gulden->response['error']['message'];
				if($gresponse=="") {
					echo "Stopping GuldenD. Please wait..." . PHP_EOL;
					
					//Wait for 10 seconds for GuldenD to stop
					sleep(10);
					
					//Get the datadir from the config
					$datadir = $CONFIG['datadir'];
					
					//Delete the blocks directory if exists
					if(is_dir($datadir."blocks")) {
						if(rrmdir($datadir."blocks")) {
							echo "Deleted blocks folder." . PHP_EOL;
						} else {
							echo "Could not delete blocks folder." . PHP_EOL;
						}
					}
					
					//Delete the chainstate directory if exists
					if(is_dir($datadir."chainstate")) {
						if(rrmdir($datadir."chainstate")) {
							echo "Deleted chainstate folder." . PHP_EOL;
						} else {
							echo "Could not delete chainstate folder." . PHP_EOL;
						}
					}
					
					//Delete the witstate directory if exists
					if(is_dir($datadir."witstate")) {
						if(rrmdir($datadir."witstate")) {
							echo "Deleted witstate folder." . PHP_EOL;
						} else {
							echo "Could not delete witstate folder." . PHP_EOL;
						}
					}
				} else {
					echo $gresponse . PHP_EOL;
				}
				
				echo PHP_EOL;
				
			//If the argument is help
			} elseif($argv[1]=="help") {			
				echo "-----------------------------------------------" . PHP_EOL;
				echo "         G-DASH Command Line Interface         " . PHP_EOL;
				echo "              G-DASH version $gdv              " . PHP_EOL;
				echo "            By Bastijn - g-dash.nl             " . PHP_EOL;
				echo "-----------------------------------------------" . PHP_EOL;
				echo "Available commands:" . PHP_EOL;
				echo "help - Shows this list of commands" . PHP_EOL;
				echo "reset_2fa - Disable the Two Factor Authentication" . PHP_EOL;
				echo "reset_login - Disable 2FA and login screen" . PHP_EOL;
				echo "reset_blockchain - Clear blockchain and resync" . PHP_EOL;
				echo PHP_EOL;
			}
		} else {
			echo "These commands should be run as root with sudo." . PHP_EOL;
		}
		
	} else {
		echo "-----------------------------------------------" . PHP_EOL;
		echo "         G-DASH Command Line Interface         " . PHP_EOL;
		echo "              G-DASH version $gdv              " . PHP_EOL;
		echo "            By Bastijn - g-dash.nl             " . PHP_EOL;
		echo "-----------------------------------------------" . PHP_EOL;
		echo "Use 'help' to show the available commands." . PHP_EOL;
		echo PHP_EOL;
	}

} else {
	echo "This script can be run from the command line only" . PHP_EOL . PHP_EOL;
}
?>