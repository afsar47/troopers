<?php

class Custom_notification extends CI_Controller {

    function __construct() {
        parent::__construct();
        ob_clean();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('custom_notification')) {
            redirect($this->path_to_view_admin . 'login');
        }

        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image_lib');
    }

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['notification_image']['name']) && $_FILES['notification_image']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['notification_image']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["notification_image"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    function index() {
        $data['custom_notification'] = true;
        $data['title'] = $this->lang->line('text_one_signal_notification');
        $data['member_list'] = $this->get_list_member();
        $data['game_list'] = $this->game_data();
       
        $data['company_name'] = $this->system->company_name;
        $data['send_to'] = 'all';
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['notification_title'] = $this->input->post('notification_title');
            $data['message'] = $this->input->post('message');            
            $data['send_to'] = $this->input->post('send_to');
            $data['member'] = $this->input->post('member');
            $data['multi_member_from'] = $this->input->post('multi_member_from');
            $data['multi_member_to'] = $this->input->post('multi_member_to');
            
            $this->form_validation->set_rules('notification_title', 'lang:text_title', 'required', array('required' => $this->lang->line('err_notification_title_req')));
            $this->form_validation->set_rules('message', 'lang:text_message', 'required', array('required' => $this->lang->line('err_message_req')));
            $this->form_validation->set_rules('notification_image', 'lang:text_image', 'callback_file_check');

            if($this->input->post('send_to') == 'single_member'){
                $this->form_validation->set_rules('member', 'lang:text_member', 'required', array('required' => $this->lang->line('err_member_id')));
            }
            if($this->input->post('send_to') == 'multi_member'){

                $max_member_id = $this->db->select('max(member_id) as max_id')->get('member')->row()->max_id;
                
                $this->form_validation->set_rules('multi_member_from', 'lang:text_member', 'integer|greater_than_equal_to[0]|less_than['. $this->input->post("multi_member_to") .']');
                $this->form_validation->set_rules('multi_member_to', 'lang:text_member', 'integer|greater_than['. $this->input->post("multi_member_from").']|less_than_equal_to['. $max_member_id .']');
            }

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'custom_notification_addedit', $data);
            } else {
                $image_url = '';
                if ($_FILES['notification_image']['name'] == "") {
                    $image = '';
                } else {
                    $unique = $this->functions->GenerateUniqueFilePrefix();
                    $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['notification_image']['name']);
                    $config['file_name'] = $image;
                    $config['upload_path'] = $this->notification_image;
                    $config['allowed_types'] = 'jpg|png|jpeg';

                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('notification_image')) {
                        $data['error'] = array('error' => $this->upload->display_errors());
                    } else {
                        $image_url = base_url() . $this->notification_image . $image;
                    }
                }
                
                if ($this->system->demo_user == 1)
                    $this->functions->sendMessage('Welcome to ' . $data['company_name'], $data['company_name'] .' push notification test', $image_url);
                else
                    $this->functions->sendMessage($this->input->post('notification_title'), $this->input->post('message'), $image_url);

                $this->session->set_flashdata('notification', $this->lang->line('text_succ_notification'));
                redirect($this->path_to_view_admin . 'custom_notification/');
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'custom_notification_addedit', $data);
        }
    }

    public function get_list_member() {
        $this->db->select('*');
        $this->db->where('player_id !=','');
        $this->db->where('push_noti','1');
        $this->db->where('member_status','1');
        $this->db->order_by("member_id", "Desc");
        $query = $this->db->get('member');
        return $query->result_array();
    }
    
    
     public function game_data() {
        $this->db->select('m_id');
        $this->db->select('match_name');
        $this->db->where('match_status','1');
        $query = $this->db->get('matches');
        return $query->result_array();
    }

}

?>
