$(document).ready(function() {
  	//Load json data for the dashboard
	loadtxdata = function() {
	  $.getJSON( "ajax/wallettx.php?account="+accountuuid, function( data ) {				 
		  if(data['server']['cpu']!='') {
		  	  if(data['disablewallet']=="0") {
		  	  	$('#tabletransactions > tbody:last-child').html(data['accounttransactionsdetails']);
			  }
		  }
	   });
	};
	
	loadtxdata();
	
	//Load the json data for the dashboard every x seconds
	setInterval (function () {
		loadtxdata()
	}, refreshRate)
});