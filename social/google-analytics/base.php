<?php header('Access-Control-Allow-Origin: *'); ?>
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

    $array['cols'][] = array('type' => 'string', 'label' => 'Date');
    $array['cols'][] = array('type' => 'number', 'label' => 'Visits');

    foreach( $rows as $row )
    {
      // Add '-' character after 3rd character in $row
      $row[1] = substr($row[1], 0, 4)."-".substr($row[1], 4);
      // Add '-' character after 3rd character in $row
      $row[1] = substr($row[1], 0, -2)."-".substr($row[1], -2);    	
      $array['rows'][]['c'] = array(
        array('v' => "Date: $row[1]"),
        array('v' => $row[2])
      );
    }
    echo json_encode($array);    
  }
  else
  {
    print "No results found.\n";
  }
}


// Organic results
function getOrganicResults(&$analytics, $profileId, $options) {
  // Calls the Core Reporting API and queries for the number of sessions
  // for the last seven days.
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       $options['start_date'],
       $options['end_date'],
        'ga:visits',
        array(
        'filters' => 'ga:pagePath==' . $options['page_path'],
        'dimensions' => 'ga:source',
        'metrics' => 'ga:' . $options['metrics'],
        //'sort' => 'ga:date',
        )        
    );
}

function printOrganicResults(&$results) {
  // Parses the response from the Core Reporting API and prints
  // the profile name and total sessions.
  if (count($results->getRows()) > 0)
  {
    // Get the profile name.
    $profileName = $results->getProfileInfo()->getProfileName();

    // Get the entry for the first entry in the first row.
    $rows = $results->getRows();
    
    $str = array();

    //print_r( $array ); exit;

    foreach( $rows as $row )
    {   	
      $str[] = '["' . $row[0] . '",' . $row[1] . ']';
    }
    echo '[["Source","No. of Visits"],' . implode( ',', $str ) . ']';
  }
  else
  {
    print "No results found.\n";
  }
}


// Geographic Locations
function getGeochartResults(&$analytics, $profileId, $options) {
  // Calls the Core Reporting API and queries for the number of sessions
  // for the last seven days.
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       $options['start_date'],
       $options['end_date'],
        'ga:visits',
        array(
        'filters' => 'ga:pagePath==' . $options['page_path'],
        'dimensions' => 'ga:country',
        'metrics' => 'ga:' . $options['metrics'],
        //'sort' => 'ga:date',
        )        
    );
}

function printGeochartResults(&$results) {
  // Parses the response from the Core Reporting API and prints
  // the profile name and total sessions.
  if (count($results->getRows()) > 0)
  {
    // Get the profile name.
    $profileName = $results->getProfileInfo()->getProfileName();

    // Get the entry for the first entry in the first row.
    $rows = $results->getRows();
    
    $str = array();

    //print_r( $array ); exit;

    foreach( $rows as $row )
    {   	
      $str[] = '["' . $row[0] . '",' . $row[1] . ']';
    }
    echo '[["Country", "No. of Visits"],' . implode( ',', $str ) . ']';
  }
  else
  {
    print "No results found.\n";
  }
}


// Full referrer
function getReferencedResults(&$analytics, $profileId, $options) {
  // Calls the Core Reporting API and queries for the number of sessions
  // for the last seven days.
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       $options['start_date'],
       $options['end_date'],
        'ga:visits',
        array(
        'filters' => 'ga:pagePath==' . $options['page_path'],
        'dimensions' => 'ga:fullReferrer',
        'metrics' => 'ga:' . $options['metrics'],
        )        
    );
}

function printReferencedResults(&$results) {
  // Parses the response from the Core Reporting API and prints
  // the profile name and total sessions.
  if (count($results->getRows()) > 0)
  {
    // Get the profile name.
    $profileName = $results->getProfileInfo()->getProfileName();

    // Get the entry for the first entry in the first row.
    $rows = $results->getRows();
    
    $str = array();

    //print_r( $array ); exit;

    foreach( $rows as $row )
    {   	
      $str[] = '["' . $row[0] . '",' . $row[1] . ']';
    }
    echo '[["Referring Path", "No. of Visits"],' . implode( ',', $str ) . ']';
  }
  else
  {
    print "No results found.\n";
  }
}


// Searches / Keywords results
function getKeywordsResults(&$analytics, $profileId, $options) {
  // Calls the Core Reporting API and queries for the number of sessions
  // for the last seven days.
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       $options['start_date'],
       $options['end_date'],
        'ga:visits',
        array(
        'filters' => 'ga:pagePath==' . $options['page_path'],
        'dimensions' => 'ga:keyword',
        'metrics' => 'ga:' . $options['metrics'],
        )        
    );
}

