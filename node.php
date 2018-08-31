<script src="js/dash/node.js?<?php echo KeyGet($CONFIG, '0.0', 'dashversion'); ?>"></script>

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
    		$nodeconfig = readGuldenConf(KeyGet($CONFIG, '', 'datadir')."Gulden.conf");
			if($nodeconfig['maxconnections']=="") { $nodeconfig['maxconnections'] = 0; }
			echo "<div class='table-responsive'><table class='table table-striped'>";
			echo "<tr><td><b>Max connections:</b></td><td>".$nodeconfig['maxconnections']."</td></tr>";
			echo "</table></div>";
    	?>
    </div>
  </div>
  <hr>
  <div class="panel panel-default">
    <div class="panel-heading">Node connections</div>
    <div class="panel-body" id="nodeconfig">
    <div class="table-responsive">
	    <table class="table table-striped" id="tablelocation">
	      <thead>
	        <tr>
	          <th>Location</th>
	          <th>Count</th>
	        </tr>
	      </thead>
	      <tbody>
	        <tr><td colspan="2"><img src="images/loading.gif" border="0" height="64" width="64"> Loading....</td></tr>
	      </tbody>
	    </table>
	</div>
    </div>
  </div>
  <hr>
  <div class="panel panel-default">
    <div class="panel-heading">Client Gulden versions</div>
    <div class="panel-body" id="guldenversions">
    <div class="table-responsive">
	    <table class="table table-striped" id="tableversion">
	      <thead>
	        <tr>
	          <th>Version</th>
	          <th>Count</th>
	        </tr>
	      </thead>
	      <tbody>
	        <tr><td colspan="2"><img src="images/loading.gif" border="0" height="64" width="64"> Loading....</td></tr>
	      </tbody>
	    </table>
	</div>
    </div>
  </div>

  </div><!--/row-->
</div>
