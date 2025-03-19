<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Currency extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('currency')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->load->model($this->path_to_view_admin . '/Currency_model', 'currency');
        $this->con = $this->functions->mysql_connection();
    }

    function index() {
        $data['currency'] = true;
        $data['btn'] = $this->lang->line('text_add_currency');
        $data['title'] = $this->lang->line('text_currency');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('currencyid') <= 7) || !$this->functions->check_permission('currency_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_currency'));
                redirect($this->path_to_view_admin . 'currency/');
            } else {
                if ($result = $this->currency->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_currency'));
                    redirect($this->path_to_view_admin . 'currency/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('currencyid') <= 7) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'currency/');
            } else {
                if ($result = $this->currency->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_currency'));
                    redirect($this->path_to_view_admin . 'currency/');
                }
            }
        }if ($this->input->post('submit_currency') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_currency'));
                redirect($this->path_to_view_admin . 'currency/');
                exit;
            }
            $data['currency'] = $this->input->post('currency');
            $data['amount'] = $this->input->post('amount');
            $data['point'] = $this->input->post('point');

            $this->form_validation->set_rules('currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
            $this->form_validation->set_rules('amount', 'lang:text_amount', 'required|numeric', array('required' => $this->lang->line('err_amount_req'), 'numeric' => $this->lang->line('err_number')));
            $this->form_validation->set_rules('point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'currency_addedit', $data);
            } else {
                $settings_arr = array('currency', 'point');
                for ($i = 0; $i < count($settings_arr); $i++) {
                    $settings_data = array('web_config_value' => $this->input->post($settings_arr[$i]));
                    $this->db->where('web_config_name', $settings_arr[$i]);
                    $this->db->update('web_config', $settings_data);
                }
                $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_currency'));
                redirect($this->path_to_view_admin . 'currency/');
            }
        }
        $data['currency'] = $this->currency->getCurrency();
        $this->load->view($this->path_to_view_admin . 'currency_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 7) || !$this->functions->check_permission('currency_delete')) {                    
                echo $this->lang->line('text_err_delete_currency');                    
            } else {
                $this->currency->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 7) || !$this->functions->check_permission('currency')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->currency->changeMultiPublishStatus();            
            }       
        }
    }

    function insert() {
        $data['currency_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_currency');
        $data['title'] = $this->lang->line('text_add_currency');
        $data['Action'] = $this->lang->line('text_action_add');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['currency_name'] = $this->input->post('currency_name');
            $data['currency_code'] = $this->input->post('currency_code');
            $data['currency_symbol'] = $this->input->post('currency_symbol');
            $data['currency_decimal_place'] = $this->input->post('currency_decimal_place');

            $this->form_validation->set_rules('currency_name', 'lang:text_currency_name', 'required', array('required' => $this->lang->line('err_currency_name_req')));
            $this->form_validation->set_rules('currency_code', 'lang:text_currency_code', 'required', array('required' => $this->lang->line('err_currency_code_req')));
            $this->form_validation->set_rules('currency_symbol', 'lang:text_currency_symbol', 'required', array('required' => $this->lang->line('err_currency_symbol_req')));
            $this->form_validation->set_rules('currency_decimal_place', 'lang:text_decimal_places', 'required', array('required' => $this->lang->line('err_currency_decimal_place_req')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'currency_addedit', $data);
            } else {
                if ($result = $this->currency->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_currency'));
                    redirect($this->path_to_view_admin . 'currency/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'currency_addedit', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('currency_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_currency'));
            redirect($this->path_to_view_admin . 'currency');
        }

        $data['currency_addedit'] = true;
        $currency_id = $this->uri->segment('4');
        $data['title'] = $this->lang->line('text_edit_currency');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['Action'] = 'Edit';
        $data['title'] = 'Edit Currency';
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('currency_id') <= 7) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_currency'));
                redirect($this->path_to_view_admin . 'currency/');
            } else {
                $data['currency_name'] = $this->input->post('currency_name');
                $data['currency_code'] = $this->input->post('currency_code');
                $data['currency_symbol'] = $this->input->post('currency_symbol');
                $data['currency_decimal_place'] = $this->input->post('currency_decimal_place');

                $this->form_validation->set_rules('currency_name', 'lang:text_currency_name', 'required', array('required' => $this->lang->line('err_currency_name_req')));
                $this->form_validation->set_rules('currency_code', 'lang:text_currency_code', 'required', array('required' => $this->lang->line('err_currency_code_req')));
                $this->form_validation->set_rules('currency_symbol', 'lang:text_currency_symbol', 'required', array('required' => $this->lang->line('err_currency_symbol_req')));
                $this->form_validation->set_rules('currency_decimal_place', 'lang:text_decimal_places', 'required', array('required' => $this->lang->line('err_currency_decimal_place_req')));

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'currency_addedit', $data);
                } else {
                    if ($result = $this->currency->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_currency'));
                        redirect($this->path_to_view_admin . 'currency/');
                    }
                }
            }
        } else {
            $data['currency_detail'] = $this->currency->getCurrencyById($currency_id);
            $this->load->view($this->path_to_view_admin . 'currency_addedit', $data);
        }
    }

    function setDatatableCurrency() {
        header('Content-Type: application/json; charset=UTF-8');
        $requestData = $_REQUEST;
        $columns = array(            
            2 => 'currency_name',
            3 => 'currency_code',
            4 => 'currency_symbol',
            5 => 'currency_status',
            6 => 'currency_dateCreated'
        );
        $totalData = $this->currency->get_list_count_currency();
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM currency";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  currency_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  currency_code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  currency_symbol LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  currency_status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  currency_dateCreated LIKE '%" . $requestData['search']['value'] . "%' ";
        }
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `currency_dateCreated` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = '<input type="checkbox" value="'. $row['currency_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            if ($this->system->currency == $row['currency_id']) {
                $nestedData[] = $row['currency_name'] . ' <B>(Default)</B>';
            } else {
                $nestedData[] = $row['currency_name'];
            }
            $nestedData[] = $row['currency_code'];
            $nestedData[] = $row['currency_symbol'];
            if ($this->system->demo_user == 1 && $row['currency_id'] <= 7) {

                if ($row['currency_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger">Inactive</span>';
                }
            } else {
                if ($row['currency_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmcurrencylist,' . $row['currency_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmcurrencylist,' . $row['currency_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }

            $nestedData[] = $row['currency_dateCreated'];
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'currency/edit/' . $row['currency_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['currency_id'] <= 7) {
                $delete = '<a  class="" disabled="disabled" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp;';
            } else {
                $delete = '<a  class="" disabled="disabled" data-original-title="Delete" data-placement="top"  style="font-size:18px;color:#007bff" ><i class="fa fa-trash-o"></i> </a>&nbsp;';
            }
            $nestedData[] = $edit . $delete;
            $data[] = $nestedData;
            $i++;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

}
