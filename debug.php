<script src="js/terminal/js/jquery.terminal.min.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<script src="js/terminal/js/runterm.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<link rel="stylesheet" type="text/css" href="js/terminal/css/jquery.terminal.min.css">

<div class="row row-offcanvas row-offcanvas-left">

 <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">
   
    <ul class="nav nav-sidebar">
      <li><a href="?page=settings">Settings</a></li>
      <li><a href="?page=upgrade">Upgrade</a></li>
      <li><a href="?page=configcheck">Config Check</a></li>
      <li class="active"><a href="?page=debug">Debug Console</a></li>
      <li><a href="?page=changelog">Changelog</a></li>
    </ul>
 </div><!--/span-->

 <div class="col-sm-9 col-md-10 main">
  
  <!--toggle sidebar button-->
  <p class="visible-xs">
    <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
  </p>
  
  <?php
  //Only show this page if a user is logged in
  if($_SESSION['G-DASH-loggedin']==TRUE) {
  ?>
  
  <h1 class="page-header">
    Debug Console
    <p class="lead">Gulden Debug Console</p>
  </h1>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>Console</b></div>
    <div class="panel-body" id="console">
  		<div id="term"></div>
  	</div>
  </div>
<?php } ?>
  </div><!--/row-->
</div>
