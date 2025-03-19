<?php

class Member_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'member';

        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image');
        $this->profile_image_size_array = array(100 => 100);

//        $this->column_headers = array(
//            'Name' => '',
//            'User Name' => '',
//            'Email' => '',
//            'Mobile No' => '',
//            'Referral No' => '',
//            'Status' => '',
//        );
//        $this->member_wallet_column_headers = array(
//            'Deposit (' . $this->functions->getPoint() . ')' => '',
//            'Withdraw (' . $this->functions->getPoint() . ')' => '',
//            'Join Money (' . $this->functions->getPoint() . ')' => '',
//            'Win Money (' . $this->functions->getPoint() . ')' => '',
//            'Note' => '',
//            'Date' => '',
//        );
//        $this->member_states_column_headers = array(
//            'Match Info' => '',
//            'Paid (' . $this->functions->getPoint() . ')' => '',
//            'Won (' . $this->functions->getPoint() . ')' => '',
//            'Date' => '',
//        );
//        $this->member_referral_column_headers = array(
//            'Player Name' => '',
//            'Earning (' . $this->functions->getPoint() . ')' => '',
//            'Status' => '',
//            'Date' => '',
//        );
    }

    public function getgameById($game_id) {
        $this->db->select('*');
        $this->db->where('game_type','0');
        $this->db->where('game_id', $game_id);
        $query = $this->db->get('game');
        return $query->row_array();
    }

    public function get_list_count_member() {
        $this->db->select('*');
        $this->db->order_by("member_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function get_list_count_MemberWallet($member_id) {
        $this->db->select('*');
        $this->db->where('member_id', $member_id);
        $this->db->where('(deposit != 0 OR withdraw != 0)');
        $query = $this->db->get('accountstatement');
        return $query->num_rows();
    }

    public function get_list_count_MemberStates($member_id) {
        $this->db->select('*');
        $this->db->where('member_id', $member_id);
        $this->db->join('matches as m', 'm.m_id = mj.match_id', 'LEFT');
        $query = $this->db->get('match_join_member as mj');
        return $query->num_rows();
    }

    public function get_list_count_MemberReferral($member_id) {
        $this->db->select('*');
        $this->db->where('referral_id', $member_id);
        $query = $this->db->get('member');
        $data = $query->result_array();
        return $query->num_rows();
    }

    public function update() {
       
        if ($_FILES['profile_image']['name'] == "") {                                                    
            $profile_image = $this->input->post('old_profile_image');
        } else {                    
            $thumb_sizes = $this->profile_image_size_array;
            if (file_exists($this->profile_image . $this->input->post('old_profile_image'))) {
                @unlink($this->profile_image . $this->input->post('old_profile_image'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->profile_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_profile_image'))) {
                    @unlink($this->profile_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_profile_image'));
                }
            }
            
            $profile_image = 'member_' . rand() .'_'. $this->input->post('member_id') . '.' . pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
            
            $config['file_name'] = $profile_image;
            $config['upload_path'] = $this->profile_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('profile_image')) {
                $error = array('error' => $this->upload->display_errors());                        
            } else {
                $image = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->profile_image . $profile_image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->profile_image . $profile_image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->profile_image . "thumb/" . $key . "x" . $val . "_" . $profile_image);
                    } else {
                        copy($this->profile_image . $profile_image, $this->profile_image . "thumb/" . $key . "x" . $val . "_" . $profile_image);
                    }
                }
            }
        }        

        foreach($_POST['game_id'] as $key => $value) {
            $pubg[$value] = $_POST['pubg_id'][$key];
        }

        $pubg_id = serialize($pubg);
        
        $data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'user_name' => $this->input->post('user_name'),
            'email_id' => $this->input->post('email_id'),
            'dob' => $this->input->post('dob'),
            'mobile_no' => $this->input->post('mobile_no'),
            'gender' => $this->input->post('gender'),
            'country_id' => $this->input->post('country_id'),
            'country_code' => $this->input->post('country_code'),
            'profile_image' => $profile_image,
            'pubg_id' => $pubg_id
        );
        if ($this->input->post('password') != '') {
            $passwordarr = array('password' => md5($this->input->post('password')));
            $data = $data + $passwordarr;
        }

        $this->db->where('member_id', $this->input->post('member_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_wallet() {
        $member_id = $this->input->post('member_id');
        $amount = $this->input->post('amount');
        $wallet = $this->input->post('wallet');
        $this->load->library('user_agent');
        $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
        $ip = $this->input->ip_address();
        if ($this->input->post('plus_minus') == '-') {
            $this->db->select('*');
            $this->db->where("member_id", $member_id);
            $query2 = $this->db->get('member');
            $result = $query2->row_array();
            if ($wallet == 'wallet_balance') {
                $win_money = $result['wallet_balance'] - $amount;
                $join_money = $result['join_money'];
                $comment = 'Withdraw from Win Wallet By Admin';
                if ($this->input->post('comment') != '') {
                    $comment = $this->input->post('comment');
                }
                $note_id = '1';
            } elseif ($wallet == 'join_money') {
                $win_money = $result['wallet_balance'];
                $join_money = $result['join_money'] - $amount;
                $comment = 'Withdraw from Join Wallet By Admin';
                if ($this->input->post('comment') != '') {
                    $comment = $this->input->post('comment');
                }
                $note_id = '8';
            }
            $accountstm_data = array(
                'member_id' => $member_id,
                'pubg_id' => $result['pubg_id'],
                'deposit' => 0,
                'withdraw' => $amount,
                'join_money' => $join_money,
                'win_money' => $win_money,
                'note' => $comment,
                'note_id' => $note_id,
                'entry_from' => '3',
                'ip_detail' => $ip,
                'browser' => $browser,
                'accountstatement_dateCreated' => date('Y-m-d H:i:s')
            );
            $this->db->insert('accountstatement', $accountstm_data);
            $member_data = array(
                'join_money' => $join_money,
                'wallet_balance' => $win_money,
            );
            $this->db->where('member_id', $member_id);
            $this->db->update('member', $member_data);
        } elseif ($this->input->post('plus_minus') == '+') {
            $this->db->select('*');
            $this->db->where("member_id", $member_id);
            $query2 = $this->db->get('member');
            $result = $query2->row_array();
            if ($wallet == 'wallet_balance') {
                $win_money = $result['wallet_balance'] + $amount;
                $join_money = $result['join_money'];
                $comment = 'Add Money to Win Wallet By Admin';
                if ($this->input->post('comment') != '') {
                    $comment = $this->input->post('comment');
                }
                $note_id = '7';
            } elseif ($wallet == 'join_money') {
                $win_money = $result['wallet_balance'];
                $join_money = $result['join_money'] + $amount;
                $comment = 'Add Money to Join Wallet By Admin';
                if ($this->input->post('comment') != '') {
                    $comment = $this->input->post('comment');
                }
                $note_id = '0';
            }
            $accountstm_data = array(
                'member_id' => $member_id,
                'pubg_id' => $result['pubg_id'],
                'deposit' => $amount,
                'withdraw' => 0,
                'join_money' => $join_money,
                'win_money' => $win_money,
                'note' => $comment,
                'note_id' => $note_id,
                'entry_from' => '3',
                'ip_detail' => $ip,
                'browser' => $browser,
                'accountstatement_dateCreated' => date('Y-m-d H:i:s')
            );
            $this->db->insert('accountstatement', $accountstm_data);

            $member_data = array(
                'join_money' => $join_money,
                'wallet_balance' => $win_money,
            );
            $this->db->where('member_id', $member_id);
            $this->db->update('member', $member_data);
        }
        return true;
    }

    public function getmemberById($member_id) {
        $this->db->select('m.*,m2.user_name as referral_no');
        $this->db->where('m.member_id', $member_id);
        $this->db->join('member as m2', 'm2.member_id = m.referral_id', 'LEFT');
        $query = $this->db->get($this->table . ' as m');
        return $query->row_array();
    }

    public function get_tot_match_play($member_id) {
        $this->db->select('count(match_join_member_id) as total_match');
        $this->db->where('member_id', $member_id);
        $query = $this->db->get('match_join_member');
        return $query->row_array();
    }

    public function get_tot_kill($member_id) {
        $this->db->select('sum(killed) as total_kill');
        $this->db->where('member_id', $member_id);
        $query = $this->db->get('match_join_member');
        return $query->row_array();
    }

    public function get_tot_win($member_id) {
        $this->db->select('sum(total_win) as total_win');
        $this->db->where('member_id', $member_id);
        $query = $this->db->get('match_join_member');
        return $query->row_array();
    }

    public function get_tot_balance($member_id) {
        $this->db->select('wallet_balance');
        $this->db->where('member_id', $member_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus() {
        $this->db->set('member_status', $this->input->post('publish'));
        $this->db->where('member_id', $this->input->post('memberid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        
        $result = $this->getmemberById($this->input->post('memberid'));

        $thumb_sizes = $this->profile_image_size_array;
        
        if (file_exists($this->profile_image . $result['profile_image'])) {
            @unlink($this->profile_image . $result['profile_image']);
        }
            
        foreach ($thumb_sizes as $width => $height) {
            if (file_exists($this->profile_image . "thumb/" . $width . "x" . $height . "_" . $result['profile_image'])) {
                @unlink($this->profile_image . "thumb/" . $width . "x" . $height . "_" . $result['profile_image']);
            }
        }

        $this->db->where('member_id', $this->input->post('memberid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        $thumb_sizes = $this->profile_image_size_array;
        foreach($this->input->post('ids') as $key => $member_id){
            $result = $this->getmemberById($member_id);
            
            if (file_exists($this->profile_image . $result['profile_image'])) {
                @unlink($this->profile_image . $result['profile_image']);
            }
                
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->profile_image . "thumb/" . $width . "x" . $height . "_" . $result['profile_image'])) {
                    @unlink($this->profile_image . "thumb/" . $width . "x" . $height . "_" . $result['profile_image']);
                }
            }

            $this->db->where('member_id', $member_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $member_id){
            $member_data = $this->getmemberById($member_id);

            if($member_data['member_status'] == '0')
                $member_status = '1';
            else
                $member_status = '0';

            $this->db->set('member_status', $member_status);
            $this->db->where('member_id', $member_id);
            $this->db->update($this->table);            
        }
        return true;        
    }

}
