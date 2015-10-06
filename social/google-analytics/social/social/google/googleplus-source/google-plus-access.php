<?php
require_once 'google-api-php-client/src/apiClient.php';
require_once 'google-api-php-client/src/contrib/apiPlusService.php';

session_start();

$client = new apiClient();
$client->setApplicationName("Google+ PHP Starter Application");

//*********** Replace with Your API Credentials **************
$client->setClientId('312021842419-ad2m0g33mbe862eapkl19qpbnd5mfhag.apps.googleusercontent.com');
$client->setClientSecret('9efxIU0KSGb6m2QTqcUew0eG');
$client->setRedirectUri('http://localhost/samples/php/social/google/googleplus-source/');
$client->setDeveloperKey('AIzaSyC8Y8HuCv44VAbUO8uvlDhK_NobMS7bwdo');
//************************************************************
 
$client->setScopes(array('https://www.googleapis.com/auth/plus.me'));
$plus = new apiPlusService($client);

echo '<pre>'; print_r( $_SESSION ); echo '</pre>';

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
  setcookie('token', "", time() - 3600);
}

if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['access_token'])) {
  $client->setAccessToken($_SESSION['access_token']);

  //json decode the session token and save it in a variable as object
  $sessionToken = json_decode($_SESSION['access_token']);
  //Save the refresh token (object->refresh_token) into a cookie called 'token' and make last for 1 month
  if (isset($sessionToken->refresh_token)) { //refresh token is only set after a proper authorisation
      $number_of_days = 30 ;
      $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days ;
      setcookie('token', $sessionToken->refresh_token, $date_of_expiry);
  }

}
else if (isset($_COOKIE["token"])) {//if we don't have a session we will grab it from the cookie
    $client->refreshToken($_COOKIE["token"]);//update token
}

if ($client->getAccessToken()) {
  $me = $plus->people->get('me');

  $optParams = array('maxResults' => 100);
  $activities = $plus->activities->listActivities('me', 'public', $optParams);


  $_SESSION['access_token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
}
?>