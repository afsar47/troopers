<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Currency_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'currency';
        $this->column_headers = array(
            'Title' => '',
            'Code' => '',
            'Status' => '',
            'Date' => '',
        );
    }

    public function get_list_count_currency()
    {
        $this->db->select('*');
        $this->db->order_by("currency_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function insert()
    {
        $data = array(
            'currency_name' => $this->input->post('currency_name'),
            'currency_code' => $this->input->post('currency_code'),
            'currency_symbol' => $this->input->post('currency_symbol'),
            'currency_decimal_place' => $this->input->post('currency_decimal_place'),
            'currency_status' => '1',
            'currency_dateCreated' => date('Y-m-d H:i:s')
        );
        if ($this->db->insert($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update()
    {
        $currency_id = $this->input->post('currency_id');
        $data = array(
            'currency_name' => $this->input->post('currency_name'),
            'currency_code' => $this->input->post('currency_code'),
            'currency_symbol' => $this->input->post('currency_symbol'),
            'currency_decimal_place' => $this->input->post('currency_decimal_place'),
        );
        $this->db->where('currency_id', $currency_id);
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getCurrencyById($currency_id)
    {
        $this->db->select('*');
        $this->db->where('currency_id', $currency_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function getCurrency()
    {
        $this->db->select('*');
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function changePublishStatus()
    {
        $this->db->set('currency_status', $this->input->post('publish'));
        $this->db->where('currency_id', $this->input->post('currencyid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }

    }

    public function delete()
    {        
        $this->db->where('currency_id', $this->input->post('currencyid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }

    }

    public function multiDelete() {   
        foreach($this->input->post('ids') as $key => $currency_id){
            $this->db->where('currency_id', $currency_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $currency_id){
            $data = $this->getCurrencyById($currency_id);

            if($data['currency_status'] == '0')
                $currency_status = '1';
            else
                $currency_status = '0';

            $this->db->set('currency_status', $currency_status);
            $this->db->where('currency_id', $currency_id);
            $this->db->update($this->table);
        }
        return true;        
    }

}
