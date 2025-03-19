<?php

class Product extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        if ($this->member->front_logged_in !== true) {
            redirect('login');
        }
        $this->load->model($this->path_to_default . 'Product_model', 'product');
        $this->load->model($this->path_to_default . 'Account_model', 'account');
    }

    function index() {
        $data['product'] = true;
        $data['title'] = $this->lang->line('text_product');
        $data['breadcrumb_title'] = $this->lang->line('text_shop_now');
        $data['product_data'] = $this->product->getAllProduct();
        $this->load->view($this->path_to_view_default . 'products', $data);
    }

    public function product_detail() {
        $product_id = $this->uri->segment('4');
        $data['product_detail'] = true;
        $data['title'] = $this->lang->line('text_product');
        $data['breadcrumb_title'] = $this->lang->line('text_product');
        $data['product'] = $this->product->getProductByID($product_id);
        $data['breadcrumb_title'] = $data['product']['product_name'];
        if (!empty($data['product'])) {
            $this->load->view($this->path_to_view_default . 'single_product', $data);
        } else {
            redirect($this->path_to_default . 'product');
        }
    }

    public function order_detail() {
        $order_id = $this->uri->segment('4');
        $data['order_detail'] = true;
        $data['title'] = $this->lang->line('text_order');
        $data['breadcrumb_title'] = $this->lang->line('text_product');
        $data['order_data'] = $this->product->getOrderByID($order_id);
        if (!empty($data['order_data'])) {
            $this->load->view($this->path_to_view_default . 'single_order_detail', $data);
        } else {
            redirect($this->path_to_default . 'product/order');
        }
    }

    public function order() {
        $product_id = $this->uri->segment('4');
        $data['product_order'] = true;
        $data['title'] = $this->lang->line('text_order');
        $data['breadcrumb_title'] = $this->lang->line('text_order');

        if ($this->input->post("submit") == "buy_now") {
            $data['full_name'] = $this->input->post('full_name');
            $data['address'] = $this->input->post('address');

            $this->form_validation->set_rules('full_name', 'lang:text_full_name', 'required', array('required' => $this->lang->line('err_full_name_req')));
            $this->form_validation->set_rules('address', 'lang:text_address', 'required', array('required' => $this->lang->line('err_address_req')));
            if ($this->form_validation->run() == FALSE) {
                $data['product'] = $this->product->getProductByID($this->input->post('product_id'));
                $data['member'] = $this->account->getMemberDetail($this->member->front_member_id);
                $data['breadcrumb_title'] = $data['product']['product_name'];
                $this->load->view($this->path_to_view_default . 'product_order', $data);
            } else {
                $this->load->library('user_agent');
                $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                $ip = $this->input->ip_address();
                $product = $this->product->getProductByID($this->input->post('product_id'));
                $member = $this->account->getMemberDetail($this->member->front_member_id);
                if ($member['wallet_balance'] + $member['join_money'] >= $product['product_selling_price']) {
                    $this->db->select("*");
                    $this->db->order_by("orders_id", 'DESC');
                    $this->db->limit("1");
                    $query = $this->db->get('orders');
                    $invoice = $query->row();
                    if ($invoice) {
                        $invoice_no = $invoice->no + 1;
                        $no = $invoice->no + 1;
                    } else {
                        $invoice_no = $no = 1;
                    }
                    $order_no = str_pad($invoice_no, 8, 'ORD0000', STR_PAD_LEFT);
                    $shipping_address = array(
                        'name' => $this->input->post('full_name'),
                        'address' => $this->input->post('address'),
                        'add_info' => $this->input->post('add_info'),
                    );
                    $orders_data = array(
                        'member_id' => $this->member->front_member_id,
                        'no' => $no,
                        'order_no' => $order_no,
                        'product_name' => $product['product_name'],
                        'product_image' => $product['product_image'],
                        'product_price' => $product['product_selling_price'],
                        'shipping_address' => serialize($shipping_address),
                        'order_status' => 'Hold',
                        'entry_from' => '2',
                        'created_date' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('orders', $orders_data);
                    $order_id = $this->db->insert_id();
                    if ($product['product_selling_price'] > 0) {
                        if ($member['join_money'] > $product['product_selling_price']) {
                            $join_money = $member['join_money'] - $product['product_selling_price'];
                            $wallet_balance = $member['wallet_balance'];
                        } elseif ($member['join_money'] < $product['product_selling_price']) {
                            $join_money = 0;
                            $amount1 = $product['product_selling_price'] - $member['join_money'];
                            $wallet_balance = $member['wallet_balance'] - $amount1;
                        } elseif ($member['join_money'] == $product['product_selling_price']) {
                            $join_money = 0;
                            $wallet_balance = $member['wallet_balance'];
                        }
                        $acc_data = array(
                            'member_id' => $this->member->front_member_id,
                            'order_id' => $order_id,
                            'deposit' => 0,
                            'withdraw' => $product['product_selling_price'],
                            'join_money' => $join_money,
                            'win_money' => $wallet_balance,
                            'note' => 'Product Order',
                            'note_id' => '12',
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

                        $this->session->set_flashdata('success', $this->lang->line('text_succ_order'));
                        redirect($this->path_to_default . 'product/product_detail/' . $product['product_id']);
                    }
                } else {
                    $this->session->set_flashdata('error', $this->lang->line('text_err_wallet'));
                    redirect($this->path_to_default . 'wallet/');
                }
            }
        } else {
            $data['product'] = $this->product->getProductByID($product_id);
            $data['member'] = $this->account->getMemberDetail($this->member->front_member_id);
            $data['breadcrumb_title'] = $data['product']['product_name'];
            if (!empty($data['product'])) {
                if ($data['product']['product_status'] == 1) {
                    $this->load->view($this->path_to_view_default . 'product_order', $data);
                } else {
                    redirect($this->path_to_default . 'product');
                }
            } else {
                redirect($this->path_to_default . 'product');
            }
        }
    }

    public function my_orders() {
        $data['product'] = true;
        $data['title'] = $this->lang->line('text_my_orders');
        $data['breadcrumb_title'] = $this->lang->line('text_my_orders');
        $data['order_data'] = $this->product->getMyOrder();
        $this->load->view($this->path_to_view_default . 'my_orders', $data);
    }

}

?>
