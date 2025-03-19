<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class How_to_play extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }

        if(!$this->functions->check_permission('how_to_play')) {
            redirect($this->path_to_view_admin . 'login');
        }
        
        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image_lib');
        $this->img_size_array = array(100 => 100);
        $this->load->model($this->path_to_view_admin . '/Howtoplay_model', 'htpm');
        $this->con = $this->functions->mysql_connection();
    }

    function index() {
        $data['howtoplay'] = true;
        $data['btn'] = $this->lang->line('text_add_howtoplay');
        $data['title'] = $this->lang->line('text_howtoplay');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('htpcid') <= 3) || !$this->functions->check_permission('how_to_play_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete'));
                redirect($this->path_to_view_admin . 'how_to_play/');
            } else {
                if ($result = $this->htpm->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_howtoplay'));
                    redirect($this->path_to_view_admin . 'how_to_play/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('htpcid') <= 3) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'how_to_play/');
            } else {
                if ($result = $this->htpm->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_howtoplay'));
                    redirect($this->path_to_view_admin . 'how_to_play/');
                }
            }
        }if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change'));
                redirect($this->path_to_view_admin . 'how_to_play/');
                exit;
            }
            $data['htp_title'] = $this->input->post('htp_title');
            $data['htp_text'] = $this->input->post('htp_text');

            $this->form_validation->set_rules('htp_title', 'lang:text_title', 'required', array('required' => $this->lang->line('err_htp_title_req')));
            $this->form_validation->set_rules('htp_text', 'lang:text_sub_title', 'required', array('required' => $this->lang->line('err_htp_text_req')));

            $settings_arr = array('htp_title', 'htp_text');
            for ($i = 0; $i < count($settings_arr); $i++) {
                $settings_data = array('web_config_value' => $this->input->post($settings_arr[$i]));
                $this->db->where('web_config_name', $settings_arr[$i]);
                if ($query = $this->db->update('web_config', $settings_data)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_err_change_howtoplay'));
                }
            }
            redirect($this->path_to_view_admin . 'how_to_play/');
        }
        $this->load->view($this->path_to_view_admin . 'howtoplay_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 3) || !$this->functions->check_permission('how_to_play_delete')) {                    
                echo $this->lang->line('text_err_delete');                    
            } else {
                $this->htpm->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 3) || !$this->functions->check_permission('how_to_play')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->htpm->changeMultiPublishStatus();            
            }       
        }
    }

    function insert() {
        $thumb_sizes = $this->img_size_array;
        $data['htp_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_howtoplay');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_howtoplay');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $thumb_sizes = $this->img_size_array;
            $data['htp_content_title'] = $this->input->post('htp_content_title');
            $data['htp_content_text'] = $this->input->post('htp_content_text');
            $data['htp_order'] = $this->input->post('htp_order');

            $this->form_validation->set_rules('htp_content_title', 'lang:text_edit_htp_content_title', 'required', array('required' => $this->lang->line('err_htp_content_title_req')));
            $this->form_validation->set_rules('htp_order', 'lang:text_edit_htp_content_order', 'required|numeric', array('required' => $this->lang->line('err_dp_order_req'), 'numeric' => $this->lang->line('err_number')));
            $this->form_validation->set_rules('htp_content_text', 'lang:text_edit_htp_content_text', 'required', array('required' => $this->lang->line('err_htp_content_text_req')));
            $this->form_validation->set_rules('htp_content_image', 'lang:text_edit_htp_content_image', 'callback_file_check');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'howtoplay_addedit', $data);
            } else {
                if ($result = $this->htpm->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_howtoplay'));
                    redirect($this->path_to_view_admin . 'how_to_play/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'howtoplay_addedit', $data);
        }
    }

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['htp_content_image']['name']) && $_FILES['htp_content_image']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['htp_content_image']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["htp_content_image"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    function edit() {
        if(!$this->functions->check_permission('how_to_play_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_change'));
            redirect($this->path_to_view_admin . 'how_to_play');
        }

        $thumb_sizes = $this->img_size_array;
        $data['htp_addedit'] = true;
        $htp_content_id = $this->uri->segment('4');
        $data['Action'] = $this->lang->line('text_edit_howtoplay');
        $data['title'] = $this->lang->line('text_add_howtoplay');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('htp_content_id') <= 3) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change'));
                redirect($this->path_to_view_admin . 'how_to_play/');
            } else {
                $data['htp_content_title'] = $this->input->post('htp_content_title');
                $data['htp_content_text'] = $this->input->post('htp_content_text');
                $data['htp_order'] = $this->input->post('htp_order');

                $this->form_validation->set_rules('htp_content_title', 'lang:text_edit_htp_content_title', 'required', array('required' => $this->lang->line('err_htp_content_title_req')));
                $this->form_validation->set_rules('htp_order', 'lang:text_edit_htp_content_order', 'required|numeric', array('required' => $this->lang->line('err_dp_order_req'), 'numeric' => $this->lang->line('err_number')));
                $this->form_validation->set_rules('htp_content_text', 'lang:text_edit_htp_content_text', 'required', array('required' => $this->lang->line('err_htp_content_text_req')));
                $this->form_validation->set_rules('htp_content_image', 'lang:text_edit_htp_content_image', 'callback_file_check');
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'howtoplay_addedit', $data);
                } else {
                    if ($result = $this->htpm->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_howtoplay'));
                        redirect($this->path_to_view_admin . 'how_to_play/');
                    }
                }
            }
        } else {
            $data['htp_content_detail'] = $this->htpm->getHTPContentById($htp_content_id);
            $this->load->view($this->path_to_view_admin . 'howtoplay_addedit', $data);
        }
    }

    function setDatatableHTPContent() {
        header('Content-Type: application/json; charset=UTF-8');
        $requestData = $_REQUEST;
        $columns = array(           
            2 => 'htp_content_title',
            3 => 'htp_content_text',
            4 => 'htp_content_image',
            5 => 'htp_content_status',
            6 => 'date_created'
        );
        $totalData = $this->htpm->get_list_count_htpcontent();
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM howtoplay_content";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  htp_content_title LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  htp_content_text LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  htp_content_status LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData[] = '<input type="checkbox" value="'. $row['htp_content_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['htp_content_title'];
            $nestedData[] = substr($row['htp_content_text'], 0, 70) . '...';
            $nestedData[] = '<img src="' . base_url() . $this->screenshot_image . "thumb/100x100_" . $row['htp_content_image'] . '">';
            if ($this->system->demo_user == 1 && $row['htp_content_id'] <= 3) {
                if ($row['htp_content_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger">Inactive</span>';
                }
            } else {
                if ($row['htp_content_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmhtpclist,' . $row['htp_content_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmhtpclist,' . $row['htp_content_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }

            $nestedData[] = $row['date_created'];
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'how_to_play/edit/' . $row['htp_content_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['htp_content_id'] <= 3) {
                $delete = '<a  class="" disabled="disabled" data-original-title="Delete" data-placement="top"  style="font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeletehtpcontent(document.frmhtpclist,' . $row['htp_content_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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
