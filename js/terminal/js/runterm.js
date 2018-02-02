$(document).ready(function() {
	jQuery(function($) {
	    $('#term').terminal("lib/terminal/rpc_commands.php", {
	        greetings: "Type 'help' to show a list of commands.",
	        height: 400,
	    });
	});
});