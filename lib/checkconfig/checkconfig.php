<?php
$ERRORS = array();

//Is the config file writable?
if(!is_writable("config/config.php")) {
	$ERRORS[] = "The configuration file (config/config.php) is not writable. Make sure the webserver (usually 'www-data') has write permissions.<br>
	  	 You can set www-data as the owner of the G-DASH folder by using the command 'sudo chown -R www-data:www-data /path/to/g-dash/'.";
}

//Is the dashboard configured?
if($CONFIG['rpcuser']=="" || $CONFIG['configured']!="1") {
	$ERRORS[] = "This dashboard has not been fully configured yet. Go to 'Settings' first.";
}

//TODO: Remove this function at 1.0 release. Used to show the passwords needs to be updated to the new hashing
if($CONFIG['bcrypt']!="1") {
	$ERRORS[] = "The G-DASH password uses an outdated hashing. Please update your G-DASH password in the 'Settings' screen. Your 
				 password must also be updated if you have disabled the login screen.";
}

//Is the system upgraded and are the settings reviewed?
if($CONFIG['dashversion'] != $GDASH['currentversion']) {
	$ERRORS[] = "This dashboard has been upgraded recently. Review and save the settings.";
}

if(count($ERRORS)>0) {
	$errormes = "<br><font color='red' size='4'><b>ERRORS</b></font>
	<font color='red' size='3'><ul>";
	
	$errlist = "";
	foreach ($ERRORS as $errval) {
		$errlist .= "<li>$errval</li>";
	}
	
	$errormes = $errormes.$errlist."</ul></font><br>";
	$errormes = trim(preg_replace('/\s+/', ' ', $errormes));
	
	echo "<script>$('#gdasherrors').html(\"$errormes\");</script>";
} else {
	echo "<script>$('#gdasherrors').html(\"\");</script>";;
}
?>