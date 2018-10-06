<?php
//start session
session_start();

//TODO: Create functions for version checks below instead of static code in the index page

include('lib/settings/settings.php');
include('config/config.php');
include('lib/functions/functions.php');

if (isset($_GET['logout'])) {
    if ($_GET['logout'] == "true") {
        LoginCheck("", true);
    }
}

if ($CONFIG['otp'] == "1" && $CONFIG['disablelogin'] != "1" && isset($_POST['login'])) {
    require_once("lib/phpotp/rfc6238.php");
    $otpkey = $CONFIG['otpkey'];
    if (TokenAuth6238::verify($otpkey, $_POST['otppassword'])) {
        $loginchecked = LoginCheck($CONFIG['datadir'] . "Gulden.conf", false, $CONFIG['disablelogin']);
    } else {
        $loginchecked = false;
    }
} else {
    $loginchecked = LoginCheck($CONFIG['datadir'] . "Gulden.conf", false, $CONFIG['disablelogin']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-DASH: Gulden Witness Node Dashboard</title>
    <link rel="icon" type="image/png" href="images/gdblue128x128.png"/>
    <base target="_self">
    <meta name="google" value="notranslate">

    <link rel="stylesheet" href="js/bootstrap/css/bootstrap.min.css?<?php echo $CONFIG['dashversion']; ?>"/>
    <link rel="stylesheet" type="text/css" href="style/style.css?<?php echo $CONFIG['dashversion']; ?>">
</head>
<body>
<script src="js/jquery/js/jquery.min.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<script src="js/sonic/jquery.sonic-gauge.min.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<script src="js/sonic/raphael-min.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<script src="js/bootstrap/js/bootstrap.min.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<script src="js/jquery/js/jquery.validate.min.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<script src="js/qrcodejs/qrcode.min.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<script src="https://code.highcharts.com/highcharts.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<script src="https://code.highcharts.com/highcharts-3d.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<script src="js/dash/index.js?<?php echo $CONFIG['dashversion']; ?>"></script>

<script>
    $(document).ready(function () {
        $.ajaxSetup({cache: false});

        $('[data-toggle=offcanvas]').click(function () {
            $('.row-offcanvas').toggleClass('active');
        });
    });
</script>
<?php
if ($_SESSION['G-DASH-loggedin'] == true) {
    ?>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainnavbarcol">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $CONFIG['weblocation']; ?>"><img src="images/gblue64x64.png"
                                                                                          width='20' height='20'
                                                                                          border='0'
                                                                                          style="display:inline;vertical-align:top;">
                    - DASH</a>
            </div>
            <div class="navbar-collapse collapse" id="mainnavbarcol">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="?">Dashboard</a></li>
                    <li><a href="?page=settings">Settings</a></li>
                    <li><a href="?page=about">About</a></li>
                    <?php
                    if ($CONFIG['disablelogin'] != "1") {
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
//Check if there is an update for G-DASH
    $currentversion = $GDASH['currentversion'];
    $latestversionsarray = array();
    $latestversionsarray = @json_decode(file_get_contents($GDASH['updatecheck'] . "?cv=$currentversion"));
    if ($CONFIG['updatechannel'] == "1") {
        $getlatestversion = $latestversionsarray->beta;
    } else {
        $getlatestversion = $latestversionsarray->stable;
    }

    if ($getlatestversion > $currentversion) {
        echo "<div class='alert alert-info'>
	  	  <strong>Update available:</strong> G-DASH version $getlatestversion available. 
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

            $gulden = new Gulden($CONFIG['rpcuser'], $CONFIG['rpcpass'], $CONFIG['rpchost'], $CONFIG['rpcport']);

            if ($gulden->getinfo() == "") {
                $guldenprimaryresponsecode = $gulden->response['error']['code'];

                //Check if GuldenD is upgrading
                if ($guldenprimaryresponsecode != "-28") {
                    //If there is a connection error to the Gulden server
                    echo "<div class='alert alert-danger' id='connectionerror'>
			   <strong>Error:</strong><br>There is a problem connecting to the Gulden server.
			   Check if the server is running (by looking at the CPU/MEM usage) and use the
			   \"Config Check\" in settings to identify the problem.
			  </div>";
                }
            } else {
                //Check if there is an update for Gulden
                $checkversioninfo = $gulden->getinfo();
                $currentguldenversion = $checkversioninfo['version'];
                $guldenversion = $latestversionsarray->gulden;

                if ($currentguldenversion < $guldenversion) {
                    echo "<div class='alert alert-info' id='guldenupdateavailable'>
		   <strong>Gulden update available:</strong><br>There is an update available
		   for Gulden. You are running version <b>$currentguldenversion</b> and the
		   latest version is <b>$guldenversion</b>. If you run G-DASH on a Raspberry
		   Pi with the Gulden repository configured, you can restart your Pi to
		   automatically update to the latest version.
		  </div>";
                }
            }

            //what page are we on?
            $page = "main";
            if (isset($_GET['page'])) {
                if ($_GET['page'] != "" && file_exists($_GET['page'] . ".php")) {
                    $page = str_replace("/", "", $_GET['page']);
                }
            }

            if (count($ERRORS) > 0) {
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
                                    <input type="text" class="form-control" id="rpcuser" name="rpcuser"
                                           placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <label for="rpcpassword">Password</label>
                                    <input type="password" class="form-control" id="rpcpassword" name="rpcpassword"
                                           placeholder="Password">
                                </div>

                                <?php
                                if ($CONFIG['otp'] == "1") {
                                    require_once("lib/phpotp/rfc6238.php");
                                    $otpkey = $CONFIG['otpkey'];
                                    echo "<div class='form-group'>
						        		<label for='otppassword'>Two Factor Authentication code</label>
						        		<input type='password' class='form-control' id='otppassword' name='otppassword' placeholder='2FA code' autocomplete='off'>
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
    <div class="footerspan">G-DASH - by Bastijn - <a href="http://g-dash.nl" target="_blank">G-DASH.nl</a><br>Donations:
        <a href="Gulden:GYuUatrVy5xd26Z2JMqbEATn3Sdourq5Pq">GYuUatrVy5xd26Z2JMqbEATn3Sdourq5Pq</a></div>
</div>

</body>
</html>

<?php
session_write_close();
?>
