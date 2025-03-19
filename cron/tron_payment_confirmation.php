<?php

error_reporting(-1);
ini_set('display_errors', 1);

use IEXBase\TronAPI\Exception\TronException;
use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Tron;

// db connection
include '../database.php';

$con=mysqli_connect($hostname,$username,$password,$database);
mysqli_query ($con,"set character_set_results='utf8'");
mysqli_set_charset($con,'utf8');


// Check connection
if (mysqli_connect_errno()){
  die("Failed to connect to mysqli: " . mysqli_connect_error());
}

$config_query = mysqli_query($con, "SELECT * FROM `web_config` where web_config_name = 'timezone'");

$config = mysqli_fetch_assoc($config_query);

date_default_timezone_set($config['web_config_value']);

mysqli_query($con, "SET time_zone='". $config['web_config_value'] ."'");

// db connection end

include ('../tron/vendor/autoload.php');

$pg_sql = mysqli_query($con, "SELECT * FROM `pg_detail` as pg left join currency as c on c.currency_id = pg.currency where payment_name = 'Tron'");

$payment_detail = mysqli_fetch_assoc($pg_sql);

$receiver = $payment_detail['mid'];
$contract_address = $payment_detail['mkey'];

if($payment_detail['payment_status'] == 'Test') {
    $tron_api_url = 'https://api.shasta.trongrid.io';
} else {
    $tron_api_url = 'https://api.trongrid.io';
}

$fullNode = new HttpProvider($tron_api_url);
$solidityNode = new HttpProvider($tron_api_url);
$eventServer = new HttpProvider($tron_api_url);

try {
    $tron = new Tron($fullNode, $solidityNode, $eventServer);
} catch (TronException $exception) {
    exit($exception->getMessage());
}

$deposit_ids = array();

$ip = $_SERVER['REMOTE_ADDR'];
$browser = 'Entry by cron';

$query = "SELECT * FROM `deposit` WHERE `deposit_status`='0' AND deposit_by = 'Tron' AND `deposit_dateCreated` < NOW() ORDER BY `deposit_id` ASC LIMIT 50";
$qry = mysqli_query($con, $query);    

$current_time = strtotime(date("Y-m-d H:i:s"));                

$deposit_data = array();
     
    while ($deposit_data = mysqli_fetch_assoc($qry)) {
        try {
            $owner_address = $deposit_data['wallet_address']; 	
            $priv = $deposit_data['private_key'];   
            $amount = sprintf("%.2f",$deposit_data['deposit_amount'] / $payment_detail['currency_point']);

            $tron->setAddress($owner_address);
            $tron->setPrivateKey($priv);

            $trx_balance = $tron->getBalance(null, true);    
            $trx_balance = sprintf("%.6f",$trx_balance);           

            if($trx_balance > 0 && $trx_balance >= sprintf("%.6f",$amount)){ 
                
                // send to receiver address
                $account = $tron->send( $receiver, (float)$amount); 

                if(isset($account['result']) && $account['result'] == 1){
                    mysqli_query($con, "UPDATE `deposit` SET bank_transection_no = '". $account['txid'] ."' WHERE deposit_id = '" . $deposit_data['deposit_id'] . "'"); 
                    
                    array_push($deposit_ids,$deposit_data['deposit_id']);
                } else {
                    mysqli_query($con, "UPDATE `deposit` SET `deposit_status` = '2' WHERE deposit_id = '" . $deposit_data['deposit_id'] . "'");
                }

            } else {
                $db_date = strtotime('+30 minutes', strtotime($deposit_data['deposit_dateCreated']));
                
                if($db_date <= $current_time) {
                    mysqli_query($con, "UPDATE `deposit` SET `deposit_status` = '2' WHERE deposit_id = '" . $deposit_data['deposit_id'] . "'");
                }                
            }
        } catch (\IEXBase\TronAPI\Exception\TronException $e) {                                                 
            exit($exception->getMessage());
        }
    }    

    if(!empty($deposit_ids)) {
        sleep('10');
        // send to receiver confirmation
        $deposit_ids = '("'.implode('", "', $deposit_ids).'")';
        
        $deposit_data = array();

        $qry = mysqli_query($con, "SELECT * FROM `deposit` WHERE deposit_id IN ". $deposit_ids . " ORDER BY `deposit_id` ASC");
        
        while ($deposit_data = mysqli_fetch_assoc($qry)) {
            try {
                $owner_address = $deposit_data['wallet_address']; 	
                $priv = $deposit_data['private_key'];   
                $amount = $deposit_data['deposit_amount'];
                                
                $transaction_detail = $tron->getTransaction($deposit_data['bank_transection_no']); 
                                
                $tron_pay_status = $transaction_detail['ret'][0]['contractRet'];                        
                $transaction_receiver_address = $transaction_detail['raw_data']['contract'][0]['parameter']['value']['to_address'];            
                $value = $transaction_detail['raw_data']['contract'][0]['parameter']['value']['amount'];            
                                        
                if($tron_pay_status == 'SUCCESS' && $transaction_receiver_address == $tron->toHex($receiver)) {
                    mysqli_query($con, "UPDATE `deposit` SET `deposit_status` = '1' WHERE deposit_id = '" . $deposit_data['deposit_id'] . "'");

                        $member_query_data = mysqli_query($con, "SELECT * FROM `member` where member_id = '". $deposit_data['member_id'] ."'");
                        $member_data = mysqli_fetch_assoc($member_query_data);

                        $join_money = $member_data['join_money'] + $amount;
                                
                        $member_update = mysqli_query($con, "UPDATE member set join_money = '". $join_money  ."' where member_id = '". $member_data['member_id'] ."'");
                                
                        $acc_data = mysqli_query($con, "INSERT into accountstatement (member_id,pubg_id,deposit,withdraw,join_money,win_money,note,note_id,entry_from,ip_detail,browser,accountstatement_dateCreated) VALUES('". $member_data['member_id'] ."','". $member_data['pubg_id'] ."','". $amount ."','0','". $join_money."','". $member_data['wallet_balance'] ."','Add Money to Join Wallet','0','". $deposit_data['entry_from']."','". $ip ."','". $browser ."','". date('Y-m-d H:i:s') ."')");
                } else {
                    mysqli_query($con, "UPDATE `deposit` SET `deposit_status` = '2' WHERE deposit_id = '" . $deposit_data['deposit_id'] . "'");
                }                
                
            } catch (\IEXBase\TronAPI\Exception\TronException $e) {                                                 
                exit($exception->getMessage());
            }
        }
    }
?>