<?php

class Profilesetting extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('profilesetting') && !$this->functions->check_permission('changepassword')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->load->library('upload');
        $this->load->helper('file');        
        $this->load->library('image'); 
        $this->logo_size_array = array(100 => 100, 189 => 40);
        $this->favicon_size_array = array(100 => 100, 40 => 40);
    }

    function index() {

        $data['profilesetting'] = true;
        $data['title'] = $this->lang->line('text_profile_setting');
        $data['country_data'] = $this->functions->getCountry();
        if ($this->input->post('profile_submit') == $this->lang->line('text_btn_submit')) {
            if(!$this->functions->check_permission('profilesetting')) {
                redirect($this->path_to_view_admin . 'login');
            }

            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_profile'));
                redirect($this->path_to_view_admin . 'profilesetting/');
            }
            $data['username'] = $this->input->post('username');
            $data['useremail'] = $this->input->post('useremail');

            $this->form_validation->set_rules('username', 'lang:text_user_name', 'required', array('required' => $this->lang->line('err_username_req')));
            $this->form_validation->set_rules('useremail', 'lang:text_email', 'required|valid_email', array('required' => $this->lang->line('err_email_id_req'), 'valid_email' => $this->lang->line('err_email_id_valid')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'profilesetting_addedit', $data);
            } else {
                $data = array(
                    'name' => $this->input->post('username'),
                    'email' => $this->input->post('useremail'),
                );
                $this->db->where('id', $this->input->post('userid'));
                if ($this->db->update('admin', $data)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_profile'));
                    redirect($this->path_to_view_admin . 'profilesetting/');
                }
            }
        } elseif ($this->input->post('social_submit') == $this->lang->line('text_btn_submit')) {
            if(!$this->functions->check_permission('profilesetting')) {
                redirect($this->path_to_view_admin . 'login');
            }
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_social_set'));
                redirect($this->path_to_view_admin . 'profilesetting/');
            }
            $data['fb_link'] = $this->input->post('fb_link');
            $data['insta_link'] = $this->input->post('insta_link');
            $data['twitter_link'] = $this->input->post('twitter_link');
            $data['google_link'] = $this->input->post('google_link');

            $this->form_validation->set_rules('fb_link', 'lang:text_fb_link', 'valid_url', array('valid_url' => $this->lang->line('err_fb_link_valid')));
            $this->form_validation->set_rules('insta_link', 'lang:text_insta_link', 'valid_url', array('valid_url' => $this->lang->line('err_insta_link_valid')));
            $this->form_validation->set_rules('twitter_link', 'lang:text_twitter_link', 'valid_url', array('valid_url' => $this->lang->line('err_twitter_link_valid')));
            $this->form_validation->set_rules('google_link', 'lang:text_gp_link', 'valid_url', array('valid_url' => $this->lang->line('err_google_link_valid')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'profilesetting_addedit', $data);
            } else {
                $settings_arr = array('fb_link', 'insta_link', 'twitter_link', 'google_link');
                for ($i = 0; $i < count($settings_arr); $i++) {
                    $settings_data = array('web_config_value' => $this->input->post($settings_arr[$i]));
                    $this->db->where('web_config_name', $settings_arr[$i]);
                    if ($query = $this->db->update('web_config', $settings_data)) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_cnt_upd'));
                    }
                }
                $this->load->view($this->path_to_view_admin . 'profilesetting_addedit', $data);
            }
        } elseif ($this->input->post('contact_submit') == $this->lang->line('text_btn_submit')) {
            if(!$this->functions->check_permission('profilesetting')) {
                redirect($this->path_to_view_admin . 'login');
            }
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_company_set'));
                redirect($this->path_to_view_admin . 'profilesetting/');
            }
            $data['company_name'] = $this->input->post('company_name');
            $data['comapny_phone'] = $this->input->post('comapny_phone');
            $data['comapny_country_code'] = $this->input->post('comapny_country_code');
            $data['company_email'] = $this->input->post('company_email');
            $data['company_street'] = $this->input->post('company_street');
            $data['company_address'] = $this->input->post('company_address');
            $data['company_time'] = $this->input->post('company_time');
            $data['copyright_text'] = $this->input->post('copyright_text');

            $this->form_validation->set_rules('company_logo', 'lang:text_logo', 'callback_file_check_logo');
            $this->form_validation->set_rules('company_favicon', 'lang:text_favicon', 'callback_file_check_icon');
            $this->form_validation->set_rules('company_email', 'lang:text_email', 'required|valid_email', array('required' => $this->lang->line('err_email_id_req'), 'valid_email' => $this->lang->line('err_email_id_valid')));

