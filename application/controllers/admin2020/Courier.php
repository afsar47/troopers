<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Courier extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('courier')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image_lib');
        $this->load->model($this->path_to_view_admin . 'Courier_model', 'courier');
    }

    function index() {
        $data['courier'] = true;
        $data['btn'] = $this->lang->line('text_add_courier');
        $data['title'] = $this->lang->line('text_courier');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('courierid') <= 2) || !$this->functions->check_permission('courier_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_courier'));
                redirect($this->path_to_view_admin . 'courier/');
            } else {
                if ($result = $this->courier->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_courier'));
                    redirect($this->path_to_view_admin . 'courier/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('courierid') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'courier/');
            } else {
                if ($result = $this->courier->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_courier'));
                    redirect($this->path_to_view_admin . 'courier/');
                }
            }
        }
        $data['courier_data'] = $this->courier->courier_data();
        $this->load->view($this->path_to_view_admin . 'courier_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 2) || !$this->functions->check_permission('courier_delete')) {                    
                echo $this->lang->line('text_err_delete_courier');                    
            } else {
                $this->courier->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 2) || !$this->functions->check_permission('courier')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->courier->changeMultiPublishStatus();            
            }       
        }
    }

    function setDatatableCourier() {
        $requestData = $_REQUEST;
        $columns = array(            
            2 => 'courier_name',
            3 => 'courier_link',
            4 => 'status',
            5 => 'date_created',
        );
        $totalData = $this->courier->get_list_count_courier();
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM courier";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  courier_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  courier_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  courier_link LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  status LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData[] = '<input type="checkbox" value="'. $row['courier_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['courier_name'];
            $nestedData[] = '<a href="' . $row['courier_link'] . '">' . $row['courier_link'] . '</a>';
            if ($this->system->demo_user == 1 && $row['courier_id'] <= 2) {

                if ($row['status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top">Inactive</span>';
                }
            } else {
                if ($row['status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmcourierlist,' . $row['courier_id'] . ',0);">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmcourierlist,' . $row['courier_id'] . ',1);">Inactive</span>';
                }
            }
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'courier/edit/' . $row['courier_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';

            if ($this->system->demo_user == 1 && $row['courier_id'] <= 2) {
                $delete = '<a disabled="disabled" class="" data-original-title="Delete" data-placement="top"  style="font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteCourier(document.frmcourierlist,' . $row['courier_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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

    function insert() {
        $data['courier_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_courier');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_courier');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['courier_name'] = $this->input->post('courier_name');
            $data['courier_link'] = $this->input->post('courier_link');
            $this->form_validation->set_rules('courier_name', 'lang:text_courier_name', 'required', array('required' => $this->lang->line('err_courier_name_req')));
            $this->form_validation->set_rules('courier_link', 'lang:text_courier_link', 'required|valid_url', array('required' => $this->lang->line('err_courier_link_req'), 'valid_url' => $this->lang->line('err_courier_link_valid')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'courier_addedit', $data);
            } else {
                if ($result = $this->courier->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_courier'));
                    redirect($this->path_to_view_admin . 'courier/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'courier_addedit', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('courier_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_courier'));
            redirect($this->path_to_view_admin . 'courier');
        }

        $data['courier_addedit'] = true;
        $courier_id = $this->uri->segment('4');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['title'] = $this->lang->line('text_edit_courier');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('courier_id') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_courier'));
                redirect($this->path_to_view_admin . 'courier/');
            }
            $data['courier_name'] = $this->input->post('courier_name');
            $data['courier_link'] = $this->input->post('courier_link');
            $this->form_validation->set_rules('courier_name', 'lang:text_courier_name', 'required', array('required' => $this->lang->line('err_courier_name_req')));
            $this->form_validation->set_rules('courier_link', 'lang:text_courier_link', 'required|valid_url', array('required' => $this->lang->line('err_courier_link_req'), 'valid_url' => $this->lang->line('err_courier_link_valid')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'courier_addedit', $data);
            } else {
                if ($result = $this->courier->update()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_courier'));
                    redirect($this->path_to_view_admin . 'courier/');
                }
            }
        } else {
            $data['courier_detail'] = $this->courier->getcourierById($courier_id);
            $this->load->view($this->path_to_view_admin . 'courier_addedit', $data);
        }
    }

}
