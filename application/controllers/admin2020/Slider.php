<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends CI_Controller {

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
        if(!$this->functions->check_permission('slider')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_view_admin . 'Slider_model', 'slider');
        $this->load->model($this->path_to_view_admin . 'Match_model', 'match');
    }

    function index() {
        $data['slider'] = true;
        $data['btn'] = $this->lang->line('text_add_slider');
        $data['title'] = $this->lang->line('text_slider');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('sliderid') <= 2) || !$this->functions->check_permission('slider_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_slider'));
                redirect($this->path_to_view_admin . 'slider/');
            } else {
                if ($result = $this->slider->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_slider'));
                    redirect($this->path_to_view_admin . 'slider/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('sliderid') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'slider/');
            } else {
                if ($result = $this->slider->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_slider'));
                    redirect($this->path_to_view_admin . 'slider/');
                }
            }
        }
        $this->load->view($this->path_to_view_admin . 'slider_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 2) || !$this->functions->check_permission('slider_delete')) {                    
                echo $this->lang->line('text_err_delete_slider');                    
            } else {
                $this->slider->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 2) || !$this->functions->check_permission('slider')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->slider->changeMultiPublishStatus();            
            }       
        }
    }

    function insert() {
        $data['slider_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_slider');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_slider');
        $data['game_data'] = $this->match->getgame();
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', 'You have not permission to add slider');
                redirect($this->path_to_view_admin . 'slider/');
            } else {
                $data['slider_title'] = $this->input->post('slider_title');
                $data['slider_link_type'] = $this->input->post('slider_link_type');
                $data['web_slider_link'] = $this->input->post('web_slider_link');
                $data['app_slider_link'] = $this->input->post('app_slider_link');
                $data['game_id'] = $this->input->post('game_id');

                $this->form_validation->set_rules('slider_title', 'lang:text_title', 'required', array('required' => $this->lang->line('err_slider_title_req')));
    //            $this->form_validation->set_rules('slider_link_type', 'lang:text_link', 'required', array('required' => $this->lang->line('err_slider_link_type_req')));
                $this->form_validation->set_rules('slider_image', 'lang:text_image', 'callback_file_check');

                if ($this->input->post('slider_link') == 'app')
                    $this->form_validation->set_rules('app_slider_link', 'lang:text_link', 'required', array('required' => $this->lang->line('err_app_slider_link_req')));
                elseif ($this->input->post('slider_link') == 'web')
                    $this->form_validation->set_rules('web_slider_link', 'lang:text_link', 'required|valid_url', array('required' => $this->lang->line('err_web_slider_link_req'), 'valid_url' => $this->lang->line('err_url_valid')));
                if ($this->input->post('app_slider_link') == 'Game')
                    $this->form_validation->set_rules('game_id', 'lang:text_game', 'required', array('required' => $this->lang->line('err_game_id_req')));

    //            $this->form_validation->set_rules('web_slider_link', 'Link', 'required|valid_url');
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'slider_addedit', $data);
                } else {
                    if ($result = $this->slider->insert()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_slider'));
                        redirect($this->path_to_view_admin . 'slider/');
                    }
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'slider_addedit', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('slider_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_slider'));
            redirect($this->path_to_view_admin . 'slider');
        }

        $data['slider_addedit'] = true;
        $slider_id = $this->uri->segment('4');
        $data['title'] = $this->lang->line('text_edit_slider');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['game_data'] = $this->match->getgame();
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
//            print_r($_POST);exit;
            if ($this->system->demo_user == 1 && $this->input->post('slider_id') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_slider'));
                redirect($this->path_to_view_admin . 'slider/');
            } else {
                $data['slider_title'] = $this->input->post('slider_title');
                $data['slider_link_type'] = $this->input->post('slider_link_type');
                $data['web_slider_link'] = $this->input->post('web_slider_link');
                $data['app_slider_link'] = $this->input->post('app_slider_link');
                $data['game_id'] = $this->input->post('game_id');

                $this->form_validation->set_rules('slider_title', 'lang:text_title', 'required', array('required' => $this->lang->line('err_slider_title_req')));
//                $this->form_validation->set_rules('slider_link_type', 'lang:text_link', 'required', array('required' => $this->lang->line('err_slider_link_type_req')));
                $this->form_validation->set_rules('slider_image', 'lang:text_image', 'callback_file_check');

                if ($this->input->post('slider_link') == 'app')
                    $this->form_validation->set_rules('app_slider_link', 'lang:text_link', 'required', array('required' => $this->lang->line('err_app_slider_link_req')));
                elseif ($this->input->post('slider_link') == 'web')
                    $this->form_validation->set_rules('web_slider_link', 'lang:text_link', 'required|valid_url', array('required' => $this->lang->line('err_web_slider_link_req'), 'valid_url' => $this->lang->line('err_url_valid')));
                if ($this->input->post('app_slider_link') == 'Game')
                    $this->form_validation->set_rules('game_id', 'lang:text_game', 'required', array('required' => $this->lang->line('err_game_id_req')));

//                    $this->form_validation->set_rules('web_slider_link', 'Link', 'required|valid_url');
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'slider_addedit', $data);
                } else {
                    if ($result = $this->slider->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_slider'));
                        redirect($this->path_to_view_admin . 'slider/');
                    }
                }
            }
        } else {
            $data['slider_detail'] = $this->slider->getSliderById($slider_id);
            $this->load->view($this->path_to_view_admin . 'slider_addedit', $data);
        }
    }

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['slider_image']['name']) && $_FILES['slider_image']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['slider_image']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["slider_image"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    function setDatatableSlider() {
        $requestData = $_REQUEST;
        $columns = array(            
            2 => 'slider_title',
            3 => 'slider_image',
            4 => 'slider_link',
            5 => 'status',
            6 => 'date_created'
        );
        $totalData = $this->slider->get_list_count_slider();
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM slider";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  slider_title LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  slider_image LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  slider_link LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData[] = '<input type="checkbox" value="'. $row['slider_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['slider_title'];
            $nestedData[] = '<img src="' . base_url() . $this->slider_image . "thumb/100x100_" . $row['slider_image'] . '">';
            $nestedData[] = $row['slider_link'];
            $nestedData[] = $row['date_created'];
            if ($this->system->demo_user == 1 && $row['slider_id'] <= 2) {
                if ($row['status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger">Inactive</span>';
                }
            } else {
                if ($row['status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmsliderlist,' . $row['slider_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmsliderlist,' . $row['slider_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }

            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'slider/edit/' . $row['slider_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['slider_id'] <= 2) {
                $delete = '<a  class="" disabled="disabled" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" ><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top" style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteSlider(document.frmsliderlist,' . $row['slider_id'] . ');" style="font-size:18px;color:#007bff" ><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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
