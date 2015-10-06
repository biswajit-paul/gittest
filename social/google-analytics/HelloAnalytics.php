<?php

function getService()
{
  // Creates and returns the Analytics service object.

  // Load the Google API PHP Client Library.
  require_once 'google-api-php-client/src/Google/autoload.php';

  // Use the developers console and replace the values with your
  // service account email, and relative location of your key file.
  $service_account_email = '736346199349-jo1j8fvja38egd46ouooubga2vg3aofv@developer.gserviceaccount.com';
  $key_file_location = 'client_secrets.p12';

  // Create and configure a new client object.
  $client = new Google_Client();
  $client->setApplicationName("HelloAnalytics");
  $analytics = new Google_Service_Analytics($client);

  // Read the generated client_secrets.p12 key.
  $key = file_get_contents($key_file_location);
  $cred = new Google_Auth_AssertionCredentials(
      $service_account_email,
      array(Google_Service_Analytics::ANALYTICS_READONLY),
      $key
  );
  $client->setAssertionCredentials($cred);
  if($client->getAuth()->isAccessTokenExpired()) {
    $client->getAuth()->refreshTokenWithAssertion($cred);
  }

  return $analytics;
}

function getFirstprofileId(&$analytics) {
  // Get the user's first view (profile) ID.

  // Get the list of accounts for the authorized user.
  $accounts = $analytics->management_accounts->listManagementAccounts();

  if (count($accounts->getItems()) > 0) {
    $items = $accounts->getItems();
    $firstAccountId = $items[0]->getId();

    // Get the list of properties for the authorized user.
    $properties = $analytics->management_webproperties
        ->listManagementWebproperties($firstAccountId);

    if (count($properties->getItems()) > 0) {
      $items = $properties->getItems();
      $firstPropertyId = $items[0]->getId();

      // Get the list of views (profiles) for the authorized user.
      $profiles = $analytics->management_profiles
          ->listManagementProfiles($firstAccountId, $firstPropertyId);

      if (count($profiles->getItems()) > 0) {
        $items = $profiles->getItems();

        // Return the first view (profile) ID.
        return $items[0]->getId();

      } else {
        throw new Exception('No views (profiles) found for this user.');
      }
    } else {
      throw new Exception('No properties found for this user.');
    }
  } else {
    throw new Exception('No accounts found for this user.');
  }
}

function getResults(&$analytics, $profileId) {
  // Calls the Core Reporting API and queries for the number of sessions
  // for the last seven days.
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       //'7daysAgo',
       //'today',
       //'ga:sessions',
       
       '30daysAgo',
       'yesterday',
       'ga:sessions',
        array(
        'dimensions' => 'ga:userType',
        'metrics' => 'ga:sessions',
        )
       
       //'ga:visits',
       // array(
       // 'filters' => 'ga:pagePath=@/local/ca',
       // 'dimensions' => 'ga:pagePath,ga:city,ga:country',
       // 'metrics' => 'ga:pageviews,ga:newUsers,ga:bounceRate',
       // 'sort' => '-ga:pageviews',
       // 'max-results' => '25'
       // )
        
    );
}

function getGAResults(&$analytics, $profileId, $start_dt='30daysAgo',$end_dt='yesterday',$arr=array()) {
  // Calls the Core Reporting API and queries for the number of sessions
  // for the last seven days.
   return $analytics->data_ga->get(
       'ga:' . $profileId,
        $start_dt,//yyyy-mm-dd(2015-06-25)
        $end_dt,//yyyy-mm-dd(2015-06-25)
       'ga:sessions',//'ga:visits'
        $arr
       
       //'ga:visits',
       // array(
       // 'filters' => 'ga:pagePath=@/local/ca',
       // 'dimensions' => 'ga:pagePath,ga:city,ga:country',
       // 'metrics' => 'ga:pageviews,ga:newUsers,ga:bounceRate',
       // 'sort' => '-ga:pageviews',
       // 'max-results' => '25'
       // )
        
    );
}

function printResults(&$results) {
  // Parses the response from the Core Reporting API and prints
  // the profile name and total sessions.
  if (count($results->getRows()) > 0) {

    // Get the profile name.
    $profileName = $results->getProfileInfo()->getProfileName();

    // Get the entry for the first entry in the first row.
    $rows = $results->getRows();
    $sessions = $rows[0][0];
    

    // Print the results.
    print "First view (profile) found: $profileName\n";
    //print "Total sessions: $sessions\n";
    print_r($rows);
  } else {
    print "No results found.\n";
  }
}

