<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'order_method';
    }
}
