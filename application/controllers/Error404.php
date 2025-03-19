<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Error404 extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
    }

    public function index() {
        if ($this->uri->segment('1') == ADMINPATH) {
            header("HTTP/1.0 404 Not Found");
            echo "<h1>" . $this->lang->line('text_404_page_not_found') . "</h1>";
            echo $this->lang->line('not_found');
            exit();
        } else {
            $data['title'] = $this->lang->line('text_404');
            $data['page_menutitle'] = $this->lang->line('text_404');
            $data['meta_description'] = $this->lang->line('text_404');
            $data['meta_keyword'] = $this->lang->line('text_404');
            $data['page_banner_image'] = $this->lang->line('text_404');
            $data['page_content'] = $this->lang->line('text_404');
            $this->load->view($this->path_to_view_front . 'error404', $data);
        }
    }

}

?>