//            $this->form_validation->set_rules('company_name', 'lang:text_company_name', 'required', array('required' => $this->lang->line('err_company_name_valid')));
//            if ($this->input->post('comapny_phone') != '')
//                $this->form_validation->set_rules('comapny_country_code', 'lang:text_phone', 'required', array('required' => $this->lang->line('err_mobile_no_number')));
//            $this->form_validation->set_rules('company_time', 'lang:text_time', 'required', array('required' => $this->lang->line('err_company_time_valid')));
//            $this->form_validation->set_rules('copyright_text', 'lang:text_copyright_text', 'required', array('required' => $this->lang->line('err_copyright_text_valid')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'profilesetting_addedit', $data);
            } else {

                if ($_FILES['company_logo']['name'] == "") {
                    $logo = $this->input->post('old_company_logo');
                } else {
                    $thumb_sizes = $this->logo_size_array;
                    if (file_exists($this->company_image . $this->input->post('old_company_logo'))) {
                        @unlink($this->company_image . $this->input->post('old_company_logo'));
                    }
                    foreach ($thumb_sizes as $width => $height) {
                        if (file_exists($this->company_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_company_logo'))) {
                            @unlink($this->company_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_company_logo'));
                        }
                    }
                    $unique = $this->functions->GenerateUniqueFilePrefix();
                    $logo = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['company_logo']['name']);
                    $config['file_name'] = $logo;
                    $config['upload_path'] = $this->company_image;
                    $config['allowed_types'] = 'jpg|png|jpeg';
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('company_logo')) {
                        $data['error'] = array('error' => $this->upload->display_errors());
                    } else {
                        $data['image'] = $this->upload->data();
                        foreach ($thumb_sizes as $key => $val) {

                            list($width_orig, $height_orig, $image_type) = getimagesize($this->company_image . $logo);				                                                
                                                            
                            if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                           
                                $this->image->initialize($this->company_image . $logo);                                                       
                                $this->image->resize($key, $val);
                                $this->image->save($this->company_image . "thumb/" . $key . "x" . $val . "_" . $logo);
                            } else {
                                copy($this->company_image . $logo, $this->company_image . "thumb/" . $key . "x" . $val . "_" . $logo);
                            }                            
                        }
                    }
                }
                $settings_data1 = array('web_config_value' => $logo);
                $this->db->where('web_config_name', 'company_logo');
                $query = $this->db->update('web_config', $settings_data1);
                if ($_FILES['company_favicon']['name'] == "") {
                    $favicon = $this->input->post('old_company_favicon');
                } else {
                    $thumb_sizes = $this->favicon_size_array;
                    if (file_exists($this->company_favicon . $this->input->post('old_company_favicon'))) {
                        @unlink($this->company_favicon . $this->input->post('old_company_favicon'));
                    }
                    foreach ($thumb_sizes as $width => $height) {
                        if (file_exists($this->company_favicon . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_company_favicon'))) {
                            @unlink($this->company_favicon . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_company_favicon'));
                        }
                    }
                    $unique = $this->functions->GenerateUniqueFilePrefix();
                    $favicon = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['company_favicon']['name']);
                    $config['file_name'] = $favicon;
                    $config['upload_path'] = $this->company_favicon;
                    $config['allowed_types'] = 'jpg|png|jpeg';
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('company_favicon')) {
                        $data['error'] = array('error' => $this->upload->display_errors());
                    } else {
                        $data['image'] = $this->upload->data();
                        foreach ($thumb_sizes as $key => $val) {

                            list($width_orig, $height_orig, $image_type) = getimagesize($this->company_favicon . $favicon);				                                                
                                                            
                            if ($width_orig != $key || $height_orig != $height) {                                                                                                                                           
                                $this->image->initialize($this->company_favicon . $favicon);                                                       
                                $this->image->resize($key, $height);
                                $this->image->save($this->company_favicon . "thumb/" . $key . "x" . $val . "_" . $favicon);
                            } else {
                                copy($this->company_favicon . $favicon, $this->company_favicon . "thumb/" . $key . "x" . $val . "_" . $favicon);
                            }                           
                        }
                    }
                }
                $settings_data1 = array('web_config_value' => $favicon);
                $this->db->where('web_config_name', 'company_favicon');
                $query = $this->db->update('web_config', $settings_data1);
                $settings_arr = array('company_name', 'comapny_phone', 'comapny_country_code', 'company_email', 'company_address', 'company_street', 'company_time', 'copyright_text', 'company_about');
                for ($i = 0; $i < count($settings_arr); $i++) {
                    $settings_data = array('web_config_value' => $this->input->post($settings_arr[$i]));
                    $this->db->where('web_config_name', $settings_arr[$i]);
                    if ($query = $this->db->update('web_config', $settings_data)) {
                        
                    }
                }
                $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_company_set'));
                $this->load->view($this->path_to_view_admin . 'profilesetting_addedit', $data);
            }
        } elseif ($this->input->post('password_update') == $this->lang->line('text_btn_update')) {
            if(!$this->functions->check_permission('changepassword')) {
                redirect($this->path_to_view_admin . 'login');
            }
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_password'));
                redirect($this->path_to_view_admin . 'profilesetting/');
            }
            $data['old_password'] = $this->input->post('old_password');
            $data['new_password'] = $this->input->post('new_password');
            $data['c_passowrd'] = $this->input->post('c_passowrd');
            
            $this->form_validation->set_rules('old_password', 'lang:text_old_password', 'required|callback_checkOldPass', array('required' => $this->lang->line('err_old_password_req')));
            $this->form_validation->set_rules('new_password', 'lang:text_new_password', 'required', array('required' => $this->lang->line('err_new_password_req')));
            $this->form_validation->set_rules('c_passowrd', 'lang:text_confirm_password', 'required|callback_checkNewpass', array('required' => $this->lang->line('err_c_passowrd_req')));
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'profilesetting_addedit', $data);
            } else {
                if ($result = $this->update()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_password'));
                    redirect($this->path_to_view_admin . 'profilesetting');
                }
            }
        } else {
            $data['profile_detail'] = $this->getProfile($this->session->userdata('id'));
            $this->load->view($this->path_to_view_admin . 'profilesetting_addedit', $data);
        }
    }

    public function file_check_logo() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['company_logo']['name']) && $_FILES['company_logo']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['company_logo']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check_logo', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["company_logo"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check_logo', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    public function file_check_icon() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['company_favicon']['name']) && $_FILES['company_favicon']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['company_favicon']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check_icon', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["company_favicon"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check_icon', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    function getProfile($id) {
        $this->db->select('*');
        $this->db->where('id', $id);
        $qry = $this->db->get('admin');
        return $qry->row_array();
    }

    function update() {
        $array = array(
            'password' => md5($this->input->post('new_password')),
            'default_login' => '1'
        );
        $this->db->where('id', $this->session->userdata('id'));
        if ($this->db->update('admin', $array)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkOldPass() {
        $this->db->select('*');
        $this->db->where('id', $this->session->userdata('id'));
        $query = $this->db->get('admin');
        $data = $query->row_array();
        if ($data['password'] != md5($this->input->post('old_password'))) {
            $this->form_validation->set_message('checkOldPass', $this->lang->line('err_old_password_valid'));
            return false;
        } else {
            return true;
        }
    }

    public function checkNewpass() {
        if ($this->input->post('new_password') != $this->input->post('c_passowrd')) {
            $this->form_validation->set_message('checkNewpass', $this->lang->line('err_c_passowrd_equal'));
            return false;
        } else {
            return true;
        }
    }

}

?>
