<?php
session_start();
include_once("config-test.php");
include_once("inc/twitteroauth.php");

if(isset($_SESSION['token']) && $_SESSION['token_secret'])
{
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['token'] , $_SESSION['token_secret']);
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

	if($connection->http_code=='200')
	{
		$_SESSION['screen_name']  = $access_token['screen_name'];
    $_SESSION['tw_user_id']   = $access_token['user_id'];
    $_SESSION['access_token'] = $access_token['oauth_token'];
    $_SESSION['access_token_secret'] = $access_token['oauth_token_secret'];	

		header('Location: ./test.php');
	}else{
		die("error, try again later!");
	}
}
else
{

	if(isset($_GET["denied"]))
	{
		header('Location: ./test.php');
		die();
	}

	//fresh authentication
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
	
	//received token info from twitter
	$_SESSION['token'] 			= $request_token['oauth_token'];
	$_SESSION['token_secret'] 	= $request_token['oauth_token_secret'];
	
	// any value other than 200 is failure, so continue only if http code is 200
	if($connection->http_code=='200')
	{
		//redirect user to twitter
		$twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
		header('Location: ' . $twitter_url); 
	}else{
		die("error connecting to twitter! try again later!");
	}
}