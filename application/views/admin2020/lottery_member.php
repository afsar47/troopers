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
                        <h1 class="h2"><?php echo $this->lang->line('text_lottery_member_list'); ?></h1>
                        <?php if (isset($btn)) { ?>
                            <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>lottery/insert">
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
                        <div class="col-md-12">
                            <form name="frmlotterymemberlist"  method="post" action="<?php echo base_url() . $this->path_to_view_admin . 'lottery/viewmember/' . $this->uri->segment('4'); ?>">
                                <div class=" table-responsive">
                                    <table id="manage_tbl" class="table  table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('text_winner'); ?></th>
                                                <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                <th><?php echo $this->lang->line('text_user_name'); ?></th>
                                                <!--<th><?php echo $this->lang->line('text_lottery_member'); ?></th>-->
                                                <th><?php echo $this->lang->line('text_status'); ?></th>
                                                <th><?php echo $this->lang->line('text_date'); ?></th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th><?php echo $this->lang->line('text_winner'); ?></th>
                                                <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                <th><?php echo $this->lang->line('text_user_name'); ?></th>
                                                <!--<th><?php echo $this->lang->line('text_lottery_member'); ?></th>-->
                                                <th><?php echo $this->lang->line('text_status'); ?></th>
                                                <th><?php echo $this->lang->line('text_date'); ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="text-center">
                                    <input type="hidden" name="lottery_id" value="<?php echo $this->uri->segment('4'); ?>">
                                    <input class="btn btn-primary " type="submit" name="update" value="<?php echo $this->lang->line('text_btn_update'); ?>" <?php
                                           if ($this->system->demo_user == 1 && $this->uri->segment('4') <= 2) {
                                               echo 'disabled';
                                           }
                                           ?>>
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