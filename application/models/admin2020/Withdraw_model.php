<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Withdraw_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'withdraw_method';
        $this->column_headers = array(
            'Withdraw Method' => '',
            'Withdraw Field' => '',
            'Status' => '',
            'Date' => '',
        );
    }

    public function get_list_count_withdraw_method() {
        $this->db->select('*');
        $this->db->order_by("withdraw_method_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function insert() {
        $data = array(
            'withdraw_method' => $this->input->post('withdraw_method'),
            'withdraw_method_field' => $this->input->post('withdraw_method_field'),
            'withdraw_method_currency' => $this->input->post('withdraw_method_currency'),
            'withdraw_method_currency_point' => $this->input->post('withdraw_method_currency_point'),
            'withdraw_method_status' => '1',
            'withdraw_method_dateCreated' => date('Y-m-d H:i:s')
        );
        if ($this->db->insert($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $withdraw_method_id = $this->input->post('withdraw_method_id');
        $data = array(
            'withdraw_method' => $this->input->post('withdraw_method'),
            'withdraw_method_field' => $this->input->post('withdraw_method_field'),
            'withdraw_method_currency' => $this->input->post('withdraw_method_currency'),
            'withdraw_method_currency_point' => $this->input->post('withdraw_method_currency_point'),
        );
        $this->db->where('withdraw_method_id', $withdraw_method_id);
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getWithdrawMethodById($withdraw_method_id) {
        $this->db->select('*');
        $this->db->where('withdraw_method_id', $withdraw_method_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function getWithdraw() {
        $this->db->select('*');
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function changePublishStatusMethod() {
        $this->db->set('withdraw_method_status', $this->input->post('publish'));
        $this->db->where('withdraw_method_id', $this->input->post('withdrawmethodid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $this->db->where('withdraw_method_id', $this->input->post('withdrawmethodid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        foreach($this->input->post('ids') as $key => $withdraw_method_id){
            $this->db->where('withdraw_method_id', $withdraw_method_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $withdraw_method_id){
            $data = $this->getWithdrawMethodById($withdraw_method_id);

            if($data['withdraw_method_status'] == '0')
                $withdraw_method_status = '1';
            else
                $withdraw_method_status = '0';

            $this->db->set('withdraw_method_status', $withdraw_method_status);
            $this->db->where('withdraw_method_id', $withdraw_method_id);
            $this->db->update($this->table);
        }
        return true;        
    }

}
