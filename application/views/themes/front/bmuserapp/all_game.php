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
        <style>
            .carousel-item {
                background:#ccc;
                border-radius: 5px;
            }
            #carousel1_indicator {
                margin-bottom: 20px;
            }
            .carousel-inner {
                border-radius: 5px;
            }
        </style>
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
                            <?php foreach ($announcement_data as $announcement) { ?>
                                <a href="<?php echo base_url() . $this->path_to_default . 'announcement'; ?>" class="btn-lightpink m-2 br-5 p-1 d-block">
                                    <b><?php echo $this->lang->line('text_announcement'); ?> :</b>
                                    <div>
                                        <?php echo $announcement->announcement_desc; ?>
                                    </div>
                                </a>
                                <style>
                                    .bm-full-height{
                                        padding-bottom: 180px;
                                    }
                                </style>
                                <?php
                                break;
                            }
                            ?>                            
                            <div class="bm-mdl-center bm-full-height">
                                <div class="content-section">
                                    <div class="bm-content-listing">
                                        <div id="carousel1_indicator" class="carousel slide" data-ride="carousel">
                                            <ol class="carousel-indicators">
                                                <?php
                                                $i = 0;
                                                foreach ($slider_data as $slider) {
                                                    ?>
                                                    <li data-target="#carousel1_indicator" data-slide-to="<?php echo $i; ?>" class="<?php if ($i == 0) echo 'active'; ?>"></li>
                                                    <!--                                                    <li data-target="#carousel1_indicator" data-slide-to="1"></li>
                                                                                                        <li data-target="#carousel1_indicator" data-slide-to="2"></li>-->
                                                    <?php
                                                    $i++;
                                                }
                                                ?>
                                            </ol>  
                                            <div class="carousel-inner">
                                                <?php
                                                $i = 0;
                                                foreach ($slider_data as $slider) {
                                                    ?>
                                                    <div class="carousel-item <?php if ($i == 0) echo 'active'; ?>" style="border-radius: 5px;">
                                                        <a <?php
                                                        if ($slider->slider_link_type == '')
                                                            echo '';
                                                        else if ($slider->slider_link_type == 'web')
                                                            echo 'href="' . $slider->slider_link . '"';
                                                        else {
                                                            if ($slider->slider_link != 'Watch and Earn') {
                                                                echo 'href="' . base_url() . $this->path_to_default;
                                                                if ($slider->slider_link == 'Refer and Earn')
                                                                    echo 'refer_earn/detail"';
                                                                elseif ($slider->slider_link == 'Luckey Draw')
                                                                    echo 'lottery"';
                                                                elseif ($slider->slider_link == 'Buy Product')
                                                                    echo 'product"';
                                                                elseif ($slider->slider_link == 'My Profile')
                                                                    echo 'profile"';
                                                                elseif ($slider->slider_link == 'My Wallet')
                                                                    echo 'wallet"';
                                                                elseif ($slider->slider_link == 'My Matches')
                                                                    echo 'play/my_match"';
                                                                elseif ($slider->slider_link == 'My Statics')
                                                                    echo 'statistics"';
                                                                elseif ($slider->slider_link == 'My Referral')
                                                                    echo 'referrals"';
                                                                elseif ($slider->slider_link == 'Announcement')
                                                                    echo 'announcement"';
                                                                elseif ($slider->slider_link == 'Top Players')
                                                                    echo 'topplayers"';
                                                                elseif ($slider->slider_link == 'Leaderboard')
                                                                    echo 'leaderbord"';
                                                                elseif ($slider->slider_link == 'App Tutorials')
                                                                    echo 'apptutorial"';
                                                                elseif ($slider->slider_link == 'About us')
                                                                    echo 'aboutus"';
                                                                elseif ($slider->slider_link == 'Customer Support')
                                                                    echo 'support"';
                                                                elseif ($slider->slider_link == 'Terms and Condition')
                                                                    echo 'terms"';
                                                                elseif ($slider->slider_link == 'Game')
                                                                    echo 'play/matches/' . $slider->link_id . '"';
                                                            }
                                                        }
                                                        ?>><img class="d-block w-100" src="<?php echo base_url() . $this->slider_image . "thumb/1000x500_" . $slider->slider_image; ?>" alt="First slide"> </a>
                                                    </div>
                                                    <?php
                                                    $i++;
                                                }
                                                ?>
                                            </div>
                                            <a class="carousel-control-prev" href="#carousel1_indicator" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carousel1_indicator" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div> 
                                        <?php foreach ($games_data as $game) { ?>
                                            <a href="<?php echo base_url() . $this->path_to_default . 'play/matches/' . $game->game_id; ?>" class="bm-single-href">
                                                <div class="bm-single-game game box-shadow" style="background-image:url('<?php echo base_url() . $this->game_image . "thumb/1000x500_" . $game->game_image; ?>')">
                                                    <span class="bm-tot-match">Matches available : <?php echo $game->total_upcoming_match; ?></span>
                                                </div>
                                                <div class="bm-game-name text-black f-18 box-shadow"><?php echo $game->game_name; ?></div>
                                            </a>
                                        <?php } ?>
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
        <script>
            // $('.carousel').carousel({
            //     interval: 1000 * 2
            // });
        </script>
    </body>
</html>