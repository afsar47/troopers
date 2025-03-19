<?php

class Topplayers extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        if ($this->member->front_logged_in !== true) {
            redirect('login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_default . 'Topplayers_model', 'topplayers');
    }

    function index() {
        $data['topplayers'] = true;
        $data['title'] = $this->lang->line('text_top_players');
        $data['breadcrumb_title'] = $this->lang->line('text_top_players');
        $data['top_players'] = $this->topplayers->getTopPlayers();
        $this->load->view($this->path_to_view_default . 'top_players', $data);
    }

}

?>
