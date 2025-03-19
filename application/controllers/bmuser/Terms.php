<?php

class Terms extends CI_Controller {

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
        $data['terms_condition'] = true;
        $data['title'] = $this->lang->line('text_terms_conditions');
        $data['breadcrumb_title'] = $this->lang->line('text_terms_conditions');
        $data['terms'] = $this->getTerms();
        $this->load->view($this->path_to_view_default . 'terms', $data);
    }

    public function getTerms() {
        $this->db->select('page_content as terms_conditions');
        $this->db->where('page_slug', 'terms_conditions');
        $qry = $this->db->get('page');
        return $qry->row_array();
    }

}

?>
