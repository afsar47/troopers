<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Image extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('image')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image_lib');
        $this->load->model($this->path_to_view_admin . 'Image_model', 'image');
    }

    function index() {
        $data['image'] = true;
        $data['btn'] = $this->lang->line('text_add_image');
        $data['title'] = $this->lang->line('text_image');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('imageid') <= 2) || !$this->functions->check_permission('image_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_image'));
                redirect($this->path_to_view_admin . 'image/');
            } else {
                if ($result = $this->image->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_image'));
                    redirect($this->path_to_view_admin . 'image/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('imageid') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'image/');
            } else {
                if ($result = $this->image->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_image'));
                    redirect($this->path_to_view_admin . 'image/');
                }
            }
        }
        $this->load->view($this->path_to_view_admin . 'image_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 2) || !$this->functions->check_permission('image_delete')) {                    
                echo $this->lang->line('text_err_delete_image');                    
            } else {
                $this->image->multiDelete();                       
            }           
        }
    }

    function setDatatableImage() {
        $requestData = $_REQUEST;
        $columns = array(            
            2 => 'image_title',
            3 => 'image_name',
            4 => 'created_date',
        );
        $totalData = $this->image->get_list_count_image();
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM image";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  image_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  image_title LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  image_name LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData[] = '<input type="checkbox" value="'. $row['image_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['image_title'];
            $nestedData[] = '<img src="' . base_url() . $this->select_image . "thumb/100x100_" . $row['image_name'] . '">';
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'image/edit/' . $row['image_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['image_id'] <= 2) {
                $delete = '<a  class="" data-original-title="Delete" disabled="disabled" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteImage(document.frmimagelist,' . $row['image_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['image_name']['name']) && $_FILES['image_name']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['image_name']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["image_name"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    public function logo_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['image_logo']['name']) && $_FILES['image_logo']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['image_logo']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('logo_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["image_logo"]["size"] > 2000000) {
                $this->form_validation->set_message('logo_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    function insert() {
        $data['image_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_image');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_image');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['image_title'] = $this->input->post('image_title');
            $data['image_name'] = $this->input->post('image_name');

            $this->form_validation->set_rules('image_name', 'lang:text_image', 'callback_file_check', array('required' => $this->lang->line('err_image_req')));
            $this->form_validation->set_rules('image_title', 'lang:text_image_title', 'required', array('required' => $this->lang->line('err_image_title_req')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'image_addedit', $data);
            } else {
                if ($result = $this->image->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_image'));
                    redirect($this->path_to_view_admin . 'image/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'image_addedit', $data);
        }
    }

    function edit() {

        if(!$this->functions->check_permission('image_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_image'));
            redirect($this->path_to_view_admin . 'image');
        }

        $data['image_addedit'] = true;
        $image_id = $this->uri->segment('4');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['title'] = $this->lang->line('text_edit_image');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('imageid') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_image'));
                redirect($this->path_to_view_admin . 'image/');
            } else {
                $data['image_title'] = $this->input->post('image_title');
                $data['image_name'] = $this->input->post('image_name');

                $this->form_validation->set_rules('image_name', 'lang:text_image', 'callback_file_check');
                $this->form_validation->set_rules('image_title', 'lang:text_image_title', 'required', array('required' => $this->lang->line('err_image_title_req')));
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'image_addedit', $data);
                } else {
                    if ($result = $this->image->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_image'));
                        redirect($this->path_to_view_admin . 'image/');
                    }
                }
            }
        } else {
            $data['image_detail'] = $this->image->getimageById($image_id);
            $this->load->view($this->path_to_view_admin . 'image_addedit', $data);
        }
    }

    public function getImageRules() {
        $image_id = $this->uri->segment('4');
        $data['image_rules'] = $this->image->getimageById($image_id)['image_rules'];
        echo json_encode($data);
    }

}
