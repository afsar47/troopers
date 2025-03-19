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

$ludo_challenge_data = mysqli_query($con, "SELECT * FROM `ludo_challenge` where challenge_status = '1' and accept_status = '1' and room_code = ''");

$num_challenge =  mysqli_num_rows($ludo_challenge_data);

if($num_challenge > 0) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $browser = 'Entry by cron';
     
    $heading_msg = 'Contest Canceled';
                                    
    $heading = array(
                "en" => $heading_msg,
                );
                
	while ($ludo_challenge_result = mysqli_fetch_assoc($ludo_challenge_data)) {
	    
		$game_data = mysqli_query($con, "SELECT * FROM `game` where game_id = '". $ludo_challenge_result['game_id'] ."'");

		$game_result =  mysqli_fetch_assoc($game_data);
		
	    $content_msg = $ludo_challenge_result['auto_id'] ." is canceled because room code not uploaded.";
                            
            $content = array(
                "en" => $content_msg
                );
                
	    if($ludo_challenge_result['accepted_date'] != '0000-00-00 00:00:00' || $ludo_challenge_result['accepted_date'] != '') {
	       
    	   $five_min_added = date('Y-m-d H:i:s', strtotime($ludo_challenge_result['accepted_date']. ' + 5 minutes'));
    	   
    	   	if(strtotime($five_min_added) < strtotime(date("Y-m-d H:i:s"))){
				    $ludo_challenge_update = mysqli_query($con, "UPDATE ludo_challenge set challenge_status = '2' where ludo_challenge_id = '". $ludo_challenge_result['ludo_challenge_id'] ."'");	
				
                    $member_query_data = mysqli_query($con, "SELECT * FROM `member` where member_id = '". $ludo_challenge_result['member_id'] ."'");
                    $member_data = mysqli_fetch_assoc($member_query_data);
                    

                    $join_money = $member_data['join_money'] + $ludo_challenge_result['coin'];
                        
                    $member_update = mysqli_query($con, "UPDATE member set join_money = '". $join_money  ."' where member_id = '". $member_data['member_id'] ."'");
                      
					$note = 'Cancel ' . $game_result['game_name']. ' Challenge';
				
                    $acc_data = mysqli_query($con, "insert into accountstatement (member_id,pubg_id,deposit,withdraw,join_money,win_money,note,note_id,entry_from,ip_detail,browser,accountstatement_dateCreated) VALUES('". $member_data['member_id'] ."','". $member_data['pubg_id'] ."','". $ludo_challenge_result['coin'] ."','0','". $join_money."','". $member_data['wallet_balance'] ."','". $note ."','16','3','". $ip ."','". $browser ."','". date('Y-m-d H:i:s') ."')");
                       
                    $accepted_member_query_data = mysqli_query($con, "SELECT * FROM `member` where member_id = '". $ludo_challenge_result['accepted_member_id'] ."'");
                    $accepted_member_data = mysqli_fetch_assoc($accepted_member_query_data);
                    

                    $accepted_join_money = $accepted_member_data['join_money'] + $ludo_challenge_result['coin'];
                        
                    $accepted_member_update = mysqli_query($con, "UPDATE member set join_money = '". $accepted_join_money  ."' where member_id = '". $accepted_member_data['member_id'] ."'");
                        
                    $accepted_acc_data = mysqli_query($con, "insert into accountstatement (member_id,pubg_id,deposit,withdraw,join_money,win_money,note,note_id,entry_from,ip_detail,browser,accountstatement_dateCreated) VALUES('". $accepted_member_data['member_id'] ."','". $accepted_member_data['pubg_id'] ."','". $ludo_challenge_result['coin'] ."','0','". $accepted_join_money."','". $accepted_member_data['wallet_balance'] ."','". $note ."','16','3','". $ip ."','". $browser ."','". date('Y-m-d H:i:s') ."')");
                            
                        
                        $include_player_ids = array();
                        
                        if($member_data['player_id'] != ''){
                            array_push($include_player_ids,$member_data['player_id']);
                        }
                        
                        if($accepted_member_data['player_id'] != ''){
                            array_push($include_player_ids,$accepted_member_data['player_id']);
                        }
                            
                            if(!empty($include_player_ids)){ 
                                
                                $fields = array(
                                    'app_id' => $one_signal_result['web_config_value'],
                                    'include_player_ids' => $include_player_ids,
                                    'contents' => $content,
                                    'heading' => $heading
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
                        
                                $not_response = curl_exec($ch);
                                
                                curl_close($ch);
                                
                                $not_response = json_decode($not_response,true);
                                
                                if (!array_key_exists('errors', $not_response)) {
                                    
                                    if($member_data['player_id'] != ''){
                                        $member_noti_data = mysqli_query($con, "insert into notifications (member_id,id,heading,content,date_created) VALUES('". $member_data['member_id'] ."','". $not_response['id'] ."','". $heading_msg ."','". $content_msg."','". date('Y-m-d H:i:s') ."')");
                                    }
                                    
                                    if($accepted_member_data['player_id'] != ''){
                                        $accepted_member_noti_data = mysqli_query($con, "insert into notifications (member_id,id,heading,content,date_created) VALUES('". $accepted_member_data['member_id'] ."','". $not_response['id'] ."','". $heading_msg ."','". $content_msg."','". date('Y-m-d H:i:s') ."')");
                                    }
                                }
                            }
                        
    	   	}
	    }
	}
}

    echo 'RoomCode Check Successfully !';
?>
