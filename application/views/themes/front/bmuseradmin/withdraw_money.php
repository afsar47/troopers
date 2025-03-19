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
        <?php $this->load->view($this->path_to_view_default . 'header'); ?>
    </head>
    <body>
        <?php $this->load->view($this->path_to_view_default . 'header_body'); ?>
        <div class="d-flex" id="wrapper">
            <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h3><?php echo $breadcrumb_title; ?></h3>
                    </div>                    
                    <div class="row d-flex" sy>

                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $breadcrumb_title; ?></strong></div>
                                <div class="card-body">
                                    <div class="text-danger mb-2"><strong><?php echo $this->lang->line('text_note'); ?> : </strong><?php echo $this->lang->line('text_withdraw_money_note'); ?></div>

                                    <form method="POST" enctype="multipart/form-data" action="<?php echo base_url() . $this->path_to_default ?>wallet/withdraw/" id="withdraw-form" >                                           
                                        <div class="row">
                                            <div class="form-group col-md-6 mobile_no-div d-none">
                                                <label for="pyatmnumber"><?php echo $this->lang->line('text_mobile_no'); ?><span class="required" aria-required="true"> * </span></label>
                                                <!--<label for="pyatmnumber"class="email-div d-none">Email<span class="required" aria-required="true"> * </span></label>-->
                                                <input type="text" class="form-control"  name="pyatmnumber" value="<?php if (isset($pyatmnumber)) echo $pyatmnumber; ?>">
                                                <?php echo form_error('pyatmnumber', '<em style="color:red">', '</em>'); ?>
                                            </div>
											<div class="form-group col-md-6 upi-div d-none">
                                                <label for="upi">UPI ID<span class="required" aria-required="true"> * </span></label>
                                                <!--<label for="pyatmnumber"class="email-div d-none">Email<span class="required" aria-required="true"> * </span></label>-->
                                                <input type="text" class="form-control"  name="upi" value="<?php if (isset($upi)) echo $upi; ?>">
                                                <?php echo form_error('upi', '<em style="color:red">', '</em>'); ?>
                                            </div>
                                            <div class="form-group col-md-6 wallet_address-div d-none">
                                                <label for="wallet_address"><?php echo $this->lang->line('text_wallet_address'); ?><span class="required" aria-required="true"> * </span></label>
                                               <input type="text" class="form-control"  name="wallet_address" value="<?php if (isset($wallet_address)) echo $wallet_address; ?>">
                                                <?php echo form_error('wallet_address', '<em style="color:red">', '</em>'); ?>
                                            </div>
                                            <div class="form-group col-md-6 email-div d-none">
                                                <label for="email"><?php echo $this->lang->line('text_email'); ?><span class="required" aria-required="true"> * </span></label>
                                                <input type="text" class="form-control"  name="email" value="<?php if (isset($email)) echo $email; ?>">
                                                <?php echo form_error('email', '<em style="color:red">', '</em>'); ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="amount"><?php echo $this->lang->line('text_amount') . '(<span style="">' . $this->functions->getPoint() . '</span>)'; ?><span class="required" aria-required="true"> * </span></label>
                                                <input id="amount" type="text" class="form-control" name="amount" onblur="change_withdraw();" value="<?php if (isset($amount)) echo $amount; ?>">
                                                <?php echo form_error('amount', '<em style="color:red">', '</em>'); ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label><?php echo $this->lang->line('text_withdraw_to'); ?> : </label>
                                                <?php
                                                $i = 0;
                                                foreach ($withdraw_methods as $method) {
                                                    ?>
                                                    <div class="custom-control custom-radio">
                                                        <input type="hidden" name="<?php echo str_replace(' ', '_', $method->withdraw_method) . '_field'; ?>"  id="<?php echo str_replace(' ', '_', $method->withdraw_method) . '_field'; ?>" value="<?php echo $method->withdraw_method_field; ?>">
                                                        <input onchange="change_withdraw()" <?php
                                                        if (isset($amount) && $withdraw_method == $method->withdraw_method)
                                                            echo 'checked';
                                                        elseif ($i == 0)
                                                            echo 'checked';
                                                        ?> id="<?php echo str_replace(' ', '_', $method->withdraw_method); ?>"  onchange="getcurrency();" name="withdraw_method" type="radio" class="custom-control-input" value="<?php echo $method->withdraw_method; ?>">
                                                        <label class="custom-control-label" for="<?php echo str_replace(' ', '_', $method->withdraw_method); ?>"><?php echo $method->withdraw_method; ?></label>
                                                    </div>
                                                    <?php
                                                    $i++;
                                                }
                                                ?>     
                                                <?php echo form_error('withdraw_method', '<em style="color:red">', '</em>'); ?>
                                            </div>                                                
                                        </div> 
                                        <div class="form-group bm_text_lightgreen d-none getcurrency_div"> <?php echo $this->lang->line('text_you_will_get'); ?> </div>

                                        <div class="form-group text-center">
                                            <input type="submit" value="<?php echo $this->lang->line('text_withdraw_money'); ?>" name="withdraw_money" class="btn btn-primary">                                                    
                                        </div> 
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
        <script>

            function change_withdraw() {
//                    $('input[type=radio][name=withdraw_method]').change(function () {
                $(".card-header strong").html("Withdraw to " + $('input[type=radio][name=withdraw_method]:checked').val());
                if ($('#' + $('input[type=radio][name=withdraw_method]:checked').attr('id') + '_field').val() == 'email') {
                    $('.mobile_no-div').addClass('d-none');
					$('.upi-div').addClass('d-none');
					$('.wallet_address-div').addClass('d-none');
                    $('.email-div').removeClass('d-none');
               } 
				if ($('#' + $('input[type=radio][name=withdraw_method]:checked').attr('id') + '_field').val() == 'mobile no'){					
                    $('.mobile_no-div').removeClass('d-none');
                    $('.email-div').addClass('d-none');
					$('.wallet_address-div').addClass('d-none');
				   	$('.upi-div').addClass('d-none');
                } 
				if ($('#' + $('input[type=radio][name=withdraw_method]:checked').attr('id') + '_field').val() == 'UPI ID'){
					$('.mobile_no-div').addClass('d-none');
                    $('.email-div').addClass('d-none');
					$('.wallet_address-div').addClass('d-none');
				   	$('.upi-div').removeClass('d-none');
				}
                if ($('#' + $('input[type=radio][name=withdraw_method]:checked').attr('id') + '_field').val() == 'Wallet Address'){
					$('.mobile_no-div').addClass('d-none');
                    $('.email-div').addClass('d-none');
				   	$('.upi-div').addClass('d-none');
                    $('.wallet_address-div').removeClass('d-none');
				}
                amount = $('#amount').val();
                withdraw_method = $('input[name="withdraw_method"]:checked').val();
                if (amount != '' && withdraw_method != '' && typeof withdraw_method !== 'undefined') {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url() . $this->path_to_default; ?>wallet/getWithdrawMethodails/",
                        data: {'withdraw_method': withdraw_method},
                        success: function funSuccess(response) {
                            obj = JSON.parse(response);
                            console.log(obj);
                            get_val = amount / obj.currency_point;
                            $('.getcurrency').html( obj.currency_symbol + ' ' + get_val);
                            $('.getcurrency_div').removeClass('d-none');
                        }
                    });
                } else {
                    $('.getcurrency_div').addClass('d-none');
                }
