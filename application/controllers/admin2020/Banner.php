<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends CI_Controller {

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
        if(!$this->functions->check_permission('banner')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_view_admin . 'Banner_model', 'banner');
        $this->load->model($this->path_to_view_admin . 'Match_model', 'match');
    }

    function index() {
        $data['banner'] = true;
        $data['btn'] = $this->lang->line('text_add_banner');
        $data['title'] = $this->lang->line('text_banner');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('bannerid') <= 2) || !$this->functions->check_permission('banner_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_banner'));
                redirect($this->path_to_view_admin . 'banner/');
            } else {
                if ($result = $this->banner->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_banner'));
                    redirect($this->path_to_view_admin . 'banner/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('bannerid') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'banner/');
            } else {
                if ($result = $this->banner->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_banner'));
                    redirect($this->path_to_view_admin . 'banner/');
                }
            }
        }
        $this->load->view($this->path_to_view_admin . 'banner_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 2) || !$this->functions->check_permission('banner_delete')) {                    
                echo $this->lang->line('text_err_delete_banner');                    
            } else {
                $this->banner->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 2) || !$this->functions->check_permission('banner')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->banner->changeMultiPublishStatus();            
            }       
        }
    }

    function insert() {
        $data['banner_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_banner');
        $data['title'] = $this->lang->line('text_add_banner');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['game_data'] = $this->match->getgame();
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['banner_title'] = $this->input->post('banner_title');
            $data['banner_link_type'] = $this->input->post('banner_link_type');
            $data['web_banner_link'] = $this->input->post('web_banner_link');
            $data['app_banner_link'] = $this->input->post('app_banner_link');
            $data['game_id'] = $this->input->post('game_id');

            $this->form_validation->set_rules('banner_title', 'lang:text_title', 'required', array('required' => $this->lang->line('err_banner_title_req')));
            $this->form_validation->set_rules('banner_link_type', 'lang:text_link', 'required', array('required' => $this->lang->line('err_banner_link_type_req')));
            $this->form_validation->set_rules('banner_image', 'lang:text_image', 'callback_file_check');

            if ($this->input->post('banner_link') == 'app')
                $this->form_validation->set_rules('banner_link_type', 'lang:text_link', 'required', array('required' => $this->lang->line('err_app_banner_link_req')));
//                $this->form_validation->set_rules('app_banner_link', 'Link', 'required');
            elseif ($this->input->post('banner_link') == 'web')
                $this->form_validation->set_rules('banner_link_type', 'lang:text_link', 'required|valid_url', array('required' => $this->lang->line('err_web_banner_link_req'), 'valid_url' => $this->lang->line('err_url_valid')));
//                $this->form_validation->set_rules('web_banner_link', 'Link', 'required|valid_url');
            if ($this->input->post('app_banner_link') == 'Game')
                $this->form_validation->set_rules('game_id', 'lang:text_game', 'required', array('required' => $this->lang->line('err_game_id_req')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'banner_addedit', $data);
            } else {
                if ($result = $this->banner->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_banner'));
                    redirect($this->path_to_view_admin . 'banner/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'banner_addedit', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('banner_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_banner'));
            redirect($this->path_to_view_admin . 'banner');
        }

        $data['banner_addedit'] = true;
        $banner_id = $this->uri->segment('4');
        $data['title'] = $this->lang->line('text_edit_banner');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['game_data'] = $this->match->getgame();
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('banner_id') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_banner'));
                redirect($this->path_to_view_admin . 'banner/');
            } else {
                $data['banner_title'] = $this->input->post('banner_title');
                $data['banner_link_type'] = $this->input->post('banner_link_type');
                $data['web_banner_link'] = $this->input->post('web_banner_link');
                $data['app_banner_link'] = $this->input->post('app_banner_link');
                $data['game_id'] = $this->input->post('game_id');

                $this->form_validation->set_rules('banner_title', 'lang:text_title', 'required', array('required' => $this->lang->line('err_banner_title_req')));
                $this->form_validation->set_rules('banner_link_type', 'lang:text_link', 'required', array('required' => $this->lang->line('err_banner_link_type_req')));
                $this->form_validation->set_rules('banner_image', 'lang:text_image', 'callback_file_check');

                if ($this->input->post('banner_link') == 'app')
                    $this->form_validation->set_rules('banner_link_type', 'lang:text_link', 'required', array('required' => $this->lang->line('err_app_banner_link_req')));
//                $this->form_validation->set_rules('app_banner_link', 'Link', 'required');
                elseif ($this->input->post('banner_link') == 'web')
                    $this->form_validation->set_rules('banner_link_type', 'lang:text_link', 'required|valid_url', array('required' => $this->lang->line('err_web_banner_link_req'), 'valid_url' => $this->lang->line('err_url_valid')));
//                $this->form_validation->set_rules('web_banner_link', 'Link', 'required|valid_url');
                if ($this->input->post('app_banner_link') == 'Game')
                    $this->form_validation->set_rules('game_id', 'lang:text_game', 'required', array('required' => $this->lang->line('err_game_id_req')));

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'banner_addedit', $data);
                } else {
                    if ($result = $this->banner->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_banner'));
                        redirect($this->path_to_view_admin . 'banner/');
                    }
                }
            }
        } else {
            $data['banner_detail'] = $this->banner->getBannerById($banner_id);
            $this->load->view($this->path_to_view_admin . 'banner_addedit', $data);
        }
    }

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['banner_image']['name']) && $_FILES['banner_image']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['banner_image']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["banner_image"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    function setDatatableBanner() {
        $requestData = $_REQUEST;
        $columns = array(            
            2 => 'banner_title',
            3 => 'banner_image',
            4 => 'banner_link',
            5 => 'status',
            6 => 'date_created'
        );
        $totalData = $this->banner->get_list_count_banner();
        $totalFiltered = $totalData;
        $sql = "SELECT banner_id,banner_title,banner_image,banner_link,status,date_created FROM banner";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  banner_title LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  banner_image LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  banner_link LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData[] = '<input type="checkbox" value="'. $row['banner_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['banner_title'];
            $nestedData[] = '<img src="' . base_url() . $this->banner_image . "thumb/100x100_" . $row['banner_image'] . '">';
            $nestedData[] = $row['banner_link'];
            $nestedData[] = $row['date_created'];
            if ($this->system->demo_user == 1 && $row['banner_id'] <= 2) {
                if ($row['status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger">Inactive</span>';
                }
            } else {
                if ($row['status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmbannerlist,' . $row['banner_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmbannerlist,' . $row['banner_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }

            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'banner/edit/' . $row['banner_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['banner_id'] <= 2) {
                $delete = '<a  class="" disabled="disabled" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" ><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top" style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteSlider(document.frmbannerlist,' . $row['banner_id'] . ');" style="font-size:18px;color:#007bff" ><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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
