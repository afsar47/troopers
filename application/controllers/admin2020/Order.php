<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('order')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->load->model($this->path_to_view_admin . '/Order_model', 'order');
        $this->con = $this->functions->mysql_connection();
    }

    public function index() {
        $data['order'] = true;
        $data['title'] = $this->lang->line('text_order');

        if ($this->input->post('action') == "change_publish") {
            if ($result = $this->changePublishStatus()) {
                $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_order'));
                redirect($this->path_to_view_admin . 'order/');
            }
        }
        $this->load->view($this->path_to_view_admin . 'order_manage', $data);
    }

    public function order_detail() {
        if(!$this->functions->check_permission('order_view')) {
            redirect($this->path_to_view_admin . 'order');
        }

        $data['order_detail'] = true;
        $data['title'] = $this->lang->line('text_order_detail');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['orders_id'] = $this->input->post('orders_id');
            $data['courier_id'] = $this->input->post('courier_id');
            $data['tracking_id'] = $this->input->post('tracking_id');
            $this->form_validation->set_rules('courier_id', 'lang:text_courier', 'required', array('required' => $this->lang->line('err_courier_id_req')));
            $this->form_validation->set_rules('tracking_id', 'lang:text_tracking_id', 'required', array('required' => $this->lang->line('err_tracking_id_req')));
            if ($this->form_validation->run() == FALSE) {
                $data['order'] = $this->getOrderDetail($this->input->post('orders_id'));
                $data['courier_data'] = $this->getCourierData();
                $this->load->view($this->path_to_view_admin . 'match_addedit', $data);
            } else {
                $this->db->set('courier_id', $this->input->post('courier_id'));
                $this->db->set('tracking_id', $this->input->post('tracking_id'));
                $this->db->where('orders_id', $this->input->post('orders_id'));
                if ($this->db->update('orders')) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_tracking'));
                    redirect($this->path_to_view_admin . 'order/order_detail/' . $this->input->post('orders_id'));
                }
            }
        } else {
            $order_id = $this->uri->segment('4');
            $data['order'] = $this->getOrderDetail($order_id);
            $data['courier_data'] = $this->getCourierData();
            $this->load->view($this->path_to_view_admin . 'order_detail', $data);
        }
    }

    public function changePublishStatus() {
        $this->db->set('order_status', $this->input->post('publish'));
        $this->db->where('orders_id', $this->input->post('oid'));
        if ($query = $this->db->update('orders')) {
            return true;
        } else {
            return false;
        }
    }

    public function getOrderDetail($order_id) {
        $this->db->select('o.*,m.user_name');
        $this->db->where('o.orders_id', $order_id);
        $this->db->join('member as m', 'o.member_id = m.member_id', 'LEFT');
        $query = $this->db->get('orders as o');
        return $query->row();
    }

    public function getCourierData() {
        $this->db->select('*');
        $this->db->where('status', '1');
        $query = $this->db->get('courier');
        return $query->result();
    }

    public function get_list_count_Order() {
        $this->db->select('*');
        $this->db->join('member as m', 'o.member_id = m.member_id', 'LEFT');
        $query = $this->db->get('orders as o');
        return $query->num_rows();
    }

    public function setDatatableOrder() {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'order_id',
            1 => 'order_no',
            2 => 'user_name',
            3 => 'product_name',
            5 => 'order_status',
            6 => 'created_date',
        );
        $totalData = $this->get_list_count_Order();
        $totalFiltered = $totalData;
        $sql = "SELECT o.member_id,o.orders_id,o.order_no,o.order_status,o.product_price,o.created_date,m.user_name FROM orders as o";        
        $sql .= " LEFT JOIN member as m ON o.member_id = m.member_id";
        $sql .= " WHERE 1";
        if (!empty($requestData['search']['value'])) {
            $sql .= "  AND ( order_no LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  order_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  user_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  order_status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  created_date LIKE '%" . $requestData['search']['value'] . "%' ) ";
        }

        // echo $sql;die();
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query);

        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY o.created_date DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }

        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $row['order_no'];
            $nestedData[] = '<a href=' . base_url() . $this->path_to_view_admin . 'members/member_detail/' . $row['member_id'] . '>' . $row['user_name'] . '</a>';
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['product_price']);
            $Hold = $Processing = $Delivered = $Failed = $Cancelled = '';
            if ($row['order_status'] == $this->lang->line('text_hold'))
                $Hold = 'selected';
            elseif ($row['order_status'] == $this->lang->line('text_processing'))
                $Processing = 'selected';
            elseif ($row['order_status'] == $this->lang->line('text_delivered'))
                $Delivered = 'selected';
            elseif ($row['order_status'] == $this->lang->line('text_failed'))
                $Failed = 'selected';
            elseif ($row['order_status'] == $this->lang->line('text_cancelled'))
                $Cancelled = 'selected';

            $nestedData[] = '<select onChange="javascript: changePublishStatus(document.frmorderlist,' . $row['orders_id'] . ',this.value);">'
                    . '<option value="' . $this->lang->line('text_hold') . '" ' . $Hold . '>' . $this->lang->line('text_hold') . '</option>'
                    . '<option value="' . $this->lang->line('text_processing') . '" ' . $Processing . ' >' . $this->lang->line('text_processing') . '</option>'
                    . '<option value="' . $this->lang->line('text_delivered') . '" ' . $Delivered . '>' . $this->lang->line('text_delivered') . '</option>'
                    . '<option value="' . $this->lang->line('text_failed') . '" ' . $Failed . '>' . $this->lang->line('text_failed') . '</option>'
                    . '<option value="' . $this->lang->line('text_cancelled') . '"  ' . $Cancelled . '>' . $this->lang->line('text_cancelled') . '</option></select>';
            $nestedData[] = $row['created_date'];
            $nestedData[] = '<a  class="" data-original-title="Detail" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" href=' . base_url() . $this->path_to_view_admin . 'order/order_detail/' . $row['orders_id'] . '><i class="fa fa-eye"></i> </a>';
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

}
