<?php

class Ludo_challenge_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'ludo_challenge';
        $this->table_member = 'member';
//        $this->column_headers = array(
//            'Game Name' => '',
//            'Image' => '',
//        );
    }

    public function  update_result(){
            
            $this->db->where('ludo_challenge_id', $_POST['ludo_challenge_id']);
            $ludo_challenge_data = $this->db->get('ludo_challenge')->row();
             
            $this->db->where('game_id', $ludo_challenge_data->game_id);
            $game_data = $this->db->get('game')->row();

            if($ludo_challenge_data->challenge_status == '2' || $ludo_challenge_data->challenge_status == '3') {
                return false;
            }
            
            $this->load->library('user_agent');
            $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
            $ip = $this->input->ip_address();
                                
            if($_POST['challenge_status'] == 3){
                
                        $this->db->where('member_id', $_POST['member_id']);
                        $member_data = $this->db->get('member')->row();
                        
                        $ludo_challenge_update = [
                                                    'winner_id' => $_POST['member_id'],
                                                    'challenge_status' => '3',
                                                ];
                                                
                            $this->db->where('ludo_challenge_id', $_POST['ludo_challenge_id']);
                            $this->db->update('ludo_challenge',$ludo_challenge_update);
        
                                $wallet_balance = $member_data->wallet_balance + $ludo_challenge_data->winning_price;
                                
                                $member_update_data = [
                                    'wallet_balance' => $wallet_balance,
                                ];
                                
                                $this->db->where('member_id', $_POST['member_id']);
                                $this->db->update('member',$member_update_data);
        
                                $acc_data = [
                                    'member_id' => $_POST['member_id'],
                                    'pubg_id' => $member_data->pubg_id,
                                    'deposit' => $ludo_challenge_data->winning_price,
                                    'withdraw' => 0,
                                    'join_money' => $member_data->join_money,
                                    'win_money' => $wallet_balance,
                                    'note' => 'Win '. $game_data->game_name.' Challenge #' . $_POST['ludo_challenge_id'],                                    
                                    'note_id' => '17',
                                    'entry_from' => '1',
                                    'ip_detail' => $ip,
                                    'browser' => $browser,
                                    'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                                    ];
                                $this->db->insert('accountstatement',$acc_data);
                
                $this->db->where('member_id', $ludo_challenge_data->accepted_member_id);
                $accepted_member_data = $this->db->get('member')->row();
                    
                $heading_msg = 'Contest Completed';
                $content_msg = 'Winner of Challenge ' . $ludo_challenge_data->auto_id . ' is ' . $member_data->first_name . ' ' . $member_data->last_name;
                
                $include_player_ids = array();
                        
                        if($member_data->player_id != ''){
                            array_push($include_player_ids,$member_data->player_id);
                        }
                        
                        if($accepted_member_data->player_id != ''){
                            array_push($include_player_ids,$accepted_member_data->player_id);
                        }
                        
            } elseif($_POST['challenge_status'] == 2){
                    
                    $ludo_challenge_update = ['challenge_status' => '2'];
                                                
                    $this->db->where('ludo_challenge_id', $_POST['ludo_challenge_id']);
                    $this->db->update('ludo_challenge',$ludo_challenge_update);
                    
                    $this->db->where('member_id', $ludo_challenge_data->member_id);
                    $member_data = $this->db->get('member')->row();

                        $join_money = $member_data->join_money + $ludo_challenge_data->coin;
                        
                        $member_update_data = [
                            'join_money' => $join_money,
                        ];
                        
                        $this->db->where('member_id', $ludo_challenge_data->member_id);
                        $this->db->update('member',$member_update_data);
                        
                        $acc_data = [
                            'member_id' => $ludo_challenge_data->member_id,
                            'pubg_id' => $member_data->pubg_id,
                            'deposit' => $ludo_challenge_data->coin,
                            'withdraw' => 0,
                            'join_money' => $join_money,
                            'win_money' => $member_data->wallet_balance,
                            'note' => 'Cancel '. $game_data->game_name.' Challenge #' . $_POST['ludo_challenge_id'],                            
                            'note_id' => '16',
                            'entry_from' => '1',
                            'ip_detail' => $ip,
                            'browser' => $browser,
                            'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                        ];
                        $this->db->insert('accountstatement',$acc_data); 
                        
                    $this->db->where('member_id', $ludo_challenge_data->accepted_member_id);
                    $accepted_member_data = $this->db->get('member')->row();
            
                        $accepted_join_money = $accepted_member_data->join_money + $ludo_challenge_data->coin;
                        
                        $accepted_member_update_data = [
                            'join_money' => $accepted_join_money,
                        ];
                        
                        $this->db->where('member_id', $ludo_challenge_data->accepted_member_id);
                        $this->db->update('member',$accepted_member_update_data);
                        
                        $accepted_acc_data = [
                            'member_id' => $ludo_challenge_data->accepted_member_id,
                            'pubg_id' => $accepted_member_data->pubg_id,
                            'deposit' => $ludo_challenge_data->coin,
                            'withdraw' => 0,
                            'join_money' => $accepted_join_money,
                            'win_money' => $accepted_member_data->wallet_balance,
                            'note' => 'Cancel '. $game_data->game_name.' Challenge #' . $_POST['ludo_challenge_id'],                            
                            'note_id' => '16',
                            'entry_from' => '1',
                            'ip_detail' => $ip,
                            'browser' => $browser,
                            'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                        ];
                        $this->db->insert('accountstatement',$accepted_acc_data);
                        
                $heading_msg = 'Contest Canceled';
                $content_msg = $ludo_challenge_data->auto_id ." is canceled by Admin.";
                
                $player_ids = array();
                        
                if($member_data->player_id != '' && $member_data->push_noti == '1'){
                    array_push($player_ids,$member_data->player_id);
                }
                
                if($accepted_member_data->player_id != '' && $accepted_member_data->push_noti == '1'){
                    array_push($player_ids,$accepted_member_data->player_id);
                }
                        
            }
             
            if(!empty($player_ids)) {
                $msg = array(
                    'body'  => $content_msg,
                    'title' => $heading_msg,                
                    'icon'  => 'Default',                   
                );
                        
                $fields = array (
                    'registration_ids' => $player_ids,
                    'notification' => $msg,        
                );                  
                                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization:key=' . $this->system->app_id));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                
                $not_response = curl_exec($ch);
                
                curl_close($ch);
                
                $not_response = json_decode($not_response,true);
                
                if (isset($not_response['success'])) {
                    if($member_data->player_id != '' && $member_data->push_noti == '1'){
                        $notification_data = [
                            'member_id' => $member_data->member_id,
                            'id' => $not_response['multicast_id'],
                            'heading' => $heading_msg,
                            'content' => $content_msg,
                            'game_id' => $ludo_challenge_data->game_id,
                            'date_created' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->db->insert('notifications',$notification_data);
                    }
                    
                    if($accepted_member_data->player_id != '' && $accepted_member_data->push_noti == '1'){
                        $accepted_notification_data = [
                            'member_id' => $accepted_member_data->member_id,
                            'id' => $not_response['multicast_id'],
                            'heading' => $heading_msg,
                            'content' => $content_msg,
                            'game_id' => $ludo_challenge_data->game_id,                       
                            'date_created' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->db->insert('notifications',$accepted_notification_data);
                    }
                }
            }
            
            return true;
    }
    
    public function get_list_count_ludo_challenge() {
        $this->db->select('*');
        $this->db->order_by("ludo_challenge_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }
    
    public function getRoomCodeById($ludo_challenge_id) {
        $this->db->select('*');
        $this->db->where('challenge_id', $ludo_challenge_id);
        $this->db->order_by("challenge_room_code_id", "Desc");
        $query = $this->db->get('challenge_room_code');
        return $query->result_array();
    }
    
    public function ludo_challenge_data() {
        $this->db->select('l.*,g.game_name');
        $this->db->order_by("ludo_challenge_id", "Desc");
		$this->db->join('game as g', 'g.game_id = l.game_id','left');
        $query = $this->db->get($this->table . ' as l');
        return $query->result();
    }

    public function getchallengeById($ludo_challenge_id) {
        $this->db->select('l.*,m.first_name,m.last_name,m.profile_image,m1.first_name  as accepted_first_name,m1.last_name  as accepted_last_name,m1.profile_image as accepted_profile_image,g.game_logo');
        $this->db->where('ludo_challenge_id', $ludo_challenge_id);
        $this->db->join('member as m', 'm.member_id = l.member_id','left');		
        $this->db->join('member as m1', 'm1.member_id = l.accepted_member_id','left');
		$this->db->join('game as g', 'g.game_id = l.game_id','left');
        $query = $this->db->get('ludo_challenge as l');
        
        return $query->row_array();
    }
    
    public function getChallengeAddeddUplodedResult($ludo_challenge_id) {
        $this->db->select('l.ludo_challenge_id,l.auto_id,l.ludo_king_username,cr.challenge_result_upload_id,cr.result_image,cr.reason,cr.result_status');
        $this->db->where('cr.ludo_challenge_id', $ludo_challenge_id);
        $this->db->where('cr.result_uploded_by_flag', '0');
        $this->db->join('ludo_challenge as l', 'cr.ludo_challenge_id = l.ludo_challenge_id','left');
        $query = $this->db->get('challenge_result_upload as cr');
        
        return $query->row_array();
    }
    
    public function getChallengeAcceptedUplodedResult($ludo_challenge_id) {
        $this->db->select('l.ludo_challenge_id,l.auto_id,l.accepted_ludo_king_username,cr.challenge_result_upload_id,cr.result_image,cr.reason,cr.result_status');
        $this->db->where('cr.ludo_challenge_id', $ludo_challenge_id);
        $this->db->where('cr.result_uploded_by_flag', '1');
        $this->db->join('ludo_challenge as l', 'cr.ludo_challenge_id = l.ludo_challenge_id','left');
        $query = $this->db->get('challenge_result_upload as cr');
        
        return $query->row_array();
    }

    public function delete() {
        
        $this->db->where('ludo_challenge_id', $this->input->post('ludo_challenge_id'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        foreach($this->input->post('ids') as $key => $ludo_challenge_id){
            $this->db->where('ludo_challenge_id', $ludo_challenge_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    } 

}
