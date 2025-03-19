<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Leaderboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
         if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('leaderboard')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
    }

    function index() {
        $data['leaderboard'] = true;
        $data['title'] = $this->lang->line('text_leaderboard');
        $this->load->view($this->path_to_view_admin . 'leaderboard_manage', $data);
    }

    function get_list_count_leader_board() {
        $this->db->select('*');
        $this->db->join('member as m2', 'm2.member_id = m.referral_id', 'LEFT');
        $this->db->limit(10);
        $query = $this->db->get('member as m');
        return $query->num_rows();
    }

    function setDatatableLeaderBoard() {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'user_name',
            2 => 'tot_referral'
        );
        $totalData = $this->get_list_count_leader_board();
        $totalFiltered = $totalData;
        $sql = "SELECT m2.`member_id`,m2.`user_name`,m.`referral_id`,COUNT(m.`referral_id`) as `tot_referral` FROM `member` as m";
        $sql .= " LEFT JOIN `member` as m2 ON m.referral_id = m2.member_id";
        $sql .= " WHERE m.`referral_id` != '0'";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND  m.user_name LIKE '%" . $requestData['search']['value'] . "%' ";
        }
        $sql .= " GROUP BY  m.`referral_id`";
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT 0,10";
        } else {
            $sql .= " ORDER BY tot_referral DESC LIMIT 0,10";
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = '<a href=' . base_url() . $this->path_to_view_admin . 'members/member_detail/' . $row['member_id'] . '>' . $row['user_name'] . '</a>';
            $nestedData[] = $row['tot_referral'];
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
