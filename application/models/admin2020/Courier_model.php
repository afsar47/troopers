<?php

class Courier_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'courier';
        $this->img_size_array = array(100 => 100, 1000 => 500, 253 => 90);
        $this->logo_size_array = array(100 => 100, 253 => 90);
//        $this->column_headers = array(
//            'Courier Name' => '',
//            'Image' => '',
//        );
    }

    public function get_list_count_courier() {
        $this->db->select('*');
        $this->db->order_by("courier_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function courier_data() {
        $this->db->select('*');
        $this->db->order_by("courier_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function insert() {
        $data = array(
            'courier_name' => $this->input->post('courier_name'),
            'courier_link' => $this->input->post('courier_link'),
            'date_created' => date('Y-m-d H:i:s')
        );
        if ($this->db->insert('courier', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $data = array(
            'courier_name' => $this->input->post('courier_name'),
            'courier_link' => $this->input->post('courier_link'),
        );
        $this->db->where('courier_id', $this->input->post('courier_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getcourierById($courier_id) {
        $this->db->select('*');
        $this->db->where('courier_id', $courier_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus() {
        $this->db->set('status', $this->input->post('publish'));
        $this->db->where('courier_id', $this->input->post('courierid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $this->db->where('courier_id', $this->input->post('courierid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        foreach($this->input->post('ids') as $key => $courier_id){
            $this->db->where('courier_id', $courier_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $courier_id){
            $courier_data = $this->getcourierById($courier_id);

            if($courier_data['status'] == '0')
                $status = '1';
            else
                $status = '0';

            $this->db->set('status', $status);
            $this->db->where('courier_id', $courier_id);
            $this->db->update($this->table);
        }
        return true;        
    }

}
