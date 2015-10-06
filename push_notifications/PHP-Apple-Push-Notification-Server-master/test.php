<?php
/**
 * Usage Examples
 *
 * @author  Sebastian Borggrewe <me@sebastianborggrewe.de>
 * @since   2010/01/24
 * @package APNP
 */

error_reporting(E_ALL | E_STRICT);
include 'APNSBase.php';
include 'APNotification.php';
include 'APFeedback.php';

try{

  # Notification Example
  $notification = new APNotification('production');
  $notification->setDeviceToken("e1fc74f98def8ae160e0e1d56d4abb7507a5d2d543962c5f0c0077217eee4ab3");
  $notification->setMessage("Test Push");
  $notification->setBadge(1);
  $notification->setPrivateKey('Vintelli.pem');
  $notification->setPrivateKeyPassphrase('b3net');
  $notification->send();

  # Feedback Example
  //$feedback = new APFeedback('development');
  //$feedback->setPrivateKey('Vintelli.pem');
  //$feedback->setPrivateKeyPassphrase('b3net');
  //$feedback->receive();

}catch(Exception $e){
  echo $e->getLine().': '.$e->getMessage();
}
?>
