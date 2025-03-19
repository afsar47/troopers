<?php

class Topplayers_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('image_lib');
//        $this->column_headers = array(
//            'Position' => '',
//            'Player Name' => '',
//            'Winning ('.$this->functions->getPoint().')' => '',
//        );
    }

    public function getGameData()
    {
        $this->db->select('*');
        $this->db->where('status', '1');
        $this->db->where('game_type','0');
        $query = $this->db->get('game');
        return $query->result();
    }

    public function getTopPlayersByGame($game_id)
    {
        $this->db->select('SUM(total_win) as t_win,member.user_name,member.member_id');
        $this->db->join('member', 'match_join_member.member_id = member.member_id');
        $this->db->join('matches', 'matches.m_id = match_join_member.match_id');
        $this->db->where('matches.game_id', $game_id);
        $this->db->group_by('match_join_member.member_id');
        $this->db->order_by('t_win', 'DESC');
        $this->db->limit('10');
        $query = $this->db->get('match_join_member');
        return $query->result();
    }

}
