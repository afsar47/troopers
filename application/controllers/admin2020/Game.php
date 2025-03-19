<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Game extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('game')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image'); 

        $this->load->model($this->path_to_view_admin . 'Game_model', 'game');
    }

    function index() {
        $data['game'] = true;
        $data['btn'] = $this->lang->line('text_add_game');
        $data['title'] = $this->lang->line('text_game');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('gameid') <= 23) || !$this->functions->check_permission('game_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_game'));
                redirect($this->path_to_view_admin . 'game/');
            } else {
                if ($result = $this->game->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_game'));
                    redirect($this->path_to_view_admin . 'game/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('gameid') <= 23) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'game/');
            } else {
                if ($result = $this->game->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_game'));
                    redirect($this->path_to_view_admin . 'game/');
                }
            }
        }
        $data['game_data'] = $this->game->game_data();
        $this->load->view($this->path_to_view_admin . 'game_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 23) || !$this->functions->check_permission('game_delete')) {                    
                echo $this->lang->line('text_err_delete_game');                    
            } else {
                $this->game->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 23) || !$this->functions->check_permission('game')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->game->changeMultiPublishStatus();            
            }       
        }
    }

    function setDatatableGame() {
        $requestData = $_REQUEST;
        $columns = array(            
            2 => 'game_name',
			3 => 'game_type',
            4 => 'game_image',            
            5 => 'status',
            6 => 'date_created',
        );
        $totalData = $this->game->get_list_count_game();
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM game";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  game_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  game_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  game_image LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData[] = '<input type="checkbox" value="'. $row['game_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['game_name'];

            if ($row['game_type'] == '1') {
				$nestedData[] = '<span class="badge badge-warning"  data-placement="top">' . $this->lang->line('text_user_challenge') . '</span>';
			} else {
				$nestedData[] = '';
			}

            $nestedData[] = '<img src="' . base_url() . $this->game_image . "thumb/100x100_" . $row['game_image'] . '">';
            // if ($row['banned'] == '1') {
            //     $nestedData[] = '<span class="badge badge-danger" data-placement="top">Banned <i class="fa fa-ban"></i></span>';
            // } else {
            //     $nestedData[] = '';
            // }
            if ($this->system->demo_user == 1 && $row['game_id'] > 23) {
                if ($row['status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmgamelist,' . $row['game_id'] . ',0);">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmgamelist,' . $row['game_id'] . ',1);">Inactive</span>';
                }
            } else {
                if ($row['status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top">Inactive</span>';
                }
            }
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'game/edit/' . $row['game_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';

            if ($this->system->demo_user == 1 && $row['game_id'] > 23) {
                $delete = '<a class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteGame(document.frmgamelist,' . $row['game_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a disabled="disabled" class="" data-original-title="Delete" data-placement="top"  style="font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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
        if (isset($_FILES['game_image']['name']) && $_FILES['game_image']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['game_image']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["game_image"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    public function logo_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['game_logo']['name']) && $_FILES['game_logo']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['game_logo']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('logo_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["game_logo"]["size"] > 2000000) {
                $this->form_validation->set_message('logo_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    function insert() {
        $data['game_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_game');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_game');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {            

            $data['game_name'] = $this->input->post('game_name');
            $data['package_name'] = $this->input->post('package_name');
            $data['game_rules'] = $this->input->post('game_rules');
			$data['game_type'] = $this->input->post('game_type');
			$data['id_prefix'] = $this->input->post('id_prefix');
            // $data['banned'] = $this->input->post('banned');
            $this->form_validation->set_rules('game_image', 'lang:text_image', 'callback_file_check', array('required' => $this->lang->line('err_image_req')));
            $this->form_validation->set_rules('game_name', 'lang:text_game_name', 'required', array('required' => $this->lang->line('err_game_name_req')));
            $this->form_validation->set_rules('game_logo', 'lang:text_logo', 'callback_logo_check', array('required' => $this->lang->line('err_image_req')));
            $this->form_validation->set_rules('package_name', 'lang:text_package_name', 'required', array('required' => $this->lang->line('err_package_name_req')));
            $this->form_validation->set_rules('game_rules', 'lang:text_game_rules', 'required', array('required' => $this->lang->line('err_game_rules_req')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'game_addedit', $data);
            } else {
                if ($result = $this->game->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_game'));
                    redirect($this->path_to_view_admin . 'game/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'game_addedit', $data);
        }
    }

    function edit() {

        if(!$this->functions->check_permission('game_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_game'));
            redirect($this->path_to_view_admin . 'game');
        }

        $data['game_addedit'] = true;
        $game_id = $this->uri->segment('4');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['title'] = $this->lang->line('text_edit_game');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('game_id') <= 23) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_game'));
                redirect($this->path_to_view_admin . 'game/');
            }
            $data['game_name'] = $this->input->post('game_name');
            $data['package_name'] = $this->input->post('package_name');
            $data['game_rules'] = $this->input->post('game_rules');
			$data['game_type'] = $this->input->post('game_type');
			$data['id_prefix'] = $this->input->post('id_prefix');
            // $data['banned'] = $this->input->post('banned');

            $this->form_validation->set_rules('game_image', 'lang:text_image', 'callback_file_check');
            $this->form_validation->set_rules('game_name', 'lang:text_game_name', 'required', array('required' => $this->lang->line('err_game_name_req')));
            $this->form_validation->set_rules('game_logo', 'lang:text_logo', 'callback_logo_check');
            $this->form_validation->set_rules('package_name', 'lang:text_package_name', 'required', array('required' => $this->lang->line('err_package_name_req')));
            $this->form_validation->set_rules('game_rules', 'lang:text_game_rules', 'required', array('required' => $this->lang->line('err_game_rules_req')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'game_addedit', $data);
            } else {
                if ($result = $this->game->update()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_game'));
                    redirect($this->path_to_view_admin . 'game/');
                }
            }
        } else {
            $data['game_detail'] = $this->game->getgameById($game_id);
            $this->load->view($this->path_to_view_admin . 'game_addedit', $data);
        }
    }

    public function getGameRules() {
        $game_id = $this->uri->segment('4');
        $data['game_rules'] = $this->game->getgameById($game_id)['game_rules'];
        echo json_encode($data);
    }

}
