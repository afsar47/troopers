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
                        <h1 class="h2"><?php echo $this->lang->line('text_currency'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>currency/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_currency'); ?></strong></div>
                                <div class="card-body">
                                    <form method="POST" id="validate" enctype="multipart/form-data" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>currency/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>currency/edit<?php } ?>">                                                                                                                                 
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="currency_name"><?php echo $this->lang->line('text_currency_name'); ?><span class="required" aria-required="true"> * </span></label>
                                                <input id="currency_name" type="text" class="form-control" name="currency_name" value="<?php if (isset($currency_name)) echo $currency_name;elseif (isset($currency_detail['currency_name'])) echo $currency_detail['currency_name'] ?>">                                                 
                                                <?php echo form_error('currency_name', '<em style="color:red">', '</em>'); ?>                                                
                                                <input type="hidden" name="currency_id"  value="<?php echo (isset($currency_detail['currency_id'])) ? $currency_detail['currency_id'] : ''; ?>" class="form-control-file">                                                   
                                            </div> 
                                            <div class="form-group col-md-6">
                                                <label for="currency_code"><?php echo $this->lang->line('text_currency_code'); ?><span class="required" aria-required="true"> * </span></label>
                                                <input id="currency_code" type="text" class="form-control" name="currency_code" value="<?php if (isset($currency_code)) echo $currency_code;elseif (isset($currency_detail['currency_code'])) echo $currency_detail['currency_code'] ?>">                                                 
                                                <?php echo form_error('currency_code', '<em style="color:red">', '</em>'); ?>                                                
                                            </div> 
                                        </div>  
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="currency_symbol"><?php echo $this->lang->line('text_currency_symbol'); ?><span class="required" aria-required="true"> * </span></label> 
                                                <input id="currency_symbol" type="text" class="form-control" name="currency_symbol" value="<?php if (isset($currency_symbol)) echo $currency_symbol;elseif (isset($currency_detail['currency_symbol'])) echo $currency_detail['currency_symbol'] ?>">                                                 
                                                <?php echo form_error('currency_symbol', '<em style="color:red">', '</em>'); ?>                                                
                                            </div> 
                                            <div class="form-group col-md-6">
                                                <label for="currency_decimal_place"><?php echo $this->lang->line('text_decimal_places'); ?><span class="required" aria-required="true"> * </span></label>
                                                <input id="currency_symbol" type="text" class="form-control" name="currency_decimal_place" value="<?php if (isset($currency_decimal_place)) echo $currency_decimal_place;elseif (isset($currency_detail['currency_decimal_place'])) echo $currency_detail['currency_decimal_place'] ?>">                                                 
                                                <?php echo form_error('currency_decimal_place', '<em style="color:red">', '</em>'); ?>                                                
                                            </div> 
                                        </div>  
                                        <div class="form-group text-center">
                                            <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary "  <?php
                                            if ($this->system->demo_user == 1 && isset($currency_detail) && $currency_detail['currency_id'] <= 5) {
                                                echo 'disabled';
                                            }
                                            ?>> 
                                            <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>currency/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                    
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
                        'currency_name': {
                            required: true,
                        },
                        'currency_code': {
                            required: true,
                        },
                        'currency_symbol': {
                            required: true,
                        },
                        'currency_decimal_place': {
                            required: true,
                        },
                    },
                    messages: {
                        'currency_name': {
                            required: '<?php echo $this->lang->line('err_currency_name_req'); ?>',
                        },
                        'currency_code': {
                            required: '<?php echo $this->lang->line('err_currency_code_req'); ?>',
                        },
                        'currency_symbol': {
                            required: '<?php echo $this->lang->line('err_currency_symbol_req'); ?>',
                        },
                        'currency_decimal_place': {
                            required: '<?php echo $this->lang->line('err_currency_decimal_place_req'); ?>',
                        },
                    },
                    errorPlacement: function (error, element)
                    {
                        if (element.is(":file"))
                        {
                            error.insertAfter(element.parent());
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