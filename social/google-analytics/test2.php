<?php //header('Access-Control-Allow-Origin: *'); ?>
<html>
<head>
<title>Test 1</title>
<!--<link rel="stylesheet" href="http://reset5.googlecode.com/hg/reset.min.css"/>-->
<link rel="stylesheet" type="text/css" href="resource/css/bootstrap-datepicker.css" />
</head>
<body>
<div id="linechart_material" style="min-height: 400px;"></div>
<div id="linechart" style="min-height: 400px;"></div>
<div id="container" style="width:100%; height:400px;"></div>

<hr>
<h1>Page specific views</h1>
<form action="" method="post" id="pform">
  <p>
    <label>Page URL</label>
    <input type="text" name="url" class="url" size="100" required value="https://www.vintelli.com/local/ca/santaana-test-business-161-24415/">
  </p>
  <p>
    <label>Select View Type</label>
    <select name="func">
      <option value="uniquePageviews">Unique Pageviews</option>
      <option value="users">Users</option>
      <option value="organicSearches">Organic Searches</option>
      <option value="pageViews">Pageviews</option>
      <option value="visitBounceRate">Bounce Rate</option>
      <option value="sessions">Locations</option>
      <option value="references">References</option>
      <option value="searches">Searches</option>
      <option value="newUsers">Visitors</option>
    </select>
  </p>
  <p>
    <label>Start Date</label>
    <input type="text" name="start" class="date start" autocomplete="off" oldautocomplete="off" required>
    <label>End Date</label>
    <input type="text" name="end" class="date end" autocomplete="off" oldautocomplete="off" required>
  </p>
  <p>
    <input name="subt" type="button" value="Submit" id="btn">
  </p>
</form>

<?php
	echo strtotime('20150101') * 1000 . '+';
	echo time() * 1000;
?>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="resource/js/bootstrap-datepicker.js"></script>
<!--<script src="resource/js/highcharts-custom.js"></script>-->
<script>
google.load("visualization", "1", { packages: ["corechart","table","geochart"] });


$(function(){
	
    $('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Fruit Consumption'
        },
        xAxis: {
            categories: ['Apples', 'Bananas', 'Oranges']
        },
        yAxis: {
            title: {
                text: 'Fruit eaten'
            }
        },
        series: [{
            name: 'Jane',
            data: [1, 0, 4]
        }, {
            name: 'John',
            data: [5, 7, 3]
        }]
    });	
	
    $.getJSON('http://b3net.tv/2015/google-analytics/base2.php', function (data) {

		//console.log( data );
		
        $('#linechart_material').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Unique Pageviews over Time'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                        'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: 'No. of Visits'
                }
            },
            legend: {
                enabled: true
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },

            series: [{
                type: 'area',
                name: 'Unique Pageviews',
                data: data
            }]
        });
    });	

  $('.date').datepicker({
      'format': 'yyyy-mm-dd',
      'autoclose': true,
      startDate: '2015-01-01',
      endDate: '0d',
      orientation: 'bottom left',
      todayBtn: true,
      todayHighlight: true
  });

  $('#btn').click(function(){
  	
	str_title 	= $( 'select option:selected' ).text();
	str_val 	= $( 'select option:selected' ).val();
	
      $.ajax({
        method: 'POST',
        url: 'http://b3net.tv/2015/google-analytics/base2.php',
        data: $('#pform').serialize(),
        dataType: "json",
      })
      .done(function( msg ) {     	

			switch ( str_val ) {
				case "uniquePageviews":
				case "users":
				case "pageViews":
				case "visitBounceRate":
						        $('#linechart').highcharts({
						            chart: {
						                zoomType: 'x'
						            },
						            title: {
						                text: str_title + ' over Date'
						            },
						            subtitle: {
						                text: document.ontouchstart === undefined ?
						                        'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
						            },
						            xAxis: {
						                type: 'datetime'
						            },
						            yAxis: {
						                title: {
						                    text: 'No. of ' + str_title
						                }
						            },
						            legend: {
						                enabled: true
						            },
						            plotOptions: {
						                area: {
						                    fillColor: {
						                        linearGradient: {
						                            x1: 0,
						                            y1: 0,
						                            x2: 0,
						                            y2: 1
						                        },
						                        stops: [
						                            [0, Highcharts.getOptions().colors[0]],
						                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						                        ]
						                    },
						                    marker: {
						                        radius: 2
						                    },
						                    lineWidth: 1,
						                    states: {
						                        hover: {
						                            lineWidth: 1
						                        }
						                    },
						                    threshold: null
						                }
						            },

						            series: [{
						                type: 'area',
						                name: str_title,
						                data: msg
						            }]
						        });
								break;

				case "organicSearches":
							var data = new google.visualization.arrayToDataTable(msg);
							var options = {
								title: str_title,
								//legend: { position: 'bottom' },
								hAxis: {
									title: 'Sources',
									textStyle: {
										fontSize: 14,
										color: '#053061',
										bold: true,
										italic: false
									},
									titleTextStyle: {
										fontSize: 18,
										color: '#053061', 
										bold: true,
										italic: false
									} 
								},
								vAxis: { 
									title: 'Total',
									textStyle: {
										fontSize: 18,
										color: '#67001f',
										bold: false,
										italic: false
									},
									titleTextStyle: {
										fontSize: 18,
										color: '#67001f',
										bold: true,
										italic: false 
									}
								}					
							};
							
							var chart = new google.visualization.ColumnChart(document.getElementById('linechart_material'));
							chart.draw(data, options);
							break;

				case "sessions":
							var data = new google.visualization.arrayToDataTable(msg);
							var options = {
								title: str_title,			
							};
							var chart = new google.visualization.GeoChart(document.getElementById('linechart_material'));
							chart.draw(data, options);
							break;

				case "references":
				case "searches":
							var data = new google.visualization.arrayToDataTable(msg);
							var options = {
								title: str_title,				
							};
							var chart = new google.visualization.Table(document.getElementById('linechart_material'));
							chart.draw(data, options);
							break;

				case "newUsers":
							var data = new google.visualization.arrayToDataTable(msg);
							var options = {
								title: str_title,
								is3D: 'true',
							};
							var chart = new google.visualization.PieChart(document.getElementById('linechart_material'));
							chart.draw(data, options);
							break;

				default :
							break;
			}

       
      	})
		  .fail(function( jqxhr, textStatus, error ) {
		    var err = textStatus + ", " + error;
		    console.log( "Request Failed: " + err );
		});      	 

  }); 


});



</script>
</body>
</html>
