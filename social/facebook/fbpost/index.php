<?php
  error_reporting(1);

  define( 'REDIR_URL', 'http://localhost/samples/php/social/facebook/fbpost/' );  

	if( ! isset( $_GET['code'] ) )
	{
		session_start();
	}	

    require_once __DIR__ . '/vendor/autoload.php';
    //facebook application configuration
    //$fbconfig['appid'] = "1598414660447378";
    //$fbconfig['secret'] = "6a9c0c67f425d79a77488b1ca5b03320";

    //echo '<pre>'; print_r( $fbconfig ); echo '</pre>'; die;

    $fbPermissions = 'publish_stream,manage_pages';  //Required facebook permissions

    // setup application using API keys and handlers
    $fb = new Facebook\Facebook([
      'app_id' => '1598414660447378',
      'app_secret' => '6a9c0c67f425d79a77488b1ca5b03320',
      'default_graph_version' => 'v2.4',
      'http_client_handler' => 'curl', // can be changed to stream or guzzle
      'persistent_data_handler' => 'session' // make sure session has started
    ]);
	
	if( isset( $_GET['code'] ) )
	{
		$helper = $fb->getRedirectLoginHelper();
		$_SESSION['FBRLH_state'] = $_REQUEST['state'];
	}
	else
	{
		// login helper with redirect_uri
		$helper = $fb->getRedirectLoginHelper( REDIR_URL );

    echo '<a href="'.  $helper->getLoginUrl(  ) .'">Login With Facebook</a>';
	}
	
	
	// see if we have a code in the URL
	if( isset( $_GET['code'] ) ) {
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
		
		// get stored access token
		$access_token = $helper->getPersistentDataHandler()->get( 'access_token' );  
	}
	
	// check if we have an access_token, and that it's valid
	if ( $access_token && !$access_token->isExpired() )
	{ 
		// set default access_token so we can use it in any requests
		$fb->setDefaultAccessToken( $access_token );
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
		//echo '<pre>' . print_r( $user ) . '</pre>'; 
		//$fb_email 		= $user['email'];
		//$fb_firstname 	= $user['first_name']; 
		//$fb_lastname	= $user['last_name'];

    $args = array(
       'message'   => 'My First Fbapplication With PHP script!',
       'link'      => 'http://www.c-sharpcorner.com/',
       'caption'   => 'Latest toorials!'
   );
   $post_id = $facebook->api("/me/feed", "post", $args);

  echo 'Post ID: ' . $post_id;

	}




 


