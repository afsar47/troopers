<?php

class Announcement_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'announcement';
        $this->table_announcement_map = 'announcement_map';
        $this->img_size_array = array(100 => 100, 1000 => 500, 253 => 90);
        $this->logo_size_array = array(100 => 100, 253 => 90);
//        $this->column_headers = array(
//            $this->lang->line('text_announcement_name') => '',
//            $this->lang->line('text_date') => '',
//        );
    }

    public function get_list_count_announcement() {

        $this->db->select('*');
        $this->db->order_by("announcement_id", "Desc");
        $query = $this->db->get('announcement');
        return $query->num_rows();
    }

    public function announcement_data() {
        $this->db->select('*');
        $this->db->order_by("announcement_id", "ASC");
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function insert() {
        $data = array(
            'announcement_desc' => $this->input->post('announcement_desc'),
            'date_created' => date('Y-m-d H:i:s')
        );
        if ($this->db->insert('announcement', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $data = array(
            'announcement_desc' => $this->input->post('announcement_desc'),
        );
        $this->db->where('announcement_id', $this->input->post('announcement_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getannouncementById($announcement_id) {
        $this->db->select('*');
        $this->db->where('announcement_id', $announcement_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus() {
        $this->db->set('announcement_status', $this->input->post('publish'));
        $this->db->where('announcement_id', $this->input->post('announcementid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $this->db->where('announcement_id', $this->input->post('announcementid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        foreach($this->input->post('ids') as $key => $announcement_id){
            $this->db->where('announcement_id', $announcement_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }  

}
