<div id="sidebar-wrapper" class="border-right">
    <ul class="nav flex-column list-group list-group-flush">
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'dashboard') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>dashboard/">
                <i class="fa fa-home"></i>
                <?php echo $this->lang->line('text_dashboard'); ?> <span class="sr-only">(current)</span>
            </a>
        </li>

        <?php            
            if($this->functions->check_permission('members') || $this->functions->check_permission('register_referral') || $this->functions->check_permission('referral')) {
        ?>
        <li class="nav-item">
            <a class="nav-link
            <?php
            if (($this->uri->segment('2') == 'members') || ($this->uri->segment('2') == 'register_referral') || ($this->uri->segment('2') == 'referral'))
                echo 'active';
            ?>" data-toggle="collapse" href="#item-6">
                <i class="fa fa-users"></i> 
                <?php echo $this->lang->line('text_users'); ?>  <span class="float-right"><i class="fa fa-angle-down"></i></span>
            </a>
            <div id="item-6" class="collapse 
            <?php
            if (($this->uri->segment('2') == 'members') || ($this->uri->segment('2') == 'register_referral') || ($this->uri->segment('2') == 'referral'))
                echo 'show';
            ?>">
                <ul class="nav flex-column ml-3"> 
                    <?php
                        if($this->functions->check_permission('members')) {
                    ?>      
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'members') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>members/">
                            <i class="fa fa-user"></i>
                            <?php echo $this->lang->line('text_all_user'); ?>
                        </a>
                    </li>
                    <?php
                        }
                        if($this->functions->check_permission('register_referral')) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'register_referral') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>register_referral/">
                            <i class="fa fa-user-plus"></i>
                            <?php echo $this->lang->line('text_register_referral'); ?>
                        </a>
                    </li>
                    <?php
                        }
                        if($this->functions->check_permission('referral')) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'referral') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>referral/">
                            <i class="fa fa-user-plus"></i>
                            <?php echo $this->lang->line('text_joined_referral'); ?>
                        </a>
                    </li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
        </li>
        <?php
            }
        ?>
        <?php            
            if($this->functions->check_permission('game') || $this->functions->check_permission('matches') || $this->functions->check_permission('image') || $this->functions->check_permission('ludo_challenge')) {
        ?>
        <li class="nav-item">
            <a class="nav-link
            <?php
            if (($this->uri->segment('2') == 'game') || ($this->uri->segment('2') == 'matches') || ($this->uri->segment('2') == 'image') || ($this->uri->segment('2') == 'ludo_challenge'))
                echo 'active';
            ?>" data-toggle="collapse" href="#item-7">
                <i class="fa fa-gamepad"></i> 
                <?php echo $this->lang->line('text_games'); ?>  <span class="float-right"><i class="fa fa-angle-down"></i></span>
            </a>
            <div id="item-7" class="collapse 
            <?php
            if (($this->uri->segment('2') == 'game') || ($this->uri->segment('2') == 'matches') || ($this->uri->segment('2') == 'image') || ($this->uri->segment('2') == 'ludo_challenge'))
                echo 'show';
            ?>">
                <ul class="nav flex-column ml-3">
                    <?php
                        if($this->functions->check_permission('game')) {                    
                    ?>       
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'game') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>game/">
                            <i class="fa fa-gamepad"></i>
                            <?php echo $this->lang->line('text_all_games'); ?>
                        </a>
                    </li>
                    <?php
                        }
                        if($this->functions->check_permission('matches')) {                    
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'matches') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>matches/">
                            <i class="fa fa-gamepad"></i>
                            <?php echo $this->lang->line('text_matches'); ?>
                        </a>
                    </li>
                    <?php
                        }
                        if($this->functions->check_permission('ludo_challenge')) {                    
                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($this->uri->segment('2') == 'ludo_challenge') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>ludo_challenge/">
                                    <i class="fa fa-trophy"></i>
                                    <?php echo $this->lang->line('text_ludo_challenge'); ?>
                                </a>
                            </li>
                    <?php
                        }
                        if($this->functions->check_permission('image')) {                    
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'image') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>image/">
                            <i class="fa fa-image"></i>
                            <?php echo $this->lang->line('text_images'); ?>
                        </a>
                    </li>
                    <?php
                        }
                        
                    ?>
                </ul>
            </div>
        </li>
        <?php
            }
        ?>
        <?php            
            if($this->functions->check_permission('product') || $this->functions->check_permission('order') || $this->functions->check_permission('courier')) {
        ?>
        <li class="nav-item">
            <a class="nav-link
            <?php
            if (($this->uri->segment('2') == 'product') || ($this->uri->segment('2') == 'order') || ($this->uri->segment('2') == 'courier'))
                echo 'active';
            ?>" data-toggle="collapse" href="#item-4">
                <i class="fa fa-hospital-o"></i> 
                <?php echo $this->lang->line('text_shop'); ?>  <span class="float-right"><i class="fa fa-angle-down"></i></span>
            </a>
            <div id="item-4" class="collapse 
            <?php
            if (($this->uri->segment('2') == 'product') || ($this->uri->segment('2') == 'order') || ($this->uri->segment('2') == 'courier'))
                echo 'show';
            ?>">
                <ul class="nav flex-column ml-3">  
                    <?php                    
                        if($this->functions->check_permission('product')) {
                    ?>                 
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'product') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>product/">
                            <i class="fa fa-product-hunt"></i>
                            <?php echo $this->lang->line('text_product'); ?>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'offerwall') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>offerwall/">
                            <i class="fa fa-product-hunt"></i>
                            Offer Wall
                        </a>
                    </li>
                    <?php
                        }
                        if($this->functions->check_permission('order')) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'order') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>order/">
                            <i class="fa fa-first-order"></i>
                            <?php echo $this->lang->line('text_order'); ?>
                        </a>
                    </li>
                    <?php
                        }
                        if($this->functions->check_permission('courier')) {                    
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'courier') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>courier/">
                            <i class="fa fa-truck"></i>
                            <?php echo $this->lang->line('text_courier'); ?>
                        </a>
                    </li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
        </li>
        <?php
            }
        ?>
        <?php
            if($this->functions->check_permission('country')) {                    
        ?>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'country') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>country/">
                <i class="fa fa-gamepad"></i>
                <?php echo $this->lang->line('text_country'); ?>
            </a>
        </li>
        <?php
            }
        ?>
        <?php
            if($this->functions->check_permission('pgorder')) {                    
        ?>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'pgorder') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>pgorder/">
                <i class="fa fa-dropbox"></i>
                <?php echo $this->lang->line('text_money_orders'); ?>
            </a>
        </li>
        <?php
            }
        ?>
        <?php
            if($this->functions->check_permission('withdraw')) {                    
        ?>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'withdraw' || $this->uri->segment('3') === '') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>withdraw/">
                <i class="fa fa-money"></i>
                <?php echo $this->lang->line('text_withdraw_requests'); ?>
            </a>
        </li>
        <?php
            }
        ?>        
        <?php
            if($this->functions->check_permission('topplayers') || $this->functions->check_permission('leaderboard')) {                    
        ?>
        <li class="nav-item">
            <a class="nav-link <?php
            if (($this->uri->segment('2') == 'topplayers') || ($this->uri->segment('2') == 'leaderboard'))
                echo 'active';
            ?>"  data-toggle="collapse" href="#item-3">
                <i class="fa fa-file-text-o"></i>
                <?php echo $this->lang->line('text_reports'); ?> <span class="float-right"><i class="fa fa-angle-down"></i></span>
            </a>
            <div id="item-3" class="collapse 
            <?php
            if (($this->uri->segment('2') == 'topplayers') || ($this->uri->segment('2') == 'leaderboard'))
                echo 'show';
            ?>">
                <ul class="nav flex-column ml-3">
                    <?php
                        if($this->functions->check_permission('topplayers')) {                    
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'topplayers') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>topplayers/">
                            <i class="fa fa-trophy"></i>
                            <?php echo $this->lang->line('text_top_players'); ?>
                        </a>
                    </li>
                    <?php
                        }
                        if($this->functions->check_permission('leaderboard')) {                    
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'leaderboard') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>leaderboard/">
                            <i class="fa fa-mortar-board"></i>
                            <?php echo $this->lang->line('text_leaderboard'); ?>
                        </a>
                    </li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
        </li>  
        <?php
            }
        ?>      
        <?php 
            if ($this->system->one_signal_notification == '1' && $this->functions->check_permission('custom_notification')) {
        ?>
            <li class="nav-item">
                <a class="nav-link <?php if ($this->uri->segment('2') == 'custom_notification') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>custom_notification/">
                    <i class="fa fa fa-bell-o"></i>
                    <?php echo $this->lang->line('text_one_signal_notification'); ?>
                </a>
            </li>
        <?php
            } 
        ?>
        <?php 
            if ($this->functions->check_permission('announcement')) {
        ?>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'announcement') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>announcement/">
                <i class="fa fa-bullhorn"></i>
                <?php echo $this->lang->line('text_announcement'); ?>
            </a>
        </li>
        <?php
            }   
        ?>
        <?php 
            if ($this->functions->check_permission('lottery')) {
        ?>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'lottery') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>lottery/">
                <i class="fa fa-thumbs-o-up"></i>
                <?php echo $this->lang->line('text_lottery'); ?>
            </a>
        </li>
        <?php
            }
        ?>
        <?php
            if ($this->functions->check_permission('page') || $this->functions->check_permission('download') || $this->functions->check_permission('features') || $this->functions->check_permission('homeheader') || $this->functions->check_permission('tab_content') || $this->functions->check_permission('how_to_play') || $this->functions->check_permission('screenshots')) {        
        ?>
        <li class="nav-item">
            <a class="nav-link
            <?php
            if (($this->uri->segment('2') == 'page') || ($this->uri->segment('2') == 'download') || ($this->uri->segment('2') == 'features') || ($this->uri->segment('2') == 'homeheader') || ($this->uri->segment('2') == 'tab_content') || ($this->uri->segment('2') == 'how_to_play') || ($this->uri->segment('2') == 'screenshots'))
                echo 'active';
            ?>" data-toggle="collapse" href="#item-5">
                <i class="fa fa-cogs"></i> 
                <?php echo $this->lang->line('text_website_setting'); ?> <span class="float-right"><i class="fa fa-angle-down"></i></span>
            </a>
            <div id="item-5" class="collapse 
            <?php
            if (($this->uri->segment('2') == 'page') || ($this->uri->segment('2') == 'download') || ($this->uri->segment('2') == 'features') || ($this->uri->segment('2') == 'homeheader') || ($this->uri->segment('2') == 'tab_content') || ($this->uri->segment('2') == 'how_to_play') || ($this->uri->segment('2') == 'screenshots'))
                echo 'show';
            ?>">
                <ul class="nav flex-column ml-3"> 
                    <?php
                        if($this->functions->check_permission('page')) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($this->uri->segment('2') == 'page') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>page/">
                            <i class="fa fa-file-text"></i>
                            <?php echo $this->lang->line('text_pages'); ?> 
                        </a>
                    </li>
                    <?php
                        }
                        if($this->functions->check_permission('features') || $this->functions->check_permission('homeheader') || $this->functions->check_permission('tab_content') || $this->functions->check_permission('how_to_play') || $this->functions->check_permission('screenshots')) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php
                        if (($this->uri->segment('2') == 'features') || ($this->uri->segment('2') == 'homeheader') || ($this->uri->segment('2') == 'tab_content') || ($this->uri->segment('2') == 'how_to_play') || ($this->uri->segment('2') == 'screenshots'))
                            echo 'active';
                        ?>"  data-toggle="collapse" href="#item-2">
                            <i class="fa fa-home"></i>
                            <?php echo $this->lang->line('text_home_page_setting'); ?> <span class="float-right"><i class="fa fa-angle-down"></i></span>
                        </a>
                        <div id="item-2" class="collapse 
                        <?php
                        if (($this->uri->segment('2') == 'features') || ($this->uri->segment('2') == 'homeheader') || ($this->uri->segment('2') == 'tab_content') || ($this->uri->segment('2') == 'how_to_play') || ($this->uri->segment('2') == 'screenshots'))
                            echo 'show';
                        ?>">
                            <ul class="nav flex-column ml-3">
                                <?php
                                    if($this->functions->check_permission('features')) {
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php
                                    if (($this->uri->segment('2') == 'homeheader'))
                                        echo 'active';
                                    ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>homeheader/">
                                        <i class="fa fa-header"></i>
                                        <?php echo $this->lang->line('text_main_banner'); ?>
                                    </a>
                                </li>   
                                <?php
                                    }
                                    if($this->functions->check_permission('screenshots')) {
                                ?>           
                                <li class="nav-item">
                                    <a class="nav-link <?php
                                    if (($this->uri->segment('2') == 'screenshots'))
                                        echo 'active';
                                    ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>screenshots/">
                                        <i class="fa fa-camera-retro"></i>
                                        <?php echo $this->lang->line('text_app_screenshots'); ?>
                                    </a>
                                </li>
                                <?php
                                    }
                                    if($this->functions->check_permission('features')) {
                                ?> 
                                <li class="nav-item">
                                    <a class="nav-link <?php
                                    if (($this->uri->segment('2') == 'features'))
                                        echo 'active';
                                    ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>features/">
                                        <i class="fa fa-th"></i>
                                        <?php echo $this->lang->line('text_features_tab'); ?>
                                    </a>
                                </li>
                                <?php
                                    }
                                    if($this->functions->check_permission('tab_content')) {
                                ?> 
                                <li class="nav-item">
                                    <a class="nav-link <?php
                                    if (($this->uri->segment('2') == 'tab_content'))
                                        echo 'active';
                                    ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>tab_content/">
                                        <i class="fa fa-th-list"></i>
                                        <?php echo $this->lang->line('text_features'); ?>
                                    </a>
                                </li>
                                <?php
                                    }
                                    if($this->functions->check_permission('how_to_play')) {
                                ?> 
                                <li class="nav-item">
                                    <a class="nav-link <?php
                                    if (($this->uri->segment('2') == 'how_to_play'))
                                        echo 'active';
                                    ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>how_to_play/">
                                        <i class="fa fa-play-circle"></i>
                                        <?php echo $this->lang->line('text_howtoplay'); ?>
                                    </a>
                                </li>  
                                <?php
                                    }                                    
                                ?>                               
                            </ul>
                        </div>
                    </li>    
                    <?php
                        }
                        if($this->functions->check_permission('download')) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php
                        if (($this->uri->segment('2') == 'download'))
                            echo 'active';
                        ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>download/">
                            <i class="fa fa-download"></i>
                            <?php echo $this->lang->line('text_howtoinstall'); ?>
                        </a>
                    </li> 
                    <?php
                        }
                    ?>
                </ul>
        </li>
        <?php
            }
        ?>
        <?php
            if($this->functions->check_permission('appsetting') || $this->functions->check_permission('currency') || $this->functions->check_permission('method') || $this->functions->check_permission('pgdetail') || $this->functions->check_permission('youtube') || $this->functions->check_permission('banner')) {
        ?>
        <li class="nav-item">
            <a class="nav-link
            <?php
            if (($this->uri->segment('2') == 'appsetting') || ($this->uri->segment('2') == 'currency') || ($this->uri->segment('3') == 'method') || ($this->uri->segment('2') == 'pgdetail') || ($this->uri->segment('2') == 'youtube') || ($this->uri->segment('2') == 'changepassword') || ($this->uri->segment('2') == 'slider') || ($this->uri->segment('2') == 'banner'))
                echo 'active';
            ?>" data-toggle="collapse" href="#item-1">
                <i class="fa fa-cogs"></i> 
                <?php echo $this->lang->line('text_appsetting'); ?>  <span class="float-right"><i class="fa fa-angle-down"></i></span>
            </a>
            <div id="item-1" class="collapse 
            <?php
            if (($this->uri->segment('2') == 'appsetting') || ($this->uri->segment('2') == 'currency') || ($this->uri->segment('3') == 'method') || ($this->uri->segment('2') == 'pgdetail') || ($this->uri->segment('2') == 'youtube') || ($this->uri->segment('2') == 'changepassword') || ($this->uri->segment('2') == 'slider') || ($this->uri->segment('2') == 'banner'))
                echo 'show';
            ?>">
                <ul class="nav flex-column ml-3">    
                    <?php
                        if($this->functions->check_permission('appsetting')) {
                    ?>               
                    <li class="nav-item">
                        <a class="nav-link <?php
                        if (($this->uri->segment('2') == 'appsetting'))
                            echo 'active';
                        ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>appsetting/">
                            <i class="fa fa-android"></i>
                            <?php echo $this->lang->line('text_appsetting'); ?>
                        </a>
                    </li>
                    <?php
                        }
                        if($this->functions->check_permission('currency')) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php
                        if (($this->uri->segment('2') == 'currency'))
                            echo 'active';
                        ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>currency/">
                            <i class="fa fa-dollar"></i>
                            <?php echo $this->lang->line('text_currency_settings'); ?>
                        </a>
                    </li>
                    <?php
                        }
                        if($this->functions->check_permission('withdraw_method')) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php
                        if (($this->uri->segment('3') == 'method'))
                            echo 'active';
                        ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>withdraw/method">
                            <i class="fa fa-money"></i>
                            <?php echo $this->lang->line('text_withdraw_method'); ?>
                        </a>
                    </li>
                    <?php
                        }
                        if($this->functions->check_permission('pgdetail')) {
                    ?>
                    <li class="nav-item ">
                        <a class="nav-link <?php
                        if (($this->uri->segment('2') == 'pgdetail'))
                            echo 'active';
                        ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>pgdetail/">
                            <i class="fa fa-credit-card"></i>
                            <?php echo $this->lang->line('text_payment_method'); ?>
                        </a>
                    </li>
                    <?php
                        }
                        if($this->functions->check_permission('youtube')) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php
                        if (($this->uri->segment('2') == 'youtube'))
                            echo 'active';
                        ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>youtube/">
                            <i class="fa fa-youtube"></i>
                            <?php echo $this->lang->line('text_app_tutorial'); ?>
                        </a>
                    </li> 
                    <?php
                        }
                        if($this->functions->check_permission('slider')) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php
                        if ($this->uri->segment('2') == 'slider')
                            echo 'active';
                        ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>slider/">
                            <i class="fa fa-sliders"></i>
                            <?php echo $this->lang->line('text_slider'); ?>
                        </a>
                    </li> 
                    <?php
                        }
                        if($this->functions->check_permission('banner')) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php
                        if ($this->uri->segment('2') == 'banner')
                            echo 'active';
                        ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>banner/">
                            <i class="fa fa-flag"></i>
                            <?php echo $this->lang->line('text_banner'); ?>
                        </a>
                    </li> 
                    <?php
                        }                        
                    ?>
                </ul>
            </div>
        </li>
        <?php
            }
        ?>
        <?php
            if($this->session->userdata('id') == 1){
        ?>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'admin') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>admin/">
                <i class="fa fa-users"></i>
                <?php echo $this->lang->line('text_admin'); ?>
            </a>
        </li>    
        <?php
            }
            if($this->functions->check_permission('license')) {
        ?>         
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'license') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_view_admin ?>license/">
                <i class="fa fa fa-bug"></i>
                <?php echo $this->lang->line('text_license'); ?>
            </a>
        </li>
        <?php
            }
        ?>
    </ul>
</div>