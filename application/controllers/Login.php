<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        $this->load->helper('string');
    }

//    login page load
    public function index() {
        if ($this->input->post('login') == $this->lang->line('text_btn_submit')) {

            $data['user_name'] = $this->input->post('user_name');
            $data['password'] = $this->input->post('password');
            $this->member->_destroy_session();
            if ($this->member->front_logged_in) {
                $data['account'] = true;
                $data['title'] = $this->lang->line('text_home');
                redirect($this->path_to_default . 'play');
            } else {
                $name = $this->input->post('user_name');
                $password = $this->input->post('password');
                if ($this->member->login($name, $password) == true) {
                    $this->session->set_flashdata('success', $this->lang->line('text_succ_login'));
                    redirect($this->path_to_default . 'play');
                } else {
                    redirect('login');
                }
            }
        } elseif ($this->input->post('g_id') != '') {
            $data['country_code'] = $this->input->post('country_code');
            $data['mobile_no'] = $this->input->post('mobile_no');
            $data['email_id'] = $this->input->post('email_id');
            $data['referral_code'] = $this->input->post('referral_code');
//            $data['login_via'] = $this->input->post('login_via');
//            $data['g_id'] = $this->input->post('g_id');
//            $this->form_validation->set_rules('user_name', 'User Name', 'required|callback_checkUsername1');
            $this->form_validation->set_rules('mobile_no', 'Mobile', 'required|numeric|min_length[7]|max_length[15]|callback_checkMobile1');
//            $this->form_validation->set_rules('email_id', 'Email', 'required|valid_email|callback_checkEmail1');
            $this->form_validation->set_rules('referral_code', 'Referral Code', 'callback_checkReferralCode1');
//            $this->form_validation->set_rules('g_id', 'Id', 'required');
//            $this->form_validation->set_rules('login_via', 'login via', 'required');
            $this->form_validation->set_rules('country_code', 'Country code', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', $this->lang->line('err_proper_detail'));
                redirect('login');
            } else {
                if ($this->system->firebase_otp == 'yes') {
                    $session = array(
                        'user_name' => $this->input->post('user_name'),
                        'mobile_no' => $this->input->post('mobile_no'),
                        'member_id' => $this->input->post('member_id'),
                        'country_code' => $this->input->post('country_code'),
                        'email_id' => $this->input->post('email_id'),
                        'referral_code' => $this->input->post('referral_code'),
//                        'login_via' => $this->input->post('login_via'),
                        'g_id' => $this->input->post('g_id'),
                        'g-recaptcha-response' => $this->input->post('g-recaptcha-response'),
                    );
                    $this->session->set_userdata($session);
                    redirect('login/verfiy');
                } else {
                    $referral_id = '';
                    if ($this->input->post('referral_code') != '')
                        $referral_id = $this->GetRefrralNo($this->input->post('referral_code'));
                    $email_id = $this->input->post('email_id');
                    if ($this->input->post('email_id') == '')
                        $email_id = '';

                    $country_id = $this->functions->getCountryCodeToID($this->input->post('country_code'));
                    $member_data = array(
                        'mobile_no' => $this->input->post('mobile_no'),
                        'country_code' => $this->input->post('country_code'),
                        'country_id' => $country_id,
                        'referral_id' => $referral_id,);
                    if ($this->input->post('email_id') == '')
                        $member_data['email_id'] = $this->input->post('email_id');
                    $this->db->where('member_id', $this->input->post('member_id'));
                    if ($this->db->update('member', $member_data)) {
                        if ($this->input->post('referral_code') != '') {
                            if ($this->system->active_referral == '1') {
                                $this->load->library('user_agent');
                                $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                                $ip = $this->input->ip_address();
                                $wallet_balance = $this->system->referral;
                                $join_data = array(
                                    'join_money' => $wallet_balance);
                                $this->db->where('member_id', $this->input->post('member_id'));
                                $this->db->update('member', $join_data);
                                $referral_data = array(
                                    'member_id' => $this->input->post('member_id'),
                                    'from_mem_id' => $referral_id,
                                    'referral_amount' => $this->system->referral,
                                    'referral_status' => '1',
                                    'entry_from' => '2',
                                    'referral_dateCreated' => date('Y-m-d H:i:s')
                                );
                                $this->db->insert('referral', $referral_data);
                                $acc_data = array(
                                    'member_id' => $this->input->post('member_id'),
                                    'from_mem_id' => $referral_id,
                                    'deposit' => $this->system->referral,
                                    'withdraw' => 0,
                                    'join_money' => $wallet_balance,
                                    'win_money' => 0,
                                    'note' => 'Register Referral',
                                    'note_id' => '3',
                                    'entry_from' => '2',
                                    'new_user' => 'No',
                                    'ip_detail' => $ip,
                                    'browser' => $browser,
                                    'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                                );
                                $this->db->insert('accountstatement', $acc_data);
                            }
                        }
                        if ($this->member->login($this->input->post('user_name'), $this->input->post('g_id')) == true) {
                            $this->session->set_flashdata('success', $this->lang->line('text_succ_login'));
                            redirect($this->path_to_default . 'play');
                        } else {
                            redirect('login');
                        }
                    }
                }
            }
        }
        if ($this->member->front_logged_in) {
            redirect($this->path_to_default . 'play');
        } else {

            if($data['page'] = $this->functions->getPage('login')) {
                $data['title'] = $data['page']['page_menutitle'];
                $data['page_title'] = $data['page']['page_title'];
            } else {
                $data['title'] = '';
                $data['page_title'] = '';
            }
            
            $data['login'] = true;
            $data['country'] = $this->functions->getCountry();
            $this->load->view($this->path_to_view_front . 'login', $data);
        }
    }

//    check mobile jquery validation
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

//    check mobile server side validation
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

//    check email jquery validation
    function checkEmail() {
        $this->db->select('*');
        if ($this->input->get('login_via') == 'FB')
            $this->db->where('login_via', '1');
        elseif ($this->input->get('login_via') == 'Google')
            $this->db->where('login_via', '2');
        else
            $this->db->where('login_via', '0');
        $this->db->where('member_id !=', $this->input->get('member_id'));
        $this->db->where('email_id', $this->input->get('email_id'));
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            echo json_encode(FALSE);
        } else {
            echo json_encode(TRUE);
        }
    }

