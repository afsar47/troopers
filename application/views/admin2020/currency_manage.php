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
                        <?php if (isset($btn)) { ?>
                            <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>currency/insert">
                                <i class="fa fa-plus"></i> <?php echo $btn; ?>
                            </a>
                        <?php } ?>
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
<!--                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_currency_settings'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-xs-12">
                                        <form method="POST" action="<?php echo base_url() . $this->path_to_view_admin ?>currency/" id="validate">                                           
                                            <div class="row">                                               
                                                <div class="form-group col-md-4">
                                                    <label for="currency"><?php echo $this->lang->line('text_currency'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <select name="currency" id="currency" class="form-control">
                                                        <option value=''><?php echo $this->lang->line('text_select'); ?></option>
                                                        <?php foreach ($currency as $cur) { ?>
                                                            <option value=' <?php echo $cur->currency_id; ?>'  <?php if ($this->system->currency == $cur->currency_id) echo 'selected'; ?>> <?php echo $cur->currency_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error('currency', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="amount"><?php echo $this->lang->line('text_amount'); ?> (<?php echo $this->functions->getCurrencySymbol($this->system->currency); ?>)<span class="required" aria-required="true"> * </span></label>
                                                    <input id="amount" readonly="" type="text" class="form-control" name="amount" value="<?php if (isset($this->system->amount)) echo $this->system->amount; ?>">                                                 
                                                    <?php echo form_error('amount', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-1 m-auto text-center" > = </div>
                                                <div class="form-group col-md-3">
                                                    <label for="point"><?php echo $this->lang->line('text_point'); ?> (<i class="fa fa-point"></i><?php echo $this->functions->getPoint(); ?>)<span class="required" aria-required="true"> * </span></label>
                                                    <input id="point" type="text" class="form-control" name="point" value="<?php if (isset($this->system->point)) echo $this->system->point; ?>">                                                 
                                                    <?php echo form_error('point', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>  
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit_currency" class="btn btn-primary " <?php
                                                if ($this->system->demo_user == 1) {
                                                    echo 'disabled';
                                                }
                                                ?>>   
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>currency/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                 
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <div class="col-md-12 mt-3" >
                            <form name="frmcurrencylist" method="post" action="<?php echo base_url() . $this->path_to_view_admin ?>currency">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="manage_tbl">
                                        <thead>
                                            <tr>                                                
                                                <th colspan="8">
                                                    <label><?php echo $this->lang->line('text_action_perform'); ?></label>
                                                    <select class="multi_action form-control d-inline w-auto ml-2">
                                                        <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                        <option value="delete"><?php echo $this->lang->line('text_delete'); ?></option>
                                                        <option value="change_publish"><?php echo $this->lang->line('text_change_status'); ?></option>
                                                    </select>
                                                </th>                                                
                                            </tr> 
                                            <tr>
                                                <th><input type="checkbox" class='checkall' id='checkall'> </th>
                                                <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                <th><?php echo $this->lang->line('text_currency_name'); ?></th>
                                                <th><?php echo $this->lang->line('text_currency_code'); ?></th>
                                                <th><?php echo $this->lang->line('text_currency_symbol'); ?></th>
                                                <th><?php echo $this->lang->line('text_status'); ?></th>
                                                <th><?php echo $this->lang->line('text_date'); ?></th>
                                                <th><?php echo $this->lang->line('text_actions'); ?></th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                <th><?php echo $this->lang->line('text_currency_name'); ?></th>
                                                <th><?php echo $this->lang->line('text_currency_code'); ?></th>
                                                <th><?php echo $this->lang->line('text_currency_symbol'); ?></th>
                                                <th><?php echo $this->lang->line('text_status'); ?></th>
                                                <th><?php echo $this->lang->line('text_date'); ?></th>
                                                <th><?php echo $this->lang->line('text_actions'); ?></th>
                                            </tr>   
                                        </tfoot>
                                    </table>
                                    <input type="hidden" name="action" />
                                    <input type="hidden" name="currencyid" />
                                    <input type="hidden" name="publish" /> 
                                </div>
                            </form>
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
                        'currency': {
                            required: true,
                        },
                        'amount': {
                            required: true,
                            number: true,
                            digits: true
                        },
                        'point': {
                            required: true,
                            number: true,
                            digits: true
                        },
                    },
                    messages: {
                        'currency': {
                            required: '<?php echo $this->lang->line('err_currency_req'); ?>',
                        },
                        'amount': {
                            required: '<?php echo $this->lang->line('err_amount_req'); ?>',
                            number: '<?php echo $this->lang->line('err_number'); ?>',
                            digits: '<?php echo $this->lang->line('err_digits'); ?>',
                        },
                        'point': {
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