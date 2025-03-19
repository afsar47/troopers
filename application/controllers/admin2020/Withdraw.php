<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Withdraw extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }        

        $this->load->model($this->path_to_view_admin . '/Withdraw_model', 'withdraw');
        $this->con = $this->functions->mysql_connection();
    }

    public function index() {

        if(!$this->functions->check_permission('withdraw')) {
            redirect($this->path_to_view_admin . 'login');
        }

        $data['withdraw'] = true;
        $data['title'] = $this->lang->line('text_withdraw');

        if ($this->input->post('action') == "change_publish") {
            if ($result = $this->changePublishStatus()) {
                $this->session->set_flashdata('notification', $this->lang->line('text_succ_change_withdraw'));
                redirect($this->path_to_view_admin . 'withdraw/');
            }
        }
        $this->load->view($this->path_to_view_admin . 'withdraw_manage', $data);
    }    

    public function changePublishStatus() {
        $this->db->set('note_id', $this->input->post('publish'));
        $this->db->where('account_statement_id', $this->input->post('accountid'));
        if ($query = $this->db->update('accountstatement')) {
            return true;
        } else {
            return false;
        }
    }

    public function get_list_count_Withdraw() {
        $note_id = array('1', '9');
        $this->db->select('*');
        $this->db->join('member as m', 'a.member_id = m.member_id', 'LEFT');
        $this->db->where_in('note_id', $note_id);
        $query = $this->db->get('accountstatement as a');
        return $query->num_rows();
    }

    public function setDatatableWithdraw() {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'withdraw_id',
            1 => 'user_name',
            2 => 'pyatmnumber',
            3 => 'withdraw',
            4 => 'note',
            5 => 'withdraw_method',
            6 => 'accountstatement_dateCreated',
        );
        $totalData = $this->get_list_count_Withdraw();
        $totalFiltered = $totalData;
        $sql = "SELECT m.member_id,m.user_name,account_statement_id,pyatmnumber,withdraw,w.withdraw_method,note_id,accountstatement_dateCreated,w.withdraw_method_currency_point,c.currency_symbol,c.currency_decimal_place FROM accountstatement as a";
        $sql .= " LEFT JOIN member as m ON a.member_id = m.member_id";
        $sql .= " LEFT JOIN withdraw_method as w ON w.withdraw_method = a.withdraw_method";
        $sql .= " LEFT JOIN currency as c ON c.currency_id = w.withdraw_method_currency";
        $sql .= " WHERE note_id IN ('1','9')";
        if (!empty($requestData['search']['value'])) {
            $sql .= "  AND ( user_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  pyatmnumber LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  withdraw LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  w.withdraw_method LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  note_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  accountstatement_dateCreated LIKE '%" . $requestData['search']['value'] . "%' ) ";
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
            $nestedData[] = '<a href=' . base_url() . $this->path_to_view_admin . 'members/member_detail/' . $row['member_id'] . '>' . $row['user_name'] . '</a>';
            // $nestedData[] = $row['pyatmnumber'];
            $nestedData[] = '<span id="pyatmnumber'. $row['account_statement_id'] .'" onclick="copyToClipboard(\'#pyatmnumber'. $row['account_statement_id'] .'\',' . $row['account_statement_id'] . ')" style="cursor:pointer;">' . $row['pyatmnumber'] . ' <i class="fa fa-copy ml-3"></i></span><br>
                            <span class="copied'. $row['account_statement_id'] .' text-white rounded px-2" style="position: absolute;left: 35%;z-index: 10;background-color:#000"></span>';
                            
            if($row['withdraw_method_currency_point'] > 0 && $row['withdraw'] > 0){
                $nestedData[] = sprintf('%.2F', $row['withdraw']) . ' - ' . $row['currency_symbol'] . sprintf('%.' . $row['currency_decimal_place'] . 'F', ($row['withdraw'] / $row['withdraw_method_currency_point']));
            } else {
                $nestedData[] = sprintf('%.2F', $row['withdraw']) . ' - ' . $row['currency_symbol'] . sprintf('%.2F', ($row['withdraw']));
            }
            
            $nestedData[] = $row['withdraw_method'];
            if ($row['note_id'] == '9') {
                $nestedData[] = '<select onChange="javascript: changePublishStatus(document.frmwithdrawlist,' . $row['account_statement_id'] . ',this.value);">'
                        . '<option value="9" selected>Pending</option>'
                        . '<option value="1">Complete</option>';
            } elseif ($row['note_id'] == '1') {
                $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top">Complete</span>';
            }
            $nestedData[] = $row['accountstatement_dateCreated'];
            $data[] = $nestedData;
            $i++;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        echo json_encode($json_data);
    }

    public function method() {
        if(!$this->functions->check_permission('withdraw_method')) {
            redirect($this->path_to_view_admin . 'login');
        }

        $data['withdraw_method'] = true;
        $data['title'] = $this->lang->line('text_withdraw_method');
        $data['btn'] = $this->lang->line('text_add_withdraw_method');
        $data['Action'] = $this->lang->line('text_action_add');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('withdrawmethodid') <= 3) || !$this->functions->check_permission('withdraw_method_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_withdraw_method'));
                redirect($this->path_to_view_admin . 'withdraw/method');
            } else {
                if ($result = $this->withdraw->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_withdraw_method'));
                    redirect($this->path_to_view_admin . 'withdraw/method');
                }
            }
        } else if ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('withdrawmethodid') <= 3) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'withdraw/method');
            }
            if ($result = $this->withdraw->changePublishStatusMethod()) {
                $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_withdraw_method'));
                redirect($this->path_to_view_admin . 'withdraw/method');
            }
        }
        $this->load->view($this->path_to_view_admin . 'withdraw_method_manage', $data);
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 3) || !$this->functions->check_permission('withdraw_method_delete')) {                    
                echo $this->lang->line('text_err_delete_withdraw_method');                    
            } else {
                $this->withdraw->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 3) || !$this->functions->check_permission('withdraw_method')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->withdraw->changeMultiPublishStatus();            
            }       
        }
    }

    public function setDatatableWithdrawMethod() {
        $requestData = $_REQUEST;
        $columns = array(            
            2 => 'withdraw_method',
            3 => 'withdraw_method_field',
            4 => 'withdraw_method_status',
            5 => 'withdraw_method_dateCreated',
        );
        $totalData = $this->withdraw->get_list_count_withdraw_method();
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM withdraw_method where 1";
        if (!empty($requestData['search']['value'])) {
            $sql .= "  AND ( withdraw_method LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  withdraw_method_status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  withdraw_method_field LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  withdraw_method_dateCreated LIKE '%" . $requestData['search']['value'] . "%' ) ";
        }
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query);

        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `withdraw_method_dateCreated` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = '<input type="checkbox" value="'. $row['withdraw_method_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['withdraw_method'];
            $nestedData[] = $row['withdraw_method_field'];
            if ($this->system->demo_user == 1 && $row['withdraw_method_id'] <= 3) {
                if ($row['withdraw_method_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger">Inactive</span>';
                }
            } else {
                if ($row['withdraw_method_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmwithdrawmethodlist,' . $row['withdraw_method_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmwithdrawmethodlist,' . $row['withdraw_method_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }
            $nestedData[] = $row['withdraw_method_dateCreated'];
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'withdraw/edit/' . $row['withdraw_method_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['withdraw_method_id'] <= 3) {
                $delete = '<a  class="" disabled="disabled" style="cursor: pointer;font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp;';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteWithdrawMethod(document.frmwithdrawmethodlist,' . $row['withdraw_method_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp;';
            }
            $nestedData[] = $edit . $delete;
            $data[] = $nestedData;
            $i++;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        echo json_encode($json_data);
    }

    public function insert() {
        $data['withdraw_method_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_withdraw_method');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_withdraw_method');
        $data['currency_data'] = $this->functions->getCurrency();
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['withdraw_method'] = $this->input->post('withdraw_method');
                $data['withdraw_method_field'] = $this->input->post('withdraw_method_field');
                $data['withdraw_method_currency'] = $this->input->post('withdraw_method_currency');
                $data['withdraw_method_currency_point'] = $this->input->post('withdraw_method_currency_point');

                $this->form_validation->set_rules('withdraw_method', 'lang:text_withdraw_method', 'required', array('required' => $this->lang->line('err_withdraw_method_req')));
                $this->form_validation->set_rules('withdraw_method_field', 'lang:text_withdraw_method_field', 'required', array('required' => $this->lang->line('err_withdraw_method_field_req')));
                $this->form_validation->set_rules('withdraw_method_currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
                $this->form_validation->set_rules('withdraw_method_currency_point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == false) {
                $this->load->view($this->path_to_view_admin . 'withdraw_method_addedit', $data);
            } else {
                if ($result = $this->withdraw->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_withdraw_method'));
                    redirect($this->path_to_view_admin . 'withdraw/method');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'withdraw_method_addedit', $data);
        }
    }

    public function edit() {
        if(!$this->functions->check_permission('withdraw_method_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_withdraw_method'));
            redirect($this->path_to_view_admin . 'withdraw/method');
        }

        $data['withdraw_method_addedit'] = true;
        $withdraw_method_id = $this->uri->segment('4');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['title'] = $this->lang->line('text_edit_withdraw_method');
        $data['currency_data'] = $this->functions->getCurrency();
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('withdraw_method_id') <= 3) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_withdraw_method'));
                redirect($this->path_to_view_admin . 'withdraw/method');
            } else {
                $data['withdraw_method'] = $this->input->post('withdraw_method');
                $data['withdraw_method_field'] = $this->input->post('withdraw_method_field');
                $data['withdraw_method_currency'] = $this->input->post('withdraw_method_currency');
                $data['withdraw_method_currency_point'] = $this->input->post('withdraw_method_currency_point');

                $this->form_validation->set_rules('withdraw_method', 'lang:text_withdraw_method', 'required', array('required' => $this->lang->line('err_withdraw_method_req')));
                $this->form_validation->set_rules('withdraw_method_field', 'lang:text_withdraw_method_field', 'required', array('required' => $this->lang->line('err_withdraw_method_field_req')));
                $this->form_validation->set_rules('withdraw_method_currency', 'lang:text_currency', 'required', array('required' => $this->lang->line('err_currency_req')));
                $this->form_validation->set_rules('withdraw_method_currency_point', 'lang:text_point', 'required|numeric', array('required' => $this->lang->line('err_point_req'), 'numeric' => $this->lang->line('err_number')));

                if ($this->form_validation->run() == false) {
                    $this->load->view($this->path_to_view_admin . 'withdraw_method_addedit', $data);
                } else {
                    if ($result = $this->withdraw->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_withdraw_method'));
                        redirect($this->path_to_view_admin . 'withdraw/method');
                    }
                }
            }
        } else {
            $data['withdraw_method_detail'] = $this->withdraw->getWithdrawMethodById($withdraw_method_id);
            $this->load->view($this->path_to_view_admin . 'withdraw_method_addedit', $data);
        }
    }

}