//    check email server side validation
    function checkEmail1() {
        $this->db->select('*');
        if ($this->input->post('login_via') == 'FB')
            $this->db->where('login_via', '1');
        elseif ($this->input->post('login_via') == 'Google')
            $this->db->where('login_via', '2');
        else
            $this->db->where('login_via', '0');
        $this->db->where('member_id !=', $this->input->post('member_id'));
        $this->db->where('email_id', $this->input->post('email_id'));
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('checkEmail1', 'Email already registered');
            return false;
        } else {
            return true;
        }
    }

//    check referral code jquery validation
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

//    check referral code server side validation
    function checkReferralCode1() {
        if ($this->input->post('referral_code') != '') {
            $this->db->select('*');
            $this->db->where('user_name', $this->input->post('referral_code'));
            $this->db->where('member_status', '1');
            $query = $this->db->get('member');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                $this->form_validation->set_message('checkReferralCode1', $this->lang->line('err_promo_code'));
                return false;
            }
        } else {
            return true;
        }
    }

    public function GetRefrralNo($promo_code) {
        $this->db->select('*');
        $this->db->where('user_name', $promo_code);
        $query = $this->db->get('member');
        return $query->row_array()['member_id'];
    }

//    mobile verification OTP page load
    function verfiy() {
        $data['title'] = 'OTP';
        $data['page_menutitle'] = 'OTP';
        $data['meta_description'] = 'OTP';
        $data['meta_keyword'] = 'OTP';
        $data['page_banner_image'] = '';
        $data['otp'] = true;
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
            redirect('login');
        } else {
            $this->session->set_userdata(array('sessionInfo' => $data['response']->sessionInfo));
            $this->load->view($this->path_to_view_front . 'login_otp', $data);
        }
    }

