<script src="js/dash/guldend.js"></script>

<div class="row row-offcanvas row-offcanvas-left">

 <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">
   
    <ul class="nav nav-sidebar">
      <li><a href="?">Overview</a></li>
      <li class="active"><a href="?page=guldend">GuldenD</a></li>
      <li><a href="?page=node">Node</a></li>
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
    GuldenD
    <p class="lead">GuldenD info</p>
  </h1>
  
  <div id="errordiv"></div>
  
  <div class="panel panel-default">
    <div class="panel-heading">CPU/MEM usage</div>
    <div class="panel-body" id="meters" align="center">
    	<div id='CPU' class="gauge"></div>
	    <div id='MEM' class="gauge"></div>
	    <div id='TEMP' class="gauge"></div>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading">Server info</div>
    <div class="panel-body" id="serverinfopanel">
    	<img src="images/loading.gif" border="0" height="64" width="64">
    </div>
  </div>
  <hr>

  </div><!--/row-->
</div>
