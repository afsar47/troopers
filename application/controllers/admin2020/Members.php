<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Members extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('members')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_view_admin . 'Member_model', 'members');
    }

    function index() {
        $data['member'] = true;
        $data['title'] = $this->lang->line('text_member');

        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $row['member_id'] <= 20) || !$this->functions->check_permission('members_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_member'));
                redirect($this->path_to_view_admin . 'members/');
            } else {
                if ($result = $this->members->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_member'));
                    redirect($this->path_to_view_admin . 'members/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('memberid') <= 20) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'members/');
            } else {
                if ($result = $this->members->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_member'));
                    redirect($this->path_to_view_admin . 'members/');
                }
            }
        }
        $this->load->view($this->path_to_view_admin . 'member_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 20) || !$this->functions->check_permission('members_delete')) {                    
                echo $this->lang->line('text_err_delete_member');                    
            } else {
                $this->members->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 20) || !$this->functions->check_permission('members')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->members->changeMultiPublishStatus();            
            }       
        }
    }

    function member_detail() {
        if(!$this->functions->check_permission('members_view')) {
            redirect($this->path_to_view_admin . 'members');
        }

        $data['member_manage'] = true;
        $data['title'] = $this->lang->line('text_member_manage');

        $member_id = $this->uri->segment('4');
        if ($this->input->post('update') == $this->lang->line('text_btn_update')) {
            if ($this->system->demo_user == 1 && $this->input->post('member_id') <= 20) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_member'));
                redirect($this->path_to_view_admin . 'members/');
            } else {
                $data['member_id'] = $this->input->post('member_id');
                $data['first_name'] = $this->input->post('first_name');
                $data['last_name'] = $this->input->post('last_name');
                $data['user_name'] = $this->input->post('user_name');
                $data['password'] = $this->input->post('password');
                $data['pubg_id'] = $this->input->post('pubg_id');
                $data['email_id'] = $this->input->post('email_id');
                $data['dob'] = $this->input->post('dob');
                $data['referral_id'] = $this->input->post('referral_id');
                $data['mobile_no'] = $this->input->post('mobile_no');
                $data['gender'] = $this->input->post('gender');
//                $data['country_id'] = $this->input->post('country_id');
                $data['country_code'] = $this->input->post('country_code');

                $this->form_validation->set_rules('user_name', 'lang:text_user_name', 'required|callback_checkUserName1', array('required' => $this->lang->line('err_user_name_req')));
                $this->form_validation->set_rules('password', 'lang:text_password', 'min_length[6]', array('min_length' => $this->lang->line('err_password_min')));
                $this->form_validation->set_rules('email_id', 'lang:text_email', 'required|valid_email|callback_checkEmail1', array('required' => $this->lang->line('err_email_id_req'), 'valid_email' => $this->lang->line('err_email_id_valid')));
                $this->form_validation->set_rules('mobile_no', 'lang:text_mobile_no', 'required|numeric|callback_checkMobile1', array('required' => $this->lang->line('err_announcement_req'), 'numeric' => $this->lang->line('err_mobile_no_number')));
                $this->form_validation->set_rules('profile_image', 'lang:text_logo', 'callback_file_profile_image');

                if ($this->form_validation->run() == FALSE) {
                    $data['member_detail'] = $this->members->getmemberById($data['member_id']);
                    $data['tot_match_play'] = $this->members->get_tot_match_play($data['member_id']);
                    $data['tot_kill'] = $this->members->get_tot_kill($data['member_id']);
                    $data['tot_win'] = $this->members->get_tot_win($data['member_id']);
                    $data['tot_balance'] = $this->members->get_tot_balance($data['member_id']);
                    $data['country_data'] = $this->functions->getCountry();
                    $this->load->view($this->path_to_view_admin . 'member_manage_detail', $data);
                } else {
                    if ($result = $this->members->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_member'));
                        redirect($this->path_to_view_admin . 'members/');
                    }
                }
            }
        } elseif ($this->input->post('add_wallet') == $this->lang->line('text_btn_submit')) {

            if ($this->system->demo_user == 1 && $this->input->post('member_id') <= 20) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_member'));
                redirect($this->path_to_view_admin . 'members/');
            } else {

                $data['member_id'] = $this->input->post('member_id');
                $data['amount'] = $this->input->post('amount');
                $data['comment'] = $this->input->post('comment');
                $data['wallet'] = $this->input->post('wallet');
                $data['plus_minus'] = $this->input->post('plus_minus');

                $this->form_validation->set_rules('amount', 'lang:text_amount', 'required|numeric|callback_checkAmount', array('required' => $this->lang->line('err_amount_req'), 'numeric' => $this->lang->line('err_amount_number')));
                $this->form_validation->set_rules('wallet', 'lang:text_wallet', 'required', array('required' => $this->lang->line('err_wallet_req')));
                $this->form_validation->set_rules('plus_minus', 'lang:text_plus_minus', 'required', array('required' => $this->lang->line('err_plus_minus_req')));

                if ($this->form_validation->run() == FALSE) {
                    $data['tot_match_play'] = $this->members->get_tot_match_play($data['member_id']);
                    $data['tot_kill'] = $this->members->get_tot_kill($data['member_id']);
                    $data['tot_win'] = $this->members->get_tot_win($data['member_id']);
                    $data['tot_balance'] = $this->members->get_tot_balance($data['member_id']);
                    $data['member_detail'] = $this->members->getmemberById($data['member_id']);
                    $data['country_data'] = $this->functions->getCountry();
                    $this->load->view($this->path_to_view_admin . 'member_manage_detail', $data);
                } else {
                    if ($result = $this->members->update_wallet()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_upd_wallet'));
                        redirect($this->path_to_view_admin . 'members/');
                    }
                }
            }
        } else {
            $data['member_detail'] = $this->members->getmemberById($member_id);
            $data['tot_match_play'] = $this->members->get_tot_match_play($member_id);
            $data['tot_kill'] = $this->members->get_tot_kill($member_id);
            $data['tot_win'] = $this->members->get_tot_win($member_id);
            $data['tot_balance'] = $this->members->get_tot_balance($member_id);
            $data['country_data'] = $this->functions->getCountry();
            $this->load->view($this->path_to_view_admin . 'member_manage_detail', $data);
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
    
    function checkUserName() {
        $member_id = $this->uri->segment('4');
        $this->db->select('*');
        $this->db->where('user_name', $this->input->get('user_name'));
        $this->db->where('member_id !=', $member_id);
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            echo json_encode(FALSE);
        } else {
            echo json_encode(TRUE);
        }
    }

    function checkUserName1() {
        $this->db->select('*');
        $this->db->where('user_name', $this->input->post('user_name'));
        $this->db->where('member_id !=', $this->input->post('member_id'));
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('checkUserName1', $this->lang->line('err_user_name_exist'));
            return false;
        } else {
            return true;
        }
    }

    function checkEmail() {
        $member_id = $this->uri->segment('4');
        $this->db->select('*');
        $this->db->where('member_id', $member_id);
        $query = $this->db->get('member');
        $member = $query->row_array();

        if ($member['login_via'] == '0') {
            $this->db->select('*');
            $this->db->where('email_id', $this->input->post('email_id'));
            $this->db->where('member_id != ', $member_id);
            $this->db->where('login_via', '0');
            $query = $this->db->get('member');
            if ($query->num_rows() > 0) {
                echo json_encode(FALSE);
            } else {
                echo json_encode(TRUE);
            }
        } else {
            $this->db->select('*');
            $this->db->where('email_id', $this->input->post('email_id'));
            $this->db->where('member_id != ', $member_id);
            $this->db->where('login_via != ', '0');
            $query = $this->db->get('member');
            if ($query->num_rows() > 0) {
                echo json_encode(FALSE);
            } else {
                echo json_encode(TRUE);
            }
        }
    }

    function checkEmail1() {
        $this->db->select('*');
        $this->db->where('member_id', $this->input->post('member_id'));
        $query = $this->db->get('member');
        $member = $query->row_array();

        if ($member['login_via'] == '0') {
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
        $member_id = $this->uri->segment('4');
        $this->db->select('*');
        $this->db->where('mobile_no', $this->input->get('mobile_no'));
        $this->db->where('member_id !=', $member_id);
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            echo json_encode(FALSE);
        } else {
            echo json_encode(TRUE);
        }
    }

    function checkMobile1() {
        $member_id = $this->uri->segment('4');
        $this->db->select('*');
        $this->db->where('mobile_no', $this->input->post('mobile_no'));
        $this->db->where('member_id !=', $this->input->post('member_id'));
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('checkMobile1', $this->lang->line('err_mobile_no_exist'));
            return false;
        } else {
            return true;
        }
    }

    function checkAmount() {
        if ($this->input->post('plus_minus') == '-') {
            $this->db->select('*');
            $this->db->where('member_id', $this->input->post('member_id'));
            $query = $this->db->get('member');
            $data = $query->row_array();
            if ($data[$this->input->post('wallet')] < $this->input->post('amount')) {
                $this->form_validation->set_message('checkAmount', $this->lang->line('err_no_balance'));
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    function setDatatableMember() {
        header('Content-Type: application/json; charset=UTF-8');
        $requestData = $_REQUEST;
        $columns = array(
            2 => 'first_name',
            3 => 'user_name',
            4 => 'email_id',
            5 => 'mobile_no',
            6 => 'referral_id',
            7 => 'login_via',
            8 => 'member_status'
        );
        $totalData = $this->members->get_list_count_member();
        $totalFiltered = $totalData;
        $sql = "SELECT m.*,m2.user_name as referral_no FROM member m";
        $sql .= " LEFT JOIN member as m2 ON m.referral_id = m2.member_id";
        $query = mysqli_query($this->con, $sql);

        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  m.first_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  m.user_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  m.email_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  m.mobile_no LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  m.login_via LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  m2.user_name LIKE '%" . $requestData['search']['value'] . "%' ";
        }
        $sql .= " GROUP BY m.member_id";
        mysqli_query($this->con, "set character_set_results='utf8'");
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query);
        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `created_date` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        
        mysqli_query($this->con, "set character_set_results='utf8'");
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = '<input type="checkbox" value="'. $row['member_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['first_name'] . ' ' . $row['last_name'];
            $nestedData[] = $row['user_name'];
            if ($this->system->demo_user == 1) {
                $nestedData[] = $this->functions->mask_email($row['email_id']);
            } else {
                $nestedData[] = $row['email_id'];
            }
            if ($this->system->demo_user == 1) {
                $nestedData[] = str_replace(substr($row['mobile_no'], 2, 6), $this->functions->stars($row['mobile_no']), $row['mobile_no']);
            } else {
                $nestedData[] = $row['mobile_no'];
            }
            $nestedData[] = $row['referral_no'];
            if ($row['login_via'] == '0')
                $nestedData[] = '';
                // $nestedData[] = '<span class="badge badge-info">Default</span>';
            elseif ($row['login_via'] == '1')
                $nestedData[] = '<span class="badge badge-info">FaceBook</span>';
            elseif ($row['login_via'] == '2')
                $nestedData[] = '<span class="badge badge-info">Google</span>';

            if ($this->system->demo_user == 1 && $row['member_id'] <= 20) {
                if ($row['member_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top">Inactive</span>';
                }
            } else {
                if ($row['member_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmmemberlist,' . $row['member_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmmemberlist,' . $row['member_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }
            $edit = '<a style="font-size:18px;" data-original-title="Detail" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'members/member_detail/' . $row['member_id'] . '><i class="fa fa-eye"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['member_id'] <= 20) {
                $delete = '<a disabled="disabled"  class="" data-original-title="Delete" data-placement="top"  style="font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteMember(document.frmmemberlist,' . $row['member_id'] . ');"><i class="fa fa-trash-o"></i> </a>';
            }
            $nestedData[] = $edit . $delete;
            $data[] = $nestedData;
            $i++;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data, JSON_UNESCAPED_UNICODE);
    }

    function setDatatableMemberWallet() {
        $member_id = $this->uri->segment('4');
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'deposit',
            2 => 'withdraw',
            3 => 'join_money',
            4 => 'win_money',
            5 => 'note',
            6 => 'accountstatement_dateCreated',
            7 => 'note_id',
        );
        $totalData = $this->members->get_list_count_MemberWallet($member_id);
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM accountstatement";
        $sql .= " WHERE member_id = " . $member_id;
        $sql .= " AND (deposit != 0 OR withdraw != 0)";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND ( deposit LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  withdraw LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  join_money LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  win_money LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  note LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  accountstatement_dateCreated LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  note_id LIKE '%" . $requestData['search']['value'] . "%')";
        }
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query);
        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `accountstatement_dateCreated` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['deposit']);
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['withdraw']);
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['join_money']);
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['win_money']);
            $nestedData[] = $row['note'];

            $nestedData[] = $row['accountstatement_dateCreated'];
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

    function setDatatableMemberStates() {
        $member_id = $this->uri->segment('4');
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'match_name',
            2 => 'entry_fee',
            3 => 'total_win',
            4 => 'date_craeted',
        );
        $totalData = $this->members->get_list_count_MemberStates($member_id);
        $totalFiltered = $totalData;
        $sql = "SELECT m.match_name,m.entry_fee,mj.total_win,mj.date_craeted FROM match_join_member as mj";
        $sql .= " LEFT JOIN matches as m ON m.m_id = mj.match_id";
        $sql .= " WHERE member_id = " . $member_id;
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND ( m.match_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  m.entry_fee LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  mj.total_win LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  mj.date_craeted LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query);
        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY mj.date_craeted DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $row['match_name'];
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['entry_fee']);
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['total_win']);
            $nestedData[] = $row['date_craeted'];
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

    function setDatatableMemberReferral() {
        $member_id = $this->uri->segment('4');
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'user_name',
            2 => 'tot_amount',
            3 => 'member_status',
            4 => 'created_date'
        );
        $totalData = $this->members->get_list_count_MemberReferral($member_id);
        $totalFiltered = $totalData;
        $sql = "SELECT m.member_id,m.user_name,m.member_status,m.member_package_upgraded,m.created_date,sum(r.referral_amount) as tot_amount FROM member as m";
        $sql .= " LEFT JOIN referral as r ON m.member_id = r.from_mem_id";
        $sql .= " WHERE m.referral_id = " . $member_id;
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND ( user_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  created_date LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  member_status LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        $sql .= " GROUP BY m.member_id";
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query);
        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY created_date DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $row['user_name'];
            if ($row['tot_amount'] == NULL) {
                $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', 0);
            } else {
                $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['tot_amount']);
            }
            if ($row['member_status'] == '1' && $row['member_package_upgraded'] == '1') {
                $nestedData[] = "Rewarded";
            } else if ($row['member_status'] == '1') {
                $nestedData[] = "Registered";
            } else {
                $nestedData[] = "Inactive";
            }
            $nestedData[] = $row['created_date'];
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
