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
                                <a href="<?php echo base_url() . $this->path_to_default . 'account/'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a><h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="bm-mdl-center bm-full-height pb-6">
                                <div class="content-section">
                                    <div class="bm-content-listing">
                                        <span class="btn-block btn-green text-center rounded-top p-1 text-uppercase"><?php echo $this->lang->line('text_total_balance'); ?></span> 
                                        <div class="container wallet">                                          
                                            <div class="row btn-lightgreen rounded-bottom p-3 text-black">
                                                <div class="col-7">
                                                    <h6><?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $member['join_money'] + $member['wallet_balance'])); ?></h6>
                                                    <p><?php echo $this->lang->line('text_win_money'); ?> : <?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $member['wallet_balance'])); ?></p>
                                                    <p class="mt-1"><?php echo $this->lang->line('text_join_money'); ?> : <?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $member['join_money'])); ?></p>
                                                </div>
                                                <div class="col-5 text-right">
                                                    <a href="<?php echo base_url() . $this->path_to_default . 'wallet/addmoney'; ?>" class="btn btn-md f-14 btn-lightpink text-uppercase"> <?php echo $this->lang->line('text_action_add'); ?>  </a>
                                                    <a href="<?php echo base_url() . $this->path_to_default . 'wallet/withdraw'; ?>" class="btn btn-md f-14 btn-lightpink mt-1 text-uppercase"> <?php echo $this->lang->line('text_withdraw'); ?>  </a>
                                                </div>
                                            </div>
                                            <div class="row mt-2 text-center" >
                                                <div class="col-6 p-0 pr-1">
                                                    <div class="w-100 btn-green text-center rounded-top p-1 text-uppercase"><?php echo $this->lang->line('text_earnings'); ?></div>
                                                    <div class="btn-lightgreen w-100 rounded-bottom p-3 text-black"> <?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_play['total_win'])); ?> </div>
                                                </div>
                                                <div class="col-6 p-0 pl-1">
                                                    <div class="w-100 btn-green text-center rounded-top p-1 text-uppercase"><?php echo $this->lang->line('text_payouts'); ?></div>
                                                    <div class="btn-lightgreen w-100 rounded-bottom p-3 text-black"> <?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_withdraw['tot_withdraw'])); ?> </div>
                                                </div>
                                            </div>
                                            <h6 class="text-black text-center mt-2 text-uppercase"><?php echo $this->lang->line('text_wallet_history'); ?></h6>
                                            <?php foreach ($wallet_history_data as $wallet_history) { ?>
                                                <div class="row my-2 rounded bg-lightgray box-shadow p-2 d-flex">
                                                    <div class="col-2 m-auto">
                                                        <?php if ($wallet_history->withdraw > 0) { ?>
                                                            <span class="bm_text_lightpink text-uppercase"><?php echo $this->lang->line('text_debit'); ?></span>
                                                        <?php } else { ?>
                                                            <span class="bm_text_lightgreen text-uppercase"><?php echo $this->lang->line('text_credit'); ?></span>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-6 text-dark text-center">
                                                        <p ><?php echo $wallet_history->note . '- #' . $wallet_history->account_statement_id; ?> </p>
                                                        <p class="mt-1"><?php echo $wallet_history->accountstatement_dateCreated; ?></p>
                                                    </div>
                                                    <div class="col-4 text-right">
                                                        <?php if ($wallet_history->withdraw > 0) { ?>
                                                            <span class="bm_text_lightpink"><?php echo '- ' . '<span style="">' . $this->functions->getPoint() . '</span> ' . $wallet_history->withdraw; ?></span>
                                                        <?php } else { ?>
                                                            <span class="bm_text_lightgreen"><?php echo '+ ' . '<span style="">' . $this->functions->getPoint() . '</span> ' . $wallet_history->deposit; ?></span>
                                                        <?php } ?>
                                                        <p class="text-primary my-1"> <?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', ($wallet_history->join_money + $wallet_history->win_money)); ?></p>
                                                    </div>
                                                </div>
                                            <?php } ?>
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
    </body>
</html>