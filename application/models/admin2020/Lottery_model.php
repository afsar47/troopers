<?php

class Lottery_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'lottery';
        $this->table_lottery_map = 'lottery_map';
        $this->img_size_array = array(100 => 100, 1000 => 500);
        $this->column_headers = array(
            'Title' => '',
            'Time' => '',
            'Fees (' . $this->functions->getPoint() . ')' => '',
            'Prize (' . $this->functions->getPoint() . ')' => '',
            'Size' => '',
            'Total Joined' => '',
            'Date' => '',
            'Actions' => '',
        );
        $this->member_column_headers = array(
            'User Name' => '',
            'Lottery number' => '',
            'Status' => '',
            'Date' => '',
        );
    }

    public function get_list_count_lottery() {
        $this->db->select('*');
        $this->db->order_by("lottery_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function get_list_count_lotteryMember($lottery_id) {
        $this->db->select('*');
        $this->db->where("lottery_id", $lottery_id);
        $this->db->order_by("lottery_id", "Desc");
        $query = $this->db->get('lottery_member');
        return $query->num_rows();
    }

    public function lottery_data() {
        $this->db->select('*');
        $this->db->order_by("lottery_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function insert() {
        $thumb_sizes = $this->img_size_array;
        $image = '';
        if ($this->input->post('image_id') == 0) {
            if ($_FILES['lottery_image']['name'] == "") {
                $image = '';
            } else {
                $unique = $this->functions->GenerateUniqueFilePrefix();
                $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['lottery_image']['name']);
                $config['file_name'] = $image;
                $config['upload_path'] = $this->lottery_image;
                $config['allowed_types'] = 'jpg|png|jpeg';

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('lottery_image')) {
                    $data['error'] = array('error' => $this->upload->display_errors());
                } else {
                    $data['upload_data'] = $this->upload->data();
                    foreach ($thumb_sizes as $key => $val) {
                        list($width_orig, $height_orig, $image_type) = getimagesize($this->lottery_image . $image);				                                                
                                                            
                        if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                            $this->image->initialize($this->lottery_image . $image);                                                       
                            $this->image->resize($key, $val);
                            $this->image->save($this->lottery_image . "thumb/" . $key . "x" . $val . "_" . $image);
                        } else {
                            copy($this->lottery_image . $image, $this->lottery_image . "thumb/" . $key . "x" . $val . "_" . $image);
                        }
                    }
                }
            }
        }
        $data = array(
            'lottery_title' => $this->input->post('lottery_title'),
            'lottery_image' => $image,
            'lottery_time' => $this->input->post('lottery_time'),
            'lottery_rules' => $this->input->post('lottery_rules'),
            'lottery_fees' => $this->input->post('lottery_fees'),
            'lottery_prize' => $this->input->post('lottery_prize'),
            'lottery_size' => $this->input->post('lottery_size'),
            'image_id' => $this->input->post('image_id'),
            'date_created' => date('Y-m-d H:i:s')
        );
        if ($this->db->insert('lottery', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $thumb_sizes = $this->img_size_array;
        if ($this->input->post('image_id') == 0) {
            if ($_FILES['lottery_image']['name'] == "") {
                $image = $this->input->post('old_lottery_image');
            } else {
                if (file_exists($this->lottery_image . $this->input->post('old_lottery_image'))) {
                    @unlink($this->lottery_image . $this->input->post('old_lottery_image'));
                }
                foreach ($thumb_sizes as $width => $height) {
                    if (file_exists($this->lottery_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_lottery_image'))) {
                        @unlink($this->lottery_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_lottery_image'));
                    }
                }
                $unique = $this->functions->GenerateUniqueFilePrefix();
                $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['lottery_image']['name']);
                $config['file_name'] = $image;
                $config['upload_path'] = $this->lottery_image;
                $config['allowed_types'] = 'jpg|png|jpeg';
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('lottery_image')) {
                    $data['error'] = array('error' => $this->upload->display_errors());
                } else {
                    $data['upload_data'] = $this->upload->data();
                    foreach ($thumb_sizes as $key => $val) {
                        list($width_orig, $height_orig, $image_type) = getimagesize($this->lottery_image . $image);				                                                
                                                            
                        if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                            $this->image->initialize($this->lottery_image . $image);                                                       
                            $this->image->resize($key, $val);
                            $this->image->save($this->lottery_image . "thumb/" . $key . "x" . $val . "_" . $image);
                        } else {
                            copy($this->lottery_image . $image, $this->lottery_image . "thumb/" . $key . "x" . $val . "_" . $image);
                        }
                    }
                }
            }
        } else {
            $image = '';
            if (file_exists($this->lottery_image . $this->input->post('old_lottery_image'))) {
                @unlink($this->lottery_image . $this->input->post('old_lottery_image'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->lottery_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_lottery_image'))) {
                    @unlink($this->lottery_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_lottery_image'));
                }
            }
        }
        $data = array(
            'lottery_title' => $this->input->post('lottery_title'),
            'lottery_image' => $image,
            'lottery_time' => $this->input->post('lottery_time'),
            'lottery_rules' => $this->input->post('lottery_rules'),
            'lottery_fees' => $this->input->post('lottery_fees'),
            'lottery_prize' => $this->input->post('lottery_prize'),
            'lottery_size' => $this->input->post('lottery_size'),
            'image_id' => $this->input->post('image_id'),
        );
        $this->db->where('lottery_id', $this->input->post('lottery_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getlotteryById($lottery_id) {
        $this->db->select('*');
        $this->db->where('lottery_id', $lottery_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function changePublishStatus() {
        $this->db->set('lottery_status', $this->input->post('publish'));
        $this->db->where('lottery_id', $this->input->post('lotteryid'));
        if ($query = $this->db->update($this->table)) {
//            if ($this->input->post('publish') == '2') {
//                $lottery = $this->getlotteryById($this->input->post('lotteryid'));
//
//                $this->db->select('GROUP_CONCAT(member_id) as member_id');
//                $this->db->where('lottery_id', $this->input->post('lotteryid'));
//                $query = $this->db->get('lottery_member');
//                $res = explode(',', $query->row_array()['member_id']);
//                $rand_member_id = $res[array_rand($res, 1)];
//
//                $this->db->select('*');
//                $this->db->where('member_id', $rand_member_id);
//                $member = $this->db->get('member')->row();
//                $wallet_balance = $member->wallet_balance + $lottery['lottery_prize'];
//                $accountstm_data = array(
//                    'member_id' => $rand_member_id,
//                    'deposit' => $lottery['lottery_prize'],
//                    'withdraw' => 0,
//                    'join_money' => $member->join_money,
//                    'win_money' => $wallet_balance,
//                    'lottery_id' => $this->input->post('lotteryid'),
//                    'note' => 'Lottery Reward',
//                    'note_id' => '11',
//                );
//                $this->db->insert('accountstatement', $accountstm_data);
//                $member_data = array(
//                    'wallet_balance' => $wallet_balance,
//                );
//                $this->db->where('member_id', $rand_member_id);
//                $this->db->update('member', $member_data);
//
//                $lottery_member = array(
//                    'status' => 'Winner',
//                );
//                $this->db->where('lottery_id', $this->input->post('lotteryid'));
//                $this->db->where('member_id', $rand_member_id);
//                $this->db->update('lottery_member', $lottery_member);
//            }
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $thumb_sizes = $this->img_size_array;
        
        $data = $this->getlotteryById($this->input->post('lotteryid'));

        if (file_exists($this->lottery_image . $data['lottery_image'])) {
            @unlink($this->lottery_image . $data['lottery_image']);
        }
        foreach ($thumb_sizes as $width => $height) {
            if (file_exists($this->lottery_image . "thumb/" . $width . "x" . $height . "_" . $data['lottery_image'])) {
                @unlink($this->lottery_image . "thumb/" . $width . "x" . $height . "_" . $data['lottery_image']);
            }
        }
        $this->db->where('lottery_id', $this->input->post('lotteryid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        $thumb_sizes = $this->img_size_array;
        foreach($this->input->post('ids') as $key => $lottery_id){
            $data = $this->getlotteryById($lottery_id);

            if (file_exists($this->lottery_image . $data['lottery_image'])) {
                @unlink($this->lottery_image . $data['lottery_image']);
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->lottery_image . "thumb/" . $width . "x" . $height . "_" . $data['lottery_image'])) {
                    @unlink($this->lottery_image . "thumb/" . $width . "x" . $height . "_" . $data['lottery_image']);
                }
            }
            $this->db->where('lottery_id', $lottery_id);
            $this->db->delete($this->table);
            
        }     
        
        return true;        
    }

}
