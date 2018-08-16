//Catch errors
Highcharts.error = function (code) {
	// See https://github.com/highcharts/highcharts/blob/master/errors/errors.xml
	// for error id's
	alert("Graph error with code: " + code);
};

//Create the options for the graph
var chart = '';
var chartamounts = '';

var options = {
	chart: {
        type: 'column',
        renderTo: 'addresseslocked',
    },
    title: {
        text: 'NLG locked per witness account'
    },
    xAxis: {
    	min: 1,
        title: {
            text: 'Account'
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'NLG'
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: 'Amount locked: <b>{point.y:.1f}</b>'
    },
    credits: {
        enabled: false
    },
    exporting: {
        enabled: true
    },
    series: [{}]
};

var options3d = {
	chart: {
      type: 'scatter',
      renderTo: 'monthslocked',
      options3d: {
        enabled: true,
        alpha: 20,
        beta: 30,
        depth: 400, // Set deph
        viewDistance: 5,
        frame: {
          bottom: {
            size: 1,
            color: 'rgba(0,0,0,0.05)'
          }
        }
      }
    },
    title: {
        text: 'NLG locked amount/weight/duration'
    },
    tooltip: {
        pointFormat: 'Weeks locked: <b>{point.x}</b><br>Adjusted weight: <b>{point.y}</b><br>Amount locked: <b>{point.z}</b>'
    },
    subtitle: {
      text: ''
    },
    yAxis: {
      title: {
            text: 'Adjusted weight'
        }
    },
    xAxis: {
      gridLineWidth: 1,
      title: {
            text: 'Weeks locked'
        }
    },
    zAxis: {
      labels: {
        y: 5,
        rotation: 18
      },
      title: {
            text: 'Amount locked'
        }
    },
    plotOptions: {
      series: {
        groupZPadding: 10,
        depth: 100,
        groupPadding: 0,
        grouping: false,
      }
    },
    credits: {
        enabled: false
    },
    exporting: {
        enabled: true
    },
    legend: {
        enabled: false
    },
    series: [{}]
};



//Create an array to store the data
var data = [];

//Get data from ajax
$.getJSON("ajax/witnessnetwork.php", function(json) {
	
	//The bar graph with the number of Gulden locked per address
	var addressLength = json['addresslocked'].length;
	var lockedAmount = 0;
	
	//Put the data in an array
	for (i = 0; i < addressLength; i += 1) 
	{
		lockedAmount = json['addresslocked'][i];
		data.push([i+1, lockedAmount]);
	}

	//Send the array to the chart
	options.series[0].data = data;
	
	//Create the chart
	var chartamounts = new Highcharts.chart(options);
	
	//Get all 3d values
	var tdLength = json['3d'].length;
	var tdData = json['3d'];
	
	//Create a series array
	var seriesOptions = [];
	
	//Push the data to an array
	for (i = 0; i < tdLength; i += 1) 
	{
	  seriesOptions.push({
	    x: tdData[i]['time'],
	    y: tdData[i]['weight'],
	    z: tdData[i]['amount'],
	  });
	}
	
	//Send the array to the chart
	options3d.series[0].data = seriesOptions;
	
	//Create the 3D chart
	var chart = new Highcharts.chart(options3d);
	
	// Add mouse events for rotation
	$(chart.container).bind('mousedown.hc touchstart.hc', function(eStart) {
		eStart = chart.pointer.normalize(eStart);
		var posX = eStart.pageX,
		  posY = eStart.pageY,
		  alpha = chart.options.chart.options3d.alpha,
		  beta = chart.options.chart.options3d.beta,
		  newAlpha,
		  newBeta,
		  sensitivity = 5; // lower is more sensitive
		$(document).bind({
		  'mousemove.hc touchdrag.hc': function(e) {
		    newBeta = beta + (posX - e.pageX) / sensitivity;
		    chart.options.chart.options3d.beta = newBeta;
		    newAlpha = alpha + (e.pageY - posY) / sensitivity;
		    chart.options.chart.options3d.alpha = newAlpha;
		    chart.redraw(false);
		  },
		  'mouseup touchend': function() {
		    $(document).unbind('.hc');
		      }
	    });
	});

});