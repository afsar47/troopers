<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Lottery extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('lottery')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image');
        $this->load->model($this->path_to_view_admin . 'Lottery_model', 'lottery');
        $this->load->model($this->path_to_view_admin . 'Image_model', 'image_m');
    }

    function index() {        
        $data['lottery'] = true;
        $data['btn'] = $this->lang->line('text_add_lottery');
        $data['title'] = $this->lang->line('text_lottery');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('lotteryid') <= 2) || !$this->functions->check_permission('lottery_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_lottery'));
                redirect($this->path_to_view_admin . 'lottery/');
            } else {
                if ($result = $this->lottery->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_lottery'));
                    redirect($this->path_to_view_admin . 'lottery/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('lotteryid') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'lottery/');
            } else {
                if ($result = $this->lottery->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_lottery'));
                    redirect($this->path_to_view_admin . 'lottery/');
                }
            }
        }
        $data['lottery_data'] = $this->lottery->lottery_data();
        $this->load->view($this->path_to_view_admin . 'lottery_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 2) || !$this->functions->check_permission('lottery_delete')) {                    
                echo $this->lang->line('text_err_delete_lottery');                    
            } else {
                $this->lottery->multiDelete();                       
            }           
        }
    }

    function setDatatableLottery() {
        $requestData = $_REQUEST;
        $columns = array(            
            2 => 'lottery_title',
            3 => 'lottery_time',
            4 => 'lottery_fees',
            5 => 'lottery_prize',
            6 => 'lottery_size',
            7 => 'total_joined',
            8 => 'date_created',
            9 => 'lottery_status',
        );
        $totalData = $this->lottery->get_list_count_lottery();
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM lottery";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  lottery_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  lottery_title LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  lottery_time LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  lottery_fees LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  lottery_prize LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  lottery_size LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  lottery_status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  total_joined LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData[] = '<input type="checkbox" value="'. $row['lottery_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['lottery_title'];
            $nestedData[] = $row['lottery_time'];
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['lottery_fees']);
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['lottery_prize']);

            $nestedData[] = $row['lottery_size'];
            $nestedData[] = $row['total_joined'];
            $nestedData[] = $row['date_created'];

            $a = $b = $c = '';
            if ($row['lottery_status'] == '0')
                $a = 'selected';
            else if ($row['lottery_status'] == '1')
                $b = 'selected';
            else if ($row['lottery_status'] == '2')
                $c = 'selected';
            if ($row['lottery_status'] != '2') {
                $nestedData[] = '<select onChange="javascript: changePublishStatus(document.frmlotterylist,' . $row['lottery_id'] . ',this.value);">'
                        . '<option value="0" ' . $a . '>Deactive</option>'
                        . '<option value="1" ' . $b . '>Ongoing</option>'
                        . '<option value="2" ' . $c . '>Complete</option></select>';
            } else {
                $nestedData[] = '<span class="badge badge-success">Completed</span>';
            }
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'lottery/viewmember/' . $row['lottery_id'] . '><i class="fa fa-user"></i></a>&nbsp;&nbsp;<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'lottery/edit/' . $row['lottery_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['lottery_id'] <= 2) {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top" disabled="disabled" style="cursor: pointer;font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteLottery(document.frmlotterylist,' . $row['lottery_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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
        echo json_encode($json_data);
    }

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['lottery_image']['name']) && $_FILES['lottery_image']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['lottery_image']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["lottery_image"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        } else if ($this->input->post('image_id') == "" && $this->input->post('old_lottery_image') == "") {
            $this->form_validation->set_message('file_check', $this->lang->line('err_image_req'));
            return false;
        }
    }

    function insert() {
        $data['lottery_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_lottery');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_lottery');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['lottery_title'] = $this->input->post('lottery_title');
            $data['lottery_time'] = $this->input->post('lottery_time');
            $data['lottery_rules'] = $this->input->post('lottery_rules');
            $data['lottery_fees'] = $this->input->post('lottery_fees');
            $data['lottery_prize'] = $this->input->post('lottery_prize');
            $data['lottery_size'] = $this->input->post('lottery_size');
            $data['image_id'] = $this->input->post('image_id');

            $this->form_validation->set_rules('lottery_image', 'lang:text_image', 'callback_file_check');
            $this->form_validation->set_rules('lottery_title', 'lang:text_title', 'required', array('required' => $this->lang->line('err_lottery_title_req')));
            $this->form_validation->set_rules('lottery_time', 'lang:text_time', 'required', array('required' => $this->lang->line('err_lottery_time_req')));
            $this->form_validation->set_rules('lottery_rules', 'lang:text_rules', 'required', array('required' => $this->lang->line('err_lottery_rules_req')));
            $this->form_validation->set_rules('lottery_fees', 'lang:text_fees', 'required|numeric', array('required' => $this->lang->line('err_lottery_fees_req'), 'numeric' => $this->lang->line('err_number')));
            $this->form_validation->set_rules('lottery_prize', 'lang:text_prize', 'required|numeric', array('required' => $this->lang->line('err_lottery_prize_req'), 'numeric' => $this->lang->line('err_number')));
            $this->form_validation->set_rules('lottery_size', 'lang:text_size', 'required|numeric', array('required' => $this->lang->line('err_lottery_size_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {
                $data['images'] = $this->image_m->getImage();
                $this->load->view($this->path_to_view_admin . 'lottery_addedit', $data);
            } else {
                if ($result = $this->lottery->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_lottery'));
                    redirect($this->path_to_view_admin . 'lottery/');
                }
            }
        } else {
            $data['images'] = $this->image_m->getImage();
            $this->load->view($this->path_to_view_admin . 'lottery_addedit', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('lottery_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_lottery'));
            redirect($this->path_to_view_admin . 'lottery');
        }

        $data['lottery_addedit'] = true;
        $lottery_id = $this->uri->segment('4');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['title'] = $this->lang->line('text_edit_lottery');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('lottery_id') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_lottery'));
                redirect($this->path_to_view_admin . 'lottery/');
            } else {
                $data['lottery_title'] = $this->input->post('lottery_title');
                $data['lottery_time'] = $this->input->post('lottery_time');
                $data['lottery_rules'] = $this->input->post('lottery_rules');
                $data['lottery_fees'] = $this->input->post('lottery_fees');
                $data['lottery_prize'] = $this->input->post('lottery_prize');
                $data['lottery_size'] = $this->input->post('lottery_size');
                $data['image_id'] = $this->input->post('image_id');

                $this->form_validation->set_rules('lottery_image', 'lang:text_image', 'callback_file_check');
                $this->form_validation->set_rules('lottery_title', 'lang:text_title', 'required', array('required' => $this->lang->line('err_lottery_title_req')));
                $this->form_validation->set_rules('lottery_time', 'lang:text_time', 'required', array('required' => $this->lang->line('err_lottery_time_req')));
                $this->form_validation->set_rules('lottery_rules', 'lang:text_rules', 'required', array('required' => $this->lang->line('err_lottery_rules_req')));
                $this->form_validation->set_rules('lottery_fees', 'lang:text_fees', 'required|numeric', array('required' => $this->lang->line('err_lottery_fees_req'), 'numeric' => $this->lang->line('err_number')));
                $this->form_validation->set_rules('lottery_prize', 'lang:text_prize', 'required|numeric', array('required' => $this->lang->line('err_lottery_prize_req'), 'numeric' => $this->lang->line('err_number')));
                $this->form_validation->set_rules('lottery_size', 'lang:text_size', 'required|numeric', array('required' => $this->lang->line('err_lottery_size_req'), 'numeric' => $this->lang->line('err_number')));

                if ($this->form_validation->run() == FALSE) {
                    $data['images'] = $this->image_m->getImage();
                    $this->load->view($this->path_to_view_admin . 'lottery_addedit', $data);
                } else {
                    if ($result = $this->lottery->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_lottery'));
                        redirect($this->path_to_view_admin . 'lottery/');
                    }
                }
            }
        } else {
            $data['images'] = $this->image_m->getImage();
            $data['lottery_detail'] = $this->lottery->getlotteryById($lottery_id);
            $this->load->view($this->path_to_view_admin . 'lottery_addedit', $data);
        }
    }

    public function viewmember() {
        if(!$this->functions->check_permission('lottery_view')) {
            redirect($this->path_to_view_admin . 'lottery');
        }

        $data['lottery_member'] = true;
        $lottery_id = $this->uri->segment('4');
        $data['title'] = $this->lang->line('text_lottery_member_list');
        if ($this->input->post('update') == 'Update') {
            if ($this->system->demo_user == 1 && $this->input->post('lottery_id') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_update_lottery'));
                redirect($this->path_to_view_admin . 'lottery/');
            } else {
                $this->load->library('user_agent');
                $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                $ip = $this->input->ip_address();
                if ($this->input->post('member_id')) {
                    $lottery = $this->lottery->getlotteryById($this->input->post('lottery_id'));
                    $this->db->select('GROUP_CONCAT(member_id) as member_id');
                    $this->db->where('lottery_id', $this->input->post('lottery_id'));
                    $this->db->where('member_id !=', $this->input->post('member_id'));
                    $query = $this->db->get('lottery_member');
                    $member_ids = explode(',', $query->row_array()['member_id']);

                    foreach ($member_ids as $member_id) {

                        $this->db->select('*');
                        $this->db->where("member_id", $member_id);
                        $this->db->where("lottery_id", $this->input->post('lottery_id'));
                        $this->db->where('note_id', '11');
                        $query = $this->db->get('accountstatement');
                        $result_acc = $query->row_array();

                        $this->db->select('*');
                        $this->db->where('member_id', $member_id);
                        $member = $this->db->get('member')->row();
                        if ($query->num_rows() > 0) {
                            $wallet_balance = $member->wallet_balance - $result_acc['deposit'];
                            $accountstm_data = array(
                                'deposit' => 0,
                                'win_money' => $wallet_balance,
                            );
                            $this->db->where("member_id", $member_id);
                            $this->db->where("lottery_id", $this->input->post('lottery_id'));
                            $this->db->where('note_id', '11');
                            $this->db->update('accountstatement', $accountstm_data);

                            $member_data = array(
                                'wallet_balance' => $wallet_balance,
                            );
                            $this->db->where('member_id', $member_id);
                            $this->db->update('member', $member_data);
                        }
                        $lottery_member = array(
                            'lottery_prize' => 0,
                            'status' => '0',
                        );
                        $this->db->where('lottery_id', $this->input->post('lottery_id'));
                        $this->db->where('member_id', $member_id);
                        $this->db->update('lottery_member', $lottery_member);
                    }
                    $this->db->select('*');
                    $this->db->where("member_id", $this->input->post('member_id'));
                    $this->db->where("lottery_id", $this->input->post('lottery_id'));
                    $this->db->where('note_id', '11');
                    $query = $this->db->get('accountstatement');
                    $result_acc = $query->row_array();

                    $this->db->select('*');
                    $this->db->where('member_id', $this->input->post('member_id'));
                    $member1 = $this->db->get('member')->row();
                    if ($query->num_rows() <= 0) {

                        $wallet_balance = $member1->wallet_balance + $lottery['lottery_prize'];
                        $accountstm_data = array(
                            'member_id' => $this->input->post('member_id'),
                            'deposit' => $lottery['lottery_prize'],
                            'withdraw' => 0,
                            'join_money' => $member1->join_money,
                            'win_money' => $wallet_balance,
                            'lottery_id' => $this->input->post('lottery_id'),
                            'note' => 'Lottery Reward',
                            'note_id' => '11',
                            'entry_from' => '3',
                            'ip_detail' => $ip,
                            'browser' => $browser,
                            'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('accountstatement', $accountstm_data);
                        $member_data = array(
                            'wallet_balance' => $wallet_balance,
                        );
                        $this->db->where('member_id', $this->input->post('member_id'));
                        $this->db->update('member', $member_data);
                    } else {
                        if ($result_acc['deposit'] == 0) {
                            $wallet_balance = $member1->wallet_balance + $lottery['lottery_prize'];
                            $accountstm_data = array(
                                'deposit' => $lottery['lottery_prize'],
                                'win_money' => $wallet_balance,
                            );
                            $this->db->where("member_id", $this->input->post('member_id'));
                            $this->db->where("lottery_id", $this->input->post('lottery_id'));
                            $this->db->where('note_id', '11');
                            $this->db->update('accountstatement', $accountstm_data);

                            $member_data = array(
                                'wallet_balance' => $wallet_balance,
                            );
                            $this->db->where('member_id', $this->input->post('member_id'));
                            $this->db->update('member', $member_data);
                        }
                    }
                    $lottery_member = array(
                        'lottery_prize' => $lottery['lottery_prize'],
                        'status' => '1',
                    );
                    $this->db->where('lottery_id', $this->input->post('lottery_id'));
                    $this->db->where('member_id', $this->input->post('member_id'));
                    $this->db->update('lottery_member', $lottery_member);
                } else {
                    $lottery = $this->lottery->getlotteryById($this->input->post('lottery_id'));
                    $this->db->select('GROUP_CONCAT(member_id) as member_id');
                    $this->db->where('lottery_id', $this->input->post('lottery_id'));
                    $query = $this->db->get('lottery_member');
                    $res = explode(',', $query->row_array()['member_id']);
                    $rand_member_id = $res[array_rand($res, 1)];

                    $this->db->select('*');
                    $this->db->where('member_id', $rand_member_id);
                    $member = $this->db->get('member')->row();
                    $wallet_balance = $member->wallet_balance + $lottery['lottery_prize'];
                    $accountstm_data = array(
                        'member_id' => $rand_member_id,
                        'deposit' => $lottery['lottery_prize'],
                        'withdraw' => 0,
                        'join_money' => $member->join_money,
                        'win_money' => $wallet_balance,
                        'lottery_id' => $this->input->post('lottery_id'),
                        'note' => 'Lottery Reward',
                        'note_id' => '11',
                        'entry_from' => '3',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('accountstatement', $accountstm_data);
                    $member_data = array(
                        'wallet_balance' => $wallet_balance,
                    );
                    $this->db->where('member_id', $rand_member_id);
                    $this->db->update('member', $member_data);

                    $lottery_member = array(
                        'lottery_prize' => $lottery['lottery_prize'],
                        'status' => '1',
                    );
                    $this->db->where('lottery_id', $this->input->post('lottery_id'));
                    $this->db->where('member_id', $rand_member_id);
                    $this->db->update('lottery_member', $lottery_member);
                }
                $this->session->set_flashdata('notification', $this->lang->line('text_succ_update_lottery'));
                redirect($this->path_to_view_admin . 'lottery/viewmember/' . $this->input->post('lottery_id'));
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'lottery_member', $data);
        }
    }

    function setDatatableLotteryMember() {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'lottery_member_id',
            1 => 'user_name',
            2 => 'status',
            3 => 'date_created',
        );
        $totalData = $this->lottery->get_list_count_lotteryMember($this->uri->segment('4'));
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM lottery_member as lm ";
        $sql .= "LEFT JOIN member as m ON m.member_id = lm.member_id ";
        $sql .= "where lm.lottery_id = " . $this->uri->segment('4');
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND(  lottery_member_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  user_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  date_created LIKE '%" . $requestData['search']['value'] . "%')";
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
            $checked = '';
            if ($row['status'] == '1')
                $checked = 'checked';
            $nestedData[] = "<input type='radio' name='member_id' value='" . $row['member_id'] . "' $checked>";
            $nestedData[] = $i;
            $nestedData[] = '<a href=' . base_url() . $this->path_to_view_admin . 'members/member_detail/' . $row['member_id'] . '>' . $row['user_name'] . '</a>';
            if ($row['status'] == '1')
                $nestedData[] = 'Winner';
            else
                $nestedData[] = '-';
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
