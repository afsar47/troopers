<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->load->model($this->path_to_view_admin . 'Dashboard_model', 'dashboard');
    }

    public function index() {
        $data['breadcrumb_title'] = $this->lang->line('text_dashboard');
        $data['title'] = $this->lang->line('text_dashboard');
        $data['tot_member'] = $this->dashboard->get_tot_member();
        $data['tot_match'] = $this->dashboard->get_tot_match();
        $data['tot_payment'] = $this->dashboard->get_total_received_payment();
        $data['tot_withdraw'] = $this->dashboard->get_total_withdraw();
        $this->load->view($this->path_to_view_admin . 'dashboard', $data);
    }

}
