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
FacebookSession::setDefaultApplication('1615086305435929', '56fce2d900bd1bd52b05050af7396e02');
 
// Login Healper with reditect URI
$helper = new FacebookRedirectLoginHelper( 'http://localhost/samples/php/social/fb/test3.php' );

$_SESSION['token'] = '';

if( ! isset( $_SESSION['token'] ) )
{
	try {
	  $session = $helper->getSessionFromRedirect();
	}
	catch( FacebookRequestException $ex ) {
	  // Exception
	}
	catch( Exception $ex ) {
	  // When validation fails or other local issues
	}	
}
else
{
	$session = new FacebookSession( $_SESSION['token'] );
}


if( $session ) {
	//echo '<pre>' . print_r($session,1);
  	try 
  	{
	    $user_profile = (new FacebookRequest(
	      $session, 'GET', '/me'
	    ))->execute()->getGraphObject(GraphUser::className());
	    echo "Name: " . $user_profile->getName();
	    
	    echo '<pre>'; print_r($user_profile); echo '</pre>';
	    
		// Get a list of pages that this user admins; requires "manage_pages" permission
		$request = new FacebookRequest($session, 'GET', '/me/accounts?fields=name,access_token,perms');
		$pageList = $request->execute()->getGraphObject()->asArray();

		foreach ($pageList as $page) {
		  $pageAccessToken = $page['access_token'];
		  // Store $pageAccessToken and/or
		  // send requests to Graph on behalf of the page
		  echo '<pre>'; print_r($page); echo '</pre>';
		}	    
	    
  	} 
  	catch(FacebookRequestException $e) 
  	{
    	echo "Exception occured, code: " . $e->getCode();
    	echo " with message: " . $e->getMessage();
  	}   
}
else
{
  // Login URL if session not found
  echo '<a href="' . $helper->getLoginUrl() . '">Login</a>';
}