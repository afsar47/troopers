<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Features extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        /* index of the admin. Default: Dashboard; On No Login Session: Back to login page. */
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('features')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image'); 

        $this->img_size_array = array(100 => 100);
        $this->load->model($this->path_to_view_admin . '/Features_model', 'features');
        $this->con = $this->functions->mysql_connection();
    }

    function index() {
        $data['features'] = true;
        $data['btn'] = $this->lang->line('text_add_features');
        $data['title'] = $this->lang->line('text_features');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('fid') <= 3) || !$this->functions->check_permission('features_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_feature'));
                redirect($this->path_to_view_admin . 'features/');
            } else {
                if ($result = $this->features->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_feature'));
                    redirect($this->path_to_view_admin . 'features/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('fid') <= 3) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'features/');
            } else {
                if ($result = $this->features->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_feature'));
                    redirect($this->path_to_view_admin . 'features/');
                }
            }
        }if ($this->input->post('submit_features') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_feature'));
                redirect($this->path_to_view_admin . 'features/');
                exit;
            }
            $data['features_title'] = $this->input->post('features_title');
            $data['features_text'] = $this->input->post('features_text');

            $this->form_validation->set_rules('game_image', 'lang:text_section_title', 'required', array('required' => $this->lang->line('err_features_title_req')));
            $this->form_validation->set_rules('game_image', 'lang:text_section_sub_title', 'required', array('required' => $this->lang->line('err_features_text_req')));

            $settings_arr = array('features_title', 'features_text');
            for ($i = 0; $i < count($settings_arr); $i++) {
                $settings_data = array('web_config_value' => $this->input->post($settings_arr[$i]));
                $this->db->where('web_config_name', $settings_arr[$i]);
                if ($query = $this->db->update('web_config', $settings_data)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_feature'));
                }
            }
            redirect($this->path_to_view_admin . 'features/');
        }
        $this->load->view($this->path_to_view_admin . 'features_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 3) || !$this->functions->check_permission('features_delete')) {                    
                echo $this->lang->line('text_err_delete_feature');                    
            } else {
                $this->features->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 3) || !$this->functions->check_permission('features')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->features->changeMultiPublishStatus();            
            }       
        }
    }

    function insert() {
        $thumb_sizes = $this->img_size_array;
        $data['features_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_features');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_features');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $thumb_sizes = $this->img_size_array;

            $data['f_tab_name'] = $this->input->post('f_tab_name');
            $data['f_tab_title'] = $this->input->post('f_tab_title');
            $data['f_tab_text'] = $this->input->post('f_tab_text');
            $data['f_tab_img_position'] = $this->input->post('f_tab_img_position');
            $data['f_tab_order'] = $this->input->post('f_tab_order');

            $this->form_validation->set_rules('f_tab_name', 'lang:text_feature_tab_name', 'required', array('required' => $this->lang->line('err_f_tab_name_req')));
            $this->form_validation->set_rules('f_tab_img_position', 'lang:text_feature_tab_text', 'required', array('required' => $this->lang->line('err_f_tab_img_position_req')));
            $this->form_validation->set_rules('f_tab_order', 'lang:text_display_order', 'required|numeric', array('required' => $this->lang->line('err_dp_order_req'), 'numeric' => $this->lang->line('err_number')));
            $this->form_validation->set_rules('f_tab_img', 'lang:text_image', 'callback_file_check');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'features_addedit', $data);
            } else {
                if ($result = $this->features->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_feature'));
                    redirect($this->path_to_view_admin . 'features/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'features_addedit', $data);
        }
    }

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['f_tab_img']['name']) && $_FILES['f_tab_img']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['f_tab_img']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["f_tab_img"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    function edit() {
        if(!$this->functions->check_permission('features_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_feature'));
            redirect($this->path_to_view_admin . 'features');
        }

        $thumb_sizes = $this->img_size_array;
        $data['features_addedit'] = true;
        $features_id = $this->uri->segment('4');

        $data['Action'] = $this->lang->line('text_action_edit');
        $data['title'] = $this->lang->line('text_edit_features');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('f_id') <= 3) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_feature'));
                redirect($this->path_to_view_admin . 'features/');
            } else {
                $data['f_tab_name'] = $this->input->post('f_tab_name');
                $data['f_tab_title'] = $this->input->post('f_tab_title');
                $data['f_tab_text'] = $this->input->post('f_tab_text');
                $data['f_tab_order'] = $this->input->post('f_tab_order');
                $data['f_tab_img_position'] = $this->input->post('f_tab_img_position');

                $this->form_validation->set_rules('f_tab_name', 'lang:text_feature_tab_name', 'required', array('required' => $this->lang->line('err_f_tab_name_req')));
                $this->form_validation->set_rules('f_tab_img_position', 'lang:text_feature_tab_text', 'required', array('required' => $this->lang->line('err_f_tab_img_position_req')));
                $this->form_validation->set_rules('f_tab_order', 'lang:text_display_order', 'required|numeric', array('required' => $this->lang->line('err_dp_order_req'), 'numeric' => $this->lang->line('err_number')));
                $this->form_validation->set_rules('f_tab_img', 'lang:text_image', 'callback_file_check');

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'features_addedit', $data);
                } else {
                    if ($result = $this->features->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_feature'));
                        redirect($this->path_to_view_admin . 'features/');
                    }
                }
            }
        } else {
            $data['features_detail'] = $this->features->getfeaturesById($features_id);
            $this->load->view($this->path_to_view_admin . 'features_addedit', $data);
        }
    }

    function setDatatablefeatures() {
        header('Content-Type: application/json; charset=UTF-8');
        $requestData = $_REQUEST;

        $columns = array(            
            2 => 'f_tab_name',
            3 => 'f_tab_title',
            4 => 'f_tab_text',
            5 => 'f_tab_image',
            6 => 'f_tab_img_position',
            7 => 'f_tab_status',
            8 => 'date_created'
        );

        $totalData = $this->features->get_list_count_features();
        $totalFiltered = $totalData;

        $sql = "SELECT * FROM features_tab";

        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  f_tab_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  f_tab_title LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  f_tab_text LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  f_tab_img_position LIKE '%" . $requestData['search']['value'] . "%' ";
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

            $nestedData[] = '<input type="checkbox" value="'. $row['f_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['f_tab_name'];
            $nestedData[] = $row['f_tab_title'];
            $nestedData[] = substr($row['f_tab_text'], 0, 70) . '...';
            $nestedData[] = '<img src="' . base_url() . $this->screenshot_image . "thumb/100x100_" . $row['f_tab_image'] . '">';
            $nestedData[] = $row['f_tab_img_position'];
            if ($this->system->demo_user == 1 && $row['f_id'] <= 3) {
                if ($row['f_tab_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger">Inactive</span>';
                }
            } else {
                if ($row['f_tab_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmfeatureslist,' . $row['f_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmfeatureslist,' . $row['f_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }
            $nestedData[] = $row['date_created'];
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'features/edit/' . $row['f_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($row['f_id'] <= 3) {
                $delete = '<a  class="" disabled="disabled" data-original-title="Delete" data-placement="top" style="font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeletefeatures(document.frmfeatureslist,' . $row['f_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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
