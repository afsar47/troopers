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
                                <a href="<?php echo base_url() . $this->path_to_default . 'lottery/join/' . $lottery['lottery_id']; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a>
                                <h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="row text-black p-2">
                                <div class="col-12">                                
                                    <div class="row d-flex mb-3">
                                        <div class="col-3 m-auto bm_text_lightgreen" style="height: 100%;">
                                            <i class="fa fa-google-wallet" style="font-size: 50px;"></i>
                                        </div>
                                        <div class="col-9 text-right">
                                            <span class="d-block"><?php echo $this->lang->line('text_your_curr_balance'); ?> : <strong> <i style=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $member['join_money'] + $member['wallet_balance']; ?></strong></span>                                                
                                            <!--<span class="d-block">Lottery entry fee: <strong> <i style=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $lottery['entry_fee']; ?> </strong></span>-->      
                                            <span class="d-block"><?php echo $this->lang->line('text_tot_payable_amt'); ?> : <strong> <i style=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $tot_payment = $lottery['lottery_fees']; ?> </strong></span>      
                                        </div>
                                    </div>
                                    <form action="<?php echo base_url() . $this->path_to_default . 'lottery/join'; ?>" class="profile-form" method="post" name="join-form" id="join-form">
                                        <input type="hidden" name="lottery_id" value="<?php echo $lottery['lottery_id']; ?>">
                                        <input type="hidden" name="total_amount" value="<?php echo $tot_payment; ?>">
                                        <a href="<?php echo base_url() . $this->path_to_default . 'lottery'; ?>" class="btn btn-lightpink"> Cancel</a>
                                        <button type="submit" id="join_now" class="btn btn-lightgreen text-white text-uppercase" value="<?php echo $this->lang->line('text_btn_join'); ?>" name="submit" > <?php echo $this->lang->line('text_btn_join'); ?> </button>                              
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
                </div>
            </div>
        </main>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
    </body>
</html>      