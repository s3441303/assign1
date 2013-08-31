<?php
// Insert your keys/tokens
$consumerKey = 'YWPM5W0zgrXYPXYd50xiGA';
$consumerSecret = 'iZzwZq4bTv5Nem9hScae48pv4WLIjeatYGorkkktuI';
$oAuthToken = '1664904618-bsBwAmP02kvtO9Ji2yJ8Lec8TUiZtSj0gfcEvjp';
$oAuthSecret = 'PMAAmsBZ3RDlTobt0I9rBelBesaaY0CFgJ4FDYQHCHQ';

require_once('OAuth.php');
require_once('twitteroauth.php');

// create new instance
$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $oAuthToken, $oAuthSecret);


$tweetMessage = "This is a test message.";

$tweet->post('statuses/update', array('status' => $tweetMessage));

// Send tweet 
// if(isset($_GET['sendTwitter'])) {
//   $tweetMessage = $_GET['sendTwitter'];
//   $tweet->post('statuses/update', array('status' => $tweetMessage));
//   echo "Twitter sent successfully.";
// }else {
// 	echo "Twitter sent unsuccessfully.";
// }



?>