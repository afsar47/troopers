<?php

class Refer_earn extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        if ($this->member->front_logged_in !== true) {
            redirect('login');
        }
        $this->con = $this->functions->mysql_connection();
    }

    function index() {
        $data['refer_earn'] = true;
        $data['title'] = $this->lang->line('text_refer_earn');
        $data['breadcrumb_title'] = $this->lang->line('text_earn');
        $this->db->select('*');
        $this->db->where('status','1');
        $this->db->order_by('date_created','DESC');
        $qr = $this->db->get('banner');
        $data['banner_data'] = $qr->result();
        $this->load->view($this->path_to_view_default . 'refer_earn', $data);
    }

    function detail() {
        $data['refer_earn'] = true;
        $data['title'] = $this->lang->line('text_refer_earn');
        $data['breadcrumb_title'] = "";
        $this->load->view($this->path_to_view_default . 'refer_earn_detail', $data);
    }

}

?>
