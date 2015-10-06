<?php
error_reporting(1);

date_default_timezone_set('America/Los_Angeles');

require_once 'facebook-sdk/autoload.php';

session_start();

// setup application using API keys and handlers
$fb = new Facebook\Facebook([
  'app_id' => '1615086305435929',
  'app_secret' => '56fce2d900bd1bd52b05050af7396e02',
  'default_graph_version' => 'v2.4',
  'http_client_handler' => 'curl', // can be changed to stream or guzzle
  'persistent_data_handler' => 'session' // make sure session has started
]);

// login helper with redirect_uri
$helper = $fb->getRedirectLoginHelper('http://localhost/samples/php/social/fb2/page1.php');

// see if we have a code in the URL
if ( isset( $_GET['code'] ) ) {
  echo '<pre>' . print_r( $_SESSION ) . '</pre>'; 
  // get new access token if we've been redirected from login page
  try {
    // get access token
    $access_token = $helper->getAccessToken();
    
    // save access token to persistent data store
    $helper->getPersistentDataHandler()->set( 'access_token', $access_token );
  } catch ( Exception $e ) {
    // error occured
    echo 'Exception 1: ' . $e->getMessage() . '<br>';
  }
}
// get stored access token
$access_token = $helper->getPersistentDataHandler()->get( 'access_token' );

echo '<pre>' . print_r( $access_token ) . '</pre>'; 

// check if we have an access_token, and that it's valid
if ( $access_token && !$access_token->isExpired() ) {
  
  // set default access_token so we can use it in any requests
  $fb->setDefaultAccessToken( $access_token );
  try {
    // If you provided a 'default_access_token', second parameter '{access-token}' is optional.
    $response = $fb->get( '/me' );
    // use $fb->post() to make a POST API call
  } catch( Exception $e ) {
    // catch any errors and halt script
    echo $e->getMessage();
    exit;
  }
  
  $me = $response->getGraphUser();
  echo '<pre>' . print_r( $me, 1 ) . '</pre>';
  echo '<p>Logged in as ' . $me->getName() . '</p>';
  
  echo '<p><a href="' . $helper->getLogoutUrl( $access_token, 'http://localhost/samples/php/social/fb2/page1.php' ) . '">Logout of Facebook</a></p>';
} else {
  // show login link
  echo '<a href="' . $helper->getLoginUrl( 'http://localhost/samples/php/social/fb2/page1.php', ['email'] ) . '">Login using Facebook</a>';
}





try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=first_name,last_name,email', $access_token);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$user = $response->getGraphObject()->asArray();
echo '<pre>' . print_r( $user ) . '</pre>'; 