<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class License extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
    }

    function index() {
        $data['license'] = true;
        $data['title'] = $this->lang->line('text_purchasecode');
        if ($this->input->post('license_submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_add_purchase_code'));
                redirect($this->path_to_view_admin . 'license/');
            }
            $data['purchase_code'] = $this->input->post('purchase_code');
            $this->form_validation->set_rules('purchase_code', 'lang:text_purchasecode', 'required', array('required' => $this->lang->line('err_purchase_code_req')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'license_manage', $data);
            } else {
                $this->system->add_purchase_code();
                redirect($this->path_to_view_admin . 'license');
            }
        }
        $this->load->view($this->path_to_view_admin . 'license_manage', $data);
    }

    function remove_license() {
        if ($this->system->demo_user == 1) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_deactivate_purchase_code'));
            redirect($this->path_to_view_admin . 'license/');
        }
        $this->system->remove_purchase_code();
        redirect($this->path_to_view_admin . 'license');
    }

}
