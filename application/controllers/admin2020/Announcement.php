<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Announcement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('announcement')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_view_admin . 'Announcement_model', 'announcement');
    }

    function index() {
        $data['announcement'] = true;
        $data['btn'] = $this->lang->line('text_add_announcement');
        $data['title'] = $this->lang->line('text_announcement');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('announcementid') <= 2) || !$this->functions->check_permission('announcement_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_announcement'));
                redirect($this->path_to_view_admin . 'announcement/');
            } else {
                if ($result = $this->announcement->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_announcement'));
                    redirect($this->path_to_view_admin . 'announcement/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'announcement_manage', $data);
        }
    }

    function multi_action(){

        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 2) || !$this->functions->check_permission('announcement_delete')) {
                echo $this->lang->line('text_err_delete_announcement');
            } else {
                $this->announcement->multiDelete();
            }
        }
    }

    function setDatatableAnnouncement() {
        $requestData = $_REQUEST;
        $columns = array(
            2 => 'announcement_desc',
            3 => 'date_created'
        );
        $totalData = $this->announcement->get_list_count_announcement();
        $totalFiltered = $totalData;

        $sql = "SELECT announcement_id,announcement_desc,date_created FROM announcement";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  announcement_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  announcement_desc LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData[] = '<input type="checkbox" value="'. $row['announcement_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = '<span title="' . $row['announcement_desc'] . '">' . substr($row['announcement_desc'], 0, 50) . '....' . '</span>';
            $nestedData[] = $row['date_created'];
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'announcement/edit/' . $row['announcement_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && $row['announcement_id'] <= 2) {
                $delete = '<a  class="" data-original-title="Delete" disabled="disabled" data-placement="top"  style="font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDelete(document.frmannouncementlist,' . $row['announcement_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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

    function insert() {
        $data['announcement_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_announcement');
        $data['title'] = $this->lang->line('text_announcement');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_announcement');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['announcement_desc'] = $this->input->post('announcement_desc');
            $this->form_validation->set_rules('announcement_desc', 'lang:text_announcement', 'required', array('required' => $this->lang->line('err_announcement_req')));
            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'announcement_addedit', $data);
            } else {
                if ($result = $this->announcement->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_announcement'));
                    redirect($this->path_to_view_admin . 'announcement/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'announcement_addedit', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('announcement_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_announcement'));
            redirect($this->path_to_view_admin . 'announcement');
        }

        $data['announcement_addedit'] = true;
        $announcement_id = $this->uri->segment('4');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['title'] = $this->lang->line('text_edit_announcement');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('announcement_id') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_announcement'));
                redirect($this->path_to_view_admin . 'announcement/');
            } else {
                $data['announcement_desc'] = $this->input->post('announcement_desc');
                $this->form_validation->set_rules('announcement_desc', 'lang:text_announcement', 'required', array('required' => $this->lang->line('err_announcement_req')));
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'announcement_addedit', $data);
                } else {
                    if ($result = $this->announcement->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_announcement'));
                        redirect($this->path_to_view_admin . 'announcement/');
                    }
                }
            }
        } else {
            $data['announcement_detail'] = $this->announcement->getannouncementById($announcement_id);
            $this->load->view($this->path_to_view_admin . 'announcement_addedit', $data);
        }
    }

}
