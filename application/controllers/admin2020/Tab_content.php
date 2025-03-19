<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tab_content extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('tab_content')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_view_admin . '/Tab_content_model', 'tab_content');
    }

    function index() {
        $data['tab_content'] = true;
        $data['btn'] = $this->lang->line('text_add_tab_content');
        $data['title'] = $this->lang->line('text_tab_content');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('tab_contentid') <= 14) || !$this->functions->check_permission('tab_content_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_tab_content'));
                redirect($this->path_to_view_admin . 'tab_content/');
            } else {
                if ($result = $this->tab_content->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_tab_content'));
                    redirect($this->path_to_view_admin . 'tab_content/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('tab_contentid') <= 14) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'tab_content/');
            } else {
                if ($result = $this->tab_content->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_tab_content'));
                    redirect($this->path_to_view_admin . 'tab_content/');
                }
            }
        }if ($this->input->post('submit_tab_content') == $this->lang->line('text_btn_submit')) {
            $data['tab_content'] = $this->input->post('tab_content');
            $this->form_validation->set_rules('tab_content', 'Tab Content', 'required');
            $settings_arr = array('tab_content');
            for ($i = 0; $i < count($settings_arr); $i++) {
                $settings_data = array('web_config_value' => $this->input->post($settings_arr[$i]));
                $this->db->where('web_config_name', $settings_arr[$i]);
                if ($query = $this->db->update('web_config', $settings_data)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_tab_content'));
                }
            }
        }
        $data['tab_content'] = $this->tab_content->getTabContent();
        $this->load->view($this->path_to_view_admin . 'tab_content_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 14) || !$this->functions->check_permission('tab_content_delete')) {                    
                echo $this->lang->line('text_err_delete_tab_content');                    
            } else {
                $this->tab_content->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 14) || !$this->functions->check_permission('tab_content')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->tab_content->changeMultiPublishStatus();            
            }       
        }
    }

    function insert() {
        $data['tab_content_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_tab_content');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_tab_content');
        $data['feature_tab'] = $this->tab_content->getFeatureTab();
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['features_tab_id'] = $this->input->post('features_tab_id');
            $data['content_title'] = $this->input->post('content_title');
            $data['content_text'] = $this->input->post('content_text');
            $data['content_icon'] = $this->input->post('content_icon');

            $this->form_validation->set_rules('features_tab_id', 'lang:text_feature_tab_name', 'required', array('required' => $this->lang->line('err_features_tab_id_req')));
            $this->form_validation->set_rules('content_title', 'lang:text_content_title', 'required', array('required' => $this->lang->line('err_content_title_req')));
            $this->form_validation->set_rules('content_text', 'lang:text_content_text', 'required', array('required' => $this->lang->line('err_content_text_req')));
            $this->form_validation->set_rules('content_icon', 'lang:text_content_icon', 'required', array('required' => $this->lang->line('err_content_icon_req')));
            // $this->form_validation->set_rules('content_icon', 'lang:text_content_icon', 'required|alpha_dash', array('required' => $this->lang->line('err_content_icon_req'),'alpha_dash' => $this->lang->line('err_content_icon_only_text')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'tab_content_addedit', $data);
            } else {
                if ($result = $this->tab_content->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_tab_content'));
                    redirect($this->path_to_view_admin . 'tab_content/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'tab_content_addedit', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('tab_content_delete')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_tab_content'));
            redirect($this->path_to_view_admin . 'tab_content');
        }

        $data['tab_content_addedit'] = true;
        $tab_content_id = $this->uri->segment('4');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['title'] = $this->lang->line('text_edit_tab_content');
        $data['feature_tab'] = $this->tab_content->getFeatureTab();
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('ftc_id') <= 14) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_tab_content'));
                redirect($this->path_to_view_admin . 'tab_content/');
            }
            $data['features_tab_id'] = $this->input->post('features_tab_id');
            $data['content_title'] = $this->input->post('content_title');
            $data['content_text'] = $this->input->post('content_text');
            $data['content_icon'] = $this->input->post('content_icon');
            
            $this->form_validation->set_rules('features_tab_id', 'lang:text_feature_tab_name', 'required', array('required' => $this->lang->line('err_features_tab_id_req')));
            $this->form_validation->set_rules('content_title', 'lang:text_content_title', 'required', array('required' => $this->lang->line('err_content_title_req')));
            $this->form_validation->set_rules('content_text', 'lang:text_content_text', 'required', array('required' => $this->lang->line('err_content_text_req')));
            $this->form_validation->set_rules('content_icon', 'lang:text_content_icon', 'required', array('required' => $this->lang->line('err_content_icon_req')));
            // $this->form_validation->set_rules('content_icon', 'lang:text_content_icon', 'required|alpha_dash', array('required' => $this->lang->line('err_content_icon_req'),'alpha_dash' => $this->lang->line('err_content_icon_only_text')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'tab_content_addedit', $data);
            } else {
                if ($result = $this->tab_content->update()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_tab_content'));
                    redirect($this->path_to_view_admin . 'tab_content/');
                }
            }
        } else {
            $data['tab_content_detail'] = $this->tab_content->getTabContentById($tab_content_id);
            $this->load->view($this->path_to_view_admin . 'tab_content_addedit', $data);
        }
    }

    function setDatatableTabContent() {
        header('Content-Type: application/json; charset=UTF-8');
        $requestData = $_REQUEST;
        $columns = array(            
            2 => 'f_tab_name',
            3 => 'content_title',
            4 => 'content_text',
            5 => 'content_icon',
            6 => 'content_status',
            7 => 'date_created',
        );

        $totalData = $this->tab_content->get_list_count_tab_content();
        $totalFiltered = $totalData;

        $sql = "SELECT * FROM features_tab_content as ftc LEFT JOIN features_tab as ft ON ft.f_id = ftc.features_tab_id ";
        if (!empty($requestData['search']['value'])) {
            $sql .= " WHERE f_tab_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  content_title LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  content_text LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  content_icon LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  content_status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  date_created LIKE '%" . $requestData['search']['value'] . "%' ";
        }
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query);

        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY ftc.date_created DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = '<input type="checkbox" value="'. $row['ftc_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['f_tab_name'];
            $nestedData[] = $row['content_title'];
            $nestedData[] = $row['content_text'];
            $nestedData[] = '<i class="' . $row['content_icon'] . '"></i>';
            if ($this->system->demo_user == 1 && $row['ftc_id'] <= 14) {

                if ($row['content_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top">Inactive</span>';
                }
            } else {
                if ($row['content_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmtab_contentlist,' . $row['ftc_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmtab_contentlist,' . $row['ftc_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'tab_content/edit/' . $row['ftc_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['ftc_id'] <= 14) {
                $delete = '<a  class="" disabled="disabled" data-original-title="Delete" data-placement="top"  style="font-size:18px;color:#007bff" ><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteTabContent(document.frmtab_contentlist,' . $row['ftc_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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
