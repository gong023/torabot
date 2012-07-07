<?php
define('CONNECT_URL','localhost');
define('CONNECT_USER','root');
define('CONNECT_PASWD','mylocal');
require_once('HTTP/OAuth/Consumer.php');
require_once('DB.php');
require_once('fetch_toratweet.php');
$_dsn = array(
	'phptype'  => 'mysqli',
	'username' => 'root',
	'password' => 'mylocal',
	'hostspec' => 'localhost',
	'database' => 'torabot'
);
$db = DB::connect($_dsn);
$query = "select * from access_token";
$res = $db->query($query);
$tora_user = array();
while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
	if (PEAR::isError($row)) {
		error_log('torabot DB error');
	}
	$tora_user[] = $row;
//	break;
}
$db->disconnect();
shuffle($tora_user);

$con_key = '7a93ttk22r5zPgiGDKA';
$con_sec = 'MHjVyPwDcNhimGsVJCfKDa0Fu3Vp1SuhyQwyILu6M';
$oauth = new HTTP_OAuth_Consumer($con_key, $con_sec);
$http_request = new HTTP_Request2();
$http_request->setConfig('ssl_verify_peer', false);
$consumer_request = new HTTP_OAuth_Consumer_Request;
$consumer_request->accept($http_request);
$oauth->accept($consumer_request);
$fetch_tora = new ToraBot;
foreach($tora_user as $tora_users) {
	try {
		$oauth->setToken($tora_users['token']);
		$oauth->setTokenSecret($tora_users['token_secret']);
		$tora_tweet = $fetch_tora->checkToraTweet();
		$tweet = $fetch_tora->tweet_se($tora_tweet) . ' #とら';
		$responce = $oauth->sendRequest('https://twitter.com/statuses/update.xml', array('status' => $tweet), 'POST');
	} catch (Exception $e) {
		error_log($e->getMessage());
	}
}

