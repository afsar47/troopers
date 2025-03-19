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
                                <h4 class="m-0 d-inline"><?php echo $this->lang->line('text_me'); ?></h4>                            
                            </div>
                            <div class="bm-mdl-center bm-full-height">
                                <div class="content-section">
                                    <div class="bm-user-info">
                                        <div class="row">
                                            <div class="col-3">
                                                <?php if (isset($profile_detail['profile_image']) && $profile_detail['profile_image'] != '' && file_exists($this->profile_image . $profile_detail['profile_image'])) { ?>                                                            
                                                    <img src ="<?php echo base_url() . $this->profile_image . "thumb/100x100_" . $profile_detail['profile_image'] ?>"  alt="profile"  class="profile-img float-right img-fluid img-responsive" >
                                                <?php } else {                                                
                                                ?>
                                                    <img src="<?php echo base_url() . $this->company_favicon . "thumb/100x100_" . $this->system->company_favicon ?>" alt="profile"  class="profile-img float-right img-fluid img-responsive">
                                                <?php
                                                    } 
                                                ?>
                                            </div>
                                            <div class="col-9">
                                                <p class="f-18"><?php echo $this->lang->line('text_user_name'); ?> : <?php echo $this->member->front_member_username; ?></p><br/>
                                                <p class="f-18"><?php echo $this->lang->line('text_balance'); ?> : <?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', ($tot_balance['join_money'] + $tot_balance['wallet_balance'])) . '</span>'; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bm-game-info">
                                        <div class="row">
                                            <div class="col-4 text-center">
                                                <p><?php echo ($tot_play['total_match'] == '' || $tot_play['total_match'] == null) ? '0' : $tot_play['total_match']; ?>                                        
                                                    <br/> <?php echo $this->lang->line('text_matches_played'); ?></p>
                                            </div>
                                            <div class="col-4 text-center">
                                                <p><?php echo ($tot_play['total_kill'] == '' || $tot_play['total_kill'] == null) ? '0' : $tot_play['total_kill']; ?>                                        
                                                    <br/> <?php echo $this->lang->line('text_total_killed'); ?></p>
                                            </div>
                                            <div class="col-4 text-center">
                                                <p><?php echo '<span style="">' . $this->functions->getPoint() . '</span>'; ?><?php echo ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_play['total_win'])); ?>                                        
                                                    <br/> <?php echo $this->lang->line('text_amount_won'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bm-content-listing">
                                        <div class="bm-account-listing">
                                            <ul>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>profile/">
                                                        <span class="icon"><i class="fa fa-user-circle"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_my_profile'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>wallet/">
                                                        <span class="icon"><i class="fa fa-google-wallet"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_my_wallet'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>play/my_match">
                                                        <span class="icon"><i class="fa fa-gamepad"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_my_matches'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>product/my_orders">
                                                        <span class="icon"><i class="fa fa-first-order"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_my_orders'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>statistics/">
                                                        <span class="icon"><i class="fa fa-bar-chart"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_my_statistics'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>referrals/">
                                                        <span class="icon"><i class="fa fa-users"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_my_referrals'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>announcement/">
                                                        <span class="icon"><i class="fa fa-bullhorn"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_announcement'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>topplayers/">
                                                        <span class="icon"><i class="fa fa-star"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_top_players'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>leaderbord/">
                                                        <span class="icon"><i class="fa fa-area-chart"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_leaderboard'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>apptutorial/">
                                                        <span class="icon"><i class="fa fa-question-circle"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_app_tutorial'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>aboutus/">
                                                        <span class="icon"><i class="fa fa-info-circle"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_about_us'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>support/">
                                                        <span class="icon"><i class="fa fa-headphones"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_customer_supports'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . $this->path_to_default; ?>terms/">
                                                        <span class="icon"><i class="fa fa-file-text-o"></i></span>
                                                        <span class="text f-20"><?php echo $this->lang->line('text_terms_conditions'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url(); ?>login/logout"  onclick="logout();">
                                                        <span class="icon"><i class="fa fa-power-off"></i></span>
                                                        <span class="text f-20 border-0"><?php echo $this->lang->line('text_logout'); ?><i class="fa fa-angle-right"></i></span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>                                    
                                    </div>
                                </div>
                            </div>
                            <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
                        </div>
                    </div>
                    <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
                </div>

            </div>
        </main>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
    </body>
</html>