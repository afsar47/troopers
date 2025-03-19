<?php

class Match_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'matches';
        $this->table_game = 'game';
        $this->img_size_array = array(100 => 100, 1000 => 500);
//        $this->column_headers = array(
//            'Match ID' => '',
//            'Game Name' => '',
//            'Match Name' => '',
//            'Room ID/Pass' => '',
//            'Match Time' => '',
//            'Total Player' => '',
//            'Total Player Joined' => '',
//            'Win Prize (' . $this->functions->getPoint() . ')' => '',
//            'Entry Fee (' . $this->functions->getPoint() . ')' => '',
//            'Match Type' => '',
//            'Match Satus' => '',
//        );
//        $this->member_join_column_headers = array(
//            'First Name' => '',
//            'Last Name' => '',
//            'User Name' => '',
//            'Kill' => '',
//            'Winning' => '',
//        );
    }

    public function getgame() {
        $this->db->select('*');
        $this->db->where('status', '1');
        $this->db->where('game_type', '0');
        $query = $this->db->get($this->table_game);
        return $query->result();
    }

    public function getImage() {
        $this->db->select('*');
        $query = $this->db->get('image');
        return $query->result();
    }

    public function get_list_count_match() {
        $this->db->select('*');
        $this->db->order_by("m_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function get_list_count_match_join_member($m_id) {
        $this->db->select('*');
        $this->db->where('match_id', $m_id);
        $this->db->join('member as m', 'm.member_id = mj.member_id');
        $query = $this->db->get('match_join_member as mj');
        return $query->num_rows();
    }

    public function insert() {
        $thumb_sizes = $this->img_size_array;
        $image = '';
        if ($this->input->post('image_id') == 0) {
            if ($_FILES['match_banner']['name'] == "") {
                $image = '';
            } else {
                $unique = $this->functions->GenerateUniqueFilePrefix();
                $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['match_banner']['name']);
                $config['file_name'] = $image;
                $config['upload_path'] = $this->match_banner_image;
                $config['allowed_types'] = 'jpg|png|jpeg';
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('match_banner')) {
                    $data['error'] = array('error' => $this->upload->display_errors());
                } else {
                    $data['upload_data'] = $this->upload->data();
                    foreach ($thumb_sizes as $key => $val) {
                        list($width_orig, $height_orig, $image_type) = getimagesize($this->match_banner_image . $image);				                                                
                                                            
                        if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                            $this->image->initialize($this->match_banner_image . $image);                                                       
                            $this->image->resize($key, $val);
                            $this->image->save($this->match_banner_image . "thumb/" . $key . "x" . $val . "_" . $image);
                        } else {
                            copy($this->match_banner_image . $image, $this->match_banner_image . "thumb/" . $key . "x" . $val . "_" . $image);
                        }
                    }
                }
            }
        }
        $data = array(
            'match_name' => $this->input->post('match_name'),
            'match_time' => $this->input->post('match_time'),
            'win_prize' => $this->input->post('win_prize'),
            'prize_description' => $this->input->post('prize_description'),
            'per_kill' => $this->input->post('per_kill'),
            'entry_fee' => $this->input->post('entry_fee'),
            'type' => $this->input->post('type'),
            'MAP' => $this->input->post('MAP'),
            'game_id' => $this->input->post('game_id'),
            'match_type' => $this->input->post('match_type'),
            'match_desc' => $this->input->post('match_desc'),
            'match_private_desc' => $this->input->post('match_private_desc'),
            'match_url' => $this->input->post('match_url'),
            'number_of_position' => $this->input->post('number_of_position'),
            'match_banner' => $image,
            'match_sponsor' => $this->input->post('match_sponsor'),
            'image_id' => $this->input->post('image_id'),
            'date_created' => date('Y-m-d H:i:s')
        );
        if ($result = $this->db->insert('matches', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $thumb_sizes = $this->img_size_array;
        if ($this->input->post('image_id') == 0) {
            if ($_FILES['match_banner']['name'] == "") {
                $image = $this->input->post('old_match_banner');
            } else {
                if (file_exists($this->match_banner_image . $this->input->post('old_match_banner'))) {
                    @unlink($this->match_banner_image . $this->input->post('old_match_banner'));
                }
                foreach ($thumb_sizes as $width => $height) {

                    if (file_exists($this->match_banner_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_match_banner'))) {
                        @unlink($this->match_banner_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_match_banner'));
                    }
                }
                $unique = $this->functions->GenerateUniqueFilePrefix();
                $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['match_banner']['name']);
                $config['file_name'] = $image;
                $config['upload_path'] = $this->match_banner_image;
                $config['allowed_types'] = 'jpg|png|jpeg';
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('match_banner')) {
                    $data['error'] = array('error' => $this->upload->display_errors());
                } else {
                    $data['upload_data'] = $this->upload->data();
                    foreach ($thumb_sizes as $key => $val) {
                        list($width_orig, $height_orig, $image_type) = getimagesize($this->match_banner_image . $image);				                                                
                                                            
                        if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                            $this->image->initialize($this->match_banner_image . $image);                                                       
                            $this->image->resize($key, $val);
                            $this->image->save($this->match_banner_image . "thumb/" . $key . "x" . $val . "_" . $image);
                        } else {
                            copy($this->match_banner_image . $image, $this->match_banner_image . "thumb/" . $key . "x" . $val . "_" . $image);
                        }
                    }
                }
            }
        } else {
            $image = '';
            if (file_exists($this->match_banner_image . $this->input->post('old_match_banner'))) {
                @unlink($this->match_banner_image . $this->input->post('old_match_banner'));
            }
            foreach ($thumb_sizes as $width => $height) {

                if (file_exists($this->match_banner_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_match_banner'))) {
                    @unlink($this->match_banner_image . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_match_banner'));
                }
            }
        }
        $data = array(
            'match_name' => $this->input->post('match_name'),
            'match_time' => $this->input->post('match_time'),
            'win_prize' => $this->input->post('win_prize'),
            'prize_description' => $this->input->post('prize_description'),
            'per_kill' => $this->input->post('per_kill'),
            'entry_fee' => $this->input->post('entry_fee'),
            'type' => $this->input->post('type'),
            'MAP' => $this->input->post('MAP'),
            'game_id' => $this->input->post('game_id'),
            'match_type' => $this->input->post('match_type'),
            'match_desc' => $this->input->post('match_desc'),
            'match_private_desc' => $this->input->post('match_private_desc'),
            'match_url' => $this->input->post('match_url'),
            'number_of_position' => $this->input->post('number_of_position'),
            'match_banner' => $image,
            'match_sponsor' => $this->input->post('match_sponsor'),
            'image_id' => $this->input->post('image_id'),
        );
        $this->db->where('m_id', $this->input->post('m_id'));
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_only_id() {
        $data = array(
            'room_description' => $this->input->post('room_description'),            
        );
        $this->db->where('m_id', $this->input->post('m_id'));
        if ($this->db->update($this->table, $data)) {            
            return true;
        } else {
            return false;
        }
    }

    public function getmatchById($m_id) {
        $this->db->select('*');
        $this->db->where('m_id', $m_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function getmatchById_alldata($m_id) {
        $this->db->select('m.*,m.MAP as map_name');
        $this->db->where('m_id', $m_id);
        $query = $this->db->get($this->table . ' as m');
        return $query->row_array();
    }

    public function member_position($m_id) {
        $this->db->select('mj.position,m.user_name,mj.pubg_id,mj.team,mj.match_join_member_id');
        $this->db->where('mj.match_id', $m_id);
        $this->db->join('member as m', 'm.member_id = mj.member_id');
        $this->db->join('matches as ma', 'ma.m_id = mj.match_id');
        $query = $this->db->get('match_join_member as mj');
        return $query->result();
    }

    public function match_type($m_id) {
        $this->db->select('type,number_of_position');
        $this->db->where('m_id', $m_id);
        $query = $this->db->get('matches');
        return $query->row_array();
    }

    public function getmember_join_match($m_id) {
        $this->db->select('mj.*,ma.per_kill,m.*,ma.m_id,ma.entry_fee,mj.pubg_id');
        $this->db->where('mj.match_id', $m_id);
        $this->db->join('member as m', 'm.member_id = mj.member_id');
        $this->db->join('matches as ma', 'ma.m_id = mj.match_id');
        $query = $this->db->get('match_join_member as mj');
        return $query->result();
    }

    public function changePublishStatus() {
        
        $this->db->where('m_id',$this->input->post('mid'));
        $match_current_data = $this->db->get($this->table)->row();

        $this->db->set('match_status', $this->input->post('publish'));
        $this->db->where('m_id', $this->input->post('mid'));
        if ($query = $this->db->update($this->table)) {            

            if($match_current_data->match_type == '1' && $this->input->post('publish') == '4') {
                
                $this->load->library('user_agent');
                $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                $ip = $this->input->ip_address();

                $this->db->select('mj.*,ma.entry_fee');
                $this->db->where('mj.match_id', $this->input->post('mid'));
                $this->db->join('matches as ma', 'ma.m_id = mj.match_id');
                $mem_detail = $this->db->get('match_join_member as mj')->result_array();
                                
                foreach($mem_detail as $mem) {

                    $this->db->select('*');
                    $this->db->where("member_id", $mem['member_id']);
                    $this->db->where("match_id", $this->input->post('mid'));
                    $this->db->where("pubg_id", $mem['pubg_id']);
                    $this->db->where('note_id', '6');
                    $query_acc = $this->db->get('accountstatement');

                    $result_acc = $query_acc->row_array();
                    if ($query_acc->num_rows() > 0) {
                        
                        $this->db->select('*');
                        $this->db->where("member_id", $mem['member_id']);
                        $member_record = $this->db->get('member')->row_array();
                                           
                        $join_money = $member_record['join_money'] - $mem['refund'] + $mem['entry_fee'];

                        $accountstm_data = array(
                            'deposit' => $mem['entry_fee'],
                            'join_money' => $join_money,
                        );
                        $this->db->where('member_id', $mem['member_id']);
                        $this->db->where('match_id', $this->input->post('mid'));
                        $this->db->where('pubg_id',$mem['pubg_id']);
                        $this->db->where('note_id', '6');
                        $this->db->where('note', 'Refund');
                        $this->db->update('accountstatement', $accountstm_data);

                        $member_data = array(
                            'join_money' => $join_money,
                        );
                        $this->db->where('member_id', $mem['member_id']);
                        $this->db->update('member', $member_data);
                    } else {                        
                        
                        $this->db->select('*');
                        $this->db->where("member_id", $mem['member_id']);
                        $member_record = $this->db->get('member')->row_array();
                        
                        $join_money = $member_record['join_money'] + $mem['entry_fee'];
                        
                        $member_data = array(
                            'join_money' => $join_money,
                        );

                        $this->db->where('member_id', $mem['member_id']);
                        $this->db->update('member', $member_data);
                        
                        $acc_data = array(
                            'member_id' => $mem['member_id'],
                            'pubg_id' => $mem['pubg_id'],
                            'match_id' => $this->input->post('mid'),
                            'deposit' => $mem['entry_fee'],
                            'withdraw' => 0,
                            'join_money' => $join_money,
                            'win_money' => $member_record['wallet_balance'],
                            'note' => 'Refund on Match Cancel',
                            'note_id' => '6',
                            'entry_from' => '3',
                            'ip_detail' => $ip,
                            'browser' => $browser,
                            'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('accountstatement', $acc_data);
                    }    
                    
                        $data_refund['refund'] = $mem['entry_fee'];                    
                    
                        $this->db->where('match_join_member_id', $mem['match_join_member_id']);
                        $this->db->update('match_join_member', $data_refund);
                }

            }
            return true;
        } else {
            return false;
        }
    }

    public function changePinStatus() {
        $this->db->set('pin_match', $this->input->post('publish'));
        $this->db->where('m_id', $this->input->post('mid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $thumb_sizes = $this->img_size_array;
        
        $data = $this->getmatchById($this->input->post('mid'));
        if (file_exists($this->match_banner_image . $data['match_banner'])) {
            @unlink($this->match_banner_image . $data['match_banner']);
        }
        foreach ($thumb_sizes as $width => $height) {
            if (file_exists($this->match_banner_image . "thumb/" . $width . "x" . $height . "_" . $data['match_banner'])) {
                @unlink($this->match_banner_image . "thumb/" . $width . "x" . $height . "_" . $data['match_banner']);
            }
        }
        $this->db->where('m_id', $this->input->post('mid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        $thumb_sizes = $this->img_size_array;
        foreach($this->input->post('ids') as $key => $m_id){
            $data = $this->getmatchById($m_id);
            if (file_exists($this->match_banner_image . $data['match_banner'])) {
                @unlink($this->match_banner_image . $data['match_banner']);
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->match_banner_image . "thumb/" . $width . "x" . $height . "_" . $data['match_banner'])) {
                    @unlink($this->match_banner_image . "thumb/" . $width . "x" . $height . "_" . $data['match_banner']);
                }
            }
            $this->db->where('m_id', $m_id);
            $this->db->delete($this->table);           
        }     
        
        return true;        
    }    
    
    public function get_result_notification($id) {
        $this->db->select('result_notification');
        $this->db->from('matches');
        $this->db->where('m_id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function result_notification($data) {
        $this->db->where('m_id', $data['match_id']);
        $this->db->update('matches', [
            'result_notification' => $data['result_notification'],
        ]);
        return true;
    }

}
