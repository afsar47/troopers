<?php

class Member {

    var $front_member_id = 0;
    var $front_logged_in = false;
    var $front_member_username = '';
    var $front_member_fullname = '';
    var $front_member_id_active = '';
    var $front_member_email_id = '';
    var $front_member_package_upgraded = '';
    var $front_login_via = '';
    var $table = 'member';
    var $front_player_id = '';

    function __construct() {
        $this->obj = & get_instance();

        $this->_session_to_library();
    }

    function _prep_password($password) {
        return $this->obj->encrypt->sha1($password . $this->obj->config->item('encryption_key'));
    }

    function _session_to_library() {
        $this->front_member_id = $this->obj->session->userdata('front_member_id');
        $this->front_member_username = $this->obj->session->userdata('front_member_username');
        $this->front_member_fullname = $this->obj->session->userdata('front_member_fullname');
        $this->front_member_id_active = $this->obj->session->userdata('front_member_id_active');
        $this->front_member_package_upgraded = $this->obj->session->userdata('front_member_package_upgraded');
        $this->front_member_email_id = $this->obj->session->userdata('front_member_email_id');
        $this->front_login_via = $this->obj->session->userdata('front_login_via');
        $this->front_player_id = $this->obj->session->userdata('front_player_id');
        $this->front_logged_in = $this->obj->session->userdata('front_logged_in');
    }

    function _start_session($users) {
        $data = array(
            'front_member_id' => $users->member_id,
            'front_member_username' => $users->user_name,
            'front_member_fullname' => $users->first_name,
            'front_member_email_id' => $users->email_id,
            'front_login_via' => $users->login_via,
            'front_member_package_upgraded' => $users->member_package_upgraded,
            'front_member_id_active' => $users->member_status,
            'front_player_id' => $users->player_id,
            'front_logged_in' => true,
        );

        $this->obj->session->set_userdata($data);
        $this->_session_to_library();
    }

    function _destroy_session() {
        $data = array(
            'front_member_id' => 0,
            'front_member_username' => '',
            'front_member_fullname' => '',
            'front_member_email_id' => '',
             'front_login_via' => '',
            'front_member_package_upgraded' => '',
            'front_member_id_active' => '',
            'front_player_id' => '',
            'front_logged_in' => false,
        );
        $this->obj->session->set_userdata($data);
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /* user login */

    function login($username, $password) {
        $this->obj->db->where('user_name', $username);
        $this->obj->db->where('password', md5($password));
        $query = $this->obj->db->get($this->table, 1);
        if ($query->num_rows() == 1) {
            $users = $query->row();
            if ($users->member_status == '1') {
                $this->_start_session($users);
                $this->obj->session->set_flashdata('pass_login', 'Login successful...');
                return true;
            } else {
                $this->_destroy_session();

                $this->obj->session->set_flashdata('error', 'ERROR: You are not active ,please contact our support center.');

                return false;
            }
        } else {
            $this->_destroy_session();
            $this->obj->session->set_flashdata('error', 'ERROR: Invalid Username or Password. Try Again.');
            return false;
        }
    }

    function logout() {
        $this->_destroy_session();
        $this->obj->session->set_flashdata('success', 'You are now logged out.');
    }

}

?>