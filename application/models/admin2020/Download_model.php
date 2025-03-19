<?php

class Download_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();              
        $this->img_size_array = array(100 => 100, 336 => 600);
        $this->table = 'download';
    }

    public function get_list_count_download()
    {
        $this->db->select('*');
        $this->db->order_by("download_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function insert()
    {
        $thumb_sizes = $this->img_size_array;
        $gallery_title = $this->input->post('download_image');
        if ($_FILES['download_image']['name'] == "") {
            $image = $this->input->post('old_download_image');
        } else {
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['download_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->download_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('download_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {                
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->download_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                           
                        $this->image->initialize($this->download_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->download_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->download_image . $image, $this->download_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } 
                }
            }
            $data = array(
                'download_image' => $image,
                'dp_order' => $this->input->post('dp_order'),
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

    public function update()
    {
        $thumb_sizes = $this->img_size_array;
        $download_id = $this->input->post('download_id');
        if ($_FILES['download_image']['name'] == "") {
            $image = $this->input->post('old_download_image');
        } else {
            if (file_exists($this->download_image . $this->input->post('old_download_image'))) {
                @unlink($this->download_image . $this->input->post('old_download_image'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->download_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_download_image'))) {
                    @unlink($this->download_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_download_image'));
                }
            }
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['download_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->download_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('download_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->download_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->download_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->download_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->download_image . $image, $this->download_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
        }
        $data = array(
            'download_image' => $image,
            'dp_order' => $this->input->post('dp_order'),
        );
        $this->db->where('download_id', $download_id);
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getDownloadById($download_id)
    {
        $this->db->select('*');
        $this->db->where('download_id', $download_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus()
    {
        $this->db->set('status', $this->input->post('publish'));
        $this->db->where('download_id', $this->input->post('downloadid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }

    }

    public function delete()
    {
        $thumb_sizes = $this->img_size_array;        
        $data = $this->getDownloadById($this->input->post('downloadid'));
        if (file_exists($this->download_image . $data['download_image'])) {
            @unlink($this->download_image . $data['download_image']);
        }
        foreach ($thumb_sizes as $width => $height) {
            if (file_exists($this->download_image . "thumb/" . $width . "x" . $height . "_" . $data['download_image'])) {
                @unlink($this->download_image . "thumb/" . $width . "x" . $height . "_" . $data['download_image']);
            }
        }
        $this->db->where('download_id', $this->input->post('downloadid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        $thumb_sizes = $this->img_size_array;        
        foreach($this->input->post('ids') as $key => $download_id){
            $data = $this->getDownloadById($download_id);
            if (file_exists($this->download_image . $data['download_image'])) {
                @unlink($this->download_image . $data['download_image']);
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->download_image . "thumb/" . $width . "x" . $height . "_" . $data['download_image'])) {
                    @unlink($this->download_image . "thumb/" . $width . "x" . $height . "_" . $data['download_image']);
                }
            }
            $this->db->where('download_id', $download_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $download_id){
            $data = $this->getDownloadById($download_id);

            if($data['status'] == '0')
                $status = '1';
            else
                $status = '0';

            $this->db->set('status', $status);
            $this->db->where('download_id', $download_id);
            $this->db->update($this->table);
        }
        return true;        
    }
}
