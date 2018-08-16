<div class="row row-offcanvas row-offcanvas-left">

 <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">
   
    <ul class="nav nav-sidebar">
      <li><a href="?page=settings">Settings</a></li>
      <li><a href="?page=upgrade">Upgrade</a></li>
      <li><a href="?page=configcheck">Config Check</a></li>
      <li><a href="?page=debug">Debug Console</a></li>
      <li class="active"><a href="?page=changelog">Changelog</a></li>
    </ul>
 </div><!--/span-->

 <div class="col-sm-9 col-md-10 main">
  
  <!--toggle sidebar button-->
  <p class="visible-xs">
    <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
  </p>
  
  <h1 class="page-header">
    Changelog
    <p class="lead">G-DASH changelog</p>
  </h1>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>1.03 and 1.04</b></div>
    <div class="panel-body">
		<ul>
			<li>ENHANCEMENT: GitHub issue #6: Config check page shows info for IPv4 and IPv6.</li>
			<li>BUG: Witness projection chart too large for mobile view.</li>
			<li>ENHANCEMENT: Using block info for exact block times in witness statistics.</li>
			<li>ENHANCEMENT: Updated formula to calculate projected witness earnings.</li>
			<li>ENHANCEMENT: Not funded accounts can be deleted.</li>
			<li>FEATURE: New 3D graph in witness network showing time, weight and amount locked for all witness accounts.</li>
			<li>FEATURE: GitHub issue #11: Witness accounts transactions can be found underneath the earnings graph.</li>
			<li>BUG: Exclude witness rewards from pushbullet transaction push message.</li>
			<li>ENHANCEMENT: Updated the FAQ.</li>
			<li>BUG: Fixed the immature balance.</li>
			<li>ENHANCEMENT: Added estimated witness period to witness account details.</li>
			<li>BUG: Wallet transaction list did not filter for witness accounts.</li>
			<li>BUG: Witness status (cooldown/initial funding) was incorrectly displayed when public key is not used yet.</li>
			<li>ENHANCEMENT: Change OTP key to 16 chars as an iPhone is not always able to scan a 32 or 64 char barcode.</li>
			<li>FEATURE: Show witness address and QR for the witness address.</li>
			<li>ENHANCEMENT: Updated the manual and added a link to G-DASH in the about section.</li>
			<li>BUG: Fixed incorrect witness earnings projection.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>1.02</b></div>
    <div class="panel-body">
		<ul>
			<li>IMPROVEMENT: Always show import recovery phrase option.</li>
			<li>BUG: Password field for witness key import was marked as normal text field.</li>
			<li>BUG: Orphaned blocks were counted as normal blocks in witness statistics.</li>
			<li>FEATURE: Show expected earnings percentage in witness screen.</li>
			<li>BUG: Fixed empty witness push messages when GuldenD is not running.</li>
			<li>BUG: Temporary disable the immature balance, currently work in progress.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>1.01</b></div>
    <div class="panel-body">
		<ul>
			<li>IMPROVEMENT: Add G-DASH version on calls to remaining JS files to prevent browser caching on new releases.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>1.0</b></div>
    <div class="panel-body">
		<ul>
			<li>IMPROVEMENT: Enabled funding of witness accounts and importing witness keys in Phase 2.</li>
			<li>IMPROVEMENT: Disabled auto-complete for passwords in the wallet and witness pages.</li>
			<li>BUG: When no peers are connected yet, total blocks and sync percentage were off.</li>
			<li>ENHANCEMENT: GitHub issue #9: Show command in Config Check page for making debug.log file readable.</li>
			<li>IMPROVEMENT: Current Phase shown on the dashboard screen.</li>
			<li>FEATURE: Added a graph in the witness screen showing the distribution of locked Guldens.</li>
			<li>FEATURE: Added a graph that plots the expected earnings and current earnings.</li>
			<li>BUG: Fixed withdraw balance from witness account.</li>
			<li>IMPROVEMENT: In the wallet TX list, when the blockchain API is down, stop trying to connect.</li>
			<li>IMPROVEMENT: Close the session before performing large requests in AJAX scripts, allowing multiple pages to be opened.</li>
			<li>FEATURE: Added push message feature for witness activity.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.995</b></div>
    <div class="panel-body">
		<ul>
			<li>NOTE: This is the last beta release before version 1. After this update the stable and beta channel will be used as intended.</li>
			<li>NOTE: This is a small in between release to make life easier for users to upgrade to Gulden 2.0.</li>
			<li>BUG: Header sync progress was not shown correctly sometimes.</li>
			<li>FEATURE: Added 'guldenstop' command to debug console.</li>
			<li>FEATURE: Added rescan notification to dashboard.</li>
			<li>FEATURE: In the config check page the details of the GuldenD debug.log file are shown.</li>
			<li>IMPROVEMENT: Show the Gulden message when upgrading database / blocks instead of custom message.</li>
			<li>IMPROVEMENT: Don't show an error when GuldenD is upgrading.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.99</b></div>
    <div class="panel-body">
		<ul>
			<li>NOTE: This is the last beta release before version 1. After this update the stable and beta channel will be used as intended.</li>
			<li>FEATURE: Witness functionality. Not described in detail here. See GitHub.</li>
			<li>FEATURE: Import recovery phrase and rescan for transactions.</li>
			<li>FEATURE: Set time limits on XHR calls.</li>
			<li>FEATURE: Catch the 'Upgrading block index' note when updating to a new Gulden version.</li>
			<li>FEATURE: Fetch headers from peers instead of the blockchain API.</li>
			<li>FEATURE: Changed the account list fetch to the new standard.</li>
			<li>FEATURE: Temp additions and warnings for the transition period to Gulden 2.0.</li>
			<li>FEATURE: Temp additions and warnings for the different phases for witnessning.</li>
			<li>FEATURE: Buttons are now colourful.</li>
			<li>FEATURE: Changed update servers to HTTPS.</li>
			<li>FEATURE: Added version numbers to JS scripts to prevent caching issues.</li>
			<li>FEATURE: Added terminal commands 'walletunlock', 'rescan', and 'getrescanprogress'. Use 'help' for details.</li>
			<li>FEATURE: Many more! Check <a href='https://github.com/Bastijn/g-dash/commit/fb4d1f8a855179744d79fb91a6d6123f247006ea' target='_blank'>GitHub</a></li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.29</b></div>
    <div class="panel-body">
		<ul>
			<li>BUG: Transaction list in wallet could not always use the raw data without TX indexing, created failsafe to Insight API.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.28</b></div>
    <div class="panel-body">
		<ul>
			<li>ENHANCEMENT: Changed the layout of the settings page for easier access to specific settings.</li>
			<li>FEATURE: New option in settings to set a currency exchange to your liking used in the wallet to calculate the current 
				value of NLG to other currencies (i.e. Nocks and GuldenTrader for Euro, CryptoCompare for USD, BitTrex for BTC).</li>
			<li>ENHANCEMENT: Rewrote the function for the wallet transaction list.</li>
			<li>ENHANCEMENT: Show loading image when clicking on an account in the wallet.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.27</b></div>
    <div class="panel-body">
		<ul>
			<li>BUG: OTP check is now only performed when user logs in.</li>
			<li>TEXTUAL: Settings and config check page.</li>
			<li>TEXTUAL: Define 'full node' and 'node' as 2 different items.</li>
			<li>FEATURE: Added a G-DASH CLI for password resets (inspired by ownclouds' occ). Usage: <code>php /var/www/html/gdcli</code></li>
			<li>ENHANCEMENT: GitHub issue #7: Turned of auto-complete for usernames and passwords in the settings.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.26</b></div>
    <div class="panel-body">
		<ul>
			<li>BUG: Changes for node statistics where not applied in previous update.</li>
			<li>ENHANCEMENT: Added a random delay to the cronjob for checking noderequests.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.25</b></div>
    <div class="panel-body">
		<ul>
			<li>BUG: Removed temp fix for 2FA code from version 0.21.</li>
			<li>FEATURE: Removed restrictions for upload node statistics (now available even if there are no inbound connections).</li>
			<li>FEATURE: Added a notification to the terminal if the debug.log file is not readable by the webserver.</li>
			<li>FEATURE: Added a new command 'noderequest' to the terminal to request being added by other nodes automatically (i.e. for zero inbound connections).</li>
			<li>FEATURE: G-DASH automatically adds requested nodes for 24 hours if requests are allowed.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.24</b></div>
    <div class="panel-body">
		<ul>
			<li>Quick bug fix release for the terminal (UTF8 encoding).</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.23</b></div>
    <div class="panel-body">
		<ul>
			<li>GitHub issue #5: A more secure hashing is used for passwords. Passwords are automatically updated
				after logging in.</li>
			<li>Removed deprecated RPC login function.</li>
			<li>GitHub issue #3: Changed the link at the settings page to the new developer page at Gulden.com.</li>
			<li>GitHub issue #2: Password strength check for password length fixed.</li>
			<li>Updated jQuery to 1.7.1.</li>
			<li>Added jQuery Terminal Emulator (version 1.11.4).</li>
			<li>With terminal basic Gulden commands can be passed via RPC (Settings -> Debug Console).</li>
			<li>Terminal is work in progress. More commands will be added.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.22</b></div>
    <div class="panel-body">
		<ul>
			<li>Solved a bug where the "Not encrypted wallet" message kept appending to the error message.</li>
			<li>Added witness page (still empty for now).</li>
			<li>A 'find my Pi' function was added for users who ordered a pre-installed Pi.</li>
			<li>Fixed 2FA QR-code: was not working in Google Authenticator, but did work in Authy.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.21</b></div>
    <div class="panel-body">
		<ul>
			<li>Added the raspbian repository to the auto-installer and installation guide.</li>
			<li>The wallet function returned the wrong error code for "Insufficient funds".</li>
			<li>Listtransactions in the push notifications returned only data from the default account.</li>
			<li>A push notification can be send when a new version of Gulden is available in the Raspbian repository.</li>
			<li>The big red error notification will automatically go away when Gulden is up and running.</li>
			<li>Fixed a bug in the auto-install script. RPC passwords can't contain a "#" sign.</li>
			<li>Added tooltips to the dashboard main screen.</li>
			<li>Multiple colors for the node section on the dashboard main screen (green, orange, red).</li>
			<li>A notification is shown when there is an update of Gulden available in the Raspbian repository.</li>
			<li>The check for required packages differentiates between PHP5 and PHP7.</li>
			<li>Showing the temperature of the Linux system next to the CPU and MEM percentages.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.20</b></div>
    <div class="panel-body">
		<ul>
			<li>Disabled AJAX caching.</li>
			<li>Added a pushbullet notification: notify on new G-DASH update.</li>
			<li>Show the 2FA QR code even if OTP is not activated.</li>
			<li>Added information in the FAQ on how to import a recovery phrase manually (#8).</li>
			<li>Added information in the FAQ on how to monitor your Gulden installation (#9).</li>
			<li>Added a pushbullet notification: notify when receiving Gulden.</li>
			<li>Change the notification cronjob check time to every 2 minutes instead of 5.</li>
			<li>Corrected the password check in the configuration check page.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.19</b></div>
    <div class="panel-body">
		<ul>
			<li>Oops... Forgot a bracket causing the Wallet page not loading.</li>
			<li>Added a loading message in the wallet transaction list.</li>
			<li>Disable 2FA when the user changed the password.</li>
			<li>Disable 2FA when login screen is disabled.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.18</b></div>
    <div class="panel-body">
		<ul>
			<li>Even more secure login: You can now choose a seperate username and password just for G-DASH. No
				need to use the same username and password as for GuldenD (Gulden.conf) anymore.</li>
			<li>Squashed a bug in the transaction list.</li>
			<li>Typo in the updater.</li>
			<li>Added the protocol to the node settings (TCP).</li>
			<li>Added and edited FAQ items.</li>
			<li>Added port checker for node and witness to the Config Check page (hosted by guldennodes.com).</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.17</b></div>
    <div class="panel-body">
		<ul>
			<li>More secure login option: 2-Factor Authentication can be enabled from the settings page.</li>
			<li>Added FAQ to G-DASH.nl.</li>
			<li>Release notes are shown on the update page when an update is available.</li>
			<li>Send push messages to your phone, tablet, smartwatch and PC about the status of your G-DASH and Gulden 
				server using PushBullet. You will have to create an access token yourself which you can 
				get at <a href="https://www.pushbullet.com/#settings/account" target="_blank">your account page</a>.</li>
			<li>At this point the only push message is a check if the Gulden server is running. In the future 
				more options will be added like a push message for transactions or witness actions.</li>
			<li>A cron job will check for pushable updates every 5 minutes. Of course this won't work if your machine 
				is powered off or offline.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.16</b></div>
    <div class="panel-body">
		<ul>
			<li>The transaction list looks nicer.</li>
			<li>Shortened the transaction ID.</li>
			<li>Changed the hover color when hovering over an active menu item.</li>
			<li>Changed the term "passphrase" to "password".</li>
			<li>Option to rename accounts.</li>
			<li>Added a FAQ to the "About" menu item.</li>
			<li>The auto install encrypts the wallet when starting for the first time.</li>
			<li>Euro price in wallet (using the guldentrader.com API).</li>
			<li>Separated JS and PHP scripts.</li>
			<li>Check if transaction input is a number (decimals as dots, not comma's).</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.15</b></div>
    <div class="panel-body">
		<ul>
			<li>Changed node statistics cron job from every 30 minutes to every 10 minutes.</li>
			<li>Generate QR code for the current address.</li>
			<li>Set wallet passphrase.</li>
			<li>See wallet "Recovery Phrase".</li>
			<li>If a wallet is not encrypted, it has to be encrypted before users can engage in any transactions or see the "Recovery Phrase".</li>
			<li>A lock indicates if a wallet is locked (green, closed) or unlocked (red, open).</li>
			<li>Show a new address after each transaction to the latest address (so address will not change if the address is not used yet).</li>
			<li>Create a new account in the wallet.</li>
			<li>Send Guldens to any Gulden address.</li>
			<li>Show last 50 transactions (will make it look nicer in the next version).</li>
			<li>Change the passphrase of the wallet.</li>
			<li>Import "Recovery Phrase" al set, but not activated at the moment as there is no easy way to run the rescan flag.</li>
		</ul>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading"><b>0.14</b></div>
    <div class="panel-body">
		<ul>
			<li>Checkbox to send node stats to guldennodes.com.</li>
			<li>Enabling this checkbox creates a cronjob for the www user to run twice per hour.</li>
			<li>Added a function to check which services are listening (makes it easier to troubleshoot).</li>
			<li>Added the changelog to the settings menu.</li>
		</ul>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading"><b>0.13</b></div>
    <div class="panel-body">
		<ul>
			<li>Created an empy "Witness" page.</li>
			<li>Added a function to get file permissions.</li>
			<li>Users have the option to update from beta/stable channel.</li>
			<li>Removed "Prerequisites check" from the settings page.</li>
			<li>Created a new page called "Config check".</li>
			<li>In the config check page users can troubleshoot their connection with G-DASH <-> Gulden.</li>
		</ul>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading"><b>0.12</b></div>
    <div class="panel-body">
		<ul>
			<li>Give the RCP address and port default values on install (127.0.0.1/9232).</li>
			<li>When the user has both IPv4 and IPv6 addresses, only show the IPv4 address.</li>
			<li>Added a repeated password field and a validator.</li>
			<li>Automatically log out the user when changing the password.</li>
			<li>Added info to the repeat password field.</li>
			<li>Changelog started</li>
		</ul>
    </div>
  </div>
  

  </div><!--/row-->
</div>
