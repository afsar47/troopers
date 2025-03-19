<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Lottery_model extends CI_Model {

    public function getAllOngoingLottery() {
        $lottery = array();
        $this->db->select("*");
        $this->db->join("image as i", "i.image_id = l.image_id", "left");
        $this->db->where("l.lottery_status", '1');
        $this->db->order_by('lottery_time', 'ASC');
        $this->db->limit(10);
        $query = $this->db->get('lottery as l');
        if ($query->num_rows() > 0) {
            $lottery = $query->result();
            $lottery_id = array();

            foreach ($lottery as $row) {
                $lottery_id[] = $row->lottery_id;
            }
            $this->db->select("*");
            $this->db->where_in("lottery_id", $lottery_id);
            $this->db->where("member_id", $this->member->front_member_id);
            $query1 = $this->db->get('lottery_member');
            $lottery_join = $query1->result();
            $i = 0;
            foreach ($lottery as $row) {
                $lottery[$i]->join_status = false;
                foreach ($lottery_join as $lottery_join_id) {
                    if ($row->lottery_id == $lottery_join_id->lottery_id) {
                        $lottery[$i]->join_status = true;
                    }
                }
                $i++;
            }
        }
        return $lottery;
    }

    public function getAllResultLottery() {
         $lottery = array();
        $this->db->select("*");
        $this->db->join("image as i", "i.image_id = l.image_id", "left");
        $this->db->where("l.lottery_status", '2');
        $this->db->order_by('lottery_time', 'ASC');
        $this->db->limit(10);
        $query = $this->db->get('lottery as l');
        if ($query->num_rows() > 0) {
            $lottery = $query->result();
            $lottery_id = array();

            foreach ($lottery as $row) {
                $lottery_id[] = $row->lottery_id;
            }
            $this->db->select("m.user_name,lm.*");
            $this->db->where_in("lm.lottery_id", $lottery_id);
            $this->db->where("lm.status", '1');
            $this->db->join("member as m", "m.member_id = lm.member_id", "left");
            $query1 = $this->db->get('lottery_member as lm');
            $lottery_join = $query1->result();
            $i = 0;
            foreach ($lottery as $row) {
                $lottery[$i]->user_name = false;
                foreach ($lottery_join as $lottery_join_id) {
                    if ($row->lottery_id == $lottery_join_id->lottery_id) {
                        $lottery[$i]->user_name = $lottery_join_id->user_name;
                    }
                }
                $i++;
            }
        }
        return $lottery;
    }

    public function getLotteryByID($lottery_id) {
        $this->db->select("*");
        $this->db->join("image as i", "i.image_id = l.image_id", "left");
        $this->db->where("l.lottery_id", $lottery_id);
        $query = $this->db->get('lottery as l');
        $lottery = $query->row_array();
        if ($query->num_rows()) {
            $lottery['join_status'] = false;
            $this->db->select("*");
            $this->db->where("lottery_id", $lottery_id);
            $this->db->where("member_id", $this->member->front_member_id);
            $query1 = $this->db->get('lottery_member');
            $lottery_join = $query1->result();

            foreach ($lottery_join as $lottery_join_id) {
                if ($lottery['lottery_id'] == $lottery_join_id->lottery_id) {
                    $lottery['join_status'] = true;
                }
            }
        }

        return $lottery;
    }

    public function checkMemberJoinOrNot($lottery_id) {
        $this->db->select("pubg_id,team,position");
        $this->db->where("lottery_id", $lottery_id);
        $this->db->where("member_id", $this->member->front_member_id);
        $query = $this->db->get('lottery_join_member');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getLotteryParticipate($lottery_id) {
        $this->db->select("m.user_name,l.status");
        $this->db->join("member as m", "m.member_id = l.member_id");
        $this->db->where("lottery_id", $lottery_id);
        $query = $this->db->get('lottery_member as l');
        return $query->result();
    }

    public function getLotteryResult($lottery_id) {
        $this->db->select("m.user_name,lm.status");
        $this->db->where("lottery_id", $lottery_id);
        $this->db->join("member as m", "lm.member_id = m.member_id", "left");
        $query = $this->db->get('lottery_member as lm');
        return $query->result();
    }

    public function getLotteryPosition($lottery_id) {
        $this->db->select("m.*,g.game_image");
        $this->db->where("m.m_id", $lottery_id);
        $this->db->join("game as g", "g.game_id = m.game_id", "left");
        $query = $this->db->get('lotteryes as m');
        $lottery = $query->row();

        $this->db->select("*");
        $this->db->where("member_id", $this->member->front_member_id);
        $query1 = $this->db->get('member');
        $member = $query1->row();

        $pubg_id = unserialize($member->pubg_id);
        $data['pubg_id'] = '';
        if (is_array($pubg_id) && array_key_exists($lottery->game_id, $pubg_id)) {
            $data['pubg_id'] = $pubg_id[$lottery->game_id];
        }

        $this->db->select("m.user_name,mj.pubg_id,mj.team,mj.position,ma.type");
        $this->db->where("mj.lottery_id", $lottery_id);
        $this->db->join("member as m", "m.member_id = mj.member_id", "left");
        $this->db->join("lotteryes as ma", "ma.m_id = mj.lottery_id", "left");
        $this->db->order_by('team', 'ASC');
        $this->db->order_by('position', 'ASC');
        $query2 = $this->db->get('lottery_join_member as mj');
        $lottery_join_member = $query2->result();

        if ($lottery->type == 'Solo') {
            for ($j = 1; $j <= $lottery->number_of_position; $j++) {
                if (count($lottery_join_member) == 0) {
                    $a = array(
                        'user_name' => '',
                        'pubg_id' => '',
                        'team' => '1',
                        'position' => $j,
                    );
                } else {
                    foreach ($lottery_join_member as $res) {
                        if ($res->position == $j) {
                            $a = array(
                                'user_name' => $res->user_name,
                                'pubg_id' => $res->pubg_id,
                                'team' => '1',
                                'position' => $j,
                            );
                            break;
                        } else {
                            $a = array(
                                'user_name' => '',
                                'pubg_id' => '',
                                'team' => '1',
                                'position' => $j,
                            );
                        }
                    }
                }
                $data['result'][] = $a;
            }
        } elseif ($lottery->type == 'Duo') {
            $loop = ceil($lottery->number_of_position / 2);
            for ($j = 1; $j <= $loop; $j++) {
                for ($i = 1; $i <= 2; $i++) {
                    if (count($lottery_join_member) == 0) {
                        $a = array(
                            'user_name' => '',
                            'pubg_id' => '',
                            'team' => $j,
                            'position' => $i,
                        );
                    } else {
                        foreach ($lottery_join_member as $res) {
                            if ($res->team == $j && $res->position == $i) {
                                $a = array(
                                    'user_name' => $res->user_name,
                                    'pubg_id' => $res->pubg_id,
                                    'team' => $res->team,
                                    'position' => $res->position,
                                );
                                break;
                            } else {
                                $a = array(
                                    'user_name' => '',
                                    'pubg_id' => '',
                                    'team' => $j,
                                    'position' => $i,
                                );
                            }
                        }
                    }
                    $data['result'][] = $a;
                }
            }
        } elseif ($lottery->type == 'Squad') {
            $loop = ceil($lottery->number_of_position / 4);
            for ($j = 1; $j <= $loop; $j++) {
                for ($i = 1; $i <= 4; $i++) {
                    if (count($lottery_join_member) == 0) {
                        $a = array(
                            'user_name' => '',
                            'pubg_id' => '',
                            'team' => $j,
                            'position' => $i,
                        );
                    } else {
                        foreach ($lottery_join_member as $res) {
                            if ($res->team == $j && $res->position == $i) {
                                $a = array(
                                    'user_name' => $res->user_name,
                                    'pubg_id' => $res->pubg_id,
                                    'team' => $res->team,
                                    'position' => $res->position,
                                );
                                break;
                            } else {
                                $a = array(
                                    'user_name' => '',
                                    'pubg_id' => '',
                                    'team' => $j,
                                    'position' => $i,
                                );
                            }
                        }
                    }
                    $data['result'][] = $a;
                }
            }
        } elseif ($lottery->type == 'Squad5') {
            $loop = ceil($lottery->number_of_position / 5);
            for ($j = 1; $j <= $loop; $j++) {
                for ($i = 1; $i <= 5; $i++) {
                    if (count($lottery_join_member) == 0) {
                        $a = array(
                            'user_name' => '',
                            'pubg_id' => '',
                            'team' => $j,
                            'position' => $i,
                        );
                    } else {
                        foreach ($lottery_join_member as $res) {
                            if ($res->team == $j && $res->position == $i) {
                                $a = array(
                                    'user_name' => $res->user_name,
                                    'pubg_id' => $res->pubg_id,
                                    'team' => $res->team,
                                    'position' => $res->position,
                                );
                                break;
                            } else {
                                $a = array(
                                    'user_name' => '',
                                    'pubg_id' => '',
                                    'team' => $j,
                                    'position' => $i,
                                );
                            }
                        }
                    }
                    $data['result'][] = $a;
                }
            }
        }
        return $data;
    }

}
