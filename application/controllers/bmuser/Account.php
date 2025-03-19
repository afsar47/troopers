<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        if ($this->member->front_logged_in !== true) {
            redirect('login');
        }
        $this->load->model($this->path_to_default . 'Account_model', 'account');
    }

    public function index() {
        $data['breadcrumb_title'] = $this->lang->line('text_dashboard');
        $data['title'] = $this->lang->line('text_dashboard');
        $data['tot_play'] = $this->account->get_play();
        $data['tot_balance'] = $this->account->get_tot_balance();
        $data['profile_detail'] = $this->getProfile();
        $this->load->view($this->path_to_view_default . 'account', $data);
    }

    function getProfile() {
        $this->db->select('*');
        $this->db->where('member_id', $this->member->front_member_id);
        $qry = $this->db->get('member');
        return $qry->row_array();
    }

    public function wallet() {
        $data = $this->account->getMemberDetail($this->member->front_member_id);
        echo json_encode($data);
    }

}
