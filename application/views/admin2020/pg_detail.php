<!DOCTYPE html>
<?php
    if($this->session->userdata('site_lang') && in_array($this->session->userdata('site_lang'),json_decode($this->system->rtl_supported_language,true))) {
        $dir = 'rtl';
    } else {
        $dir = 'ltr';
    }
?>
<html dir='<?php echo $dir; ?>'>
    <head>
        <?php $this->load->view($this->path_to_view_admin . 'header'); ?>
    </head>
    <body>
        <?php $this->load->view($this->path_to_view_admin . 'header_body'); ?>
        <div class="d-flex" id="wrapper">
            <?php $this->load->view($this->path_to_view_admin . 'sidebar'); ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><?php echo $this->lang->line('text_payment_gateway_int'); ?></h1>
                    </div>
                    <?php if ($this->session->flashdata('notification')) { ?>
                        <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                            <span><?php echo $this->session->flashdata('notification'); ?></span>
                        </div>
                    <?php } ?>
                    <?php if ($this->session->flashdata('error')) { ?>
                        <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                            <span><?php echo $this->session->flashdata('error'); ?></span>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $pgdetail['payment_name'] . ' ' . $this->lang->line('text_integration'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <?php if ($pgdetail['payment_name'] == 'PayTm') { ?>
                                            <form method="POST" class="member_form" name="validate" id="validate" action="<?php echo base_url() . $this->path_to_view_admin ?>pgdetail">
                                                <input id="id" type="hidden" class="form-control" name="id" value="<?php
                                                if ($pgdetail['id']) {
                                                    echo $pgdetail['id'];
                                                } elseif (isset($id)) {
                                                    echo $id;
                                                }
                                                ?>" >
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="payment_status"><?php echo $this->lang->line('text_mode'); ?></label>
                                                        <div class="form-group col-md-12">
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Test" name="payment_status" type="radio" class="custom-control-input" value="Test" <?php
                                                                if (isset($payment_status) && $payment_status == 'Test') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Test') {
                                                                    echo 'checked';
                                                                }
                                                                ?>>&nbsp;
                                                                <label class="custom-control-label" for="Test"><?php echo $this->lang->line('text_test'); ?></label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Production" name="payment_status" type="radio" class="custom-control-input" value="Production" <?php
                                                                if (isset($payment_status) && $payment_status == 'Production') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Production') {
                                                                    echo 'checked';
                                                                }
                                                                ?> >&nbsp;
                                                                <label class="custom-control-label" for="Production"><?php echo $this->lang->line('text_production'); ?></label>
                                                            </div>
                                                        </div>
                                                        <?php echo form_error('payment_status', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="mid" id="mid_label"><?php echo $this->lang->line('text_merchant_id'); ?></label>
                                                        <input id="mid" type="text" class="form-control" name="mid" value="<?php
                                                        if ($pgdetail['mid']) {
                                                            echo $pgdetail['mid'];
                                                        } else if (isset($mid)) {
                                                            echo $mid;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mid', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="mkey"  id="mkey_label"><?php echo $this->lang->line('text_merchant_key'); ?></label>
                                                        <input id="mkey" type="text" class="form-control" name="mkey" value="<?php
                                                        if ($pgdetail['mkey']) {
                                                            echo $pgdetail['mkey'];
                                                        } else if (isset($mkey)) {
                                                            echo $mkey;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mkey', '<em style="color:red">', '</em>'); ?>
                                                    </div>                                                                                               
                                                    <div class="form-group col-md-6">
                                                        <label for="wname"><?php echo $this->lang->line('text_website'); ?></label>
                                                        <input id="wname" type="text" class="form-control" name="wname" value="<?php
                                                        if ($pgdetail['wname']) {
                                                            echo $pgdetail['wname'];
                                                        } else if (isset($wname)) {
                                                            echo $wname;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('wname', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                </div>  
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="itype"><?php echo $this->lang->line('text_industry_type'); ?></label>
                                                        <select class="form-control" name="itype" id="itype" >
                                                            <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                            <option value="Retail" <?php if (isset($pgdetail['itype']) && $pgdetail['itype'] == 'Retail') echo 'selected' ?>>Retail</option>
                                                        </select>
                                                        <?php echo form_error('itype', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency"><?php echo $this->lang->line('text_currency'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <select name="currency" id="currency" class="form-control">
                                                            <option value=''><?php echo $this->lang->line('text_select'); ?></option>
                                                            <?php foreach ($currency_data as $cur) { ?>
                                                                <option value=' <?php echo $cur->currency_id; ?>'  <?php if (isset($pgdetail['currency']) && $pgdetail['currency'] == $cur->currency_id) echo 'selected';else if (isset($currency) && $currency == $cur->currency_id) echo 'selected'; ?>> <?php echo $cur->currency_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error('currency', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency_point"><?php echo $this->lang->line('text_point'); ?> (<i class="fa fa-point"></i><?php echo $this->functions->getPoint(); ?>)<span class="required" aria-required="true"> * </span></label>
                                                        <input id="currency_point" type="text" class="form-control" name="currency_point" value="<?php
                                                        if ($pgdetail['currency_point']) {
                                                            echo $pgdetail['currency_point'];
                                                        } else if (isset($currency_point)) {
                                                            echo $currency_point;
                                                        }
                                                        ?>">                                                 
                                                               <?php echo form_error('currency_point', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                </div>  
                                                <div class="form-group text-center">
                                                    <input type="submit" value="<?php echo $this->lang->line('text_btn_update'); ?>" name="update_paytm" class="btn btn-primary " <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>     
                                                    <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin . 'pgdetail'; ?>"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
                                                </div>
                                            </form>
                                            <?php } elseif ($pgdetail['payment_name'] == 'PayU') { ?>
                                            <form method="POST" class="member_form" name="validate" id="validate10" action="<?php echo base_url() . $this->path_to_view_admin ?>pgdetail">
                                                <input id="id" type="hidden" class="form-control" name="id" value="<?php
                                                if ($pgdetail['id']) {
                                                    echo $pgdetail['id'];
                                                } elseif (isset($id)) {
                                                    echo $id;
                                                }
                                                ?>" >
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="payment_status"><?php echo $this->lang->line('text_mode'); ?></label>
                                                        <div class="form-group col-md-12">
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Test" name="payment_status" type="radio" class="custom-control-input" value="Test" <?php
                                                                if (isset($payment_status) && $payment_status == 'Test') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Test') {
                                                                    echo 'checked';
                                                                }
                                                                ?> disabled >&nbsp;
                                                                <label class="custom-control-label" for="Test"><?php echo $this->lang->line('text_test'); ?></label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Production" name="payment_status" type="radio" class="custom-control-input" value="Production" <?php
                                                                if (isset($payment_status) && $payment_status == 'Production') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Production') {
                                                                    echo 'checked';
                                                                }
                                                                ?> >&nbsp;
                                                                <label class="custom-control-label" for="Production"><?php echo $this->lang->line('text_production'); ?></label>
                                                            </div>
                                                        </div>
                                                        <?php echo form_error('payment_status', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <!-- <div class="form-group col-md-6">
                                                        <label for="mid" id="mid_label"><?php echo $this->lang->line('text_merchant_id'); ?></label>
                                                        <input id="mid" type="text" class="form-control" name="mid" value="<?php
                                                        if ($pgdetail['mid']) {
                                                            echo $pgdetail['mid'];
                                                        } else if (isset($mid)) {
                                                            echo $mid;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mid', '<em style="color:red">', '</em>'); ?>
                                                    </div> -->
                                                    <div class="form-group col-md-6">
                                                        <label for="mkey"  id="mkey_label"><?php echo $this->lang->line('text_merchant_key'); ?></label>
                                                        <input id="mkey" type="text" class="form-control" name="mkey" value="<?php
                                                        if ($pgdetail['mkey']) {
                                                            echo $pgdetail['mkey'];
                                                        } else if (isset($mkey)) {
                                                            echo $mkey;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mkey', '<em style="color:red">', '</em>'); ?>
                                                    </div>                                                                                               
                                                    <div class="form-group col-md-6">
                                                        <label for="wname"><?php echo $this->lang->line('text_salt'); ?></label>
                                                        <input id="wname" type="text" class="form-control" name="wname" value="<?php
                                                        if ($pgdetail['wname']) {
                                                            echo $pgdetail['wname'];
                                                        } else if (isset($wname)) {
                                                            echo $wname;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('wname', '<em style="color:red">', '</em>'); ?>
                                                    </div>                                                                                                   
                                                    <div class="form-group col-md-6">
                                                        <label for="currency"><?php echo $this->lang->line('text_currency'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <select name="currency" id="currency" class="form-control">
                                                            <option value=''><?php echo $this->lang->line('text_select'); ?></option>
                                                            <?php foreach ($currency_data as $cur) { ?>
                                                                <option value=' <?php echo $cur->currency_id; ?>'  <?php if (isset($pgdetail['currency']) && $pgdetail['currency'] == $cur->currency_id) echo 'selected';else if (isset($wname) && $wname == $cur->currency_id) echo 'selected'; ?>> <?php echo $cur->currency_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error('currency', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency_point"><?php echo $this->lang->line('text_point'); ?> (<i class="fa fa-point"></i><?php echo $this->functions->getPoint(); ?>)<span class="required" aria-required="true"> * </span></label>
                                                        <input id="currency_point" type="text" class="form-control" name="currency_point" value="<?php
                                                        if ($pgdetail['currency_point']) {
                                                            echo $pgdetail['currency_point'];
                                                        } else if (isset($currency_point)) {
                                                            echo $currency_point;
                                                        }
                                                        ?>">                                                 
                                                               <?php echo form_error('currency_point', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                </div>  
                                                <div class="form-group text-center">
                                                    <input type="submit" value="<?php echo $this->lang->line('text_btn_update'); ?>" name="update_payu" class="btn btn-primary " <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>     
                                                    <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin . 'pgdetail'; ?>"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
                                                </div>
                                            </form>
                                        <?php } elseif ($pgdetail['payment_name'] == 'PayPal') { ?>
                                            <form method="POST" class="member_form" name="validate" id="validate1" action="<?php echo base_url() . $this->path_to_view_admin ?>pgdetail">
                                                <input id="id" type="hidden" class="form-control" name="id" value="<?php
                                                if ($pgdetail['id']) {
                                                    echo $pgdetail['id'];
                                                } elseif (isset($id)) {
                                                    echo $id;
                                                }
                                                ?>" >
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="payment_status"><?php echo $this->lang->line('text_mode'); ?></label>
                                                        <div class="form-group col-md-12">
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Sandbox" name="payment_status" type="radio" class="custom-control-input" value="Sandbox" <?php
                                                                if (isset($payment_status) && $payment_status == 'Sandbox') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Sandbox') {
                                                                    echo 'checked';
                                                                }
                                                                ?> >&nbsp;
                                                                <label class="custom-control-label" for="Sandbox"><?php echo $this->lang->line('text_sandbox'); ?></label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Live" name="payment_status" type="radio" class="custom-control-input" value="Live" <?php
                                                                if (isset($payment_status) && $payment_status == 'Live') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Live') {
                                                                    echo 'checked';
                                                                }
                                                                ?>>&nbsp;
                                                                <label class="custom-control-label" for="Live"><?php echo $this->lang->line('text_live'); ?></label>
                                                            </div>
                                                        </div>
                                                        <?php echo form_error('payment_status', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="name"><?php echo $this->lang->line('text_email'); ?></label>
                                                        <input id="name" type="text" class="form-control" name="name" value="<?php
                                                        if ($pgdetail['name']) {
                                                            echo $pgdetail['name'];
                                                        } else if (isset($name)) {
                                                            echo $name;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('name', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="mid"><?php echo $this->lang->line('text_client_id'); ?></label>
                                                        <input id="mid" type="text" class="form-control" name="mid" value="<?php
                                                        if ($pgdetail['mid']) {
                                                            echo $pgdetail['mid'];
                                                        } else if (isset($mid)) {
                                                            echo $mid;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mid', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency"><?php echo $this->lang->line('text_currency'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <select name="currency" id="currency" class="form-control">
                                                            <option value=''><?php echo $this->lang->line('text_select'); ?></option>
                                                            <?php foreach ($currency_data as $cur) { ?>
                                                                <option value=' <?php echo $cur->currency_id; ?>'  <?php if (isset($pgdetail['currency']) && $pgdetail['currency'] == $cur->currency_id) echo 'selected';else if (isset($currency) && $currency == $cur->currency_id) echo 'selected'; ?>> <?php echo $cur->currency_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error('currency', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency_point"><?php echo $this->lang->line('text_point'); ?> (<i class="fa fa-point"></i><?php echo $this->functions->getPoint(); ?>)<span class="required" aria-required="true"> * </span></label>
                                                        <input id="currency_point" type="text" class="form-control" name="currency_point" value="<?php
                                                        if ($pgdetail['currency_point']) {
                                                            echo $pgdetail['currency_point'];
                                                        } else if (isset($currency_point)) {
                                                            echo $currency_point;
                                                        }
                                                        ?>">                                                 
                                                               <?php echo form_error('currency_point', '<em style="color:red">', '</em>'); ?>
                                                    </div>                                                    
                                                </div>
                                                <div class="form-group text-center">
                                                    <input type="submit" value="<?php echo $this->lang->line('text_btn_update'); ?>" name="update_paypal" class="btn btn-primary " <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                    <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>pgdetail/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
                                                </div>
                                            </form>
                                        <?php } elseif ($pgdetail['payment_name'] == 'Offline') {
                                            ?>
                                            <form method="POST" class="member_form" name="validate" id="validate2" action="<?php echo base_url() . $this->path_to_view_admin ?>pgdetail">
                                                <input id="id" type="hidden" class="form-control" name="id" value="<?php
                                                if ($pgdetail['id']) {
                                                    echo $pgdetail['id'];
                                                } elseif (isset($id)) {
                                                    echo $id;
                                                }
                                                ?>" >
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="payment_description"><?php echo $this->lang->line('text_payment_desc'); ?></label>
                                                        <textarea id="payment_description" type="text" class="form-control ckeditor" id="editor1" name="payment_description"><?php
                                                            if ($pgdetail['payment_description']) {
                                                                echo $pgdetail['payment_description'];
                                                            } else if (isset($payment_description)) {
                                                                echo $payment_description;
                                                            }
                                                            ?></textarea>
                                                        <?php echo form_error('payment_description', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency"><?php echo $this->lang->line('text_currency'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <select name="currency" id="currency" class="form-control">
                                                            <option value=''><?php echo $this->lang->line('text_select'); ?></option>
                                                            <?php foreach ($currency_data as $cur) { ?>
                                                                <option value=' <?php echo $cur->currency_id; ?>'  <?php if (isset($pgdetail['currency']) && $pgdetail['currency'] == $cur->currency_id) echo 'selected';else if (isset($currency) && $currency == $cur->currency_id) echo 'selected'; ?>> <?php echo $cur->currency_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error('currency', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency_point"><?php echo $this->lang->line('text_point'); ?> (<i class="fa fa-point"></i><?php echo $this->functions->getPoint(); ?>)<span class="required" aria-required="true"> * </span></label>
                                                        <input id="currency_point" type="text" class="form-control" name="currency_point" value="<?php
                                                        if ($pgdetail['currency_point']) {
                                                            echo $pgdetail['currency_point'];
                                                        } else if (isset($currency_point)) {
                                                            echo $currency_point;
                                                        }
                                                        ?>">                                                 
                                                               <?php echo form_error('currency_point', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group text-center">
                                                    <input type="submit" value="<?php echo $this->lang->line('text_btn_update'); ?>" name="update_offline" class="btn btn-primary " <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                    <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>pgdetail/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
                                                </div>
                                            </form>
                                            <?php
                                        } elseif ($pgdetail['payment_name'] == 'PayStack') {
                                            ?>
                                            <form method="POST" class="member_form" name="validate" id="validate3" action="<?php echo base_url() . $this->path_to_view_admin ?>pgdetail">
                                                <input id="id" type="hidden" class="form-control" name="id" value="<?php
                                                if ($pgdetail['id']) {
                                                    echo $pgdetail['id'];
                                                } elseif (isset($id)) {
                                                    echo $id;
                                                }
                                                ?>" >
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="payment_status"><?php echo $this->lang->line('text_mode'); ?></label>
                                                        <div class="form-group col-md-12">
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Test" name="payment_status" type="radio" class="custom-control-input" value="Test" <?php
                                                                if (isset($payment_status) && $payment_status == 'Test') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Test') {
                                                                    echo 'checked';
                                                                }
                                                                ?> >&nbsp;
                                                                <label class="custom-control-label" for="Test"><?php echo $this->lang->line('text_test'); ?></label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Production" name="payment_status" type="radio" class="custom-control-input" value="Production" <?php
                                                                if (isset($payment_status) && $payment_status == 'Production') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Production') {
                                                                    echo 'checked';
                                                                }
                                                                ?>>&nbsp;
                                                                <label class="custom-control-label" for="Production"><?php echo $this->lang->line('text_production'); ?></label>
                                                            </div>
                                                        </div>
                                                        <?php echo form_error('payment_status', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="mid"><?php echo $this->lang->line('text_secret_key'); ?></label>
                                                        <input id="mid" type="text" class="form-control" name="mid" value="<?php
                                                        if ($pgdetail['mid']) {
                                                            echo $pgdetail['mid'];
                                                        } else if (isset($mid)) {
                                                            echo $mid;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mid', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="mkey"><?php echo $this->lang->line('text_public_key'); ?></label>
                                                        <input id="mkey" type="text" class="form-control" name="mkey" value="<?php
                                                        if ($pgdetail['mkey']) {
                                                            echo $pgdetail['mkey'];
                                                        } else if (isset($mkey)) {
                                                            echo $mkey;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mkey', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency"><?php echo $this->lang->line('text_currency'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <select name="currency" id="currency" class="form-control">
                                                            <option value=''><?php echo $this->lang->line('text_select'); ?></option>
                                                            <?php foreach ($currency_data as $cur) { ?>
                                                                <option value=' <?php echo $cur->currency_id; ?>'  <?php if (isset($pgdetail['currency']) && $pgdetail['currency'] == $cur->currency_id) echo 'selected';else if (isset($currency) && $currency == $cur->currency_id) echo 'selected'; ?>> <?php echo $cur->currency_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error('currency', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency_point"><?php echo $this->lang->line('text_point'); ?> (<i class="fa fa-point"></i><?php echo $this->functions->getPoint(); ?>)<span class="required" aria-required="true"> * </span></label>
                                                        <input id="currency_point" type="text" class="form-control" name="currency_point" value="<?php
                                                        if ($pgdetail['currency_point']) {
                                                            echo $pgdetail['currency_point'];
                                                        } else if (isset($currency_point)) {
                                                            echo $currency_point;
                                                        }
                                                        ?>">                                                 
                                                               <?php echo form_error('currency_point', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group text-center">
                                                    <input type="submit"  value="<?php echo $this->lang->line('text_btn_update'); ?>" name="update_paystack" class="btn btn-primary " <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                    <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>pgdetail/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
                                                </div>
                                            </form>
                                            <?php
                                        } elseif ($pgdetail['payment_name'] == 'Instamojo') {
                                            ?>
                                            <form method="POST" class="member_form" name="validate" id="validate4" action="<?php echo base_url() . $this->path_to_view_admin ?>pgdetail">
                                                <input id="id" type="hidden" class="form-control" name="id" value="<?php
                                                if ($pgdetail['id']) {
                                                    echo $pgdetail['id'];
                                                } elseif (isset($id)) {
                                                    echo $id;
                                                }
                                                ?>" >
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="payment_status"><?php echo $this->lang->line('text_mode'); ?></label>
                                                        <div class="form-group col-md-12">
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Test" name="payment_status" type="radio" class="custom-control-input" value="Test" <?php
                                                                if (isset($payment_status) && $payment_status == 'Test') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Test') {
                                                                    echo 'checked';
                                                                }
                                                                ?> >&nbsp;
                                                                <label class="custom-control-label" for="Test"><?php echo $this->lang->line('text_test'); ?></label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Production" name="payment_status" type="radio" class="custom-control-input" value="Production" <?php
                                                                if (isset($payment_status) && $payment_status == 'Production') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Production') {
                                                                    echo 'checked';
                                                                }
                                                                ?>>&nbsp;
                                                                <label class="custom-control-label" for="Production"><?php echo $this->lang->line('text_production'); ?></label>
                                                            </div>
                                                        </div>
                                                        <?php echo form_error('payment_status', '<em style="color:red">', '</em>'); ?>
                                                    </div>   
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="name"><?php echo $this->lang->line('text_api_key'); ?></label>
                                                        <input id="name" type="text" class="form-control" name="name" value="<?php
                                                        if ($pgdetail['name']) {
                                                            echo $pgdetail['name'];
                                                        } else if (isset($name)) {
                                                            echo $name;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mid', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="wname"><?php echo $this->lang->line('text_auth_token'); ?></label>
                                                        <input id="wname" type="text" class="form-control" name="wname" value="<?php
                                                        if ($pgdetail['wname']) {
                                                            echo $pgdetail['wname'];
                                                        } else if (isset($wname)) {
                                                            echo $wname;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mid', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="mid"><?php echo $this->lang->line('text_client_id'); ?></label>
                                                        <input id="mid" type="text" class="form-control" name="mid" value="<?php
                                                        if ($pgdetail['mid']) {
                                                            echo $pgdetail['mid'];
                                                        } else if (isset($mid)) {
                                                            echo $mid;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mid', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">

                                                        <label for="mkey"><?php echo $this->lang->line('text_client_secret'); ?></label>
                                                        <input id="mkey" type="text" class="form-control" name="mkey" value="<?php
                                                        if ($pgdetail['mkey']) {
                                                            echo $pgdetail['mkey'];
                                                        } else if (isset($mkey)) {
                                                            echo $mkey;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mkey', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency"><?php echo $this->lang->line('text_currency'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <select name="currency" id="currency" class="form-control">
                                                            <option value=''><?php echo $this->lang->line('text_select'); ?></option>
                                                            <?php foreach ($currency_data as $cur) { ?>
                                                                <option value=' <?php echo $cur->currency_id; ?>'  <?php if (isset($pgdetail['currency']) && $pgdetail['currency'] == $cur->currency_id) echo 'selected';else if (isset($currency) && $currency == $cur->currency_id) echo 'selected'; ?>> <?php echo $cur->currency_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error('currency', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency_point"><?php echo $this->lang->line('text_point'); ?> (<i class="fa fa-point"></i><?php echo $this->functions->getPoint(); ?>)<span class="required" aria-required="true"> * </span></label>
                                                        <input id="currency_point" type="text" class="form-control" name="currency_point" value="<?php
                                                        if ($pgdetail['currency_point']) {
                                                            echo $pgdetail['currency_point'];
                                                        } else if (isset($currency_point)) {
                                                            echo $currency_point;
                                                        }
                                                        ?>">                                                 
                                                               <?php echo form_error('currency_point', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group text-center">
                                                    <input type="submit" value="<?php echo $this->lang->line('text_btn_update'); ?>" name="update_instamojo" class="btn btn-primary " <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                    <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>pgdetail/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
                                                </div>
                                            </form>
                                            <?php
                                        } elseif ($pgdetail['payment_name'] == 'Razorpay') {
                                            ?>
                                            <form method="POST" class="member_form" name="validate" id="validate5" action="<?php echo base_url() . $this->path_to_view_admin ?>pgdetail">
                                                <input id="id" type="hidden" class="form-control" name="id" value="<?php
                                                if ($pgdetail['id']) {
                                                    echo $pgdetail['id'];
                                                } elseif (isset($id)) {
                                                    echo $id;
                                                }
                                                ?>" >
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="payment_status"><?php echo $this->lang->line('text_mode'); ?></label>
                                                        <div class="form-group col-md-12">
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Test" name="payment_status" type="radio" class="custom-control-input" value="Test" <?php
                                                                if (isset($payment_status) && $payment_status == 'Test') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Test') {
                                                                    echo 'checked';
                                                                }
                                                                ?> >&nbsp;
                                                                <label class="custom-control-label" for="Test"><?php echo $this->lang->line('text_test'); ?></label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Live" name="payment_status" type="radio" class="custom-control-input" value="Live" <?php
                                                                if (isset($payment_status) && $payment_status == 'Live') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Live') {
                                                                    echo 'checked';
                                                                }
                                                                ?>>&nbsp;
                                                                <label class="custom-control-label" for="Live"><?php echo $this->lang->line('text_live'); ?></label>
                                                            </div>
                                                        </div>
                                                        <?php echo form_error('payment_status', '<em style="color:red">', '</em>'); ?>
                                                    </div>                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="mkey"><?php echo $this->lang->line('text_api_secret'); ?></label>
                                                        <input id="mkey" type="text" class="form-control" name="mkey" value="<?php
                                                        if ($pgdetail['mkey']) {
                                                            echo $pgdetail['mkey'];
                                                        } else if (isset($mkey)) {
                                                            echo $mkey;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mkey', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="mid"><?php echo $this->lang->line('text_key_id'); ?></label>
                                                        <input id="mid" type="text" class="form-control" name="mid" value="<?php
                                                        if ($pgdetail['mid']) {
                                                            echo $pgdetail['mid'];
                                                        } else if (isset($mid)) {
                                                            echo $mid;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mid', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency"><?php echo $this->lang->line('text_currency'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <select name="currency" id="currency" class="form-control">
                                                            <option value=''><?php echo $this->lang->line('text_select'); ?></option>
                                                            <?php foreach ($currency_data as $cur) { ?>
                                                                <option value=' <?php echo $cur->currency_id; ?>'  <?php if (isset($pgdetail['currency']) && $pgdetail['currency'] == $cur->currency_id) echo 'selected';else if (isset($currency) && $currency == $cur->currency_id) echo 'selected'; ?>> <?php echo $cur->currency_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error('currency', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency_point"><?php echo $this->lang->line('text_point'); ?> (<i class="fa fa-point"></i><?php echo $this->functions->getPoint(); ?>)<span class="required" aria-required="true"> * </span></label>
                                                        <input id="currency_point" type="text" class="form-control" name="currency_point" value="<?php
                                                        if ($pgdetail['currency_point']) {
                                                            echo $pgdetail['currency_point'];
                                                        } else if (isset($currency_point)) {
                                                            echo $currency_point;
                                                        }
                                                        ?>">                                                 
                                                               <?php echo form_error('currency_point', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group text-center">
                                                    <input type="submit"  value="<?php echo $this->lang->line('text_btn_update'); ?>" name="update_razorpay" class="btn btn-primary " <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                    <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>pgdetail/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
                                                </div>
                                            </form>
                                            <?php
                                        } elseif ($pgdetail['payment_name'] == 'Cashfree') {
                                            ?>
                                            <form method="POST" class="member_form" name="validate" id="validate6" action="<?php echo base_url() . $this->path_to_view_admin ?>pgdetail">
                                                <input id="id" type="hidden" class="form-control" name="id" value="<?php
                                                if ($pgdetail['id']) {
                                                    echo $pgdetail['id'];
                                                } elseif (isset($id)) {
                                                    echo $id;
                                                }
                                                ?>" >
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="payment_status"><?php echo $this->lang->line('text_mode'); ?></label>
                                                        <div class="form-group col-md-12">
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Test" name="payment_status" type="radio" class="custom-control-input" value="Test" <?php
                                                                if (isset($payment_status) && $payment_status == 'Test') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Test') {
                                                                    echo 'checked';
                                                                }
                                                                ?> >&nbsp;
                                                                <label class="custom-control-label" for="Test"><?php echo $this->lang->line('text_test'); ?></label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Production" name="payment_status" type="radio" class="custom-control-input" value="Production" <?php
                                                                if (isset($payment_status) && $payment_status == 'Production') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Production') {
                                                                    echo 'checked';
                                                                }
                                                                ?>>&nbsp;
                                                                <label class="custom-control-label" for="Production"><?php echo $this->lang->line('text_production'); ?></label>
                                                            </div>
                                                        </div>
                                                        <?php echo form_error('payment_status', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="mid"><?php echo $this->lang->line('text_app_key'); ?></label>
                                                        <input id="mid" type="text" class="form-control" name="mid" value="<?php
                                                        if ($pgdetail['mid']) {
                                                            echo $pgdetail['mid'];
                                                        } else if (isset($mid)) {
                                                            echo $mid;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mid', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="mkey"><?php echo $this->lang->line('text_secret_key'); ?></label>
                                                        <input id="mkey" type="text" class="form-control" name="mkey" value="<?php
                                                        if ($pgdetail['mkey']) {
                                                            echo $pgdetail['mkey'];
                                                        } else if (isset($mkey)) {
                                                            echo $mkey;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mkey', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency"><?php echo $this->lang->line('text_currency'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <select name="currency" id="currency" class="form-control">
                                                            <option value=''><?php echo $this->lang->line('text_select'); ?></option>
                                                            <?php foreach ($currency_data as $cur) { ?>
                                                                <option value=' <?php echo $cur->currency_id; ?>'  <?php if (isset($pgdetail['currency']) && $pgdetail['currency'] == $cur->currency_id) echo 'selected';else if (isset($currency) && $currency == $cur->currency_id) echo 'selected'; ?>> <?php echo $cur->currency_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error('currency', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency_point"><?php echo $this->lang->line('text_point'); ?> (<i class="fa fa-point"></i><?php echo $this->functions->getPoint(); ?>)<span class="required" aria-required="true"> * </span></label>
                                                        <input id="currency_point" type="text" class="form-control" name="currency_point" value="<?php
                                                        if ($pgdetail['currency_point']) {
                                                            echo $pgdetail['currency_point'];
                                                        } else if (isset($currency_point)) {
                                                            echo $currency_point;
                                                        }
                                                        ?>">                                                 
                                                               <?php echo form_error('currency_point', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group text-center">
                                                    <input type="submit" value="<?php echo $this->lang->line('text_btn_update'); ?>" name="update_cashfree" class="btn btn-primary " <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                    <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>pgdetail/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
                                                </div>
                                            </form>
                                            <?php
                                        } elseif ($pgdetail['payment_name'] == "Google Pay") {
                                            ?>
                                            <form method="POST" class="member_form" name="validate" id="validate7" action="<?php echo base_url() . $this->path_to_view_admin ?>pgdetail">
                                                <input id="id" type="hidden" class="form-control" name="id" value="<?php
                                                if ($pgdetail['id']) {
                                                    echo $pgdetail['id'];
                                                } elseif (isset($id)) {
                                                    echo $id;
                                                }
                                                ?>" >
                                                <div class="row">                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="mid"><?php echo $this->lang->line('text_upi_id'); ?></label>
                                                        <input id="mid" type="text" class="form-control" name="mid" value="<?php
                                                        if ($pgdetail['mid']) {
                                                            echo $pgdetail['mid'];
                                                        } else if (isset($mid)) {
                                                            echo $mid;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mid', '<em style="color:red">', '</em>'); ?>
                                                    </div>  
                                                    <div class="form-group col-md-6">
                                                        <label for="currency"><?php echo $this->lang->line('text_currency'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <select name="currency" id="currency" class="form-control">
                                                            <option value=''><?php echo $this->lang->line('text_select'); ?></option>
                                                            <?php foreach ($currency_data as $cur) { ?>
                                                                <option value=' <?php echo $cur->currency_id; ?>'  <?php if (isset($pgdetail['currency']) && $pgdetail['currency'] == $cur->currency_id) echo 'selected';else if (isset($currency) && $currency == $cur->currency_id) echo 'selected'; ?>> <?php echo $cur->currency_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error('currency', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency_point"><?php echo $this->lang->line('text_point'); ?> (<i class="fa fa-point"></i><?php echo $this->functions->getPoint(); ?>)<span class="required" aria-required="true"> * </span></label>
                                                        <input id="currency_point" type="text" class="form-control" name="currency_point" value="<?php
                                                        if ($pgdetail['currency_point']) {
                                                            echo $pgdetail['currency_point'];
                                                        } else if (isset($currency_point)) {
                                                            echo $currency_point;
                                                        }
                                                        ?>">                                                 
                                                               <?php echo form_error('currency_point', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group text-center">
                                                    <input type="submit" value="<?php echo $this->lang->line('text_btn_update'); ?>" name="update_googlepay" class="btn btn-primary " <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                    <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>pgdetail/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
                                                </div>
                                            </form>
                                            <?php
                                        } elseif ($pgdetail['payment_name'] == 'Tron') {
                                            ?>
                                            <form method="POST" class="member_form" name="validate" id="validate8" action="<?php echo base_url() . $this->path_to_view_admin ?>pgdetail">
                                                <input id="id" type="hidden" class="form-control" name="id" value="<?php
                                                if ($pgdetail['id']) {
                                                    echo $pgdetail['id'];
                                                } elseif (isset($id)) {
                                                    echo $id;
                                                }
                                                ?>" >
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="payment_status"><?php echo $this->lang->line('text_mode'); ?></label>
                                                        <div class="form-group col-md-12">
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Test" name="payment_status" type="radio" class="custom-control-input" value="Test" <?php
                                                                if (isset($payment_status) && $payment_status == 'Test') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Test') {
                                                                    echo 'checked';
                                                                }
                                                                ?> >&nbsp;
                                                                <label class="custom-control-label" for="Test"><?php echo $this->lang->line('text_test'); ?></label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input id="Production" name="payment_status" type="radio" class="custom-control-input" value="Production" <?php
                                                                if (isset($payment_status) && $payment_status == 'Production') {
                                                                    echo 'checked';
                                                                } elseif (isset($pgdetail['payment_status']) && $pgdetail['payment_status'] == 'Production') {
                                                                    echo 'checked';
                                                                }
                                                                ?>>&nbsp;
                                                                <label class="custom-control-label" for="Production"><?php echo $this->lang->line('text_production'); ?></label>
                                                            </div>
                                                        </div>
                                                        <?php echo form_error('payment_status', '<em style="color:red">', '</em>'); ?>
                                                    </div>   
                                                </div>
                                                <div class="row">                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="wname"><?php echo $this->lang->line('text_abi_key'); ?></label>
                                                        <input id="wname" type="text" class="form-control" name="wname" value='<?php
                                                        if ($pgdetail["wname"]) {
                                                            echo $pgdetail["wname"];
                                                        } else if (isset($wname)) {
                                                            echo $wname;
                                                        }
                                                        ?>'>
                                                               <?php echo form_error('wname', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="mid"><?php echo $this->lang->line('text_receiver_contract_address'); ?></label>
                                                        <input id="mid" type="text" class="form-control" name="mid" value="<?php
                                                        if ($pgdetail['mid']) {
                                                            echo $pgdetail['mid'];
                                                        } else if (isset($mid)) {
                                                            echo $mid;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mid', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">

                                                        <label for="mkey"><?php echo $this->lang->line('text_contract_address'); ?></label>
                                                        <input id="mkey" type="text" class="form-control" name="mkey" value="<?php
                                                        if ($pgdetail['mkey']) {
                                                            echo $pgdetail['mkey'];
                                                        } else if (isset($mkey)) {
                                                            echo $mkey;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('mkey', '<em style="color:red">', '</em>'); ?>
                                                    </div>                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="currency"><?php echo $this->lang->line('text_currency'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <select name="currency" id="currency" class="form-control">
                                                            <option value=''><?php echo $this->lang->line('text_select'); ?></option>
                                                            <?php foreach ($currency_data as $cur) { ?>
                                                                <option value=' <?php echo $cur->currency_id; ?>'  <?php if (isset($pgdetail['currency']) && $pgdetail['currency'] == $cur->currency_id) echo 'selected';else if (isset($currency) && $currency == $cur->currency_id) echo 'selected'; ?>> <?php echo $cur->currency_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error('currency', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="currency_point"><?php echo $this->lang->line('text_point'); ?> (<i class="fa fa-point"></i><?php echo $this->functions->getPoint(); ?>)<span class="required" aria-required="true"> * </span></label>
                                                        <input id="currency_point" type="text" class="form-control" name="currency_point" value="<?php
                                                        if ($pgdetail['currency_point']) {
                                                            echo $pgdetail['currency_point'];
                                                        } else if (isset($currency_point)) {
                                                            echo $currency_point;
                                                        }
                                                        ?>">                                                 
                                                               <?php echo form_error('currency_point', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group text-center">
                                                    <input type="submit" value="<?php echo $this->lang->line('text_btn_update'); ?>" name="update_tron" class="btn btn-primary " <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                    <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>pgdetail/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
                                                </div>
                                            </form>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>
        <script>
            $("#validate").validate({
                rules: {
                    mid: {
                        required: true,
                    },
                    mkey: {
                        required: true,
                    },
                    wname: {
                        required: true,
                    },
                    itype: {
                        required: true,
                    },
                    payment_status: {
                        required: true,
                    },
                    currency: {
                        required: true,
                    },
                    currency_point: {
                        required: true,
                        number: true,
                        digits: true
                    },
                },
                messages: {
                    mid: {
                        required: '<?php echo $this->lang->line('err_mid_req'); ?>',
                    },
                    mkey: {
                        required: '<?php echo $this->lang->line('err_mkey_req'); ?>',
                    },
                    wname: {
                        required: '<?php echo $this->lang->line('err_wname_req'); ?>',
                    },
                    itype: {
                        required: '<?php echo $this->lang->line('err_itype_req'); ?>',
                    },
                    payment_status: {
                        required: '<?php echo $this->lang->line('err_payment_status_req'); ?>',
                    },
                    currency: {
                        required: '<?php echo $this->lang->line('err_currency_req'); ?>',
                    },
                    currency_point: {
                        required: '<?php echo $this->lang->line('err_point_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        digits: '<?php echo $this->lang->line('err_digits'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#validate1").validate({
                rules: {
                    mid: {
                        required: true,
                    },
                    payment_status: {
                        required: true,
                    },
                    name: {
                        required: true,
                    },
                    currency: {
                        required: true,
                    },
                    currency_point: {
                        required: true,
                        number: true,
                        digits: true
                    },
                },
                messages: {
                    mid: {
                        required: '<?php echo $this->lang->line('err_cid_req'); ?>',
                    },
                    payment_status: {
                        required: '<?php echo $this->lang->line('err_payment_status_req'); ?>',
                    },
                    name: {
                        required: '<?php echo $this->lang->line('err_email_id_req'); ?>',
                    },
                    currency: {
                        required: '<?php echo $this->lang->line('err_currency_req'); ?>',
                    },
                    currency_point: {
                        required: '<?php echo $this->lang->line('err_point_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        digits: '<?php echo $this->lang->line('err_digits'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#validate2").validate({
                rules: {
                    payment_description: {
                        required: function (textarea) {
                            CKEDITOR.instances[textarea.id].updateElement();
                            var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                            return editorcontent.length === 0;
                        }
                    },
                    currency: {
                        required: true,
                    },
                    currency_point: {
                        required: true,
                        number: true,
                        digits: true
                    },
                },
                messages: {
                    payment_description: {
                        required: '<?php echo $this->lang->line('err_payment_desc_req'); ?>',
                    },
                    currency: {
                        required: '<?php echo $this->lang->line('err_currency_req'); ?>',
                    },
                    currency_point: {
                        required: '<?php echo $this->lang->line('err_point_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        digits: '<?php echo $this->lang->line('err_digits'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#validate3").validate({
                rules: {
                    payment_status: {
                        required: true,
                    },
                    mid: {
                        required: true,
                    },
                    mkey: {
                        required: true,
                    },
                    currency: {
                        required: true,
                    },
                    currency_point: {
                        required: true,
                        number: true,
                        digits: true
                    },
                },
                messages: {
                    payment_status: {
                        required: '<?php echo $this->lang->line('err_payment_status_req'); ?>',
                    },
                    mid: {
                        required: '<?php echo $this->lang->line('err_secret_key_req'); ?>',
                    },
                    mkey: {
                        required: '<?php echo $this->lang->line('err_public_key_req'); ?>',
                    },
                    currency: {
                        required: '<?php echo $this->lang->line('err_currency_req'); ?>',
                    },
                    currency_point: {
                        required: '<?php echo $this->lang->line('err_point_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        digits: '<?php echo $this->lang->line('err_digits'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#validate4").validate({
                rules: {
                    payment_status: {
                        required: true,
                    },
                    name: {
                        required: true,
                    },
                    wname: {
                        required: true,
                    },
                    mid: {
                        required: true,
                    },
                    mkey: {
                        required: true,
                    },
                    currency: {
                        required: true,
                    },
                    currency_point: {
                        required: true,
                        number: true,
                        digits: true
                    },
                },
                messages: {
                    payment_status: {
                        required: '<?php echo $this->lang->line('err_payment_status_req'); ?>',
                    },
                    name: {
                        required: '<?php echo $this->lang->line('err_api_key_req'); ?>',
                    },
                    wname: {
                        required: '<?php echo $this->lang->line('err_auth_token_req'); ?>',
                    },
                    mid: {
                        required: '<?php echo $this->lang->line('err_cid_req'); ?>',
                    },
                    mkey: {
                        required: '<?php echo $this->lang->line('err_c_secret_req'); ?>',
                    },
                    currency: {
                        required: '<?php echo $this->lang->line('err_currency_req'); ?>',
                    },
                    currency_point: {
                        required: '<?php echo $this->lang->line('err_point_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        digits: '<?php echo $this->lang->line('err_digits'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#validate5").validate({
                rules: {
                    payment_status: {
                        required: true,
                    },
                    mid: {
                        required: true,
                    },
                    mkey: {
                        required: true,
                    },
                    currency: {
                        required: true,
                    },
                    currency_point: {
                        required: true,
                        number: true,
                        digits: true
                    },
                },
                messages: {
                    payment_status: {
                        required: '<?php echo $this->lang->line('err_payment_status_req'); ?>',
                    },
                    mid: {
                        required: '<?php echo $this->lang->line('err_key_id_req'); ?>',
                    },
                    mkey: {
                        required: '<?php echo $this->lang->line('err_api_secret_req'); ?>',
                    },
                    currency: {
                        required: '<?php echo $this->lang->line('err_currency_req'); ?>',
                    },
                    currency_point: {
                        required: '<?php echo $this->lang->line('err_point_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        digits: '<?php echo $this->lang->line('err_digits'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#validate6").validate({
                rules: {
                    payment_status: {
                        required: true,
                    },
                    mid: {
                        required: true,
                    },
                    mkey: {
                        required: true,
                    },
                    currency: {
                        required: true,
                    },
                    currency_point: {
                        required: true,
                        number: true,
                        digits: true
                    },
                },
                messages: {
                    payment_status: {
                        required: '<?php echo $this->lang->line('err_payment_status_req'); ?>',
                    },
                    mid: {
                        required: '<?php echo $this->lang->line('err_app_id_req'); ?>',
                    },
                    mkey: {
                        required: '<?php echo $this->lang->line('err_secret_key_req'); ?>',
                    },
                    currency: {
                        required: '<?php echo $this->lang->line('err_currency_req'); ?>',
                    },
                    currency_point: {
                        required: '<?php echo $this->lang->line('err_point_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        digits: '<?php echo $this->lang->line('err_digits'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#validate7").validate({
                rules: {
                    payment_status: {
                        required: true,
                    },
                    mid: {
                        required: true,
                    },
                    currency: {
                        required: true,
                    },
                    currency_point: {
                        required: true,
                        number: true,
                        digits: true
                    },
                },
                messages: {
                    payment_status: {
                        required: '<?php echo $this->lang->line('err_payment_status_req'); ?>',
                    },
                    mid: {
                        required: '<?php echo $this->lang->line('err_upi_id_req'); ?>',
                    },
                    currency: {
                        required: '<?php echo $this->lang->line('err_currency_req'); ?>',
                    },
                    currency_point: {
                        required: '<?php echo $this->lang->line('err_point_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        digits: '<?php echo $this->lang->line('err_digits'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#validate8").validate({
                rules: {
                    payment_status: {
                        required: true,
                    },                    
                    wname: {
                        required: true,
                    },
                    mid: {
                        required: true,
                    },
                    mkey: {
                        required: true,
                    },                    
                    currency: {
                        required: true,
                    },
                    currency_point: {
                        required: true,
                        number: true,                        
                    },
                },
                messages: {
                    payment_status: {
                        required: '<?php echo $this->lang->line('err_payment_status_req'); ?>',
                    },                    
                    wname: {
                        required: '<?php echo $this->lang->line('err_abi_key_req'); ?>',
                    },
                    mid: {
                        required: '<?php echo $this->lang->line('err_receiver_contract_address_req'); ?>',
                    },
                    mkey: {
                        required: '<?php echo $this->lang->line('err_contract_address_req'); ?>',
                    },                   
                    currency: {
                        required: '<?php echo $this->lang->line('err_currency_req'); ?>',
                    },
                    currency_point: {
                        required: '<?php echo $this->lang->line('err_point_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',                        
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#validate10").validate({
                rules: {
                    // mid: {
                    //     required: true,
                    // },
                    mkey: {
                        required: true,
                    },
                    wname: {
                        required: true,
                    },                
                    payment_status: {
                        required: true,
                    },
                    currency: {
                        required: true,
                    },
                    currency_point: {
                        required: true,
                        number: true,
                        digits: true
                    },
                },                
                messages: {
                    // mid: {
                    //     required: '<?php echo $this->lang->line('err_mid_req'); ?>',
                    // },
                    mkey: {
                        required: '<?php echo $this->lang->line('err_mkey_req'); ?>',
                    },
                    wname: {
                        required: '<?php echo $this->lang->line('err_salt_req'); ?>',
                    },             
                    payment_status: {
                        required: '<?php echo $this->lang->line('err_payment_status_req'); ?>',                        
                    },
                    currency: {
                        required: '<?php echo $this->lang->line('err_currency_req'); ?>',
                    },
                    currency_point: {
                        required: '<?php echo $this->lang->line('err_point_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        digits: '<?php echo $this->lang->line('err_digits'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
        </script>
    </body>
</html>