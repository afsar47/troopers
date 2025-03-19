<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['title'] = $this->lang->line('text_login');
        $this->db->select('*');
        $this->db->where('default_login', '0');
        $query = $this->db->get('admin');
        $data['login_data'] = $query->row_array();


        if ($this->user->logged_in) {
            redirect($this->path_to_view_admin . 'dashboard');
        } else {
            $this->load->view($this->path_to_view_admin . 'login', $data);
        }
    }

    public function checkdata() {
        $data['name'] = $this->input->post('name');
        $data['password'] = $this->input->post('password');
        $this->user->_destroy_session();
        if ($this->user->logged_in) {
            $data['dashboard'] = true;
            $data['title'] = "home";
            $this->load->view($this->path_to_view_admin . 'dashboard', $data);
        } else {

            if (!$this->input->post('submit')) {
                $data['login'] = true;
                $data['title'] = "login";
                redirect($this->path_to_view_admin . 'dashboard');
            } else {
//                print_r($_POST);
//                exit;
                $name = $this->input->post('name');
                $password = $this->input->post('password');
                if ($this->user->login($name, $password) == true) {

                    redirect($this->path_to_view_admin . 'dashboard');
                } else {
                    redirect($this->path_to_view_admin . 'login');
                }
            }
        }
    }

    //    send otp for forgot password
    function send_otp() {

        if ($this->input->post('forgot') == $this->lang->line('text_btn_submit')) {

            $data['email_id'] = $this->input->post('email_mobile');

                $this->form_validation->set_rules('email_mobile', 'lang:text_email', 'required|valid_email', array('required' => $this->lang->line('err_email_id_req'), 'valid_email' => $this->lang->line('err_email_id_valid')));
            
            if ($this->form_validation->run() == FALSE) {                
                $this->session->set_flashdata('error', $this->lang->line('err_email_not_exist'));                
                redirect($this->path_to_view_admin . 'login');
            } else {
                $this->db->select('*');                
                $this->db->where('email', $this->input->post('email_mobile'));               
                $query = $this->db->get('admin');
                if ($query->num_rows() > 0) {
                    $admin = $query->row_array();
                    $otp = $this->generate_otp(6);
                    $this->session->set_userdata('forgot_admin_otp', $otp);
                    $this->session->set_userdata('forgot_admin_id', $admin['id']);
                    if ($admin['email'] == $this->input->post('email_mobile')) {
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
                        // $this->email->from($this->system->company_email, $this->system->company_name);
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
                            $this->load->view($this->path_to_view_admin . 'forgot_otp', $data);
                        } else {                            
                            redirect($this->path_to_view_admin . 'login');
                        }                        
                    }
                } else {
                    $this->session->set_flashdata('error', $this->lang->line('err_email_mobile_not_exist'));
                    redirect($this->path_to_view_admin . 'login');
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
            $this->load->view($this->path_to_view_admin . 'forgot_otp', $data);
        } else {
            $data['title'] = $this->lang->line('text_changepassword');
            $data['page_menutitle'] = $this->lang->line('text_changepassword');
            $data['meta_description'] = $this->lang->line('text_changepassword');
            $data['meta_keyword'] = $this->lang->line('text_changepassword');
            $data['page_banner_image'] = '';
            $data['otp'] = true;
            $this->load->view($this->path_to_view_admin . 'forgot_pass', $data);
        }
    }

//    forgot password otp check server side
    function checkOTP1() {
        if ($this->input->post('otp') == $this->session->userdata('forgot_admin_otp')) {
            return true;
        } else {
            $this->form_validation->set_message('checkOTP1', $this->lang->line('err_otp_remote'));
            return false;
        }
    }

//    forgot password otp check jquery validation
    function checkOTP() {
        if ($this->input->get('otp') == $this->session->userdata('forgot_admin_otp'))
            echo json_encode(TRUE);
        else
            echo json_encode(FALSE);
    }

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

    function forgot_password() {
        $data['forgot_password'] = true;
        $data['title'] = $this->lang->line('text_changepassword'); 

        $this->load->view($this->path_to_view_admin . 'forgot_pass', $data);       
    }

    function forgot_change_pass() {

        if ($this->input->post('password_update') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_change_password'));
                redirect($this->path_to_view_admin . 'profilesetting/');
            }
            $data['new_password'] = $this->input->post('new_password');
            $data['c_passowrd'] = $this->input->post('c_passowrd');
            
            $this->form_validation->set_rules('new_password', 'lang:text_new_password', 'required', array('required' => $this->lang->line('err_new_password_req')));
            $this->form_validation->set_rules('c_passowrd', 'lang:text_confirm_password', 'required|callback_checkNewpass', array('required' => $this->lang->line('err_c_passowrd_req')));
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'forgot_pass', $data);
            } else {

                $array = array(
                    'password' => md5($this->input->post('new_password')),
                    'default_login' => '1'
                );
                $this->db->where('id', $this->session->userdata('forgot_admin_id'));

                if ($this->db->update('admin', $array)) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_password'));
                    redirect($this->path_to_view_admin . 'login');
                }
            }
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

    function logout() {
        $this->user->logout();
        redirect($this->path_to_view_admin . 'login');
    }

}
