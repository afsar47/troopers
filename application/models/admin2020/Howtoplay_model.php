<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Howtoplay_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('image'); 
        $this->img_size_array = array(100 => 100);
        $this->table = 'howtoplay_content';
    }

    public function get_list_count_htpcontent()
    {
        $this->db->select('*');
        $this->db->order_by("htp_content_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function insert()
    {
        $thumb_sizes = $this->img_size_array;
        $unique = $this->functions->GenerateUniqueFilePrefix();
        $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['htp_content_image']['name']);
        $config['file_name'] = $image;
        $config['upload_path'] = $this->screenshot_image;
        $config['allowed_types'] = 'jpg|png|jpeg';
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('htp_content_image')) {
            $data['error'] = array('error' => $this->upload->display_errors());
        } else {
            $data['htp_content_image'] = $this->upload->data();
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
            'htp_content_title' => $this->input->post('htp_content_title'),
            'htp_content_text' => $this->input->post('htp_content_text'),
            'htp_content_image' => $image,
            'htp_order' => $this->input->post('htp_order'),
            'htp_content_status' => '1',
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
        $htp_content_id = $this->input->post('htp_content_id');
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['htp_content_image']['name'] == "") {
            $image = $this->input->post('old_htp_content_image');
        } else {
            if (file_exists($this->screenshot_image . $this->input->post('old_htp_content_image'))) {
                @unlink($this->screenshot_image . $this->input->post('old_htp_content_image'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_htp_content_image'))) {
                    @unlink($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_htp_content_image'));
                }
            }
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['htp_content_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->screenshot_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('htp_content_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['htp_content_image'] = $this->upload->data();
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
            'htp_content_title' => $this->input->post('htp_content_title'),
            'htp_content_text' => $this->input->post('htp_content_text'),
            'htp_content_image' => $image,
            'htp_order' => $this->input->post('htp_order'),
            'htp_content_status' => '1',
            'date_created' => date('Y-m-d H:i:s'),
        );
        $this->db->where('htp_content_id', $htp_content_id);
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getHTPContentById($htp_content_id)
    {
        $this->db->select('*');
        $this->db->where('htp_content_id', $htp_content_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus()
    {
        $this->db->set('htp_content_status', $this->input->post('publish'));
        $this->db->where('htp_content_id', $this->input->post('htpcid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }

    }

    public function delete()
    {
        $thumb_sizes = $this->img_size_array;

        $data = $this->getHTPContentById($this->input->post('htpcid'));

        if (file_exists($this->screenshot_image . $data['htp_content_image'])) {
            @unlink($this->screenshot_image . $data['htp_content_image']);
        }
        foreach ($thumb_sizes as $width => $height) {
            if (file_exists($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $data['htp_content_image'])) {
                @unlink($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $data['htp_content_image']);
            }
        }
        $this->db->where('htp_content_id', $this->input->post('htpcid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }

    }

    public function multiDelete() {   
        $thumb_sizes = $this->img_size_array;
        foreach($this->input->post('ids') as $key => $htp_content_id){
            $data = $this->getHTPContentById($htp_content_id);
        
            if (file_exists($this->screenshot_image . $data['htp_content_image'])) {
                @unlink($this->screenshot_image . $data['htp_content_image']);
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $data['htp_content_image'])) {
                    @unlink($this->screenshot_image . "thumb/" . $width . "x" . $height . "_" . $data['htp_content_image']);
                }
            }
            $this->db->where('htp_content_id', $htp_content_id);
            $this->db->delete($this->table);            
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $htp_content_id){
            $data = $this->getHTPContentById($htp_content_id);

            if($data['htp_content_status'] == '0')
                $htp_content_status = '1';
            else
                $htp_content_status = '0';

            $this->db->set('htp_content_status', $htp_content_status);
            $this->db->where('htp_content_id', $htp_content_id);
            $this->db->update($this->table);
        }
        return true;        
    }

}
