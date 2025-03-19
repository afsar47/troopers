<?php

include('../database.php');

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

$one_signal_data = mysqli_query($con, "SELECT web_config_value FROM `web_config` where web_config_name = 'app_id'");

$one_signal_result =  mysqli_fetch_assoc($one_signal_data);

$ludo_challenge_data = mysqli_query($con, "SELECT * FROM `ludo_challenge` where challenge_status = '1' and accept_status = '1' and room_code != ''");

$num_challenge =  mysqli_num_rows($ludo_challenge_data);

if($num_challenge > 0) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $browser = 'Entry by cron';
                
	while ($ludo_challenge_result = mysqli_fetch_assoc($ludo_challenge_data)) {
	       
		$game_data = mysqli_query($con, "SELECT * FROM `game` where game_id = '". $ludo_challenge_result['game_id'] ."'");

		$game_result =  mysqli_fetch_assoc($game_data);
		
	        $win_heading_msg = 'Winner Declare';
            $win_content_msg = "You are winner of " . $ludo_challenge_result['auto_id'] . " Challenge.";
                                    
            $win_content = array(
                        "en" => $win_content_msg
                        );
                                            
            $win_heading = array(
                        "en" => $win_heading_msg,
                );
                
            $panelty_heading_msg = 'Panelty Charge';
            $panelty_content_msg = "10 Coin cut from your wallet because of you not upload your result of ". $ludo_challenge_result['auto_id'] . " Challenge at correct time";
                                    
            $panelty_content = array(
                        "en" => $panelty_content_msg
                        );
                                            
            $panelty_heading = array(
                        "en" => $panelty_heading_msg,
                );
                
	       $challenge_uploaded_result_data = mysqli_query($con, "SELECT * FROM `challenge_result_upload` where ludo_challenge_id = '".$ludo_challenge_result['ludo_challenge_id']."'");
	       
	       $challenge_uploaded_result =  mysqli_fetch_assoc($challenge_uploaded_result_data);
    	   
    	   if(mysqli_num_rows($challenge_uploaded_result_data) == 1 && $challenge_uploaded_result['result_status'] == '0') {
    	       
    	       $two_hour_added = date('Y-m-d H:i:s', strtotime($challenge_uploaded_result['date_created']. ' + 2 hour'));
    	       
    	   	    if(strtotime($two_hour_added) <= strtotime(date("Y-m-d H:i:s"))){
    	   	        
    	   	       // winner declare
				    $ludo_challenge_update = mysqli_query($con, "UPDATE ludo_challenge set challenge_status = '3',winner_id = '". $challenge_uploaded_result['member_id'] ."' where ludo_challenge_id = '". $challenge_uploaded_result['ludo_challenge_id'] ."'");	
				
                    $member_query_data = mysqli_query($con, "SELECT * FROM `member` where member_id = '". $challenge_uploaded_result['member_id'] ."'");
                    $member_data = mysqli_fetch_assoc($member_query_data);
                    

                    $wallet_balance = $member_data['wallet_balance'] + $ludo_challenge_result['winning_price'];
                        
                    $member_update = mysqli_query($con, "UPDATE member set wallet_balance = '". $wallet_balance  ."' where member_id = '". $member_data['member_id'] ."'");
                     
					$note = 'Win ' . $game_result['game_name'] . ' Challenge #' . $ludo_challenge_result['ludo_challenge_id'];					
					
                    $acc_data = mysqli_query($con, "insert into accountstatement (member_id,pubg_id,deposit,withdraw,join_money,win_money,note,note_id,entry_from,ip_detail,browser,accountstatement_dateCreated) VALUES('". $member_data['member_id'] ."','". $member_data['pubg_id'] ."','". $ludo_challenge_result['winning_price'] ."','0','". $member_data['join_money']."','". $wallet_balance ."','". $note ."','17','3','". $ip ."','". $browser ."','". date('Y-m-d H:i:s') ."')");
                    
                    if($member_data['player_id'] != ''){
                            $fields = array(
                                    'app_id' => $one_signal_result['web_config_value'],
                                    'include_player_ids' => array($member_data['player_id']),
                                    'contents' => $win_content,
                                    'heading' => $win_heading
                                );
                                
                                $fields = json_encode($fields);
                                
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                                curl_setopt($ch, CURLOPT_POST, TRUE);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                                
                                $not_response = '';
                                $not_response = curl_exec($ch);
                                
                                curl_close($ch);
                                
                                $not_response = json_decode($not_response,true);
                                
                                  if (!array_key_exists('errors', $not_response)) {  
                                    if($member_data['player_id'] != ''){
                                        $member_noti_data = mysqli_query($con, "insert into notifications (member_id,id,heading,content,date_created) VALUES('". $member_data['member_id'] ."','". $not_response['id'] ."','". $win_heading_msg ."','". $win_content_msg."','". date('Y-m-d H:i:s') ."')");
                                    }
                                  }
                    }
                        
                    //  panelty to other
                     
                    if($challenge_uploaded_result['member_id'] == $ludo_challenge_result['member_id']) {
                        
                        $other_member_query_data = mysqli_query($con, "SELECT * FROM `member` where member_id = '". $ludo_challenge_result['accepted_member_id'] ."'");
                        $other_member_data = mysqli_fetch_assoc($other_member_query_data);
                        
                    } elseif($challenge_uploaded_result['member_id'] == $ludo_challenge_result['accepted_member_id']) {
                        
                        $other_member_query_data = mysqli_query($con, "SELECT * FROM `member` where member_id = '". $ludo_challenge_result['member_id'] ."'");
                        $other_member_data = mysqli_fetch_assoc($other_member_query_data);
                        
                    }
                    
                        if ($other_member_data['join_money'] > 10) {
                            $other_join_money = $other_member_data['join_money'] - 10;
                            $other_wallet_balance = $other_member_data['wallet_balance'];
                        } elseif ($other_member_data['join_money'] < 10) {
                            $other_join_money = 0;
                            $amount1 = 10 - $other_member_data['join_money'];
                            $other_wallet_balance = $other_member_data['wallet_balance'] - $amount1;
                        } elseif ($other_member_data['join_money'] == 10) {
                            $other_join_money = 0;
                            $other_wallet_balance = $other_member_data['wallet_balance'];
                        }
                        
                    $other_member_update = mysqli_query($con, "UPDATE member set join_money = '". $other_join_money  ."',wallet_balance = '". $other_wallet_balance  ."' where member_id = '". $other_member_data['member_id'] ."'");
                        
                    $other_acc_data = mysqli_query($con, "insert into accountstatement (member_id,pubg_id,deposit,withdraw,join_money,win_money,note,note_id,entry_from,ip_detail,browser,accountstatement_dateCreated) VALUES('". $other_member_data['member_id'] ."','". $other_member_data['pubg_id'] ."','0','10','". $other_join_money."','". $other_wallet_balance ."','Panelty Charge','18','3','". $ip ."','". $browser ."','". date('Y-m-d H:i:s') ."')");
                            
                        
                        if($other_member_data['player_id'] != ''){
                            $other_fields = array(
                                    'app_id' => $one_signal_result['web_config_value'],
                                    'include_player_ids' => array($other_member_data['player_id']),
                                    'contents' => $panelty_content,
                                    'heading' => $panelty_heading
                                );
                                
                                $other_fields = json_encode($other_fields);
                                
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                                curl_setopt($ch, CURLOPT_POST, TRUE);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $other_fields);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                                
                                $other_not_response = '';
                                $other_not_response = curl_exec($ch);
                                 
                                curl_close($ch);
                                
                                $other_not_response = json_decode($other_not_response,true);
                                  
                                  if (!array_key_exists('errors', $other_not_response)) {  
                                    if($other_member_data['player_id'] != ''){
                                        $member_noti_data = mysqli_query($con, "insert into notifications (member_id,id,heading,content,date_created) VALUES('". $other_member_data['member_id'] ."','". $other_not_response['id'] ."','". $panelty_heading_msg ."','". $panelty_content_msg."','". date('Y-m-d H:i:s') ."')");
                                    }
                                  }
                    }
    	   	    }      
    	   	}
	}
}

echo 'Result Checking Successfully !';
?>
