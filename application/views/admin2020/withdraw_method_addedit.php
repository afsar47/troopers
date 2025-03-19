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
                        <h1 class="h2"><?php echo $this->lang->line('text_withdraw_method'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>withdraw/method">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_withdraw_method'); ?></strong></div>
                                <div class="card-body">
                                    <form method="POST" id="validate" enctype="multipart/form-data" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>withdraw/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>withdraw/edit<?php } ?>">                                                                                                                                 
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="withdraw_method"><?php echo $this->lang->line('text_withdraw_method'); ?><span class="required" aria-required="true"> * </span></label>
                                                <input id="withdraw_method" type="text" class="form-control" name="withdraw_method" value="<?php if (isset($withdraw_method)) echo $withdraw_method;elseif (isset($withdraw_method_detail['withdraw_method'])) echo $withdraw_method_detail['withdraw_method'] ?>">                                                 
                                                <?php echo form_error('withdraw_method', '<em style="color:red">', '</em>'); ?>                                                
                                                <input type="hidden" name="withdraw_method_id"  value="<?php echo (isset($withdraw_method_detail['withdraw_method_id'])) ? $withdraw_method_detail['withdraw_method_id'] : ''; ?>" class="form-control-file">                                                   
                                            </div>     
                                            <div class="form-group col-md-6">
                                                <label for="withdraw_method_field"><?php echo $this->lang->line('text_withdraw_method_field'); ?><span class="required" aria-required="true"> * </span></label>
                                                <div>
                                                    <div class="custom-control custom-radio">
                                                        <input id="mobile_no" name="withdraw_method_field" type="radio" class="custom-control-input" value="mobile no" <?php
                                                        if (isset($withdraw_method_field) && $withdraw_method_field == 'mobile no') {
                                                            echo 'checked';
                                                        } elseif (isset($withdraw_method_detail['withdraw_method_field']) && $withdraw_method_detail['withdraw_method_field'] == 'mobile no') {
                                                            echo 'checked';
                                                        }
                                                        ?>>&nbsp;
                                                        <label class="custom-control-label" for="mobile_no"><?php echo $this->lang->line('text_mobile_no'); ?></label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input id="email" name="withdraw_method_field" type="radio" class="custom-control-input" value="email" <?php
                                                        if (isset($withdraw_method_field) && $withdraw_method_field == 'email') {
                                                            echo 'checked';
                                                        } elseif (isset($withdraw_method_detail['withdraw_method_field']) && $withdraw_method_detail['withdraw_method_field'] == 'email') {
                                                            echo 'checked';
                                                        }
                                                        ?> >&nbsp;
                                                        <label class="custom-control-label" for="email"><?php echo $this->lang->line('text_email'); ?></label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input id="UPI ID" name="withdraw_method_field" type="radio" class="custom-control-input" value="UPI ID" <?php
                                                        if (isset($withdraw_method_field) && $withdraw_method_field == 'UPI ID') {
                                                            echo 'checked';
                                                        } elseif (isset($withdraw_method_detail['withdraw_method_field']) && $withdraw_method_detail['withdraw_method_field'] == 'UPI ID') {
                                                            echo 'checked';
                                                        }
                                                        ?> >&nbsp;
                                                        <label class="custom-control-label" for="UPI ID"><?php echo $this->lang->line('text_upi_id'); ?></label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input id="<?php echo $this->lang->line('text_wallet_address'); ?>" name="withdraw_method_field" type="radio" class="custom-control-input" value="Wallet Address" <?php
                                                        if (isset($withdraw_method_field) && $withdraw_method_field == 'Wallet Address') {
                                                            echo 'checked';
                                                        } elseif (isset($withdraw_method_detail['withdraw_method_field']) && $withdraw_method_detail['withdraw_method_field'] == 'Wallet Address') {
                                                            echo 'checked';
                                                        }
                                                        ?> >&nbsp;
                                                        <label class="custom-control-label" for="<?php echo $this->lang->line('text_wallet_address'); ?>"><?php echo $this->lang->line('text_wallet_address'); ?></label>
                                                    </div>
                                                </div>
                                                <?php echo form_error('withdraw_method_field', '<em style="color:red">', '</em>'); ?>                                                
                                            </div>   
                                            <div class="form-group col-md-6">
                                                <label for="withdraw_method_currency"><?php echo $this->lang->line('text_currency'); ?><span class="required" aria-required="true"> * </span></label>
                                                <select name="withdraw_method_currency" id="withdraw_method_currency" class="form-control">
                                                    <option value=''><?php echo $this->lang->line('text_select'); ?></option>
                                                    <?php foreach ($currency_data as $cur) { ?>
                                                        <option value=' <?php echo $cur->currency_id; ?>'  <?php if (isset($withdraw_method_detail['withdraw_method_currency']) && $withdraw_method_detail['withdraw_method_currency'] == $cur->currency_id) echo 'selected';else if (isset($withdraw_method_currency) && $withdraw_method_currency == $cur->currency_id) echo 'selected'; ?>> <?php echo $cur->currency_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error('withdraw_method_currency', '<em style="color:red">', '</em>'); ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="withdraw_method_currency_point"><?php echo $this->lang->line('text_point'); ?> (<i class="fa fa-point"></i><?php echo $this->functions->getPoint(); ?>)<span class="required" aria-required="true"> * </span></label>
                                                <input id="withdraw_method_currency_point" type="text" class="form-control" name="withdraw_method_currency_point" value="<?php
                                                if (isset($withdraw_method_detail['withdraw_method_currency_point'])) {
                                                    echo $withdraw_method_detail['withdraw_method_currency_point'];
                                                } else if (isset($withdraw_method_currency_point)) {
                                                    echo $withdraw_method_currency_point;
                                                }
                                                ?>">                                                 
                                                       <?php echo form_error('withdraw_method_currency_point', '<em style="color:red">', '</em>'); ?>
                                            </div>
                                        </div>                                         
                                        <div class="form-group text-center">
                                            <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary " <?php
                                            if ($this->system->demo_user == 1 && isset($withdraw_method_detail) && $withdraw_method_detail['withdraw_method_id'] <= 3) {
                                                echo 'disabled';
                                            }
                                            ?> > 
                                            <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>withdraw/method" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                    
                                        </div>
                                    </form>
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
            $(document).ready(function () {
                $("#validate").validate({
                    rules: {
                        'withdraw_method': {
                            required: true,
                        },
                        'withdraw_method_field': {
                            required: true,
                        },
                        withdraw_method_currency: {
                            required: true,
                        },
                        withdraw_method_currency_point: {
                            required: true,
                            number: true,
                            digits: true
                        },
                    },
                    messages: {
                        'withdraw_method': {
                            required: '<?php echo $this->lang->line('err_withdraw_method_req'); ?>',
                        },
                        'withdraw_method_field': {
                            required: '<?php echo $this->lang->line('err_withdraw_method_field_req'); ?>',
                        },
                        withdraw_method_currency: {
                            required: '<?php echo $this->lang->line('err_currency_req'); ?>',
                        },
                        withdraw_method_currency_point: {
                            required: '<?php echo $this->lang->line('err_point_req'); ?>',
                            number: '<?php echo $this->lang->line('err_number'); ?>',
                            digits: '<?php echo $this->lang->line('err_digits'); ?>',
                        },
                    },
                    errorPlacement: function (error, element)
                    {
                        if (element.is(":file"))
                        {
                            error.insertAfter(element.parent());
                        }
                        if (element.is(":radio"))
                        {
                            error.insertAfter(element.parent().parent());
                        } else
                        {
                            error.insertAfter(element);
                        }
                    },
                });
            });
        </script>
    </body>
</html>