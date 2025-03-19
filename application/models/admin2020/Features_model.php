<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Features_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();        
        $this->img_size_array = array(100 => 100);
        $this->table = 'features_tab';

    }

    public function get_list_count_features()
    {
        $this->db->select('*');
        $this->db->order_by("f_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function insert()
    {
        $thumb_sizes = $this->img_size_array;
        $unique = $this->functions->GenerateUniqueFilePrefix();
        $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['f_tab_img']['name']);
        $config['file_name'] = $image;
        $config['upload_path'] = $this->screenshot_image;
        $config['allowed_types'] = 'jpg|png|jpeg';
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('f_tab_img')) {
            $data['error'] = array('error' => $this->upload->display_errors());
        } else {
            $data['f_tab_img'] = $this->upload->data();
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
            'f_tab_name' => $this->input->post('f_tab_name'),
            'f_tab_title' => $this->input->post('f_tab_title'),
            'f_tab_text' => $this->input->post('f_tab_text'),
            'f_tab_image' => $image,
            'f_tab_img_position' => $this->input->post('f_tab_img_position'),
            'f_tab_order'  => $this->input->post('f_tab_order'),
            'f_tab_status' => '1',
            'date_created' => date('Y-m-d H:i:s'),
        );
        if ($this->db->insert($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update()
    {
        $f_id = $this->input->post('f_id');
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['f_tab_img']['name'] == "") {
            $image = $this->input->post('old_f_tab_img');
        } else {
            if (file_exists($this->screenshot_image . $this->input->post('old_f_tab_img'))) {
                @unlink($this->screenshot_image . $this->input->post('old_f_tab_img'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_f_tab_img'))) {
                    @unlink($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_f_tab_img'));
                }
            }
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['f_tab_img']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->screenshot_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('f_tab_img')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['f_tab_img'] = $this->upload->data();
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
            'f_tab_name' => $this->input->post('f_tab_name'),
            'f_tab_title' => $this->input->post('f_tab_title'),
            'f_tab_text' => $this->input->post('f_tab_text'),
            'f_tab_image' => $image,
            'f_tab_img_position' => $this->input->post('f_tab_img_position'),
            'f_tab_order'  => $this->input->post('f_tab_order'),
            'f_tab_status' => '1',            
        );
        $this->db->where('f_id', $f_id);
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getfeaturesById($f_id)
    {
        $this->db->select('*');
        $this->db->where('f_id', $f_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus()
    {
        $this->db->set('f_tab_status', $this->input->post('publish'));
        $this->db->where('f_id', $this->input->post('fid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }

    }

    public function delete()
    {
        $thumb_sizes = $this->img_size_array;

        $data = $this->getfeaturesById($this->input->post('fid'));
        if (file_exists($this->screenshot_image . $data['f_tab_image'])) {
            @unlink($this->screenshot_image . $data['f_tab_image']);
        }
        foreach ($thumb_sizes as $width => $height) {
            if (file_exists($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $data['f_tab_image'])) {
                @unlink($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $data['f_tab_image']);
            }
        }
        $this->db->where('f_id', $this->input->post('fid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }

    }

    public function multiDelete() {   
        $thumb_sizes = $this->img_size_array;
        foreach($this->input->post('ids') as $key => $f_id){
            $data = $this->getfeaturesById($f_id);
            if (file_exists($this->screenshot_image . $data['f_tab_image'])) {
                @unlink($this->screenshot_image . $data['f_tab_image']);
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $data['f_tab_image'])) {
                    @unlink($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $data['f_tab_image']);
                }
            }
            $this->db->where('f_id', $f_id);
            $this->db->delete($this->table);            
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $f_id){
            $data = $this->getfeaturesById($f_id);

            if($data['f_tab_status'] == '0')
                $f_tab_status = '1';
            else
                $f_tab_status = '0';

            $this->db->set('f_tab_status', $f_tab_status);
            $this->db->where('f_id', $f_id);
            $this->db->update($this->table);
        }
        return true;        
    }

}
