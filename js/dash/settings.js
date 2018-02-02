$(document).ready(function() {
	$("#settingsform").validate({
	       rules: {
	           gdashpassword: { 
	             	required: false,
	                minlength: 8,
	           } , 
	               gdashpasswordrepeat: { 
	                equalTo: "#gdashpassword",
	                minlength: 8,
	           }
	       },
		 messages:{
		     gdashpassword: { 
		             required:"The password is required",
		             minlength: "Please enter at least 8 characters",
		          },
		     gdashpasswordrepeat: {
		     		required:"The password repeat is required",
		         },
		 }
	   });
	   
	$('#changedpass').click(function(){
		$(location).attr("href", "?");
	});
	
	
	$.getJSON( "ajax/dashboard.php", function( data ) {
  	  if(data['node']['inbound']=="0" || data['node']['inbound']=="") {
  	  	$("#nodeupload").attr("disabled", true);
  	  	$("#nodeuploaddiv").attr("disabled", true);
  	  	$('#nodeupload').prop('checked', false);
  	  	$('#nodeuploadhelp').html("You have no incoming connections. You have to configure your node to enable this option.");
  	  }
	});
	
});