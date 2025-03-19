<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Wallet_model extends CI_Model {

    public function get_list_count_wallet() {
        $this->db->select("*");
        $this->db->where("member_id", $this->member->front_member_id);
        $query = $this->db->get('accountstatement');
        return $query->num_rows();
    }

    public function getWithdrawMethod() {
        $this->db->select('*');
        $this->db->where("withdraw_method_status", '1');
        $query = $this->db->get('withdraw_method');
        return $query->result();
    }

    public function getAddMoneyMethod($id) {
        $this->db->select('pg_detail.*,c.currency_name,c.currency_code,c.currency_symbol');
        $this->db->where("id", $id);
        $this->db->join("currency as c", 'c.currency_id = pg_detail.currency', 'LEFT');
        $query = $this->db->get('pg_detail');
        return $query->row_array();
    }

    public function getAddMoneyMethodByName($payment_name) {
        $this->db->select('pg_detail.*,c.currency_name,c.currency_code,c.currency_symbol');
        $this->db->where("payment_name", $payment_name);
        $this->db->join("currency as c", 'c.currency_id = pg_detail.currency', 'LEFT');
        $query = $this->db->get('pg_detail');
        return $query->row_array();
    }

    public function getPaymentMethod() {
        $this->db->select('*');
        $this->db->where("status", '1');
        $this->db->where("payment_name != ", 'Google Pay');
        $query = $this->db->get('pg_detail');
        return $query->result();
    }

    public function getWalletHistoryData() {
        $this->db->select('*');
        $this->db->where("member_id", $this->member->front_member_id);
        $this->db->order_by('account_statement_id', 'DESC');
        $query = $this->db->get('accountstatement');
        return $query->result();
    }

}
