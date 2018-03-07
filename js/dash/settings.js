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
	
});