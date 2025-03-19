<?php

class Statistics extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        if ($this->member->front_logged_in !== true) {
            redirect('login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_default . 'Account_model', 'account');
    }

    function index() {
        $data['my_statistics'] = true;
        $data['title'] = $this->lang->line('text_my_statistics');
        $data['breadcrumb_title'] = $this->lang->line('text_my_statistics');
        $data['member'] = $this->account->getMemberDetail($this->member->front_member_id);
        $data['tot_play'] = $this->account->get_play();
        $data['tot_withdraw'] = $this->account->get_tot_withdraw();
        $data['statistic_data'] = $this->getStatisticData();
        $this->load->view($this->path_to_view_default . 'my_statistics', $data);
    }

    public function getStatisticData() {
        $this->db->select("CONCAT(m.match_name,' Match - #',m.m_id) as match_name,m.entry_fee,mj.total_win,mj.date_craeted");
        $this->db->where('member_id', $this->member->front_member_id);
        $this->db->join('matches as m', 'm.m_id = mj.match_id', 'LEFT');
        $query = $this->db->get('match_join_member as mj');
        return $query->result();
    }

    function setDatatableStates() {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'match_name',
            2 => 'entry_fee',
            3 => 'total_win',
            4 => 'date_craeted',
        );
        $totalData = $this->get_list_count_MemberStates($this->member->front_member_id);
        $totalFiltered = $totalData;
        $sql = "SELECT CONCAT(m.match_name,' Match - #',m.m_id) as match_name,m.entry_fee,mj.total_win,mj.date_craeted FROM match_join_member as mj";
        $sql .= " LEFT JOIN matches as m ON m.m_id = mj.match_id";
        $sql .= " WHERE member_id = " . $this->member->front_member_id;
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

    public function get_list_count_MemberStates($member_id) {
        $this->db->select('*');
        $this->db->where('member_id', $member_id);
        $this->db->join('matches as m', 'm.m_id = mj.match_id', 'LEFT');
        $query = $this->db->get('match_join_member as mj');
        return $query->num_rows();
    }

}

?>
