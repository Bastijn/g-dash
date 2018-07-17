<script src="js/dash/wallet.js?<?php echo $CONFIG['dashversion']; ?>"></script>
<script src="js/dash/wallettx.js?<?php echo $CONFIG['dashversion']; ?>"></script>

<div class="row row-offcanvas row-offcanvas-left">

 <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">
   
    <ul class="nav nav-sidebar">
      <li><a href="?">Overview</a></li>
      <li><a href="?page=guldend">GuldenD</a></li>
      <li><a href="?page=node">Node</a></li>
      <li class="active"><a href="?page=wallet">Wallet</a></li>
      <li><a href="?page=witness">Witness</a></li>
    </ul>
 </div><!--/span-->

 <div class="col-sm-9 col-md-10 main">
  
  <!--toggle sidebar button-->
  <p class="visible-xs">
    <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
  </p>
  
  <h1 class="page-header">
    Wallet
    <p class="lead">Wallet information</p>
  </h1>
  
  <div id="errordiv"></div>
  
  
  	<!-- Add account modal content-->
  	<div id="newaccount" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="newaccountmodal" id="newaccountmodal">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Create a new account</h4>
	      </div>
	      
	      <div class="modal-body">
	      	<p><b>Note: This will overwrite your current wallet.</b></p>
	      	<div class="form-group">
		      <label for="newaccountname"><small>Account name (Normal letters only and no &#42; allowed)</small></label><br>
		      <input id="newaccountname" name="newaccountname" type="text" class="form-control">
		    </div>
		    
		    <div class="form-group">
		      <label for="createaccpass"><small>Unlock your wallet with your password</small></label><br>
		      <input id="createaccpass" name="createaccpass" type="password" class="form-control" autocomplete='off'>
		    </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="addAccount()">Create account</button>
	      </div>
	      
	    </div>	
	  </div>
	</div>
	<!-- End add account modal content-->
	
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
	        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="renameAccount()">Rename account</button>
	      </div>
	      
	    </div>	
	  </div>
	</div>
	<!-- End rename account modal content-->
	
	
	<!-- Import Recovery Phrase modal content-->
  	<div id="importrecoveryphrase" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="importrecoveryphrasemodal" id="importrecoveryphrasemodal">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Import wallet using Recovery Phrase</h4>
	      </div>
	      
	      <div class="modal-body">
	      	<div class="form-group">
		      <label for="therecphrase"><small>Recovery Phrase</small></label><br>
		      <input id="therecphrase" name="therecphrase" type="password" class="form-control" autocomplete='off'>
		    </div>
		    <div id="importrecstatus" name="importrecstatus"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" onclick="impRecovery();">Import Recovery Phrase</button>
	      </div>
	      
	    </div>	
	  </div>
	</div>
	<!-- End import recovery phrase modal content-->
	
	
	<!-- Show Recovery Phrase modal content-->
  	<div id="showrecoveryphrase" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="recoveryphrasemodal" id="recoveryphrasemodal">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Show the Recovery Phrase for this wallet</h4>
	      </div>
	      
	      <div class="modal-body">
	      	<div class="form-group">
		      <label for="rppass"><small>Unlock your wallet with your password</small></label><br>
		      <input id="rppass" name="rppass" type="password" class="form-control" autocomplete='off'>
		    </div>
		    <div id="showrp" name="showrp"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" onclick="showRecoveryPhrase();">Show Recovery Phrase</button>
	      </div>
	      
	    </div>	
	  </div>
	</div>
	<!-- End show recovery phrase modal content-->
	
	
	<!-- Set up passphrase modal content-->
  	<div id="setuppassphrase" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="setuppassphrasemodal" id="setuppassphrasemodal">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Create a password for this wallet</h4>
	      </div>
	      
	      <div class="modal-body">
	        <div class="form-group">
		      <label for="rpnewpass"><small>Normal letters only and no &#42; allowed</small></label><br>
		      <input id="rpnewpass" name="rpnewpass" type="password" class="form-control" autocomplete='off'>
		    </div>
		    <div class="form-group">
		      <label for="rpnewpasstwo"><small>Repeat password</small></label><br>
		      <input id="rpnewpasstwo" name="rpnewpasstwo" type="password" class="form-control" autocomplete='off'>
		    </div>
		    <div id="passerror" name="passerror"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" onclick="setPassPhrase();">Set password</button>
	      </div>
	      
	    </div>	
	  </div>
	</div>
	<!-- End set up passphrase modal content-->
	
	
	<!-- Change passphrase modal content-->
  	<div id="changepassphrase" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="changepassphrasemodal" id="changepassphrasemodal">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Change password for this wallet</h4>
	      </div>
	      
	      <div class="modal-body">
	        <div class="form-group">
		      <label for="oldrpnewpass"><small>Current password</small></label><br>
		      <input id="oldrpnewpass" name="oldrpnewpass" type="password" class="form-control" autocomplete='off'>
		    </div>
	        <div class="form-group">
		      <label for="changedrpnewpass"><small>New password (normal letters only and no &#42; allowed)</small></label><br>
		      <input id="changedrpnewpass" name="changedrpnewpass" type="password" class="form-control" autocomplete='off'>
		    </div>
		    <div class="form-group">
		      <label for="changedrpnewpasstwo"><small>Repeat password</small></label><br>
		      <input id="changedrpnewpasstwo" name="changedrpnewpasstwo" type="password" class="form-control" autocomplete='off'>
		    </div>
		    <div id="changepasserror" name="changepasserror"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" onclick="changePassPhrase();">Change password</button>
	      </div>
	      
	    </div>	
	  </div>
	</div>
	<!-- End change passphrase modal content-->
	
	
	<!-- Create a transaction modal content-->
  	<div id="createtransaction" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="createtransactionmodal" id="createtransactionmodal">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Create a transaction</h4>
	      </div>
	      
	      <div class="modal-body">
	        <div class="form-group">
		      <label for="transactionaddress"><small>Gulden receiving address</small></label><br>
		      <input id="transactionaddress" name="transactionaddress" type="text" class="form-control">
		    </div>
		    <div class="form-group">
		      <label for="transactionamount"><small>Amount of Gulden to send</small></label><br>
		      <input id="transactionamount" name="transactionamount" type="text" class="form-control bfh-number" data-min="1" data-max="999999">
		    </div>
		    <div id="transactionmessage" name="transactionmessage"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" onclick="createTransactionDetails();">Send</button>
	      </div>
	      
	    </div>	
	  </div>
	</div>
	<!-- End create a transaction modal content-->
	
	<!-- Create a transaction confirmation dialog-->
	<div id="transactionpopup" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content" name="transactionpopupmodal" id="transactionpopupmodal">
	      <div class="modal-header">
	        <h4 class="modal-title">Transaction confirmation</h4>
	      </div>
	      <div class="modal-body">
	      	<h3>Are you sure you want to send Gulden?</h3>
	        <p>This action cannot be undone.</p>
	        <p>
				<label for="confirmaddress"><small>Gulden receiving address</small></label><br>
				<div id="confirmaddress"></div>
			</p>
			<p>
		      <label for="confirmamount"><small>Amount of Gulden to send</small></label><br>
		      <div id="confirmamount"></div>
		    </p>
			<p>
				<label for="confirmtransactionpass"><small>Pass Phrase</small></label><br>
				<div><input type="password" id="confirmtransactionpass" name="confirmtransactionpass" autocomplete='off'></div>
			</p>
			<div id="conftransmes" name="conftransmes"></div>
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-default" onclick="confirmTransactionDetails()">Cancel</button>
	        <button type="button" class="btn btn-default" id="confirmedsubmit" name="confirmedsubmit" onclick="confirmTransactionDetails('true')">Send</button>
	      </div>
	    </div>	
	  </div>
	</div>
	<!-- End create a transaction confirmation dialog-->
	
	
  
  <div class="row">
  	<div class="col-md-3">
  		<div class="panel panel-default">
  			<div class="panel-heading" id="accountlistheader">Account list</div>
		    <div class="panel-body" id="accountlistpanel">
		    	<img src="images/loading.gif" border="0" height="64" width="64"> Loading....
		    </div>
  		</div>
  	</div>
  	<div class="col-md-9">
  		<div class="panel panel-default">
		    <div class="panel-heading" id="currentaccountname">Current account</div>
		    <div class="panel-body" id="walletinfopanel">
		    	<img src="images/loading.gif" border="0" height="64" width="64"> Loading....
		    </div>
		</div>
  	</div>
  </div>
  
  <div class="row">
  	<div class="col-md-3"></div>
  	<div class="col-md-9">
  		<div class="panel panel-default">
		    <div class="panel-heading">Account actions</div>
		    <div class="panel-body" id="accountactions">
		    	<div id="unencryptedwallet" name="unencryptedwallet" style="display: none;">
		    		<b>You have not configured your wallet yet. Choose one of the options below.</b><br><br>
		    		
		    		This is a new wallet. To recover another wallet, import your wallet using the Recovery Phrase.<br>
		    		<ul>
		    			<li><a data-toggle="modal" href="#importrecoveryphrase">Import existing Recovery Phrase</a></li>
		    		</ul>
		    		Your wallet is not encrypted. No transactions can be made until you encrypt your wallet.
		    		<ul>
		    			<li><a data-toggle="modal" href="#setuppassphrase">Set a password to encrypt this wallet</a></li>
		    		</ul>
		    	</div>
		    	<div id="encryptedwallet" name="encryptedwallet" style="display: none;">
		    		<ul>
			    		<li><a data-toggle="modal" href="#changepassphrase">Change wallet password</a></li>
			    		<li><a data-toggle="modal" href="#showrecoveryphrase">Show the Recovery Phrase</a></li>
			    		<li><a data-toggle="modal" href="#importrecoveryphrase">Import existing Recovery Phrase</a></li>
			    		<!--<li>Backup wallet</li>-->
			    		<li><a data-toggle="modal" href="#createtransaction">Create a transaction</a></li>
			    	</ul>
		    	</div>
		    </div>
		</div>
  	</div>
  </div>
  
  <div class="row">
  	<div class="col-md-3"></div>
  	<div class="col-md-9">
  		<div class="panel panel-default">
		    <div class="panel-heading">Last 30 transactions</div>
		      <div class="panel-body" id="accounttransactions">
		    	<div class="table-responsive">
			    <table class="table table-striped" id="tabletransactions">
			      <thead>
			        <tr>
			          <th>Date</th>
			          <th>Amount</th>
			          <th>TransactionID</th>
			        </tr>
			      </thead>
			      <tbody>
			        <tr><td colspan="3"><img src='images/loading.gif' border='0' height='64' width='64'> Loading....</td></tr>
			      </tbody>
			    </table>
			  </div>
		    </div>
		</div>
  	</div>
  </div>
  <hr>

  </div><!--/row-->
</div>
