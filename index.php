<?php
//start session
session_start();

include('lib/settings/settings.php');
include('config/config.php');
include('lib/functions/functions.php');

if(isset($_GET['logout'])) {
if($_GET['logout']=="true") {
	LoginCheck("", TRUE);
}
}

if($CONFIG['otp']=="1" && $CONFIG['disablelogin'] != "1") {
	require_once("lib/phpotp/rfc6238.php");
	$otpkey = $CONFIG['otpkey'];
	if (TokenAuth6238::verify($otpkey, $_POST['otppassword']))
	{
		$loginchecked = LoginCheck($CONFIG['datadir']."Gulden.conf", FALSE, $CONFIG['disablelogin']);
	} else {
		$loginchecked = FALSE;
	}
} else {
	$loginchecked = LoginCheck($CONFIG['datadir']."Gulden.conf", FALSE, $CONFIG['disablelogin']);
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>G-DASH: Gulden Witness Node Dashboard</title>
  <link rel="icon" type="image/png" href="images/gblue128x128.png" />
  <base target="_self">
  <meta name="google" value="notranslate">

  <link rel="stylesheet" href="js/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
  <script src="js/jquery/js/jquery.min.js"></script>
  <script src="js/sonic/jquery.sonic-gauge.min.js"></script>
  <script src="js/sonic/raphael-min.js"></script>
  <script src="js/bootstrap/js/bootstrap.min.js"></script>
  <script src="js/jquery/js/jquery.validate.min.js"></script>
  <script src="js/qrcodejs/qrcode.min.js"></script>
  
  <script>
  $(document).ready(function() {
  	  $.ajaxSetup({ cache: false });
  	
	  $('[data-toggle=offcanvas]').click(function() {
	    $('.row-offcanvas').toggleClass('active');
	});
  });
  </script>
  <?php
  if($_SESSION['G-DASH-loggedin']==TRUE) {
  ?>
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $CONFIG['weblocation']; ?>"><img src="images/gblue64x64.png" width='20' height='20' border='0' style="display:inline;vertical-align:top;"> - DASH</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="?">Dashboard</a></li>
            <li><a href="?page=settings">Settings</a></li>
            <li><a href="?page=about">About</a></li>
            <?php
            if($CONFIG['disablelogin']!="1") {
            ?>
            <li><a href="<?php echo "?logout=true"; ?>">Log out</a></li>
            <?php
			}
			?>
          </ul>
        </div>
      </div>
</nav>

<?php
$currentversion = $GDASH['currentversion'];
$latestversionsarray = array();
$latestversionsarray = @json_decode(file_get_contents($GDASH['updatecheck']));
if($CONFIG['updatechannel']=="1") {
	$getlatestversion = $latestversionsarray->beta;
} else {
	$getlatestversion = $latestversionsarray->stable;
}

if($getlatestversion > $currentversion) {
	echo "<div class='alert alert-info'>
	  	  <strong>Update available:</strong> G-DASH version $updateCheck available. 
	  	  <a href='?page=upgrade'>Click here to update</a>.
		  </div>";
}
?>

<div class="container-cont">
<div class="container-fluid">
<div id="gdasherrors"></div>

<?php
include('lib/checkconfig/checkconfig.php');
require_once('lib/EasyGulden/easygulden.php');

$gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);

if($gulden->getinfo()=="") {
	echo "<div class='alert alert-danger' id='connectionerror'>
		   <strong>Error:</strong><br>There is a problem connecting to the Gulden server.
		   Check if the server is running (by looking at the CPU/MEM usage) and use the
		   \"Config Check\" in settings to identify the problem.
		  </div>";
}

//what page are we on?
$page = "main";
if(isset($_GET['page']))
{
	if($_GET['page']!="" && file_exists($_GET['page'] . ".php"))
	{
		$page = str_replace("/", "", $_GET['page']);
	}
}

if(count($ERRORS)>0) {
	$page = "settings";
} 
include($page . ".php"); //include the selected page
?>

</div><!--/.container-->
</div>

<?php
} else {
	?>
	<div class="container-cont">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
				
				<div class="panel panel-default">
				    <div class="panel-heading"><b>G-DASH Login</b></div>
				    <div class="panel-body" id="loginpanel">
				    	
						<form method="POST" action="?">
						  <div class="form-group">
						    <label for="rpcuser">Username</label>
						    <input type="text" class="form-control" id="rpcuser" name="rpcuser" placeholder="Username">
						  </div>
						  <div class="form-group">
						    <label for="rpcpassword">Password</label>
						    <input type="password" class="form-control" id="rpcpassword" name="rpcpassword" placeholder="Password">
						  </div>
						  
						  <?php
						  if($CONFIG['otp']=="1") {
							  require_once("lib/phpotp/rfc6238.php");
							  $otpkey = $CONFIG['otpkey'];
							  echo "<div class='form-group'>
						        		<label for='otppassword'>Two Factor Authentication code</label>
						        		<input type='password' class='form-control' id='otppassword' name='otppassword' placeholder='2FA code'>
						      		</div>";
						  }
						  ?>
						  
						  <button type="submit" class="btn btn-primary" id="login" name="login">Login</button>
						</form>
						
				    </div>
				</div>
				
			</div>
			<div class="col-md-4">
			</div>
		</div>
	</div>
	</div>
	<?php
}
?>

<div class="footer">
	<div class="footerspan">G-DASH - by Bastijn - <a href="http://g-dash.nl" target="_blank">G-DASH.nl</a><br>Donations: <a href="Gulden:GYuUatrVy5xd26Z2JMqbEATn3Sdourq5Pq">GYuUatrVy5xd26Z2JMqbEATn3Sdourq5Pq</a></div>
</div>

</body>
</html>

<?php
session_write_close();
?>
