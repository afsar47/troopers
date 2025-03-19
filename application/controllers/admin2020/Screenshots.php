<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Screenshots extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image');
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('screenshots')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_view_admin . 'Screenshots_model', 'screenshots');
    }

    function index() {
        $data['screenshots'] = true;
        $data['btn'] = $this->lang->line('text_add_screenshots');
        $data['title'] = $this->lang->line('text_screenshots');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('screenshotsid') <= 8) || !$this->functions->check_permission('screenshots_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_screenshot'));
                redirect($this->path_to_view_admin . 'screenshots/');
            } else {
                if ($result = $this->screenshots->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_screenshot'));
                    redirect($this->path_to_view_admin . 'screenshots/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('screenshotsid') <= 8) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'screenshots/');
            } else {
                if ($result = $this->screenshots->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_screenshot'));
                    redirect($this->path_to_view_admin . 'screenshots/');
                }
            }
        }
        $this->load->view($this->path_to_view_admin . 'screenshots_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 8) || !$this->functions->check_permission('screenshots_delete')) {                    
                echo $this->lang->line('text_err_delete_screenshot');                    
            } else {
                $this->screenshots->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 8) || !$this->functions->check_permission('screenshots')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->screenshots->changeMultiPublishStatus();            
            }       
        }
    }

    function insert() {
        $data['match_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_screenshots');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_screenshots');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['dp_order'] = $this->input->post('dp_order');

            $this->form_validation->set_rules('dp_order', 'lang:text_display_order', 'required|numeric', array('required' => $this->lang->line('err_dp_order_req'), 'numeric' => $this->lang->line('err_number')));
            $this->form_validation->set_rules('screenshot', 'lang:text_add_screenshot', 'callback_file_check');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'screenshots_addedit', $data);
            } else {
                if ($result = $this->screenshots->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_screenshot'));
                    redirect($this->path_to_view_admin . 'screenshots/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'screenshots_addedit', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('screenshots_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_screenshot'));
            redirect($this->path_to_view_admin . 'screenshots');
        }
        $data['match_addedit'] = true;
        $screenshot_id = $this->uri->segment('4');
        $data['title'] = $this->lang->line('text_edit_screenshots');
        $data['Action'] = $this->lang->line('text_action_edit');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('screenshots_id') <= 8) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_screenshot'));
                redirect($this->path_to_view_admin . 'screenshots/');
            } else {
                $data['dp_order'] = $this->input->post('dp_order');

                $this->form_validation->set_rules('dp_order', 'lang:text_display_order', 'required|numeric', array('required' => $this->lang->line('err_dp_order_req'), 'numeric' => $this->lang->line('err_number')));
                $this->form_validation->set_rules('screenshot', 'lang:text_add_screenshot', 'callback_file_check');

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'screenshots_addedit', $data);
                } else {
                    if ($result = $this->screenshots->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_screenshot'));
                        redirect($this->path_to_view_admin . 'screenshots/');
                    }
                }
            }
        } else {
            $data['screenshot_detail'] = $this->screenshots->getScreenshotById($screenshot_id);
            $this->load->view($this->path_to_view_admin . 'screenshots_addedit', $data);
        }
    }

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['screenshot']['name']) && $_FILES['screenshot']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['screenshot']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["screenshot"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    function setDatatableScreenshots() {
        $requestData = $_REQUEST;
        $columns = array(
            2 => 'screenshot',
            3 => 'dp_order',
            4 => 'status',
            5 => 'created_date'
        );
        $totalData = $this->screenshots->get_list_count_screenshots();
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM screenshots";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  screenshot LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  dp_order LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  created_date LIKE '%" . $requestData['search']['value'] . "%' ";
        }
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `created_date` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = '<input type="checkbox" value="'. $row['screenshots_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = '<img src="' . base_url() . $this->screenshot_image . "thumb/100x100_" . $row['screenshot'] . '">';
            $nestedData[] = $row['dp_order'];
            $nestedData[] = $row['created_date'];
            if ($this->system->demo_user == 1 && $row['screenshots_id'] <= 8) {
                if ($row['status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" >Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger">Inactive</span>';
                }
            } else {
                if ($row['status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmscreenshotslist,' . $row['screenshots_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmscreenshotslist,' . $row['screenshots_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'screenshots/edit/' . $row['screenshots_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['screenshots_id'] <= 8) {
                $delete = '<a  class="" disabled="disabled" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" ><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top" style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteScreenshot(document.frmscreenshotslist,' . $row['screenshots_id'] . ');" style="font-size:18px;color:#007bff" ><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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
