<?php

$ch = curl_init();
 
//set the endpoint url
curl_setopt($ch,CURLOPT_URL, 'https://api.twitter.com/oauth2/token');
// has to be a post
curl_setopt($ch,CURLOPT_POST, true);
$data = array();
$data['grant_type'] = "client_credentials";
curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
 
// here's where you supply the Consumer Key / Secret from your app:
$consumerKey = 'txQz0JCph1RwslgnAl3EDHStR';
$consumerSecret = 'TgY1pAyZxHMWEOVLQU06ewXjNNb9Jk4X9beDgcdWmV4cmMEPZ7';           
curl_setopt($ch,CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
 
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
 
//execute post
$result = curl_exec($ch);
 
//close connection
curl_close($ch);
 
// show the result, including the bearer token (or you could parse it and stick it in a DB)       
print_r($result);