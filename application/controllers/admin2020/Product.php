<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    public function __construct() {
        parent::__construct();
       
        if ($this->session->userdata('logged_in') !== true) {
            redirect($this->path_to_view_admin . 'login');
        }
        if(!$this->functions->check_permission('product')) {
            redirect($this->path_to_view_admin . 'login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->library('upload');
        $this->load->helper('file');
        $this->load->library('image');
        $this->load->model($this->path_to_view_admin . 'Product_model', 'product');
    }

    function index() {
        $data['product'] = true;
        $data['btn'] = $this->lang->line('text_add_product');
        $data['title'] = $this->lang->line('text_product');
        if ($this->input->post('action') == "delete") {
            if (($this->system->demo_user == 1 && $this->input->post('productid') <= 2) || !$this->functions->check_permission('product_delete')) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_delete_product'));
                redirect($this->path_to_view_admin . 'product/');
            } else {
                if ($result = $this->product->delete()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_delete_product'));
                    redirect($this->path_to_view_admin . 'product/');
                }
            }
        } elseif ($this->input->post('action') == "change_publish") {
            if ($this->system->demo_user == 1 && $this->input->post('productid') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_status'));
                redirect($this->path_to_view_admin . 'product/');
            } else {
                if ($result = $this->product->changePublishStatus()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_status_product'));
                    redirect($this->path_to_view_admin . 'product/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'product_manage', $data);
        }
    }

    function multi_action(){        

        if ($this->input->post('action') == "delete") {  
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 2) || !$this->functions->check_permission('product_delete')) {                    
                echo $this->lang->line('text_err_delete_product');                    
            } else {
                $this->product->multiDelete();                       
            }           
        } elseif ($this->input->post('action') == "change_publish") {     
            if (($this->system->demo_user == 1 && min($this->input->post('ids')) <= 2) || !$this->functions->check_permission('product')) {                    
                echo $this->lang->line('text_err_status');                    
            } else {
                $this->product->changeMultiPublishStatus();            
            }       
        }
    }

    function setDatatableProduct() {
        $requestData = $_REQUEST;
        $columns = array(            
            2 => 'product_name',
            3 => 'product_image',
            4 => 'product_actual_price',
            5 => 'product_selling_price',
            6 => 'product_status',
            7 => 'date_created'
        );
        $totalData = $this->product->get_list_count_product();
        $totalFiltered = $totalData;

        $sql = "SELECT product_id,product_name,product_image,product_actual_price,product_selling_price,product_status,date_created FROM product";
        
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " WHERE  product_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  product_name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  product_image LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  product_actual_price LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  product_selling_price LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  product_status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  date_created LIKE '%" . $requestData['search']['value'] . "%' ";
        }
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `date_created` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = '<input type="checkbox" value="'. $row['product_id'] .'" class="all_inputs">';
            $nestedData[] = $i;
            $nestedData[] = $row['product_name'];
            $nestedData[] = '<img src="' . base_url() . $this->product_image . "thumb/100x100_" . $row['product_image'] . '">';
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['product_actual_price']);
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['product_selling_price']);
            if ($this->system->demo_user == 1 && $row['product_id'] <= 2) {
                if ($row['product_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top">Active</span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top">Inactive</span>';
                }
            } else {
                if ($row['product_status'] == '1') {
                    $nestedData[] = '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top" style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmproductlist,' . $row['product_id'] . ',0);">Active <i class="fa fa-pencil"></i></span>';
                } else {
                    $nestedData[] = '<span class="badge badge-danger" data-original-title="Publish" data-placement="top" style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmproductlist,' . $row['product_id'] . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                }
            }
            $edit = '<a class="" style="font-size:18px;" data-original-title="Edit" data-placement="top"  href=' . base_url() . $this->path_to_view_admin . 'product/edit/' . $row['product_id'] . '><i class="fa fa-edit"></i></a>&nbsp;';

            if ($this->system->demo_user == 1 && $row['product_id'] <= 2) {
                $delete = '<a disabled="disabled" class="" data-original-title="Delete" data-placement="top"  style="font-size:18px;color:#007bff"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
            } else {
                $delete = '<a class="" data-original-title="Delete" data-placement="top"  style="cursor: pointer;font-size:18px;color:#007bff" onClick="javascript: confirmDeleteProduct(document.frmproductlist,' . $row['product_id'] . ');"><i class="fa fa-trash-o"></i> </a>&nbsp; ';
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

    public function file_check() {
        $allowed_mime_type_arr = array('image/jpeg', 'image/png');
        if (isset($_FILES['product_image']['name']) && $_FILES['product_image']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['product_image']['name']);
            if (!in_array($mime, $allowed_mime_type_arr)) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_accept'));
                return false;
            } else if ($_FILES["product_image"]["size"] > 2000000) {
                $this->form_validation->set_message('file_check', $this->lang->line('err_image_size'));
                return false;
            } else {
                return true;
            }
        }
    }

    function insert() {
        $data['product_addedit'] = true;
        $data['btn'] = $this->lang->line('text_view_product');
        $data['Action'] = $this->lang->line('text_action_add');
        $data['title'] = $this->lang->line('text_add_product');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            $data['product_name'] = $this->input->post('product_name');
            $data['product_image'] = $this->input->post('product_image');
            $data['product_actual_price'] = $this->input->post('product_actual_price');
            $data['product_selling_price'] = $this->input->post('product_selling_price');
            $data['product_short_description'] = $this->input->post('product_short_description');
            $data['product_description'] = $this->input->post('product_description');

            $this->form_validation->set_rules('product_name', 'lang:text_product_name', 'required', array('required' => $this->lang->line('err_product_name_req')));
            $this->form_validation->set_rules('product_image', 'lang:text_image', 'callback_file_check');
            $this->form_validation->set_rules('product_actual_price', 'lang:text_product_actual_price', 'required|numeric', array('required' => $this->lang->line('err_actual_price_req'), 'numeric' => $this->lang->line('err_actual_price_number')));
            $this->form_validation->set_rules('product_selling_price', 'lang:text_product_selling_price', 'required|numeric', array('required' => $this->lang->line('err_selling_price_req'), 'numeric' => $this->lang->line('err_selling_price_number')));
            $this->form_validation->set_rules('product_short_description', 'lang:text_product_short_desc', 'required', array('required' => $this->lang->line('err_short_desc_req')));
            $this->form_validation->set_rules('product_description', 'lang:text_product_desc', 'required', array('required' => $this->lang->line('err_desc_req')));

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_admin . 'product_addedit', $data);
            } else {
                if ($result = $this->product->insert()) {
                    $this->session->set_flashdata('notification', $this->lang->line('text_succ_add_product'));
                    redirect($this->path_to_view_admin . 'product/');
                }
            }
        } else {
            $this->load->view($this->path_to_view_admin . 'product_addedit', $data);
        }
    }

    function edit() {
        if(!$this->functions->check_permission('product_edit')) {
            $this->session->set_flashdata('error', $this->lang->line('text_err_edit_product'));
            redirect($this->path_to_view_admin . 'product');
        }

        $data['product_addedit'] = true;
        $product_id = $this->uri->segment('4');
        $data['Action'] = $this->lang->line('text_action_edit');
        $data['title'] = $this->lang->line('text_edit_product');
        if ($this->input->post('submit') == $this->lang->line('text_btn_submit')) {
            if ($this->system->demo_user == 1 && $this->input->post('product_id') <= 2) {
                $this->session->set_flashdata('error', $this->lang->line('text_err_edit_product'));
                redirect($this->path_to_view_admin . 'product/');
            } else {
                $data['product_name'] = $this->input->post('product_name');
                $data['product_image'] = $this->input->post('product_image');
                $data['product_actual_price'] = $this->input->post('product_actual_price');
                $data['product_selling_price'] = $this->input->post('product_selling_price');
                $data['product_short_description'] = $this->input->post('product_short_description');
                $data['product_description'] = $this->input->post('product_description');

                $this->form_validation->set_rules('product_name', 'lang:text_product_name', 'required', array('required' => $this->lang->line('err_product_name_req')));
                $this->form_validation->set_rules('product_image', 'lang:text_image', 'callback_file_check');
                $this->form_validation->set_rules('product_actual_price', 'lang:text_product_actual_price', 'required|numeric', array('required' => $this->lang->line('err_actual_price_req'), 'numeric' => $this->lang->line('err_actual_price_number')));
                $this->form_validation->set_rules('product_selling_price', 'lang:text_product_selling_price', 'required|numeric', array('required' => $this->lang->line('err_selling_price_req'), 'numeric' => $this->lang->line('err_selling_price_number')));
                $this->form_validation->set_rules('product_short_description', 'lang:text_product_short_desc', 'required', array('required' => $this->lang->line('err_short_desc_req')));
                $this->form_validation->set_rules('product_description', 'lang:text_product_desc', 'required', array('required' => $this->lang->line('err_desc_req')));

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view($this->path_to_view_admin . 'product_addedit', $data);
                } else {
                    if ($result = $this->product->update()) {
                        $this->session->set_flashdata('notification', $this->lang->line('text_succ_edit_product'));
                        redirect($this->path_to_view_admin . 'product/');
                    }
                }
            }
        } else {
            $data['product_detail'] = $this->product->getproductById($product_id);
            $this->load->view($this->path_to_view_admin . 'product_addedit', $data);
        }
    }

}
