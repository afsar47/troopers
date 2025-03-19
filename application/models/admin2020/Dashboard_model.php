<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{

    public function get_tot_member()
    {
        $this->db->select('count(member_id) as total_member');
        $query = $this->db->get('member');
        return $query->row_array();
    }

    public function get_tot_match()
    {
        $this->db->select('count(m_id) as total_match');
        $query = $this->db->get('matches');
        return $query->row_array();
    }

    public function get_total_received_payment()
    {
        $this->db->select('sum(deposit_amount) as total_payment');
        $this->db->where('deposit_status', '1');
        $query = $this->db->get('deposit');
        return $query->row_array();
    }

    public function get_total_withdraw()
    {
        $array = array('1', '9');
        $this->db->select('sum(withdraw) as total_withdraw');
        $this->db->where_in('note_id', $array);
        $query = $this->db->get('accountstatement');
        return $query->row_array();
    }

}
