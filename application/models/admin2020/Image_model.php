<?php

class Image_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'image';
        $this->img_size_array = array(100 => 100, 1000 => 500, 253 => 90);
//        $this->column_headers = array(
//            'Image Name' => '',
//            'Image' => '',
//        );
    }

    public function getImage() {
        $this->db->select('*');
        $query = $this->db->get('image');
        return $query->result();
    }

    public function get_list_count_image() {
        $this->db->select('*');
        $this->db->order_by("image_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function insert() {
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['image_name']['name'] == "") {
            $image = '';
        } else {
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['image_name']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->select_image;
            $config['allowed_types'] = 'jpg|png|jpeg';

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('image_name')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $_FILES["image_name"]['tmp_name'];
                    $config['create_thumb'] = false;
                    $config['maintain_ratio'] = true;
                    $config['width'] = $key;
                    $config['height'] = $val;
                    $config['new_image'] = $this->select_image . "thumb/" . $config['width'] . "x" . $config['height'] . "_" . $image;
                    $this->image_lib->clear();
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                }
            }
        }

        $data = array(
            'image_title' => $this->input->post('image_title'),
            'image_name' => $image,
            'created_date' => date('Y-m-d H:i:s')
        );
        if ($this->db->insert('image', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['image_name']['name'] == "") {
            $image = $this->input->post('old_image_name');
        } else {
            if (file_exists($this->select_image . $this->input->post('old_image_name'))) {
                @unlink($this->select_image . $this->input->post('old_image_name'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->select_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_image_name'))) {
                    @unlink($this->select_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_image_name'));
                }
            }
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['image_name']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->select_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('image_name')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $_FILES["image_name"]['tmp_name'];
                    $config['create_thumb'] = false;
                    $config['maintain_ratio'] = true;
                    $config['width'] = $key;
                    $config['height'] = $val;
                    $config['new_image'] = $this->select_image . "thumb/" . $config['width'] . "x" . $config['height'] . "_" . $image;
                    $this->image_lib->clear();
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                }
            }
        }
        $data = array(
            'image_title' => $this->input->post('image_title'),
            'image_name' => $image,
        );
        $this->db->where('image_id', $this->input->post('image_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getimageById($image_id) {
        $this->db->select('*');
        $this->db->where('image_id', $image_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus() {
        $this->db->set('status', $this->input->post('publish'));
        $this->db->where('image_id', $this->input->post('imageid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $thumb_sizes = $this->img_size_array;
       
        $data = $this->getimageById($this->input->post('imageid'));        
        if (file_exists($this->image_image . $data['image_image'])) {
            @unlink($this->image_image . $data['image_image']);
        }
        foreach ($thumb_sizes as $width => $height) {
            if (file_exists($this->image_image . "thumb/" . $width . "x" . $height . "_" . $data['image_image'])) {
                @unlink($this->image_image . "thumb/" . $width . "x" . $height . "_" . $data['image_image']);
            }
        }
        $this->db->where('image_id', $this->input->post('imageid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        $thumb_sizes = $this->img_size_array;

        foreach($this->input->post('ids') as $key => $image_id){
            $data = $this->getimageById($image_id);        
            if (file_exists($this->image_image . $data['image_image'])) {
                @unlink($this->image_image . $data['image_image']);
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->image_image . "thumb/" . $width . "x" . $height . "_" . $data['image_image'])) {
                    @unlink($this->image_image . "thumb/" . $width . "x" . $height . "_" . $data['image_image']);
                }
            }
            $this->db->where('image_id', $image_id);
            $this->db->delete($this->table);            
        }     
        
        return true;        
    }

}