//    fb-google login otp verified or not
    function verfiyvia_OTP() {
        $data['title'] = $this->lang->line('text_otp');
        $data['page_menutitle'] = $this->lang->line('text_otp');
        $data['meta_description'] = $this->lang->line('text_otp');
        $data['meta_keyword'] = $this->lang->line('text_otp');
        $data['page_banner_image'] = '';
        $data['otp'] = true;
        $data['otp'] = $this->input->post('otp');
//        $this->form_validation->set_rules('otp', $this->lang->line('text_otp'), 'required|callback_checkViaOTP1');
//        if ($this->form_validation->run() == FALSE) {
//
//            $this->load->view($this->path_to_view_front . 'login_otp', $data);
//        } else {
        $referral_id = '';
        if ($this->session->userdata('referral_code') != '')
            $referral_id = $this->GetRefrralNo($this->session->userdata('referral_code'));
        $email_id = $this->session->userdata('email_id');
        if ($this->session->userdata('email_id') == '')
            $email_id = '';
        $country_id = $this->functions->getCountryCodeToID($this->session->userdata('country_code'));
        $member_data = array(
            'mobile_no' => $this->session->userdata('mobile_no'),
            'country_code' => $this->session->userdata('country_code'),
            'country_id' => $country_id,
            'referral_id' => $referral_id,
            'new_user' => 'No');
        if ($this->session->userdata('email_id') != '')
            $member_data['email_id'] = $this->session->userdata('email_id');

        $this->db->where('member_id', $this->session->userdata('member_id'));
        if ($this->db->update('member', $member_data)) {


            if ($this->session->userdata('referral_code') != '') {
                if ($this->system->active_referral == '1') {
                    $this->load->library('user_agent');
                    $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                    $ip = $this->input->ip_address();
                    $wallet_balance = $this->system->referral;
                    $join_data = array(
                        'join_money' => $wallet_balance);
                    $this->db->where('member_id', $this->session->userdata('member_id'));
                    $this->db->update('member', $join_data);
                    $referral_data = array(
                        'member_id' => $this->session->userdata('member_id'),
                        'from_mem_id' => $referral_id,
                        'referral_amount' => $this->system->referral,
                        'referral_status' => '1',
                        'entry_from' => '2',
                        'referral_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('referral', $referral_data);
                    $acc_data = array(
                        'member_id' => $this->session->userdata('member_id'),
                        'from_mem_id' => $referral_id,
                        'deposit' => $this->system->referral,
                        'withdraw' => 0,
                        'join_money' => $wallet_balance,
                        'win_money' => 0,
                        'note' => 'Register Referral',
                        'note_id' => '3',
                        'entry_from' => '2',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('accountstatement', $acc_data);
                }
            }
            if ($this->member->login($this->session->userdata('user_name'), $this->session->userdata('g_id')) == true) {
                $this->session->set_flashdata('success', $this->lang->line('text_succ_login'));
                redirect($this->path_to_default . 'play');
            } else {
                redirect('login');
            }
        }
//        }
    }

//    check otp fb-google login
    function checkViaOTP1() {
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
            $this->form_validation->set_message('checkViaOTP1', $response->error->message);
            return false;
        } else {
            return true;
        }
    }

//    google - fb login
    function login_google_fb() {
        $this->db->where('fb_id', $this->input->post('g_id'));
        if ($this->input->post('login_via') == 'Google')
            $this->db->where('login_via', '2');
        else if ($this->input->post('login_via') == 'FB')
            $this->db->where('login_via', '1');
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            $member = $query->row_array();
            if ($member['mobile_no'] != '') {
                if ($this->member->login($member['user_name'], $this->input->post('g_id')) == true) {
                    $data['member'] = $member;
                    $data['status'] = 'success';
                    echo json_encode($data);
                    exit;
                } else {
                    $data['member'] = $member;
                    $data['status'] = 'fail';
                    echo json_encode($data);
                    exit;
                }
            } else {
                $data['member'] = $member;
                $data['status'] = 'fail';
                echo json_encode($data);
                exit;
            }
        } else {
            $user_name = $this->generateUsername(str_replace(' ', '', explode(' ', $this->input->post('user_name'))[0]));
            $api_token = uniqid() . base64_encode(random_string('alnum', 40));
            if ($this->input->post('login_via') == 'Google')
                $login_via = '2';
            else if ($this->input->post('login_via') == 'FB')
                $login_via = '1';

            $email_id = $this->input->post('email_id');
            if ($this->input->post('email_id') == '')
                $email_id = '';
            $member_data = array(
                'user_name' => $user_name,
                'email_id' => $email_id,
                'first_name' => explode(' ', $this->input->post('user_name'))[0],
                'last_name' => explode(' ', $this->input->post('user_name'))[1],
                'mobile_no' => '',
                'password' => md5($this->input->post('g_id')),
                'fb_id' => $this->input->post('g_id'),
                'login_via' => $login_via,
                'api_token' => $api_token,
                'entry_from' => '2',
                'new_user' => 'Yes',
                'created_date' => date('Y-m-d H:i:s')
            );
            $this->db->insert('member', $member_data);
            $member_id = $this->db->insert_id();
            $member_data['member_id'] = $member_id;
            $data['member'] = $member_data;
            $data['status'] = 'fail';
            echo json_encode($data);
            exit;
        }
    }

