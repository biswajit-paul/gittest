<?php //header('Access-Control-Allow-Origin: *'); ?>
<html>
<head>
<title>Test 1</title>
<!--<link rel="stylesheet" href="http://reset5.googlecode.com/hg/reset.min.css"/>-->
<link rel="stylesheet" type="text/css" href="resource/css/bootstrap-datepicker.css" />
</head>
<body>
<div id="linechart_material" style="min-height: 200px;"></div>

<hr>
<h1>Page specific views</h1>
<form action="" method="post" id="pform">
  <p>
    <label>Page URL</label>
    <input type="text" name="url" size="100" required value="https://www.vintelli.com/local/ca/santaana-test-business-161-24415/">
  </p>
  <p>
    <label>Select View Type</label>
    <select name="viewtype">
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
    <input type="text" name="start_date" class="date" autocomplete="off" oldautocomplete="off" required>
    <label>End Date</label>
    <input type="text" name="end_date" class="date" autocomplete="off" oldautocomplete="off" required>
  </p>
  <p>
    <input name="subt" type="button" value="Submit" id="btn">
  </p>
</form>






<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="resource/js/bootstrap-datepicker.js"></script>
<script>
google.load("visualization", "1", { packages: ["corechart","table","geochart"] });


$(function(){

  $('.date').datepicker({
      'format': 'yyyy-mm-dd',
      'autoclose': true,
      endDate: '0d',
      orientation: 'bottom left',
      todayBtn: true,
      todayHighlight: true
  });

  $('#btn').click(function(){

		//str_title = $( "select option:selected" ).text();
		//str_val 	= $( "select option:selected" ).val();

		//console.log( str_val );

      $.ajax({
        method: 'POST',
        url: 'http://b3net.tv/2015/google-analytics/base.php',
        data: $('#pform').serialize(),
        dataType: "json",
      })
      .done(function( msg ) {

			str_title = $( "select option:selected" ).text();
			str_val 	= $( "select option:selected" ).val();
			console.log( str_val );

			switch ( str_val ) {
				case "uniquePageviews":
				case "users":
				case "pageViews":
				case "visitBounceRate":
								console.log( str_val );
								var data = new google.visualization.DataTable(msg);
								var options = {
									title: str_title,
									//legend: { position: 'bottom' },
									hAxis: {
										title: 'Date',
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
									, lineWidth: 2, pointSize: 3, pointWidth: 6
								};
								
								var chart = new google.visualization.LineChart(document.getElementById('linechart_material'));
								chart.draw(data, options);
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

       
      	}); 
  }); 


});



</script>
</body>
</html>
