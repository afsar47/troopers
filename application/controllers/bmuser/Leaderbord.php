<?php

class Leaderbord extends CI_Controller {

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
        $data['leaderbord'] = true;
        $data['title'] = $this->lang->line('text_leaderboard');
        $data['breadcrumb_title'] = $this->lang->line('text_leaderboard');
        $data['leaderbord'] = $this->topplayers->getLeaderBord();
        $this->load->view($this->path_to_view_default . 'leaderbord', $data);
    }

}

?>
