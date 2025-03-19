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
                    <?php if($this->system->demo_user == 1) { ?>
                    <div class="alert alert-primary" role="alert">
                        <strong><?php echo stripslashes($this->lang->line('text_licence_note')); ?></strong>
                    </div>
                    <?php } ?>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><?php echo $this->lang->line('text_dashboard');?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">                          
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 dash-box">
                            <a href="<?php echo base_url() . $this->path_to_view_admin ?>members/">
                                <div class="bg-lightpink small-box card card-sm-3">
                                    <div class="card-icon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4><?php echo $this->lang->line('text_total_user');?></h4>
                                        </div>
                                        <div class="card-body">
                                            <?php echo $tot_member['total_member']; ?>                                  
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 dash-box">
                            <a href="<?php echo base_url() . $this->path_to_view_admin ?>matches/">
                                <div class="bg-lightgreen small-box card card-sm-3">
                                    <div class="card-icon ">
                                        <i class="fa fa-gamepad"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4><?php echo $this->lang->line('text_total_match');?></h4>
                                        </div>
                                        <div class="card-body">
                                            <?php echo $tot_match['total_match']; ?>                                        
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 dash-box">
                            <a href="<?php echo base_url() . $this->path_to_view_admin ?>pgorder/">
                                <div class="bg-lightblue small-box card card-sm-3">
                                    <div class="card-icon ">
                                        <i class="fa fa-credit-card"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4><?php echo $this->lang->line('text_received_payment');?></h4>
                                        </div>
                                        <div class="card-body">
                                            <?php echo $this->functions->getPoint() .' '. utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_payment['total_payment'])); ?>                                        
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 dash-box">
                            <a href="<?php echo base_url() . $this->path_to_view_admin ?>withdraw/">
                                <div class="bg-lightpink small-box card card-sm-3">
                                    <div class="card-icon ">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4><?php echo $this->lang->line('text_withdraw');?></h4>
                                        </div>
                                        <div class="card-body">
                                            <?php echo $this->functions->getPoint() .' '. utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_withdraw['total_withdraw'])); ?>                                        
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 dash-box">
                            <a href="<?php echo base_url() . $this->path_to_view_admin ?>profilesetting/">
                                <div class="bg-lightblue small-box card card-sm-3">
                                    <div class="card-icon ">
                                        <i class="fa fa-cog"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4><?php echo $this->lang->line('text_contact_us_setting');?></h4>
                                        </div>
                                        <div class="card-body">                                                                                       
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>
    </body>
</html>