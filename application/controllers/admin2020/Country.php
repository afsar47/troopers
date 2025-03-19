<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Country extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('country')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_view_admin . 'Country_model', 'country');
    }

    function index() {
        $data['country'] = true;
        $data['btn'] = $this->lang->line('text_add_country');
        $data['title'] = $this->lang->line('text_country');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('countryid') <= 230) || !$this->functions->check_permission('country_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_country'));
                redirect($this->path_to_view_admin . 'country/');
            } else {
                if ($result = $this->country->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_err_delete_country'));
                    redirect($this->path_to_view_admin . 'country/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('countryid') <= 230) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'country/');
            } else {
                if ($result = $this->country->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_country'));
                    redirect($this->path_to_view_admin . 'country/');
                }
            }
        }
        $data['country_data'] = $this->country->country_data();
        $this->load->view($this->path_to_view_admin . 'country_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 230) || !$this->functions->check_permission('country_delete')) {                    
                echo $this->lang->line('text_err_delete_country');                    
            } else {
                $this->country->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 230) || !$this->functions->check_permission('country')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->country->changeMultiPublishStatus();            
            }       
        }
    }

    function setDatatableCountry() {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'country_id',
            2 => 'country_name',
            3 => 'p_code',
            4 => 'country_status',
            5 => 'date_created',
        );
        $totalData = $this->country->get_list_count_country();
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM country";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  country_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  country_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  p_code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  country_status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  date_created LIKE '%" . $requestData['search']['value'] . "%' ";
        }
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `date_created` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = '<input type="checkbox" value="'. $row['country_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['country_name'];
            $nestedData[] = $row['p_code'];
            if ($this->system->demo_user == 1 && $row['country_id'] <= 230) {
                if ($row['country_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top" border="0">Inactive</span>';
                }
            } else {
                if ($row['country_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmcountrylist,' . $row['country_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmcountrylist,' . $row['country_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'country/edit/' . $row['country_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['country_id'] <= 230) {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top" disabled="disabled" style="cursor: pointer;font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteCountry(document.frmcountrylist,' . $row['country_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            }
            $nestedData[] = $edit . $delete;
            $nestedData[] = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'country/edit/' . $row['country_id'] . '><i class="fa fa-edit"></i></a>&nbsp;
                <a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteCountry(document.frmcountrylist,' . $row['country_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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

    function insert() {
        $data['country_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_country');
        $data['title'] = $this->lang->line('text_add_country');
        $data['Action'] = $this->lang->line('text_action_add');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['country_name'] = $this->input->post('country_name');
            $data['p_code'] = $this->input->post('p_code');
            $this->form_validation->set_rules('country_name', 'lang:text_country_name', 'required', array('required' => $this->lang->line('err_country_name_req')));
            $this->form_validation->set_rules('p_code', 'lang:text_country_code', 'required', array('required' => $this->lang->line('err_p_code_req')));
            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'country_addedit', $data);
            } else {
                if ($result = $this->country->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_country'));
                    redirect($this->path_to_view_admin . 'country/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'country_addedit', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('country_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_succ_edit_country'));
            redirect($this->path_to_view_admin . 'country');
        }
        
        $data['country_addedit'] = true;
        $country_id = $this->uri->segment('4');
        $data['title'] = $this->lang->line('text_edit_country');
        $data['Action'] = $this->lang->line('text_action_edit');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('country_id') <= 230) {
                $this->session->set_flashdata('error', $this->lang->line('text_succ_edit_country'));
                redirect($this->path_to_view_admin . 'country/');
            } else {
                $data['country_name'] = $this->input->post('country_name');
                $data['p_code'] = $this->input->post('p_code');
                $this->form_validation->set_rules('country_name', 'lang:text_country_name', 'required', array('required' => $this->lang->line('err_country_name_req')));
                $this->form_validation->set_rules('p_code', 'lang:text_country_code', 'required', array('required' => $this->lang->line('err_p_code_req')));
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'country_addedit', $data);
                } else {
                    if ($result = $this->country->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_err_edit_country'));
                        redirect($this->path_to_view_admin . 'country/');
                    }
                }
            }
        } else {
            $data['country_detail'] = $this->country->getcountryById($country_id);
            $this->load->view($this->path_to_view_admin . 'country_addedit', $data);
        }
    }

}
