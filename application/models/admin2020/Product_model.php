<?php

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'product';
        $this->table_product_map = 'product_map';
        $this->img_size_array = array(100 => 100, 1000 => 500, 253 => 90);
        $this->column_headers = array(
            'Product Name' => '',
            'Image' => '',
            'Actual Price (' . $this->functions->getPoint() . ')' => '',
            'Selling Price (' . $this->functions->getPoint() . ')' => '',
            'Status' => '',
        );
    }

    public function get_list_count_product() {

        $this->db->select('*');
        $this->db->order_by("product_id", "Desc");
        $query = $this->db->get('product');
        return $query->num_rows();
    }

    public function product_data() {
        $this->db->select('*');
        $this->db->order_by("product_id", "ASC");
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function insert() {
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['product_image']['name'] == "") {
            $image = '';
        } else {
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['product_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->product_image;
            $config['allowed_types'] = 'jpg|png|jpeg';

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('product_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->product_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->product_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->product_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->product_image . $image, $this->product_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
        }
        $data = array(
            'product_name' => $this->input->post('product_name'),
            'product_image' => $image,
            'product_short_description' => $this->input->post('product_short_description'),
            'product_description' => $this->input->post('product_description'),
            'product_actual_price' => $this->input->post('product_actual_price'),
            'product_selling_price' => $this->input->post('product_selling_price'),
            'date_created' => date('Y-m-d H:i:s')
        );
        if ($this->db->insert('product', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['product_image']['name'] == "") {
            $image = $this->input->post('old_product_image');
        } else {
            if (file_exists($this->product_image . $this->input->post('old_product_image'))) {
                @unlink($this->product_image . $this->input->post('old_product_image'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->product_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_product_image'))) {
                    @unlink($this->product_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_product_image'));
                }
            }
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['product_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->product_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('product_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->product_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->product_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->product_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->product_image . $image, $this->product_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
        }

        $data = array(
            'product_name' => $this->input->post('product_name'),
            'product_image' => $image,
            'product_short_description' => $this->input->post('product_short_description'),
            'product_description' => $this->input->post('product_description'),
            'product_actual_price' => $this->input->post('product_actual_price'),
            'product_selling_price' => $this->input->post('product_selling_price'),
        );
        $this->db->where('product_id', $this->input->post('product_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getproductById($product_id) {
        $this->db->select('*');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus() {
        $this->db->set('product_status', $this->input->post('publish'));
        $this->db->where('product_id', $this->input->post('productid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $thumb_sizes = $this->img_size_array; 

        $data = $this->getproductById($this->input->post('productid'));

        if (file_exists($this->product_image . $data['product_image'])) {
            @unlink($this->product_image . $data['product_image']);
        }
        foreach ($thumb_sizes as $width => $height) {
            if (file_exists($this->product_image . "thumb/" . $width . "x" . $height . "_" . $data['product_image'])) {
                @unlink($this->product_image . "thumb/" . $width . "x" . $height . "_" . $data['product_image']);
            }
        }
        $this->db->where('product_id', $this->input->post('productid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        $thumb_sizes = $this->img_size_array; 
        foreach($this->input->post('ids') as $key => $product_id){
            
            $data = $this->getproductById($this->input->post('productid'));

            if (file_exists($this->product_image . $data['product_image'])) {
                @unlink($this->product_image . $data['product_image']);
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->product_image . "thumb/" . $width . "x" . $height . "_" . $data['product_image'])) {
                    @unlink($this->product_image . "thumb/" . $width . "x" . $height . "_" . $data['product_image']);
                }
            }
            $this->db->where('product_id', $product_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $product_id){
            $product_data = $this->getproductById($product_id);

            if($product_data['product_status'] == '0')
                $product_status = '1';
            else
                $product_status = '0';

            $this->db->set('product_status', $product_status);
            $this->db->where('product_id', $product_id);
            $this->db->update($this->table);
        }
        return true;        
    }

}
