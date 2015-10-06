<?php
session_start();

define( 'APP_ID', '1615086305435929' );
define( 'APP_SECRET', '56fce2d900bd1bd52b05050af7396e02' );
define( 'REDIR_URL', 'http://localhost/samples/php/social/fb/' );

require_once 'facebook-php-sdk/autoload.php';
 
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
 
// Initialize&nbsp;application by Application ID and Secret
FacebookSession::setDefaultApplication( APP_ID, APP_SECRET);
 
// Login Healper with reditect URI
$helper = new FacebookRedirectLoginHelper( REDIR_URL );
 
try {
  $session = $helper->getSessionFromRedirect();
}
catch( FacebookRequestException $ex ) {
  // Exception
}
catch( Exception $ex ) {
  // When validation fails or other local issues
}
 
// Checking Session
if(isset($session))
{
  // Request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // Responce
  $data = $response->getGraphObject();
   
  // Print data
  echo  print_r( $data, 1 );
}
else
{
  // Login URL if session not found
  echo '<a href="' . $helper->getLoginUrl(array('scope' => 'email,read_stream')) . '">Login</a>';
}
?>