//                    });
            }
            $(document).ready(function () {
                change_withdraw();
//                $('input[type=radio][name=withdraw_method]').change();
//                $(".card-header strong").html("Withdraw to " + $('input[type=radio][name=withdraw_method]:checked').val());
                $("#withdraw-form").validate({
                    rules: {
                        'amount': {
                            required: true,
                            number: true,
                            min:<?php echo $this->system->min_withdrawal; ?>,
                        },
                        'pyatmnumber': {
                            required: function () {
                                if ($('#' + $('input[type=radio][name=withdraw_method]:checked').attr('id') + '_field').val() == 'mobile no') {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                            number: function () {
                                if ($('#' + $('input[type=radio][name=withdraw_method]:checked').attr('id') + '_field').val() == 'mobile no') {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                            minlength: 7,
                            maxlength: 15,
                        },
                        'email': {
                            required: function () {
                                if ($('#' + $('input[type=radio][name=withdraw_method]:checked').attr('id') + '_field').val() == 'email') {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                            email: function () {
                                if ($('#' + $('input[type=radio][name=withdraw_method]:checked').attr('id') + '_field').val() == 'email') {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                        },
                        'withdraw_method': {
                            required: true,
                        },
                    },
                    messages: {
                        'amount': {
                            required: "<?php echo $this->lang->line('err_amount_req'); ?>",
                            number: "<?php echo $this->lang->line('err_number'); ?>",
                        },
                        'pyatmnumber': {
                            required: "<?php echo $this->lang->line('err_mobile_no_req'); ?>",
                            minlength: "<?php echo $this->lang->line('err_mobile_no_min'); ?>",
                            maxlength: "<?php echo $this->lang->line('err_mobile_no_max'); ?>",
                        },
                        'email': {
                            required: "<?php echo $this->lang->line('err_email_id_req'); ?>",
                            email: "<?php echo $this->lang->line('err_email_id_valid'); ?>",
                        },
                        'withdraw_method': {
                            required: "<?php echo $this->lang->line('err_withdraw_method_req'); ?>",
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
            });
        </script>
    </body>
</html>