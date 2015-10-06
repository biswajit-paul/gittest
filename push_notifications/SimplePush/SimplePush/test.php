<?php

// Put your device token here (without spaces):
//$deviceToken = 'e1fc74f98def8ae160e0e1d56d4abb7507a5d2d543962c5f0c0077217eee4ab3';
//$deviceToken = '98f6adae5ab161f8398e3d50bcaa402ec84c619b477dcca7d4eba43c67b7dfb1';
$deviceToken = 'c942f1bde021b7b3ac8ab12b2883f1b74d1d5b7f2eae28ad2453d2f115e51da6';

// Put your private key's passphrase here:
$passphrase = 'b3net';

// Put your alert message here:
$message = 'My new first push notification!';

////////////////////////////////////////////////////////////////////////////////

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'Vintelli.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
stream_context_set_option($ctx, 'ssl', 'cafile', 'entrust_2048_ca.cer');

// Open a connection to the APNS server
// ssl://gateway.sandbox.push.apple.com:2195
// ssl://gateway.push.apple.com:2195
$fp = stream_socket_client(
	'ssl://gateway.sandbox.push.apple.com:2195', $err,
	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
	exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
$body['aps'] = array(
	'alert' => $message,
	'sound' => 'default'
	);

$body['aps'] = array(
    'alert' => array(
      'title' => 'Game Request',
      'body'  => 'Bob wants to play poker',
      'action-loc-key' => 'VIEW'
    ),
    'sound' => 'default',
    'badge' => 1
	);

$body['acme1'] = 'bar';
//$body['acme2'] = array('bang', 'whiz');

// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
	echo 'Message not delivered' . PHP_EOL;
else
	echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
fclose($fp);