$analytics = getService();
$profile = getFirstProfileId($analytics);
$results = getResults($analytics, $profile);

//$pagepath = '';
//if(isset($_REQUEST['page_path'])){
//  
//  $pagepath = 'ca/santaana-test-business-161-24415/';//$_REQUEST['page_path']
//  $gaarr = array(
//        'filters' => 'ga:pagePath==/local/'.$pagepath,
//        'dimensions' => 'ga:pagePath,ga:userType',
//        'metrics' => 'ga:sessions',
//        'sort' => '-ga:sessions',
//        );
//}else{
//  $gaarr = array(
//        'filters' => 'ga:pagePath=@/local/',
//        'dimensions' => 'ga:pagePath,ga:userType',
//        'metrics' => 'ga:sessions',
//        'sort' => '-ga:sessions',
//        );
//}

$pagepath = '/ca/santaana-test-business-161-24415/';//$_REQUEST['page_path']
$gaarr = array(
        'filters' => 'ga:pagePath==/local'.$pagepath,
        'dimensions' => 'ga:pagePath,ga:date',//ga:date,ga:year,ga:month,ga:day
        'metrics' => 'ga:sessions',
        'sort' => '-ga:date',
        );


$garesults = getGAResults($analytics, $profile,'30daysAgo','yesterday',$gaarr);
echo '<pre>';
$alldata = printResults($results);
printResults($garesults);


$rows= $results->rows;
$pageviews=$rows[1][1];
$newUsers = $rows[1][3];
$users = $rows[1][4];
$sessionsPerUser=$rows[1][5];
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          <?php echo "['New Users', {$newUsers}],";?>
          <?php echo "['Returning Users', {$users}],";?>
          //['Work',     11],
          //['Eat',      2],
          //['Commute',  2],
          //['Watch TV', 2],
          //['Sleep',    7]
        ]);

        var options = {
          title: 'My Daily Activities',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
      
</script>

<script type="text/javascript">
google.load('visualization', '1', {packages: ['corechart', 'line']});
google.setOnLoadCallback(drawBasic);

function drawBasic() {

      var data = new google.visualization.DataTable();
      
      data.addColumn('number', 'X');
      data.addColumn('number', 'Session');

      data.addRows([
        <?php
        if($garesults->rows){
          $sessarr=array();
          foreach($garesults->rows as $garesult){
            $dt =$garesult[1];
            $sess_val = $garesult[2];
            $sessarr[]= '['.$dt.','.$sess_val.']';
          }
          echo implode(',',$sessarr);
        }
        ?>
        //[0, 0],   [1, 10],  [2, 23],  [3, 17],  [4, 18],  [5, 9],
        //[6, 11],  [7, 27],  [8, 33],  [9, 40],  [10, 32], [11, 35],
        //[12, 30], [13, 40], [14, 42], [15, 47], [16, 44], [17, 48],
        //[18, 52], [19, 54], [20, 42], [21, 55], [22, 56], [23, 57],
        //[24, 60], [25, 50], [26, 52], [27, 51], [28, 49], [29, 53],
        //[30, 55], [31, 60], [32, 61], [33, 59], [34, 62], [35, 65],
        //[36, 62], [37, 58], [38, 55], [39, 61], [40, 64], [41, 65],
        //[42, 63], [43, 66], [44, 67], [45, 69], [46, 69], [47, 70],
        //[48, 72], [49, 68], [50, 66], [51, 65], [52, 67], [53, 70],
        //[54, 71], [55, 72], [56, 73], [57, 75], [58, 70], [59, 68],
        //[60, 64], [61, 60], [62, 65], [63, 67], [64, 68], [65, 69],
        //[66, 70], [67, 72], [68, 75], [69, 80]
      ]);

      var options = {
        hAxis: {
          title: 'Date'
        },
        vAxis: {
          title: 'Session'
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

      chart.draw(data, options);
    }
    
   </script>

   

  </head>
  <body>

     <div id="chart_div" style="width: 900px; height: 500px;"><div>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>