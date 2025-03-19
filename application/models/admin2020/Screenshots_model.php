<?php

class Screenshots_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->img_size_array = array(100 => 100, 336 => 600, 375 => 812);
        $this->table = 'screenshots';
    }

    public function get_list_count_screenshots() {
        $this->db->select('*');
        $this->db->order_by("screenshots_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function insert() {
        $thumb_sizes = $this->img_size_array;
        $gallery_title = $this->input->post('screenshot');
        if ($_FILES['screenshot']['name'] == "") {
            $image = $this->input->post('old_screenshot');
        } else {
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['screenshot']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->screenshot_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('screenshot')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->screenshot_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->screenshot_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->screenshot_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->screenshot_image . $image, $this->screenshot_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
            $data = array(
                'screenshot' => $image,
                'dp_order' => $this->input->post('dp_order'),
                'status' => '1',
                'created_date' => date('Y-m-d H:i:s')
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
        $screenshots_id = $this->input->post('screenshots_id');
        if ($_FILES['screenshot']['name'] == "") {
            $image = $this->input->post('old_screenshot');
        } else {
            if (file_exists($this->screenshot_image . $this->input->post('old_screenshot'))) {
                @unlink($this->screenshot_image . $this->input->post('old_screenshot'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_screenshot'))) {
                    @unlink($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_screenshot'));
                }
            }
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['screenshot']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->screenshot_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('screenshot')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->screenshot_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->screenshot_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->screenshot_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->screenshot_image . $image, $this->screenshot_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
        }
        $data = array(
            'screenshot' => $image,
            'dp_order' => $this->input->post('dp_order'),
        );
        $this->db->where('screenshots_id', $screenshots_id);
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return flase;
        }
    }

    public function getScreenshotById($screenshot_id) {
        $this->db->select('*');
        $this->db->where('screenshots_id', $screenshot_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus() {
        $this->db->set('status', $this->input->post('publish'));
        $this->db->where('screenshots_id', $this->input->post('screenshotsid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $thumb_sizes = $this->img_size_array;
        
        $data = $this->getScreenshotById($this->input->post('screenshotsid'));
        if (file_exists($this->screenshot_image . $data['screenshot'])) {
            @unlink($this->screenshot_image . $data['screenshot']);
        }
        foreach ($thumb_sizes as $width => $height) {
            if (file_exists($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $data['screenshot'])) {
                @unlink($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $data['screenshot']);
            }
        }
        $this->db->where('screenshots_id', $this->input->post('screenshotsid'));

        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        $thumb_sizes = $this->img_size_array;
        
        foreach($this->input->post('ids') as $key => $screenshots_id){
            $data = $this->getScreenshotById($screenshots_id);
            if (file_exists($this->screenshot_image . $data['screenshot'])) {
                @unlink($this->screenshot_image . $data['screenshot']);
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $data['screenshot'])) {
                    @unlink($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $data['screenshot']);
                }
            }
            $this->db->where('screenshots_id', $screenshots_id);

            $this->db->delete($this->table);            
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $screenshots_id){
            $screenshots_data = $this->getScreenshotById($screenshots_id);

            if($screenshots_data['status'] == '0')
                $status = '1';
            else
                $status = '0';

            $this->db->set('status', $status);
            $this->db->where('screenshots_id', $screenshots_id);
            $this->db->update($this->table);
        }
        return true;        
    }


}
