<?php

class Announcement extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        if ($this->member->front_logged_in !== true) {
            redirect('login');
        }
        $this->load->model($this->path_to_default . 'Play_model', 'play');
    }

    function index() {
        $data['Announcement'] = true;
        $data['title'] = $this->lang->line('text_announcement');
        $data['breadcrumb_title'] = $this->lang->line('text_announcement');
        $data['announcement_data'] = $this->play->getAllAnnouncement();
        $this->load->view($this->path_to_view_default . 'announcement', $data);
    }

}

?>
