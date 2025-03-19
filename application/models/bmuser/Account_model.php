<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Account_model extends CI_Model {

    public function get_play() {

        $this->db->where("member_id", $this->member->front_member_id);
        $this->db->select("COUNT(match_join_member_id) as total_match,SUM(killed) as total_kill,SUM(total_win) as total_win");
        $query = $this->db->get('match_join_member');
        return $query->row_array();
    }

    public function get_tot_balance() {
        $this->db->select('join_money,wallet_balance');
        $this->db->where("member_id", $this->member->front_member_id);
        $query = $this->db->get('member');
        return $query->row_array();
    }

    public function getMemberDetail($member_id) {
        $this->db->select("*");
        $this->db->where("member_id", $member_id);
        $query = $this->db->get('member');
        return $query->row_array();
    }

    public function get_tot_withdraw() {
        $this->db->select("SUM(withdraw) as tot_withdraw");
        $this->db->where("member_id", $this->member->front_member_id);
        $this->db->where_in("note_id", array('1', '8'));
        $query = $this->db->get('accountstatement');
        return $query->row_array();
    }

    public function getTotalReferral() {
        $this->db->select("count(member_id) as total_ref");
        $this->db->where("referral_id", $this->member->front_member_id);
        $query = $this->db->get('member');
        return $query->row_array();
    }

    public function getTotalEarnings() {
        $this->db->select("sum(referral_amount) as total_earning");
        $this->db->where("member_id", $this->member->front_member_id);
        $this->db->where("referral_status", '0');
        $query = $this->db->get('referral');
        return $query->row_array();
    }

    public function getReferrals() {

        $this->db->select("user_name,member_status,member_package_upgraded,created_date");
        $this->db->where("referral_id", $this->member->front_member_id);
        $query = $this->db->get('member');
        return $query->result();
    }

}
