<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Page_model extends CI_Model {

    public function __construct() {
        //parent::CI_Model();
        parent::__construct();
        $this->img_size_array = array(100 => 100);
        $this->table = 'page';
        $this->column_headers = array(
            'Page Title' => '',
            'Page Slug' => '',
        );
    }

    /* Page add */

    public function insert() {
        if ($_FILES['page_baner']['name'] != "") {
            $thumb_sizes = $this->img_size_array;
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['page_baner']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->page_banner;
            $config['allowed_types'] = 'jpg|png|jpeg';

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('page_baner')) {
                $data['error'] = array('error' => $this->upload->display_errors());
                print_r($data);
                die();
            } else {

                $data['page_banner_image'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->page_banner . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->page_banner . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->page_banner . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->page_banner . $image, $this->page_banner . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
        } else {
            $image = "";
        }

        $slug = url_title(strtolower($this->input->post('page_menutitle')));
        if ($this->input->post('addmenu') == '') {
            $addmenu = '0';
        } else {
            $addmenu = '1';
        }
        
        if ($this->input->post('addfooter') == '') {
            $addfooter = '0';
        } else {
            $addfooter = '1';
        }
        
        $data = array(
            'page_title' => $this->input->post('page_title'),
            'page_slug' => $slug,
            'page_content' => $this->input->post('page_content'),
            'page_banner_image' => $image,
            'page_menutitle' => $this->input->post('page_menutitle'),
            // 'page_browsertitle' => $this->input->post('page_browsertitle'),
            // 'page_metatitle' => $this->input->post('page_metatitle'),
            'page_metakeyword' => $this->input->post('page_metakeyword'),
            'page_metadesc' => $this->input->post('page_metadesc'),
            'page_order' => $this->input->post('pageorder'),
            'parent' => $this->input->post('parent'),
//            'page_publish' => $this->input->post('status'),
            'add_to_menu' => $addmenu,
            'add_to_footer' => $addfooter,
            'created_date' => date('Y-m-d H:i:s'),
        );
        if ($this->db->insert($this->table, $data)) {
            return true;
        } else {
            return false;
        }

        return false;
    }

    /* Banner edit */

    public function edit() {
        $thumb_sizes = $this->img_size_array;
        if ($_FILES['page_baner']['name'] == "") {
            $image = $this->input->post('old_page_baner');
        } else {
            if (file_exists($this->page_banner . $this->input->post('old_page_baner'))) {
                @unlink($this->page_banner . $this->input->post('old_page_baner'));
            }
            foreach ($thumb_sizes as $width => $height) {
                if (file_exists($this->page_banner . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_page_baner'))) {
                    @unlink($this->page_banner . "thumb/" . $width . "x" . $height . "_" . $this->input->post('old_page_baner'));
                }
            }
            $unique = $this->functions->GenerateUniqueFilePrefix();
            $image = $unique . '_' . preg_replace("/\s+/", "_", $_FILES['page_baner']['name']);
            $config['file_name'] = $image;
            $config['upload_path'] = $this->page_banner;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('page_baner')) {
                $data['error'] = array('error' => $this->upload->display_errors());
            } else {
                $data['page_banner_image'] = $this->upload->data();
                foreach ($thumb_sizes as $key => $val) {
                    list($width_orig, $height_orig, $image_type) = getimagesize($this->page_banner . $image);				                                                
                                                            
                    if ($width_orig != $key || $height_orig != $val) {                                                                                                                                                                    
                        $this->image->initialize($this->page_banner . $image);                                                       
                        $this->image->resize($key, $val);
                        $this->image->save($this->page_banner . "thumb/" . $key . "x" . $val . "_" . $image);
                    } else {
                        copy($this->page_banner . $image, $this->page_banner . "thumb/" . $key . "x" . $val . "_" . $image);
                    }
                }
            }
        }

        if ($this->input->post('addmenu') == '') {
            $addmenu = '0';
        } else {
            $addmenu = '1';
        }
        if ($this->input->post('addfooter') == '') {
            $addfooter = '0';
        } else {
            $addfooter = '1';
        }
        
        if ($this->input->post('page_slug') == 'about-us' || $this->input->post('page_slug') == 'home' || $this->input->post('page_slug') == 'how_to_install' || $this->input->post('page_slug') == 'contact' || $this->input->post('page_slug') == 'terms_conditions') {
            $slug = $this->input->post('page_slug');
        } else {
            $slug = url_title(strtolower($this->input->post('page_menutitle')));
        }
        $data = array(
            'page_title' => $this->input->post('page_title'),
            'page_slug' => $slug,
            'page_content' => $this->input->post('page_content'),
            'page_banner_image' => $image,
            'page_menutitle' => $this->input->post('page_menutitle'),
            // 'page_browsertitle' => $this->input->post('page_browsertitle'),
            // 'page_metatitle' => $this->input->post('page_metatitle'),
            'page_metakeyword' => $this->input->post('page_metakeyword'),
            'page_metadesc' => $this->input->post('page_metadesc'),
            'add_to_menu' => $addmenu,
            'add_to_footer' => $addfooter,
            'page_order' => $this->input->post('pageorder'),
            'parent' => $this->input->post('parent'),
//            'page_publish' => $this->input->post('status'),
        );
//        echo '<pre>';      
//        print_r($data);
//        exit;

        $this->db->where('page_id', $this->input->post('page_id'));

        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getMainMenuList() {
        $this->db->select('page_id,page_title');
        $this->db->where('parent', '0');
        $qry = $this->db->get('page');
        return $qry->result();
    }

    /* Count no. of page */

    public function get_list_count_page() {

        $this->db->select('*');
        $this->db->order_by("page_id", "Desc");
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    /* Get banner by id */

    public function get_pageById($id) {
        $this->db->select('*');
        $this->db->where("page_id", $id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    /* banner status change */

    public function changePublishStatus() {
        $this->db->set('page_publish', $this->input->post('publish'));
        $this->db->where('page_id', $this->input->post('pageid'));
        if ($query = $this->db->update($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    /* banner delete */

    public function delete() {

        $this->db->where('page_id', $this->input->post('pageid'));
        if ($query = $this->db->delete($this->table)) {
            return true;
        } else {
            return false;
        }
    }

    public function multiDelete() {   
        foreach($this->input->post('ids') as $key => $page_id){
            $this->db->where('page_id', $page_id);
            $this->db->delete($this->table);
        }     
        
        return true;        
    }    

    public function changeMultiPublishStatus() {

        foreach($this->input->post('ids') as $key => $page_id){
            $page_data = $this->get_pageById($page_id);

            if($page_data['page_publish'] == '0')
                $page_publish = '1';
            else
                $page_publish = '0';

            $this->db->set('page_publish', $page_publish);
            $this->db->where('page_id', $page_id);
            $this->db->update($this->table);
        }
        return true;        
    }

}
