<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Youtube extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        $this->load->library('upload');
        $this->load->helper('file');
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('youtube')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_view_admin . 'Youtube_model', 'youtube');
    }

    function index() {
        $data['youtube'] = true;
        $data['title'] = $this->lang->line('text_app_tutorial');
        $data['btn'] = $this->lang->line('text_add_app_tutorial');
        if ($this->input->post('action') == "delete") {
            if($this->system->demo_user == 1 || !$this->functions->check_permission('youtube_delete')) {
                redirect($this->path_to_view_admin . 'youtube');
            }

            if ($result = $this->youtube->delete()) {
                $this->session->set_flashdata('notification', $this->lang->line('text_err_delete_tutorial_link'));
                redirect($this->path_to_view_admin . 'youtube/');
            }
        }
        $this->load->view($this->path_to_view_admin . 'youtube_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if ($this->system->demo_user == 1 || !$this->functions->check_permission('youtube_delete')) {                    
                echo $this->lang->line('text_err_delete');                    
            } else {
                $this->youtube->multiDelete();                       
            }           
        }
    }

    function insert() {
        $data['youtube_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_app_tutorial');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_app_tutorial');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['youtube_link'] = $this->input->post('youtube_link');
            $data['youtube_link_title'] = $this->input->post('youtube_link_title');

            $this->form_validation->set_rules('youtube_link', 'lang:text_app_tutorial_title', 'required|valid_url', array('required' => $this->lang->line('err_youtube_link_req'), 'valid_url' => $this->lang->line('err_url_valid')));
            $this->form_validation->set_rules('youtube_link_title', 'lang:text_app_tutorial_link', 'required', array('required' => $this->lang->line('err_youtube_link_title_req')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'youtube_addedit', $data);
            } else {
                if ($this->youtube->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_tutorial_link'));
                    redirect($this->path_to_view_admin . 'youtube/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'youtube_addedit', $data);
        }
    }

    function edit() {

        if(!$this->functions->check_permission('youtube_edit')) {
            redirect($this->path_to_view_admin . 'youtube');
        }

        $data['youtube_addedit'] = true;
        $youtube_link_id = $this->uri->segment('4');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['title'] = $this->lang->line('text_edit_app_tutorial');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if($this->system->demo_user == 1) {
                redirect($this->path_to_view_admin . 'youtube');
            }

            $data['youtube_link'] = $this->input->post('youtube_link');
            $data['youtube_link_title'] = $this->input->post('youtube_link_title');

            $this->form_validation->set_rules('youtube_link', 'lang:text_app_tutorial_title', 'required|valid_url', array('required' => $this->lang->line('err_youtube_link_req'), 'valid_url' => $this->lang->line('err_url_valid')));
            $this->form_validation->set_rules('youtube_link_title', 'lang:text_app_tutorial_link', 'required', array('required' => $this->lang->line('err_youtube_link_title_req')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'youtube_addedit', $data);
            } else {
                if ($this->youtube->update()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_withdraw_method'));
                    redirect($this->path_to_view_admin . 'youtube/');
                }
            }
        } else {
            $data['youtube_detail'] = $this->youtube->getYoutubeLinkById($youtube_link_id);
            $this->load->view($this->path_to_view_admin . 'youtube_addedit', $data);
        }
    }

    function setDatatableYoutubeLink() {
        $requestData = $_REQUEST;
        $columns = array(            
            2 => 'youtube_link_title',
            3 => 'youtube_link',
            4 => 'date_created'
        );
        $totalData = $this->youtube->get_list_count_youtube();
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM youtube_link";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  youtube_link_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  youtube_link LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  youtube_link_title LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData[] = '<input type="checkbox" value="'. $row['youtube_link_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['youtube_link_title'];
            $nestedData[] = $row['youtube_link'];

            $nestedData[] = $row['date_created'];
            $nestedData[] = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'youtube/edit/' . $row['youtube_link_id'] . '><i class="fa fa-edit"></i></a>&nbsp;
                <a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteYoutubeLink(document.frmyoutubelist,' . $row['youtube_link_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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
