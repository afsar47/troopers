<?php

class Support extends CI_Controller {

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
        $data['custromer-support'] = true;
        $data['title'] = $this->lang->line('text_customer_supports');
        $data['breadcrumb_title'] = $this->lang->line('text_customer_supports');
        $this->load->view($this->path_to_view_default . 'support', $data);
    }  
}

?>
