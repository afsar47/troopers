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
    <body oncontextmenu="return:false;">
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
                    <?php foreach ($announcement_data as $announcement) { ?>
                        <a href="<?php echo base_url() . $this->path_to_default . 'announcement'; ?>" class="btn btn-sm btn-block bg-primary text-white mb-2 text-left">
                            <b><?php echo $this->lang->line('text_announcement'); ?> :</b>
                            <div>
                                <?php echo $announcement->announcement_desc; ?>
                            </div>
                        </a>
                        <?php
                        break;
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-12 mb-2">
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
                                <div class="carousel-inner game-box">
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
                                            ?> style="border-radius: 5px;"><img class="d-block w-100" src="<?php echo base_url() . $this->slider_image . "thumb/1000x500_" . $slider->slider_image; ?>" alt="First slide"> </a>
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
                        </div>
                        <?php foreach ($games_data as $game) { ?>
                            <div class="col-lg-4 col-md-6 game-box">
                                <a href="<?php echo base_url() . $this->path_to_default . 'play/matches/' . $game->game_id; ?>">
                                    <div class="card card-sm-3 mb-4" style="">
                                        <span class="bm-tot-match">Matches Available: <?php echo $game->total_upcoming_match; ?></span>
                                        <img src="<?php echo base_url() . $this->game_image . "thumb/1000x500_" . $game->game_image; ?>" class="img-responsive" style="width: 100%;">
                                        <h6 class="text-white text-center pt-1"><?php echo $game->game_name; ?></h6>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
        <script>
            // $('.carousel').carousel({
            //     interval: 1000 * 2
            // });
        </script>
    </body>
</html>