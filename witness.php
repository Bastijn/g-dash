<script src="js/dash/witness.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<script src="js/jquery-ui/js/jquery-ui.min.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<link rel="stylesheet" href="js/jquery-ui/css/jquery-ui.min.css">
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
  </h1>
  
  <div id="errordiv"></div>
  
  
  <!-- Add account modal content-->
  	<div id="addwitnessaccount" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="addwitnessaccountmodal" id="addwitnessaccountmodal">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Create a new witness account</h4>
	      </div>
	      
	      <div class="modal-body">
	      	<div class="form-group">
		      <label for="newaccountname"><small>Account name (Normal letters only and no &#42; allowed)</small></label><br>
		      <input id="newaccountname" name="newaccountname" type="text" class="form-control">
		    </div>
		    <div class="form-group">
		      <label for="confirmwithdrawpass"><small>Pass Phrase</small></label><br>
			  <input type="password" id="newaccountpassword" name="newaccountpassword" autocomplete='off'>
		    </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-success" id="creataccountbutton" onclick="addAccount()">Create account</button>
	      </div>
	      <div id="creationmessage" name="creationmessage"></div>
	    </div>	
	  </div>
	</div>
	<!-- End add account modal content-->
	
	
	<!-- Fund witness account modal content-->
  	<div id="fundwitnessaccount" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="fundwitnessaccountmodal" id="fundwitnessaccountmodal">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Fund witness account</h4>
	      </div>
	      
	      <div class="modal-body">
	        <div class="form-group">
		      <label for="selectedwitnessaccount"><small>Selected witness account</small></label><br>
		      <input id="selectedwitnessaccount" name="selectedwitnessaccount" type="text" class="form-control" readonly>
		    </div>
		    <div class="form-group">
		      <label for="selectedguldenaccount"><small>From Gulden account</small></label><br>
		      <select name="selectedguldenaccount" id="selectedguldenaccount"></select>
		    </div>
		    <div class="form-group">
		      <label for="lockamount"><small>Amount of Gulden to lock</small></label><br>
		      <input id="lockamount" name="lockamount" type="text" class="form-control bfh-number" data-min="5000" data-max="999999999">
		    </div>
		    <div class="form-group">
		      <label for="locktime"><small>Time to lock Gulden (months)</small></label><br>
		      <div id="slider"><div id="locktime" name="locktime" class="ui-slider-handle"></div></div>
		    </div>
		    <div class="form-group">
		      <label for="lockweight"><small>Your weight (minimum 10000; max 1%)</small></label><br>
		      <div id="lockweight" name="lockweight">0 (0%)</div>
		    </div>
		    <div id="transactionmessage" name="transactionmessage"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-success" id="lockbutton" onclick="createWitnessDetails();">Lock Gulden</button>
	      </div>
	      
	    </div>	
	  </div>
	</div>
	<!-- End fund witness account modal content-->
	
	
	<!-- Fund witness account confirmation dialog-->
	<div id="fundingpopup" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="fundingpopupmodal" id="fundingpopupmodal">
	      <div class="modal-header">
	        <h4 class="modal-title">Lock funds confirmation</h4>
	      </div>
	      <div class="modal-body">
	      	<h3>Confirm witness funding</h3>
	        <p>This action cannot be undone.</p>
	        <p>
				<label for="confirmwitnessaccount"><small>Selected witness account</small></label><br>
				<div id="confirmwitnessaccount"></div>
			</p>
			<p>
				<label for="confirmguldenaccount"><small>From Gulden account</small></label><br>
				<div id="confirmguldenaccount"></div>
			</p>
			<p>
		      <label for="confirmamount"><small>Amount of Gulden to lock</small></label><br>
		      <div id="confirmamount"></div>
		    </p>
		    <p>
		      <label for="confirmtime"><small>Time to lock</small></label><br>
		      <div id="confirmtime"></div>
		    </p>
			<p>
				<label for="confirmtransactionpass"><small>Pass Phrase</small></label><br>
				<div><input type="password" id="confirmtransactionpass" name="confirmtransactionpass" autocomplete='off'></div>
			</p>
			<div id="conftransmes" name="conftransmes"></div>
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-danger" id="confirmedcancel" name="confirmedcancel" onclick="confirmWitnessDetails()">Cancel</button>
	        <button type="button" class="btn btn-success" id="confirmedsubmit" name="confirmedsubmit" onclick="confirmWitnessDetails('true')">Send</button>
	      </div>
	    </div>	
	  </div>
	</div>
	<!-- End fund witness account confirmation dialog-->
  
  
  	<!-- Import witness account modal content-->
  	<div id="importwitnessaccount" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="importwitnessaccountmodal" id="importwitnessaccountmodal">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Import witness account</h4>
	      </div>
	      
	      <div class="modal-body">
	        <div class="form-group">
		      <label for="importwaccountname"><small>Account name (Normal letters only and no &#42; allowed)</small></label><br>
		      <input id="importwaccountname" name="importwaccountname" type="text" class="form-control">
		    </div>
		    <div class="form-group">
		      <label for="importwaccountkey"><small>Account key</small></label><br>
		      <input id="importwaccountkey" name="importwaccountkey" type="text" class="form-control">
		    </div>
		    <div class="form-group">
		      <label for="importpass"><small>Pass Phrase</small></label><br>
			  <input type="text" id="importpass" name="importpass">
		    </div>
		    <div id="keyimportmessage" name="keyimportmessage"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-success" id="importbutton" onclick="importWitnessAccount();">Import witness account</button>
	      </div>
	      
	    </div>	
	  </div>
	</div>
	<!-- End import witness account modal content-->
	
	
	<!-- Withdraw witness earnings modal content-->
  	<div id="withdrawwitnessaccount" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="withdrawwitnessaccountmodal" id="withdrawwitnessaccountmodal">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Withdraw witness earnings</h4>
	      </div>
	      
	      <div class="modal-body">
	      	<div class="form-group">
		      <label for="selectedwitnessaccountwithdraw"><small>Selected witness account</small></label><br>
		      <input id="selectedwitnessaccountwithdraw" name="selectedwitnessaccountwithdraw" type="text" class="form-control" readonly>
		    </div>
		    <div class="form-group">
		      <label for="guldenaddresswithdraw"><small>Send to Gulden address</small></label><br>
		      <input id="guldenaddresswithdraw" name="guldenaddresswithdraw" type="text" class="form-control">
		    </div>
		    <div class="form-group">
		      <label for="confirmwithdrawpass"><small>Pass Phrase</small></label><br>
			  <input type="password" id="confirmwithdrawpass" name="confirmwithdrawpass" autocomplete='off'>
		    </div>
		    <div id="withdrawmessage" name="withdrawmessage"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-success" id="witnesswithdrawbutton" onclick="withdrawWitnessEarnings();">Withdraw witness earnings</button>
	      </div>
	      
	    </div>	
	  </div>
	</div>
	<!-- End withdraw witness earnings modal content-->
	
	<!-- Show export witness key modal content-->
  	<div id="exportwitnesskey" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="exportwitnesskeymodal" id="exportwitnesskeymodal">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Export the witness key for this account</h4>
	      </div>
	      
	      <div class="modal-body">
	      	<div class="form-group">
		      <label for="selectedwitnessaccountkey"><small>Selected witness account</small></label><br>
		      <input id="selectedwitnessaccountkey" name="selectedwitnessaccountkey" type="text" class="form-control" readonly>
		    </div>
	      	<div class="form-group">
		      <label for="rppass"><small>Unlock your wallet with your password</small></label><br>
		      <input id="rppass" name="rppass" type="password" class="form-control" autocomplete='off'>
		    </div>
		    <div id="showkey" name="showkey"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-success" onclick="showWitnessKey();">Show witness key</button>
	      </div>
	      
	    </div>	
	  </div>
	</div>
	<!-- End show export witness key modal content-->
	
	<!-- Rename account modal content-->
  	<div id="renameaccount" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="renameaccountmodal" id="renameaccountmodal">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <div id="renameaccounttitle"><h4 class="modal-title">Rename account</h4></div>
	      </div>
	      
	      <div class="modal-body">
	      	<div class="form-group">
		      <label for="renameaccountname"><small>Account name (Normal letters only and no &#42; allowed)</small></label><br>
		      <input id="renameaccountname" name="renameaccountname" type="text" class="form-control">
		    </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="renameWitnessAccount()">Rename account</button>
	      </div>
	      
	    </div>	
	  </div>
	</div>
	<!-- End rename account modal content-->
	
	<!-- Delete account modal content-->
  	<div id="deleteaccount" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="deleteaccount" id="deleteaccount">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <div id="deleteaccounttitle"><h4 class="modal-title">Delete account</h4></div>
	      </div>
	      
	      <div class="modal-body">
	      	<div class="form-group">
		      <label for="selectedwitnessaccountdelete"><small>Selected witness account</small></label><br>
		      <input id="selectedwitnessaccountdelete" name="selectedwitnessaccountdelete" type="text" class="form-control" readonly>
		    </div>
		    
	      	Are you sure you want to delete this account? By pressing "yes" this account will be removed from every overview.
	      	<br><br>
	      	<div class="form-group">
		      <label for="delpass"><small>Unlock your wallet with your password</small></label><br>
		      <input id="delpass" name="delpass" type="password" class="form-control" autocomplete='off'>
		    </div>
	      <div id="showdelresponse" name="showdelresponse"></div>
	    </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-danger" id="confirmdelcancel" name="confirmdelcancel" onclick="confirmDeleteAccount()">Cancel</button>
	        <button type="button" class="btn btn-danger" id="confirmdelsubmit" name="confirmdelsubmit" onclick="confirmDeleteAccount('true')">Yes</button>
	      </div>
	    </div>	
	  </div>
	</div>
	<!-- End delete account modal content-->
  
  <div class="row">
  	<div class="col-md-12">
  		<div class="panel panel-default">
  			<div class="panel-heading" id="witnessnetworkheader">Witness Network</div>
		    <div class="panel-body" id="witnessnetworkpanel">
		    	
		    	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="glyphicon glyphicon-tasks huge"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="pow2phase"></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                        	<div class="text-center">Current PoW<sup>2</sup> Phase</div>
                        </div>
                    </div>
                </div>
		    	
		    	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="glyphicon glyphicon-globe huge"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="totalwitnesses"></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                        	<div class="text-center">Total Gulden Witnesses</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="glyphicon glyphicon-scale huge"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="totalwitnessweight"></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                        	<div class="text-center">Total Witness Weight</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="glyphicon glyphicon-lock huge"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="totalwitnesslocked"></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                        	<div class="text-center">Total Gulden Locked</div>
                        </div>
                    </div>
                </div>
		    	
		    </div>
  		</div>
  	</div>
  </div>
  
  <hr>
  
  <div class="row">
  	<div class="col-md-3">
  		<div class="panel panel-default">
  			<div class="panel-heading" id="witnessstatusheader">Witness actions</div>
		    <div class="panel-body" id="witnessstatuspanel">
		    	<img src="images/loading.gif" border="0" height="64" width="64"> Loading....
		    </div>
  		</div>
  		
  		<div class="panel panel-default">
  			<div class="panel-heading" id="witnessaccountsheader">Witness accounts</div>
		    <div class="panel-body" id="witnessaccountspanel">
		    	<img src="images/loading.gif" border="0" height="64" width="64"> Loading....
		    </div>
  		</div>
  	</div>
  	<div class="col-md-9">
		<div id="witnesslistpanel">
	    	<img src="images/loading.gif" border="0" height="64" width="64"> Loading....
	    </div>
  	</div>
  </div>
  
  </div><!--/row-->
</div>
