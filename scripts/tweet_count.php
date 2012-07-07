<?php
define('CONNECT_URL','localhost');
define('CONNECT_USER','root');
define('CONNECT_PASWD','mylocal');
require_once('HTTP/OAuth/Consumer.php');
require_once('DB.php');
$_dsn = array(
	'phptype'  => 'mysqli',
	'username' => 'root',
	'password' => 'mylocal',
	'hostspec' => 'localhost',
	'database' => 'torabot'
);
$db = DB::connect($_dsn);
$query = "select count(*) from access_token";
$res = $db->query($query);
while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
	if (PEAR::isError($row)) {
		error_log('torabot cannot post');
	}
	$result = $row;
}
$db->disconnect();

$con_key = '7a93ttk22r5zPgiGDKA';
$con_sec = 'MHjVyPwDcNhimGsVJCfKDa0Fu3Vp1SuhyQwyILu6M';
$oauth = new HTTP_OAuth_Consumer($con_key, $con_sec);
$http_request = new HTTP_Request2();
$http_request->setConfig('ssl_verify_peer', false);
$consumer_request = new HTTP_OAuth_Consumer_Request;
$consumer_request->accept($http_request);
$oauth->accept($consumer_request);
//ごんげ
$tora_user[0]['token'] = '128187038-HLnkbkoK2bGCI4jEEJ5utwW40EIsm3MXJk9ji6ic';
$tora_user[0]['token_secret'] = 'EFs6YJ91uwiVTFCvMu7jgpY0odeNcnv0ZoQBnQVeuo4';
//とら
$tora_user[1]['token'] = '163820436-2qtdWx1iEt0S7qfoQUlR5OdenQ1nI6rb1fkDKndg';
$tora_user[1]['token_secret'] = 'fLWa9mcxEyQKY7vfUW39kgVx5vaQ58JbWfBqzQWrtI';

foreach($tora_user as $users) {
	$oauth->setToken($users['token']);
	$oauth->setTokenSecret($users['token_secret']);
	$tweet = $result['count(*)'] . '人がとらちゃんとセッ！しています http://gong023.com/torabot/www/?action_index=true';
	$responce = $oauth->sendRequest('https://twitter.com/statuses/update.xml', array('status' => $tweet), 'POST');
}

