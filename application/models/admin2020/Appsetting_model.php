<?php

class Appsetting_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('image_lib');
        $this->table = 'app_upload';
        $this->column_headers = array(
            'Map Name' => '',
            'Date' => '',
        );
    }

    public function get_list_count_appupload() {

        $this->db->select('*');
        $this->db->order_by("app_upload_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function get_list_count_announcement() {

        $this->db->select('*');
        $this->db->order_by("announcement_id", "Desc");
        $query = $this->db->get('announcement');
        return $query->num_rows();
    }

    public function insert() {
        $image = $_FILES['app_upload']['name'];
        $config['file_name'] = $image;
        $config['upload_path'] = $this->apk;
        $config['allowed_types'] = '*';
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('app_upload')) {
            $data['error'] = array('error' => $this->upload->display_errors());
        } else {
            $data['upload_data'] = $this->upload->data();
        }
        $force_logged_out = 'No';
        if ($this->input->post('force_logged_out'))
            $force_logged_out = $this->input->post('force_logged_out');
        $data = array(
            'app_upload' => $image,
            'app_version' => $this->input->post('app_version'),
            'force_update' => $this->input->post('force_update'),
            'force_logged_out' => $force_logged_out,
            'app_description' => $this->input->post('app_description'),
            'date_created' => date('Y-m-d H:i:s')
        );
        if ($this->db->insert($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $this->db->where('match_map_id', $this->input->post('macthmapid'));
        if ($query = $this->db->delete('match_map')) {
            return true;
        } else {
            return false;
        }
    }

    public function announcement_insert() {
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

    public function delete_announcement() {
        $this->db->where('announcement_id', $this->input->post('announcementid'));
        if ($query = $this->db->delete('announcement')) {
            return true;
        } else {
            return false;
        }
    }

}
