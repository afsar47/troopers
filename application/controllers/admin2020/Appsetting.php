<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Appsetting extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('file');
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('appsetting')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_view_admin . 'Appsetting_model', 'appsetting');
    }

    function index() {
                
        $data['appsetting'] = true;
        $data['Action'] = 'Add';
        $data['title'] = 'App Setting';
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', 'You have not permission to upload app.');
                redirect($this->path_to_view_admin . 'appsetting/');
            } else {
                $data['app_version'] = $this->input->post('app_version');
                $data['app_description'] = $this->input->post('app_description');

                $this->form_validation->set_rules('app_version', 'App Version', 'required');
                $this->form_validation->set_rules('force_update', 'Force Update', 'required');
                $this->form_validation->set_rules('app_description', 'App Description', 'required');
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'appsetting_manage', $data);
                } else {
                    if ($result = $this->appsetting->insert()) {
                        $this->session->set_flashdata('notification', 'App uploaded successfully.');
                        redirect($this->path_to_view_admin . 'appsetting/');
                    }
                }
            }
        } elseif ($this->input->post('other_submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', 'You have not permission to change appsetting.');
                redirect($this->path_to_view_admin . 'appsetting/');
            } else {
                $data['active_referral'] = $this->input->post('active_referral');
                $data['referral'] = $this->input->post('referral');
                $data['referral_level1'] = $this->input->post('referral_level1');
                $data['referral_min_paid_fee'] = $this->input->post('referral_min_paid_fee');
                $data['referandearn_description'] = $this->input->post('referandearn_description');
                $data['share_description'] = $this->input->post('share_description');
                $data['match_url'] = $this->input->post('match_url');
                $data['min_withdrawal'] = $this->input->post('min_withdrawal');
                $data['min_require_balance_for_withdrawal'] = $this->input->post('min_require_balance_for_withdrawal');
                $data['min_addmoney'] = $this->input->post('min_addmoney');
                $data['place_point_show'] = $this->input->post('place_point_show');
                $data['one_signal_notification'] = $this->input->post('one_signal_notification');
                $data['app_id'] = $this->input->post('app_id');
                // $data['rest_api_key'] = $this->input->post('rest_api_key');
                $data['firebase_otp'] = $this->input->post('firebase_otp');
                $data['fb_login'] = $this->input->post('fb_login');
                $data['google_login'] = $this->input->post('google_login');
                $data['firebase_api_key'] = $this->input->post('firebase_api_key');
                $data['firebase_script'] = $this->input->post('firebase_script');
                $data['footer_script'] = $this->input->post('footer_script');
                $data['google_client_id'] = $this->input->post('google_client_id');
                $data['fb_app_id'] = $this->input->post('fb_app_id');
                $data['msg91_otp'] = $this->input->post('msg91_otp');
                $data['msg91_authkey'] = $this->input->post('msg91_authkey');
                $data['msg91_sender'] = $this->input->post('msg91_sender');
                $data['msg91_route'] = $this->input->post('msg91_route');
                $data['under_maintenance'] = $this->input->post('under_maintenance');
                // $data['user_template'] = $this->input->post('user_template');
                $data['admin_user'] = $this->input->post('admin_user');
                $data['admin_profit'] = $this->input->post('admin_profit');
                $data['smtp_host'] = $this->input->post('smtp_host');
                $data['smtp_user'] = $this->input->post('smtp_user');
                $data['smtp_pass'] = $this->input->post('smtp_pass');
                $data['smtp_port'] = $this->input->post('smtp_port');
                $data['smtp_secure'] = $this->input->post('smtp_secure');
                $data['watch_ads_per_day'] = $this->input->post('watch_ads_per_day');
                $data['point_on_watch_ads'] = $this->input->post('point_on_watch_ads');
                $data['watch_earn_note'] = $this->input->post('watch_earn_note');
                $data['watch_earn_description'] = $this->input->post('watch_earn_description');
                $data['banner_ads_show'] = $this->input->post('banner_ads_show');
                $data['timezone'] = $this->input->post('timezone');
                $data['language'] = $this->input->post('language');
                
                $this->form_validation->set_rules('active_referral', 'lang:text_active_referral', 'required', array('required' => $this->lang->line('err_active_referral_req')));
                if ($this->input->post('active_referral') == '1') {
                    $this->form_validation->set_rules('referral', 'lang:text_main_user', 'required|numeric', array('required' => $this->lang->line('err_referral_req'), 'numeric' => $this->lang->line('err_referral_req')));
                    $this->form_validation->set_rules('referral_level1', 'lang:text_referral_user', 'required|numeric', array('required' => $this->lang->line('err_referral_level1_req')));
                    $this->form_validation->set_rules('referral_min_paid_fee', 'lang:text_referral_user', 'required|numeric', array('required' => $this->lang->line('err_referral_min_paid_fee_req')));
                    $this->form_validation->set_rules('referandearn_description', 'lang:text_refer_earn_desc', 'required', array('required' => $this->lang->line('err_referandearn_desc_req')));
                }
                $this->form_validation->set_rules('share_description', 'lang:text_share_desc', 'required', array('required' => $this->lang->line('err_match_url_req'),));
                $this->form_validation->set_rules('match_url', 'lang:text_match_url', 'required|valid_url', array('required' => $this->lang->line('err_app_desc_req'), 'valid_url' => $this->lang->line('err_match_url_valid')));
                $this->form_validation->set_rules('coin_under_hundrade', 'lang:text_coin_under_hundrade', 'required|numeric', array('required' => $this->lang->line('err_amount_req')));
                $this->form_validation->set_rules('coin_up_to_hundrade', 'lang:text_coin_up_to_hundrade', 'required|numeric', array('required' => $this->lang->line('err_amount_req')));           
                $this->form_validation->set_rules('min_withdrawal', 'lang:text_min_withdrawal', 'required|numeric', array('required' => $this->lang->line('err_min_withdrawal_req')));
                $this->form_validation->set_rules('min_require_balance_for_withdrawal', 'lang:text_min_require_balance_for_withdrawal', 'required|numeric', array('required' => $this->lang->line('err_amount_req')));
                $this->form_validation->set_rules('min_addmoney', 'lang:text_min_deposit', 'required|numeric', array('required' => $this->lang->line('err_min_addmoney_req')));
                $this->form_validation->set_rules('place_point_show', 'lang:text_place_point_show', 'required', array('required' => $this->lang->line('err_place_point_req')));
                $this->form_validation->set_rules('one_signal_notification', 'lang:text_one_signal_notification', 'required', array('required' => $this->lang->line('err_one_signal_req')));
                $this->form_validation->set_rules('msg91_otp', 'lang:text_msg91_otp', 'required', array('required' => $this->lang->line('err_msg91_otp_req')));
                $this->form_validation->set_rules('under_maintenance', 'lang:text_under_maintenance_mode', 'required', array('required' => $this->lang->line('err_under_maintenance_req')));
                $this->form_validation->set_rules('firebase_otp', 'lang:text_firebase_otp', 'required', array('required' => $this->lang->line('err_firebase_otp_req')));
                $this->form_validation->set_rules('fb_login', 'lang:text_fb_login', 'required', array('required' => $this->lang->line('err_fb_login_req')));
                $this->form_validation->set_rules('google_login', 'lang:text_google_login', 'required', array('required' => $this->lang->line('err_google_login_req')));
                $this->form_validation->set_rules('admin_user', 'lang:text_admin_user', 'required', array('required' => $this->lang->line('err_admin_user_req')));
                // $this->form_validation->set_rules('user_template', 'lang:text_web_template', 'required', array('required' => $this->lang->line('err_user_template_req')));
                $this->form_validation->set_rules('smtp_host', 'lang:text_smtp_host', 'required', array('required' => $this->lang->line('err_smtp_host_req')));
                $this->form_validation->set_rules('smtp_user', 'lang:text_smtp_user', 'required', array('required' => $this->lang->line('err_smtp_user_req')));
                $this->form_validation->set_rules('smtp_pass', 'lang:text_smtp_pass', 'required', array('required' => $this->lang->line('err_smtp_pass_req')));
                $this->form_validation->set_rules('smtp_port', 'lang:text_smtp_port', 'required', array('required' => $this->lang->line('err_smtp_port_req')));
                $this->form_validation->set_rules('smtp_secure', 'lang:text_smtp_secure', 'required', array('required' => $this->lang->line('err_smtp_secure_req')));
                $this->form_validation->set_rules('watch_ads_per_day', 'lang:text_watch_ads_per_day', 'required|numeric', array('required' => $this->lang->line('err_watch_ads_per_day_req'), 'numeric' => $this->lang->line('err_number')));
                $this->form_validation->set_rules('point_on_watch_ads', 'lang:text_point_on_watch_ads', 'required|numeric', array('required' => $this->lang->line('err_point_on_watch_ads_req'), 'numeric' => $this->lang->line('err_number')));
                $this->form_validation->set_rules('banner_ads_show', 'lang:text_banner_ads_show', 'required', array('required' => $this->lang->line('err_banner_ads_show_req')));
                $this->form_validation->set_rules('language[]', 'lang:text_language', 'required', array('required' => $this->lang->line('err_language_req')));

                if ($this->input->post('firebase_otp') == 'yes') {
                    $this->form_validation->set_rules('firebase_api_key', 'lang:text_firebase_api_key', 'required', array('required' => $this->lang->line('err_firebase_api_key_req')));
                    $this->form_validation->set_rules('firebase_script', 'lang:text_firebase_script', 'required', array('required' => $this->lang->line('err_firebase_script_req')));
                }
                if ($this->input->post('fb_login') == 'yes') {
                    $this->form_validation->set_rules('fb_app_id', 'lang:text_facebook_app_id', 'required', array('required' => $this->lang->line('err_fb_app_id_req')));
                }
                if ($this->input->post('google_login') == 'yes') {
                    $this->form_validation->set_rules('google_client_id', 'lang:text_gogole_client_id', 'required', array('required' => $this->lang->line('err_google_client_id_req')));
                }
                if ($this->input->post('one_signal_notification') == '1') {
                    $this->form_validation->set_rules('app_id', 'lang:text_server_key', 'required', array('required' => $this->lang->line('err_server_key_req')));
                    // $this->form_validation->set_rules('rest_api_key', 'lang:text_rest_api_key', 'required', array('required' => $this->lang->line('err_rest_api_key_req')));
                }
                if ($this->input->post('msg91_otp') == '1') {
                    $this->form_validation->set_rules('msg91_authkey', 'lang:text_msg91_auth_key', 'required', array('required' => $this->lang->line('err_msg91_authkey_req')));
                    $this->form_validation->set_rules('msg91_sender', 'lang:text_msg91_sender', 'required', array('required' => $this->lang->line('err_msg91_sender_req')));
                    $this->form_validation->set_rules('msg91_route', 'lang:text_msg91_route', 'required', array('required' => $this->lang->line('err_msg91_route_req')));
                }
                if ($this->form_validation->run() == FALSE) {
                    $data['users_data'] = $this->functions->getUsers();
                    $this->load->view($this->path_to_view_admin . 'appsetting_manage', $data);
                } else {
                   
                    $settings_arr = array('referral', 'referral_level1', 'referral_min_paid_fee','active_referral', 'referandearn_description', 'share_description', 'match_url', 'min_withdrawal','min_require_balance_for_withdrawal', 'min_addmoney', 'place_point_show', 'one_signal_notification', 'app_id',  'firebase_otp', 'fb_login', 'google_login', 'firebase_api_key', 'firebase_script', 'google_client_id', 'fb_app_id', 'msg91_otp', 'msg91_authkey', 'msg91_sender', 'msg91_route', 'under_maintenance', 'admin_user','admin_profit', 'smtp_host', 'smtp_user', 'smtp_port', 'smtp_secure', 'point_on_watch_ads', 'watch_ads_per_day', 'watch_earn_description', 'watch_earn_note', 'banner_ads_show', 'timezone','footer_script', 'coin_up_to_hundrade','coin_under_hundrade'); //, 'one_signal_notification','app_id','rest_api_key'
                    for ($i = 0; $i < count($settings_arr); $i++) {
                        $settings_data = array('web_config_value' => $this->input->post($settings_arr[$i]));
                        $this->db->where('web_config_name', $settings_arr[$i]);
                        if ($query = $this->db->update('web_config', $settings_data)) {
                            $this->session->set_flashdata('notification', 'Other Setting has been updated successfully.');
                        }
                    }
                    $this->db->set('web_config_value', urlencode($this->input->post('smtp_pass')));
                    $this->db->where('web_config_name', 'smtp_pass');
                    $this->db->update('web_config');

                    $lan_arr = array();
                    foreach($this->input->post('language') as $key => $value) {

                        $lan_pair = explode('---',$value);
                        $lan_arr[$lan_pair[0]] = strtolower($lan_pair[1]);

                        $target_dir = APPPATH . "language/" . $lan_arr[$lan_pair[0]];
                       
                        if(!file_exists($target_dir)){                            
                            mkdir($target_dir,0755);
                            copy(APPPATH . "language/english/alert_lang.php",APPPATH . "language/" . $lan_arr[$lan_pair[0]] . "/alert_lang.php");
                            copy(APPPATH . "language/english/information_lang.php",APPPATH . "language/" . $lan_arr[$lan_pair[0]] . "/information_lang.php");
                            copy(APPPATH . "language/english/validation_lang.php",APPPATH . "language/" . $lan_arr[$lan_pair[0]] . "/validation_lang.php");
                        }

                        $api_target_dir = FCPATH . "api/resources/lang/" . $lan_pair[0];
                       
                        if(!file_exists($api_target_dir)){                            
                            mkdir($api_target_dir,0755);
                            copy(FCPATH . "api/resources/lang/en/message.php",FCPATH . "api/resources/lang/" . $lan_pair[0] . "/message.php");                            
                        }
                    }
                                       
                    $language = (object) $lan_arr;
                    
                    $this->db->set('web_config_value', json_encode($language));
                    $this->db->where('web_config_name', 'supported_language');
                    $this->db->update('web_config');

                    redirect($this->path_to_view_admin . 'appsetting/');
                }
            }
        } elseif ($this->input->post('action') == "delete") {
            if ($result = $this->appsetting->delete()) {
                $this->session->set_flashdata('notification', 'Match Map has been deleted successfully.');
                redirect($this->path_to_view_admin . 'appsetting/');
            }
        } else {
            $data['languages'] = array("af" => "Afrikaans","sq" => "Albanian","am" => "Amharic","ar" => "Arabic","an" => "Aragonese","hy" => "Armenian","ast" => "Asturian",
                            "az" => "Azerbaijani","eu" => "Basque","be" => "Belarusian","bn" => "Bengali","bs" => "Bosnian","br" => "Breton","bg" => "Bulgarian","ca" => "Catalan",    
                            "zh" => "Chinese",   "co" => "Corsican","hr" => "Croatian","cs" => "Czech","da" => "Danish","nl" => "Dutch","en" => "English","eo" => "Esperanto",
                            "et" => "Estonian","fo" => "Faroese","fil" => "Filipino","fi" => "Finnish","fr" => "French",    "gl" => "Galician","ka" => "Georgian","de" => "German",
                            "el" => "Greek","gn" => "Guarani","gu" => "Gujarati","ha" => "Hausa","haw" => "Hawaiian","he" => "Hebrew","hi" => "Hindi","hu" => "Hungarian","is" => "Icelandic",
                            "id" => "Indonesian","ia" => "Interlingua","ga" => "Irish","it" => "Italian",    "ja" => "Japanese","kn" => "Kannada","kk" => "Kazakh","km" => "Khmer","ko" => "Korean",
                            "ku" => "Kurdish","ky" => "Kyrgyz","lo" => "Lao","la" => "Latin","lv" => "Latvian","ln" => "Lingala","lt" => "Lithuanian","mk" => "Macedonian","ms" => "Malay",
                            "ml" => "Malayalam","mt" => "Maltese","mr" => "Marathi","mn" => "Mongolian","ne" => "Nepali","no" => "Norwegian","oc" => "Occitan","or" => "Oriya","om" => "Oromo",
                            "ps" => "Pashto","fa" => "Persian","pl" => "Polish","pt" => "Portuguese",    "pa" => "Punjabi","qu" => "Quechua","ro" => "Romanian",    "rm" => "Romansh",
                            "ru" => "Russian",    "sr" => "Serbian","sh" => "Serbo","sn" => "Shona","sd" => "Sindhi","si" => "Sinhala","sk" => "Slovak","sl" => "Slovenian","so" => "Somali",
                            "es" => "Spanish",    "su" => "Sundanese","sw" => "Swahili","sv" => "Swedish","tg" => "Tajik","ta" => "Tamil","tt" => "Tatar","te" => "Telugu","th" => "Thai",
                            "ti" => "Tigrinya","to" => "Tongan","tr" => "Turkish","tk" => "Turkmen","tw" => "Twi","uk" => "Ukrainian","ur" => "Urdu","ug" => "Uyghur","uz" => "Uzbek",
                            "vi" => "Vietnamese","wa" => "Walloon","cy" => "Welsh",    "xh" => "Xhosa","yi" => "Yiddish","yo" => "Yoruba","zu" => "Zulu"
                        );            

            $data['users_data'] = $this->functions->getUsers();
            $this->load->view($this->path_to_view_admin . 'appsetting_manage', $data);
        }
    }

    function insert() {
        $data['appsetting'] = true;
        $data['btn'] = 'View Appupload';
        $data['Action'] = 'Add';
        $data['title'] = 'App Setting';
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', 'You have not permission to upload app.');
                redirect($this->path_to_view_admin . 'appsetting/');
            } else {
                $data['app_upload'] = $this->input->post('app_upload');
                $this->form_validation->set_rules('app_upload', 'Appupload', 'required');
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'appsetting_manage', $data);
                } else {
                    if ($result = $this->appsetting->insert()) {
                        $this->session->set_flashdata('notification', 'App uploaded successfully.');
                        redirect($this->path_to_view_admin . 'appsetting/');
                    }
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'appsetting_manage', $data);
        }
    }

    function setDatatableAppupload() {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'app_upload_id',
            1 => 'app_upload',
            2 => 'app_version',
            3 => 'force_update',
            4 => 'force_logged_out',
            5 => 'link',
            6 => 'date_created'
        );
        $totalData = $this->appsetting->get_list_count_appupload();
        $totalFiltered = $totalData;

        $sql = "SELECT * FROM app_upload";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  app_upload LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  app_version LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  force_update LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  force_logged_out LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  date_created LIKE '%" . $requestData['search']['value'] . "%' ";
        }
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `date_created` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $row['app_upload'];
            $nestedData[] = $row['app_version'];
            $nestedData[] = $row['force_update'];
            $nestedData[] = $row['force_logged_out'];
            $nestedData[] = "<a href='" . base_url() . $this->apk . $row['app_upload'] . "' download>" . base_url() . $row['app_upload'] . "</a>";
            $nestedData[] = $row['date_created'];
            $data[] = $nestedData;
            $i++;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }    

}
