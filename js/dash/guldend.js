$(document).ready(function() {	
	var refreshRate = 10000;
	
	// Create speedometer gauge CPU
	var CPUmeter = $('#CPU').SonicGauge ({
		label	: 'CPU (%)',
		start	: {angle: -225, num: 0},
		end		: {angle: 45, num: 100},
		markers	: [
			{
				gap: 25,
				line: {"width": 15, "stroke": "none", "fill": "#eeeeee"},
				text: {"space": 16, "text-anchor": "middle", "fill": "#333333", "font-size": 12}
			},{
				gap: 10, 
				line: {"width": 10, "stroke": "none", "fill": "#aaaaaa"},
				text: {"space": 13, "text-anchor": "middle", "fill": "#333333", "font-size": 9}
			},{
				gap: 1, 
				line: {"width": 7, "stroke": "none", "fill": "#999999"}
			}
		],
		animation_speed : 2000,
		diameter: 250,
		style	: {
					"label"		: {"font-size": 13}
			},
		digital	: {
					"width"				: "25%",
					"font-size"			: 13,
					"color"				: '#fff',
					"text-align"		: "center",
					"border"			: "2px solid #590303",
					"border-radius"		: 25,
					"padding"			: 5,
					"background-color"	: "#a83209"
				},
	});
	
	// Create speedometer gauge MEM
	var MEMmeter = $('#MEM').SonicGauge ({
		label	: 'MEM (%)',
		start	: {angle: -225, num: 0},
		end		: {angle: 45, num: 100},
		markers	: [
			{
				gap: 25,
				line: {"width": 15, "stroke": "none", "fill": "#eeeeee"},
				text: {"space": 16, "text-anchor": "middle", "fill": "#333333", "font-size": 12}
			},{
				gap: 10, 
				line: {"width": 10, "stroke": "none", "fill": "#aaaaaa"},
				text: {"space": 13, "text-anchor": "middle", "fill": "#333333", "font-size": 9}
			},{
				gap: 1, 
				line: {"width": 7, "stroke": "none", "fill": "#999999"}
			}
		],
		animation_speed : 2000,
		diameter: 250,
		style	: {
					"label"		: {"font-size": 13}
			},
		digital	: {
					"width"				: "25%",
					"font-size"			: 13,
					"color"				: '#fff',
					"text-align"		: "center",
					"border"			: "2px solid #590303",
					"border-radius"		: 25,
					"padding"			: 5,
					"background-color"	: "#a83209"
				},
	});	
	
	//Load json data for the dashboard
	var loadjsondata = function() {
	
	  $.getJSON( "ajax/dashboard.php", function( data ) {				 
		  
		  if(data['server']['cpu']!='') {
		  	  CPUmeter.SonicGauge ('val', data['server']['cpu']);
		  	  MEMmeter.SonicGauge ('val', data['server']['mem']);
		  	
			  $('#serverinfopanel').html("<div class='table-responsive'><table class='table table-striped'>"+
			  							 "<tr><td><b>Version:</b></td><td>"+data['gulden']['version']+"</td></tr>"+
			  							 "<tr><td><b>Protocol:</b></td><td>"+data['gulden']['protocolversion']+"</td></tr>"+
			  							 "<tr><td><b>Server uptime:</b></td><td>"+data['gulden']['uptime']+"</td></tr>"+
			  							 "<tr><td><b>Sync status:</b></td><td>"+data['gulden']['sync']+"</td></tr>"+
			  							 "<tr><td><b>Local blocks:</b></td><td>"+data['gulden']['blocks']+"</td></tr>"+
			  							 "<tr><td><b>Total blocks:</b></td><td>"+data['gulden']['allblocks']+"</td></tr>"+
			  							 "<tr><td><b>Time offset:</b></td><td>"+data['gulden']['timeoffset']+"</td></tr>"+
			  							 "<tr><td><b>Current difficulty:</b></td><td>"+data['gulden']['difficulty']+"</td></tr>"+
			  							 "<tr><td><b>Connections:</b></td><td>"+data['node']['connections']+"</td></tr>"+
			  							 "<tr><td><b>CPU usage:</b></td><td>"+data['server']['cpu']+"%</td></tr>"+
			  							 "<tr><td><b>MEM usage:</b></td><td>"+data['server']['mem']+"%</td></tr>"+
			  							 "</table></div>");
			  							 
		  } else {
		  	  $('#serverinfopanel').html("GuldenD is not running");
		  }
		  
		  if(data['errors']!='') {
		  	$('#errordiv').html("<div class='alert alert-warning'>"+data['errors']+"</div>")
		  }	  
	   });
	};
	
	loadjsondata();
	
	//Load the json data for the dashboard every x seconds
	setInterval (function () {
		loadjsondata()
	}, refreshRate)	
});