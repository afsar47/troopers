<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pgdetail extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('pgdetail')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
    }

    function index() {
        $data['pgdetail'] = true;
        $data['title'] = $this->lang->line('text_payment');
        if ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'pgdetail/');
            } else {
                $this->db->set('status', $this->input->post('publish'));
                $this->db->where('id', $this->input->post('paymentid'));
                if ($query = $this->db->update('pg_detail')) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_payment'));
                    redirect($this->path_to_view_admin . 'pgdetail/');
                }
            }
        } else if ($this->input->post('submit_payment') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_payment'));
                redirect($this->path_to_view_admin . 'pgdetail/');
            }
            $data['payment'] = $this->input->post('payment');
            $this->form_validation->set_rules('payment', 'Payment', 'required');
            $settings_data = array('web_config_value' => $this->input->post('payment'));
            $this->db->where('web_config_name', 'payment');
            if ($query = $this->db->update('web_config', $settings_data)) {
                $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_payment'));
                redirect($this->path_to_view_admin . 'pgdetail/');
            }
        } elseif ($this->input->post('update_paytm') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_payment'));
                redirect($this->path_to_view_admin . 'pgdetail/');
            }
            $data['mid'] = $this->input->post('mid');
            $data['mkey'] = $this->input->post('mkey');
            $data['wname'] = $this->input->post('wname');
            $data['itype'] = $this->input->post('itype');
            $data['payment_status'] = $this->input->post('payment_status');
            $data['currency'] = $this->input->post('currency');
            $data['currency_point'] = $this->input->post('currency_point');

            $this->form_validation->set_rules('mid', 'lang:text_merchant_id', 'required', array('required' => $this->lang->line('err_mid_req')));
            $this->form_validation->set_rules('mkey', 'lang:text_merchant_key', 'required', array('required' => $this->lang->line('err_mkey_req')));
            $this->form_validation->set_rules('wname', 'lang:text_website', 'required', array('required' => $this->lang->line('err_wname_req')));
            $this->form_validation->set_rules('itype', 'lang:text_industry_type', 'required', array('required' => $this->lang->line('err_itype_req')));
            $this->form_validation->set_rules('payment_status', 'lang:text_mode', 'required', array('required' => $this->lang->line('err_payment_status_req')));
            $this->form_validation->set_rules('currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
            $this->form_validation->set_rules('currency_point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'pg_detail', $data);
            } else {
                if ($result = $this->update()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_payment'));
                    redirect($this->path_to_view_admin . 'pgdetail/');
                }
            }
        } elseif ($this->input->post('update_payu') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_payment'));
                redirect($this->path_to_view_admin . 'pgdetail/');
            }
            // $data['mid'] = $this->input->post('mid');
            $data['mkey'] = $this->input->post('mkey');
            $data['wname'] = $this->input->post('wname');            
            $data['payment_status'] = $this->input->post('payment_status');
            $data['currency'] = $this->input->post('currency');
            $data['currency_point'] = $this->input->post('currency_point');

            // $this->form_validation->set_rules('mid', 'lang:text_merchant_id', 'required', array('required' => $this->lang->line('err_mid_req')));
            $this->form_validation->set_rules('mkey', 'lang:text_merchant_key', 'required', array('required' => $this->lang->line('err_mkey_req')));
            $this->form_validation->set_rules('wname', 'lang:text_salt', 'required', array('required' => $this->lang->line('err_salt_req')));
            $this->form_validation->set_rules('payment_status', 'lang:text_mode', 'required', array('required' => $this->lang->line('err_payment_status_req')));
            $this->form_validation->set_rules('currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
            $this->form_validation->set_rules('currency_point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $data['pgdetail'] = $this->getPgDetail($this->input->post('id'));
                $this->load->view($this->path_to_view_admin . 'pg_detail', $data);
            } else {
                if ($result = $this->update()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_payment'));
                    redirect($this->path_to_view_admin . 'pgdetail/');
                }
            }
        } elseif ($this->input->post('update_paypal') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_payment'));
                redirect($this->path_to_view_admin . 'pgdetail/');
            }
            $data['mid'] = $this->input->post('mid');
            $data['payment_status'] = $this->input->post('payment_status');
            $data['name'] = $this->input->post('name');
            $data['currency'] = $this->input->post('currency');
            $data['currency_point'] = $this->input->post('currency_point');

            $this->form_validation->set_rules('mid', 'lang:text_client_id', 'required', array('required' => $this->lang->line('err_cid_req')));
            $this->form_validation->set_rules('payment_status', 'lang:text_mode', 'required', array('required' => $this->lang->line('err_payment_status_req')));
            $this->form_validation->set_rules('name', 'lang:text_email', 'required', array('required' => $this->lang->line('err_email_req')));
            $this->form_validation->set_rules('currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
            $this->form_validation->set_rules('currency_point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'pg_detail', $data);
            } else {
                $array = array(
                    'mid' => $this->input->post('mid'),
                    'payment_status' => $this->input->post('payment_status'),
                    'name' => $this->input->post('name'),
                    'date' => date('d-m-Y'),
                    'currency' => $this->input->post('currency'),
                    'currency_point' => $this->input->post('currency_point'),
                );
                $this->db->where('id', $this->input->post('id'));
                if ($this->db->update('pg_detail', $array)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_payment'));
                    redirect($this->path_to_view_admin . 'pgdetail/');
                }
            }
        } elseif ($this->input->post('update_offline') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', 'You have not permission to change payment.');
                redirect($this->path_to_view_admin . 'pgdetail/');
            }
            $data['payment_description'] = $this->input->post('payment_description');
            $data['currency'] = $this->input->post('currency');
            $data['currency_point'] = $this->input->post('currency_point');

            $this->form_validation->set_rules('payment_description', 'lang:text_payment_desc', 'required', array('required' => $this->lang->line('err_payment_desc_req')));
            $this->form_validation->set_rules('currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
            $this->form_validation->set_rules('currency_point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'pg_detail', $data);
            } else {
                $array = array(
                    'payment_description' => $this->input->post('payment_description'),
                    'currency' => $this->input->post('currency'),
                    'currency_point' => $this->input->post('currency_point'),
                );
                $this->db->where('id', $this->input->post('id'));
                if ($this->db->update('pg_detail', $array)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_payment'));
                    redirect($this->path_to_view_admin . 'pgdetail/');
                }
            }
        } elseif ($this->input->post('update_paystack') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', 'You have not permission to change payment.');
                redirect($this->path_to_view_admin . 'pgdetail/');
            }
            $data['mid'] = $this->input->post('mid');
            $data['mkey'] = $this->input->post('mkey');
            $data['payment_status'] = $this->input->post('payment_status');
            $data['currency'] = $this->input->post('currency');
            $data['currency_point'] = $this->input->post('currency_point');

            $this->form_validation->set_rules('mid', 'lang:text_public_key', 'required', array('required' => $this->lang->line('err_public_key_req')));
            $this->form_validation->set_rules('mkey', 'lang:text_secret_key', 'required', array('required' => $this->lang->line('err_secret_key_req')));
            $this->form_validation->set_rules('payment_status', 'lang:text_mode', 'required', array('required' => $this->lang->line('err_payment_status_req')));
            $this->form_validation->set_rules('currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
            $this->form_validation->set_rules('currency_point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'pg_detail', $data);
            } else {
                $array = array(
                    'mid' => $this->input->post('mid'),
                    'mkey' => $this->input->post('mkey'),
                    'payment_status' => $this->input->post('payment_status'),
                    'currency' => $this->input->post('currency'),
                    'currency_point' => $this->input->post('currency_point'),
                );
                $this->db->where('id', $this->input->post('id'));
                if ($this->db->update('pg_detail', $array)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_payment'));
                    redirect($this->path_to_view_admin . 'pgdetail/');
                }
            }
        } elseif ($this->input->post('update_instamojo') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_payment'));
                redirect($this->path_to_view_admin . 'pgdetail/');
            }
            $data['mid'] = $this->input->post('mid');
            $data['mkey'] = $this->input->post('mkey');
            $data['name'] = $this->input->post('name');
            $data['wname'] = $this->input->post('wname');
            $data['payment_status'] = $this->input->post('payment_status');
            $data['currency'] = $this->input->post('currency');
            $data['currency_point'] = $this->input->post('currency_point');

            $this->form_validation->set_rules('mid', 'lang:text_client_id', 'required', array('required' => $this->lang->line('err_cid_req')));
            $this->form_validation->set_rules('mkey', 'lang:text_client_secret', 'required', array('required' => $this->lang->line('err_c_secret_req')));
            $this->form_validation->set_rules('payment_status', 'lang:text_mode', 'required', array('required' => $this->lang->line('err_payment_status_req')));
            $this->form_validation->set_rules('name', 'lang:text_api_key', 'required', array('required' => $this->lang->line('err_api_key_req')));
            $this->form_validation->set_rules('wname', 'lang:text_auth_token', 'required', array('required' => $this->lang->line('err_auth_token_req')));
            $this->form_validation->set_rules('currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
            $this->form_validation->set_rules('currency_point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'pg_detail', $data);
            } else {
                $array = array(
                    'mid' => $this->input->post('mid'),
                    'mkey' => $this->input->post('mkey'),
                    'name' => $this->input->post('name'),
                    'wname' => $this->input->post('wname'),
                    'payment_status' => $this->input->post('payment_status'),
                    'currency' => $this->input->post('currency'),
                    'currency_point' => $this->input->post('currency_point'),
                );
                $this->db->where('id', $this->input->post('id'));
                if ($this->db->update('pg_detail', $array)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_payment'));
                    redirect($this->path_to_view_admin . 'pgdetail/');
                }
            }
        } elseif ($this->input->post('update_razorpay') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_payment'));
                redirect($this->path_to_view_admin . 'pgdetail/');
            }
            $data['mid'] = $this->input->post('mid');
            $data['mkey'] = $this->input->post('mkey');
            $data['payment_status'] = $this->input->post('payment_status');
            $data['currency'] = $this->input->post('currency');
            $data['currency_point'] = $this->input->post('currency_point');

            $this->form_validation->set_rules('mid', 'lang:text_key_id', 'required', array('required' => $this->lang->line('err_key_id_req')));
            $this->form_validation->set_rules('mkey', 'lang:text_api_secret', 'required', array('required' => $this->lang->line('err_api_secret_req')));
            $this->form_validation->set_rules('payment_status', 'lang:text_mode', 'required', array('required' => $this->lang->line('err_payment_status_req')));
            $this->form_validation->set_rules('currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
            $this->form_validation->set_rules('currency_point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'pg_detail', $data);
            } else {
                $array = array(
                    'mid' => $this->input->post('mid'),
                    'mkey' => $this->input->post('mkey'),
                    'payment_status' => $this->input->post('payment_status'),
                    'currency' => $this->input->post('currency'),
                    'currency_point' => $this->input->post('currency_point'),
                );
                $this->db->where('id', $this->input->post('id'));
                if ($this->db->update('pg_detail', $array)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_payment'));
                    redirect($this->path_to_view_admin . 'pgdetail/');
                }
            }
        } elseif ($this->input->post('update_cashfree') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_payment'));
                redirect($this->path_to_view_admin . 'pgdetail/');
            }
            $data['payment_status'] = $this->input->post('payment_status');
            $data['mid'] = $this->input->post('mid');
            $data['mkey'] = $this->input->post('mkey');
            $data['currency'] = $this->input->post('currency');
            $data['currency_point'] = $this->input->post('currency_point');

            $this->form_validation->set_rules('mid', 'lang:text_app_key', 'required', array('required' => $this->lang->line('err_app_key_req')));
            $this->form_validation->set_rules('mkey', 'lang:text_secret_key', 'required', array('required' => $this->lang->line('err_secret_key_req')));
            $this->form_validation->set_rules('payment_status', 'lang:text_mode', 'required', array('required' => $this->lang->line('err_payment_status_req')));
            $this->form_validation->set_rules('currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
            $this->form_validation->set_rules('currency_point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'pg_detail', $data);
            } else {
                $array = array(
                    'mid' => $this->input->post('mid'),
                    'mkey' => $this->input->post('mkey'),
                    'payment_status' => $this->input->post('payment_status'),
                    'date' => date('d-m-Y'),
                    'currency' => $this->input->post('currency'),
                    'currency_point' => $this->input->post('currency_point'),
                );
                $this->db->where('id', $this->input->post('id'));
                if ($this->db->update('pg_detail', $array)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_payment'));
                    redirect($this->path_to_view_admin . 'pgdetail/');
                }
            }
        } elseif ($this->input->post('update_googlepay') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_payment'));
                redirect($this->path_to_view_admin . 'pgdetail/');
            }
            $data['mid'] = $this->input->post('mid');
            $data['currency'] = $this->input->post('currency');
            $data['currency_point'] = $this->input->post('currency_point');

            $this->form_validation->set_rules('mid', 'lang:text_upi_id', 'required', array('required' => $this->lang->line('err_upi_id_req')));
            $this->form_validation->set_rules('currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
            $this->form_validation->set_rules('currency_point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'pg_detail', $data);
            } else {
                $array = array(
                    'mid' => $this->input->post('mid'),
                    'date' => date('d-m-Y'),
                    'currency' => $this->input->post('currency'),
                    'currency_point' => $this->input->post('currency_point'),
                );
                $this->db->where('id', $this->input->post('id'));
                if ($this->db->update('pg_detail', $array)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_payment'));
                    redirect($this->path_to_view_admin . 'pgdetail/');
                }
            }
        } elseif ($this->input->post('update_tron') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_payment'));
                redirect($this->path_to_view_admin . 'pgdetail/');
            }
            $data['mid'] = $this->input->post('mid');
            $data['mkey'] = $this->input->post('mkey');            
            $data['wname'] = $this->input->post('wname');
            $data['payment_status'] = $this->input->post('payment_status');
            $data['currency'] = $this->input->post('currency');
            $data['currency_point'] = $this->input->post('currency_point');

            $this->form_validation->set_rules('mid', 'lang:text_receiver_contract_address', 'required', array('required' => $this->lang->line('err_receiver_contract_address_req')));
            $this->form_validation->set_rules('mkey', 'lang:text_contract_address', 'required', array('required' => $this->lang->line('err_contract_address_req')));
            $this->form_validation->set_rules('payment_status', 'lang:text_mode', 'required', array('required' => $this->lang->line('err_payment_status_req')));
            $this->form_validation->set_rules('wname', 'lang:text_abi_key', 'required', array('required' => $this->lang->line('err_abi_key_req')));
            $this->form_validation->set_rules('currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
            $this->form_validation->set_rules('currency_point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'pg_detail', $data);
            } else {
                $array = array(
                    'mid' => $this->input->post('mid'),
                    'mkey' => $this->input->post('mkey'),                    
                    'wname' => $this->input->post('wname'),
                    'payment_status' => $this->input->post('payment_status'),
                    'currency' => $this->input->post('currency'),
                    'currency_point' => $this->input->post('currency_point'),
                );
                $this->db->where('id', $this->input->post('id'));
                if ($this->db->update('pg_detail', $array)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_payment'));
                    redirect($this->path_to_view_admin . 'pgdetail/');
                }
            }
        } else {

            $data['pgdetail'] = $this->getPgDetailData();
            $this->load->view($this->path_to_view_admin . 'pg_detail_manage', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('pgdetail_edit')) {
            redirect($this->path_to_view_admin . 'pgdetail');
        }

        $data['pgdetail'] = true;
        $data['title'] = $this->lang->line('text_payment_gateway_int');
        $id = $this->uri->segment('4');
        $data['currency_data'] = $this->functions->getCurrency();
        $data['pgdetail'] = $this->getPgDetail($id);
        $this->load->view($this->path_to_view_admin . 'pg_detail', $data);
    }

    function getPgDetailData() {
        $this->db->select('*');
        $query = $this->db->get('pg_detail');
        return $query->result();
    }

    function getPgDetail($id) {
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get('pg_detail');
        return $query->row_array();
    }

    function update() {
        $array = array(
            'mid' => $this->input->post('mid'),
            'mkey' => $this->input->post('mkey'),
            'wname' => $this->input->post('wname'),
            'itype' => $this->input->post('itype'),
            'payment_status' => $this->input->post('payment_status'),
            'currency' => $this->input->post('currency'),
            'currency_point' => $this->input->post('currency_point'),
            'date' => date('d-m-Y'),
        );
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->update('pg_detail', $array)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
