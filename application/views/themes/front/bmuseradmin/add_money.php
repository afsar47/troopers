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
                    <div class="row d-flex">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $breadcrumb_title; ?></strong></div>
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data" action="<?php echo base_url() . $this->path_to_default ?>wallet/addmoney/" id="addmoney-form" >                                           
                                        <div class="row">                                            
                                            <div class="form-group col-md-6">
                                                <label for="amount"><?php echo $this->lang->line('text_amount') . '(<span style="">' . $this->functions->getCurrencySymbol($this->system->currency) . '</span>)'; ?></label>
                                                <input id="amount" type="text" class="form-control" name="amount" onblur="getpoint();" value="<?php if (isset($amount)) echo $amount; ?>">
                                                <?php echo form_error('amount', '<em style="color:red">', '</em>'); ?>
                                            </div>       
                                        </div>  
                                        <div class="row">                                            
                                            <div class="form-group col-md-6">
                                                <label for="gender"><?php echo $this->lang->line('text_add_money'); ?> : </label><?php
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
                                        <div class="form-group d-none payment_description"></div>
                                        <div class="form-group text-center">
                                            <input type="submit" value="<?php echo $this->lang->line('text_add_money'); ?>" name="add_money" class="btn btn-primary">                                                    
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
                            if (obj.payment_name == 'PayStack')
                                $('.getpoint').html(obj.currency_symbol + ' ' + Math.floor(get_val));
                            else if (obj.payment_name == 'Tron')
                                $('.getpoint').html(obj.currency_symbol + ' ' + get_val.toFixed(2));
                            else
                                $('.getpoint').html(obj.currency_symbol + ' ' + get_val);

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