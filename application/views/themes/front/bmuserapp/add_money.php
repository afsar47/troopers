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
        <main class="bm-full-width bm-full-height">
            <div class="container-fluid">
                <div class="row d-flex">
                    <div class="col-xl-4 col-left">
                        <div class="bm-modal">
                            <div class="bm-mdl-header">
                                <a href="<?php echo base_url() . $this->path_to_default . 'wallet/'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a><h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="bm-mdl-center bm-full-height pb-6">
                                <div class="content-section">
                                    <div class="bm-content-listing">   
                                        <div class="profile-content text-black">
                                            <form method="POST" class="container profile-form mt-2" action="<?php echo base_url() . $this->path_to_default ?>wallet/addmoney/" id="addmoney-form" >
                                                <div class="form-group row">
                                                    <div class="col-12 mt-4">
                                                        <label for="amount"><?php echo $this->lang->line('text_amount') . '(' . $this->functions->getPoint() . ')'; ?></label>
                                                        <input type="text" id="amount" name="amount" class="form-control border-bottom rounded-0" onblur="getpoint();"  value="<?php if (isset($amount)) echo $amount; ?>">
                                                        <?php echo form_error('amount', '<em style="color:red">', '</em>'); ?>
                                                    </div>  
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-6">
                                                        <label for="gender"><?php echo $this->lang->line('text_add_money'); ?> : </label>
                                                        <?php
                                                        $i = 0;
                                                        foreach ($payment_methods as $method) {
                                                            ?>
                                                            <div class="custom-control custom-radio ml-5">
                                                                <input <?php
                                                                if (isset($payment_method) && $payment_method == $method->id)
                                                                    echo 'checked';
//                                                                elseif ($i == 0)
//                                                                    echo 'checked';
                                                                ?> id="<?php echo str_replace(' ', '_', $method->payment_name); ?>" onchange="getpoint();" name="payment_method" type="radio" class="custom-control-input" value="<?php echo $method->id; ?>">
                                                                <label class="custom-control-label" for="<?php echo str_replace(' ', '_', $method->payment_name); ?>"><?php echo $method->payment_name; ?></label>
                                                            </div>
                                                            <?php
                                                            $i++;
                                                        }
                                                        ?>     
                                                        <?php echo form_error('payment_method', '<em style="color:red">', '</em>'); ?>
                                                    </div> 
                                                </div>                                                                                              
                                                <div class="form-group col-md-12 text-lightgreen d-none point_note_div"><?php echo $this->lang->line('text_you_will_pay'); ?>  </div>

                                                <div class="form-group bm_text_lightgreen d-none getpoint"></div>
                                                <div class="form-group d-none payment_description"></div>
                                                
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                        <button type="submit" value="<?php echo $this->lang->line('text_add_money'); ?>" name="add_money" class="btn btn-block btn-green"><?php echo $this->lang->line('text_add_money'); ?></button>
                                                    </div>                                            
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>                       
                        </div>
                    </div>
                    <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
                </div>
            </div>            
        </main>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
        <script>
            
            function getpoint() {
                amount = $('#amount').val();
                payment_method = $('input[name="payment_method"]:checked').val();
                
                if (amount != '' && payment_method != '' && typeof payment_method !== 'undefined') {                    

                    $.ajax({
                        type: "GET",
                        url: "<?php echo base_url() . $this->path_to_default; ?>wallet/getPaymentDetails/" + payment_method,
                        success: function funSuccess(response) {
                            obj = JSON.parse(response);
                            console.log(obj);                            
                            get_val = amount / obj.currency_point;
                            
                            if (obj.payment_name == 'PayStack') {
                                $('.getpoint').html(obj.currency_symbol + ' ' + Math.floor(get_val));
                            } else if (obj.payment_name == 'Tron') {
                                $('.getpoint').html(obj.currency_symbol + ' ' + get_val.toFixed(2));
                            } else {
                                $('.getpoint').html(obj.currency_symbol + ' ' + get_val);
                            }

                            if(obj.payment_name == 'Offline') {
                                if(obj.payment_description != ''){
                                    $('.payment_description').html('<hr/><h6>Note:</h6>' + obj.payment_description);
                                    $('.payment_description').removeClass('d-none');
                                }
                            } else {
                                $('.payment_description').addClass('d-none');
                            }

                            $('.point_note_div').removeClass('d-none');
                        }
                    });
                } else {
                    $('.point_note_div').addClass('d-none');
                }
            }
            $(document).ready(function () {
                $("#addmoney-form").validate({
                    rules: {
                        'amount': {
                            required: true,
                            number: true,
                            min:<?php echo $this->system->min_addmoney; ?>,
                        },
                        'payment_method': {
                            required: true,
                        },                        
                    },
                    messages: {
                        'amount': {
                            required: "<?php echo $this->lang->line('err_amount_req'); ?>",
                            number: "<?php echo $this->lang->line('err_amount_number'); ?>",
                        },
                        'payment_method': {
                            required: "<?php echo $this->lang->line('err_payment_method_req'); ?>",
                        },                        
                    }, errorPlacement: function (error, element)
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