<script src="js/dash/node.js?<?php echo $CONFIG['dashversion']; ?>"></script>

<div class="row row-offcanvas row-offcanvas-left">

 <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">
   
    <ul class="nav nav-sidebar">
      <li><a href="?">Overview</a></li>
      <li><a href="?page=guldend">GuldenD</a></li>
      <li class="active"><a href="?page=node">Node</a></li>
      <li><a href="?page=wallet">Wallet</a></li>
      <li><a href="?page=witness">Witness</a></li>
    </ul>
 </div><!--/span-->

 <div class="col-sm-9 col-md-10 main">
  
  <!--toggle sidebar button-->
  <p class="visible-xs">
    <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
  </p>
  
  <h1 class="page-header">
    Node
    <p class="lead">Node info</p>
  </h1>
  
  <div id="errordiv"></div>
  
  <div class="panel panel-default">
    <div class="panel-heading">Node info</div>
    <div class="panel-body" id="serverinfopanel">
    	<img src="images/loading.gif" border="0" height="64" width="64"> Loading....
    </div>
  </div>
  <hr>
  
  <div class="panel panel-default">
    <div class="panel-heading">Node configuration (Gulden.conf)</div>
    <div class="panel-body" id="nodeconfig">
    	<?php
    		$nodeconfig = readGuldenConf($CONFIG['datadir']."Gulden.conf");
			if($nodeconfig['maxconnections']=="") { $nodeconfig['maxconnections'] = 0; }
			echo "<table class='table table-striped'>";
			echo "<tr><td class='col-md-2'><b>Max connections:</b></td><td class='col-md-2'>".$nodeconfig['maxconnections']."</td></tr>";
			echo "</table>";
    	?>
    </div>
  </div>
  <hr>
  
  <div class="panel panel-default">
    <div class="panel-heading">Node connections</div>
    <div class="panel-body" id="nodeconfig">
	    <table class="table table-striped" id="tablelocation">
	      <thead>
	        <tr>
	          <th class='col-md-2'>Location</th>
	          <th class='col-md-2'>Count</th>
	        </tr>
	      </thead>
	      <tbody>
	        <tr><td colspan="2"><img src="images/loading.gif" border="0" height="64" width="64"> Loading....</td></tr>
	      </tbody>
	    </table>
    </div>
  </div>
  <hr>
  
  <div class="panel panel-default">
    <div class="panel-heading">Client Gulden versions</div>
    <div class="panel-body" id="guldenversions">
	    <table class="table table-striped" id="tableversion">
	      <thead>
	        <tr>
	          <th class='col-md-2'>Version</th>
	          <th class='col-md-2'>Count</th>
	        </tr>
	      </thead>
	      <tbody>
	        <tr><td colspan="2"><img src="images/loading.gif" border="0" height="64" width="64"> Loading....</td></tr>
	      </tbody>
	    </table>
    </div>
  </div>
  </div><!--/row-->
</div>
