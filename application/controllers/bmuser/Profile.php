<?php

class Profile extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        if ($this->member->front_logged_in !== true) {
            redirect('login');
        }

        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image');
        $this->profile_image_size_array = array(100 => 100);
    }

    function index() {
        $data['profilesetting'] = true;
        $data['title'] = $this->lang->line('text_my_profile');
        $data['breadcrumb_title'] = $this->lang->line('text_my_profile');
        $data['profile_detail'] = $this->getProfile();
        $data['country_data'] = $this->functions->getCountry();
        if ($this->input->post('profile_submit') == $this->lang->line('text_update_profile')) {
            if ($this->member->front_member_username == 'demouser' && $this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change'));
                redirect($this->path_to_default . 'profile/');
            }

            $data['first_name'] = $this->input->post('first_name');
            $data['last_name'] = $this->input->post('last_name');
            $data['user_name'] = $this->input->post('user_name');
            $data['email_id'] = $this->input->post('email_id');
            $data['mobile_no'] = $this->input->post('mobile_no');
            $data['dob'] = $this->input->post('dob');
            $data['gender'] = $this->input->post('gender');
//            $data['country_id'] = $this->input->post('country_id');
            $data['country_code'] = $this->input->post('country_code');

            $this->form_validation->set_rules('first_name', 'lang:text_first_name', 'required', array('required' => $this->lang->line('err_first_name_req')));
            $this->form_validation->set_rules('last_name', 'lang:text_last_name', 'required', array('required' => $this->lang->line('err_last_name_req')));
            $this->form_validation->set_rules('user_name', 'lang:text_user_name', 'required', array('required' => $this->lang->line('err_user_name_req')));
            $this->form_validation->set_rules('email_id', 'lang:text_email', 'required|callback_checkEmail1', array('required' => $this->lang->line('err_email_id_req')));
//            $this->form_validation->set_rules('country_id', 'lang:text_country', 'required', array('required' => $this->lang->line('err_country_req')));
            $this->form_validation->set_rules('country_code', 'lang:text_country_code', 'required', array('required' => $this->lang->line('err_country_code_req')));
            $this->form_validation->set_rules('mobile_no', 'lang:text_mobile_no', 'required|numeric|min_length[7]|max_length[15]|callback_checkMobile1', array('required' => $this->lang->line('err_mobile_no_req'), 'numeric' => $this->lang->line('err_mobile_no_number'), 'min_length' => $this->lang->line('err_mobile_no_min'), 'max_length' => $this->lang->line('err_mobile_no_max')));
            $this->form_validation->set_rules('profile_image', 'lang:text_logo', 'callback_file_profile_image');
            $this->form_validation->set_rules('user_template', 'lang:text_web_template', 'required', array('required' => $this->lang->line('err_user_template_req')));

            if ($this->form_validation->run() == FALSE) {                
                $this->load->view($this->path_to_view_default . 'my_profile', $data);
            } else {               
                if ($this->system->firebase_otp == 'yes')
                    $mobile_no = $this->input->post('old_mobile_no');
                else
                    $mobile_no = $this->input->post('mobile_no');
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
//                    'mobile_no' => $this->input->post('mobile_no'),
//                    'country_code' => $this->input->post('country_code'),
                    'email_id' => $this->input->post('email_id'),
                    'dob' => $this->input->post('dob'),
                    'gender' => $this->input->post('gender'),
                    'user_template' => $this->input->post('user_template'),
                );
                if ($this->system->firebase_otp !== 'yes') {
                    $data['country_code'] = $this->input->post('country_code');
                    $data['mobile_no'] = $this->input->post('mobile_no');
                }

                if ($_FILES['profile_image']['name'] == "") {                                                    
                    $profile_image = $this->input->post('old_profile_image');
                } else {                    
                    $thumb_sizes = $this->profile_image_size_array;
                    if (file_exists($this->profile_image . $this->input->post('old_profile_image'))) {
                        @unlink($this->profile_image . $this->input->post('old_profile_image'));
                    }
                    foreach ($thumb_sizes as $width => $height) {
                        if (file_exists($this->profile_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_profile_image'))) {
                            @unlink($this->profile_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_profile_image'));
                        }
                    }
                                        
                    $profile_image = 'member_' . rand() .'_'. $this->input->post('member_id') . '.' . pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
                    $config['file_name'] = $profile_image;
                    $config['upload_path'] = $this->profile_image;
                    $config['allowed_types'] = 'jpg|png|jpeg';
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('profile_image')) {
                        $error = array('error' => $this->upload->display_errors());                        
                    } else {
                        $image = $this->upload->data();                        
                       
                        foreach ($thumb_sizes as $key => $val) {
                            list($width_orig, $height_orig, $image_type) = getimagesize($this->profile_image . $profile_image);				                                                
                                                                    
                            if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                                $this->image->initialize($this->profile_image . $profile_image);                                                       
                                $this->image->resize($key, $val);
                                $this->image->save($this->profile_image . "thumb/" . $key . "x" . $val . "_" . $profile_image);
                            } else {
                                copy($this->profile_image . $profile_image, $this->profile_image . "thumb/" . $key . "x" . $val . "_" . $profile_image);
                            }
                        }

                    }
                }

                $data['profile_image'] = $profile_image;

                $this->db->where('member_id', $this->input->post('member_id'));
                if ($this->db->update('member', $data)) {
                    $this->session->set_flashdata('success', $this->lang->line('text_succ_profile'));
                    redirect($this->path_to_default . 'profile/');
                }
            }
        } elseif ($this->input->post('change_password') == "Change Password") {
            if ($this->member->front_member_username == 'demouser' && $this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_password'));
                redirect($this->path_to_default . 'profile/');
            }
            $data['old_password'] = $this->input->post('old_password');
            $data['new_password'] = $this->input->post('new_password');
            $data['c_passowrd'] = $this->input->post('c_passowrd');
            $this->form_validation->set_rules('old_password', 'lang:text_old_password', 'required|callback_checkOldPass', array('required' => $this->lang->line('err_old_password_req')));
            $this->form_validation->set_rules('new_password', 'lang:text_new_password', 'required|min_length[6]', array('required' => $this->lang->line('err_new_password_req'), 'min_length' => $this->lang->line('err_password_min')));
            $this->form_validation->set_rules('c_passowrd', 'lang:text_confirm_password', 'required|matches[new_password]', array('required' => $this->lang->line('err_c_passowrd_req'), 'min_length' => $this->lang->line('err_c_passowrd_equal')));
            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_default . 'my_profile', $data);
            } else {
                $array = array(
                    'password' => md5($this->input->post('new_password')),
                );
                $this->db->where('member_id', $this->member->front_member_id);
                if ($this->db->update('member', $array)) {
                    $this->session->set_flashdata('success', $this->lang->line('text_succ_change_password'));
                    redirect($this->path_to_default . 'profile/');
                }
            }
        } else {

            $this->load->view($this->path_to_view_default . 'my_profile', $data);
        }
    }

    public function file_profile_image() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['profile_image']['name']) && $_FILES['profile_image']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['profile_image']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_profile_image', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["profile_image"]["size"] > 2000000) {
                $this->form_validation->set_message('file_profile_image', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    public function sendOTP() {
        $curl = curl_init();
        $curl_data = array(
            "phoneNumber" => $this->input->post('country_code') . $this->input->post('mobile_no'),
            "recaptchaToken" => $this->input->post('grecaptcha_response')
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.googleapis.com/identitytoolkit/v3/relyingparty/sendVerificationCode?key=" . $this->system->firebase_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($curl_data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        if (isset($response->error)) {
            echo json_encode($response->error->message);
        } else {
            $this->session->set_userdata(array('check_mobile_sessionInfo' => $response->sessionInfo));
            echo json_encode(TRUE);
        }
    }

    public function verifyOTP() {
        $curl = curl_init();
        $curl_data = array(
            "sessionInfo" => $this->session->userdata('check_mobile_sessionInfo'),
            "code" => $this->input->post('otp')
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPhoneNumber?key=" . $this->system->firebase_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($curl_data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        if (isset($response->error)) {
            echo json_encode(FALSE);
        } else {
//            $country_id = $this->functions->getCountryCodeToID($this->input->post('otp_country_code'));

            $data = array(
                'mobile_no' => $this->input->post('otp_mobile'),
//                'country_id' => $country_id,
                'country_code' => $this->input->post('otp_country_code'),
            );
            $this->db->where('member_id', $this->member->front_member_id);
            $this->db->update('member', $data);
            $this->session->set_userdata('check_mobile_sessionInfo', '');
            echo json_encode(TRUE);
        }
    }

    function getProfile() {
        $this->db->select('*');
        $this->db->where('member_id', $this->member->front_member_id);
        $qry = $this->db->get('member');
        return $qry->row_array();
    }

    public function checkOldPass() {
        $this->db->select('*');
        $this->db->where('member_id', $this->member->front_member_id);
        $query = $this->db->get('member');
        $data = $query->row_array();
        if ($data['password'] != md5($this->input->post('old_password'))) {
            $this->form_validation->set_message('checkOldPass', $this->lang->line('err_old_password_valid'));
            return false;
        } else {
            return true;
        }
    }

    function checkMobile1() {
        
        $this->db->select('*');
        $this->db->where('mobile_no', $this->input->post('mobile_no'));
        $this->db->where('member_id != ', $this->input->post('member_id'));
        $query = $this->db->get('member');
        
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('checkMobile1', $this->lang->line('err_mobile_no_exist'));
            return false;
        } else {
            return true;
        }
    }

    function checkEmail1() {
        if ($this->member->front_login_via == '0') {
            $this->db->select('*');
            $this->db->where('email_id', $this->input->post('email_id'));
            $this->db->where('member_id != ', $this->input->post('member_id'));
            $this->db->where('login_via', '0');
            $query = $this->db->get('member');
            if ($query->num_rows() > 0) {
                $this->form_validation->set_message('checkEmail1', $this->lang->line('err_email_id_exist'));
                return false;
            } else {
                return true;
            }
        } else {
            $this->db->select('*');
            $this->db->where('email_id', $this->input->post('email_id'));
            $this->db->where('member_id != ', $this->input->post('member_id'));
            $this->db->where('login_via != ', '0');
            $query = $this->db->get('member');
            if ($query->num_rows() > 0) {
                $this->form_validation->set_message('checkEmail1', $this->lang->line('err_email_id_exist'));
                return false;
            } else {
                return true;
            }
        }
    }

    function checkMobile() {
        
        $this->db->select('*');
        $this->db->where('member_id != ', $this->member->front_member_id);
        $this->db->where('mobile_no', $this->input->get('mobile_no'));
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            echo json_encode(FALSE);
        } else {
            echo json_encode(TRUE);
        }
    }

    function checkEmail() {
        $this->db->select('*');
        $this->db->where('member_id != ', $this->member->front_member_id);
        $this->db->where('email_id', $this->input->get('email_id'));
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            echo json_encode(FALSE);
        } else {
            echo json_encode(TRUE);
        }
    }

}

?>
