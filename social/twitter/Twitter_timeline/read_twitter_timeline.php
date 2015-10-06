<?php

include("TwitterAPIExchange.php");

$settings = array(
    'oauth_access_token' => "-- Enter Your Access Token --",
    'oauth_access_token_secret' => "-- Enter Your Token Secret --",
    'consumer_key' => "-- Enter Your Consumer Key --",
    'consumer_secret' => "-- Enter Your Consumer Secret --"
);



$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$getfield = '?screen_name=a2zwebhelp';

$requestMethod = 'GET';

$twitter = new TwitterAPIExchange($settings);
$response = $twitter->setGetfield($getfield)
                    ->buildOauth($url, $requestMethod)
                    ->performRequest();
                    
//var_dump(json_decode($response)); /* Here you will get all info from user timeline */

$valid_data = json_decode($response); //JSON data to PHP.

print "<ul>";
foreach ($valid_data as $key=>$value) {
  print "<li>";
  print $value->text;
  print "</li>";
}
print "</ul>";
?>
