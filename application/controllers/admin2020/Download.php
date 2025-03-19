<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends CI_Controller {

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
        if(!$this->functions->check_permission('download')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_view_admin . 'Download_model', 'download');
    }

    function index() {
        $data['download'] = true;
        $data['btn'] = $this->lang->line('text_add_screenshot');
        $data['title'] = $this->lang->line('text_howtoinstall');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('downloadid') <= 7) || !$this->functions->check_permission('download_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete'));
                redirect($this->path_to_view_admin . 'download/');
            } else {
                if ($result = $this->download->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_download'));
                    redirect($this->path_to_view_admin . 'download/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('downloadid') <= 7) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'download/');
            } else {
                if ($result = $this->download->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_download'));
                    redirect($this->path_to_view_admin . 'download/');
                }
            }
        }
        $this->load->view($this->path_to_view_admin . 'download_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 7) || !$this->functions->check_permission('download_delete')) {                    
                echo $this->lang->line('text_err_delete');                    
            } else {
                $this->download->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 7) || !$this->functions->check_permission('download')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->download->changeMultiPublishStatus();            
            }       
        }
    }

    function insert() {
        $data['download_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_screenshot');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_howtoinstall');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['download_image'] = $this->input->post('download_image');
            $data['dp_order'] = $this->input->post('dp_order');

            $this->form_validation->set_rules('download_image', 'lang:text_howtoinstall', 'callback_file_check');
            $this->form_validation->set_rules('dp_order', 'lang:text_display_order', 'required|numeric', array('required' => $this->lang->line('err_dp_order_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'download_addedit', $data);
            } else {
                if ($result = $this->download->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_download'));
                    redirect($this->path_to_view_admin . 'download/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'download_addedit', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('download_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_download'));
            redirect($this->path_to_view_admin . 'download');
        }

        $data['download_addedit'] = true;
        $download_id = $this->uri->segment('4');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['title'] = $this->lang->line('text_edit_howtoinstall');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('download_id') <= 7) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_download'));
                redirect($this->path_to_view_admin . 'download/');
            } else {
                $data['download_image'] = $this->input->post('download_image');
                $data['dp_order'] = $this->input->post('dp_order');

                $this->form_validation->set_rules('download_image', 'lang:text_howtoinstall', 'callback_file_check');
                $this->form_validation->set_rules('dp_order', 'lang:text_display_order', 'required|numeric', array('required' => $this->lang->line('err_dp_order_req'), 'numeric' => $this->lang->line('err_number')));
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'download_addedit', $data);
                } else {
                    if ($result = $this->download->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_download'));
                        redirect($this->path_to_view_admin . 'download/');
                    }
                }
            }
        } else {
            $data['download_detail'] = $this->download->getDownloadById($download_id);
            $this->load->view($this->path_to_view_admin . 'download_addedit', $data);
        }
    }

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');

        if (isset($_FILES['download_image']['name']) && $_FILES['download_image']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['download_image']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["download_image"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    function setDatatableDownload() {

        $requestData = $_REQUEST;

        $columns = array(
            2 => 'download_image',
            3 => 'dp_order',
            4 => 'status',
            5 => 'date_created'
        );

        $totalData = $this->download->get_list_count_download();
        $totalFiltered = $totalData;

        $sql = "SELECT * FROM download";

        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  download_image LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  dp_order LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  date_created LIKE '%" . $requestData['search']['value'] . "%' ";
        }
        $query = mysqli_query($this->con, $sql);

        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 

        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `dp_order` ASC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }

        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = '<input type="checkbox" value="'. $row['download_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = '<img src="' . base_url() . $this->download_image . "thumb/100x100_" . $row['download_image'] . '">';
            $nestedData[] = $row['dp_order'];
            $nestedData[] = $row['date_created'];
            if ($this->system->demo_user == 1 && $row['download_id'] <= 7) {
                if ($row['status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger">Inactive</span>';
                }
            } else {
                if ($row['status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmdownloadlist,' . $row['download_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmdownloadlist,' . $row['download_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'download/edit/' . $row['download_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['download_id'] <= 7) {
                $delete = '<a  class="" disabled="disabled" style="cursor: pointer;font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteDownload(document.frmdownloadlist,' . $row['download_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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
