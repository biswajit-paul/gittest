<?php 
	header('Access-Control-Allow-Origin: *'); 
	header("Content-type: text/json");
?>
<?php

//echo '<pre>'; print_r($_POST); exit;

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

function getResults(&$analytics, $profileId, $options) {
  // Calls the Core Reporting API and queries for the number of sessions
  // for the last seven days.
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       $options['start_date'],
       $options['end_date'],
        'ga:sessions',
        array(
        'filters' => 'ga:pagePath==' . $options['page_path'],
        'dimensions' => 'ga:day,ga:date',//ga:date,ga:year,ga:month,ga:day
        'metrics' => 'ga:' . $options['metrics'],
        'sort' => 'ga:date',
        )        
    );
}

function printResults(&$results) {
  // Parses the response from the Core Reporting API and prints
  // the profile name and total sessions.
  if (count($results->getRows()) > 0)
  {
    // Get the profile name.
    $profileName = $results->getProfileInfo()->getProfileName();

    // Get the entry for the first entry in the first row.
    $rows = $results->getRows();
	$array = array();
	
	//echo time() . strtotime('20150101');
    foreach( $rows as $row )
    {
		$d = DateTime::createFromFormat('Ymd', $row[1]);  	
		$array[] = array( date( 'U', strtotime( $row[1] ) ) * 1000, $row[2] );
    }

    echo json_encode($array, JSON_NUMERIC_CHECK);
  }
  else
  {
    print "No results found.\n";
  }
}








// Variables Declarations
$analytics = getService();
$profile = getFirstProfileId($analytics);
$start_date = $_POST['start'];
$end_date = $_POST['end'];
$url = $_POST['url'];
$mod_url = parse_url( $url );























$options = array(
'start_date'  => '2015-06-01',
'end_date'    => '2015-06-30',
'page_path'   => '/local/ca/santaana-test-business-161-24415/',
'metrics'     => 'uniquePageviews'
);

//$results = getResults($analytics, $profile, $options);
//printResults($results);

$sess_view = array('uniquePageviews', 'users', 'pageViews', 'visitBounceRate');

if( in_array( $_POST['func'], $sess_view ) )
{
  $options = array(
    'start_date'  => $start_date,
    'end_date'    => $end_date,
    'page_path'   => $mod_url['path'],
    'metrics'     => $_POST['func']
  );
	//print_r( $options );
  $results = getResults($analytics, $profile, $options);
  printResults($results);
  //exit;
}
