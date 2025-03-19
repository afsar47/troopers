<?php

class Country_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'country';
        $this->table_country_map = 'country_map';
        $this->img_size_array = array(100 => 100, 1000 => 500, 253 => 90);
        $this->logo_size_array = array(100 => 100, 253 => 90);
        $this->column_headers = array(
            'Country Name' => '',
            'Image' => '',
        );
    }

    public function get_list_count_country() {
        $this->db->select('*');
        $this->db->order_by("country_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function country_data() {
        $this->db->select('*');
        $this->db->order_by("country_id", "ASC");
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function insert() {
        $data = array(
            'country_name' => $this->input->post('country_name'),
            'p_code' => $this->input->post('p_code'),
            'date_created' => date('Y-m-d H:i:s')
        );
        if ($this->db->insert('country', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $data = array(
            'country_name' => $this->input->post('country_name'),
            'p_code' => $this->input->post('p_code'),
        );
        $this->db->where('country_id', $this->input->post('country_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getcountryById($country_id) {
        $this->db->select('*');
        $this->db->where('country_id', $country_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus() {
        $this->db->set('country_status', $this->input->post('publish'));
        $this->db->where('country_id', $this->input->post('countryid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {        
        $this->db->where('country_id', $this->input->post('countryid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        foreach($this->input->post('ids') as $key => $country_id){
            $this->db->where('country_id', $country_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $country_id){
            $country_data = $this->getcountryById($country_id);

            if($country_data['country_status'] == '0')
                $country_status = '1';
            else
                $country_status = '0';

            $this->db->set('country_status', $country_status);
            $this->db->where('country_id', $country_id);
            $this->db->update($this->table);
        }
        return true;        
    }

}
