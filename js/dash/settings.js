$(document).ready(function() {
	$.validator.addMethod("pwcheck", function(value) {
		if($("#gdashpassword").length>1) {
	   	return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
	       && /[A-Z]/.test(value) // has a upperrcase letter
	       && /\d/.test(value) // has a digit
	   } else {
	   	return true;
	   }
	});
	
	$("#settingsform").validate({
	       rules: {
	           gdashpassword: { 
	             	required: false,
	                minlength: 8,
	                pwcheck: true,
	           } , 
	               gdashpasswordrepeat: { 
	                equalTo: "#gdashpassword",
	                minlength: 8,
	           }
	       },
		 messages:{
		     gdashpassword: { 
		             required:"The password is required",
		             pwcheck: "The password is not strong enough.",
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