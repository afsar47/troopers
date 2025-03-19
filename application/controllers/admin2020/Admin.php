<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {

//            parent::CI_Controller();
        parent::__construct();
    
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('admin')) {
            redirect($this->path_to_view_admin . 'login');
        }

        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_view_admin . 'Admin_model', 'admin');
    }

    function index() {

        $data['admin'] = true;
        $data['btn'] = 'Add Admin';
        if ($this->input->post('action') == "delete") {
            if ($this->system->demo_user == 1 || $this->input->post('adminid') == 1 || !$this->functions->check_permission('admin_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_admin'));
                redirect($this->path_to_view_admin . 'admin/');
            } else {
                if ($result = $this->admin->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_admin'));
                    redirect($this->path_to_view_admin . 'admin/');
                }
            }
        }
        $this->load->view($this->path_to_view_admin . 'admin_manage', $data);
    }

    function checkName() {
        $admin_id = $this->uri->segment('4');
        $this->db->select('*');
        $this->db->where('name', $this->input->post('name'));
        $query = $this->db->get('admin');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('checkName', $this->lang->line('err_name_exist'));
            return false;
        } else {
            return true;
        }
    }

    function checkName1() {
        $this->db->select('*');
        $this->db->where('name', $this->input->post('name'));
        $this->db->where('id !=', $this->input->post('admin_id'));
        $query = $this->db->get('admin');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('checkName1', $this->lang->line('err_name_exist'));
            return false;
        } else {
            return true;
        }
    }

    function checkEmail() {
        $admin_id = $this->uri->segment('4');
        $this->db->select('*');
        $this->db->where('email', $this->input->post('email'));
        $query = $this->db->get('admin');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('checkEmail', $this->lang->line('err_email_id_exist'));
            return false;
        } else {
            return true;
        }
    }

    function checkEmail1() {
        $this->db->select('*');
        $this->db->where('email', $this->input->post('email'));
        $this->db->where('id !=', $this->input->post('admin_id'));
        $query = $this->db->get('admin');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('checkEmail1', $this->lang->line('err_email_id_exist'));
            return false;
        } else {
            return true;
        }
    }

    function insert() {
        $data['admin_addedit'] = true;
        $data['btn'] = 'View admin';
        $data['Action'] = 'Add';
        if ($this->input->post('submit') == "Submit") {

            if ($this->system->demo_user == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_add_admin'));
                redirect($this->path_to_view_admin . 'admin/');
            }

            $data['name'] = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            $data['password'] = $this->input->post('password');
            $data['permission'] = $this->input->post('permission');

            $this->form_validation->set_rules('name', 'Name', 'required|callback_checkName');
            $this->form_validation->set_rules('email', 'Email', 'required|callback_checkEmail');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == FALSE) {
                $data['permissions'] = $this->admin->getPermission();
                $this->load->view($this->path_to_view_admin . 'admin_addedit', $data);
            } else {
                if ($result = $this->admin->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_admin'));
                    redirect($this->path_to_view_admin . 'admin/');
                }
            }
        } else {
            $data['permissions'] = $this->admin->getPermission();
            $this->load->view($this->path_to_view_admin . 'admin_addedit', $data);
        }
    }

    function setDatatableAdmin() {

        header('Content-Type: application/json; charset=UTF-8');
        $requestData = $_REQUEST;

        $columns = array(
            1 => 'name',
            2 => 'email',
            3 => 'craeted_date',
        );

        $totalData = $this->admin->get_list_count_admin();
        $totalFiltered = $totalData;

        $sql = "SELECT id,name,email,craeted_date FROM admin";

        $query = mysqli_query($this->con, $sql);

        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  email LIKE '%" . $requestData['search']['value'] . "%' ";
        }

        mysqli_query($this->con, "set character_set_results='utf8'");
        $query = mysqli_query($this->con, $sql);

        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `craeted_date` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }

        mysqli_query($this->con, "set character_set_results='utf8'");
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $row['name'];
            $nestedData[] = $row['email'];
            $nestedData[] = $row['craeted_date'];

            if($row['id'] != 1) {

                $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'admin/edit/' . $row['id'] . '><i class="fa fa-edit"></i></a>&nbsp;';

                $delete = '<a class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteAdmin(document.frmadminlist,' . $row['id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';

                $nestedData[] = $edit . $delete;

            } else {
                $nestedData[] = '';
            }

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

    function edit() {
        if(!$this->functions->check_permission('admin_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_admin'));
            redirect($this->path_to_view_admin . 'admin');
        }

        $data['admin_addedit'] = true;
        $data['btn'] = 'View admin';
        $data['Action'] = 'Edit';
        $admin_id = $this->uri->segment('4');
        if ($this->input->post('submit') == "Submit") {
            if ($this->system->demo_user == 1 || $this->input->post('admin_id') == 1) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_admin'));
                redirect($this->path_to_view_admin . 'admin/');
            }
            $data['name'] = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            $data['permission'] = $this->input->post('permission');
            $data['admin_id'] = $this->input->post('admin_id');

            $this->form_validation->set_rules('name', 'Name', 'required|callback_checkName1');
            $this->form_validation->set_rules('email', 'Email', 'required|callback_checkEmail1');
            // $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == FALSE) {
                $data['permissions'] = $this->admin->getPermission();
                $this->load->view($this->path_to_view_admin . 'admin_addedit', $data);
            } else {
                if ($result = $this->admin->update()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_admin'));
                    redirect($this->path_to_view_admin . 'admin/');
                }
            }
        } else {
            $data['admin_detail'] = $this->admin->getadminById($admin_id);
            $data['permissions'] = $this->admin->getPermission();
            $this->load->view($this->path_to_view_admin . 'admin_addedit', $data);
        }
    }

}
