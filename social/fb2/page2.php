<?php

session_start();

require_once 'facebook-sdk/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;

FacebookSession::setDefaultApplication('1615086305435929', '56fce2d900bd1bd52b05050af7396e02');

$helper = new FacebookRedirectLoginHelper('http://localhost/samples/php/social/fb2/page2.php');

// Now you have the session
$session = $helper->getSessionFromRedirect();
?>