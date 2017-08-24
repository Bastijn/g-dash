<div class="row row-offcanvas row-offcanvas-left">

 <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">
   
    <ul class="nav nav-sidebar">
      <li><a href="?">Overview</a></li>
      <li><a href="?page=guldend">GuldenD</a></li>
      <li><a href="?page=node">Node</a></li>
      <li><a href="?page=wallet">Wallet</a></li>
      <li class="active"><a href="?page=witness">Witness</a></li>
    </ul>
 </div><!--/span-->

 <div class="col-sm-9 col-md-10 main">
  
  <!--toggle sidebar button-->
  <p class="visible-xs">
    <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
  </p>
  
  <h1 class="page-header">
    Witness
    <p class="lead">Witness stats</p>
  </h1>
  
  <div id="errordiv"></div>

  Not yet available!
  
  <?php
  /*
   * Weight = (Quantity × (1 + Time/(576×365)) × 2) − 10000
   * Time = blocks (1 day = 576 blocks)
   * MinimumWeight of a witness account = 10000
   * Minimum lock time = 17280 blocks (~ 1 month)
   * Maximum lock time = 630720 blocks (~ 3 years)
   * Minimum coins = 5000 NLG
   * 
   * 
   * TotalNetworkWeight (can be retreived using RPC command)
   * Maximum weight per account = 2% of TotalNetworkWeight, otherwise it will be reduced to 2%
   * WitnessExpiryTime = max((Weight/TotalNetworkWeight) * 2, 200)
   * Cool-off period = 100 blocks
   * 
   * Multiple witness accounts possible
   * 
   * 
   * 
  */
  ?>
  
  <hr>

  </div><!--/row-->
</div>
