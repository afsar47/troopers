<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        $this->load->model('home_model', 'home');
    }

    public function index($id = 'home') {

        $data['page'] = "content";

        if($data['content'] = $this->functions->getPage($id)) {
            $data['title'] = $data['content']['page_title'];
            $data['page_menutitle'] = $data['content']['page_menutitle'];
            $data['meta_description'] = $data['content']['page_metadesc'];
            $data['meta_keyword'] = $data['content']['page_metakeyword'];
            $data['page_banner_image'] = $data['content']['page_banner_image'];
            $data['page_content'] = $data['content']['page_content'];
        } else {
            $data['title'] = '';
            $data['page_menutitle'] = '';
            $data['meta_description'] = '';
            $data['meta_keyword'] = '';
            $data['page_banner_image'] = '';
            $data['page_content'] = '';
        }

        if ($id == 'home') {
            $this->db->select('*');
            $this->db->where('status', '1');
            $this->db->order_by('dp_order', 'ASC');
            $query = $this->db->get('screenshots');
            $data['screenshots'] = $query->result();

            $this->db->select('*');
            $this->db->order_by('app_upload_id', 'DESC');
            $this->db->limit('1');
            $query1 = $this->db->get('app_upload');
            $data['app_upload'] = $query1->row_array();
            $this->db->select('*');
            $this->db->order_by('game_id', 'DESC');
            $this->db->where('game_type','0');
            $query2 = $this->db->get('game');
            $data['tournaments'] = $query2->result();
            $this->db->select('*');
            $this->db->order_by('htp_order', 'ASC');
            $query3 = $this->db->get('howtoplay_content');
            $data['htp_contents'] = $query3->result();
            $data['features_tabs'] = $this->home->getFeaturesTab();
        }
       
        if ($id == 'contact') {
            $data['includefile'] = "contact";
        }
        if ($id == 'how_to_install') {
            $this->db->select('*');
            $this->db->where('status', '1');
            $this->db->order_by('dp_order', 'ASC');
            $query = $this->db->get('download');
            $data['downloads'] = $query->result();
            $data['includefile'] = "howtoinstall";
        }
        $this->load->view($this->path_to_view_front . 'layout', $data);
    }

    function contact() {

        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $message = $this->input->post('message');
        $subject1 = $this->input->post('subject');
        $phone = $this->input->post('phone');
        $this->form_validation->set_rules('name', 'lang:text_full_name', 'required', array('required' => $this->lang->line('err_fname_req')));
        $this->form_validation->set_rules('email', 'lang:text_email', 'required', array('required' => $this->lang->line('err_email_req')));
        $this->form_validation->set_rules('message', 'lang:text_message', 'required', array('required' => $this->lang->line('err_subject_req')));
        $this->form_validation->set_rules('subject', 'lang:text_subject', 'required', array('required' => $this->lang->line('err_message_req')));

        if ($this->form_validation->run() == FALSE) {
                    echo 'test';

            echo false;
        } else {

            $this->load->library('email');
            $this->load->library('parser');
            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => $this->system->smtp_host,
                'smtp_port' => $this->system->smtp_port,
                'smtp_user' => $this->system->smtp_user,
                'smtp_pass' => urldecode($this->system->smtp_pass),
                'mailtype' => 'html',
                'charset' => 'iso-8859-1'
            );
            $this->email->initialize($config);
            $this->email->set_mailtype("html");
            $this->email->set_newline("\r\n");
            $this->email->from($email, $name);
            $this->email->to($this->system->company_email);
            $this->email->subject($subject1);
            $message = '<table>
                                <tr>
                                    <th>Subject: </th>
                                    <td>' . $subject1 . '</td>
                                </tr>
                                <tr>
                                    <th>Name: </th>
                                    <td>' . $name . '</td>
                                </tr>
                                <tr>
                                    <th>Email: </th>
                                    <td>' . $email . '</td>
                                </tr>
                                <tr>
                                    <th>Message: </th>
                                    <td>' . $message . '</td>
                                </tr>
                            </table>';
            $this->email->message($message);
            if ($this->email->send()) {
                echo true;
            } else {
                show_error($this->email->print_debugger());
                exit;
                echo false;
            }
        }
    }

    function gettemplate() {
        $this->system->gettemplate();
    }    

}

?>