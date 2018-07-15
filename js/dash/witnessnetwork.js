//Catch errors
Highcharts.error = function (code) {
	// See https://github.com/highcharts/highcharts/blob/master/errors/errors.xml
	// for error id's
	alert("Graph error with code: " + code);
};

//Create the options for the graph
var chart = '';
var x = 1;

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

//Create an array to store the data
var data = [];

//Get data from ajax
$.getJSON("ajax/witnessnetwork.php", function(json) {
	
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
	var chart = new Highcharts.chart(options);

});