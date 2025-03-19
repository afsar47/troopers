<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Topplayers_model extends CI_Model {

    public function getTopPlayers() {
        $this->db->select("*");
        $this->db->where("status", '1');
        $this->db->where('game_type','0');
        $query = $this->db->get('game');
        $data['players'] = array();
        $data['games'] = array();
        $games = $query->result();
        foreach ($games as $key => $game) {
            $this->db->select('sum(total_win) as winning,m.user_name,m.member_id,m.pubg_id');
            $this->db->join("member as m", "m.member_id = mj.member_id");
            $this->db->join("matches as m1", "m1.m_id = mj.match_id");
            $this->db->where("m1.game_id", $game->game_id);
            $this->db->group_by("mj.member_id");
            $this->db->order_by('winning', 'DESC');
            $this->db->limit('10');
            $query1 = $this->db->get('match_join_member as mj');
            if ($query1->num_rows() > 0) {
                $data['games'][] = $game;
                $data['players'][$game->game_name] = $query1->result();
            }
        }
        return $data;
    }

    public function getLeaderBord() {
        $this->db->select('m2.user_name,m.referral_id,COUNT(m.referral_id) as tot_referral');
        $this->db->join("member as m2", "m.referral_id = m2.member_id");
        $this->db->where("m.referral_id != ", 0);
        $this->db->group_by("m.referral_id");
        $this->db->order_by('tot_referral', 'DESC');
        $this->db->limit('10');
        $query = $this->db->get('member as m');
        return $query->result();
    }

}
