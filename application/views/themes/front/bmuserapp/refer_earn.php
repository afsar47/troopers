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
                                <h4 class="m-0 d-inline"><?php echo $breadcrumb_title; ?></h4>
                                <p class="badge badge-light float-right f-18 text-black d-inline" id="tot_wallet"><?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', 0); ?></p>
                            </div>
                            <div class="bm-mdl-center bm-full-height">
                                <div class="content-section">
                                    <!--                                    <div class="bm-content-listing">
                                                                            <a href="<?php echo base_url() . $this->path_to_default; ?>refer_earn/detail">
                                                                                <div>
                                                                                    <img src="<?php echo $this->default_img; ?>refer_earn.png" class="img-responsive" style="width: 100%;border-radius: 5px;">
                                                                                </div>
                                                                            </a>
                                                                        </div>-->
                                    <!--                                    <div class="bm-content-listing">
                                                                            <div class="bm-single-game refer-bg" style="border-radius: 5px;">
                                                                                <div class="row d-flex" onclick="location.href = '<?php echo base_url() . $this->path_to_default; ?>refer_earn/detail';" style="cursor: pointer;">
                                                                                    <div class="col-5 text-center">
                                                                                        <img src="<?php echo $this->default_img; ?>refer.png" alt="refer" class="img-fluid img-responsive">
                                                                                    </div>
                                                                                    <div class="col-7 text-center m-auto">
                                                                                        <h4 class="text-danger">Refer And Earn</h4>
                                                                                        <p class="f-12 text-dark">Invite your friends to the App and earn huge</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>                                    
                                                                        </div>-->
                                    <?php
                                    foreach ($banner_data as $banner) {
                                        if ($banner->banner_link != 'Watch and Earn' && $banner->banner_link != 'My Rewards') {
                                            ?>
                                            <div class="bm-content-listing">
                                                <a href="<?php
                                                if ($banner->banner_link_type == 'web')
                                                    echo $banner->banner_link;
                                                else {
                                                    if ($banner->banner_link == 'Refer and Earn')
                                                        echo base_url() . $this->path_to_default . 'refer_earn/detail';
                                                    elseif ($banner->banner_link == 'Luckey Draw')
                                                        echo base_url() . $this->path_to_default . 'lottery';
                                                    elseif ($banner->banner_link == 'Buy Product')
                                                        echo base_url() . $this->path_to_default . 'product';
                                                    elseif ($banner->banner_link == 'My Profile')
                                                        echo base_url() . $this->path_to_default . 'profile';
                                                    elseif ($banner->banner_link == 'My Wallet')
                                                        echo base_url() . $this->path_to_default . 'wallet';
                                                    elseif ($banner->banner_link == 'My Matches')
                                                        echo base_url() . $this->path_to_default . 'play/my_match';
                                                    elseif ($banner->banner_link == 'My Statics')
                                                        echo base_url() . $this->path_to_default . 'statistics';
                                                    elseif ($banner->banner_link == 'My Referral')
                                                        echo base_url() . $this->path_to_default . 'referrals';
                                                    elseif ($banner->banner_link == 'Announcement')
                                                        echo base_url() . $this->path_to_default . 'announcement';
                                                    elseif ($banner->banner_link == 'Top Players')
                                                        echo base_url() . $this->path_to_default . 'topplayers';
                                                    elseif ($banner->banner_link == 'Leaderboard')
                                                        echo base_url() . $this->path_to_default . 'leaderbord';
                                                    elseif ($banner->banner_link == 'App Tutorials')
                                                        echo base_url() . $this->path_to_default . 'apptutorial';
                                                    elseif ($banner->banner_link == 'About us')
                                                        echo base_url() . $this->path_to_default . 'aboutus';
                                                    elseif ($banner->banner_link == 'Customer Support')
                                                        echo base_url() . $this->path_to_default . 'support';
                                                    elseif ($banner->banner_link == 'Terms and Condition')
                                                        echo base_url() . $this->path_to_default . 'terms';
                                                    elseif ($banner->banner_link == 'Game')
                                                        echo base_url() . $this->path_to_default . 'play/matches/' . $banner->link_id;
                                                }
                                                ?>">    
                                                    <div>
                                                        <img src="<?php echo base_url() . $this->banner_image . "thumb/1000x500_" . $banner->banner_image; ?>" class="img-responsive" style="width: 100%;border-radius: 5px;">
                                                    </div>
                                                </a>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <!--                                                                        <div class="bm-content-listing">
                                                                                                                <div class="bm-single-game refer-bg" onclick="location.href = '<?php echo base_url() . $this->path_to_default; ?>lottery';"  style="cursor: pointer;background-image: url('<?php echo $this->default_img . 'lottery.png'; ?>');background-repeat: no-repeat;min-height: 200px;border-radius: 5px;" >
                                                                                                                    <div class="row d-flex" onclick="location.href = '<?php echo base_url() . $this->path_to_default; ?>refer_earn/detail';" style="cursor: pointer;">                                                
                                                                                                                    </div>
                                                                                                                </div>                                    
                                                                                                            </div>-->
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