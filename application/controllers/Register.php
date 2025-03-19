<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        $this->load->helper('string');
        $this->load->model('home_model', 'home');
    }

    public function index() {
    
        if($data['page'] = $this->functions->getPage('sign-up')) {
            $data['title'] = $data['page']['page_menutitle'];
            $data['page_title'] = $data['page']['page_title'];
        } else {
            $data['title'] = 'Sign UP';
            $data['page_title'] = 'Sign UP';
        }
        $data['register'] = true;
        if ($this->input->post('register') == $this->lang->line('text_btn_submit')) {
            $data['user_name'] = $this->input->post('user_name');
            $data['mobile_no'] = $this->input->post('mobile_no');
            $data['email_id'] = $this->input->post('email_id');
            $data['referral_code'] = $this->input->post('referral_code');
            $data['password'] = $this->input->post('password');
            $data['c_password'] = $this->input->post('c_password');
//            $data['country_id'] = $this->input->post('country_id');
            $data['country_code'] = $this->input->post('country_code');

            $this->form_validation->set_rules('user_name', $this->lang->line('text_user_name'), 'required|callback_checkUsername1', array('required' => $this->lang->line('err_new_password_req'), 'min_length[6]' => $this->lang->line('err_password_min')));
            $this->form_validation->set_rules('mobile_no', $this->lang->line('text_mobile_no'), 'required|numeric|min_length[7]|max_length[15]|callback_checkMobile1', array('required' => $this->lang->line('err_new_password_req'), 'min_length[6]' => $this->lang->line('err_password_min')));
            $this->form_validation->set_rules('email_id', $this->lang->line('text_email'), 'required|valid_email|callback_checkEmail1', array('required' => $this->lang->line('err_new_password_req'), 'min_length[6]' => $this->lang->line('err_password_min')));
            $this->form_validation->set_rules('referral_code', $this->lang->line('text_promo_code'), 'callback_checkReferralCode1', array('required' => $this->lang->line('err_new_password_req'), 'min_length[6]' => $this->lang->line('err_password_min')));
            $this->form_validation->set_rules('password', $this->lang->line('text_password'), 'required|min_length[6]', array('required' => $this->lang->line('err_new_password_req'), 'min_length[6]' => $this->lang->line('err_password_min')));
            $this->form_validation->set_rules('c_password', $this->lang->line('text_confirm_password'), 'required|matches[password]', array('required' => $this->lang->line('err_new_password_req'), 'min_length[6]' => $this->lang->line('err_password_min')));
//            $this->form_validation->set_rules('country_id', $this->lang->line('text_country'), 'required', array('required' => $this->lang->line('err_country_req')));
            $this->form_validation->set_rules('country_code', $this->lang->line('text_country_code'), 'required', array('required' => $this->lang->line('err_country_code_req')));

            if ($this->form_validation->run() == FALSE) {
                $data['country'] = $this->home->register();
                $this->load->view($this->path_to_view_front . 'register', $data);
            } else {
                if ($this->system->firebase_otp == 'yes') {
                    $session = array(
                        'user_name' => $this->input->post('user_name'),
                        'mobile_no' => $this->input->post('mobile_no'),
//                        'country_id' => $this->input->post('country_id'),
                        'country_code' => $this->input->post('country_code'),
                        'email_id' => $this->input->post('email_id'),
                        'referral_code' => $this->input->post('referral_code'),
                        'password' => $this->input->post('password'),
                        'g-recaptcha-response' => $this->input->post('g-recaptcha-response'),
                    );
                    $this->session->set_userdata($session);
                    redirect('register/verfiy');
                } else {
                    if ($result = $this->home->register()) {
                        $this->session->set_flashdata('success', 'You have registered successfully.');
                        redirect('login');
                    }
                }
            }
        } elseif ($this->input->post('submit_via') == $this->lang->line('text_btn_submit')) {
            $data['user_name'] = $this->input->post('user_name');
            $data['mobile_no'] = $this->input->post('mobile_no');
            $data['email_id'] = $this->input->post('email_id');
            $data['referral_code'] = $this->input->post('referral_code');
            $data['country_code'] = $this->input->post('country_code');
            $data['login_via'] = $this->input->post('login_via');
            $data['g_id'] = $this->input->post('g_id');

            $this->form_validation->set_rules('user_name', 'User Name', 'required|callback_checkUsername1');
            $this->form_validation->set_rules('mobile_no', 'Mobile', 'required|numeric|min_length[7]|max_length[15]|callback_checkMobile1');
            $this->form_validation->set_rules('email_id', 'Email', 'required|valid_email|callback_checkEmail1');
            $this->form_validation->set_rules('referral_code', 'Referral Code', 'callback_checkReferralCode1');
            $this->form_validation->set_rules('g_id', 'Id', 'required');
            $this->form_validation->set_rules('login_via', 'login via', 'required');
            $this->form_validation->set_rules('country_code', 'Country code', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Please enter proper detail');
                redirect('register');
            } else {
                if ($this->system->firebase_otp == 'yes') {
                    $session = array(
                        'user_name' => $this->input->post('user_name'),
                        'mobile_no' => $this->input->post('mobile_no'),
                        'country_code' => $this->input->post('country_code'),
                        'email_id' => $this->input->post('email_id'),
                        'referral_code' => $this->input->post('referral_code'),
                        'login_via' => $this->input->post('login_via'),
                        'g_id' => $this->input->post('g_id'),
                        'g-recaptcha-response' => $this->input->post('g-recaptcha-response'),
                    );
                    $this->session->set_userdata($session);
                    redirect('register/verfiy');
                } else {
                    if ($result = $this->home->register_via()) {
                        $this->session->set_flashdata('success', 'You have registered successfully.');
                        redirect('register');
                    }
                }
            }
            print_r($_POST);
            exit;
        } else {
            $data['country'] = $this->functions->getCountry();
            $this->load->view($this->path_to_view_front . 'register', $data);
        }
    }

    function register_google_fb() {
        $this->db->where('fb_id', $this->input->post('g_id'));
        if ($this->input->post('login_via') == 'Google')
            $this->db->where('login_via', '2');
        else if ($this->input->post('login_via') == 'FB')
            $this->db->where('login_via', '1');
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            echo json_encode('fail');
//            $member = $query->row_array();
//            if ($this->member->login($member['user_name'], $this->input->post('g_id')) == true) {
//                echo json_encode('success');
//            } else {
//                echo json_encode('fail');
//            }
        } else {
//            $session = array(
//                'user_name' => $this->input->post('user_name'),
//                'login_via' => $this->input->post('login_via'),
//                'email_id' => $this->input->post('email_id'),
//                'g_id' => $this->input->post('g_id'),
//            );
//            $this->session->set_userdata($session);
            echo json_encode('success');
//            $user_name = $this->generateUsername(str_replace(' ', '', explode(' ', $this->input->post('user_name'))[0]));
//            $api_token = uniqid() . base64_encode(random_string('alnum', 40));
//            if ($this->input->post('login_via') == 'Google')
//                $login_via = '2';
//            else if ($this->input->post('login_via') == 'FB')
//                $login_via = '1';
//
//            $email_id = $this->input->post('email_id');
//            if ($this->input->post('email_id') == '')
//                $email_id = '';
//            $member_data = array(
//                'user_name' => $user_name,
//                'email_id' => $email_id,
//                'first_name' => explode(' ', $this->input->post('user_name'))[0],
//                'last_name' => explode(' ', $this->input->post('user_name'))[1],
//                'password' => md5($this->input->post('g_id')),
//                'fb_id' => $this->input->post('g_id'),
//                'login_via' => $login_via,
//                'api_token' => $api_token,
//                'entry_from' => '1',);
//            $this->db->insert('member', $member_data);
//            $member_id = $this->db->insert_id();
//            if ($this->member->login($user_name, $this->input->post('g_id')) == true) {
//                echo json_encode('success');
//            } else {
//                echo json_encode('fail');
//            }
        }
    }

    function verfiy_OTP() {
        $data['title'] = 'OTP';
        $data['page_menutitle'] = 'OTP';
        $data['meta_description'] = 'OTP';
        $data['meta_keyword'] = 'OTP';
        $data['page_banner_image'] = '';
        $data['otp'] = true;
        $data['otp'] = $this->input->post('otp');
        $this->form_validation->set_rules('otp', 'OTP', 'required|callback_checkOTP1');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view($this->path_to_view_front . 'otp', $data);
        } else {
            if ($result = $this->home->register()) {
                $this->session->set_flashdata('success', 'You have registered successfully.');
                redirect('login');
            }
        }
    }

    function verfiy() {
        $data['title'] = 'OTP';
        $data['page_menutitle'] = 'OTP';
        $data['meta_description'] = 'OTP';
        $data['meta_keyword'] = 'OTP';
        $data['page_banner_image'] = '';
        $data['otp'] = true;
        if ($this->input->post('send') == $this->lang->line('text_btn_submit')) {
            $data['otp'] = $this->input->post('otp');
            $this->form_validation->set_rules('otp', 'OTP', 'required|callback_checkOTP1');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_front . 'otp', $data);
            } else {
                if ($result = $this->reg->register()) {
                    $this->session->set_flashdata('success', 'You have registered successfully.');
                    redirect('login');
                }
            }
        } else {
            $curl = curl_init();
            $curl_data = array(
                "phoneNumber" => $this->session->userdata('country_code') . $this->session->userdata('mobile_no'),
                "recaptchaToken" => $this->session->userdata('g-recaptcha-response')
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
            $data['response'] = json_decode($response);
            if (isset($data['response']->error)) {
                $this->session->set_flashdata('error', $data['response']->error->message);
                redirect('register');
            } else {
                $this->session->set_userdata(array('sessionInfo' => $data['response']->sessionInfo));
                $this->load->view($this->path_to_view_front . 'otp', $data);
            }
        }
    }

    function resend_otp() {
        $session = array('g-recaptcha-response' => $this->input->post('g-recaptcha-response'));
        $this->session->set_userdata($session);
        redirect('register/verfiy');
   }
   
    function checkOTP1() {
        $curl = curl_init();
        $curl_data = array(
            "sessionInfo" => $this->session->userdata('sessionInfo'),
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
            $this->form_validation->set_message('checkOTP1', $response->error->message);
            return false;
        } else {
            return true;
        }
    }

    function checkOTP() {
        $curl = curl_init();
        $curl_data = array(
            "sessionInfo" => $this->session->userdata('sessionInfo'),
            "code" => $this->input->get('otp')
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
            echo json_encode(TRUE);
        }
    }

    function checkUsername() {
        $this->db->select('*');
        $this->db->where('user_name', $this->input->get('user_name'));
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            echo json_encode(FALSE);
        } else {
            echo json_encode(TRUE);
        }
    }

    function checkUsername1() {
        $this->db->select('*');
        $this->db->where('user_name', $this->input->post('user_name'));
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('checkUsername1', 'User name already registered');
            return false;
        } else {
            return true;
        }
    }

    function checkMobile() {
        $this->db->select('*');
        $this->db->where('mobile_no', $this->input->get('mobile_no'));
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            echo json_encode(FALSE);
        } else {
            echo json_encode(TRUE);
        }
    }

    function checkMobile1() {
        $this->db->select('*');
        $this->db->where('mobile_no', $this->input->post('mobile_no'));
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('checkMobile1', 'Mobile Number already registered');
            return false;
        } else {
            return true;
        }
    }

    function checkEmail() {
        $this->db->select('*');
        $this->db->where('login_via', '0');
        $this->db->where('email_id', $this->input->get('email_id'));
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            echo json_encode(FALSE);
        } else {
            echo json_encode(TRUE);
        }
    }

    function checkEmail1() {
        $this->db->select('*');
        $this->db->where('login_via', '0');
        $this->db->where('email_id', $this->input->post('email_id'));
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('checkEmail1', 'Email already registered');
            return false;
        } else {
            return true;
        }
    }

    function checkReferralCode() {
        $this->db->select('*');
        $this->db->where('user_name', $this->input->get('referral_code'));
        $this->db->where('member_status', '1');
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            echo json_encode(TRUE);
        } else {
            echo json_encode(FALSE);
        }
    }

    function checkReferalOrNot($sponser_id) {
        if (!empty($sponser_id)) {
            $this->db->select('*');
            $this->db->where('user_name', $sponser_id);
            $this->db->where('member_status', '1');
            $query = $this->db->get('member');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function checkReferralCode1() {
        if ($this->input->post('referral_code') != '') {
            $this->db->select('*');
            $this->db->where('user_name', $this->input->post('referral_code'));
            $this->db->where('member_status', '1');
            $query = $this->db->get('member');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                $this->form_validation->set_message('checkReferralCode1', 'Please enter valid referral code');
                return false;
            }
        } else {
            return true;
        }
    }

}

?>