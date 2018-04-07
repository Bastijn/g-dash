<?php
//Only allow this script to run from PHP CLI, not from HTTP
if (php_sapi_name() == "cli") {
	require_once(__DIR__.'/config/config.php');
	require_once(__DIR__.'/lib/settings/settings.php');
	
	$gdv = $GDASH['currentversion'];
	
	//Check if there are arguments passed to the script, otherwise just return the version info
	if(count($argv)>1) {
		
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
			echo PHP_EOL;
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