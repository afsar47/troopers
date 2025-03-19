<?php

class Aboutus extends CI_Controller {

    function __construct() {
        parent::__construct();

        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        if ($this->member->front_logged_in !== true) {
            redirect('login');
        }
    }

    function index() {
        $data['about-us'] = true;
        $data['title'] = $this->lang->line('text_about_us');
        $data['breadcrumb_title'] = $this->lang->line('text_about_us');
        $data['about'] = $this->getAboutUs();
        $this->load->view($this->path_to_view_default . 'aboutus', $data);
    }

    public function getAboutUs() {
        $this->db->select('page_content as aboutus');
        $this->db->where('page_slug', 'about-us');
        $qry = $this->db->get('page');
        return $qry->row_array();
    }

}

?>
