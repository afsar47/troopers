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
        <?php $this->load->view($this->path_to_view_default . 'header_body'); ?>
        <div class="d-flex" id="wrapper">
            <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">                   
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h3><?php echo $breadcrumb_title; ?></h3>
                    </div>                     
                    <div class="row d-flex"> 
                        <!--                        <div class="col-lg-4 col-md-6 text-center mb-sm-3 mb-xs-3">
                                                    <div class=" box-shadow" style="height: 100%;">
                                                        <a href="<?php echo base_url() . $this->path_to_default; ?>refer_earn/detail">
                                                            <div class="card card-sm-3" style="">
                                                                <img src="<?php echo $this->default_img; ?>refer_earn.png" class="img-responsive" style="width: 100%;">
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>-->
                        <!--                        <div class="col-lg-4 col-md-6 text-center">
                                                    <div class="bm-content-listing">
                                                    <div class="box-shadow m-auto" style="height: 100%;border-radius: 5px;">
                                                        <div class="row d-flex mt-10" style="height: 100%" onclick="location.href = '<?php echo base_url() . $this->path_to_default; ?>refer_earn/detail';" style="cursor: pointer;">
                                                            <div class="col-5 text-center m-auto">
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
                                <div class="col-lg-4 col-md-6 text-center mb-sm-3 mb-xs-3">
                                    <div class=" box-shadow" style="height: 100%;">
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
                                            <div class="card card-sm-3" style="">
                                                <img src="<?php echo base_url() . $this->banner_image . "thumb/1000x500_" . $banner->banner_image; ?>" class="img-responsive" style="width: 100%;">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <!--
                                                <div class="col-lg-4 col-md-6 game-box">
                                                    <a href="<?php echo base_url() . $this->path_to_default . 'play/matches/' . $game->game_id; ?>">
                                                        <div class="card card-sm-3 mb-4" style="">
                                                            <span class="bm-tot-match">Matches Available: <?php echo $game->total_upcoming_match; ?></span>
                                                            <img src="<?php echo base_url() . $this->game_image . "thumb/1000x500_" . $game->game_image; ?>" class="img-responsive" style="width: 100%;">
                                                            <h6 class="text-white text-center pt-1"><?php echo $game->game_name; ?></h6>
                                                        </div>
                                                    </a>
                                                </div>-->
                        <!--                        <div class="offset-md-3 col-md-6">
                                                    <div class="card bg-light text-center  box-shadow">
                                                        <div class="card-body">
                                                            <h4 class="text-lightgreen text-uppercase mb-3">Refer More To Earn More</h4>
                                                            <p class="mb-4"><?php echo $this->system->referandearn_description; ?></p>
                                                            <h6 class="text-lightgreen text-uppercase mb-3">Your Referral Code</h6>
                                                            <h6 id="refer-code" onclick="copyToClipboard('#refer-code')" style="cursor:pointer;"><?php echo $this->member->front_member_username; ?><i class="fa fa-copy ml-3"></i></h6>                                           
                                                            <span class="copied text-white bg-black rounded px-2" style="position: absolute;left: 35%;z-index: 10;"></span>
                        
                                                            <img src="<?php echo $this->default_img . 'refer_earn.jpeg'; ?>" style="width: 100%" class="img-responsive mt-3">
                                                        </div>
                                                    </div>
                                                </div>-->
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
        <script>
            function copyToClipboard(element) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).text()).select();
                document.execCommand("copy");
                $(".copied").text("Copied to clipboard").show().fadeOut(1200);
                $temp.remove();
            }
        </script>        
    </body>
</html>