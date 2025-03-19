<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

    public function getMatchdetail($gameid = '') {
        $mathes = array();
        $this->db->select('*');
        if ($gameid != '') {
            $this->db->where('game_id', $gameid);
        }
        $this->db->join("image as i", "i.image_id = matches.image_id", "left");
        $this->db->where("match_status", '1');
        $this->db->order_by('m_id', 'DESC');
        $this->db->limit('3');
        $query = $this->db->get('matches');
        $mathes = $query->result();
        if ($query->num_rows() > 0) {
            if ($this->session->userdata('front_logged_in') == true) {
                $match_id = array();
                foreach ($mathes as $row) {
                    $match_id[] = $row->m_id;
                }
                $this->db->select("*");
                $this->db->where_in("match_id", $match_id);
                $this->db->where("member_id", $this->member->front_member_id);
                $query1 = $this->db->get('match_join_member');
                $match_join = $query1->result();
                $i = 0;
                foreach ($mathes as $row) {
                    $mathes[$i]->join_status = false;
                    foreach ($match_join as $match_join_id) {
                        if ($row->m_id == $match_join_id->match_id) {
                            $mathes[$i]->join_status = true;
                        }
                    }
                    $i++;
                }
            }
        }

        return $mathes;
    }

    public function getPlayerlistbyGame($gameid) {
        $this->db->select('SUM(`total_win`) as `t_win`,`member`.`user_name`');
        $this->db->join('member', '`match_join_member`.`member_id` = `member`.`member_id`');
        $this->db->join('matches', '`matches`.`m_id` = `match_join_member`.`match_id`', 'left');
        $this->db->join('game', '`game`.`game_id` = `matches`.`game_id`', 'left');
        $this->db->where('game.game_id', $gameid);
        $this->db->group_by('`match_join_member`.`member_id`');
        $this->db->order_by('t_win', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get('match_join_member');
        return $query->result();
    }

    public function getFeaturesTab() {
        $this->db->select('*');
        $this->db->where('f_tab_status', "1");
        $this->db->order_by('f_tab_order', 'ASC');
        $query = $this->db->get('features_tab');
        return $query->result();
    }

    public function getFeaturesTabContent($features_tab_id) {
        $this->db->select('*');
        $this->db->where('features_tab_id', $features_tab_id);
        $this->db->where('content_status', "1");
        $this->db->order_by('date_created', 'ASC');
        $query = $this->db->get('features_tab_content');
        return $query->result();
    }

    public function GetRefrralNo($promo_code) {
        $this->db->select('*');
        $this->db->where('user_name', $promo_code);
        $query = $this->db->get('member');
        return $query->row_array()['member_id'];
    }

    function generateUsername($user_name) {
        $chars = "0123456789";
        $r_str = '';
        for ($i = 0; $i < 6; $i++) {
            $r_str .= substr($chars, rand(0, strlen($chars)), 1);
        }
        $new_user_name = $user_name . $r_str;

        $this->db->where('user_name', $new_user_name);
        $query = $this->db->get('member');

        if ($query->num_rows() > 0) {
            $this->generateUsername($user_name);
        } else {
            return $new_user_name;
        }
    }

    public function register() {
        if ($this->system->firebase_otp == 'yes') {
            $referral_id = '';
            $api_token = uniqid() . base64_encode(random_string('alnum', 40));
            if ($this->session->userdata('referral_code') != '')
                $referral_id = $this->GetRefrralNo($this->session->userdata('referral_code'));

            $member_data = array(
                'user_name' => $this->session->userdata('user_name'),
                'email_id' => $this->session->userdata('email_id'),
                'mobile_no' => $this->session->userdata('mobile_no'),
                'password' => md5($this->session->userdata('password')),
                'country_code' => $this->session->userdata('country_code'),
                'referral_id' => $referral_id,
                'api_token' => $api_token,
                'entry_from' => '2',
                'created_date' => date('Y-m-d H:i:s')
            );

            $this->db->insert('member', $member_data);
            $member_id = $this->db->insert_id();

            if ($this->session->userdata('referral_code') != '') {
                if ($this->system->active_referral == '1') {
                    $this->load->library('user_agent');
                    $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                    $ip = $this->input->ip_address();
                    $wallet_balance = $this->system->referral;
                    $data = array(
                        'join_money' => $wallet_balance);
                    $this->db->where('member_id',$member_id);
                    $this->db->update('member', $data);
                    $referral_data = array(
                        'member_id' => $member_id,
                        'from_mem_id' => $referral_id,
                        'referral_amount' => $this->system->referral,
                        'referral_status' => '1',
                        'entry_from' => '2',
                        'referral_dateCreated' => date('Y-m-d H:i:s')
                   );
                    $this->db->insert('referral', $referral_data);
                    $acc_data = array(
                        'member_id' => $member_id,
                        'from_mem_id' => $referral_id,
                        'deposit' => $this->system->referral,
                        'withdraw' => 0,
                        'join_money' => $wallet_balance,
                        'win_money' => 0,
                        'note' => 'Register Referral',
                        'note_id' => '3',
                        'entry_from' => '2',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('accountstatement', $acc_data);
                }
            }
            $this->session->set_userdata('user_name', '');
            $this->session->set_userdata('email_id', '');
            $this->session->set_userdata('mobile_no', '');
            $this->session->set_userdata('password', '');
            $this->session->set_userdata('country_code', '');
            return true;
        } else {
            $referral_id = '';
            $api_token = uniqid() . base64_encode(random_string('alnum', 40));
            if ($this->input->post('referral_code') != '')
                $referral_id = $this->GetRefrralNo($this->input->post('referral_code'));

            $member_data = array(
                'user_name' => $this->input->post('user_name'),
                'email_id' => $this->input->post('email_id'),
                'mobile_no' => $this->input->post('mobile_no'),
                'password' => md5($this->input->post('password')),
                'country_code' => $this->input->post('country_code'),
                'referral_id' => $referral_id,
                'api_token' => $api_token,
                'entry_from' => '2',
                'created_date' => date('Y-m-d H:i:s')
            );

            $this->db->insert('member', $member_data);
            $member_id = $this->db->insert_id();

            if ($this->input->post('referral_code') != '') {
                if ($this->system->active_referral == '1') {
                    $this->load->library('user_agent');
                    $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                    $ip = $this->input->ip_address();
                    $wallet_balance = $this->system->referral;
                    $data = array(
                        'join_money' => $wallet_balance);
                    $this->db->where('member_id',$member_id);
                    $this->db->update('member', $data);
                    $referral_data = array(
                        'member_id' => $member_id,
                        'from_mem_id' => $referral_id,
                        'referral_amount' => $this->system->referral,
                        'referral_status' => '1',
                        'entry_from' => '2',
                        'referral_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('referral', $referral_data);
                    $acc_data = array(
                        'member_id' => $member_id,
                        'from_mem_id' => $referral_id,
                        'deposit' => $this->system->referral,
                        'withdraw' => 0,
                        'join_money' => $wallet_balance,
                        'win_money' => 0,
                        'note' => 'Register Referral',
                        'note_id' => '3',
                        'entry_from' => '2',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('accountstatement', $acc_data);
                }
            }
            return true;
        }
    }

    public function register_via() {
        if ($this->system->firebase_otp == 'yes') {
            $referral_id = '';
            $api_token = uniqid() . base64_encode(random_string('alnum', 40));
            $user_name = $this->generateUsername(str_replace(' ', '', explode(' ', $this->session->userdata('user_name'))[0]));
            $api_token = uniqid() . base64_encode(random_string('alnum', 40));
            if ($this->session->userdata('login_via') == 'Google')
                $login_via = '2';
            else if ($this->session->userdata('login_via') == 'FB')
                $login_via = '1';

            $email_id = $this->session->userdata('email_id');
            if ($this->session->userdata('email_id') == '')
                $email_id = '';
            if ($this->session->userdata('referral_code') != '')
                $referral_id = $this->GetRefrralNo($this->session->userdata('referral_code'));

            $member_data = array(
                'user_name' => $user_name,
                'email_id' => $email_id,
                'mobile_no' => $this->session->userdata('mobile_no'),
                'first_name' => explode(' ', $this->session->userdata('user_name'))[0],
                'last_name' => explode(' ', $this->session->userdata('user_name'))[1],
                'password' => md5($this->session->userdata('g_id')),
                'country_code' => $this->session->userdata('country_code'),
                'fb_id' => $this->session->userdata('g_id'),
                'referral_id' => $referral_id,
                'login_via' => $login_via,
                'api_token' => $api_token,
                'entry_from' => '2',
                'created_date' => date('Y-m-d H:i:s')
            );
            $this->db->insert('member', $member_data);
            $member_id = $this->db->insert_id();

            if ($this->session->userdata('referral_code') != '') {
                if ($this->system->active_referral == '1') {
                    $this->load->library('user_agent');
                    $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                    $ip = $this->input->ip_address();
                    $wallet_balance = $this->system->referral;
                    $data = array(
                        'join_money' => $wallet_balance);
                    $this->db->where('member_id',$member_id);
                    $this->db->update('member', $data);
                    $referral_data = array(
                        'member_id' => $member_id,
                        'from_mem_id' => $referral_id,
                        'referral_amount' => $this->system->referral,
                        'referral_status' => '1',
                        'entry_from' => '2',
                        'referral_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('referral', $referral_data);
                    $acc_data = array(
                        'member_id' => $member_id,
                        'from_mem_id' => $referral_id,
                        'deposit' => $this->system->referral,
                        'withdraw' => 0,
                        'join_money' => $wallet_balance,
                        'win_money' => 0,
                        'note' => 'Register Referral',
                        'note_id' => '3',
                        'entry_from' => '2',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('accountstatement', $acc_data);
                }
            }
            $this->session->set_userdata('user_name', '');
            $this->session->set_userdata('email_id', '');
            $this->session->set_userdata('mobile_no', '');
            $this->session->set_userdata('password', '');
            $this->session->set_userdata('country_code', '');
            return true;
        } else {
            $referral_id = '';
            $api_token = uniqid() . base64_encode(random_string('alnum', 40));
            $referral_id = '';
            $api_token = uniqid() . base64_encode(random_string('alnum', 40));
            $user_name = $this->generateUsername(str_replace(' ', '', explode(' ', $this->input->post('user_name'))[0]));
            $api_token = uniqid() . base64_encode(random_string('alnum', 40));
            if ($this->input->post('login_via') == 'Google')
                $login_via = '2';
            else if ($this->input->post('login_via') == 'FB')
                $login_via = '1';

            $email_id = $this->input->post('email_id');
            if ($this->input->post('email_id') == '')
                $email_id = '';
            if ($this->input->post('referral_code') != '')
                $referral_id = $this->GetRefrralNo($this->input->post('referral_code'));
            $member_data = array(
                'user_name' => $user_name,
                'email_id' => $email_id,
                'mobile_no' => $this->input->post('mobile_no'),
                'first_name' => explode(' ', $this->input->post('user_name'))[0],
                'last_name' => explode(' ', $this->input->post('user_name'))[1],
                'password' => md5($this->input->post('g_id')),
                'country_code' => $this->input->post('country_code'),
                'fb_id' => $this->input->post('g_id'),
                'referral_id' => $referral_id,
                'login_via' => $login_via,
                'api_token' => $api_token,
                'entry_from' => '2',
                'created_date' => date('Y-m-d H:i:s')
            );

            $this->db->insert('member', $member_data);
            $member_id = $this->db->insert_id();

            if ($this->input->post('referral_code') != '') {
                if ($this->system->active_referral == '1') {
                    $this->load->library('user_agent');
                    $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                    $ip = $this->input->ip_address();
                    $wallet_balance = $this->system->referral;
                    $data = array(
                        'join_money' => $wallet_balance);
                    $this->db->where('member_id',$member_id);
                    $this->db->update('member', $data);
                    $referral_data = array(
                        'member_id' => $member_id,
                        'from_mem_id' => $referral_id,
                        'referral_amount' => $this->system->referral,
                        'referral_status' => '1',
                        'entry_from' => '2',
                        'referral_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('referral', $referral_data);
                    $acc_data = array(
                        'member_id' => $member_id,
                        'from_mem_id' => $referral_id,
                        'deposit' => $this->system->referral,
                        'withdraw' => 0,
                        'join_money' => $wallet_balance,
                        'win_money' => 0,
                        'note' => 'Register Referral',
                        'note_id' => '3',
                        'entry_from' => '2',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('accountstatement', $acc_data);
                }
            }
            return true;
        }
    }

}

?>