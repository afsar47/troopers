<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tab_content_model extends CI_Model
{

    public function __construct()
    {
        //parent::CI_Model();
        parent::__construct();

        $this->table = 'features_tab_content';
        $this->column_headers = array(
            'Feature Tab Name' => '',
            'Content Title' => '',
            'Content Text' => '',
            'Content Icon' => '',
        );
    }

    public function getFeatureTab()
    {
        $this->db->select('*');
        $this->db->where("f_tab_status", "1");
        $query = $this->db->get('features_tab');
        return $query->result();
    }

    public function get_list_count_tab_content()
    {

        $this->db->select('*');
        $this->db->join("features_tab as ft", "ft.f_id = ftc.features_tab_id");
        $this->db->order_by("ftc.ftc_id", "Desc");
        $query = $this->db->get($this->table." as ftc");
        return $query->num_rows();
    }

    public function insert()
    {

        $data = array(
            'features_tab_id' => $this->input->post('features_tab_id'),
            'content_title' => $this->input->post('content_title'),
            'content_text' => $this->input->post('content_text'),
            'content_icon' => $this->input->post('content_icon'),
            'content_status' => '1',
            'date_created' => date('Y-m-d H:i:s')
        );
        if ($this->db->insert($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update()
    {
        $ftc_id = $this->input->post('ftc_id');
        $data = array(
            'features_tab_id' => $this->input->post('features_tab_id'),
            'content_title' => $this->input->post('content_title'),
            'content_text' => $this->input->post('content_text'),
            'content_icon' => $this->input->post('content_icon'),
        );
        $this->db->where('ftc_id', $ftc_id);
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getTabContentById($tab_content_id)
    {
        $this->db->select('*');
        $this->db->where('ftc_id', $tab_content_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function getTabContent()
    {
        $this->db->select('*');
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function changePublishStatus()
    {
        $this->db->set('content_status', $this->input->post('publish'));
        $this->db->where('ftc_id', $this->input->post('tab_contentid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('ftc_id', $this->input->post('tab_contentid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }

    }

    public function multiDelete() {   
        foreach($this->input->post('ids') as $key => $ftc_id){
            $this->db->where('ftc_id', $ftc_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $ftc_id){
            $data = $this->getTabContentById($ftc_id);

            if($data['content_status'] == '0')
                $content_status = '1';
            else
                $content_status = '0';

            $this->db->set('content_status', $content_status);
            $this->db->where('ftc_id', $ftc_id);
            $this->db->update($this->table);
        }
        return true;        
    }

}