//    generate username
    function generateUsername($user_name) {
        $chars = "0123456789";
        $r_str = '';
        for ($i = 0; $i < 6; $i++) {
            $r_str .= substr($chars, rand(0, strlen($chars)), 1);
        }
        $new_user_name = $user_name . $r_str;

        $this->db->where('user_name', $new_user_name);
        $query = $this->db->get('member');

        if ($query->num_rows() > 0) {
            $this->generateUsername($user_name);
        } else {
            return $new_user_name;
        }
    }

//    send otp for forgot password
    function send_otp() {

        if ($this->input->post('forgot') == $this->lang->line('text_btn_submit')) {

            $data['email_id'] = $this->input->post('email_mobile');
            if ($this->system->msg91_otp == '0' || $this->system->msg91_otp == 0) {
                $this->form_validation->set_rules('email_mobile', 'lang:text_email', 'required|valid_email', array('required' => $this->lang->line('err_email_id_req'), 'valid_email' => $this->lang->line('err_email_id_valid')));
            } else {
                $this->form_validation->set_rules('email_mobile', 'lang:text_email_or_mobile', 'required', array('required' => $this->lang->line('err_email_mobile_no_req')));
            }
            if ($this->form_validation->run() == FALSE) {
                if ($this->system->msg91_otp == '0' || $this->system->msg91_otp == 0)
                    $this->session->set_flashdata('error', $this->lang->line('err_email_not_exist'));
                else
                    $this->session->set_flashdata('error', $this->lang->line('err_email_mobile_not_exist'));
                redirect('login');
            } else {
                $this->db->select('*');
                $this->db->where('login_via', '0');
                $this->db->where('email_id', $this->input->post('email_mobile'));
                $this->db->or_where('mobile_no', $this->input->post('email_mobile'));
                $this->db->join('country as c', 'c.p_code = m.country_code','left');
                $query = $this->db->get('member as m');
                if ($query->num_rows() > 0) {
                    $member = $query->row_array();
                    $otp = $this->generate_otp(6);
                    $this->session->set_userdata('forgot_member_otp', $otp);
                    $this->session->set_userdata('forgot_member_id', $member['member_id']);
                    if ($member['email_id'] == $this->input->post('email_mobile')) {
                        $this->load->library('email');
                        $this->load->library('parser');
                        $config = Array(
                            'protocol' => 'smtp',
                            'smtp_host' => $this->system->smtp_host,
                            'smtp_port' => $this->system->smtp_port,
                            'smtp_user' => $this->system->smtp_user,
                            'smtp_pass' => urldecode($this->system->smtp_pass),
                            'mailtype' => 'html',
                            'charset' => 'iso-8859-1'
                        );
                        $this->email->initialize($config);
                        $this->email->set_mailtype("html");
                        $this->email->set_newline("\r\n");
                        $this->email->from($this->system->smtp_user, $this->system->company_name);
                        $this->email->to($this->input->post('email_mobile'));
                        $this->email->subject('Password Recover');
                        $message = "<html>
                            <head>
                            <title>Password Recover </title>
                            </head>
                            <body>
                            <p>Your verification otp is : $otp</p>                            
                            </body>
                            </html>";
                        $this->email->message($message);
                        if ($this->email->send()) {
                            $data['title'] = $this->lang->line('text_otp');
                            $data['page_menutitle'] = $this->lang->line('text_otp');
                            $data['meta_description'] = $this->lang->line('text_otp');
                            $data['meta_keyword'] = $this->lang->line('text_otp');
                            $data['page_banner_image'] = '';
                            $data['otp'] = true;
                            $this->session->set_flashdata('success', $this->lang->line('text_succ_credential_email'));
                            $this->load->view($this->path_to_view_front . 'forgot_otp', $data);
                        } else {
                            redirect('login');
                        }

//                        $to = $this->input->post('email_mobile');
//                        $subject = "Password Recover";
//                        $message = "<html>
//                            <head>
//                            <title>Password Recover </title>
//                            </head>
//                            <body>
//                            <p>Your verification otp is : $otp</p>                            
//                            </body>
//                            </html>";
//                        $company_email = $this->system->company_email;
//                        $headers = "From: $company_email \r\n";
//                        $headers .= "MIME-Version: 1.0\r\n";
//                        $headers .= "Content-type: text/html\r\n";
//
//                        if (mail($to, $subject, $message, $headers)) {
//                            $data['title'] = 'OTP';
//                            $data['page_menutitle'] = 'OTP';
//                            $data['meta_description'] = 'OTP';
//                            $data['meta_keyword'] = 'OTP';
//                            $data['page_banner_image'] = '';
//                            $data['otp'] = true;
//                            $this->session->set_flashdata('success', 'Your credential is send in mail.Please check your email.');
//                            $this->load->view($this->path_to_view_front . 'forgot_otp', $data);
//                        } else {
//                            redirect('login');
//                        }                        
                    } elseif ($member['mobile_no'] == $this->input->post('email_mobile')) {
                        $message = "Your verification code is : $otp";
                        $m_number = $member['p_code'] . $member['mobile_no'];
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?sender=" . $this->system->msg91_sender . "&route=" . $this->system->msg91_route . "&mobiles=" . $m_number . "&authkey=" . $this->system->msg91_authkey . "&encrypt=0&country=" . $member['p_code'] . "&message=" . urlencode($message) . "&response=json",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "GET",
                            CURLOPT_SSL_VERIFYHOST => 0,
                            CURLOPT_SSL_VERIFYPEER => 0,
                        ));
                        $response = curl_exec($curl);
                        $response = json_decode($response);
                        $err = curl_error($curl);
                        curl_close($curl);
                        if ($response->type == 'success') {
                            $data['title'] = $this->lang->line('text_otp');
                            $data['page_menutitle'] = $this->lang->line('text_otp');
                            $data['meta_description'] = $this->lang->line('text_otp');
                            $data['meta_keyword'] = $this->lang->line('text_otp');
                            $data['page_banner_image'] = '';
                            $data['otp'] = true;
                            $this->session->set_flashdata('success', $this->lang->line('text_succ_credential_mobile'));
                            $this->load->view($this->path_to_view_front . 'forgot_otp', $data);
//                            redirect('login/otp');
                        } else {
                            redirect('login');
                        }
                    }
                } else {
                    $this->session->set_flashdata('error', $this->lang->line('err_email_mobile_not_exist'));
                    redirect('login');
                }
            }
        }
    }

