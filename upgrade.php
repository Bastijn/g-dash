<div class="row row-offcanvas row-offcanvas-left">

 <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">
   
    <ul class="nav nav-sidebar">
      <li><a href="?page=settings">Settings</a></li>
      <li class="active"><a href="?page=upgrade">Upgrade</a></li>
      <li><a href="?page=configcheck">Config Check</a></li>
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
    Upgrade
    <p class="lead">Auto upgrade to newest version</p>
  </h1>
  
  <?php
  $currentversion = $GDASH['currentversion'];
  $latestversionsarray = array();
  $latestversionsarray = @json_decode(file_get_contents($GDASH['updatecheck']));
  
  if($CONFIG['updatechannel']=="1") {
	$getlatestversion = $latestversionsarray->beta;
	$releasenotes = $latestversionsarray->betacl;
  } else {
	$getlatestversion = $latestversionsarray->stable;
	$releasenotes = $latestversionsarray->stablecl;
  }
  
  if($_GET['upgrade']==$getlatestversion)
  {
  	echo "Upgrading!<br><br>";
	
	$output = shell_exec("wget ".$GDASH['updatelocation']."G-DASH-".$getlatestversion.".tar.gz -P ".getcwd()."/updater && 
			   tar -xvf ".getcwd()."/updater/G-DASH-".$getlatestversion.".tar.gz --directory ".getcwd()." && 
			   rm ".getcwd()."/updater/G-DASH-".$getlatestversion.".tar.gz");
	
	echo "Upgrade complete!<br>";
	echo "<a href='?page=settings'>Click here to review your settings</a><br><br>";
	
	echo "List of upgraded files:<br>";
	echo "<pre>$output</pre>";
	
  } else {
  	if($getlatestversion > $currentversion) {
  		if(!is_writable(getcwd())) {
			echo "<font color='red'>A new version is available, but the auto updater can't run as the webserver can't write to
				 the G-DASH folder. Make sure the webserver (usually 'www-data') is the owner or has write permissions.</font><br>";
		} else {
  		  	echo "New version of G-DASH available ($getlatestversion). You are running $currentversion.<br>
	  	     <a href='?page=upgrade&upgrade=$getlatestversion'>Upgrade now</a><br><br>
	  	     <div class=\"panel panel-default\">
		     <div class=\"panel-heading\"><b>Changed in $getlatestversion</b></div>
		     <div class=\"panel-body\">
				<ul>
	  	     		$releasenotes
				</ul>
			 </div>
			 </div>";
		}
	  } else {
	  	echo "You are running the latest version of G-DASH.";
	  }
  }
  ?>
  </div><!--/row-->
</div>
