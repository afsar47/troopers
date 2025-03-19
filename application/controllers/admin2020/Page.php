<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    function __construct() {
        parent::__construct();
        if (YES == 'yes') {
            redirect($this->path_to_view_admin . 'license');
        }
        /* index of the admin. Default: Dashboard; On No Login Session: Back to login page. */
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('page')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image');
        $this->page_id_array = array(6, 7, 8, 9, 10, 11);
        $this->img_size_array = array(100 => 100);
        $this->load->model($this->path_to_view_admin . '/Page_model', 'page');
        $this->con = $this->functions->mysql_connection();
    }

    /* Load Pages page */
    /* Pages add, edit, view, delete */

    public function index() {
        /* Pages add page load */
        if ($this->input->get('action') == 'add') {
            $data['page_addedit'] = true;
            $data['title'] = $this->lang->line('text_add_page');
            $data['action'] = $this->lang->line('text_action_add');
            $data['main_menu'] = $this->page->getMainMenuList();

            $this->load->view($this->path_to_view_admin . 'page_addedit', $data);
        }
        /* Pages Edit page load */ elseif ($this->input->get('page_id') != "") {
            $data['page_addedit'] = true;
            $data['title'] = $this->lang->line('text_edit_page');
            $data['btn'] = $this->lang->line('text_view_page');
            $data['action'] = $this->lang->line('text_action_edit');
            $data['main_menu'] = $this->page->getMainMenuList();
            $data['entry'] = $this->page->get_pageById($_GET['page_id']);
            $this->load->view($this->path_to_view_admin . 'page_addedit', $data);
        }
        /* Pages add */ elseif ($this->input->post('submit') == $this->lang->line('text_action_add')) {
            $thumb_sizes = $this->img_size_array;
            $data['page_addedit'] = true;
            $data['title'] = $this->lang->line('text_add_page');
            $data['action'] = $this->lang->line('text_action_add');
            $data['page_title'] = $this->input->post('page_title');
            $data['page_menutitle'] = $this->input->post('page_menutitle');
            $data['page_browsertitle'] = $this->input->post('page_browsertitle');
            $data['page_metatitle'] = $this->input->post('page_metatitle');
            $data['page_metakeyword'] = $this->input->post('page_metakeyword');
            $data['page_metadesc'] = $this->input->post('page_metadesc');
            $data['page_content'] = $this->input->post('page_content');
//            $data['status'] = $this->input->post('status');
            $data['addmenu'] = $this->input->post('addmenu');
            if ($data['addmenu'] == '') {
                $data['addmenu'] = '0';
            }
            
            $data['addfooter'] = $this->input->post('addfooter');
                if ($data['addfooter'] == '') {
                    $data['addfooter'] = '0';
                }
                
            $data['parent'] = $this->input->post('parent');
            $data['pageorder'] = $this->input->post('pageorder');

            $this->form_validation->set_rules('page_title', 'lang:text_page_title', 'required', array('required' => $this->lang->line('err_page_title_req')));
            $this->form_validation->set_rules('page_baner', 'lang:text_page_banner_image', 'callback_file_check');
            $this->form_validation->set_rules('page_menutitle', 'lang:text_page_menu_title', 'required', array('required' => $this->lang->line('err_page_menutitle_req')));
            $this->form_validation->set_rules('page_metakeyword', 'lang:text_meta_keyword', 'required', array('required' => $this->lang->line('err_page_metakeyword_req')));
            $this->form_validation->set_rules('page_metadesc', 'lang:text_meta_description', 'required', array('required' => $this->lang->line('err_page_metadesc_req')));
//            $this->form_validation->set_rules('status', 'lang:text_status', 'required', array('required' => $this->lang->line('err_status_req')));
            $this->form_validation->set_rules('pageorder', 'lang:text_page_order', 'required|numeric', array('required' => $this->lang->line('err_pageorder_req'), 'numeric' => $this->lang->line('err_number')));

            if ($this->form_validation->run() == FALSE) {

                $this->load->view($this->path_to_view_admin . '/page_addedit', $data);
            } else {

                if ($this->page->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_page'));
                    redirect($this->path_to_view_admin . 'page/');
                }
            }
        }
        /* Pages edit */ elseif ($this->input->post('submit') == $this->lang->line('text_action_edit')) {
            if (($this->system->demo_user == 1 && in_array($this->input->post('page_id'), $this->page_id_array)) || !$this->functions->check_permission('page_edit')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_page'));
                redirect($this->path_to_view_admin . 'page/');
            } else {
                $thumb_sizes = $this->img_size_array;
                $data['page_addedit'] = true;
                $data['title'] = $this->lang->line('text_edit_page');
                $data['action'] = $this->lang->line('text_action_edit');
                $data['page_title'] = $this->input->post('page_title');
                $data['page_menutitle'] = $this->input->post('page_menutitle');
                $data['page_browsertitle'] = $this->input->post('page_browsertitle');
                $data['page_metatitle'] = $this->input->post('page_metatitle');
                $data['page_metakeyword'] = $this->input->post('page_metakeyword');
                $data['page_metadesc'] = $this->input->post('page_metadesc');
                $data['page_content'] = $this->input->post('page_content');
//                $data['status'] = $this->input->post('status');
                $data['addmenu'] = $this->input->post('addmenu');
                if ($data['addmenu'] == '') {
                    $data['addmenu'] = '0';
                }
                
                $data['addfooter'] = $this->input->post('addfooter');
                if ($data['addfooter'] == '') {
                    $data['addfooter'] = '0';
                }
                
                $data['pageorder'] = $this->input->post('pageorder');
                $data['parent'] = $this->input->post('parent');

                $this->form_validation->set_rules('page_title', 'lang:text_page_title', 'required', array('required' => $this->lang->line('err_page_title_req')));
                $this->form_validation->set_rules('page_baner', 'lang:text_page_banner_image', 'callback_file_check');
                $this->form_validation->set_rules('page_menutitle', 'lang:text_page_menu_title', 'required', array('required' => $this->lang->line('err_page_menutitle_req')));
                $this->form_validation->set_rules('page_metakeyword', 'lang:text_meta_keyword', 'required', array('required' => $this->lang->line('err_page_metakeyword_req')));
                $this->form_validation->set_rules('page_metadesc', 'lang:text_meta_description', 'required', array('required' => $this->lang->line('err_page_metadesc_req')));
//                $this->form_validation->set_rules('status', 'lang:text_status', 'required', array('required' => $this->lang->line('err_status_req')));
                $this->form_validation->set_rules('pageorder', 'lang:text_page_order', 'required|numeric', array('required' => $this->lang->line('err_pageorder_req'), 'numeric' => $this->lang->line('err_number')));

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . '/page_addedit', $data);
                } else {
                    if ($this->page->edit()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_page'));
                        redirect($this->path_to_view_admin . 'page/');
                    }
                }
            }
        }
        /* Pages delete */ elseif ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && in_array($this->input->post('pageid'), $this->page_id_array)) || !$this->functions->check_permission('page_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_page'));
                redirect($this->path_to_view_admin . 'page/');
            } else {
                if ($result = $this->page->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_page'));
                    redirect($this->path_to_view_admin . 'page/');
                }
            }
        }
        /* Pages status change */ elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && in_array($this->input->post('pageid'), $this->page_id_array)) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'page/');
            } else {
                if ($result = $this->page->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_page'));
                    redirect($this->path_to_view_admin . 'page/');
                }
            }
        }
        /* Pages delete multiple */ elseif ($this->input->post('ids') != "") {
            if ($result = $this->page->delete_multiple()) {
                echo json_encode(true);
            }
        } else {

            $data['pages_manage'] = true;
            $data['title'] = $this->lang->line('text_page');
            $data['btn'] = $this->lang->line('text_add_page');

            $this->load->view($this->path_to_view_admin . 'page_manage', $data);
        }
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if ($this->system->demo_user == 1 || !$this->functions->check_permission('page_delete')) {                    
                echo $this->lang->line('text_err_delete_page');                    
            } else {
                $this->page->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if ($this->system->demo_user == 1 || !$this->functions->check_permission('page')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->page->changeMultiPublishStatus();            
            }       
        }
    }

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['page_baner']['name']) && $_FILES['page_baner']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['page_baner']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["page_baner"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    /* show Pages using ajax */

    function setDatatablePage() {
        $requestData = $_REQUEST;

        $columns = array(            
            2 => 'page_title',
            3 => 'page_slug',
            4 => 'page_publish',
        );

        $totalData = $this->page->get_list_count_page();
        $totalFiltered = $totalData;

        $sql = "SELECT * FROM page";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE page_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR page_title LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR page_slug LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR page_publish LIKE '%" . $requestData['search']['value'] . "%' ";
        }

        $query = mysqli_query($this->con, $sql);

        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 

        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `page_id` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }

        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = 1;
        while ($row = mysqli_fetch_array($query)) {

            $nestedData = array();
            if($this->system->demo_user == 1 && in_array($row['page_id'], $this->page_id_array)) {
                $nestedData[] = '';
            } else {
                $nestedData[] = '<input type="checkbox" value="'. $row['page_id'] .'" class="all_inputs">';
            }
            $nestedData[] = $i;
            $nestedData[] = $row['page_title'];
            $nestedData[] = $row['page_slug'];
            if ($this->system->demo_user == 1 && in_array($row['page_id'], $this->page_id_array)) {
                if ($row['page_publish'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top" border="0">Inactive</span>';
                }
            } else {
                if ($row['page_publish'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmpagelist,' . $row['page_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmpagelist,' . $row['page_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }

            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'page?page_id=' . $row['page_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';
            if ($this->system->demo_user == 1 && in_array($row['page_id'], $this->page_id_array)) {
                if ($row['page_slug'] == 'about-us' || $row['page_slug'] == 'home' || $row['page_slug'] == 'contact' || $row['page_slug'] == 'how_to_install' || $row['page_slug'] == 'terms_conditions') {
                    $delete = '';
                } else {
                    $delete = '<a  class="" disabled="disabled" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp;';
                }
            } else {
                if ($row['page_slug'] == 'about-us' || $row['page_slug'] == 'home' || $row['page_slug'] == 'contact' || $row['page_slug'] == 'how_to_install' || $row['page_slug'] == 'terms_conditions') {
                    $delete = '';
                } else {
                    $delete = '<a  class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDelete(document.frmpagelist,' . $row['page_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp;';
                }
            }
            $nestedData[] = $edit . $delete;

            $data[] = $nestedData;
            $i++;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

}