function printKeywordsResults(&$results) {
  // Parses the response from the Core Reporting API and prints
  // the profile name and total sessions.
  if (count($results->getRows()) > 0)
  {
    // Get the profile name.
    $profileName = $results->getProfileInfo()->getProfileName();

    // Get the entry for the first entry in the first row.
    $rows = $results->getRows();
    
    $str = array();

    //print_r( $array ); exit;

    foreach( $rows as $row )
    {   	
      $str[] = '["' . $row[0] . '",' . $row[1] . ']';
    }
    echo '[["Keywords", "No. of Users"],' . implode( ',', $str ) . ']';
  }
  else
  {
    print "No results found.\n";
  }
}


// New Users
function getNewVisitorResults(&$analytics, $profileId, $options) {
  // Calls the Core Reporting API and queries for the number of sessions
  // for the last seven days.
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       $options['start_date'],
       $options['end_date'],
        'ga:visits',
        array(
        'filters' => 'ga:pagePath==' . $options['page_path'],
        'dimensions' => 'ga:userType',
        'metrics' => 'ga:' . $options['metrics'],
        //'sort' => 'ga:date',
        )        
    );
}

function printNewVisitorResults(&$results) {
  // Parses the response from the Core Reporting API and prints
  // the profile name and total sessions.
  if (count($results->getRows()) > 0)
  {
    // Get the profile name.
    $profileName = $results->getProfileInfo()->getProfileName();

    // Get the entry for the first entry in the first row.
    $rows = $results->getRows();
    
    $str = array();

    //print_r( $array ); exit;

    foreach( $rows as $row )
    {   	
      $str[] = '["' . $row[0] . '",' . $row[1] . ']';
    }
    echo '[["User Type", "Users"],' . implode( ',', $str ) . ']';
  }
  else
  {
    print "No results found.\n";
  }
}


// Variables Declarations
$analytics = getService();
$profile = getFirstProfileId($analytics);
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$mod_url = parse_url( $_POST['url'] );























$sess_view = array('uniquePageviews', 'users', 'pageViews', 'visitBounceRate');

if( in_array( $_POST['viewtype'], $sess_view ) )
{
  $options = array(
    'start_date'  => $start_date,
    'end_date'    => $end_date,
    'page_path'   => $mod_url['path'],
    'metrics'     => $_POST['viewtype']
  );

  $results = getResults($analytics, $profile, $options);
  printResults($results);
  //exit;
}
if( $_POST['viewtype'] == 'organicSearches' )
{
  $options = array(
    'start_date'  => $start_date,
    'end_date'    => $end_date,
    'page_path'   => $mod_url['path'],
    'metrics'     => $_POST['viewtype']
  );

  $results = getOrganicResults($analytics, $profile, $options);
  //echo '<pre>'; print_r($results); echo '</pre>';
  printOrganicResults($results);
  //exit;
}
if( $_POST['viewtype'] == 'sessions' )
{
  $options = array(
    'start_date'  => $start_date,
    'end_date'    => $end_date,
    'page_path'   => $mod_url['path'],
    'metrics'     => $_POST['viewtype']
  );

  $results = getGeochartResults($analytics, $profile, $options);
  //echo '<pre>'; print_r($results); echo '</pre>';
  printGeochartResults($results);
  //exit;
}
if( $_POST['viewtype'] == 'references' )
{
  $options = array(
    'start_date'  => $start_date,
    'end_date'    => $end_date,
    'page_path'   => $mod_url['path'],
    'metrics'     => 'pageviews'
  );

  $results = getReferencedResults($analytics, $profile, $options);
  //echo '<pre>'; print_r($results); echo '</pre>';
  printReferencedResults($results);
  //exit;
}
if( $_POST['viewtype'] == 'searches' )
{
  $options = array(
    'start_date'  => $start_date,
    'end_date'    => $end_date,
    'page_path'   => $mod_url['path'],
    'metrics'     => 'users'
  );

  $results = getKeywordsResults($analytics, $profile, $options);
  //echo '<pre>'; print_r($results); echo '</pre>';
  printKeywordsResults($results);
  //exit;
}
if( $_POST['viewtype'] == 'newUsers' )
{
  $options = array(
    'start_date'  => $start_date,
    'end_date'    => $end_date,
    'page_path'   => $mod_url['path'],
    'metrics'     => 'users'
  );

  $results = getNewVisitorResults($analytics, $profile, $options);
  //echo '<pre>'; print_r($results); echo '</pre>';
  printNewVisitorResults($results);
  //exit;
}