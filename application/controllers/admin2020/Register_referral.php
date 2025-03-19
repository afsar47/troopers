<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register_referral extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('register_referral')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
    }

    function index() {
        $data['register_referral'] = true;
        $data['title'] = $this->lang->line('text_register_referral');
        $this->load->view($this->path_to_view_admin . 'register_referral_manage', $data);
    }

    function get_list_count_RegisterReferral() {
        $this->db->select('*');
        $this->db->where('referral_status', '1');
        $query = $this->db->get('referral');
        return $query->num_rows();
    }

    function setDatatableRegisterReferral() {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'referral_id',
            2 => 'user_name',
            3 => 'from_mem_id',
            4 => 'referral_amount',
            5 => 'referral_dateCreated'
        );
        $totalData = $this->get_list_count_RegisterReferral();
        $totalFiltered = $totalData;
        $sql = "SELECT m.member_id,m.user_name,r.*,m1.user_name as from_member_name,from_mem_id FROM referral as r";
        $sql .= " LEFT JOIN member as m ON r.member_id = m.member_id";
        $sql .= " LEFT JOIN member as m1 ON r.from_mem_id = m1.member_id";
        $sql .= " WHERE  referral_status ='1'";
        if (!empty($requestData['search']['value'])) {
            $sql .= "  AND ( m.user_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  referral_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  referral_amount LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  m1.user_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  referral_dateCreated LIKE '%" . $requestData['search']['value'] . "%' ) ";
        }
        $query = mysqli_query($this->con, $sql);

        $totalFiltered = mysqli_num_rows($query);

        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `referral_dateCreated` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = '<a href=' . base_url() . $this->path_to_view_admin . 'members/member_detail/' . $row['member_id'] . '>' . $row['user_name'] . '</a>';
            $nestedData[] = '<a href=' . base_url() . $this->path_to_view_admin . 'members/member_detail/' . $row['from_mem_id'] . '>' . $row['from_member_name'] . '</a>';
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['referral_amount']);
            $nestedData[] = $row['referral_dateCreated'];
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
