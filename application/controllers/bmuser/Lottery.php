<?php

class Lottery extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        if ($this->member->front_logged_in !== true) {
            redirect('login');
        }
        $this->load->model($this->path_to_default . 'Lottery_model', 'lottery');
        $this->load->model($this->path_to_default . 'Account_model', 'account');
    }

    function index() {
        $data['lottery'] = true;
        $data['title'] = $this->lang->line('text_lottery');
        $data['breadcrumb_title'] = $this->lang->line('text_lottery');
        $data['ongoing_lottery_data'] = $this->lottery->getAllOngoingLottery();
        $data['result_lottery_data'] = $this->lottery->getAllResultLottery();
        $this->load->view($this->path_to_view_default . 'lottery', $data);
    }

    public function lottery_detail() {
        $lottery_id = $this->uri->segment('4');
        $data['lottery_detail'] = true;
        $data['title'] = $this->lang->line('text_lottery');
        $data['breadcrumb_title'] = $this->lang->line('text_lottery');
        $data['lottery'] = $this->lottery->getLotteryByID($lottery_id);
        $data['breadcrumb_title'] = $data['lottery']['lottery_title'];
        if (!empty($data['lottery'])) {
            $data['lottery_participate_data'] = $this->lottery->getLotteryParticipate($lottery_id);
            $this->load->view($this->path_to_view_default . 'single_lottery', $data);
        } else {
            redirect($this->path_to_default . 'lottery');
        }
    }

    public function join() {
        if ($this->input->post("submit") == $this->lang->line('text_btn_join')) {
            $lottery = $this->lottery->getLotteryByID($this->input->post('lottery_id'));
            $member = $this->account->getMemberDetail($this->member->front_member_id);
            if ($lottery['total_joined'] >= $lottery['lottery_size']) {
                $this->session->set_flashdata('notification', $this->lang->line('text_err_spot'));
                redirect($this->path_to_default . 'lottery/lottery_detail/' . $this->input->post('lottery_id'));
            } elseif ($lottery['join_status'] == true) {
                $this->session->set_flashdata('notification', $this->lang->line('text_err_already_join_lottery'));
                redirect($this->path_to_default . 'lottery/lottery_detail/' . $this->input->post('lottery_id'));
            } else {
                $this->load->library('user_agent');
                $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                $ip = $this->input->ip_address();
                if ($member['wallet_balance'] + $member['join_money'] >= $lottery['lottery_fees']) {
                    $lottery_member_data = array(
                        'lottery_id' => $this->input->post('lottery_id'),
                        'member_id' => $this->member->front_member_id,
                        'lottery_prize' => $lottery['lottery_prize'],
                        'entry_from' => '2',
                        'date_created' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('lottery_member', $lottery_member_data);
                    if ($lottery['lottery_fees'] > 0) {
                        if ($member['join_money'] > $lottery['lottery_fees']) {
                            $join_money = $member['join_money'] - $lottery['lottery_fees'];
                            $wallet_balance = $member['wallet_balance'];
                        } elseif ($member['join_money'] < $lottery['lottery_fees']) {
                            $join_money = 0;
                            $amount1 = $lottery['lottery_fees'] - $member['join_money'];
                            $wallet_balance = $member['wallet_balance'] - $amount1;
                        } elseif ($member['join_money'] == $lottery['lottery_fees']) {
                            $join_money = 0;
                            $wallet_balance = $member['wallet_balance'];
                        }
                        $acc_data = array(
                            'member_id' => $this->member->front_member_id,
                            'lottery_id' => $this->input->post('lottery_id'),
                            'deposit' => 0,
                            'withdraw' => $lottery['lottery_fees'],
                            'join_money' => $join_money,
                            'win_money' => $wallet_balance,
                            'note' => 'Lottery Joined',
                            'note_id' => '10',
                            'entry_from' => '2',
                            'ip_detail' => $ip,
                            'browser' => $browser,
                            'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('accountstatement', $acc_data);
                        $member_upd_data = array(
                            'join_money' => $join_money,
                            'wallet_balance' => $wallet_balance,
                        );
                        $this->db->where('member_id', $this->member->front_member_id);
                        $this->db->update('member', $member_upd_data);

                        $this->db->select("COUNT(*) as total_joined");
                        $this->db->where('lottery_id', $this->input->post('lottery_id'));
                        $query = $this->db->get('lottery_member');
                        $total_joined = $query->row_array()['total_joined'];

                        $lottery_upd_data = array(
                            'total_joined' => $total_joined);
                        $this->db->where('lottery_id', $this->input->post('lottery_id'));
                        $this->db->update('lottery', $lottery_upd_data);

                        $this->session->set_flashdata('success', $lottery['lottery_title'] . $this->lang->line('text_for_lottery_id') . $lottery['lottery_id'] . $this->lang->line('text_succ_join'));
                        redirect($this->path_to_default . 'lottery/lottery_detail/' . $lottery['lottery_id']);
                    } else {
                        $this->db->select("COUNT(*) as total_joined");
                        $this->db->where('lottery_id', $this->input->post('lottery_id'));
                        $query = $this->db->get('lottery_member');
                        $total_joined = $query->row_array()['total_joined'];

                        $lottery_upd_data = array(
                            'total_joined' => $total_joined);
                        $this->db->where('lottery_id', $this->input->post('lottery_id'));
                        $this->db->update('lottery', $lottery_upd_data);

                        $this->session->set_flashdata('success', $lottery['lottery_title'] . $this->lang->line('text_for_lottery_id') . $lottery['lottery_id'] . $this->lang->line('text_succ_join'));
                        redirect($this->path_to_default . 'lottery/lottery_detail/' . $lottery['lottery_id']);
                    }
                } else {
                    $this->session->set_flashdata('error', $this->lang->line('text_err_wallet'));
                    redirect($this->path_to_default . 'wallet/');
                }
            }
        } else {
            $lottery_id = $this->uri->segment('4');
            $data['lottery_join'] = true;
            $data['title'] = $this->lang->line('text_lottery_join');
            $data['breadcrumb_title'] = $this->lang->line('text_lottery_join');
            $data['lottery'] = $this->lottery->getLotteryByID($lottery_id);
            $data['member'] = $this->account->getMemberDetail($this->member->front_member_id);
            $data['breadcrumb_title'] = $data['lottery']['lottery_title'];
            if (!empty($data['lottery'])) {
                if ($data['lottery']['lottery_status'] == 1) {
                    if ($data['lottery']['total_joined'] >= $data['lottery']['lottery_size']) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_err_spot'));
                        redirect($this->path_to_default . 'lottery/lottery_detail/' . $lottery_id);
                    } elseif ($data['lottery']['join_status'] == true) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_err_already_join_lottery'));
                        redirect($this->path_to_default . 'lottery/lottery_detail/' . $lottery_id);
                    } else {
                        $this->load->view($this->path_to_view_default . 'lottery_join', $data);
                    }
                } else {
                    redirect($this->path_to_default . 'lottery');
                }
            } else {
                redirect($this->path_to_default . 'lottery');
            }
        }
    }

}

?>
