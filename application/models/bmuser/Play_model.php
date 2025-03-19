<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Play_model extends CI_Model {

    public function getAllGame() {
        $this->db->select("*,(select count(*) from matches as m where m.game_id = game.game_id and m.match_status = '1') as total_upcoming_match");
        $this->db->where("status", '1');
        $this->db->where("game_type", '0');
        $this->db->order_by('game_id', 'DESC');
        $query = $this->db->get('game');
        return $query->result();
    }

    public function getAllSlider() {
        $this->db->select("*");
        $this->db->where('status', '1');
        $this->db->order_by('date_created', 'DESC');
        $query = $this->db->get('slider');
        return $query->result();
    }

    public function getAllAnnouncement() {
        $this->db->select("*");
        $this->db->order_by('announcement_id', 'DESC');
        $query = $this->db->get('announcement');
        return $query->result();
    }

    public function getGamByID($game_id) {
        $this->db->select("*");
        $this->db->where("game_id", $game_id);
        $query = $this->db->get('game');
        return $query->row_array();
    }

    public function getAllOngoingMatch($game_id) {
        $mathes = array();
        $this->db->select("*,STR_TO_DATE(match_time, '%d/%m/%Y %h:%i %p') as m_time");
        $this->db->join("image as i", "i.image_id = matches.image_id", "left");
        $this->db->where("match_status", '3');
        $this->db->where("game_id", $game_id);
        $this->db->order_by('m_time', 'DESC');
        $query = $this->db->get('matches');
        if ($query->num_rows() > 0) {
            $mathes = $query->result();
            $match_id = array();

            foreach ($mathes as $row) {
                $match_id[] = $row->m_id;
            }
            $this->db->select("*");
            $this->db->where_in("match_id", $match_id);
            $this->db->where("member_id", $this->member->front_member_id);
            $query1 = $this->db->get('match_join_member');
            $match_join = $query1->result();
            $i = 0;
            foreach ($mathes as $row) {
                $mathes[$i]->join_status = false;
                foreach ($match_join as $match_join_id) {
                    if ($row->m_id == $match_join_id->match_id) {
                        $mathes[$i]->join_status = true;
                    }
                }
                $i++;
            }
        }
        return $mathes;
    }

    public function getAllUpcomingMatch($game_id) {
        $mathes = array();
        $this->db->select("*,STR_TO_DATE(match_time, '%d/%m/%Y %h:%i %p') as m_time");
        $this->db->join("image as i", "i.image_id = m.image_id", "left");
        $this->db->where("m.match_status", '1');
        $this->db->where("m.game_id", $game_id);
        $this->db->order_by('pin_match', 'DESC');
        $this->db->order_by('m_time', 'ASC');
        $this->db->limit(10);
        $query = $this->db->get('matches as m');
        if ($query->num_rows() > 0) {
            $mathes = $query->result();
            $match_id = array();

            foreach ($mathes as $row) {
                $match_id[] = $row->m_id;
            }
            $this->db->select("*");
            $this->db->where_in("match_id", $match_id);
            $this->db->where("member_id", $this->member->front_member_id);
            $query1 = $this->db->get('match_join_member');
            $match_join = $query1->result();
            $i = 0;
            foreach ($mathes as $row) {
                $mathes[$i]->join_status = false;
                foreach ($match_join as $match_join_id) {
                    if ($row->m_id == $match_join_id->match_id) {
                        $mathes[$i]->join_status = true;
                    }
                }
                $i++;
            }
        }
        return $mathes;
    }

    public function getAllResultMatch($game_id) {
        $mathes = array();
        $this->db->select("*,STR_TO_DATE(match_time, '%d/%m/%Y %h:%i %p') as m_time");
        $this->db->join("image as i", "i.image_id = matches.image_id", "left");
        $this->db->where("match_status", '2');
        $this->db->where("game_id", $game_id);
        $this->db->order_by('m_time', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get('matches');
        if ($query->num_rows() > 0) {
            $mathes = $query->result();
            $match_id = array();

            foreach ($mathes as $row) {
                $match_id[] = $row->m_id;
            }
            $this->db->select("*");
            $this->db->where_in("match_id", $match_id);
            $this->db->where("member_id", $this->member->front_member_id);
            $query1 = $this->db->get('match_join_member');
            $match_join = $query1->result();
            $i = 0;
            foreach ($mathes as $row) {
                $mathes[$i]->join_status = false;
                foreach ($match_join as $match_join_id) {
                    if ($row->m_id == $match_join_id->match_id) {
                        $mathes[$i]->join_status = true;
                    }
                }
                $i++;
            }
        }
        return $mathes;
    }

    public function getMyMatch($status) {
        $mathes = array();
        $this->db->select("m.*,STR_TO_DATE(m.match_time, '%d/%m/%Y %h:%i %p') as m_time,i.*,g.game_image");
        $this->db->order_by('m_time', 'DESC');
        $this->db->group_by('m.m_id', 'DESC');
        $this->db->join("matches as m", "mj.match_id = m.m_id");
        $this->db->join("game as g", "g.game_id = m.game_id");
        $this->db->join("image as i", "i.image_id = m.image_id", "left");
        if ($status == 'ongoing')
            $this->db->where("m.match_status", "3");
        if ($status == 'upcoming')
            $this->db->where("m.match_status", "1");
        if ($status == 'result')
            $this->db->where("m.match_status", "2");
        $this->db->where("mj.member_id", $this->member->front_member_id);
        $query = $this->db->get('match_join_member as mj');
        return $query->result();
    }

    public function getMatchByID($match_id) {
        $this->db->select("m.*,i.*,g.game_image,g.game_name");
        $this->db->join("image as i", "i.image_id = m.image_id", "left");
        $this->db->where("m.m_id", $match_id);
        $this->db->join("game as g", "g.game_id = m.game_id", "left");
        $query = $this->db->get('matches as m');
        $match = $query->row_array();
        if ($query->num_rows()) {
            $match['join_status'] = false;
            $this->db->select("*");
            $this->db->where("match_id", $match_id);
            $this->db->where("member_id", $this->member->front_member_id);
            $query1 = $this->db->get('match_join_member');
            $match_join = $query1->result();

            foreach ($match_join as $match_join_id) {
                if ($match['m_id'] == $match_join_id->match_id) {
                    $match['join_status'] = true;
                }
            }
        }

        return $match;
    }

    public function checkMemberJoinOrNot($match_id) {
        $this->db->select("pubg_id,team,position");
        $this->db->where("match_id", $match_id);
        $this->db->where("member_id", $this->member->front_member_id);
        $query = $this->db->get('match_join_member');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function JoinMemberDetail($match_id) {
        $this->db->select("pubg_id,team,position,match_join_member_id");
        $this->db->where("match_id", $match_id);
        $this->db->where("member_id", $this->member->front_member_id);
        $query = $this->db->get('match_join_member');
        return $query->result();
    }

    public function getMatchParticipate($match_id) {
        $this->db->select("pubg_id,team,position");
        $this->db->where("match_id", $match_id);
        $query = $this->db->get('match_join_member');
        return $query->result();
    }

    public function getMatchResult($match_id, $type) {
        if ($type == 'Solo') {
            $limit = 1;
        } elseif ($type == 'Duo') {
            $limit = 2;
        } elseif ($type == 'Squad') {
            $limit = 4;
        } elseif ($type == 'Squad5') {
            $limit = 5;
        }
        $this->db->select("user_name,mj.pubg_id,killed,total_win");
        $this->db->where("match_id", $match_id);
        $this->db->join("member as m", "mj.member_id = m.member_id", "left");
        $this->db->order_by('win_prize', 'DESC');
        $this->db->order_by('total_win', 'DESC');
        $query = $this->db->get('match_join_member as mj');
        $data['full_result'] = $query->result();
        $data['match_winner'] = array_slice($data['full_result'], 0, $limit);
        return $data;
    }

    public function getMatchPosition($match_id) {
        $this->db->select("m.*,g.game_image");
        $this->db->where("m.m_id", $match_id);
        $this->db->join("game as g", "g.game_id = m.game_id", "left");
        $query = $this->db->get('matches as m');
        $match = $query->row();

        $this->db->select("*");
        $this->db->where("member_id", $this->member->front_member_id);
        $query1 = $this->db->get('member');
        $member = $query1->row();

        $pubg_id = unserialize($member->pubg_id);
        $data['pubg_id'] = '';
        if (is_array($pubg_id) && array_key_exists($match->game_id, $pubg_id)) {
            $data['pubg_id'] = $pubg_id[$match->game_id];
        }

        $this->db->select("m.user_name,mj.pubg_id,mj.team,mj.position,ma.type");
        $this->db->where("mj.match_id", $match_id);
        $this->db->join("member as m", "m.member_id = mj.member_id", "left");
        $this->db->join("matches as ma", "ma.m_id = mj.match_id", "left");
        $this->db->order_by('team', 'ASC');
        $this->db->order_by('position', 'ASC');
        $query2 = $this->db->get('match_join_member as mj');
        $match_join_member = $query2->result();

        if ($match->type == 'Solo') {
            for ($j = 1; $j <= $match->number_of_position; $j++) {
                if (count($match_join_member) == 0) {
                    $a = array(
                        'user_name' => '',
                        'pubg_id' => '',
                        'team' => '1',
                        'position' => $j,
                    );
                } else {
                    foreach ($match_join_member as $res) {
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
        } elseif ($match->type == 'Duo') {
            $loop = ceil($match->number_of_position / 2);
            for ($j = 1; $j <= $loop; $j++) {
                for ($i = 1; $i <= 2; $i++) {
                    if (count($match_join_member) == 0) {
                        $a = array(
                            'user_name' => '',
                            'pubg_id' => '',
                            'team' => $j,
                            'position' => $i,
                        );
                    } else {
                        foreach ($match_join_member as $res) {
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
        } elseif ($match->type == 'Squad') {
            $loop = ceil($match->number_of_position / 4);
            for ($j = 1; $j <= $loop; $j++) {
                for ($i = 1; $i <= 4; $i++) {
                    if (count($match_join_member) == 0) {
                        $a = array(
                            'user_name' => '',
                            'pubg_id' => '',
                            'team' => $j,
                            'position' => $i,
                        );
                    } else {
                        foreach ($match_join_member as $res) {
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
        } elseif ($match->type == 'Squad5') {
            $loop = ceil($match->number_of_position / 5);
            for ($j = 1; $j <= $loop; $j++) {
                for ($i = 1; $i <= 5; $i++) {
                    if (count($match_join_member) == 0) {
                        $a = array(
                            'user_name' => '',
                            'pubg_id' => '',
                            'team' => $j,
                            'position' => $i,
                        );
                    } else {
                        foreach ($match_join_member as $res) {
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
