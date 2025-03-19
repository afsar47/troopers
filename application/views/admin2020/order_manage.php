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
                        <h1 class="h2"><?php echo $this->lang->line('text_order'); ?></h1>
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
                            <form name="frmorderlist" method="post" action="<?php echo base_url() . $this->path_to_view_admin ?>order">
                                <div class=" table-responsive">
                                    <table id="manage_tbl" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th style='width: 5%;'><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                <th style='width: 10%;'><?php echo $this->lang->line('text_order_no'); ?></th>
                                                <th style='width: 10%;'><?php echo $this->lang->line('text_user_name'); ?></th>
                                                <!--<th style='width: 10%;'><?php echo $this->lang->line('text_product_name'); ?></th>-->
                                                <th style='width: 10%;'><?php echo $this->lang->line('text_order_price') . '(' . $this->functions->getPoint() . ')'; ?></th>
                                                <!--<th style='width: 40%;'><?php echo $this->lang->line('text_shipping_detail'); ?></th>-->
                                                <th style='width: 10%;'><?php echo $this->lang->line('text_status'); ?></th>
                                                <th style='width: 10%;'><?php echo $this->lang->line('text_date'); ?></th>
                                                <th style='width: 10%;'><?php echo $this->lang->line('text_view'); ?></th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th style='width: 5%;'><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                <th style='width: 10%;'><?php echo $this->lang->line('text_order_no'); ?></th>
                                                <th style='width: 10%;'><?php echo $this->lang->line('text_user_name'); ?></th>
                                                <!--<th style='width: 10%;'><?php echo $this->lang->line('text_product_name'); ?></th>-->
                                                <th style='width: 10%;'><?php echo $this->lang->line('text_order_price') . '(' . $this->functions->getPoint() . ')'; ?></th>
                                                <!--<th style='width: 40%;'><?php echo $this->lang->line('text_shipping_detail'); ?></th>-->
                                                <th style='width: 10%;'><?php echo $this->lang->line('text_status'); ?></th>
                                                <th style='width: 10%;'><?php echo $this->lang->line('text_date'); ?></th>
                                                <th style='width: 10%;'><?php echo $this->lang->line('text_view'); ?></th>
                                            </tr> 
                                        </tfoot>
                                    </table>
                                    <input type="hidden" name="action" />
                                    <input type="hidden" name="oid" />
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