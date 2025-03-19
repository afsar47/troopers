<?php

class Slider_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->img_size_array = array(100 => 100, 1000 => 500, 253 => 90);
        $this->table = 'slider';
    }

    public function get_list_count_slider() {
        $this->db->select('*');
        $this->db->order_by("slider_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function insert() {
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['slider_image']['name'] == "") {
            $image = $this->input->post('old_slider_image');
        } else {
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['slider_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->slider_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('slider_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->slider_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->slider_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->slider_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->slider_image . $image, $this->slider_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
            $slider_link = '';
            if ($this->input->post('slider_link_type') == 'app')
                $slider_link = $this->input->post('app_slider_link');
            elseif ($this->input->post('slider_link_type') == 'web')
                $slider_link = $this->input->post('web_slider_link');
            $slider_link_type = '';
            if ($this->input->post('slider_link_type'))
                $slider_link_type = $this->input->post('slider_link_type');
            $data = array(
                'slider_image' => $image,
                'slider_title' => $this->input->post('slider_title'),
                'slider_link_type' => $slider_link_type,
                'slider_link' => $slider_link,
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
        if ($_FILES['slider_image']['name'] == "") {
            $image = $this->input->post('old_slider_image');
        } else {
            if (file_exists($this->slider_image . $this->input->post('old_slider_image'))) {
                @unlink($this->slider_image . $this->input->post('old_slider_image'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->slider_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_slider_image'))) {
                    @unlink($this->slider_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_slider_image'));
                }
            }
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['slider_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->slider_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('slider_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->slider_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->slider_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->slider_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->slider_image . $image, $this->slider_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
        }
        $slider_link = '';
        if ($this->input->post('slider_link_type') == 'app')
            $slider_link = $this->input->post('app_slider_link');
        elseif ($this->input->post('slider_link_type') == 'web')
            $slider_link = $this->input->post('web_slider_link');
        if ($this->input->post('slider_link_type'))
            $slider_link_type = $this->input->post('slider_link_type');
        $data = array(
            'slider_image' => $image,
            'slider_title' => $this->input->post('slider_title'),
            'slider_link_type' => $slider_link_type,
            'slider_link' => $slider_link,
            'link_id' => $this->input->post('game_id'),
            'status' => '1',
        );
        $this->db->where('slider_id', $this->input->post('slider_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getSliderById($slider_id) {
        $this->db->select('*');
        $this->db->where('slider_id', $slider_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus() {
        $this->db->set('status', $this->input->post('publish'));
        $this->db->where('slider_id', $this->input->post('sliderid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $thumb_sizes = $this->img_size_array;
        $data = $this->getSliderById($this->input->post('sliderid'));
        if (file_exists($this->slider_image . $data['slider_image'])) {
            @unlink($this->slider_image . $data['slider_image']);
        }
        foreach ($thumb_sizes as $width => $height) {
            if (file_exists($this->slider_image . "thumb/" . $width . "x" . $height . "_" . $data['slider_image'])) {
                @unlink($this->slider_image . "thumb/" . $width . "x" . $height . "_" . $data['slider_image']);
            }
        }
        $this->db->where('slider_id', $this->input->post('sliderid'));

        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        $thumb_sizes = $this->img_size_array;
        foreach($this->input->post('ids') as $key => $slider_id){
            $data = $this->getSliderById($slider_id);
            if (file_exists($this->slider_image . $data['slider_image'])) {
                @unlink($this->slider_image . $data['slider_image']);
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->slider_image . "thumb/" . $width . "x" . $height . "_" . $data['slider_image'])) {
                    @unlink($this->slider_image . "thumb/" . $width . "x" . $height . "_" . $data['slider_image']);
                }
            }
            $this->db->where('slider_id', $slider_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $slider_id){
            $data = $this->getSliderById($slider_id);

            if($data['status'] == '0')
                $status = '1';
            else
                $status = '0';

            $this->db->set('status', $status);
            $this->db->where('slider_id', $slider_id);
            $this->db->update($this->table);
        }
        return true;        
    }

}
