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

$game_sql = mysqli_query($con, "SELECT * FROM `game` where `status` = '1' and `game_type` = '1'");

while($game_data = mysqli_fetch_assoc($game_sql)){
    
    $ludo_challenge_sql = mysqli_query($con, "SELECT * FROM `ludo_challenge` where `challenge_status` = '1' and `notification_status` = '0' and `accept_status` = '0' and `date_created` <= DATE_SUB(NOW(),INTERVAL 5 MINUTE)");

    $ludo_challenge_auto_id = '';
    while($ludo_challenge_data = mysqli_fetch_assoc($ludo_challenge_sql)){
        $ludo_challenge_auto_id .= $ludo_challenge_data['auto_id'] . ',';        
    }

    if($ludo_challenge_auto_id != '') {
        $heading_msg = 'New Challenge Available';                        
        $content_msg = 'New Challenges "' . trim($ludo_challenge_auto_id, ',') . '" available in '. $game_data['game_name'] . '. If you interested then accept the challenge.';

        $not_intrested_member = json_decode($game_data['not_intrested_member'],true);

        $member_sql = mysqli_query($con, "SELECT * FROM `member` where `member_status` = '1' and `player_id` != ''");

        $player_ids = array();
        $member_ids = array();

        while($member_data = mysqli_fetch_assoc($member_sql)){
            if(!in_array($member_data['member_id'],$not_intrested_member)){                
                array_push($player_ids,$member_data['player_id']);                                
                array_push($member_ids,$member_data['member_id']);
            }
        }

        if(!empty($player_ids)){

            $heading = array(
                "en" => $heading_msg,
            );
            
            $content = array(
                "en" => $content_msg
            );

            $fields = array(
                'app_id' => $one_signal_result['web_config_value'],
                'include_player_ids' => $player_ids,
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
                foreach($member_ids as $member_id) {
                    mysqli_query($con, "insert into notifications (member_id,id,heading,content,date_created) VALUES('". $member_id ."','". $not_response['id'] ."','". $heading_msg ."','". $content_msg."','". date('Y-m-d H:i:s') ."')");
                }   
                
                echo 'Notification send successfully.';
            }
        } else {
            echo 'No one member interested.';
        }

        while($ludo_challenge_data = mysqli_fetch_assoc($ludo_challenge_sql)){            
            $ludo_challenge_update = mysqli_query($con, "UPDATE ludo_challenge set notification_status = '1' where ludo_challenge_id = '". $ludo_challenge_data['ludo_challenge_id'] ."'");	
        }
    } else {
        echo 'all challenges already accepted';
    }
}
?>
