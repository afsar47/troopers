<?php

class Banner_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->img_size_array = array(100 => 100, 1000 => 500);
        $this->table = 'banner';
    }

    public function get_list_count_banner() {
        $this->db->select('*');
        $this->db->order_by("banner_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function insert() {
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['banner_image']['name'] == "") {
            $image = $this->input->post('old_banner_image');
        } else {
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['banner_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->banner_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('banner_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {               
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->banner_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                           
                        $this->image->initialize($this->banner_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->banner_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->banner_image . $image, $this->banner_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }                    
                }
            }
            $banner_link = '';
            if ($this->input->post('banner_link_type') == 'app')
                $banner_link = $this->input->post('app_banner_link');
            elseif ($this->input->post('banner_link_type') == 'web')
                $banner_link = $this->input->post('web_banner_link');
            $data = array(
                'banner_image' => $image,
                'banner_title' => $this->input->post('banner_title'),
                'banner_link_type' => $this->input->post('banner_link_type'),
                'banner_link' => $banner_link,
                'link_id' => $this->input->post('game_id'),
                'status' => '1',
                'date_created' => date('Y-m-d H:i:s')
            );
            if ($this->db->insert($this->table, $data)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function update() {
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['banner_image']['name'] == "") {
            $image = $this->input->post('old_banner_image');
        } else {
            if (file_exists($this->banner_image . $this->input->post('old_banner_image'))) {
                @unlink($this->banner_image . $this->input->post('old_banner_image'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->banner_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_banner_image'))) {
                    @unlink($this->banner_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_banner_image'));
                }
            }
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['banner_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->banner_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('banner_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
                print_r($data['error']);
                exit;
            } else {                
                foreach ($thumb_sizes as $key => $val) {                    
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->banner_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                           
                        $this->image->initialize($this->banner_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->banner_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->banner_image . $image, $this->banner_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
        }
       
        $banner_link = '';
        if ($this->input->post('banner_link_type') == 'app')
            $banner_link = $this->input->post('app_banner_link');
        elseif ($this->input->post('banner_link_type') == 'web')
            $banner_link = $this->input->post('web_banner_link');
        $data = array(
            'banner_image' => $image,
            'banner_title' => $this->input->post('banner_title'),
            'banner_link_type' => $this->input->post('banner_link_type'),
            'banner_link' => $banner_link,
            'link_id' => $this->input->post('game_id'),
            'status' => '1',
        );
        $this->db->where('banner_id', $this->input->post('banner_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return flase;
        }
    }

    public function getBannerById($banner_id) {
        $this->db->select('*');
        $this->db->where('banner_id', $banner_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus() {
        $this->db->set('status', $this->input->post('publish'));
        $this->db->where('banner_id', $this->input->post('bannerid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $thumb_sizes = $this->img_size_array;        
        $data = $this->getBannerById($this->input->post('bannerid'));
        if (file_exists($this->banner_image . $data['banner_image'])) {
            @unlink($this->banner_image . $data['banner_image']);
        }
        foreach ($thumb_sizes as $width => $height) {
            if (file_exists($this->banner_image . "thumb/" . $width . "x" . $height . "_" . $data['banner_image'])) {
                @unlink($this->banner_image . "thumb/" . $width . "x" . $height . "_" . $data['banner_image']);
            }
        }
        $this->db->where('banner_id', $this->input->post('bannerid'));

        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        $thumb_sizes = $this->img_size_array;
        foreach($this->input->post('ids') as $key => $banner_id){
            $data = $this->getBannerById($banner_id);
            if (file_exists($this->banner_image . $data['banner_image'])) {
                @unlink($this->banner_image . $data['banner_image']);
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->banner_image . "thumb/" . $width . "x" . $height . "_" . $data['banner_image'])) {
                    @unlink($this->banner_image . "thumb/" . $width . "x" . $height . "_" . $data['banner_image']);
                }
            }
            $this->db->where('banner_id', $banner_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $banner_id){
            $data = $this->getBannerById($banner_id);

            if($data['status'] == '0')
                $status = '1';
            else
                $status = '0';

            $this->db->set('status', $status);
            $this->db->where('banner_id', $banner_id);
            $this->db->update($this->table);
        }
        return true;        
    }

}
