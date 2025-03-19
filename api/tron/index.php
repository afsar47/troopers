<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include ('vendor/autoload.php');
// For test net https://api.shasta.trongrid.io
// for live https://api.trongrid.io


$fullNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.shasta.trongrid.io');
$solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.shasta.trongrid.io');
$eventServer = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.shasta.trongrid.io');

try {
    $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer);
} catch (\IEXBase\TronAPI\Exception\TronException $e) {
    exit($e->getMessage());
}

try {
	
	$senderaddress ='TH3g29ann5ttkZ6NV7rNyygWxieD8D9EMj'; 
	$priv = '9b85f21f0e4299a84fdd05fe93b1f3fc2a06cbdb9a47ac4bcfdee4c2727ecd15';
	$receiver = 'TTVtKBCnW1jEahzDTmqFR9QmXhrQm8dfE3';
	$tron->setAddress($senderaddress);
	$tron->setPrivateKey($priv);
    $account = $tron->send( $receiver, 1); 

	// check balance 
    // $account = $tron->getBalance('TWuby5gkrvVfFpRTgFGF77KLqyjEeiiLqH', true);


} catch (\IEXBase\TronAPI\Exception\TronException $e) {
    die($e->getMessage());
}
// if($account['result'] == 1){
// echo $account['result']."<br/>";
// echo $account['txid'];

// echo "<pre>";print_r($account);	
// }


?>