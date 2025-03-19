<?php

class Changepassword extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('changepassword')) {
            redirect($this->path_to_view_admin . 'login');
        }
    }

    function index() {
        $data['change_password'] = true;
        $data['title'] = $this->lang->line('text_changepassword');
        if ($this->input->post('password_update') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_password'));
                redirect($this->path_to_view_admin . 'profilesetting/');
            }
            $data['old_password'] = $this->input->post('old_password');
            $data['new_password'] = $this->input->post('new_password');
            $data['c_passowrd'] = $this->input->post('c_passowrd');
            
            $this->form_validation->set_rules('old_password', 'lang:text_old_password', 'required|callback_checkOldPass', array('required' => $this->lang->line('err_old_password_req')));
            $this->form_validation->set_rules('new_password', 'lang:text_new_password', 'required', array('required' => $this->lang->line('err_new_password_req')));
            $this->form_validation->set_rules('c_passowrd', 'lang:text_confirm_password', 'required|callback_checkNewpass', array('required' => $this->lang->line('err_c_passowrd_req')));
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'change_password', $data);
            } else {
                if ($result = $this->update()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_password'));
                    redirect($this->path_to_view_admin . 'changepassword');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'change_password', $data);
        }
    }

    function update() {
        $array = array(
            'password' => md5($this->input->post('new_password')),
            'default_login' => '1'
        );
        $this->db->where('id', $this->session->userdata('id'));
        if ($this->db->update('admin', $array)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkOldPass() {
        $this->db->select('*');
        $this->db->where('id', $this->session->userdata('id'));
        $query = $this->db->get('admin');
        $data = $query->row_array();
        if ($data['password'] != md5($this->input->post('old_password'))) {
            $this->form_validation->set_message('checkOldPass', $this->lang->line('err_old_password_valid'));
            return false;
        } else {
            return true;
        }
    }

    public function checkNewpass() {
        if ($this->input->post('new_password') != $this->input->post('c_passowrd')) {
            $this->form_validation->set_message('checkNewpass', $this->lang->line('err_c_passowrd_equal'));
            return false;
        } else {
            return true;
        }
    }

}

?>
