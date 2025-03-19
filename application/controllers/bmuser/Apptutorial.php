<?php

class Apptutorial extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        if ($this->member->front_logged_in !== true) {
            redirect('login');
        }
        $this->con = $this->functions->mysql_connection();
    }

    function index() {
        $data['apptutorial'] = true;
        $data['title'] = $this->lang->line('text_app_tutorial');
        $data['breadcrumb_title'] = $this->lang->line('text_app_tutorial');
        $data['app_tutorials'] = $this->getAppTutorial();
        $this->load->view($this->path_to_view_default . 'app_tutorial', $data);
    }

    public function getAppTutorial() {
        $this->db->select('*');
        $query = $this->db->get('youtube_link');
        return $query->result();
    }

}

?>