//     verify otp for forgot password
    function verfiy_OTP() {
        $data['title'] = $this->lang->line('text_otp');
        $data['page_menutitle'] = $this->lang->line('text_otp');
        $data['meta_description'] = $this->lang->line('text_otp');
        $data['meta_keyword'] = $this->lang->line('text_otp');
        $data['page_banner_image'] = '';
        $data['otp'] = true;
        $data['otp'] = $this->input->post('otp');
        $this->form_validation->set_rules('otp', 'OTP', 'required|callback_checkOTP1');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view($this->path_to_view_front . 'forgot_otp', $data);
        } else {
            $data['title'] = $this->lang->line('text_changepassword');
            $data['page_menutitle'] = $this->lang->line('text_changepassword');
            $data['meta_description'] = $this->lang->line('text_changepassword');
            $data['meta_keyword'] = $this->lang->line('text_changepassword');
            $data['page_banner_image'] = '';
            $data['otp'] = true;
            $this->load->view($this->path_to_view_front . 'change_password', $data);
        }
    }

//    forgot password otp check server side
    function checkOTP1() {
        if ($this->input->post('otp') == $this->session->userdata('forgot_member_otp')) {
            return true;
        } else {
            $this->form_validation->set_message('checkOTP1', $this->lang->line('err_otp_remote'));
            return false;
        }
    }

