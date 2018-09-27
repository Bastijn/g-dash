<div class="row row-offcanvas row-offcanvas-left">

 <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">
   
    <ul class="nav nav-sidebar">
      <li><a href="?page=settings">Settings</a></li>
      <li><a href="?page=upgrade">Upgrade</a></li>
      <li class="active"><a href="?page=configcheck">Config Check</a></li>
      <li><a href="?page=debug">Debug Console</a></li>
      <li><a href="?page=changelog">Changelog</a></li>
    </ul>
 </div><!--/span-->

 <div class="col-sm-9 col-md-10 main">
  
  <!--toggle sidebar button-->
  <p class="visible-xs">
    <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
  </p>
  
  <h1 class="page-header">
    Config Check
    <p class="lead">G-DASH configuration check</p>
  </h1>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>Prerequisites</b></div>
    <div class="panel-body" id="prerequisites">
		<?php echo checkRequiredPackages(); ?>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>Gulden</b></div>
    <div class="panel-body" id="guldenconf">
		<?php
		$guldenconfperms = getFilePermissions(KeyGet($CONFIG, '', 'datadir')."Gulden.conf");
		if($guldenconfperms['exists']) {
			echo "The owner of Gulden.conf is \"".$guldenconfperms['owner']['name']."\"<br>";
			echo "The permissions of Gulden.conf are \"".$guldenconfperms['permissions']."\"<br>";
			if($guldenconfperms['readable']) { echo "<font color='green'>Gulden.conf is readable</font>"; } else { echo "<font color='red'>Gulden.conf is not readable</font>"; }
		} else {
			echo "<font color='red'>Gulden.conf does not exist! Define the correct datadir in the settings.</font>";
		}
		
		echo "<br><br>";
		
		$guldendperms = getFilePermissions(KeyGet($CONFIG, '', 'guldenlocation')."GuldenD");
		if($guldendperms['exists']) {
			echo "The owner of GuldenD is \"".$guldendperms['owner']['name']."\"<br>";
			echo "The permissions of GuldenD are \"".$guldendperms['permissions']."\"<br>";
			if($guldendperms['executable']) { echo "<font color='green'>GuldenD is executable</font>"; } else { echo "<font color='red'>GuldenD is not executable</font>"; }
		} else {
			echo "<font color='red'>GuldenD does not exist! Define the correct Gulden directory in the settings.</font>";
		}
		
		echo "<br><br>";
		
		$guldencliperms = getFilePermissions(KeyGet($CONFIG, '', 'guldenlocation')."Gulden-cli");
		if($guldencliperms['exists']) {
			echo "The owner of Gulden-cli is \"".$guldencliperms['owner']['name']."\"<br>";
			echo "The permissions of Gulden-cli are \"".$guldencliperms['permissions']."\"<br>";
			if($guldencliperms['executable']) { echo "<font color='green'>Gulden-cli is executable</font>"; } else { echo "<font color='red'>Gulden-cli is not executable</font>"; }
		} else {
			echo "<font color='red'>Gulden-cli does not exist! Define the correct Gulden directory in the settings.</font>";
		}
		
		echo "<br><br>";
		
		$guldenlogperms = getFilePermissions(KeyGet($CONFIG, '', 'datadir')."debug.log");
		if($guldenlogperms['exists']) {
			echo "The owner of debug.log is \"".$guldenlogperms['owner']['name']."\"<br>";
			echo "The permissions of debug.log are \"".$guldenlogperms['permissions']."\"<br>";
			echo "The file size of debug.log is \"".round(filesize(KeyGet($CONFIG, '', 'datadir')."debug.log") / pow(1024, 2), 2)."\" MB <br>";
			if($guldenlogperms['readable']) {
				echo "<font color='green'>debug.log is readable</font>"; 
			} else {
				echo "<font color='red'>debug.log is not readable. To make this file readable for G-DASH, use </font><code>chmod 0644 ".KeyGet($CONFIG, '', 'datadir')."debug.log</code>"; }
		} else {
			echo "<font color='red'>debug.log does not exist! Define the correct datadir in the settings.</font>";
		}
		?>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>Listening services</b></div>
    <div class="panel-body" id="listeningservices">
		<?php
		echo getGuldenServices();
		?>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>Full Node port forward</b></div>
    <div class="panel-body" id="portforward">
		<?php
			$checks = fullNodeCheck();
			
			foreach ($checks as $check) {
				echo $check . "<br />";
			}
		?>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>G-DASH</b></div>
    <div class="panel-body" id="gdashconf">
		<?php
		if(!is_writable("config/config.php")) {
			echo "<font color='red'>The configuration file (config/config.php) is not writable. Make sure the webserver (usually 'www-data') has write permissions.<br>
				 You can set www-data as the owner of the G-DASH folder by using the command 'sudo chown -R www-data:www-data /path/to/g-dash/'.</font><br><br>";
		}
		if($guldenconfperms['exists']) {
			$gconfcontents = readGuldenConf(KeyGet($CONFIG, '', 'datadir')."Gulden.conf");
			if($gconfcontents['rpcuser'] == KeyGet($CONFIG, '', 'rpcuser')) {
				echo "<font color='green'>Username entered in G-DASH matches Gulden username</font><br>"; 
			} else {
				echo "<font color='red'>Username entered in G-DASH does not match Gulden username</font><br>"; 
			}
			if($gconfcontents['rpcpassword'] == KeyGet($CONFIG, '', 'rpcpass')) {
				echo "<font color='green'>Password entered in G-DASH matches Gulden password</font><br>"; 
			} else {
				echo "<font color='red'>Password entered in G-DASH does not match Gulden password</font><br>"; 
			}			
		} else {
			echo "<font color='red'>Gulden.conf does not exist! Define the correct datadir in the settings.</font>";
		}
		?>
    </div>
  </div>

  </div><!--/row-->
</div>
