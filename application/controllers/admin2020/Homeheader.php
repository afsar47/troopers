<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Homeheader extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('homeheader')) {
            redirect($this->path_to_view_admin . 'login');
        }

        $this->load->library('upload');
        $this->load->helper('file');        
        $this->load->library('image'); 
        $this->img_size_array = array(100 => 100);
        $this->con = $this->functions->mysql_connection();
    }

    function index() {
        $data['homeheader'] = true;
        $data['title'] = $this->lang->line('text_main_banner_setting');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_homeheader'));
                redirect($this->path_to_view_admin . 'homeheader/');
            }
            $thumb_sizes = $this->img_size_array;
            if ($_FILES['home_sec_bnr_image']['name'] == "") {
                $image_banner = $this->input->post('old_home_sec_bnr_image');
            } else {
                if (file_exists($this->page_banner . $this->input->post('old_home_sec_bnr_image'))) {
                    @unlink($this->page_banner . $this->input->post('old_home_sec_bnr_image'));
                }
                foreach ($thumb_sizes as $width => $height) {
                    if (file_exists($this->page_banner . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_home_sec_bnr_image'))) {
                        @unlink($this->page_banner . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_home_sec_bnr_image'));
                    }
                }
                $unique = $this->functions->GenerateUniqueFilePrefix();
                $image_banner = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['home_sec_bnr_image']['name']);
                $config['file_name'] = $image_banner;
                $config['upload_path'] = $this->page_banner;
                $config['allowed_types'] = 'jpg|png|jpeg';
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('home_sec_bnr_image')) {
                    $data['error'] = array('error' => $this->upload->display_errors());
                } else {                    
                    foreach ($thumb_sizes as $key => $val) {
                        
                        list($width_orig, $height_orig, $image_type) = getimagesize($this->page_banner . $image_banner);				                                                
                                                            
                        if ($width_orig != $key || $height_orig != $val) {                                                                                                                                           
                            $this->image->initialize($this->page_banner . $image_banner);                                                       
                            $this->image->resize($key, $val);
                            $this->image->save($this->page_banner . "thumb/" . $key . "x" . $val . "_" . $image_banner);
                        } else {
                            copy($this->page_banner . $image_banner, $this->page_banner . "thumb/" . $key . "x" . $val . "_" . $image_banner);
                        }                    
                    }
                }
            }
            $settings_data1 = array('web_config_value' => $image_banner);
            $this->db->where('web_config_name', 'home_sec_bnr_image');
            $query = $this->db->update('web_config', $settings_data1);
            if ($_FILES['home_sec_side_image']['name'] == "") {
                $image_side = $this->input->post('old_home_sec_side_image');
            } else {
                if (file_exists($this->screenshot_image . $this->input->post('old_home_sec_side_image'))) {
                    @unlink($this->screenshot_image . $this->input->post('old_home_sec_side_image'));
                }
                foreach ($thumb_sizes as $width => $height) {
                    if (file_exists($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_home_sec_side_image'))) {
                        @unlink($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_home_sec_side_image'));
                    }
                }
                $unique = $this->functions->GenerateUniqueFilePrefix();
                $image_side = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['home_sec_side_image']['name']);
                $config['file_name'] = $image_side;
                $config['upload_path'] = $this->screenshot_image;
                $config['allowed_types'] = 'jpg|png|jpeg';
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('home_sec_side_image')) {
                    $data['error'] = array('error' => $this->upload->display_errors());
                } else {                    
                    foreach ($thumb_sizes as $key => $val) {
                        list($width_orig, $height_orig, $image_type) = getimagesize($this->screenshot_image . $image_side);				                                                
                                                            
                        if ($width_orig != $key || $height_orig != $height) {                                                                                                                                           
                            $this->image->initialize($this->screenshot_image . $image_side);                                                       
                            $this->image->resize($key, $height);
                            $this->image->save($this->screenshot_image . "thumb/" . $key . "x" . $val . "_" . $image_side);
                        } else {
                            copy($this->screenshot_image . $image_side, $this->screenshot_image . "thumb/" . $key . "x" . $val . "_" . $image_side);
                        }                         
                    }
                }
            }
            $settings_data2 = array('web_config_value' => $image_side);
            $this->db->where('web_config_name', 'home_sec_side_image');
            $query = $this->db->update('web_config', $settings_data2);
            $data['home_sec_title'] = $this->input->post('home_sec_title');
            $data['home_sec_text'] = $this->input->post('home_sec_text');
            $data['home_sec_btn'] = $this->input->post('home_sec_btn');

            $this->form_validation->set_rules('home_sec_title', 'lang:text_title', 'required', array('required' => $this->lang->line('err_home_sec_title_req')));
            $this->form_validation->set_rules('home_sec_text', 'lang:text_sub_title', 'required', array('required' => $this->lang->line('err_home_sec_text_req')));
            $this->form_validation->set_rules('home_sec_btn', 'lang:text_button_text', 'required', array('required' => $this->lang->line('err_home_sec_btn_req')));
            $this->form_validation->set_rules('home_sec_side_image', 'lang:text_main_banner_image', 'callback_file_check');
            $this->form_validation->set_rules('home_sec_bnr_image', 'lang:text_image', 'callback_file_check1');

            $settings_arr = array('home_sec_title', 'home_sec_text', 'home_sec_btn');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'homeheader', $data);
            } else {
                for ($i = 0; $i < count($settings_arr); $i++) {
                    $settings_data = array('web_config_value' => $this->input->post($settings_arr[$i]));
                    $this->db->where('web_config_name', $settings_arr[$i]);
                    if ($query = $this->db->update('web_config', $settings_data)) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_homeheader'));
                    }
                }
                redirect($this->path_to_view_admin . 'homeheader/');
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'homeheader', $data);
        }
    }

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['home_sec_side_image']['name']) && $_FILES['home_sec_side_image']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['home_sec_side_image']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["home_sec_side_image"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    public function file_check1() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['home_sec_bnr_image']['name']) && $_FILES['home_sec_bnr_image']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['home_sec_bnr_image']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check1', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["home_sec_bnr_image"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check1', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

}
