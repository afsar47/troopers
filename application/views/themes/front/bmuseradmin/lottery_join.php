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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    </head>
    <body>
        <?php $this->load->view($this->path_to_view_default . 'header_body'); ?>

        <div class="d-flex" id="wrapper">
            <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h3><?php echo $breadcrumb_title; ?></h3>
                        <div class="btn-toolbar mb-2 mb-md-0">                          
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-lg-12">                                
                            <div class="row d-flex mb-3">
                                <div class="col-sm-6 m-auto text-lightpink" style="height: 100%;">
                                    <i class="fa fa-google-wallet" style="font-size: 50px;"></i>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <span class="d-block">Your Current Balance : <strong> <i><?php echo $this->functions->getPoint(); ?></i> <?php echo $member['join_money'] + $member['wallet_balance']; ?></strong></span>                                                
                                    <span class="d-block">Total payable Amount : <strong> <i><?php echo $this->functions->getPoint(); ?></i> <?php echo $tot_payment = $lottery['lottery_fees']; ?> </strong></span>      
                                </div>
                            </div>
                            <form action="<?php echo base_url() . $this->path_to_default . 'lottery/join'; ?>" method="post" name="position-form" id="position-form">                                
                                <input type="hidden" name="lottery_id" value="<?php echo $lottery['lottery_id']; ?>">
                                <input type="hidden" name="total_amount" value="<?php echo $tot_payment; ?>">
                                <a href="<?php echo base_url() . $this->path_to_default . 'lottery'; ?>" class="btn btn-secondary"> Cancel</a>                                        
                                <button type="submit" id="join_now" class="btn bg-lightgreen text-white" value="<?php echo $this->lang->line('text_btn_join'); ?>" name="submit" > <?php echo $this->lang->line('text_btn_join'); ?> </button>                              
                            </form>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
    </body>
</html>