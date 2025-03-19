<?php

require('razorpay-php/Razorpay.php');

use Razorpay\Api\Api;
use IEXBase\TronAPI\Tron;

class Wallet extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->system->under_maintenance == 1) {
            header('Location: ' . base_url() . 'maintenance.html');
        }
        if ($this->member->front_logged_in !== true) {
            redirect('login');
        }
        $this->con = $this->functions->mysql_connection();
        $this->load->model($this->path_to_default . 'Wallet_model', 'wallet');
        $this->load->model($this->path_to_default . 'Account_model', 'account');
    }

    function index() {
        
        $data['my_wallet'] = true;
        $data['title'] = $this->lang->line('text_my_wallet');
        $data['breadcrumb_title'] = $this->lang->line('text_my_wallet');
        $data['member'] = $this->account->getMemberDetail($this->member->front_member_id);
        $data['tot_play'] = $this->account->get_play();
        $data['tot_withdraw'] = $this->account->get_tot_withdraw();
        $data['wallet_history_data'] = $this->wallet->getWalletHistoryData();
        $this->load->view($this->path_to_view_default . 'my_wallet', $data);
    }

    function setDatatableWallet() {

        $requestData = $_REQUEST;
        $columns = array(
            0 => 'account_statement_id',
            1 => 'transaction',
            2 => 'note',
            3 => 'deposit',
            4 => 'join_money',
            5 => 'win_money',
            6 => 'accountstatement_dateCreated'
        );
        $totalData = $this->wallet->get_list_count_wallet();
        $totalFiltered = $totalData;

        $sql = "SELECT * FROM accountstatement where member_id = " . $this->member->front_member_id;
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND(  account_statement_id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  deposit LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  note LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  withdraw LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  join_money LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  win_money LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  accountstatement_dateCreated LIKE '%" . $requestData['search']['value'] . "%' )";
        }
        $query = mysqli_query($this->con, $sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        if (isset($requestData['order'][0]['column'])) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY `account_statement_id` DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        $query = mysqli_query($this->con, $sql);
        $data = array();
        $i = $requestData['start'] + 1;
        while ($row = mysqli_fetch_array($query)) {
            $nestedData = array();
            $nestedData[] = $i;
            if ($row['withdraw'] > 0)
                $nestedData[] = '<span class="text-primary"> Debit</span>';
            else
                $nestedData[] = '<span class="text-lightgreen"> Credit</span>';
            $nestedData[] = $row['note'];
            if ($row['withdraw'] > 0)
                $nestedData[] = '<span class="text-primary"> - ' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['withdraw']) . '</span>';
            else
                $nestedData[] = '<span class="text-lightgreen"> + ' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['deposit']) . '</span>';
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['join_money']);
            $nestedData[] = sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $row['win_money']);
            $nestedData[] = $row['accountstatement_dateCreated'];
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

    function addmoney() {
        $data['add_money'] = true;
        $data['title'] = $this->lang->line('text_add_money');
        $data['breadcrumb_title'] = $this->lang->line('text_add_money');
        $data['payment_methods'] = $this->wallet->getPaymentMethod();

        if ($this->input->post('add_money') == $this->lang->line('text_add_money')) {
            $data['amount'] = $this->input->post('amount');
            $data['payment_method'] = $this->input->post('payment_method');
            $payment_method = $this->wallet->getAddMoneyMethod($this->input->post('payment_method'));

            $this->form_validation->set_rules('amount', 'lang:text_amount', 'required|numeric|greater_than_equal_to[' . $this->system->min_addmoney . ']', array('required' => $this->lang->line('err_amount_req'), 'numeric' => $this->lang->line('err_amount_number')));
            $this->form_validation->set_rules('payment_method', 'lang:text_payment_method', 'required', array('required' => $this->lang->line('err_payment_method_req')));
                        
            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_default . 'add_money', $data);
            } else {
                $member = $this->account->getMemberDetail($this->member->front_member_id);
                if ($payment_method['payment_name'] == 'Offline') {
                    $deposit_data = array(
                        'member_id' => $this->member->front_member_id,
                        'deposit_amount' => $this->input->post('amount'),
                        'deposit_status' => '0',
                        'deposit_by' => $payment_method['payment_name'],
                        'entry_from' => '2',
                        'deposit_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('deposit', $deposit_data);

                    $this->session->set_flashdata('success', $this->lang->line('text_add_money_requsted'));
                    redirect($this->path_to_default . 'wallet');
                } elseif ($payment_method['payment_name'] == 'PayPal') {
                    $deposit_data = array(
                        'member_id' => $this->member->front_member_id,
                        'deposit_amount' => $this->input->post('amount'),
                        'deposit_status' => '0',
                        'deposit_by' => $payment_method['payment_name'],
                        'entry_from' => '2',
                        'deposit_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('deposit', $deposit_data);
                    $order_id = $this->db->insert_id();
                    $this->load->library('paypal');
                    $this->paypal->add_field('amount', $this->input->post('amount') / $payment_method['currency_point']);
                    $this->paypal->add_field('business', $payment_method['name']);
                    $this->paypal->add_field('currency_code', $payment_method['currency_code']);
                    $this->paypal->add_field('notify_url', base_url() . $this->path_to_default . 'wallet/paypal_ipn');
                    $this->paypal->add_field('cancel_return', base_url() . $this->path_to_default . 'wallet/paypal_cancel');
                    $this->paypal->add_field('return', base_url() . $this->path_to_default . 'wallet/paypal_success');
                    $this->paypal->submit_paypal_post();
                } elseif ($payment_method['payment_name'] == 'PayStack') {
                    $deposit_data = array(
                        'member_id' => $this->member->front_member_id,
                        'deposit_amount' => $this->input->post('amount'),
                        'deposit_status' => '0',
                        'deposit_by' => $payment_method['payment_name'],
                        'entry_from' => '2',
                        'deposit_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('deposit', $deposit_data);
                    $order_id = $this->db->insert_id();
                    $metadata = array(
                        "custom_fields" => array(
                            "order_id" => $order_id,
                        )
                    );
                    $metadata = json_encode($metadata);
                    $curl = curl_init();
                    $callback_url = base_url() . $this->path_to_default . 'wallet/paystack_callback';
//                    echo round($this->input->post('amount'));exit;
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode(['amount' => floor($this->input->post('amount') / $payment_method['currency_point']), 'email' => $member['email_id'], 'callback_url' => $callback_url, 'metadata' => $metadata]),
                        CURLOPT_HTTPHEADER => ["authorization: Bearer " . $payment_method['mid'], //replace this with your own test key 
                            "content-type: application/json",
                            "cache-control: no-cache"]));
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    if ($err) {
                        $this->session->set_flashdata('error', $this->lang->line('text_err_money_add'));
                        redirect($this->path_to_default . 'wallet');
                    }
                    $tranx = json_decode($response, true);
                    if (!$tranx['status']) {
                        $this->session->set_flashdata('error', $this->lang->line('text_err_money_add'));
                        redirect($this->path_to_default . 'wallet');
                    }
                    header("Location: " . $tranx['data']['authorization_url']);
                } elseif ($payment_method['payment_name'] == 'Instamojo') {
                    $deposit_data = array(
                        'member_id' => $this->member->front_member_id,
                        'deposit_amount' => $this->input->post('amount'),
                        'deposit_status' => '0',
                        'deposit_by' => $payment_method['payment_name'],
                        'entry_from' => '2',
                        'deposit_dateCreated' => date('Y-m-d H:i:s')
                    );
                        
                    $this->db->insert('deposit', $deposit_data);
                    $order_id = $this->db->insert_id();
                    $this->load->library('instamojo');
                    $this->load->helper('url');
                    $custom_fields = array(
                        'order_id' => 1,
                    );
                    $pay = $this->instamojo->pay_request(
                            $amount = $this->input->post('amount') / $payment_method['currency_point'], $purpose = "Add Money to wallet", $buyer_name = '', $email = '', //$member['email_id'], 
                            $phone = '', //$member['mobile_no'], 
                            $send_email = 'FALSE', $send_sms = 'FALSE', $repeated = 'FALSE', $custom_fields
                    );
                    if (isset($pay['longurl'])) {
                        $redirect_url = $pay['longurl'];
                        redirect($redirect_url, 'refresh');
                    } else {
                        $this->session->set_flashdata('error', $pay);
                        redirect($this->path_to_default . 'wallet');
                    }
                } elseif ($payment_method['payment_name'] == 'Cashfree') {
                    if ($member['email_id'] != '' && $member['mobile_no'] != '') {
                        $deposit_data = array(
                            'member_id' => $this->member->front_member_id,
                            'deposit_amount' => $this->input->post('amount'),
                            'deposit_status' => '0',
                            'deposit_by' => $payment_method['payment_name'],
                            'entry_from' => '2',
                            'deposit_dateCreated' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('deposit', $deposit_data);
                        $order_id = $this->db->insert_id();
                        $curl = curl_init();

                        if ($payment_method['payment_status'] == 'Production')
                            $url = "https://api.cashfree.com/api/v1/order/create";
                        else
                            $url = "https://test.cashfree.com/api/v1/order/create";
                        $cf_request = array();
                        $cf_request["appId"] = $payment_method['mid'];
                        $cf_request["secretKey"] = $payment_method['mkey'];
                        $cf_request["orderId"] = $order_id;
                        $cf_request["orderAmount"] = $this->input->post('amount') / $payment_method['currency_point'];
                        $cf_request["orderNote"] = "Add money to wallet";
                        $cf_request["customerPhone"] = $member['mobile_no'];
                        $cf_request["customerName"] = $member['user_name'];
                        $cf_request["customerEmail"] = $member['email_id'];
                        $cf_request["returnUrl"] = base_url() . $this->path_to_default . 'wallet/cashfree_response';
                        $cf_request["notifyUrl"] = base_url() . $this->path_to_default . 'wallet/cashfree_notify_response';

                        $timeout = 10;

                        $request_string = "";
                        foreach ($cf_request as $key => $value) {
                            $request_string .= $key . '=' . rawurlencode($value) . '&';
                        }

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "$url?");
                        curl_setopt($ch, CURLOPT_POST, count($cf_request));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
                        $curl_result = curl_exec($ch);
                        curl_close($ch);
                        $jsonResponse = json_decode($curl_result);
                        
                        if ($jsonResponse->status == "OK") {
                            $paymentLink = $jsonResponse->paymentLink;
                            redirect($paymentLink, 'refresh');
                        } else {
                            $this->session->set_flashdata('error', $jsonResponse->reason);
                            redirect($this->path_to_default . 'wallet');
                        }
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('text_update_email_mobile'));
                        redirect($this->path_to_default . 'profile');
                    }
                } elseif ($payment_method['payment_name'] == 'Razorpay') {
                    if ($member['email_id'] != '' && $member['mobile_no'] != '') {
                        $api = new Api($payment_method['mid'], $payment_method['mkey']);
                        $deposit_data = array(
                            'member_id' => $this->member->front_member_id,
                            'deposit_amount' => $this->input->post('amount'),
                            'deposit_status' => '0',
                            'deposit_by' => $payment_method['payment_name'],
                            'entry_from' => '2',
                            'deposit_dateCreated' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('deposit', $deposit_data);
                        $order_id = $this->db->insert_id();
                        $orderData = [
                            'amount' => ($this->input->post('amount') / $payment_method['currency_point']) * 100,
                            'currency' => $payment_method['currency_code'],
                            'payment_capture' => 1,
                            "receipt" => $order_id
                        ];
                        $razorpayOrder = $api->order->create($orderData);
                        $razorpayOrderId = $razorpayOrder['id'];
                        echo '<form method="POST" name="f1"  action="https://api.razorpay.com/v1/checkout/embedded">
                        <input type="hidden" name="key_id" value="' . $payment_method['mid'] . '">
                        <input type="hidden" name="order_id" value="' . $razorpayOrderId . '">
                        <input type="hidden" name="name" value="' . $this->system->company_name . '">
                        <input type="hidden" name="image" value="' . base_url() . $this->company_image . "thumb/189x40_" . $this->system->company_logo . '">
                        <input type="hidden" name="prefill[name]" value="' . $member['first_name'] . '">
                        <input type="hidden" name="prefill[contact]" value="' . $member['mobile_no'] . '">
                        <input type="hidden" name="prefill[email]" value="' . $member['email_id'] . '">
                        <input type="hidden" name="callback_url" value="' . base_url() . $this->path_to_default . 'wallet/razorpay_response">
                        <input type="hidden" name="cancel_url" value="' . base_url() . $this->path_to_default . 'wallet/razorpay_cancel_response">
                        <button>Submit</button>
                      </form>
                      <script type="text/javascript">
                        document.f1.submit();
                        </script>';
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('text_update_email_mobile'));
                        redirect($this->path_to_default . 'profile');
                    }
                } elseif ($payment_method['payment_name'] == 'PayTm') {
                    $this->load->library('Paytm');
                    $posted = $_POST;
                    $user_id = $this->member->front_member_id;

                    $deposit_data = array(
                        'member_id' => $this->member->front_member_id,
                        'deposit_amount' => $this->input->post('amount'),
                        'deposit_status' => '0',
                        'deposit_by' => $payment_method['payment_name'],
                        'entry_from' => '2',
                        'deposit_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('deposit', $deposit_data);
                    $posted['ORDER_ID'] = $this->db->insert_id();

                    $posted["TXN_AMOUNT"] = $this->input->post('amount') / $payment_method['currency_point'];

                    $paytmParams = array();
                    $paytmParams['ORDER_ID'] = $posted['ORDER_ID'];
                    $paytmParams['TXN_AMOUNT'] = $posted['TXN_AMOUNT'];
                    $paytmParams["CUST_ID"] = $user_id;
                    $paytmParams["EMAIL"] = ''; //$member['email_id']

                    $paytmParams["MID"] = PAYTM_MERCHANT_MID;
                    $paytmParams["CHANNEL_ID"] = PAYTM_CHANNEL_ID;
                    $paytmParams["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
                    $paytmParams["CALLBACK_URL"] = PAYTM_CALLBACK_URL;
                    $paytmParams["INDUSTRY_TYPE_ID"] = PAYTM_INDUSTRY_TYPE_ID;
                    $paytmChecksum = $this->paytm->getChecksumFromArray($paytmParams, PAYTM_MERCHANT_KEY);
                    $paytmParams["CHECKSUMHASH"] = $paytmChecksum;
                    $transactionURL = PAYTM_TXN_URL;
                    $data = array();
                    $data['paytmParams'] = $paytmParams;
                    $data['transactionURL'] = $transactionURL;
                        echo "<html>
                    <head>
                    <title>Merchant Check Out Page</title>
                    </head>
                    <body>
                        <center><h1>Please do not refresh this page...</h1></center>
                            <form method='post' action='" . $transactionURL . "' name='f1'>
                    <table border='1'>
                    <tbody>";
                        foreach ($paytmParams as $name => $value) {
                            echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
                        }
                        echo "</tbody>
                </table>
                <script type='text/javascript'>
                    document.f1.submit();
                </script>
                </form>
                </body>
                </html>";
                } elseif ($payment_method['payment_name'] == 'PayU') {

                    $deposit_data = array(
                        'member_id' => $this->member->front_member_id,
                        'deposit_amount' => $this->input->post('amount'),
                        'deposit_status' => '0',
                        'deposit_by' => $payment_method['payment_name'],
                        'entry_from' => '2',
                        'deposit_dateCreated' => date('Y-m-d H:i:s')
                    );   
                    $this->db->insert('deposit', $deposit_data);
                    
                    $id = $this->db->insert_id();

                    $fields['key'] = $payment_method['mkey'];
                    $fields['txnid'] = substr(hash('sha256', mt_rand() . microtime()), 0, 20);;
                    $fields['amount'] = sprintf("%.2f",$this->input->post('amount'));
                    $fields['productinfo'] = $this->system->company_name . ' Wallet Balance';
                    $fields['firstname'] = $member['user_name'];
                    $fields['email'] = $member['email_id'];
                    $fields['phone'] = $member['mobile_no'];                   
                    $fields['surl'] = base_url() . $this->path_to_default . 'wallet/payu_response';
                    $fields['furl'] = base_url() . $this->path_to_default . 'wallet/payu_fail_response';
                    $fields['udf1'] = $id;                   
                    
                    $hash_string = $fields['key'].'|'.$fields['txnid'].'|'.sprintf("%.2f",$this->input->post('amount')).'|'.$fields['productinfo'].'|'.$fields['firstname'].'|'.$fields['email'].'|'.$fields['udf1'].'||||||||||'.$payment_method['wname'];            

                    $fields['hash'] = strtolower(hash('sha512', $hash_string));

                    if ($payment_method['payment_status'] == 'Production'){
                        $url = "https://secure.payu.in/_payment";
                        $fields['service_provider'] = 'payu_paisa';
                    } else {
                        $url = "https://sandboxsecure.payu.in/_payment";
                        $fields['service_provider'] = '';
                    }

                    echo "<html>\n";
                    echo "<body onLoad=\"document.forms['payU_form'].submit();\">\n";
                    echo "<form method=\"post\" name=\"payU_form\" ";
                    echo "action=\"" . $url . "\">\n";

                    foreach ($fields as $name => $value) {
                        echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
                    }
                    
                    echo "</form>\n
                        <script type='text/javascript'>
                            document.payU_form.submit();
                        </script>";
                    echo "</body></html>\n";
                                     
                } elseif ($payment_method['payment_name'] == 'Tron') {  
                                                
                            $deposit_data = array(
                                'member_id' => $this->member->front_member_id,
                                'deposit_amount' => $this->input->post('amount'),                        
                                'deposit_status' => '0',
                                'deposit_by' => $payment_method['payment_name'],
                                'entry_from' => '2',
                                'deposit_dateCreated' => date('Y-m-d H:i:s')
                            );
                            $this->db->insert('deposit', $deposit_data);
                            $data['order_id'] = $this->db->insert_id();
                
                            // gateway
                            ini_set('display_errors', 1);
                            ini_set('display_startup_errors', 1);
                            error_reporting(E_ALL);

                            include ('./tron/vendor/autoload.php');                                                
                            
                            if($payment_method['payment_status'] == 'Test') {
                                $tron_api_url = 'https://api.shasta.trongrid.io';
                            } else {
                                $tron_api_url = 'https://api.trongrid.io';
                            }
                
                            $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider($tron_api_url);
                            $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider($tron_api_url);
                            $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider($tron_api_url);
                        
                            try {                            
                                $tron = new Tron($fullNode, $solidityNode, $eventServer, null, null);                                                    
                            } catch (\IEXBase\TronAPI\Exception\TronException $e) {                                                                        
                                $this->session->set_flashdata('error', $e->getMessage()); //'Money not added, some error accured');
                                redirect($this->path_to_default . 'wallet');                
                            }
                
                            try {

                                $account = $tron->createAccount(); 
            
                                $data['wallet_address'] = $account->getAddress(true);
                                $data['address_hex']    = $account->getAddress();
                                $data['private_key']    = $account->getPrivateKey();
                                $data['public_key']     = $account->getPublicKey();
                                
                                $update_deposit = array(
                                    'wallet_address' => $data['wallet_address'],
                                    'address_hex' => $data['address_hex'],                        
                                    'private_key' => $data['private_key'],
                                    'public_key' => $data['public_key'],                                
                                );

                                $this->db->where('deposit_id',$data['order_id']);
                                $this->db->update('deposit', $update_deposit); 
                                                               
                                $data['tron_qr'] = 'true';
                                $data['payment_method'] = $this->input->post('payment_method');

                                $this->load->view($this->path_to_view_default . 'tron_qr', $data);

                            } catch (\IEXBase\TronAPI\Exception\TronException $e) {                                                 
                                $this->session->set_flashdata('error', $e->getMessage()); //'Money not added, some error accured');
                                redirect($this->path_to_default . 'wallet');
                            }                                                                           
                }                
            }
        } else {
            $this->load->view($this->path_to_view_default . 'add_money', $data);
        }
    }    

    function tron_qr() {
        if ($this->input->post('add_money') == $this->lang->line('text_add_money')) {
            // gateway
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            include ('./tron/vendor/autoload.php');
                               
            if($payment_method['payment_status'] == 'Test') {
                $tron_api_url = 'https://api.shasta.trongrid.io';
            } else {
                $tron_api_url = 'https://api.trongrid.io';
            }

            $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider($tron_api_url);
            $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider($tron_api_url);
            $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider($tron_api_url);
            
                try {                            
                    $tron = new Tron($fullNode, $solidityNode, $eventServer, null, null);                                                    
                } catch (\IEXBase\TronAPI\Exception\TronException $e) {                                                                        
                    $this->session->set_flashdata('error', $e->getMessage()); //'Money not added, some error accured');
                    redirect($this->path_to_default . 'wallet');                
                }
    
                try {
                    $payment_method = $this->wallet->getAddMoneyMethod($this->input->post('payment_method'));

                    $this->db->where('deposit_id',$_POST['order_id']);
                    $deposit_data = $this->db->get('deposit')->row_array();

                    $owner_address = $deposit_data['wallet_address']; 	
                    $priv = $deposit_data['private_key'];
                    $receiver = $payment_method['mid'];
                    $contract_address = $payment_method['mkey'];
                    $amount = $deposit_data['amount'];

                    $tron->setAddress($deposit_data['wallet_address']);
                    $tron->setPrivateKey($deposit_data['private_key']);

                    $trx_balance = $tron->getBalance(null, true);    
                    $trx_balance = sprintf("%.6f",$trx_balance);
                   
                    $db_amount = sprintf("%.6f",$amount);

                    if($trx_balance > 0 && $trx_balance >= $db_amount){                                                
                        $account = $tron->send( $receiver, (float)$amount); 
                        
                        if($account['result'] == 1){
                            $deposit_update_data = array(
                                'bank_transection_no' => $account['txid'],                                
                            );
                            
                            $this->db->where('deposit_id', $_POST['order_id']);
                            $this->db->update('deposit', $deposit_update_data);

                            sleep('10');

                            $transaction_detail = $tron->getTransaction($account['txid']); 
                            
                            $tron_pay_status = $transaction_detail['ret'][0]['contractRet'];                        
                            $transaction_receiver_address = $transaction_detail['raw_data']['contract'][0]['parameter']['value']['to_address'];            
                            $value = $transaction_detail['raw_data']['contract'][0]['parameter']['value']['amount'];            
                                    
                            if($tron_pay_status == 'SUCCESS' && $transaction_receiver_address == $tron->toHex($receiver) && $value == ($amount * 1000000)) {
                                $this->load->library('user_agent');
                                $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                                $ip = $this->input->ip_address();
                                
                                $deposit_update_data = array(                                
                                    'deposit_status' => '1'
                                );
                                
                                $this->db->where('deposit_id', $_POST['order_id']);
                                $this->db->update('deposit', $deposit_update_data);

                                $member = $this->account->getMemberDetail($this->member->front_member_id);

                                $join_money = $member['join_money'] + $amount;
                                
                                $acc_data = array(
                                    'member_id' => $this->member->front_member_id,
                                    'pubg_id' => $member['pubg_id'],
                                    'deposit' => $amount,
                                    'withdraw' => 0,
                                    'join_money' => $join_money,
                                    'win_money' => $member['wallet_balance'],
                                    'note' => 'Add Money to Join Wallet',
                                    'note_id' => '0',
                                    'entry_from' => '2',
                                    'ip_detail' => $ip,
                                    'browser' => $browser,
                                    'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                                );
                                $this->db->insert('accountstatement', $acc_data);
                                
                                $upd_data = array(
                                    'join_money' => $join_money);

                                $this->db->where('member_id', $member['member_id']);
                                $this->db->update('member', $upd_data);

                                $this->session->set_flashdata('success', $this->lang->line('text_succ_money_add'));
                                redirect($this->path_to_default . 'wallet');
                            } 
                        } 
                    } 

                    // fail
                        $update_deposit = array(
                            'deposit_status' => '2',                                                          
                        );
    
                        $this->db->where('deposit_id',$_POST['order_id']);
                        $this->db->update('deposit', $update_deposit);

                        $this->session->set_flashdata('error', $this->lang->line('text_err_money_add')); //'Money not added, some error accured');
                        redirect($this->path_to_default . 'wallet');                    
                                        
                } catch (\IEXBase\TronAPI\Exception\TronException $e) {                                                 
                    $this->session->set_flashdata('error', $e->getMessage()); //'Money not added, some error accured');
                    redirect($this->path_to_default . 'wallet');
                }
        } 
        // else {
        //     $update_deposit = array(
        //         'deposit_status' => '2',                                                          
        //     );

        //     $order_id = $this->uri->segment('4');

        //     if($order_id != '') {
        //         $this->db->where('deposit_id',$order_id);
        //         $this->db->update('deposit', $update_deposit);
        //     }

        //     $this->session->set_flashdata('error', $this->lang->line('text_err_money_add')); //'Money not added, some error accured');
        //     redirect($this->path_to_default . 'wallet');
        // } 
        else {
            $data['tron_qr'] = 'true';

            $data['wallet_address'] = 'TRicFfS1m6MtDaWBsmhpMarB6KdkJE6z23';
            $data['address_hex']    = '41acbf2ec7fe15deb1f53e2176ff130cfae890fd7a';
            $data['private_key']    = 'edd96352c09202a96de4a2ee0c6d76258b6b443dcdcb06569a610409c3bd4076';
            $data['public_key']     = '04198813351254ad030aad9a243d695a4e8afc7ba11fb4759a99e3d95775e9a4bc7134672eaa73e1fc723472de52d0d65d57d604c8a459751c9b2043f32764a190';
            $data['payment_method'] = '9';
            $data['order_id'] = '14';

            $this->load->view($this->path_to_view_default . 'tron_qr', $data);
        }        
    }

    function payu_fail_response() {
        $payment_method = $this->wallet->getAddMoneyMethodByName('PayU');

        $status = $this->input->post("status");
        $firstname = $this->input->post("firstname");
        $amount = $this->input->post("amount");
        $txnid = $this->input->post("txnid");
        $posted_hash = $this->input->post("hash");
        $key = $this->input->post("key");
        $productinfo = $this->input->post("productinfo");
        $email = $this->input->post("email");
        $udf1 = $this->input->post("udf1");
        $salt = $payment_method['wname'];
        
        $deposit_id = $udf1;

        if ($this->input->post("additionalCharges")) {
            $additionalCharges = $this->input->post("additionalCharges");
            $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'||||||||||'.$udf1.'|'. $email.'|'.$firstname.'|'.$productinfo.'|'. $amount.'|'. $txnid.'|'.$key;
        } else {            
            $retHashSeq = $salt.'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        }
        
        $hash = hash("sha512", $retHashSeq);
            
        if ($hash != $posted_hash) {
            $this->session->set_flashdata('error', 'Invalid Transaction. Please try again');
            redirect($this->path_to_default . 'wallet');            
        } else {

            $deposit_data = array(                    
                'bank_transection_no' => $txnid,
                'deposit_status' => '2',
            );

            $this->db->where('deposit_id', $deposit_id);
            $this->db->update('deposit', $deposit_data);

            redirect($this->path_to_default . 'wallet');                        
        }
    }

    function payu_response() {
        $payment_method = $this->wallet->getAddMoneyMethodByName('PayU');
        
        $status = $this->input->post("status");
        $firstname = $this->input->post("firstname");
        $amount = $this->input->post("amount");
        $txnid = $this->input->post("txnid");
        $posted_hash = $this->input->post("hash");
        $key = $this->input->post("key");
        $productinfo = $this->input->post("productinfo");
        $email = $this->input->post("email");
        $udf1 = $this->input->post("udf1");
        $payuMoneyId = $this->input->post("payuMoneyId");
        $salt = $payment_method['wname'];

        $deposit_id = $udf1;
        
        if ($this->input->post("additionalCharges")) {
            $additionalCharges = $this->input->post("additionalCharges");
            $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'||||||||||'.$udf1.'|'. $email.'|'.$firstname.'|'.$productinfo.'|'. $amount.'|'. $txnid.'|'.$key;
        } else {            
            $retHashSeq = $salt.'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        }

        $hash = hash("sha512", $retHashSeq);
            
        if ($hash != $posted_hash) {
            $this->session->set_flashdata('error', 'Invalid Transaction. Please try again');
            redirect($this->path_to_default . 'wallet');            
        } else {
                $this->db->select("*");
                $this->db->where("deposit_id", $deposit_id);
                $query = $this->db->get('deposit');
                $data = $query->row_array();

            if($status == 'success') {                

                if ($data['deposit_status'] == '0') {

                    $deposit_data = array(                    
                        'bank_transection_no' => $txnid,
                        'deposit_status' => '1',
                    );

                    $this->db->where('deposit_id', $data['deposit_id']);
                    $this->db->update('deposit', $deposit_data);

                    $member = $this->account->getMemberDetail($data['member_id']);
                    $join_money = $member['join_money'] + $amount;

                    $this->load->library('user_agent');
                    $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                    $ip = $this->input->ip_address();

                    $acc_data = array(
                        'member_id' => $data['member_id'],
                        'pubg_id' => $member['pubg_id'],
                        'deposit' => $amount,
                        'withdraw' => 0,
                        'join_money' => $join_money,
                        'win_money' => $member['wallet_balance'],
                        'note' => 'Add Money to Join Wallet',
                        'note_id' => '0',
                        'entry_from' => '2',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('accountstatement', $acc_data);

                    $upd_data = array(
                        'join_money' => $join_money);
                    $this->db->where('member_id', $member['member_id']);
                    $this->db->update('member', $upd_data);

                    $this->session->set_flashdata('success', $this->lang->line('text_succ_money_add'));
                    redirect($this->path_to_default . 'wallet');
                } else {
                    $this->session->set_flashdata('error', $this->lang->line('text_err_balance_already_add'));
                    redirect($this->path_to_default . 'wallet');
                }
            } else {

                $deposit_data = array(                    
                    'bank_transection_no' => $txnid,
                    'deposit_status' => '2',
                );

                $this->db->where('deposit_id', $data['deposit_id']);
                $this->db->update('deposit', $deposit_data);

                $this->session->set_flashdata('error', $_POST['error_Message']);
                redirect($this->path_to_default . 'wallet');
            }
        }
    }

    function paytm_response() {
        $this->load->library('user_agent');
        $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
        $ip = $this->input->ip_address();
        $this->load->library('Paytm');
        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";

        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        $isValidChecksum = $this->paytm->verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

        if ($isValidChecksum == "TRUE") {
            if ($this->input->post('STATUS') == "TXN_SUCCESS") {
                $this->db->select("*");
                $this->db->where("deposit_id", $this->input->post('ORDERID'));
                $query = $this->db->get('deposit');
                $data = $query->row_array();
                if ($data['deposit_status'] == '0' || $data['deposit_status'] == '2') {
                    $payment_method = $this->wallet->getAddMoneyMethodByName('PayTm');
                    $deposit_data = array(
                        'bank_transection_no' => $this->input->post('TXNID'),
                        'deposit_status' => '1',
                        'reason' => $this->input->post('RESPMSG'));
                    $this->db->where('deposit_id', $this->input->post('ORDERID'));
                    $this->db->update('deposit', $deposit_data);
                    $member = $this->account->getMemberDetail($this->member->front_member_id);
                    $join_money = $member['join_money'] + ($this->input->post('TXNAMOUNT') * $payment_method['currency_point']);

                    $acc_data = array(
                        'member_id' => $data['member_id'],
                        'pubg_id' => $member['pubg_id'],
                        'deposit' => $this->input->post('TXNAMOUNT') * $payment_method['currency_point'],
                        'withdraw' => 0,
                        'join_money' => $join_money,
                        'win_money' => $member['wallet_balance'],
                        'note' => 'Add Money to Join Wallet',
                        'note_id' => '0',
                        'entry_from' => '2',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('accountstatement', $acc_data);

                    $upd_data = array(
                        'join_money' => $join_money);
                    $this->db->where('member_id', $member['member_id']);
                    $this->db->update('member', $upd_data);

                    $this->session->set_flashdata('success', $this->lang->line('text_succ_money_add'));
                    redirect($this->path_to_default . 'wallet');
                } else {
                    $this->session->set_flashdata('error', $this->lang->line('text_err_balance_already_add'));
                    redirect($this->path_to_default . 'wallet');
                }
            } else {
                $this->session->set_flashdata('error', $this->input->post('RESPMSG')); //'Money not added, some error accured');
                redirect($this->path_to_default . 'wallet');
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('text_err_money_add'));
            redirect($this->path_to_default . 'wallet');
        }
    }

    function paypal_cancel() {
        $this->session->set_flashdata('error', $this->lang->line('text_err_payment_cancel'));
        redirect($this->path_to_default . 'wallet');
    }

    function paypal_ipn() {
        $this->session->set_flashdata('error', $this->lang->line('text_err_money_add'));
        redirect($this->path_to_default . 'wallet');
    }

    function paypal_success() {
        $this->load->library('user_agent');
        $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
        $ip = $this->input->ip_address();
        $this->db->select("*");
        $this->db->where("member_id", $this->member->front_member_id);
        $this->db->where("deposit_by", 'PayPal');
        $this->db->order_by('deposit_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('deposit');
        $data = $query->row_array();
        if ($data['deposit_status'] == '0' || $data['deposit_status'] == '2') {
            $deposit_data = array(
                'bank_transection_no' => $this->input->post('txn_id'),
                'deposit_status' => '1',);
            $this->db->where('deposit_id', $data['deposit_id']);
            $this->db->update('deposit', $deposit_data);

            $member = $this->account->getMemberDetail($this->member->front_member_id);
            $join_money = $member['join_money'] + $data['deposit_amount'];

            $acc_data = array(
                'member_id' => $data['member_id'],
                'pubg_id' => $member['pubg_id'],
                'deposit' => $data['deposit_amount'],
                'withdraw' => 0,
                'join_money' => $join_money,
                'win_money' => $member['wallet_balance'],
                'note' => 'Add Money to Join Wallet',
                'note_id' => '0',
                'entry_from' => '2',
                'ip_detail' => $ip,
                'browser' => $browser,
                'accountstatement_dateCreated' => date('Y-m-d H:i:s')
            );
            $this->db->insert('accountstatement', $acc_data);

            $upd_data = array(
                'join_money' => $join_money);
            $this->db->where('member_id', $member['member_id']);
            $this->db->update('member', $upd_data);

            $this->session->set_flashdata('success', $this->lang->line('text_succ_money_add'));
            redirect($this->path_to_default . 'wallet');
        } else {
            $this->session->set_flashdata('error', $this->lang->line('text_err_balance_already_add'));
            redirect($this->path_to_default . 'wallet');
        }
    }

    function paystack_callback() {
        $this->load->library('user_agent');
        $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
        $ip = $this->input->ip_address();
        $reference = $this->input->get('reference');
        $payment_method = $this->wallet->getAddMoneyMethodByName('PayStack');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $payment_method['mid']
            ),
        ));
        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);
        if ($response['status'] == true) {
            $order_id = $response['data']['metadata']['custom_fields']['order_id'];
            $this->db->select("*");
            $this->db->where("deposit_id", $order_id);
            $query = $this->db->get('deposit');
            $data = $query->row_array();
            if ($data['deposit_status'] == '0' || $data['deposit_status'] == '2') {
                $deposit_data = array(
                    'bank_transection_no' => $reference,
                    'deposit_status' => '1',);
                $this->db->where('deposit_id', $order_id);
                $this->db->update('deposit', $deposit_data);

                $member = $this->account->getMemberDetail($this->member->front_member_id);
                $join_money = $member['join_money'] + $data['deposit_amount'];

                $acc_data = array(
                    'member_id' => $data['member_id'],
                    'pubg_id' => $member['pubg_id'],
                    'deposit' => $data['deposit_amount'],
                    'withdraw' => 0,
                    'join_money' => $join_money,
                    'win_money' => $member['wallet_balance'],
                    'note' => 'Add Money to Join Wallet',
                    'note_id' => '0',
                    'entry_from' => '2',
                    'ip_detail' => $ip,
                    'browser' => $browser,
                    'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                );
                $this->db->insert('accountstatement', $acc_data);

                $upd_data = array(
                    'join_money' => $join_money);
                $this->db->where('member_id', $member['member_id']);
                $this->db->update('member', $upd_data);

                $this->session->set_flashdata('success', $this->lang->line('text_succ_money_add'));
                redirect($this->path_to_default . 'wallet');
            } else {
                $this->session->set_flashdata('error', $this->lang->line('text_err_balance_already_add'));
                redirect($this->path_to_default . 'wallet');
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('text_err_money_add'));
            redirect($this->path_to_default . 'wallet');
        }
    }

    function instamojo_response() {
        $this->load->library('user_agent');
        $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
        $ip = $this->input->ip_address();
        $this->load->library('instamojo');
        $this->load->helper('url');
        $status = $this->instamojo->status($this->input->get('payment_request_id'));
        $this->db->select("*");
        $this->db->where("member_id", $this->member->front_member_id);
        $this->db->where("deposit_by", 'Instamojo');
        $this->db->order_by('deposit_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('deposit');
        $data = $query->row_array();

        if ($status['status'] == 'Completed') {
            if ($data['deposit_status'] == '0' || $data['deposit_status'] == '2') {
                $deposit_data = array(
                    'bank_transection_no' => $this->input->get('payment_id'),
                    'deposit_status' => '1',);
                $this->db->where('deposit_id', $data['deposit_id']);
                $this->db->update('deposit', $deposit_data);

                $member = $this->account->getMemberDetail($this->member->front_member_id);
                $join_money = $member['join_money'] + $data['deposit_amount'];

                $acc_data = array(
                    'member_id' => $data['member_id'],
                    'pubg_id' => $member['pubg_id'],
                    'deposit' => $data['deposit_amount'],
                    'withdraw' => 0,
                    'join_money' => $join_money,
                    'win_money' => $member['wallet_balance'],
                    'note' => 'Add Money to Join Wallet',
                    'note_id' => '0',
                    'entry_from' => '2',
                    'ip_detail' => $ip,
                    'browser' => $browser,
                    'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                );
                $this->db->insert('accountstatement', $acc_data);

                $upd_data = array(
                    'join_money' => $join_money);
                $this->db->where('member_id', $member['member_id']);
                $this->db->update('member', $upd_data);

                $this->session->set_flashdata('success', $this->lang->line('text_succ_money_add'));
                redirect($this->path_to_default . 'wallet');
            } else {
                $this->session->set_flashdata('error', $this->lang->line('text_err_balance_already_add'));
                redirect($this->path_to_default . 'wallet');
            }
        } else {
            $deposit_data = array(
                'deposit_status' => '2',);
            $this->db->where('deposit_id', $data['deposit_id']);
            $this->db->update('deposit', $deposit_data);

            $this->session->set_flashdata('error', $this->lang->line('text_err_money_add'));
            redirect($this->path_to_default . 'wallet');
        }
    }

    function razorpay_cancel_response() {
        $this->session->set_flashdata('error', $this->lang->line('text_err_payment_cancel'));
        redirect($this->path_to_default . 'wallet');
    }

    function razorpay_response() {
        $this->load->library('user_agent');
        $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
        $ip = $this->input->ip_address();
        $payment_method = $this->wallet->getAddMoneyMethodByName('Razorpay');
        $api = new Api($payment_method['mid'], $payment_method['mkey']);
        $attributes = array(
            'razorpay_signature' => $this->input->post('razorpay_signature'),
            'razorpay_payment_id' => $this->input->post('razorpay_payment_id'),
            'razorpay_order_id' => $this->input->post('razorpay_order_id')
        );
        $order = $api->utility->verifyPaymentSignature($attributes);
        $payment = $api->payment->fetch($this->input->post('razorpay_payment_id'));

        $this->db->select("*");
        $this->db->where("member_id", $this->member->front_member_id);
        $this->db->where("deposit_by", 'Razorpay');
        $this->db->order_by('deposit_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('deposit');
        $data = $query->row_array();
        if ($data['deposit_status'] == '0' || $data['deposit_status'] == '2') {
            $deposit_data = array(
                'bank_transection_no' => $this->input->post('razorpay_payment_id'),
                'deposit_status' => '1',);
            $this->db->where('deposit_id', $data['deposit_id']);
            $this->db->update('deposit', $deposit_data);

            $member = $this->account->getMemberDetail($this->member->front_member_id);
            $join_money = $member['join_money'] + $data['deposit_amount'];

            $acc_data = array(
                'member_id' => $data['member_id'],
                'pubg_id' => $member['pubg_id'],
                'deposit' => $data['deposit_amount'],
                'withdraw' => 0,
                'join_money' => $join_money,
                'win_money' => $member['wallet_balance'],
                'note' => 'Add Money to Join Wallet',
                'note_id' => '0',
                'entry_from' => '2',
                'ip_detail' => $ip,
                'browser' => $browser,
                'accountstatement_dateCreated' => date('Y-m-d H:i:s')
            );
            $this->db->insert('accountstatement', $acc_data);

            $upd_data = array(
                'join_money' => $join_money);
            $this->db->where('member_id', $member['member_id']);
            $this->db->update('member', $upd_data);

            $this->session->set_flashdata('success', $this->lang->line('text_succ_money_add'));
            redirect($this->path_to_default . 'wallet');
        } else {
            $this->session->set_flashdata('error', $this->lang->line('text_err_balance_already_add'));
            redirect($this->path_to_default . 'wallet');
        }
    }

    function cashfree_response() {
        $this->load->library('user_agent');
        $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
        $ip = $this->input->ip_address();
        if ($this->input->post('txStatus') == 'SUCCESS') {
            $this->db->select("*");
            $this->db->where("deposit_id", $this->input->post('orderId'));
            $query = $this->db->get('deposit');
            $data = $query->row_array();
            if ($data['deposit_status'] == '0' || $data['deposit_status'] == '2') {
                $deposit_data = array(
                    'bank_transection_no' => $this->input->post('referenceId'),
                    'deposit_status' => '1',
                    'reason' => $this->input->post('txMsg'));
                $this->db->where('deposit_id', $this->input->post('orderId'));
                $this->db->update('deposit', $deposit_data);

                $member = $this->account->getMemberDetail($this->member->front_member_id);
                $join_money = $member['join_money'] + $data['deposit_amount'];

                $acc_data = array(
                    'member_id' => $data['member_id'],
                    'pubg_id' => $member['pubg_id'],
                    'deposit' => $data['deposit_amount'],
                    'withdraw' => 0,
                    'join_money' => $join_money,
                    'win_money' => $member['wallet_balance'],
                    'note' => 'Add Money to Join Wallet',
                    'note_id' => '0',
                    'entry_from' => '2',
                    'ip_detail' => $ip,
                    'browser' => $browser,
                    'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                );
                $this->db->insert('accountstatement', $acc_data);

                $upd_data = array(
                    'join_money' => $join_money);
                $this->db->where('member_id', $member['member_id']);
                $this->db->update('member', $upd_data);

                $this->session->set_flashdata('success', $this->lang->line('text_succ_money_add'));
                redirect($this->path_to_default . 'wallet');
            } else {
                $this->session->set_flashdata('error', $this->lang->line('text_err_balance_already_add'));
                redirect($this->path_to_default . 'wallet');
            }
        } else {
            $deposit_data = array(
                'deposit_status' => '2',
                'reason' => $this->input->post('txMsg'));
            $this->db->where('deposit_id', $this->input->post('orderId'));
            $this->session->set_flashdata('error', $this->input->post('txMsg')); //$this->lang->line('text_err_money_add'));
            redirect($this->path_to_default . 'wallet');
        }
    }

    function cashfree_notify_response() {
        $this->session->set_flashdata('error', $this->lang->line('text_err_money_add'));
        redirect($this->path_to_default . 'wallet');
    }

    function withdraw() {
        $data['withdraw_money'] = true;
        $data['title'] = $this->lang->line('text_withdraw_money');
        $data['breadcrumb_title'] = $this->lang->line('text_withdraw_money');
        $data['withdraw_methods'] = $this->wallet->getWithdrawMethod();
        if ($this->input->post('withdraw_money') == $this->lang->line('text_withdraw_money')) {
            $data['amount'] = $this->input->post('amount');
            $data['email'] = $this->input->post('email');
            $data['pyatmnumber'] = $this->input->post('pyatmnumber');
            $data['wallet_address'] = $this->input->post('wallet_address');
            $data['withdraw_method'] = $this->input->post('withdraw_method');
            $this->form_validation->set_rules('amount', 'lang:text_amount', 'required|numeric|greater_than_equal_to[' . $this->system->min_withdrawal . ']', array('required' => $this->lang->line('err_amount_req'), 'numeric' => $this->lang->line('err_amount_number')));
            $this->form_validation->set_rules('withdraw_method', 'lang:text_withdraw_method', 'required', array('required' => $this->lang->line('err_withdraw_method_req')));

            if ($this->input->post(str_replace(' ','_', $this->input->post('withdraw_method')) . '_field') == 'mobile no')
                $this->form_validation->set_rules('pyatmnumber', 'Mobile number', 'required|numeric|min_length[7]|max_length[15]', array('required' => $this->lang->line('err_mobile_no_req'), 'numeric' => $this->lang->line('err_mobile_no_number'), 'min_length' => $this->lang->line('err_mobile_no_min'), 'max_length' => $this->lang->line('err_mobile_no_max')));
            if ($this->input->post(str_replace(' ','_', $this->input->post('withdraw_method')) . '_field') == 'email')
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email', array('required' => $this->lang->line('err_email_req'), 'valid_email' => $this->lang->line('err_email_id_valid')));
            if ($this->input->post(str_replace(' ','_', $this->input->post('withdraw_method')) . '_field') == 'UPI ID')
                $this->form_validation->set_rules('upi', 'UPI ID', 'required');
            if ($this->input->post(str_replace(' ','_', $this->input->post('withdraw_method')) . '_field') == 'Wallet Address')
                $this->form_validation->set_rules('wallet_address', 'Wallet Address', 'required');
            if ($this->input->post(str_replace(' ','_', $this->input->post('withdraw_method')) . '_field') == 'Wallet Address')
                        $pyatmnumber = $this->input->post('wallet_address');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view($this->path_to_view_default . 'withdraw_money', $data);
            } else {
            
                $this->load->library('user_agent');
                $browser = $this->agent->platform() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
                $ip = $this->input->ip_address();
                $member = $this->account->getMemberDetail($this->member->front_member_id);

                if($member['wallet_balance'] < $this->system->min_require_balance_for_withdrawal) {
                    $this->session->set_flashdata('error', 'Wallet Balance shoulde be greater than '. $this->system->min_require_balance_for_withdrawal .' for withdraw.');
                    redirect($this->path_to_default . 'wallet/withdraw');
                }

                if ($member['wallet_balance'] >= $this->input->post('amount')) {
                    $wallet_balance = $member['wallet_balance'] - $this->input->post('amount');
                    if ($this->input->post(str_replace(' ','_', $this->input->post('withdraw_method')) . '_field') == 'mobile no')
                        $pyatmnumber = $this->input->post('pyatmnumber');
                    if ($this->input->post(str_replace(' ','_', $this->input->post('withdraw_method')) . '_field') == 'email')
                        $pyatmnumber = $this->input->post('email');
                    if ($this->input->post(str_replace(' ','_', $this->input->post('withdraw_method')) . '_field') == 'UPI ID')
                        $pyatmnumber = $this->input->post('upi');
                    if ($this->input->post(str_replace(' ','_', $this->input->post('withdraw_method')) . '_field') == 'Wallet Address')
                        $pyatmnumber = $this->input->post('wallet_address');

                    $acc_data = array(
                        'member_id' => $this->member->front_member_id,
                        'pubg_id' => $member['pubg_id'],
                        'from_mem_id' => 0,
                        'deposit' => 0,
                        'withdraw' => $this->input->post('amount'),
                        'join_money' => $member['join_money'],
                        'win_money' => $wallet_balance,
                        'pyatmnumber' => $pyatmnumber,
                        'withdraw_method' => $this->input->post('withdraw_method'),
                        'note' => 'Withdraw Money from Win Wallet',
                        'note_id' => '9',
                        'entry_from' => '2',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('accountstatement', $acc_data);

                    $data = array(
                        'wallet_balance' => $wallet_balance);
                    $this->db->where('member_id', $this->member->front_member_id);
                    $this->db->update('member', $data);
                    $this->session->set_flashdata('success', $this->lang->line('text_succ_withdraw'));
                    redirect($this->path_to_default . 'wallet');
                } else {
                    $this->session->set_flashdata('error', $this->lang->line('text_err_insufficient_wallet'));
                    redirect($this->path_to_default . 'wallet/withdraw');
                }
            }
        } else {
            $this->load->view($this->path_to_view_default . 'withdraw_money', $data);
        }
    }

    public function getPaymentDetails() {
        $payment_method_id = $this->uri->segment('4');
        $payment_method = $this->wallet->getAddMoneyMethod($payment_method_id);
        $data['payment_name'] = $payment_method['payment_name'];
        $data['currency_point'] = $payment_method['currency_point'];
        $data['currency_code'] = $payment_method['currency_code'];
        $data['currency_name'] = $payment_method['currency_name'];
        $data['currency_symbol'] = $payment_method['currency_symbol'];
        $data['payment_description'] = $payment_method['payment_description'];
        echo json_encode($data);
    }

    public function getWithdrawMethodails() {
        $this->db->select('withdraw_method.*,c.currency_name,c.currency_code,c.currency_symbol');
        $this->db->where("withdraw_method", $this->input->post('withdraw_method'));
        $this->db->where("withdraw_method_status", '1');
        $this->db->join("currency as c", 'c.currency_id = withdraw_method.withdraw_method_currency', 'LEFT');
        $query = $this->db->get('withdraw_method');
        $withdraw_method = $query->row_array();
        $data['currency_point'] = $withdraw_method['withdraw_method_currency_point'];
        $data['currency_code'] = $withdraw_method['currency_code'];
        $data['currency_name'] = $withdraw_method['currency_name'];
        $data['currency_symbol'] = $withdraw_method['currency_symbol'];
        echo json_encode($data);
    }

}

?>
