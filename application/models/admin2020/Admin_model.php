<?php

class Admin_model extends CI_Model {

    function __construct() {
//parent::CI_Model();
        parent::__construct();

        $this->table = 'admin';        
    }

    public function getPermission() {
        $this->db->select('*');
        $this->db->where('parent_status', 'parent');
        $query = $this->db->get('permission');
        return $query->result_array();
    }

    function get_list_count_admin() {

        $this->db->select('*');
        $this->db->order_by("id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }
    
    function insert() {

        if ($this->input->post('permission')) {
            $permission = json_encode($this->input->post('permission'));
        } else {
            $permission = '[]';
        }

        $data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password')),
            'permission' => $permission,
            'craeted_date' => date('Y-m-d H:i:s')
        );
        
        if ($this->db->insert($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }    

    function update() {
        
        if ($this->input->post('permission')) {
            $permission = json_encode($this->input->post('permission'));
        } else {
            $permission = '[]';
        }
        
        $data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),            
            'permission' => $permission,            
        );            

        $this->db->where('id', $this->input->post('admin_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    
    function getadminById($id) {
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }


    function delete() {
//        print_r($_POST);die();        

        $this->db->where('id', $this->input->post('adminid'));

        if ($query = $this->db->delete($this->table))
            return true;
        else
            return false;
    }

}

?>
