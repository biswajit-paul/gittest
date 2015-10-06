<?php
include_once("inc/facebook.php"); //include facebook SDK
 
######### edit details ##########
$appId = '1598414660447378'; //Facebook App ID
$appSecret = '6a9c0c67f425d79a77488b1ca5b03320'; // Facebook App Secret
$return_url = 'http://localhost/samples/php/social/facebook/publish_to_wall/process.php';  //return url (url to script)
$homeurl = 'http://localhost/samples/php/social/facebook/publish_to_wall/index.php';  //return to home
$fbPermissions = 'publish_stream,manage_pages';  //Required facebook permissions
##################################

//Call Facebook API
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $appSecret
));

$fbuser = $facebook->getUser();
?>