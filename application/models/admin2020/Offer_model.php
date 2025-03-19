<?php

class Offer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'offer';
        $this->table_product_map = 'offer_map';
        $this->img_size_array = array(100 => 100, 1000 => 500, 253 => 90);
        $this->column_headers = array(
            'Offer Name' => '',
            'Image' => '',
            'Actual Price (' . $this->functions->getPoint() . ')' => '',
            'Selling Price (' . $this->functions->getPoint() . ')' => '',
            'Status' => '',
        );
    }

    public function get_list_count_product() {

        $this->db->select('*');
        $this->db->order_by("offer_id", "Desc");
        $query = $this->db->get('offer');
        return $query->num_rows();
    }

    public function product_data() {
        $this->db->select('*');
        $this->db->order_by("offer_id", "ASC");
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function insert() {
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['offer_image']['name'] == "") {
            $image = '';
        } else {
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['offer_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->offer_image;
            $config['allowed_types'] = 'jpg|png|jpeg';

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('offer_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->offer_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->offer_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->offer_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->offer_image . $image, $this->offer_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
        }
        $data = array(
            'offer_name' => $this->input->post('offer_name'),
            'offer_image' => $image,
            'offer_short_description' => $this->input->post('offer_short_description'),
            'offer_description' => $this->input->post('offer_description'),
            'offer_actual_price' => $this->input->post('offer_actual_price'),
            'offer_selling_price' => $this->input->post('offer_selling_price'),
            'date_created' => date('Y-m-d H:i:s')
        );
        if ($this->db->insert('offer', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['offer_image']['name'] == "") {
            $image = $this->input->post('old_offer_image');
        } else {
            if (file_exists($this->offer_image . $this->input->post('old_offer_image'))) {
                @unlink($this->offer_image . $this->input->post('old_offer_image'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->offer_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_offer_image'))) {
                    @unlink($this->offer_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_offer_image'));
                }
            }
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['offer_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->offer_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('offer_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->offer_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->offer_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->offer_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->offer_image . $image, $this->offer_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
        }

        $data = array(
            'offer_name' => $this->input->post('offer_name'),
            'offer_image' => $image,
            'offer_short_description' => $this->input->post('product_short_description'),
            'offer_description' => $this->input->post('product_description'),
            'offer_actual_price' => $this->input->post('product_actual_price'),
            'offer_selling_price' => $this->input->post('product_selling_price'),
        );
        $this->db->where('offer_id', $this->input->post('offer_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getproductById($offer_id) {
        $this->db->select('*');
        $this->db->where('offer_id', $offer_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus() {
        $this->db->set('offer_status', $this->input->post('publish'));
        $this->db->where('offer_id', $this->input->post('offerid'));
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
