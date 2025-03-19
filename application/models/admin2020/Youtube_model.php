<?php

class Youtube_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'youtube_link';
        $this->column_headers = array(
            'App Link Title' => '',
            'App Link' => '',
            'Date' => '',
        );
    }

    public function get_list_count_youtube()
    {
        $this->db->select('*');
        $this->db->order_by("youtube_link_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function getYoutubeLinkById($youtube_link_id)
    {
        $this->db->select('*');
        $this->db->where('youtube_link_id', $youtube_link_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function insert()
    {
        $data = array(
            'youtube_link' => $this->input->post('youtube_link'),
            'youtube_link_title' => $this->input->post('youtube_link_title'),
            'date_created' => date('Y-m-d H:i:s')
        );
        if ($result = $this->db->insert('youtube_link', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update()
    {
        $data = array(
            'youtube_link' => $this->input->post('youtube_link'),
            'youtube_link_title' => $this->input->post('youtube_link_title'),
        );
        $this->db->where('youtube_link_id', $this->input->post('youtube_link_id'));
        if ($this->db->update('youtube_link', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('youtube_link_id', $this->input->post('youtubelinkid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }

    }

    public function multiDelete() {   
        foreach($this->input->post('ids') as $key => $youtube_link_id){
            $this->db->where('youtube_link_id', $youtube_link_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }    
    
}
