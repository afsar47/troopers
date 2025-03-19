<?php

$this->obj = & get_instance();
$this->obj->load->library('system');
/**
 *
 * @param CI_Controller $ci        	
 */
if (YES == 'yes' && $this->obj->session->userdata('logged_in') == true && !isset($_COOKIE['bmpcs'])) {

    $settings_arr = array('purchase_code', 'purchase_code_valid', 'purchase_code_msg', 'purchase_domain');
    $settings_val_arr = array(
        'purchase_code' => PURCHASE_CODE,
        'purchase_code_valid' => 'yes',
        'purchase_code_msg' => '',
        'purchase_domain' => base_url()
    );
    for ($i = 0; $i < count($settings_arr); $i++) {
        $settings_data = array('web_config_value' => $settings_val_arr[$settings_arr[$i]]);
        $this->obj->db->where('web_config_name', $settings_arr[$i]);
        $query = $this->obj->db->update('web_config', $settings_data);
    }

    setcookie('bmpcs', 'yes', time() + 86400 * 7, '/', parse_url(current_url())['host']);
    $_COOKIE ['bmpcs'] = 'yes';
}
