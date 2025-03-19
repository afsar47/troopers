<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Matches extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }

        if(!$this->functions->check_permission('matches')) {
            redirect($this->path_to_view_admin . 'login');
        }

        $this->con = $this->functions->mysql_connection();
        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image');
        $this->img_size_array = array(100 => 100, 1000 => 500);
        $this->load->model($this->path_to_view_admin . 'Match_model', 'matches');
    }

    function index() {
        $data['match'] = true;
        $data['btn'] = $this->lang->line('text_add_match');
        $data['title'] = $this->lang->line('text_match');

        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('mid') <= 7) || !$this->functions->check_permission('matches_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_match'));
                redirect($this->path_to_view_admin . 'matches/');
            } else {
                if ($result = $this->matches->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_match'));
                    redirect($this->path_to_view_admin . 'matches/');
                }
            }
        } elseif ($this->input->post('action') == "change_pin_status") {
            if (($this->system->demo_user == 1 && $this->input->post('mid') <= 7)) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_pin_match'));
                redirect($this->path_to_view_admin . 'matches/');
            } else {
                if ($result = $this->matches->changePinStatus()) {
                    if ($this->input->post('publish') == '1')
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_pinned_match'));
                    else
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_unpinned_match'));
                    redirect($this->path_to_view_admin . 'matches/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if (($this->system->demo_user == 1 && $this->input->post('mid') <= 7)) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status_match'));
                redirect($this->path_to_view_admin . 'matches/');
            } else {
                if ($result = $this->matches->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_match'));
                    redirect($this->path_to_view_admin . 'matches/');
                }
            }
        }
        $this->load->view($this->path_to_view_admin . 'match_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 7) || !$this->functions->check_permission('matches_delete')) {                    
                echo $this->lang->line('text_err_delete_match');                    
            } else {
                $this->matches->multiDelete();                       
            }           
        }
    }    

    function setDatatableMatch() {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'm_id',
            2 => 'game_name',
            3 => 'match_name',            
            4 => 'match_time',
            5 => 'number_of_position',
            6 => 'no_of_player',
            7 => 'win_prize',
            8 => 'entry_fee',
            9 => 'match_type',
            10 => 'match_status',
        );
        $totalData = $this->matches->get_list_count_match();
        $totalFiltered = $totalData;
        $sql = "SELECT m.*,g.game_name FROM matches as m";
        $sql .= " LEFT Join game as g ON g.game_id = m.game_id";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  m_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR g.game_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  match_name LIKE '%" . $requestData['search']['value'] . "%' ";            
            $sql .= " OR  match_time LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  number_of_position LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  no_of_player LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  win_prize LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  entry_fee LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  match_type LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  match_status LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData[] = '<input type="checkbox" value="'. $row['m_id'] .'" class="all_inputs">';
            $nestedData[] = $row['m_id'];
            $nestedData[] = $row['game_name'];
            $nestedData[] = $row['match_name'];           
            $nestedData[] = $row['match_time'];
            $nestedData[] = $row['number_of_position'];
            $nestedData[] = $row['no_of_player'];
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['win_prize']);
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['entry_fee']);
            if ($row['match_type'] == '0') {
                $nestedData[] = 'Unpaid';
            } else {
                $nestedData[] = 'Paid';
            }
            if ($row['match_status'] == '0') {
                $nestedData[] = '<select onChange="javascript: changePublishStatus(document.frmmatchlist,' . $row['m_id'] . ',this.value);">'
                        . '<option value="0" selected>Deactive</option>'
                        . '<option value="1">Active</option>'
                        . '<option value="3">Start</option>'
                        . '<option value="2">Complete</option>'
                        . '<option value="4">Cancel</option></select>';                      
            } elseif ($row['match_status'] == '1') {
                $nestedData[] = '<select onChange="javascript: changePublishStatus(document.frmmatchlist,' . $row['m_id'] . ',this.value);">'
                        . '<option value="0" >Deactive</option>'
                        . '<option value="1" selected>Active</option>'
                        . '<option value="3">Start</option>'
                        . '<option value="2">Complete</option>'
                        . '<option value="4">Cancel</option></select>';
            } elseif ($row['match_status'] == '2') {
                $nestedData[] = '<select onChange="javascript: changePublishStatus(document.frmmatchlist,' . $row['m_id'] . ',this.value);">'
                        . '<option value="0" >Deactive</option>'
                        . '<option value="1" >Active</option>'
                        . '<option value="3">Start</option>'
                        . '<option value="2" selected>Complete</option>'
                        . '<option value="4">Cancel</option></select>';
            } elseif ($row['match_status'] == '3') {
                $nestedData[] = '<select onChange="javascript: changePublishStatus(document.frmmatchlist,' . $row['m_id'] . ',this.value);">'
                        . '<option value="0" >Deactive</option>'
                        . '<option value="1" >Active</option>'
                        . '<option value="3" selected>Start</option>'
                        . '<option value="2">Complete</option>'
                        . '<option value="4">Cancel</option></select>';
            } elseif ($row['match_status'] == '4') {
                $nestedData[] = '<select onChange="javascript: changePublishStatus(document.frmmatchlist,' . $row['m_id'] . ',this.value);" disabled>'
                        . '<option value="0" >Deactive</option>'
                        . '<option value="1" >Active</option>'
                        . '<option value="3" >Start</option>'
                        . '<option value="2">Complete</option>'
                        . '<option value="4" selected>Cancel</option></select>';
            }
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'matches/edit/' . $row['m_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['m_id'] <= 7) {
                $delete = '<a class = "" disabled="disabled" data-original-title = "Delete" data-placement = "top" style = "font-size:18px;color:#007bff" ><i class = "fa fa-trash-o"></i> </a>&nbsp;';
            } else {
                $delete = '<a class = "" data-original-title = "Delete" data-placement = "top" style = "cursor: pointer;font-size:18px;color:#007bff" onClick = "javascript: confirmDeleteMember(document.frmmatchlist,' . $row['m_id'] . ');"><i class = "fa fa-trash-o"></i> </a>&nbsp;';
            }
            $nestedData[] = $edit . $delete;
            if ($row['pin_match'] == 1)
                $pin = '<a class = "" data-original-title = "Unpin" data-placement = "top" style = "cursor: pointer;font-size:18px;color:#007bff" onClick = "javascript: changePin(document.frmmatchlist,' . $row['m_id'] . ',0);"><i class="fa fa-thumb-tack" aria-hidden="true"></i></a>';
            else
                $pin = '<a class = "" data-original-title = "Pin" data-placement = "top" style = "cursor: pointer;font-size:18px;color:#9ecaf8" onClick = "javascript: changePin(document.frmmatchlist,' . $row['m_id'] . ',1);"><i class="fa fa-thumb-tack" aria-hidden="true"></i></a>';
            $nestedData[] = $pin . '<a  class="" data-original-title="Detail" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" href=' . base_url() . $this->path_to_view_admin . 'matches/member_join_match/' . $row['m_id'] . '><i class="fa fa-trophy"></i> </a>'
                    . '<a  class="" data-original-title="Positiion" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" href=' . base_url() . $this->path_to_view_admin . 'matches/member_position/' . $row['m_id'] . '><i class="fa fa-bullseye"></i> </a>';
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

    function member_position() {

        if(!$this->functions->check_permission('matches_member_position')) {
            redirect($this->path_to_view_admin . 'matches');
        }

        $data['member_position'] = true;
        $data['title'] = $this->lang->line('text_member_position');
        $m_id = $this->uri->segment('4');
        $data['match_type'] = $this->matches->match_type($m_id);
        $data['positions'] = $this->matches->member_position($m_id);
        $data['match_detail'] = $this->matches->getmatchById($m_id);
        $this->load->view($this->path_to_view_admin . 'member_position', $data);
    }

    function member_join_match() {
        if(!$this->functions->check_permission('matches_member_join_match')) {
            redirect($this->path_to_view_admin . 'matches');
        }        

        $data['member_join_match'] = true;
        $data['title'] = $this->lang->line('text_member_join_match');
        $m_id = $this->uri->segment('4');
        $data['match_details'] = $this->matches->getmember_join_match($m_id);
        $data['match'] = $this->matches->getmatchById_alldata($m_id);
        $this->load->view($this->path_to_view_admin . 'member_join_match', $data);
    }

    function update_member_join_match() {        
        
        $data = json_decode($this->input->post('data'))[0];
        $match_join_member_ids = $data->match_join_member_ids;
        $member_ids = $data->member_ids;
        $pubg_ids = $data->pubg_ids;
        $match_ids = $data->match_ids;
        $places = $data->places;
        $place_points = $data->place_points;
        $killeds = $data->killeds;
        $wins = $data->wins;
        $total_wins = $data->total_wins;
        $bonuses = $data->bonuses;
        $win_prizes = $data->win_prizes;
        $total_refunds = $data->total_refunds;
        $j = 0;
        $this->load->library('user_agent');
        $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
        $ip = $this->input->ip_address();
        foreach ($match_join_member_ids as $i) {

            $this->db->select('*');
            $this->db->where("member_id", $member_ids[$j]);
            $this->db->where("match_id", $match_ids[$j]);
            $this->db->where("match_join_member_id", $match_join_member_ids[$j]);
            $member_exist = $this->db->get('match_join_member')->num_rows();
            
            if($member_exist > 0) {

                if ((!$this->functions->check_permission('matches_member_join_match')) || ($this->system->demo_user == 1 && $match_ids[$j] <= 7)) {                    
                    echo false;
                    exit;
                } else {
                    $this->db->select('*');
                    $this->db->where("member_id", $member_ids[$j]);
                    $this->db->where("match_id", $match_ids[$j]);
                    $this->db->where("pubg_id", $pubg_ids[$j]);
                    $this->db->where('note_id', '5');
                    $query = $this->db->get('accountstatement');
                    $result_acc = $query->row_array();
                    if ($query->num_rows() > 0) {
                        $this->db->select('*');
                        $this->db->where("match_join_member_id", $match_join_member_ids[$j]);
                        $query1 = $this->db->get('match_join_member');
                        $result_match_member = $query1->row_array();
                        $amount = $result_acc['deposit'] - $result_match_member['total_win'] + $total_wins[$j];
                        $this->db->select('*');
                        $this->db->where("member_id", $member_ids[$j]);
                        $query2 = $this->db->get('member');
                        $result = $query2->row_array();
                        $wallet_balance = $result['wallet_balance'] - $result_match_member['total_win'] + $total_wins[$j];
                        $accountstm_data = array(
                            'deposit' => $total_wins[$j],
                            'win_money' => $wallet_balance,
                        );
                        $this->db->where('member_id', $member_ids[$j]);
                        $this->db->where('match_id', $match_ids[$j]);
                        $this->db->where('pubg_id', $pubg_ids[$j]);
                        $this->db->where('note_id', '5');
                        $this->db->update('accountstatement', $accountstm_data);
                        $member_data = array(
                            'wallet_balance' => $wallet_balance,
                        );
                        $this->db->where('member_id', $member_ids[$j]);
                        $this->db->update('member', $member_data);
                    } else {
                        $this->db->select('*');
                        $this->db->where("member_id", $member_ids[$j]);
                        $query2 = $this->db->get('member');
                        $result = $query2->row_array();
                        $wallet_balance = $result['wallet_balance'] + $total_wins[$j];
                        $accountstm_data = array(
                            'member_id' => $member_ids[$j],
                            'pubg_id' => $pubg_ids[$j],
                            'deposit' => $total_wins[$j],
                            'withdraw' => 0,
                            'join_money' => $result['join_money'],
                            'win_money' => $wallet_balance,
                            'match_id' => $match_ids[$j],
                            'note' => 'Match Reward',
                            'note_id' => '5',
                            'entry_from' => '3',
                            'ip_detail' => $ip,
                            'browser' => $browser,
                            'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('accountstatement', $accountstm_data);
                        $member_data = array(
                            'wallet_balance' => $wallet_balance,
                        );
                        $this->db->where('member_id', $member_ids[$j]);
                        $this->db->update('member', $member_data);
                    }
                    $this->db->select('*');
                    $this->db->where("member_id", $member_ids[$j]);
                    $this->db->where("match_id", $match_ids[$j]);
                    $this->db->where("pubg_id", $pubg_ids[$j]);
                    $this->db->where('note_id', '6');
                    $query = $this->db->get('accountstatement');
                    $result_acc = $query->row_array();
                    if ($query->num_rows() > 0) {
                        $this->db->select('*');
                        $this->db->where("match_join_member_id", $match_join_member_ids[$j]);
                        $query1 = $this->db->get('match_join_member');
                        $result_match_member = $query1->row_array();
                        $amount = $result_acc['deposit'] - $result_match_member['refund'] + $total_refunds[$j];
                        $this->db->select('*');
                        $this->db->where("member_id", $member_ids[$j]);
                        $query2 = $this->db->get('member');
                        $result = $query2->row_array();
                        $join_money = $result['join_money'] - $result_match_member['refund'] + $total_refunds[$j];
                        $accountstm_data = array(
                            'deposit' => $total_refunds[$j],
                            'join_money' => $join_money,
                        );
                        $this->db->where('member_id', $member_ids[$j]);
                        $this->db->where('match_id', $match_ids[$j]);
                        $this->db->where('pubg_id', $pubg_ids[$j]);
                        $this->db->where('note_id', '6');
                        $this->db->where('note', 'Refund');
                        $this->db->update('accountstatement', $accountstm_data);
                        $member_data = array(
                            'join_money' => $join_money,
                        );
                        $this->db->where('member_id', $member_ids[$j]);
                        $this->db->update('member', $member_data);
                    } else if ($total_refunds[$j] != 0 || $total_refunds[$j] != '0') {
                        $this->db->select('*');
                        $this->db->where("member_id", $member_ids[$j]);
                        $query2 = $this->db->get('member');
                        $result = $query2->row_array();
                        $join_money = $result['join_money'] + $total_refunds[$j];
                        $accountstm_data = array(
                            'member_id' => $member_ids[$j],
                            'pubg_id' => $pubg_ids[$j],
                            'deposit' => $total_refunds[$j],
                            'withdraw' => 0,
                            'join_money' => $join_money,
                            'win_money' => $result['wallet_balance'],
                            'match_id' => $match_ids[$j],
                            'note' => 'Refund',
                            'note_id' => '6',
                            'entry_from' => '3',
                            'ip_detail' => $ip,
                            'browser' => $browser,
                            'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('accountstatement', $accountstm_data);
                        $member_data = array(
                            'join_money' => $join_money,
                        );
                        $this->db->where('member_id', $member_ids[$j]);
                        $this->db->update('member', $member_data);
                    }
                    $data = array(
                        'place' => $places[$j],
                        'place_point' => $place_points[$j],
                        'bonus' => $bonuses[$j],
                        'win_prize' => $win_prizes[$j],
                        'killed' => $killeds[$j],
                        'win' => $wins[$j],
                        'total_win' => $total_wins[$j],
                        'refund' => $total_refunds[$j],
                    );
                    $this->db->where('match_join_member_id', $match_join_member_ids[$j]);
                    $this->db->update('match_join_member', $data);
                    $j++;
                }
            }
        }
        echo true;exit;
    }

    function delete_join_member() {

        if(!$this->functions->check_permission('matches_member_position') || $this->system->demo_user == 1) {
            redirect($this->path_to_view_admin . 'matches');
        }

        $data['member_position'] = true;
        $mj_id = $this->uri->segment('4');

        $this->db->select('mj.*,m.entry_fee,m.no_of_player');
        $this->db->where("match_join_member_id", $mj_id);
        $this->db->join("matches as m", "m.m_id = mj.match_id");
        $query1 = $this->db->get('match_join_member as mj');
        $match_join_member = $query1->row_array();
        $this->db->select('*');
        $this->db->where("member_id", $match_join_member['member_id']);
        $query2 = $this->db->get('member');
        $result = $query2->row_array();
        $join_money = $result['join_money'] + $match_join_member['entry_fee'];

        if ($match_join_member['entry_fee'] > 0) {
            $this->load->library('user_agent');
            $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
            $ip = $this->input->ip_address();
            $accountstm_data = array(
                'member_id' => $match_join_member['member_id'],
                'pubg_id' => $match_join_member['pubg_id'],
                'deposit' => $match_join_member['entry_fee'],
                'withdraw' => 0,
                'join_money' => $join_money,
                'win_money' => $result['wallet_balance'],
                'match_id' => $match_join_member['match_id'],
                'note' => 'Refund',
                'note_id' => '6',
                'entry_from' => '3',
                'ip_detail' => $ip,
                'browser' => $browser,
                'accountstatement_dateCreated' => date('Y-m-d H:i:s')
            );
            $this->db->insert('accountstatement', $accountstm_data);
        }
        $member_data = array(
            'join_money' => $join_money,
        );
        $this->db->where('member_id', $match_join_member['member_id']);
        $this->db->update('member', $member_data);

        $no_of_player = 0;
        if ($match_join_member['no_of_player'] > 0) {
            $no_of_player = $match_join_member['no_of_player'] - 1;
        }
        $match_data = array(
            'no_of_player' => $no_of_player,
        );
        $this->db->where('m_id', $match_join_member['match_id']);
        $this->db->update('matches', $match_data);

        $this->db->where('match_join_member_id', $mj_id);
        $this->db->delete('match_join_member');
        redirect($this->path_to_view_admin . 'matches/member_position/' . $match_join_member['match_id']);
    }

    function get_result_notification() {
        $result = $this->matches->get_result_notification($_GET['match_id']);
        echo json_encode($result);
    }

    function add_result_notification() {
        if (!$this->functions->check_permission('matches_member_join_match') || ($this->system->demo_user == 1 && $_POST['match_id'] <= 7)) {
            echo false;
        } else {
            $data['result_notification'] = $_POST['result_notification'];
            $data['match_id'] = $_POST['match_id'];
            if (empty($data['result_notification'])) {
                echo 'empty';
            } else {
                $result = $this->matches->result_notification($data);
                if ($result == true) {
                    echo true;
                }
            }
        }
    }

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['match_banner']['name']) && $_FILES['match_banner']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['match_banner']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["match_banner"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    public function price_percent_check() {
        $win_prize = $this->input->post('win_prize');        
       
        $admin_profit = $this->system->admin_profit;
        
        $total = 100 - ($admin_profit + $win_prize);
        
        if($total > 0) {
            echo sprintf("%.2f",$total);exit;                  
        } else {
            echo '0';
        }
    }

    function insert() {
        $data['match_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_match');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_match');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $thumb_sizes = $this->img_size_array;
            $data['match_name'] = $this->input->post('match_name');
            $data['match_time'] = $this->input->post('match_time');
            $data['win_prize'] = $this->input->post('win_prize');
            $data['per_kill'] = $this->input->post('per_kill');
            $data['entry_fee'] = $this->input->post('entry_fee');
            $data['type'] = $this->input->post('type');
            $data['MAP'] = $this->input->post('MAP');
            $data['game_id'] = $this->input->post('game_id');
            $data['match_type'] = $this->input->post('match_type');
            $data['match_url'] = $this->input->post('match_url');
            $data['number_of_position'] = $this->input->post('number_of_position');
            $data['match_banner'] = $this->input->post('match_banner');
            $data['prize_description'] = $this->input->post('prize_description');
            $data['match_sponsor'] = $this->input->post('match_sponsor');
            $data['match_desc'] = $this->input->post('match_desc');
            $data['match_private_desc'] = $this->input->post('match_private_desc');
            $data['image_id'] = $this->input->post('image_id');

            $this->form_validation->set_rules('match_banner', 'lang:text_browse_banner', 'callback_file_check');
            $this->form_validation->set_rules('match_name', 'lang:text_match_name', 'required', array('required' => $this->lang->line('err_match_name_req')));
            $this->form_validation->set_rules('match_time', 'lang:text_match_schedule', 'required', array('required' => $this->lang->line('err_match_time_req')));
            $this->form_validation->set_rules('win_prize', 'lang:text_win_prize', 'required', array('required' => $this->lang->line('err_win_prize_req')));
            $this->form_validation->set_rules('per_kill', 'lang:text_per_kill', 'required', array('required' => $this->lang->line('err_per_kill_req')));
            $this->form_validation->set_rules('entry_fee', 'lang:text_entry_fee', 'required', array('required' => $this->lang->line('err_entry_fee_req')));
            $this->form_validation->set_rules('type', 'lang:text_type', 'required', array('required' => $this->lang->line('err_type_req')));
            $this->form_validation->set_rules('MAP', 'lang:text_map', 'required', array('required' => $this->lang->line('err_MAP_req')));
            $this->form_validation->set_rules('game_id', 'lang:text_game', 'required', array('required' => $this->lang->line('err_game_id_req')));
            $this->form_validation->set_rules('match_type', 'lang:text_match_type', 'required', array('required' => $this->lang->line('err_match_type_req')));
            $this->form_validation->set_rules('match_desc', 'lang:text_match_description', 'required', array('required' => $this->lang->line('err_match_desc_req')));
            $this->form_validation->set_rules('match_url', 'lang:text_match_url', 'required|valid_url', array('required' => $this->lang->line('err_match_url_req'), 'valid_url' => $this->lang->line('err_match_url_valid')));
            $this->form_validation->set_rules('number_of_position', 'lang:text_total_player', 'required|numeric|greater_than[0]', array('required' => $this->lang->line('err_number_of_position_req'), 'numeric' => $this->lang->line('err_number_of_position_number'), 'greater_than' => $this->lang->line('err_number_of_position_min1')));


            if ($this->form_validation->run() == FALSE) {
                $data['games'] = $this->matches->getgame();
                $data['images'] = $this->matches->getImage();
                $this->load->view($this->path_to_view_admin . 'match_addedit', $data);
            } else {
                
                if ($result = $this->matches->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_match'));
                    redirect($this->path_to_view_admin . 'matches/');
                }
            }
        } else {
            $data['games'] = $this->matches->getgame();
            $data['images'] = $this->matches->getImage();
            $this->load->view($this->path_to_view_admin . 'match_addedit', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('matches_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_match'));
            redirect($this->path_to_view_admin . 'matches');
        }

        $data['match_addedit'] = true;
        $m_id = $this->uri->segment('4');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['title'] = $this->lang->line('text_edit_match');
        if ($this->input->post('id_submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('mid') <= 7) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_update_roomid_pass'));
                redirect($this->path_to_view_admin . 'matches/');
            } else {
                $data['room_description'] = $this->input->post('room_description');
                
                $this->form_validation->set_rules('room_description', 'lang:text_room_description', 'required');
                
                if ($this->form_validation->run() == FALSE) {
                    $data['games'] = $this->matches->getgame();
                    $this->load->view($this->path_to_view_admin . 'match_addedit', $data);
                } else {
                    if ($result = $this->matches->update_only_id()) {
                        $this->db->select('player_id,mobile_no,first_name,country_code,push_noti');
                        $this->db->where('match_id', $this->input->post('m_id'));
                        $this->db->join('member as m', 'm.member_id = mj.member_id');
                        $this->db->group_by('m.member_id');
                        $query = $this->db->get('match_join_member as mj');
                        $mem_country = $query->result();
                        $data['player_ids'] = array();
                        foreach ($mem_country as $mem) {
                            if ($mem->player_id != '' && $mem->push_noti == '1')
                                $data['player_ids'][] = $mem->player_id;
                        }
                        $this->db->select('m.match_name,m.match_type');
                        $this->db->where('m.m_id', $this->input->post('m_id'));
                        $qr = $this->db->get('matches as m');
                        $match = $qr->row_array();
                        if (!empty($data['player_ids']) && $data['push_noti'] == '1')
                            $this->functions->sendMessageMember("Room Detail Updated for " . $match['match_name'] . ",Please Check from ". $this->system->company_name .".", $data['player_ids'], "ROOM ID & PASSWORD UPDATED");

                        if ($this->system->msg91_otp == '1' || $this->system->msg91_otp == 1) {
                            foreach ($mem_country as $mem) {
                                $m_number = $mem->country_code . $mem->mobile_no;
                                $message = "Dear " . $mem->first_name . ",\nRoom Detail Updated for " . $match['match_name'] . "\nPlease Check from" . $this->system->company_name . "\n" . base_url();
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?sender=" . $this->system_config['msg91_sender'] . "&route=" . $this->system_config['msg91_route'] . "&mobiles=" . $m_number . "&authkey=" . $this->system->msg91_authkey . "&encrypt=0&country=" . $mem->country_code . "&message=" . urlencode($message) . "&response=json",
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
                                $err = curl_error($curl);
                                curl_close($curl);
                            }
                        }
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_update_roomid_pass'));
                        redirect($this->path_to_view_admin . 'matches/');
                    }
                }
            }
        } elseif ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('mid') <= 7) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_match'));
                redirect($this->path_to_view_admin . 'matches/');
            } else {
                $data['match_name'] = $this->input->post('match_name');
                $data['match_time'] = $this->input->post('match_time');
                $data['win_prize'] = $this->input->post('win_prize');
                $data['per_kill'] = $this->input->post('per_kill');
                $data['entry_fee'] = $this->input->post('entry_fee');
                $data['type'] = $this->input->post('type');
                $data['MAP'] = $this->input->post('MAP');
                $data['game_id'] = $this->input->post('game_id');
                $data['match_type'] = $this->input->post('match_type');
                $data['match_url'] = $this->input->post('match_url');
                $data['number_of_position'] = $this->input->post('number_of_position');
                $data['match_banner'] = $this->input->post('match_banner');
                $data['prize_description'] = $this->input->post('prize_description');
                $data['match_sponsor'] = $this->input->post('match_sponsor');
                $data['match_desc'] = $this->input->post('match_desc');
                $data['image_id'] = $this->input->post('image_id');
                $data['match_private_desc'] = $this->input->post('match_private_desc');

                $this->form_validation->set_rules('match_banner', 'lang:text_browse_banner', 'callback_file_check');
                $this->form_validation->set_rules('match_name', 'lang:text_match_name', 'required', array('required' => $this->lang->line('err_match_name_req')));
                $this->form_validation->set_rules('match_time', 'lang:text_match_schedule', 'required', array('required' => $this->lang->line('err_match_time_req')));
                $this->form_validation->set_rules('win_prize', 'lang:text_win_prize', 'required', array('required' => $this->lang->line('err_win_prize_req')));
                $this->form_validation->set_rules('per_kill', 'lang:text_per_kill', 'required', array('required' => $this->lang->line('err_per_kill_req')));
                $this->form_validation->set_rules('entry_fee', 'lang:text_entry_fee', 'required', array('required' => $this->lang->line('err_entry_fee_req')));
                $this->form_validation->set_rules('type', 'lang:text_type', 'required', array('required' => $this->lang->line('err_type_req')));
                $this->form_validation->set_rules('MAP', 'lang:text_map', 'required', array('required' => $this->lang->line('err_MAP_req')));
                $this->form_validation->set_rules('game_id', 'lang:text_game', 'required', array('required' => $this->lang->line('err_game_id_req')));
                $this->form_validation->set_rules('match_type', 'lang:text_match_type', 'required', array('required' => $this->lang->line('err_match_type_req')));
                $this->form_validation->set_rules('match_desc', 'lang:text_match_description', 'required', array('required' => $this->lang->line('err_match_desc_req')));
                $this->form_validation->set_rules('match_url', 'lang:text_match_url', 'required|valid_url', array('required' => $this->lang->line('err_match_url_req'), 'valid_url' => $this->lang->line('err_match_url_valid')));
                $this->form_validation->set_rules('number_of_position', 'lang:text_total_player', 'required|numeric|greater_than[0]', array('required' => $this->lang->line('err_number_of_position_req'), 'numeric' => $this->lang->line('err_number_of_position_number'), 'greater_than' => $this->lang->line('err_number_of_position_min1')));

                if ($this->form_validation->run() == FALSE) {
                    $data['games'] = $this->matches->getgame();
                    $data['images'] = $this->matches->getImage();
                    $this->load->view($this->path_to_view_admin . 'match_addedit', $data);
                } else {
                    if ($result = $this->matches->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_match'));
                        redirect($this->path_to_view_admin . 'matches/');
                    }
                }
            }
        } else {
            $data['match_detail'] = $this->matches->getmatchById($m_id);
            $data['games'] = $this->matches->getgame();
            $data['images'] = $this->matches->getImage();
            $this->load->view($this->path_to_view_admin . 'match_addedit', $data);
        }
    }

    public function checkMatchid() {
        $this->db->select('*');
        $this->db->where('match_id', $this->input->post('match_id'));
        $query = $this->db->get('matches');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('checkMatchid', 'Match ID already Exist!');
            return false;
        } else {
            return true;
        }
    }

    function checkUsername() {
        $this->db->select('*');
        $this->db->where('user_name', $this->input->get('user_name'));
        $this->db->where('member_status', '1');
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            echo json_encode(TRUE);
        } else {
            echo json_encode(FALSE);
        }
    }

    public function getMemberDetail() {
        $this->db->select('*');
        $this->db->where('member_id', $this->input->get('member_id'));
        $this->db->where('member_status', '1');
        $query = $this->db->get('member');
        if ($query->num_rows() > 0) {
            echo json_encode($query->row_array());
        } else {
            echo json_encode(FALSE);
        }
    }

    public function edit_member_join() {
        $this->db->select("*");
        $this->db->where("match_id", $this->input->post('match_id'));
        $this->db->where("pubg_id", $this->input->post('pubg_id'));
        $this->db->where("match_join_member_id != ", $this->input->post('match_join_member_id'));
        $qr = $this->db->get('match_join_member');
        if ($qr->num_rows() > 0) {
            $array['status'] = false;
            $array['message'] = $this->input->post('pubg_id') . $this->lang->line('text_already_join') . ", ";
            echo json_encode($array);
            exit;
        } else {
            $this->db->set('pubg_id', $this->input->post('pubg_id'));
            $this->db->where('match_join_member_id', $this->input->post('match_join_member_id'));
            if ($this->db->update('match_join_member')) {
                $array['status'] = true;
                $array['message'] = $this->lang->line('text_player_name_changed');
                echo json_encode($array);
                exit;
            } else {
                $array['status'] = false;
                $array['message'] = $this->lang->line('text_player_name_not_changed');
                echo json_encode($array);
                exit;
            }
        }
    }

}
