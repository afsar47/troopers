<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function getAllProduct() {
        $product = array();
        $this->db->select("*");
        $this->db->where("p.product_status", '1');
        $this->db->order_by('date_created', 'DESC');
        $query = $this->db->get('product as p');
        $product = $query->result();
        return $product;
    }

    public function getProductByID($product_id) {
        $this->db->select("*");
        $this->db->where("p.product_id", $product_id);
        $query = $this->db->get('product as p');
        $product = $query->row_array();
        return $product;
    }

    public function getMyOrder() {
        $this->db->select("*");
        $this->db->where("member_id", $this->member->front_member_id);
        $this->db->order_by('created_date', 'DESC');
        $query = $this->db->get('orders');
        return $query->result();
    }

    public function getOrderByID($order_id) {
        $this->db->select("o.*,c.courier_link");
        $this->db->join('courier as c', 'c.courier_id = o.courier_id', 'left');
        $this->db->where("orders_id", $order_id);
        $query = $this->db->get('orders as o');
        $order = $query->row_array();
        return $order;
    }

}
