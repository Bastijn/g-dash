<script src="js/dash/settings.js"></script>

<div class="row row-offcanvas row-offcanvas-left">

 <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">
   
    <ul class="nav nav-sidebar">
      <li class="active"><a href="?page=settings">Settings</a></li>
      <li><a href="?page=upgrade">Upgrade</a></li>
      <li><a href="?page=configcheck">Config Check</a></li>
      <li><a href="?page=changelog">Changelog</a></li>
    </ul>
 </div><!--/span-->

 <div class="col-sm-9 col-md-10 main">
  
  <!--toggle sidebar button-->
  <p class="visible-xs">
    <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
  </p>
  
  <?php
  //If the settings are updated
  if(isset($_POST['weblocation'])) {
	
  	$CONFIG['weblocation'] = AddTrailingSlash($_POST['weblocation']);
	$CONFIG['guldenlocation'] = AddTrailingSlash($_POST['glocation']);
	$CONFIG['datadir'] = AddTrailingSlash($_POST['datalocation']);
	
	$CONFIG['rpcuser'] = $_POST['rpcuser'];
	$CONFIG['rpcpass'] = $_POST['rpcpassword'];
	
	//TODO: REMOVE BEFORE VERSION 1.0. THIS IS TO REMOVE THE 2FA CODE AS IT WAS BUGGED IN VERSION 0.21
	if($_POST['otpbug']=="1") {
		$CONFIG['otp'] = "0";
		$CONFIG['otpkey'] = "";
	} else {
	
		$CONFIG['otp'] = $_POST['otp'];
		if($_POST['otpkey']!="") { $CONFIG['otpkey'] = $_POST['otpkey']; }
	
	}
	
	$CONFIG['gdashuser'] = $_POST['gdashuser'];
	if($_POST['gdashpassword']!="") {
		 $originalpassworddash = $CONFIG['gdashpassword'];
  		 $newpassworddash = md5(sha1($_POST['gdashpassword']));
  		 
		 if($originalpassworddash != $newpassworddash) {
		 	//Disable 2FA if the password has changed.
			$CONFIG['otp'] = "0";
		 }
		 
		 $CONFIG['gdashpassword'] = md5(sha1($_POST['gdashpassword'])); 
	}
	if($_POST['rpchost']=="") { $CONFIG['rpchost'] = "127.0.0.1"; } else { $CONFIG['rpchost'] = $_POST['rpchost']; }
	if($_POST['rpcport']=="") { $CONFIG['rpcport'] = "9232"; } else { $CONFIG['rpcport'] = $_POST['rpcport']; }
	
	$CONFIG['configured'] = "1";
	$CONFIG['dashversion'] = $GDASH['currentversion'];
	
	$CONFIG['disablelogin'] = $_POST['disablelogin'];
	if($_POST['disablelogin']=="1") { $CONFIG['otp'] = "0"; }
	
	$CONFIG['updatechannel'] = $_POST['updatechannel'];
	$CONFIG['nodeupload'] = $_POST['nodeupload'];
	
	$CONFIG['pushbullet'] = $_POST['pushbullet'];
	$CONFIG['pushbulletgulden']['active'] = $_POST['pushbulletgulden'];
	if($CONFIG['pushbulletgulden']['lastmes']=="") { $CONFIG['pushbulletgulden']['lastmes'] = ""; }
	$CONFIG['pushbulletgdash']['active'] = $_POST['pushbulletgdash'];
	if($CONFIG['pushbulletgdash']['lastmes']=="") { $CONFIG['pushbulletgdash']['lastmes'] = ""; }
	$CONFIG['pushbullettx']['active'] = $_POST['pushbullettx'];
	if($CONFIG['pushbullettx']['lastmes']=="") { $CONFIG['pushbullettx']['lastmes'] = ""; }
	$CONFIG['pushbulletguldenupdate']['active'] = $_POST['pushbulletguldenupdate'];
	if($CONFIG['pushbulletguldenupdate']['lastmes']=="") { $CONFIG['pushbulletguldenupdate']['lastmes'] = ""; }
	
	//******************//
	//**CRON FOR STATS**//
	//******************//
	
	//Check the crontab for stats upload
	$nodestatscron = exec("crontab -l | grep -q 'getpeerinfo' && echo '1' || echo '0'");
	
	//Twice per hour, random between 1-600 seconds
	$randomstatstime = rand(1,600);
	$nodestatscronentry = "*/10 * * * * sleep ".$randomstatstime."; ".$CONFIG['guldenlocation']."Gulden-cli -datadir=".$CONFIG['datadir']." getpeerinfo | curl -X POST -H \"Content-Type:application/json\" -d @- https://guldennodes.com/endpoint/ >/dev/null 2>&1";
	$currentcron = explode(PHP_EOL, shell_exec('crontab -l'));
	
	if($nodestatscron=="0" && $CONFIG['nodeupload']=="1") {
		
		//If not available and user wants to upload stats
		$currentcron[] = $nodestatscronentry;
	
	} elseif($nodestatscron=="1" && $CONFIG['nodeupload']=="1") {
		
		//If available and user wants to upload stats
		for($i=0; $i < count($currentcron); $i++) { //Update current entry in case anything changed (path, ...)
			if (strpos($currentcron[$i], 'getpeerinfo') !== false) {
				$currentcron[$i] = $nodestatscronentry;
			}
		}
		
	} elseif($nodestatscron=="1" && $CONFIG['nodeupload']=="0") {
		
		//If available and user doesn't want to upload stats
		//Find entry and remove it
		for($i=0; $i < count($currentcron); $i++) {
			if (strpos($currentcron[$i], 'getpeerinfo') !== false) {
				unset($currentcron[$i]);
			}
		}
		
	}
	
	//Remove empty array elements
	$currentcron = array_filter($currentcron);
	
	//Update the cron tab
	$cronstr = implode("\n", $currentcron);
	
	if(file_put_contents('/tmp/crontab.txt', $cronstr.PHP_EOL)) {
		$out = shell_exec('crontab /tmp/crontab.txt');
		unlink('/tmp/crontab.txt');
	} else {
		echo "<div class='alert alert-warning'>
		  		<strong>Error:</strong> Could not write crontab for Node Stats. Please try saving your settings again.
			  </div>";
	}
	
	
	
	//***********************//
	//**CRON FOR PUSHBULLET**//
	//***********************//
	
	//Check the crontab for pushbullet notifications
	$pushbulletcron = exec("crontab -l | grep -q 'cronnotifications.php' && echo '1' || echo '0'");
	
	//Every five minutes
	$pushbulletcronentry = "*/2 * * * * php ".__DIR__."/lib/push/cronnotifications.php >/dev/null 2>&1";
	$currentcron = explode(PHP_EOL, shell_exec('crontab -l'));
	
	if($pushbulletcron=="0" && $CONFIG['pushbullet']!="") {
		
		//If not available and user wants to receive notifications
		$currentcron[] = $pushbulletcronentry;
	
	} elseif($pushbulletcron=="1" && $CONFIG['pushbullet']!="") {
		
		//If available and user wants to receive notifications
		for($i=0; $i < count($currentcron); $i++) { //Update current entry in case anything changed (path, ...)
			if (strpos($currentcron[$i], 'cronnotifications.php') !== false) {
				$currentcron[$i] = $pushbulletcronentry;
			}
		}
		
	} elseif($pushbulletcron=="1" && $CONFIG['pushbullet']=="") {
		
		//If available and user doesn't want to receive notifications
		//Find entry and remove it
		for($i=0; $i < count($currentcron); $i++) {
			if (strpos($currentcron[$i], 'cronnotifications.php') !== false) {
				unset($currentcron[$i]);
			}
		}
		
	}
	
	//Remove empty array elements
	$currentcron = array_filter($currentcron);
	
	//Update the cron tab
	$cronstr = implode("\n", $currentcron);
	
	if(file_put_contents('/tmp/crontabpb.txt', $cronstr.PHP_EOL)) {
		$out = shell_exec('crontab /tmp/crontabpb.txt');
		unlink('/tmp/crontabpb.txt');
	} else {
		echo "<div class='alert alert-warning'>
		  		<strong>Error:</strong> Could not write crontab for PushBullet. Please try saving your settings again.
			  </div>";
	}
	
	
	
	
	if(file_put_contents('config/config.php', '<?php $CONFIG = '.var_export($CONFIG, true).'; ?>')) {
		echo "<div class='alert alert-success'>
		  		<strong>Success:</strong> Configuration file saved.
			  </div>";
			  
		include('lib/checkconfig/checkconfig.php');
		if(($originalpassworddash != $newpassworddash) && $newpassworddash!="") {
			//If the password was changed, logout and let the user login again with the new password
			LoginCheck("", TRUE);
			?>
			<script>
			$(document).ready(function() {
				$("#logoutmodal").modal()
			});
			</script>
			  <div class="modal fade" id="logoutmodal" role="dialog">
			    <div class="modal-dialog">
			    
			      <!-- Modal content-->
			      <div class="modal-content">
			        <div class="modal-header">
			          <h4 class="modal-title">Password changed</h4>
			        </div>
			        <div class="modal-body">
			          <p>If you have not disabled the login screen, you will be automatically logged out when clicking OK. 
			          	 You have to log in with your new username and password. Note that Two Factor Authentication
			          	 has been disabled as you changed your password. You can safely turn this back on when you
			          	 are logged in again.</p>
			        </div>
			        <div class="modal-footer">
			          <button type="button" id="changedpass" class="btn btn-default" data-dismiss="modal">OK</button>
			        </div>
			      </div>
			      
			    </div>
			  </div>
			<?php
		}
	} else {
		echo "<div class='alert alert-warning'>
		  		<strong>Error:</strong> Configuration file not saved. Is the file writable?
			  </div>";
	}
  }
  
  ?>
  
  <h1 class="page-header">
    Settings
    <p class="lead">G-DASH settings (Version <?php echo $CONFIG['dashversion']; ?>)</p>
  </h1>
  
  <form method="POST" action="?page=settings" id="settingsform">
  <div class="panel panel-default">
    <div class="panel-heading"><b>Dashboard settings</b></div>
    <div class="panel-body" id="dashboardsettings">
      <div class="form-group">
	    <label for="gdashuser">G-DASH username</label>
	    <input type="text" class="form-control" id="gdashuser" name="gdashuser" placeholder="Username" <?php if($CONFIG['gdashuser']!='') { echo "value='".$CONFIG['gdashuser']."'"; } ?>>
	  </div>
	  
	  <div class="form-group">
	    <label for="gdashpassword">G-DASH password</label>
	    <input type="password" class="form-control" id="gdashpassword" name="gdashpassword" placeholder="Password">
	  </div>
	  
	  <div class="form-group">
	    <label for="gdashpasswordrepeat">Repeat G-DASH password</label>
	    <input type="password" class="form-control" id="gdashpasswordrepeat" name="gdashpasswordrepeat" aria-describedby="gdashpasswordrepeathelp" placeholder="Password">
	    <small id="gdashpasswordrepeathelp" class="form-text text-muted">Choose a strong password!! Minimum of 8 characters, 1 uppercase letter and 1 number.</small>
	  </div>
    	
      <div id="passworderror" name="passworderror"></div>
    	
      <div class="form-group">
	    <label for="weblocation">Dashboard web address</label>
	    <input type="text" class="form-control" id="weblocation" name="weblocation" aria-describedby="weblocationhelp" placeholder="Enter web address" <?php if($CONFIG['weblocation']!='') { echo "value='".$CONFIG['weblocation']."'"; } ?>>
	    <small id="weblocationhelp" class="form-text text-muted">For example: http://192.168.2.1/gulden/</small>
	  </div>
	  
	  <div class="checkbox">
	    <label>
	    <input type="checkbox" id="disablelogin" name="disablelogin" aria-describedby="disableloginhelp" value="1" <?php if($CONFIG['disablelogin']=="1") { echo "checked='checked'"; } ?>>Disable login screen</label><br>
	    <small id="disableloginhelp" class="form-text text-muted">Note: By disabling the login screen, everyone on your network (or if your server can be reached 
	                                                              from the internet --> EVERYONE) can access this dashboard</small>
	  </div>
	  
	  <div class="checkbox">
	    <label>
	    <input type="checkbox" id="updatechannel" name="updatechannel" aria-describedby="updatechannelhelp" value="1" <?php if($CONFIG['updatechannel']=="1") { echo "checked='checked'"; } ?>>Use the Beta update channel</label><br>
	    <small id="updatechannelhelp" class="form-text text-muted">The Beta updates may contain bugs. Use this option only if you want to help testing!</small>
	  </div>
	  
	  <?php if($CONFIG['disablelogin']!="1") {
		require_once("lib/phpotp/rfc6238.php");
		if($CONFIG['otpkey']=="") {
			$randomkeyforotp = hash("sha1", rand(999,999999), false);
			$otpkey = Base32Static::encode($randomkeyforotp);
			echo "<input type='hidden' name='otpkey' id='otpkey' value='".$otpkey."'>";
		} else {
			$otpkey = $CONFIG['otpkey'];
		}
	  ?>
	  <div class="checkbox">
	    <label>
	    <input type="checkbox" id="otp" name="otp" aria-describedby="otphelp" value="1" <?php if($CONFIG['otp']=="1") { echo "checked='checked'"; } ?>>Use 2-factor authentication</label><br>
	    <small id="otphelp" class="form-text text-muted">With 2-factor authentication your dashboard is better protected as you will need your smartphone to log in.<br>
	    												 Note: You will not be able to log in without your smartphone. If you lost your phone (or broke it), you will have
	    												 to manually remove 2FA from the G-DASH settings file (OTP).</small>
	    </div>
	    
	    <?php
    	echo "<br><br>Scan this code using a 2FA app on your phone (for example Google Authenticator <a href='https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en' target='_blank'>for Android</a> 
    		  or <a href='https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8' target='_blank'>for iOS</a>)<br>";
    	$currentDomain = $_SERVER['HTTP_HOST'];
		print sprintf('<img src="%s"/>',TokenAuth6238::getBarCodeUrl('', $currentDomain, $otpkey, 'G-DASH'));
	    } 
	    ?>
	    
	    <div class="checkbox">
	    <label>
	    <input type="checkbox" id="otpbug" name="otpbug" aria-describedby="otpbughelp" value="1">Reset 2FA QR if bugged on Google Authenticator</label><br>
	    <small id="otpbughelp" class="form-text text-muted"><font color='red'>On version 0.21 a bug was found that prevented the QR code from working on the Google
	    												 Authenticator app. If you have problems with the QR code not being recognized by this
	    												 program, check this box. 2FA will be disabled and a new key and QR code will be
	    												 generated. <b>NOTE THAT THE PREVIOUS QR CODE AND KEY WILL NOT WORK ANYMORE!!</b>
	    												 This function will be removed before version 1.0, so make sure you update your key before that.</font></small>
	    </div>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>Notifications</b></div>
    <div class="panel-body" id="notifications">
    	
      <div class="form-group">
	    <label for="pushbullet">PushBullet access token</label>
	    <input type="text" class="form-control" id="pushbullet" name="pushbullet" aria-describedby="pushbullethelp" placeholder="Enter access token" <?php if($CONFIG['pushbullet']!='') { echo "value='".$CONFIG['pushbullet']."'"; } ?>>
	    <small id="pushbullethelp" class="form-text text-muted">Using PushBullet (free), you can send notifications to your phone, tablet, smartwatch and computer when one of the actions below
	    														are triggered. Check <a href='https://www.pushbullet.com' target='_blank'>pushbullet.com</a> for more details
	    														and to set up an account. If you have an account, you can create an access token in the 
	    														<a href='https://www.pushbullet.com/#settings/account' target='_blank'>account settings page</a>
	    														to monitor G-DASH. Clear the access token field to stop receiving updates or un-check all items below. 
	    														Check is performed every 5 minutes.</small>
	  </div>
	  
	  <div class="checkbox">
	      <label>
	      <input type="checkbox" id="pushbulletgulden" name="pushbulletgulden" aria-describedby="pushbulletguldenhelp" value="1" <?php if($CONFIG['pushbulletgulden']['active']=="1") { echo "checked='checked'"; } ?>>Send a notification if the Gulden server is down.</label><br>
	      <small id="pushbulletguldenhelp" class="form-text text-muted">A notification will be sent to pushbullet if the Gulden server is not responding.<br>
	      																Last message pushed: <?php echo $CONFIG['pushbulletgulden']['lastmes']; ?>
	      </small>
	  </div>
	  
	  <div class="checkbox">
	      <label>
	      <input type="checkbox" id="pushbulletgdash" name="pushbulletgdash" aria-describedby="pushbulletgdashhelp" value="1" <?php if($CONFIG['pushbulletgdash']['active']=="1") { echo "checked='checked'"; } ?>>Send a notification if an update of G-DASH is available.</label><br>
	      <small id="pushbulletgdashhelp" class="form-text text-muted">A notification will be sent to pushbullet if an update is available for G-DASH.<br>
	      																Last message pushed: <?php echo $CONFIG['pushbulletgdash']['lastmes']; ?>
	      </small>
	  </div>
	  
	  <div class="checkbox">
	      <label>
	      <input type="checkbox" id="pushbulletguldenupdate" name="pushbulletguldenupdate" aria-describedby="pushbulletguldenupdatehelp" value="1" <?php if($CONFIG['pushbulletguldenupdate']['active']=="1") { echo "checked='checked'"; } ?>>Send a notification when there is an update for Gulden.</label><br>
	      <small id="pushbulletguldenupdatehelp" class="form-text text-muted">A notification will be sent to pushbullet when an update for Gulden is available in the Raspbian repository.<br>
	      																		Last message pushed: <?php echo $CONFIG['pushbulletguldenupdate']['lastmes']; ?>
	      </small>
	  </div>
	  
	  <div class="checkbox">
	      <label>
	      <input type="checkbox" id="pushbullettx" name="pushbullettx" aria-describedby="pushbullettxhelp" value="1" <?php if($CONFIG['pushbullettx']['active']=="1") { echo "checked='checked'"; } ?>>Send a notification when Guldens are received.</label><br>
	      <small id="pushbullettxhelp" class="form-text text-muted">A notification will be sent to pushbullet when you receive Guldens in your wallet.<br>
	      																Last message pushed: <?php echo $CONFIG['pushbullettx']['lastmes']; ?>
	      </small>
	  </div>
	  
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>Node settings</b></div>
    <div class="panel-body" id="nodesettings">
      <div id='nodeuploaddiv' class="checkbox">
	    <label>
	    <input type="checkbox" id="nodeupload" name="nodeupload" aria-describedby="nodeuploadhelp" value="1" <?php if($CONFIG['nodeupload']=="1") { echo "checked='checked'"; } ?>>Upload node statistics</label><br>
	    <small id="nodeuploadhelp" class="form-text text-muted">Help the Gulden network by uploading your node statistics. You can see the stats
	    														of your node and a spider web showing all your connections on 
	    														<a href="https://guldennodes.com/?crawler" target="_blank">
	    														https://guldennodes.com/?crawler</a><br>(Note: This link only works if you visit 
	    														this website from the same network as your node. Otherwise, use "?crawler=YourIP")</small>
	  </div>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>Gulden settings</b></div>
    <div class="panel-body" id="guldensettings">
  
	  <div class="form-group">
	    <label for="glocation">GuldenD location</label>
	    <input type="text" class="form-control" id="glocation" name="glocation" aria-describedby="glocationhelp" placeholder="Enter path to GuldenD" <?php if($CONFIG['guldenlocation']!='') { echo "value='".$CONFIG['guldenlocation']."'"; } ?>>
	    <small id="glocationhelp" class="form-text text-muted">The folder containing the GuldenD. For example: /opt/Gulden/Gulden-1.6.4/</small>
	  </div>
	  <div class="form-group">
	    <label for="datalocation">Data location</label>
	    <input type="text" class="form-control" id="datalocation" name="datalocation" aria-describedby="datalocationhelp" placeholder="Enter path to Gulden.conf" <?php if($CONFIG['datadir']!='') { echo "value='".$CONFIG['datadir']."'"; } ?>>
	    <small id="datalocationhelp" class="form-text text-muted">The folder containing Gulden.conf. For example: /opt/Gulden/datadir/</small>
	  </div>
	  <div class="form-group">
	    <label for="rpcuser">RPC username</label>
	    <input type="text" class="form-control" id="rpcuser" name="rpcuser" placeholder="Username" <?php if($CONFIG['rpcuser']!='') { echo "value='".$CONFIG['rpcuser']."'"; } ?>>
	  </div>
	  <div class="form-group">
	    <label for="rpcpassword">RPC password</label>
	    <input type="password" class="form-control" id="rpcpassword" name="rpcpassword" placeholder="Password" <?php if($CONFIG['rpcpass']!='') { echo "value='".$CONFIG['rpcpass']."'"; } ?>>
	    <small id="rpcpasswordrepeathelp" class="form-text text-muted">Note: The username and password must match the username and password used in the Gulden.conf file</small>
	  </div>
	  <div class="form-group">
	    <label for="rpchost">Host address</label>
	    <input type="text" class="form-control" id="rpchost" name="rpchost" aria-describedby="rpchosthelp" placeholder="RPC Host" <?php if($CONFIG['rpchost']!='') { echo "value='".$CONFIG['rpchost']."'"; } ?>>
	    <small id="rpchosthelp" class="form-text text-muted">The RPC host address of the GuldenD. Default: 127.0.0.1</small>
	  </div>
	  <div class="form-group">
	    <label for="rpcport">Host port</label>
	    <input type="text" class="form-control" id="rpcport" name="rpcport" aria-describedby="rpcporthelp" placeholder="RPC Port" <?php if($CONFIG['rpcport']!='') { echo "value='".$CONFIG['rpcport']."'"; } ?>>
	    <small id="rpcporthelp" class="form-text text-muted">The RPC port number of GuldenD. Default: 9232</small>
	  </div>
  
    </div>
  </div>
  
  <button type="submit" class="btn btn-primary" id="savesettings">Submit</button>
</form>

  </div><!--/row-->
</div>
