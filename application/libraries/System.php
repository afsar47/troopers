<?php

class System {

    public function __construct() {
        $this->obj = & get_instance();
        $this->get_config();
        $this->obj->load->helper('system_helper');        
    }

    function get_config() {
        $query = $this->obj->db->get('web_config');
        foreach ($query->result() as $row) {
            $var = $row->web_config_name;
            $this->$var = $row->web_config_value;
        }
        if (!defined('PURCHASE_CODE')) {
            define('PURCHASE_CODE', $this->purchase_code);
        }
        if (!defined(str_rot13('LRF'))) {
            define(str_rot13('LRF'), $this->purchase_code_valid);
        }
        if (!defined('PURCHASE_CODE_MSG')) {
            define('PURCHASE_CODE_MSG', $this->purchase_code_msg);
        }
        if (!defined('PURCHASE_CODE_DOMAIN')) {
            define('PURCHASE_CODE_DOMAIN', $this->purchase_domain);
        }
    }

    function add_purchase_code() {
        $settings_arr = array('purchase_code', 'purchase_code_valid', 'purchase_code_msg', 'purchase_domain');
        $settings_val_arr = array(
            'purchase_code' => $this->obj->input->post('purchase_code'),
            'purchase_code_valid' => 'yes',
            'purchase_code_msg' => '',
            'purchase_domain' => base_url()
        );
        for ($i = 0; $i < count($settings_arr); $i++) {
            $settings_data = array('web_config_value' => $settings_val_arr[$settings_arr[$i]]);
            $this->obj->db->where('web_config_name', $settings_arr[$i]);
            $query = $this->obj->db->update('web_config', $settings_data);
        }
    }

    function remove_purchase_code() {
		//
    }

    function gettemplate() {
		echo 'n';
    }

}

?>