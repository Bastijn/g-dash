<div class="row row-offcanvas row-offcanvas-left">

 <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">
   
    <ul class="nav nav-sidebar">
      <li><a href="?page=about">About</a></li>
      <li><a href="manual/G-DASH_manual.pdf" target="_blank">Manual</a></li>
      <li class="active"><a href="?page=faq">FAQ</a></li>
    </ul>
 </div><!--/span-->

 <div class="col-sm-9 col-md-10 main">
  
  <!--toggle sidebar button-->
  <p class="visible-xs">
    <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
  </p>
  
  <h1 class="page-header">
    FAQ
    <p class="lead">Frequently Asked Questions</p>
  </h1>
  
  <div id="errordiv"></div>
  
  <div id="content">
  	<ul>
	  	<li><a href="#faq1">I want to change my RPC password. How do I do this?</a></li>
	  	<li><a href="#faq2">I opened TCP port 9231 as requested, but I still get the message "No inbound connections. Did you open/forwarded port 9231?".</a></li>
	  	<li><a href="#faq3">I want to submit my node statistics, but I get the message "You have no incoming connections. You have to configure your node to enable this option".</a></li>
	  	<li><a href="#faq4">I want to change the maximum number of connections, how can I do this?</a></li>
	  	<li><a href="#faq5">Why can't I change the Gulden.conf file from the dashboard?</a></li>
	  	<li><a href="#faq6">Is there a way to import my Gulden Recovery Phrase?</a></li>
	  	<li><a href="#faq7">When I open my wallet in G-DASH for the first time, it is empty!</a></li>
	  	<li><a href="#faq8">I want to see my Recovery Phrase, but I get the error the password is wrong, but I did not set a password.</a></li>
	  	<li><a href="#faq9">I want to get a notification when my computer or internet connection is down, but how can I do that?</a></li>
	  	<li><a href="#faq10">I'm 100 percent sure I set up my full node correctly, but I still don't get any incoming connections. What's wrong?</a></li>
	  	<li><a href="#faq11">G-DASH can't connect to Gulden, but the config check says username and password match.</a></li>
	  	<li><a href="#faq12">The witness statistics of my imported account are different in G-DASH and on the desktop app.</a></li>
  	</ul>
  	
  	<hr>
  	<br><br>
  	
  	<div class="panel panel-default">
	    <div class="panel-heading" id="faq1" name="faq1"><b>I want to change my RPC password. How do I do this?</b></div>
	    <div class="panel-body">
			First, edit the "Gulden.conf" file in the datadir of your Gulden installation. Here you can change the password. Then go to G-DASH and change the 
			RPC password there as well (in the section "Gulden settings"). When the passwords on both places are changed, restart Gulden by either restarting your 
			Pi, or by running "autostart.sh".
	    </div>
	</div>
  
    <div class="panel panel-default">
	    <div class="panel-heading" id="faq2" name="faq2"><b>I opened TCP port 9231 as requested, but I still get the message "No inbound connections. Did you open/forwarded port 9231?".</b></div>
	    <div class="panel-body">
			It can take a while before the first clients connect to your node. Please check again in 30 minutes / 1 hour. If there are still no inbound connections,
			check if you forwarded the port correctly. If you go to the "Config check" page in the Settings menu, you can check if your port is forwarded correctly.
			If you don't know how to forward the port on your router, check if your router is listed on this website:
			<a href="https://portforward.com/" target="_blank">https://portforward.com/</a>
	    </div>
	</div>
  	
  	<div class="panel panel-default">
	    <div class="panel-heading" id="faq3" name="faq3"><b>I want to submit my node statistics, but I get the message "You have no incoming connections. You have to configure your node to enable this option".</b></div>
	    <div class="panel-body">
			If you have opened your port on your router, please wait for about 30 minutes / 1 hour before the first connections will be made to your node. 
			If there are still no inbound connections, check if you forwarded the port correctly. If you go to the "Config check" page in the Settings menu, 
			you can check if your port is forwarded correctly. If you don't know how to forward the port on your router, check if your router is listed on 
			this website: <a href="https://portforward.com/" target="_blank">https://portforward.com/</a>
	    </div>
	</div>
	
	<div class="panel panel-default">
	    <div class="panel-heading" id="faq4" name="faq4"><b>I want to change the maximum number of connections, how can I do this?</b></div>
	    <div class="panel-body">
			You can change this number in the "Gulden.conf" file in the datadir of you Gulden installation.
	    </div>
	</div>
    
    <div class="panel panel-default">
	    <div class="panel-heading" id="faq5" name="faq5"><b>Why can't I change the Gulden.conf file from the dashboard?</b></div>
	    <div class="panel-body">
			For security reasons, G-DASH will only read from the Gulden.conf file. If your credentials would be compromised and others have access to your
			G-DASH, they will be able to change your Gulden.conf file which is a risk for yourself.
	    </div>
	</div>
	
	<div class="panel panel-default">
	    <div class="panel-heading" id="faq6" name="faq6"><b>Is there a way to import my Gulden Recovery Phrase?</b></div>
	    <div class="panel-body">
			At this moment it is not possible to import the Recovery Phrase from G-DASH as a rescan is needed after importing the key
			and this is currently not supported by Gulden without starting the rescan manually on startup.<br>
			For now, if you want to import your Recovery Phrase, you can do this in the command line and start GuldenD with the -rescan flag like this:<br>
			<code>
				/opt/gulden/gulden/Gulden-cli -datadir=/opt/gulden/datadir importseed "Your Recovery Phrase"<br>
				/opt/gulden/gulden/Gulden-cli -datadir=/opt/gulden/datadir stop<br>
				/opt/gulden/gulden/GuldenD -datadir=/opt/gulden/datadir -rescan &amp;<br>
			</code>
			Don't forget to change the directories if these are different in your installation!
	    </div>
	</div>
	
	<div class="panel panel-default">
	    <div class="panel-heading" id="faq7" name="faq7"><b>When I open my wallet in G-DASH for the first time, it is empty!</b></div>
	    <div class="panel-body">
			The wallet in G-DASH is a new wallet. There are no funds there yet. You can transfer some Gulden to your wallet in G-DASH
			using the address shown or by scanning the QR code.
	    </div>
	</div>
	
	<div class="panel panel-default">
	    <div class="panel-heading" id="faq8" name="faq8"><b>I want to see my Recovery Phrase, but I get the error the password is wrong, but I did not set a password.</b></div>
	    <div class="panel-body">
			If you used the auto installer, the default password for the wallet is "changeme" (without the quotes).
	    </div>
	</div>
	
	<div class="panel panel-default">
	    <div class="panel-heading" id="faq9" name="faq9"><b>I want to get a notification when my computer or internet connection is down, but how can I do that?</b></div>
	    <div class="panel-body">
			If you want to be notified when your computer running Gulden is offline, you can use the monitoring service from
			<a href="https://uptimerobot.com/" target="_blank">uptimerobot.com</a>. This service checks your connection
			every 5 minutes. Just create an account, add a new monitor (type 'Port'), enter your IP address and port 
			(custom port) '9231'. They also have a mobile app so you can receive push messages.
	    </div>
	</div>
	
	<div class="panel panel-default">
	    <div class="panel-heading" id="faq10" name="faq10"><b>I'm 100 percent sure I set up my full node correctly, but I still don't get any incoming connections. 
	    													  What's wrong?</b></div>
	    <div class="panel-body">
	    	First of all. Your system is not broken or anything if you have no incoming connections. But to make you a bit more discoverable, you can now use the
	    	<code>noderequest</code> function in the debug console (within G-DASH --> settings) that adds your node to a database. This database is then checked
	    	automatically by other G-DASH users and these instances (max 10) then make a connection with you for 24 hours, which makes you more discoverable to other
	    	nodes and seeds. When 10 nodes have connected to your node, you are removed from the database.
	    </div>
	</div>
	
	<div class="panel panel-default">
	    <div class="panel-heading" id="faq11" name="faq11"><b>G-DASH can't connect to Gulden, but the config check says username and password match.</b></div>
	    <div class="panel-body">
	    	The RPC server can't handle passwords that contain some special characters, such as "#" and "$". If you have entered a password in Gulden.conf and 
	    	the "Gulden" settings tab in G-DASH, create a new password without these symbols.
	    </div>
	</div>
	
	<div class="panel panel-default">
	    <div class="panel-heading" id="faq12" name="faq12"><b>The witness statistics of my imported account are different in G-DASH and on the desktop app.</b></div>
	    <div class="panel-body">
	    	Statistics can be different in G-DASH and on the desktop. The key imported in G-DASH is a read-only key, so if a witness action is performed on the 
	    	desktop this is not visible on G-DASH and there might be a difference in the number of earnings and the total amount of Gulden available compared to 
	    	the statistics on the desktop app. The desktop app can read the information from both instances (local and witness key).
	    </div>
	</div>
    	
  </div>

  </div><!--/row-->
</div>
