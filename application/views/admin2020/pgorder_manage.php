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
                        <h1 class="h2"><?php echo $this->lang->line('text_money_orders');?></h1>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form name="frmpgorderlist" method="post" action="<?php echo base_url() . $this->path_to_view_admin ?>pgorder">
                                <div class=" table-responsive">
                                    <table id="manage_tbl" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('text_sr_no');?></th>
                                                <th><?php echo $this->lang->line('text_order_no');?></th>
                                                <th><?php echo $this->lang->line('text_user_name');?></th>
                                                <th><?php echo $this->lang->line('text_amount'). '(' . $this->functions->getPoint() . ')'; ?></th>
                                                <th><?php echo $this->lang->line('text_deposit_by');?></th>
                                                <th><?php echo $this->lang->line('text_status');?></th>
                                                <th><?php echo $this->lang->line('text_transaction_no');?></th>
                                                <th><?php echo $this->lang->line('text_date');?></th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th><?php echo $this->lang->line('text_sr_no');?></th>
                                                <th><?php echo $this->lang->line('text_order_no');?></th>
                                                <th><?php echo $this->lang->line('text_user_name');?></th>
                                                <th><?php echo $this->lang->line('text_amount'). '(' . $this->functions->getPoint() . ')'; ?></th>
                                                <th><?php echo $this->lang->line('text_deposit_by');?></th>
                                                <th><?php echo $this->lang->line('text_status');?></th>
                                                <th><?php echo $this->lang->line('text_transaction_no');?></th>
                                                <th><?php echo $this->lang->line('text_date');?></th>
                                            </tr> 
                                        </tfoot>
                                    </table>
                                    <input type="hidden" name="action" />
                                    <input type="hidden" name="depositid" />
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
    </body>
</html>