<?php
if( session_status() == PHP_SESSION_NONE ) {
	session_start();	
}
require_once 'facebook-php-sdk/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;

// Initialize&nbsp;application by Application ID and Secret
FacebookSession::setDefaultApplication('739032912892158', '26f4bb11c2ba9ca6c363fa20ca5ffe95');
 
// Login Healper with reditect URI
$helper = new FacebookRedirectLoginHelper( 'http://localhost/samples/php/social/fb/test.php' );

echo '<pre>'; print_r($_SESSION); echo '</pre>';


try {
	if( ! isset( $_SESSION['token'] ) )
	{
  		$session = $helper->getSessionFromRedirect();
  	}
  	else
  	{
		$session = new FacebookSession( $_SESSION['token'] );			
	}

	if( $session ) {
		//echo '<pre>' . print_r($session,1);
	  try {
	    $user_profile = (new FacebookRequest(
	      $session, 'GET', '/me'
	    ))->execute()->getGraphObject(GraphUser::className());
	    echo "Name: " . $user_profile->getName() . '<br>';
			echo "Email: " . $user_profile->getProperty("email") . '<br>';
			echo "First Name: " . $user_profile->getProperty("first_name") . '<br>';
			echo "Last Name: " . $user_profile->getProperty("last_name") . '<br>';
	    
	    echo '<pre>'; print_r($user_profile); echo '</pre>';
	  } catch(FacebookRequestException $e) {
	    echo "Exception occured, code: " . $e->getCode();
	    echo " with message: " . $e->getMessage();
	  }   
	  
		$accessToken = $session->getAccessToken();
		$longLivedAccessToken = $accessToken->extend();
		echo 'Short Access Token: ' . $accessToken . '<br/><br/>Long Access Token: ' . $longLivedAccessToken;

    $request = new FacebookRequest(
      $session,
      'GET',
      '/me/feed'
    );
    $response = $request->execute();
    $graphObject = $response->getGraphObject();
  
    echo '<pre>'; print_r( $graphObject ); echo '</pre>';

	}
	else
	{
	  // Login URL if session not found
	  echo '<a href="' . $helper->getLoginUrl(array('scope' => 'email,read_stream,user_posts')) . '">Login</a>';
	}
}
catch( FacebookRequestException $ex ) {
  // Exception
}
catch( Exception $ex ) {
  // When validation fails or other local issues
}



