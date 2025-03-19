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
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><?php echo $this->lang->line('text_slider'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>slider/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_slider'); ?></strong></div>
                                <div class="card-body">
                                    <form method="POST" id="validate" enctype="multipart/form-data" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>slider/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>slider/edit<?php } ?>">                                                                                                                                 
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="slider_title"><?php echo $this->lang->line('text_title'); ?><span class="required" aria-required="true"> * </span></label>
                                                <input type="text" class="form-control" name="slider_title" value="<?php if (isset($slider_title)) echo $slider_title;elseif (isset($slider_detail['slider_title'])) echo $slider_detail['slider_title'] ?>" >
                                                <?php echo form_error('slider_title', '<em style="color:red">', '</em>'); ?>
                                            </div> 
                                            <div class="form-group col-md-offset-1 col-md-6">
                                                <label for="slider"><?php echo $this->lang->line('text_image'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                <input id="slider_image" type="file" class="file-input d-block" name="slider_image">
                                                <?php echo form_error('slider_image', '<em style="color:red">', '</em>'); ?>
                                                <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_1000x500'); ?></p>    
                                                <?php if (isset($slider_detail['slider_image']) && file_exists($this->slider_image . $slider_detail['slider_image'])) { ?>
                                                    <br>
                                                    <img src ="<?php echo base_url() . $this->slider_image . "thumb/100x100_" . $slider_detail['slider_image'] ?>" >
                                                <?php } ?>
                                                <input type="hidden" id="file-input" name="old_slider_image"  value="<?php echo (isset($slider_detail['slider_image'])) ? $slider_detail['slider_image'] : ''; ?>" class="form-control-file">                                                   
                                                <input type="hidden" id="file-input" name="slider_id"  value="<?php echo (isset($slider_detail['slider_id'])) ? $slider_detail['slider_id'] : ''; ?>" class="form-control-file">                                                   
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="slider_link_type"><?php echo $this->lang->line('text_link_type'); ?><span class="required" aria-required="true"> * </span></label>
                                                <div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input id="app" name="slider_link_type" type="radio" class="custom-control-input" value='app' <?php
                                                        if (isset($slider_detail['slider_link_type']) && $slider_detail['slider_link_type'] == 'app') {
                                                            echo 'checked';
                                                        } elseif (isset($slider_link_type) && $slider_link_type == 'app') {
                                                            echo 'checked';
                                                        }
                                                        ?>>&nbsp;
                                                        <label class="custom-control-label" for="app"><?php echo $this->lang->line('text_app'); ?></label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input id="web" name="slider_link_type" type="radio" class="custom-control-input" value='web' <?php
                                                        if (isset($slider_detail['slider_link_type']) && $slider_detail['slider_link_type'] == 'web') {
                                                            echo 'checked';
                                                        } elseif (isset($slider_link_type) && $slider_link_type == 'web') {
                                                            echo 'checked';
                                                        }
                                                        ?> >&nbsp;
                                                        <label class="custom-control-label" for="web"><?php echo $this->lang->line('text_web'); ?></label>
                                                    </div>
                                                    <?php echo form_error('slider_link_type', '<em style="color:red">', '</em>'); ?> 
                                                </div>
                                            </div>
                                            <div class="col-md-6 d-none" id="web-div">
                                                <div class="form-group">
                                                    <label for="web_slider_link"><?php echo $this->lang->line('text_link'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                    <input type="text" class="form-control" name="web_slider_link" value="<?php if (isset($slider_link) && $slider_link_type == 'web') echo $slider_link;elseif (isset($slider_detail['slider_link']) && $slider_detail['slider_link_type'] == 'web') echo $slider_detail['slider_link'] ?>">
                                                    <?php echo form_error('web_slider_link', '<em style="color:red">', '</em>'); ?>       
                                                </div>
                                            </div>
                                            <!--                                            <div class="col-md-6 d-none" id="app-div">
                                                                                            <div class="form-group">
                                                                                                <label for="app_slider_link"><?php echo $this->lang->line('text_link'); ?><span class="required" aria-required="true"> * </span></label>
                                                                                                <div>
                                                                                                    <div class="custom-control custom-radio">
                                                                                                        <input id="game" name="app_slider_link" type="radio" class="custom-control-input" value='game' <?php
                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'game') {
                                                echo 'checked';
                                            } elseif (isset($slider_link) && $slider_link == 'game') {
                                                echo 'checked';
                                            }
                                            ?>>&nbsp;
                                                                                                        <label class="custom-control-label" for="game">Game</label>
                                                                                                    </div>
                                                                                                    <div class="custom-control custom-radio">
                                                                                                        <input id="match" name="app_slider_link" type="radio" class="custom-control-input" value='match' <?php
                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'match') {
                                                echo 'checked';
                                            } elseif (isset($slider_link) && $slider_link == 'match') {
                                                echo 'checked';
                                            }
                                            ?> >&nbsp;
                                                                                                        <label class="custom-control-label" for="match">Match</label>
                                                                                                    </div>
                                            <?php echo form_error('app_slider_link', '<em style="color:red">', '</em>'); ?>       
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>-->
                                            <div class="col-md-6 d-none" id="app-div">
                                                <div class="form-group">
                                                    <label for="app_slider_link"><?php echo $this->lang->line('text_link'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Refer and Earn" name="app_slider_link" type="radio" class="custom-control-input" value='Refer and Earn' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'Refer and Earn') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'Refer and Earn') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="Refer and Earn"><?php echo $this->lang->line('text_refer_earn'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Luckey Draw" name="app_slider_link" type="radio" class="custom-control-input" value='Luckey Draw' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'Luckey Draw') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'Luckey Draw') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Luckey Draw"><?php echo $this->lang->line('text_luckey_draw'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Buy Product" name="app_slider_link" type="radio" class="custom-control-input" value='Buy Product' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'Buy Product') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'Buy Product') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Buy Product"><?php echo $this->lang->line('text_buy_product'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Watch and Earn" name="app_slider_link" type="radio" class="custom-control-input" value='Watch and Earn' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'Watch and Earn') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'Watch and Earn') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Watch and Earn"><?php echo $this->lang->line('text_watch_earn'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="My Profile" name="app_slider_link" type="radio" class="custom-control-input" value='My Profile' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'My Profile') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'My Profile') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="My Profile"><?php echo $this->lang->line('text_my_profile'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="My Wallet" name="app_slider_link" type="radio" class="custom-control-input" value='My Wallet' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'My Wallet') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'My Wallet') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="My Wallet"><?php echo $this->lang->line('text_my_wallet'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="My Matches" name="app_slider_link" type="radio" class="custom-control-input" value='My Matches' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'My Matches') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'My Matches') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="My Matches"><?php echo $this->lang->line('text_my_matches'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="My Statics" name="app_slider_link" type="radio" class="custom-control-input" value='My Statics' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'My Statics') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'My Statics') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="My Statics"><?php echo $this->lang->line('text_my_statistics'); ?></label>
                                                        </div>

                                                        <div class="custom-control custom-radio">
                                                            <input id="My Referral" name="app_slider_link" type="radio" class="custom-control-input" value='My Referral' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'My Referral') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'My Referral') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="My Refferrals"><?php echo $this->lang->line('text_my_referrals'); ?></label>
                                                        </div>

                                                        <div class="custom-control custom-radio">
                                                            <input id="My Rewards" name="app_slider_link" type="radio" class="custom-control-input" value='My Rewards' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'My Rewards') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'My Rewards') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="My Rewards"><?php echo $this->lang->line('text_my_rewards'); ?></label>
                                                        </div>

                                                        <div class="custom-control custom-radio">
                                                            <input id="Announcement" name="app_slider_link" type="radio" class="custom-control-input" value='Announcement' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'Announcement') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'Announcement') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Announcement"><?php echo $this->lang->line('text_announcement'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Top Players" name="app_slider_link" type="radio" class="custom-control-input" value='Top Players' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'Top Players') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'Top Players') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Top Players"><?php echo $this->lang->line('text_top_players'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Leaderboard" name="app_slider_link" type="radio" class="custom-control-input" value='Leaderboard' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'Leaderboard') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'Leaderboard') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Leaderboard"><?php echo $this->lang->line('text_leaderboard'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="App Tutorials" name="app_slider_link" type="radio" class="custom-control-input" value='App Tutorials' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'App Tutorials') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'App Tutorials') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="App Tutorials"><?php echo $this->lang->line('text_app_tutorial'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="About us" name="app_slider_link" type="radio" class="custom-control-input" value='About us' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'About us') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'About us') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="About us"><?php echo $this->lang->line('text_about_us'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Customer Support" name="app_slider_link" type="radio" class="custom-control-input" value='Customer Support' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'Customer Support') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'Customer Support') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Customer Support"><?php echo $this->lang->line('text_customer_supports'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Terms and Condition" name="app_slider_link" type="radio" class="custom-control-input" value='Terms and Condition' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'Terms and Condition') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'Terms and Condition') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Terms and Condition"><?php echo $this->lang->line('text_terms_conditions'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Game" name="app_slider_link" type="radio" class="custom-control-input" value='Game' <?php
                                                            if (isset($slider_detail['slider_link']) && $slider_detail['slider_link'] == 'Game') {
                                                                echo 'checked';
                                                            } elseif (isset($slider_link) && $slider_link == 'Game') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Game"><?php echo $this->lang->line('text_game'); ?></label>
                                                        </div>
                                                        <?php echo form_error('app_slider_link', '<em style="color:red">', '</em>'); ?>       
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 d-none" id="game-div">
                                                <label for="game_id"><?php echo $this->lang->line('text_game'); ?> <span class="required" aria-required="true"> * </span></label>
                                                <select class="form-control" name="game_id">
                                                    <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                    <?php
                                                    foreach ($game_data as $game) {
                                                        ?>
                                                        <option value="<?php echo $game->game_id; ?>" <?php
                                                        if (isset($game_id) && $game_id == $game->game_id)
                                                            echo 'selected';
                                                        elseif (isset($slider_detail['link_id']) && $slider_detail['link_id'] == $game->game_id)
                                                            echo 'selected';
                                                        else
                                                            echo '';
                                                        ?>><?php echo $game->game_name; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                </select>
                                                <?php echo form_error('game_id', '<em style="color:red">', '</em>'); ?>
                                            </div>
                                        </div>                                             
                                        <div class="form-group text-center">
                                            <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary " <?php
                                            if ($this->system->demo_user == 1 && isset($slider_detail['slider_id']) && $slider_detail['slider_id'] <= 2) {
                                                echo 'disabled';
                                            }
                                            ?>>  
                                            <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>slider/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                  
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>
        <script>
<?php if ((isset($slider_link_type) && $slider_link_type == 'app') || (isset($slider_detail) && $slider_detail['slider_link_type'] == 'app')) { ?>
                $("#app-div").removeClass('d-none');
                $("#web-div").addClass('d-none');
<?php } else if ((isset($slider_link_type) && $slider_link_type == 'web') || (isset($slider_detail) && $slider_detail['slider_link_type'] == 'web')) { ?>
                $("#web-div").removeClass('d-none');
                $("#app-div").addClass('d-none');
<?php } if ((isset($slider_link) && $slider_link == 'Game') || (isset($slider_detail) && $slider_detail['slider_link'] == 'Game')) { ?>
                $("#game-div").removeClass('d-none');
<?php } ?>
            $('input[name="slider_link_type"]').change(function () {
                slider_link_type = $('input[name="slider_link_type"]:checked').val();
                if (slider_link_type == 'app') {
                    $("#app-div").removeClass('d-none');
                    $("#web-div").addClass('d-none');
                } else if (slider_link_type == 'web') {
                    $("#web-div").removeClass('d-none');
                    $("#app-div").addClass('d-none');
                    $("#game-div").addClass('d-none');
                }
            });
            $('input[name="app_slider_link"]').change(function () {
                app_slider_link = $('input[name="app_slider_link"]:checked').val();
                if (app_slider_link == 'Game') {
                    $("#game-div").removeClass('d-none');
                } else {
                    $("#game-div").addClass('d-none');
                }
            });
            $(document).ready(function () {
                $.validator.addMethod('filesize', function (value, element, arg) {
                    if ((element.files[0].size <= arg)) {
                        return true;
                    } else {
                        return false;
                    }
                }, '<?php echo $this->lang->line('err_image_size'); ?>');
                $("#validate").validate({
                    rules: {
                        'slider_title': {
                            required: true,
                        },
                        'slider_link_type': {
//                            required: true,
                        },
                        'app_slider_link': {
                            required: function () {
                                if ($('input[name="slider_link_type"]:checked').val() == "app") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                        },
                        'web_slider_link': {
                            required: function () {
                                if ($('input[name="slider_link_type"]:checked').val() == "web") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                            url: function () {
                                if ($('input[name="slider_link_type"]:checked').val() == "web") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                        },
                        'slider_image': {
                            required: function () {
                                if ($('input[name="old_slider_image"]').val() == "") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                            accept: "jpg|png|jpeg",
                        },
                        'game_id': {
                            required: function () {
                                if ($('input[name="app_slider_link"]:checked').val() == "Game") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                        },
                    },
                    messages: {
                        'slider_title': {
                            required: '<?php echo $this->lang->line('err_slider_title_req'); ?>',
                        },
                        'slider_link_type': {
                            required: '<?php echo $this->lang->line('err_slider_link_type_req'); ?>',
                        },
                        'app_slider_link': {
                            required: '<?php echo $this->lang->line('err_app_slider_link_req'); ?>',
                        },
                        'web_slider_link': {
                            required: '<?php echo $this->lang->line('err_web_slider_link_req'); ?>',
                            url: '<?php echo $this->lang->line('err_url_valid'); ?>',
                        },
                        'slider_image': {
                            required: '<?php echo $this->lang->line('err_image_req'); ?>',
                            accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                        },
                        'game_id': {
                            required: '<?php echo $this->lang->line('err_game_id_req'); ?>',
                        },
                    },
                    errorPlacement: function (error, element)
                    {
                        if (element.is(":file"))
                        {
                            error.insertAfter(element);
                        } else if (element.is(":radio"))
                        {
                            error.insertAfter(element.parent().parent().parent());
                        } else
                        {
                            error.insertAfter(element);
                        }
                    },
                });
            });
        </script>
    </body>
</html>