<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ludo_challenge extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('ludo_challenge')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image_lib');
        $this->load->model($this->path_to_view_admin . 'Ludo_challenge_model', 'ludo_challenge');
    }

    function index() {
        $data['ludo_challenge'] = true;
        $data['title'] = $this->lang->line('text_ludo_challenge');
        
        if ($this->input->post('action') == "delete") {
            if ($this->system->demo_user == 1 || !$this->functions->check_permission('ludo_challenge_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_ludo_challenge'));
                redirect($this->path_to_view_admin . 'ludo_challenge/');
            } else {
                if ($result = $this->ludo_challenge->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_ludo_challenge'));
                    redirect($this->path_to_view_admin . 'ludo_challenge/');
                }
            }
        } 
        $data['ludo_challenge_data'] = $this->ludo_challenge->ludo_challenge_data();
				
        $this->load->view($this->path_to_view_admin . 'ludo_challenge_manage', $data);
    }
    
    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if ($this->system->demo_user == 1 || !$this->functions->check_permission('ludo_challenge_delete')) {                    
                echo $this->lang->line('text_err_delete_ludo_challenge');                    
            } else {
                $this->ludo_challenge->multiDelete();                       
            }           
        } 
    }

    function download_result() {
        if(!$this->functions->check_permission('luo_challenge_view')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_download_result'));
            redirect($this->path_to_view_admin . 'game');
        }
        
        $data['ludo_challenge'] = true;
        $data['challenge_manage'] = true;
        
        $challenge_result_upload_id = $this->uri->segment('4');
        
        $this->db->where("challenge_result_upload_id", $challenge_result_upload_id);
        
        $result_detail = $this->db->get("challenge_result_upload")->row();
        
        $this->load->helper('download');
        
        $result_image = explode(';base64,',$result_detail->result_image);
        
        $extension = explode('/',$result_image[0]);
        
        $name = 'result_image_' . $challenge_result_upload_id . '.' . $extension[1];
        
        $img = str_replace(' ', '+', $result_image[1]);
        
        $img_decoded = base64_decode($img);
        
        if ($img_decoded === false) {
            $this->session->set_flashdata('notification', 'Failed to Image Decoding');
        } else {
            
            header('Content-Disposition: attachment;filename="'. $name .'"');
            header('Content-Type: application/force-download'); 
            echo $img_decoded;
        }

        return;
    }
    
    function challenge_detail() {
        $data['ludo_challenge'] = true;
        $data['challenge_manage'] = true;
        $data['title'] = $this->lang->line('text_ludo_challenge');
        $data['text_uploded_result'] = $this->lang->line('text_uploded_result');
        $data['text_decide_result'] = $this->lang->line('text_decide_result');
        
        $ludo_challenge_id = $this->uri->segment('4');
        
        $data['challenge_detail'] = $this->ludo_challenge->getchallengeById($ludo_challenge_id);
        
        $data['room_code_detail'] = $this->ludo_challenge->getRoomCodeById($ludo_challenge_id);
        
        $data['result_by_addedd_detail'] = $this->ludo_challenge->getChallengeAddeddUplodedResult($ludo_challenge_id);
        
        $data['result_by_accepted_detail'] = $this->ludo_challenge->getChallengeAcceptedUplodedResult($ludo_challenge_id);
        
        $this->load->view($this->path_to_view_admin . 'ludo_challenge_manage_detail', $data);
            
    }

    function upload_result() {

        if(!$this->functions->check_permission('ludo_challenge_view')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_upload_challenge_result'));
            redirect($this->path_to_view_admin . 'game');
        }

        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_upload_challenge_result'));
                redirect($this->path_to_view_admin . 'ludo_challenge/');
            } else {
                $this->ludo_challenge->update_result();
                $this->session->set_flashdata('notification', $this->lang->line('text_succ_upload_challenge_result'));
                redirect($this->path_to_view_admin . 'ludo_challenge/');
            }
        }
    }
    
    function setDatatable() {
        $requestData = $_REQUEST;
        $columns = array(            
            2 => 'auto_id',
			3 => 'game_name',
            4 => 'date_created',
            5 => 'challenge_status',
        );
        $totalData = $this->ludo_challenge->get_list_count_ludo_challenge();
        $totalFiltered = $totalData;
        $sql = "SELECT l.*,g.game_name FROM ludo_challenge as l";
		
		$sql .= " left join game as g on l.game_id = g.game_id";
		
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  ludo_challenge_id LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR  game_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  auto_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  challenge_status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  l.date_created LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData[] = '<input type="checkbox" value="'. $row['ludo_challenge_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['auto_id'];
			$nestedData[] = $row['game_name'];
            $nestedData[] = $row['date_created'];
            
                if ($row['challenge_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="Active" data-placement="top">Active</span>';
                } elseif ($row['challenge_status'] == '2') {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Canceled" data-placement="top">Canceled</span>';
                } elseif ($row['challenge_status'] == '3') {
                    $nestedData[] = '<span class="badge badge-warning" data-original-title="Completed" data-placement="top">Completed</span>';
                } elseif ($row['challenge_status'] == '4') {
                    $nestedData[] = '<span class="badge badge-info" data-original-title="Completed" data-placement="top">Pending</span>';
                }
           
            $edit = '<a style="font-size:18px;" data-original-title="Detail" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'ludo_challenge/challenge_detail/' . $row['ludo_challenge_id'] . '><i class="fa fa-eye"></i></a>&nbsp;';
            
            if ($this->system->demo_user == 0) {
                $delete = '<a class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteLudoChallenge(document.frmludochallengelist,' . $row['ludo_challenge_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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

    

}
