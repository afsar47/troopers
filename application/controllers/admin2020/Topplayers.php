<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Topplayers extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('topplayers')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_view_admin . 'Topplayers_model', 'topplayers');
    }

    function index() {
        $data['topplayers'] = true;
        $data['title'] = $this->lang->line('text_top_players');

        $data['game_data'] = $this->topplayers->getGameData();
        $this->load->view($this->path_to_view_admin . 'top_players_manage', $data);
    }

}
