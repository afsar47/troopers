<?php

class Functions {

    public function __construct() {
        $this->obj = &get_instance();
        //date_default_timezone_set('Asia/Kolkata');
    }

    public function GenerateUniqueFilePrefix() {
        list($usec, $sec) = explode(" ", microtime());
        list($trash, $usec) = explode(".", $usec);
        return (date("YmdHis") . substr(($sec + $usec), -10) . '_');
    }

    function check_permission($code_name) {
                
        if($this->obj->session->userdata('id') == 1){
            return true;
        } else {
            $this->obj->db->select('*');
            $this->obj->db->where('id', $this->obj->session->userdata('id'));
            $admin_data = $this->obj->db->get('admin')->row_array();
            
            $this->obj->db->select('*');
            $this->obj->db->where('code_name', $code_name);
            $permission_data = $this->obj->db->get('permission')->row_array();

            if(!empty($permission_data)) {
                if(in_array($permission_data['permission_id'],json_decode($admin_data['permission'],true))) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function getAllPages() {
        $this->obj->db->select('*');
        $this->obj->db->where('page_publish', '1');
        $this->obj->db->where('add_to_menu', '1');
        $this->obj->db->where('parent', '0');
        $this->obj->db->order_by('page_order', 'ASC');
        $query = $this->obj->db->get('page');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function getFooterPages() {
        $this->obj->db->select('*');
        $this->obj->db->where('page_publish', '1');
        $this->obj->db->where('add_to_footer', '1');        
        $this->obj->db->order_by('page_order', 'ASC');
        $query = $this->obj->db->get('page');        
        return $query->result();        
    }

    public function getPage($id) {
        $this->obj->db->select('*');
        $this->obj->db->where('page_slug', $id);

        $query = $this->obj->db->get('page');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function getAllChild($page_id) {
        $this->obj->db->select('*');
        $this->obj->db->where('parent', $page_id);
        $this->obj->db->order_by('page_order', 'ASC');
        $qry = $this->obj->db->get('page');
        if ($qry->num_rows() > 0) {
            return $qry->result();
        }
    }

    public function getCurrentApp() {
        $this->obj->db->select('*');
        $this->obj->db->order_by('app_upload_id', 'DESC');
        $this->obj->db->limit('1');
        $query = $this->obj->db->get('app_upload');
        $app_upload = $query->row_array();

        $app_upload = array();
        if ($query->num_rows() > 0) {
            $app_upload = $query->row_array();
            return $app_upload['app_upload'];
        } else {
            return '';
        }
    }

    public function mysql_connection() {
        include 'database.php';

        $connection = new mysqli($hostname, $username, $password, $database);
        mysqli_set_charset($connection, "utf8");
        return $connection;
    }

    public function sendMessage($title, $message, $image_url = '') {
        if ($this->obj->system->one_signal_notification == '1' || $this->obj->system->one_signal_notification == 1) {
            
            $registration_ids = array();
            if($this->obj->input->post('send_to') == 'single_member') {
                $registration_ids[] = $this->obj->input->post('member');
            } else if($this->obj->input->post('send_to') == 'match'){
                
                
                
                $this->obj->db->select('player_id');
                $this->obj->db->from('match_join_member');
                $this->obj->db->where('match_join_member.match_id',$this->obj->input->post('match'));
               $this->obj->db->join('member','member.member_id = match_join_member.member_id');
                
              $match_mem =  $this->obj->db->get()->result_array();
             
                 
                   foreach($match_mem as $mem){
                    $registration_ids[] = $mem['player_id'];
                }
                
             
            
                //   $members = $this->obj->db->get('member')->result_array();
                                     
                // foreach($members as $mem){
                //     $registration_ids[] = $mem['player_id'];
                // }
                
            } else {
                $this->obj->db->select('player_id');
                $this->obj->db->where('player_id !=','');
                $this->obj->db->where('push_noti','1');
                $this->obj->db->where('member_status','1');
                
                if($this->obj->input->post('send_to') == 'multi_member') {
                    $this->obj->db->where('member_id BETWEEN "'. $this->obj->input->post('multi_member_from'). '" AND "'. $this->obj->input->post('multi_member_to'). '" ');
                }
                
                $members = $this->obj->db->get('member')->result_array();
                                     
                foreach($members as $mem){
                    $registration_ids[] = $mem['player_id'];
                }
            }
            
            $msg = array(
                'body'  => $message,
                'title' => $title,
                // 'icon'  => 'myicon',/*Default Icon*/        
                'icon'  => 'Default',                   
            );

            if($image_url != ''){
                $msg['image'] = $image_url;
            }
                
            $fields = array (
                'registration_ids' => $registration_ids,
                'notification' => $msg,        
            ); 
                             
                        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization:key=' . $this->obj->system->app_id));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            
            $not_response = curl_exec($ch);
            
            curl_close($ch);
            
            $not_response = json_decode($not_response,true);  
          
        }

        return true;
    }

    public function sendMessageMember($message, $registration_ids, $title, $match_type = '1') {
        if ($this->obj->system->one_signal_notification == '1' || $this->obj->system->one_signal_notification == 1) {
            $msg = array(
                'body'  => $message,
                'title' => $title,
                // 'icon'  => 'myicon',/*Default Icon*/        
                'icon'  => 'Default',                   
            );           
                
            $fields = array (
                'registration_ids' => $registration_ids,
                'notification' => $msg,        
                );            
                        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization:key=' . $this->obj->system->app_id));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            
            $not_response = curl_exec($ch);
            
            curl_close($ch);
            
            $not_response = json_decode($not_response,true); 
        }
        return true;
    }

    function getCurrency() {
        $this->obj->db->select('*');
        $this->obj->db->where('currency_status', '1');
        $query = $this->obj->db->get('currency');
        return $query->result();
    }

    function getCurrencySymbol($id) {
        $this->obj->db->select('currency_symbol');
        $this->obj->db->where('currency_id', $id);
        $query = $this->obj->db->get('currency');
        //echo $this->obj->db->last_query();exit;
        $row = "";
        if ($query->num_rows() > 0) {
            $row = $query->row()->currency_symbol;
        }
        return $row;
    }

    function getCurrencyDecimal($id) {
        $this->obj->db->select('currency_decimal_place');
        $this->obj->db->where('currency_id', $id);
        $query = $this->obj->db->get('currency');
        $row = "";
        if ($query->num_rows() > 0) {
            $row = $query->row()->currency_decimal_place;
        }
        return $row;
    }

    function getPoint() {
//        return '<i class="fa fa-product-hunt point"></i>';
        return '<img src="' . $this->obj->template_img . 'coin.png" style="vertical-align: sub;width:20px">';
    }

    public function getCountry() {
        $this->obj->db->select('*');
        $this->obj->db->where('country_status', '1');
        $this->obj->db->order_by('country_name', 'ASC');
        $query = $this->obj->db->get('country');
        $result = $query->result();
        return $result;
    }

    public function getCountryCodeToID($country_code) {
        $this->obj->db->select('country_id');
        $this->obj->db->where('p_code', $country_code);
        $query = $this->obj->db->get('country');
        return $query->row_array()['country_id'];
    }

    public function mask_email($email) {
        if ($email != '') {
            $mail_parts = explode("@", $email);
            $domain_parts = explode('.', $mail_parts[1]);

            $mail_parts[0] = $this->mask($mail_parts[0], 2, 1);
            $domain_parts[0] = $this->mask($domain_parts[0], 2, 1);
            $mail_parts[1] = implode('.', $domain_parts);
            return implode("@", $mail_parts);
        } else
            return "";
    }

    public function mask($str, $first, $last) {
        $len = strlen($str);
        $toShow = $first + $last;
        return substr($str, 0, $len <= $toShow ? 0 : $first) . str_repeat("*", $len - ($len <= $toShow ? 0 : $toShow)) . substr($str, $len - $last, $len <= $toShow ? 0 : $last);
    }

    public function stars($phone) {
        $times = strlen(trim(substr($phone, 5, 26)));
        $star = '';
        for ($i = 0; $i < $times; $i++) {
            $star .= '*';
        }
        return $star;
    }

    public function stars_smtp_pass($str) {
        $times = strlen($str);
        $star = '';
        for ($i = 0; $i < $times; $i++) {
            $star .= '*';
        }
        return $star;
    }

    public function getUsers() {
        $this->obj->db->select('member_id,user_name');
        $this->obj->db->where('member_status', '1');
        $query = $this->obj->db->get('member');
        return $query->result();
    }

    function getCurrencyCode($id) {
        $this->obj->db->select('currency_code');
        $this->obj->db->where('currency_id', $id);
        $query = $this->obj->db->get('currency');
        $row = "";
        if ($query->num_rows() > 0) {
            $row = $query->row()->currency_code;
        }
        return $row;
    }

}
