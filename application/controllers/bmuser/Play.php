<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Play extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        if ($this->member->front_logged_in !== true) {
            redirect('login');
        }
        $this->load->model($this->path_to_default . 'Play_model', 'play');
        $this->load->model($this->path_to_default . 'Account_model', 'account');
    }

    public function index() {
        $data['breadcrumb_title'] = $this->lang->line('text_all_game');
        $data['title'] = $this->lang->line('text_all_game');
        $data['games_data'] = $this->play->getAllGame();
        $data['slider_data'] = $this->play->getAllSlider();
        $data['announcement_data'] = $this->play->getAllAnnouncement();
        $this->load->view($this->path_to_view_default . 'all_game', $data);
    }

    public function matches() {
        $game_id = $this->uri->segment('4');
        $data['title'] = $this->lang->line('text_matches');
        $data['tournament'] = $this->play->getGamByID($game_id);
        $data['breadcrumb_title'] = $data['tournament']['game_name'];
        $data['ongoing_match_data'] = $this->play->getAllOngoingMatch($game_id);
        $data['upcoming_match_data'] = $this->play->getAllUpcomingMatch($game_id);
        $data['result_match_data'] = $this->play->getAllResultMatch($game_id);
        $this->load->view($this->path_to_view_default . 'all_matches', $data);
    }

    public function my_match() {
        $data['title'] = $this->lang->line('text_my_matches');
        $data['breadcrumb_title'] = $this->lang->line('text_my_matches');
        $data['ongoing_match_data'] = $this->play->getMyMatch("ongoing");
        $data['upcoming_match_data'] = $this->play->getMyMatch("upcoming");
        $data['result_match_data'] = $this->play->getMyMatch("result");
        $this->load->view($this->path_to_view_default . 'my_match', $data);
    }

    public function checkPlayerName() {
        $this->db->select("*");
        $this->db->where("match_id", $this->input->post('match_id'));
        $this->db->where("pubg_id", $this->input->post('pubg_id'));
        $this->db->where("match_join_member_id != ", $this->input->post('match_join_member_id'));
        $qr = $this->db->get('match_join_member');
        if ($qr->num_rows() > 0) {
            $this->form_validation->set_message('checkPlayerName', $this->lang->line('err_playername_exist'));
            return false;
        } else {
            return true;
        }
    }

    public function match_detail() {
        if ($this->input->post('submit') == 'change_player_name') {
            $data['pubg_id'] = $this->input->post('pubg_id');

            $this->form_validation->set_rules('pubg_id', 'lang:text_player_name', 'required|callback_checkPlayerName', array('required' => $this->lang->line('err_player_name_req')));
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', $this->input->post('pubg_id') . $this->lang->line('text_err_player_name_exist'));
                redirect($this->path_to_default . 'play/match_detail/' . $this->input->post('match_id'));
            } else {
                $this->db->set('pubg_id', $this->input->post('pubg_id'));
                $this->db->where('match_join_member_id', $this->input->post('match_join_member_id'));
                if ($this->db->update('match_join_member')) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_player_name'));
                    redirect($this->path_to_default . 'play/match_detail/' . $this->input->post('match_id'));
                } else {
                    $this->session->set_flashdata('error', $this->lang->line('text_err_player_name'));
                    redirect($this->path_to_default . 'play/match_detail/' . $this->input->post('match_id'));
                }
            }
        } else {
            $match_id = $this->uri->segment('4');
            $data['title'] = $this->lang->line('text_single_match');
            $data['match'] = $this->play->getMatchByID($match_id);
            if (!empty($data['match'])) {
                $data['breadcrumb_title'] = $data['match']['match_name'];
                if ($data['match']['match_status'] == 2)
                    $data['match_result_data'] = $this->play->getMatchResult($match_id, $data['match']['type']);
                else {
                    $data['match_participate_data'] = $this->play->getMatchParticipate($match_id);
                    $data['join_member_data'] = $this->play->JoinMemberDetail($match_id);
                }
                $this->load->view($this->path_to_view_default . 'single_match', $data);
            } else {
                redirect($this->path_to_default . 'play');
            }
        }
    }

    public function select_position() {
        if ($this->input->post('submit') == $this->lang->line('text_btn_join_now')) {
            $data['match_id'] = $this->input->post('match_id');
            $data['pubg_id'] = $this->input->post('pubg_id');
            $this->form_validation->set_rules('position', 'Position', 'required');
            if ($this->input->post('type') == 'Solo' || $this->input->post('type') == 'solo')
                $no_player = 1;
            else if ($this->input->post('type') == 'Duo' || $this->input->post('type') == 'duo')
                $no_player = 2;
            elseif ($this->input->post('type') == 'Squad' || $this->input->post('type') == 'squad')
                $no_player = 4;
            elseif ($this->input->post('type') == 'Squad5' || $this->input->post('type') == 'squad5')
                $no_player = 5;
            if (!$this->input->post('position') || count($this->input->post('position')) > $no_player) {
                $data['title'] = "Select Position";
                $data['match'] = $this->play->getMatchByID($data['match_id']);
                $data['breadcrumb_title'] = $data['match']['match_name'];
                if ($data['match']['match_status'] != '1') {
                    redirect($this->path_to_default . 'play/match_detail/' . $data['match_id']);
                } elseif ($data['match']['no_of_player'] >= $data['match']['number_of_position']) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_err_spot'));
                    redirect($this->path_to_default . 'play/match_detail/' . $data['match_id']);
                } elseif ($this->play->checkMemberJoinOrNot($data['match_id'])) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_err_already_join_match'));
                    redirect($this->path_to_default . 'play/match_detail/' . $data['match_id']);
                } elseif ($this->input->post('position') && count($this->input->post('position')) > $no_player) {
                    $data['match_position'] = $this->play->getMatchPosition($data['match_id']);
                    $this->session->set_flashdata('error', 'You can select only ' . $no_player . ' spot in ' . $this->input->post('type'));
                    $this->load->view($this->path_to_view_default . 'match_position', $data);
                } else {
                    $data['match_position'] = $this->play->getMatchPosition($data['match_id']);
                    $this->session->set_flashdata('error', $this->lang->line('text_err_position'));
                    $this->load->view($this->path_to_view_default . 'match_position', $data);
                }
            } else {
                $data['title'] = $this->lang->line('text_joining_position');
                $data['breadcrumb_title'] = $this->lang->line('text_joining_position');
                $data['positions'] = $this->input->post('position');
                $data['match'] = $this->play->getMatchByID($this->input->post('match_id'));
                $data['member'] = $this->account->getMemberDetail($this->member->front_member_id);
                if ($this->play->checkMemberJoinOrNot($this->input->post('match_id')))
                    $data['join_status'] = "true";
                else
                    $data['join_status'] = "false";
                $this->load->view($this->path_to_view_default . 'selected_position', $data);
            }
        } else {
            $match_id = $this->uri->segment('4');
            if ($match_id != '') {
                $data['title'] = $this->lang->line('text_select_position');
                $data['match'] = $this->play->getMatchByID($match_id);
                if (!empty($data['match'])) {
                    $data['breadcrumb_title'] = $data['match']['match_name'];
                    if ($data['match']['match_status'] != '1') {
                        redirect($this->path_to_default . 'play/match_detail/' . $match_id);
                    } elseif ($data['match']['no_of_player'] >= $data['match']['number_of_position']) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_err_spot'));
                        redirect($this->path_to_default . 'play/match_detail/' . $match_id);
                    } elseif ($this->play->checkMemberJoinOrNot($match_id)) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_err_already_join_match'));
                        redirect($this->path_to_default . 'play/match_detail/' . $match_id);
                    } else {
                        $data['match_position'] = $this->play->getMatchPosition($match_id);
                        $this->load->view($this->path_to_view_default . 'match_position', $data);
                    }
                } else {
                    redirect($this->path_to_default . 'play');
                }
            } else {
                redirect($this->path_to_default . 'play');
            }
        }
    }

    public function joinmatch() {
        $match = $this->play->getMatchByID($this->input->post('match_id'));
        $member = $this->account->getMemberDetail($this->member->front_member_id);
        if ($match['no_of_player'] + count($this->input->post('no')) > $match['number_of_position']) {
            $this->session->set_flashdata('notification', $this->lang->line('text_err_spot'));
            redirect($this->path_to_default . 'play/match_detail/' . $this->input->post('match_id'));
        }
        $resp = '';
        foreach ($this->input->post('no') as $no) {
            if ($this->input->post('position_' . $no)[2] != '') {
                $this->db->select("*");
                $this->db->where("match_id", $this->input->post('match_id'));
                $this->db->where("pubg_id", $this->input->post('position_' . $no)[2]);
                $query = $this->db->get('match_join_member');
                $match_join = $query->row_array();
                if ($query->num_rows() > 0) {
                    $resp .= $this->input->post('position_' . $no)[2] . $this->lang->line('text_err_player_name_exist') . " .<br/>";
                }
            } else {
                $resp .= $this->lang->line('err_player_name_req');
                break;
            }
        }
        if ($resp != '') {
            $this->session->set_flashdata('error', $resp);
            redirect($this->path_to_default . 'play/match_detail/' . $this->input->post('match_id'));
        }
        $resp1 = '';
        foreach ($this->input->post('no') as $no) {
            $this->db->select("*");
            $this->db->where('match_id', $this->input->post('match_id'));
            $this->db->where('team', $this->input->post('position_' . $no)[0]);
            $this->db->where('position', $this->input->post('position_' . $no)[1]);
            $query = $this->db->get('match_join_member');
            $match_join = $query->row_array();
            if ($query->num_rows() > 0) {
                $resp1 .= "Already Joined for that team " . $this->input->post('position_' . $no)[0] . " Position " . $this->input->post('position_' . $no)[1] . "<br/>";
            }
        }
        if ($resp1 != '') {
            $this->session->set_flashdata('error', $resp1);
            redirect($this->path_to_default . 'play/match_detail/' . $this->input->post('match_id'));
        }
        $ar_len = count($this->input->post('no'));
        $fee = $match['entry_fee'] * $ar_len;
        if ($member['wallet_balance'] + $member['join_money'] >= $fee) {
            $i = 1;
            $no_of_player = $match['no_of_player'] + $ar_len;

            foreach ($this->input->post('no') as $no) {
                $member = $this->account->getMemberDetail($this->member->front_member_id);
                $match_join_member_data = array(
                    'match_id' => $this->input->post('match_id'),
                    'member_id' => $this->member->front_member_id,
                    'pubg_id' => $this->input->post('position_' . $no)[2],
                    'team' => $this->input->post('position_' . $no)[0],
                    'position' => $this->input->post('position_' . $no)[1],
                    'place' => 0,
                    'place_point' => 0,
                    'killed' => 0,
                    'win' => 0,
                    'win_prize' => 0,
                    'bonus' => 0,
                    'total_win' => 0,
                    'refund' => 0,
                    'entry_from' => '2',
                    'date_craeted' => date('Y-m-d H:i:s')
                );
                $this->db->insert('match_join_member', $match_join_member_data);
                if ($match['match_type'] == '0' || $match['match_type'] == 0) {
                    if ($i == 1 && $this->input->post('join_status') == "false") {
                        $pubg_id = unserialize($member['pubg_id']);
                        if (is_array($pubg_id)) {
                            if (array_key_exists($match['game_id'], $pubg_id)) {
                                $pubg_id[$match['game_id']] = $this->input->post('position_' . $no)[2];
                            } else {
                                $pubg_id[$match['game_id']] = $this->input->post('position_' . $no)[2];
                            }
                            $member_upd_data = array(
                                'pubg_id' => serialize($pubg_id),
                            );
                        } else {
                            $pubg = array(
                                $match['game_id'] => $this->input->post('position_' . $no)[2],
                            );
                            $pubg_id = serialize($pubg);
                            $member_upd_data = array(
                                'pubg_id' => $pubg_id,
                            );
                        }
                        $this->db->where('member_id', $this->member->front_member_id);
                        $this->db->update('member', $member_upd_data);
                    }
                    if ($ar_len == $i) {
                        $this->db->select("COUNT(*) as no_of_player");
                        $this->db->where('match_id', $this->input->post('match_id'));
                        $query = $this->db->get('match_join_member');
                        $no_of_player = $query->row_array()['no_of_player'];

                        $match_upd_data = array(
                            'no_of_player' => $no_of_player);
                        $this->db->where('m_id', $this->input->post('match_id'));
                        $this->db->update('matches', $match_upd_data);

                        $this->session->set_flashdata('success', $match['match_name'] . $this->lang->line('text_for_macth_id') . $match['m_id'] . $this->lang->line('text_succ_join'));
                        redirect($this->path_to_default . 'play/match_detail/' . $match['m_id']);
                    }
                } else {
                    $this->load->library('user_agent');
                    $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                    $ip = $this->input->ip_address();
                    if ($member['join_money'] > $match['entry_fee']) {
                        $join_money = $member['join_money'] - $match['entry_fee'];
                        $wallet_balance = $member['wallet_balance'];
                    } elseif ($member['join_money'] < $match['entry_fee']) {
                        $join_money = 0;
                        $amount1 = $match['entry_fee'] - $member['join_money'];
                        $wallet_balance = $member['wallet_balance'] - $amount1;
                    } elseif ($member['join_money'] == $match['entry_fee']) {
                        $join_money = 0;
                        $wallet_balance = $member['wallet_balance'];
                    }
                    if ($i == 1 && $this->input->post('join_status') == "false") {
                        $pubg = unserialize($member['pubg_id']);
                        if (is_array($pubg)) {
                            if (array_key_exists($match['game_id'], $pubg)) {
                                $pubg[$match['game_id']] = $this->input->post('position_' . $no)[2];
                            } else {
                                $pubg[$match['game_id']] = $this->input->post('position_' . $no)[2];
                            }
                        } else {
                            $pubg = array(
                                $match['game_id'] => $this->input->post('position_' . $no)[2],
                            );
                        }
                        $pubg_id = serialize($pubg);
                        $member_upd_data = array(
                            'join_money' => $join_money,
                            'wallet_balance' => $wallet_balance,
                            'pubg_id' => $pubg_id,
                        );
                    } else {
                        $member_upd_data = array(
                            'join_money' => $join_money,
                            'wallet_balance' => $wallet_balance,
                        );
                    }
                    $acc_data = array(
                        'member_id' => $this->member->front_member_id,
                        'pubg_id' => $this->input->post('position_' . $no)[2],
                        'match_id' => $this->input->post('match_id'),
                        'deposit' => 0,
                        'withdraw' => $match['entry_fee'],
                        'join_money' => $join_money,
                        'win_money' => $wallet_balance,
                        'note' => 'Match Joined',
                        'note_id' => '2',
                        'entry_from' => '2',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('accountstatement', $acc_data);

                    $this->db->where('member_id', $this->member->front_member_id);
                    $this->db->update('member', $member_upd_data);
                    if ($member['member_package_upgraded'] == 0 && (float)$match['entry_fee'] >= (float)$this->system->referral_min_paid_fee) {
                        $wallet_balance = $wallet_balance + $this->system->referral;
                        $data = array(
                            'member_package_upgraded' => '1',
                        );
                        $this->db->where('member_id', $this->member->front_member_id);
                        $this->db->update('member', $data);
                        if ($member['referral_id'] != 0 && $this->system->active_referral == '1') {
                            $member2 = $this->account->getMemberDetail($member['referral_id']);
                            if ($member2['member_package_upgraded'] == 1) {
                                $join_money2 = $member2['join_money'] + $this->system->referral_level1;
                                $data = array(
                                    'join_money' => $join_money2,
                                );
                                $this->db->where('member_id', $member['referral_id']);
                                $this->db->update('member', $data);

                                $referral_data = array(
                                    'member_id' => $member['referral_id'],
                                    'from_mem_id' => $this->member->front_member_id,
                                    'referral_amount' => $this->system->referral_level1,
                                    'referral_status' => '0',
                                    'entry_from' => '2',
                                    'referral_dateCreated' => date('Y-m-d H:i:s')
                                );
                                $this->db->insert('referral', $referral_data);
                                $acc_data = array(
                                    'member_id' => $member2['member_id'],
                                    'pubg_id' => $member2['pubg_id'],
                                    'from_mem_id' => $this->member->front_member_id,
                                    'deposit' => $this->system->referral_level1,
                                    'withdraw' => 0,
                                    'join_money' => $join_money2,
                                    'win_money' => $member2['wallet_balance'],
                                    'note' => 'Referral',
                                    'note_id' => '4',
                                    'entry_from' => '2',
                                    'ip_detail' => $ip,
                                    'browser' => $browser,
                                    'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                                );
                                $this->db->insert('accountstatement', $acc_data);
                            }
                        }
                    }
                    if ($ar_len == $i) {
                        $this->db->select("COUNT(*) as no_of_player");
                        $this->db->where('match_id', $this->input->post('match_id'));
                        $query = $this->db->get('match_join_member');
                        $no_of_player = $query->row_array()['no_of_player'];

                        $match_upd_data = array(
                            'no_of_player' => $no_of_player);
                        $this->db->where('m_id', $this->input->post('match_id'));
                        $this->db->update('matches', $match_upd_data);

                        $this->session->set_flashdata('success', $match['match_name'] . $this->lang->line('text_for_macth_id') . $match['m_id'] . $this->lang->line('text_succ_join'));
                        redirect($this->path_to_default . 'play/match_detail/' . $match['m_id']);
                    }
                }
                $i++;
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('text_err_wallet'));
            redirect($this->path_to_default . 'wallet/');
        }
    }

    public function checkGameId() {
        $resp = '';
        foreach ($this->input->post('game_ids') as $game_id) {
            $this->db->select("*");
            $this->db->where("match_id", $this->input->post('match_id'));
            $this->db->where("pubg_id", $game_id);
            $query = $this->db->get('match_join_member');
            $match = $query->row_array();
            if ($query->num_rows() > 0) {
                $resp .= $game_id . $this->lang->line('text_err_player_name_exist') . " .<br/>";
            }
        }
        if ($resp != '') {
            echo json_encode($resp);
        } else {
            echo json_encode(true);
        }
    }

}
