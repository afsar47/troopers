<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pgorder extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('pgorder')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
    }

    function index() {
        $data['pgorder'] = true;
        $data['title'] = $this->lang->line('text_money_orders');
        if ($this->input->post('action') == "change_publish") {
            if ($result = $this->changePublishStatus()) {
                $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_order'));
                redirect($this->path_to_view_admin . 'pgorder/');
            }
        }
        $this->load->view($this->path_to_view_admin . 'pgorder_manage', $data);
    }

    function changePublishStatus() {


        $this->db->set('deposit_status', $this->input->post('publish'));
        $this->db->where('deposit_id', $this->input->post('depositid'));
        if ($query = $this->db->update('deposit')) {
            if ($this->input->post('publish') == 2) {
                return true;
            }
            $this->db->select('*');
            $this->db->where('deposit_id', $this->input->post('depositid'));
            $deposit_query = $this->db->get('deposit');
            $deposit_data = $deposit_query->row_array();

            $this->db->select('*');
            $this->db->where('member_id', $deposit_data['member_id']);
            $member_query = $this->db->get('member');
            $member_data = $member_query->row_array();

            $join_money = $member_data['join_money'] + $deposit_data['deposit_amount'];
            $this->load->library('user_agent');
            $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
            $ip = $this->input->ip_address();
            $acc_data = [
                'member_id' => $deposit_data['member_id'],
                'pubg_id' => $member_data['pubg_id'],
                'deposit' => $deposit_data['deposit_amount'],
                'withdraw' => 0,
                'join_money' => $join_money,
                'win_money' => $member_data['wallet_balance'],
                'note' => 'Add Money to Join Wallet',
                'note_id' => '3',
                'entry_from' => '3',
                'ip_detail' => $ip,
                'browser' => $browser,
                'accountstatement_dateCreated' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('accountstatement', $acc_data);

            $this->db->set('join_money', $join_money);
            $this->db->where('member_id', $deposit_data['member_id']);
            $this->db->update('member');
            return true;
        } else
            return false;
    }

    function get_list_count_Pgorder() {
        $this->db->select('*');
        $query = $this->db->get('deposit');
        return $query->num_rows();
    }

    function setDatatableWithdraw() {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'deposit_id',
            2 => 'user_name',
            3 => 'deposit_amount',
            4 => 'deposit_by',
            5 => 'deposit_status',
            6 => 'bank_transection_no',
            7 => 'deposit_dateCreated'
        );
        $totalData = $this->get_list_count_Pgorder();
        $totalFiltered = $totalData;
        $sql = "SELECT m.member_id,m.user_name,d.deposit_id,d.deposit_amount,d.deposit_by,d.deposit_status,d.bank_transection_no,d.deposit_dateCreated,p.currency_point,c.currency_symbol,c.currency_decimal_place FROM deposit as d";        
        $sql .= " LEFT JOIN member as m ON d.member_id = m.member_id";
        $sql .= " LEFT JOIN pg_detail as p ON p.payment_name = d.deposit_by";
        $sql .= " LEFT JOIN currency as c ON p.currency = c.currency_id";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= "  WHERE ( user_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  deposit_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  deposit_amount LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  deposit_by LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  deposit_status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  bank_transection_no LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  deposit_dateCreated LIKE '%" . $requestData['search']['value'] . "%' ) ";
        }
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query);
        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `deposit_dateCreated` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $row['deposit_id'];
            $nestedData[] = '<a href=' . base_url() . $this->path_to_view_admin . 'members/member_detail/' . $row['member_id'] . '>' . $row['user_name'] . '</a>';

            if($row['currency_point'] > 0 && $row['deposit_amount'] > 0){
                $nestedData[] = sprintf('%.2F', $row['deposit_amount']) . ' - ' . $row['currency_symbol'] . sprintf('%.' . $row['currency_decimal_place'] . 'F', ($row['deposit_amount'] / $row['currency_point']));
            } else {
                $nestedData[] = sprintf('%.2F', $row['deposit_amount']) . ' - ' . $row['currency_symbol'] . sprintf('%.2F', ($row['deposit_amount']));
            }            

//            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['deposit_amount']);
            $nestedData[] = $row['deposit_by'];
            if ($row['deposit_status'] == '0') {
                if ($row['deposit_by'] == 'Offline') {
                    $nestedData[] = '<select onChange="javascript: changePublishStatus(document.frmpgorderlist,' . $row['deposit_id'] . ',this.value);">'
                            . '<option value="0" selected>Pending</option>'
                            . '<option value="1">Complete</option>'
                            . '<option value="2">Failed</option>';
                } else {
                    $nestedData[] = '<span class="badge badge-warning text-white" data-original-title="UnPublish" data-placement="top">Pending</span>';
                }
            } elseif ($row['deposit_status'] == '1') {
                $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top">Complete</span>';
            } elseif ($row['deposit_status'] == '2') {
                $nestedData[] = '<span class="badge badge-danger" data-original-title="UnPublish" data-placement="top">Failed</span>';
            }

            $nestedData[] = $row['bank_transection_no'];
            $nestedData[] = $row['deposit_dateCreated'];
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