//    forgot password otp check jquery validation
    function checkOTP() {
        if ($this->input->get('otp') == $this->session->userdata('forgot_member_otp'))
            echo json_encode(TRUE);
        else
            echo json_encode(FALSE);
    }

//    generate otp
    function generate_otp($len) {
        $r_str = "";
        $chars = "0123456789";
        do {
            $r_str = "";
            for ($i = 0; $i < $len; $i++) {
                $r_str .= substr($chars, rand(0, strlen($chars)), 1);
            }
        } while (strlen($r_str) != $len);
        return $r_str;
    }

//    change password page load
    function change_password() {
        $data['title'] = $this->lang->line('text_changepassword');
        $data['page_menutitle'] = $this->lang->line('text_changepassword');
        $data['meta_description'] = $this->lang->line('text_changepassword');
        $data['meta_keyword'] = $this->lang->line('text_changepassword');
        $data['page_banner_image'] = '';
        $data['change_password'] = true;

        $data['new_password'] = $this->input->post('new_password');
        $data['confirm_password'] = $this->input->post('confirm_password');

        $this->form_validation->set_rules('new_password', $this->lang->line('text_new_password'), 'required|min_length[6]', array('required' => $this->lang->line('err_new_password_req'), 'min_length[6]' => $this->lang->line('err_password_min')));
        $this->form_validation->set_rules('confirm_password', $this->lang->line('text_confirm_password'), 'required|matches[new_password]', array('required' => $this->lang->line('err_c_passowrd_req'), 'matches[new_password]' => $this->lang->line('err_c_passowrd_equal')));
        if ($this->form_validation->run() == FALSE) {
            $this->load->view($this->path_to_view_front . 'change_password', $data);
        } else {
            $this->db->set('password', md5($this->input->post('new_password')));
            $this->db->where('member_id', $this->session->userdata('forgot_member_id'));
            if ($this->db->update('member as m')) {
                $this->session->set_userdata('forgot_member_id', '');
                $this->session->set_flashdata('success', $this->lang->line('text_succ_changepass'));
                redirect('login');
            } else {
                $this->session->set_flashdata('error', $this->lang->line('text_err_changepass'));
                redirect('login');
            }
        }
    }

//    user logout
    function logout() {
        $this->member->logout();
        session_start();
        session_destroy();
        redirect('login');
    }

}

?>