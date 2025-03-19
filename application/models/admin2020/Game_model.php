<?php

class Game_model extends CI_Model {

    public function __construct() {
        parent::__construct();       
        $this->table = 'game';
        $this->table_game_map = 'game_map';
        $this->img_size_array = array(100 => 100, 1000 => 500);
        $this->logo_size_array = array(100 => 100);
//        $this->column_headers = array(
//            'Game Name' => '',
//            'Image' => '',
//        );
    }

    public function get_list_count_game() {
        $this->db->select('*');
        $this->db->order_by("game_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function game_data() {
        $this->db->select('*');
        $this->db->order_by("game_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function insert() {
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['game_image']['name'] == "") {
            $image = '';
        } else {
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['game_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->game_image;
            $config['allowed_types'] = 'jpg|png|jpeg';

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('game_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->game_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->game_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->game_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->game_image . $image, $this->game_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
        }
        $logo_thumb_sizes = $this->logo_size_array;
        if ($_FILES['game_logo']['name'] == "") {
            $image_logo = '';
        } else {
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image_logo = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['game_logo']['name']);
            $config['file_name'] = $image_logo;
            $config['upload_path'] = $this->game_logo_image;
            $config['allowed_types'] = 'jpg|png|jpeg';

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('game_logo')) {
                $data['error'] = array('error' => $this->upload->display_errors());                
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($logo_thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->game_logo_image . $image_logo);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->game_logo_image . $image_logo);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->game_logo_image . "thumb/" . $key . "x" . $val . "_" . $image_logo);
                    } else {
                        copy($this->game_logo_image . $image_logo, $this->game_logo_image . "thumb/" . $key . "x" . $val . "_" . $image_logo);
                    }
                }
            }
        }
        $data = array(
            'game_name' => $this->input->post('game_name'),
            'package_name' => $this->input->post('package_name'),
            'game_image' => $image,
            'game_rules' => $this->input->post('game_rules'),
            'game_logo' => $image_logo,
			'game_type' => $this->input->post('game_type'),
			'id_prefix' => $this->input->post('id_prefix'),
            'follower' => '[]',
            // 'banned' => $this->input->post('banned'),
            'date_created' => date('Y-m-d H:i:s')
        );
        if ($this->db->insert('game', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['game_image']['name'] == "") {
            $image = $this->input->post('old_game_image');
        } else {
            if (file_exists($this->game_image . $this->input->post('old_game_image'))) {
                @unlink($this->game_image . $this->input->post('old_game_image'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->game_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_game_image'))) {
                    @unlink($this->game_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_game_image'));
                }
            }
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['game_image']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->game_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('game_image')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->game_image . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->game_image . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->game_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->game_image . $image, $this->game_image . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
        }
        $logo_thumb_sizes = $this->logo_size_array;
        if ($_FILES['game_logo']['name'] == "") {
            $image_logo = $this->input->post('old_game_logo');
        } else {
            if (file_exists($this->game_logo_image . $this->input->post('old_game_logo'))) {
                @unlink($this->game_logo_image . $this->input->post('old_game_logo'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->game_logo_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_game_logo'))) {
                    @unlink($this->game_logo_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_game_logo'));
                }
            }
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image_logo = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['game_logo']['name']);
            $config['file_name'] = $image_logo;
            $config['upload_path'] = $this->game_logo_image;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('game_logo')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['upload_data'] = $this->upload->data();
                foreach ($logo_thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->game_logo_image . $image_logo);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->game_logo_image . $image_logo);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->game_logo_image . "thumb/" . $key . "x" . $val . "_" . $image_logo);
                    } else {
                        copy($this->game_logo_image . $image_logo, $this->game_logo_image . "thumb/" . $key . "x" . $val . "_" . $image_logo);
                    }
                }
            }
        }
        $data = array(
            'game_name' => $this->input->post('game_name'),
            'package_name' => $this->input->post('package_name'),
            'game_image' => $image,
            'game_rules' => $this->input->post('game_rules'),
			'game_type' => $this->input->post('game_type'),
			'id_prefix' => $this->input->post('id_prefix'),
            'game_logo' => $image_logo,
            'follower' => '[]',
            // 'banned' => $this->input->post('banned'),
        );
        $this->db->where('game_id', $this->input->post('game_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getgameById($game_id) {
        $this->db->select('*');
        $this->db->where('game_id', $game_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus() {
        $this->db->set('status', $this->input->post('publish'));
        $this->db->where('game_id', $this->input->post('gameid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $thumb_sizes = $this->img_size_array;
       
        $data = $this->getgameById($this->input->post('gameid')); 

        if (file_exists($this->game_image . $data['game_image'])) {
            @unlink($this->game_image . $data['game_image']);
        }
        foreach ($thumb_sizes as $width => $height) {
            if (file_exists($this->game_image . "thumb/" . $width . "x" . $height . "_" . $data['game_image'])) {
                @unlink($this->game_image . "thumb/" . $width . "x" . $height . "_" . $data['game_image']);
            }
        }
        $this->db->where('game_id', $this->input->post('gameid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        $thumb_sizes = $this->img_size_array;

        foreach($this->input->post('ids') as $key => $game_id){
            $data = $this->getgameById($game_id); 
               
            if (file_exists($this->game_image . $data['game_image'])) {
                @unlink($this->game_image . $data['game_image']);
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->game_image . "thumb/" . $width . "x" . $height . "_" . $data['game_image'])) {
                    @unlink($this->game_image . "thumb/" . $width . "x" . $height . "_" . $data['game_image']);
                }
            }
            $this->db->where('game_id', $game_id);
            $this->db->delete($this->table);            
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $game_id){
            $game_data = $this->getgameById($game_id);

            if($game_data['status'] == '0')
                $status = '1';
            else
                $status = '0';

            $this->db->set('status', $status);
            $this->db->where('game_id', $game_id);
            $this->db->update($this->table);
        }
        return true;        
    }

}
