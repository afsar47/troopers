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
                        <h1 class="h2"><?php echo $this->lang->line('text_banner'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>banner/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_banner'); ?></strong></div>
                                <div class="card-body">
                                    <form method="POST" id="validate" enctype="multipart/form-data" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>banner/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>banner/edit<?php } ?>">                                                                                                                                 
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="banner_title"><?php echo $this->lang->line('text_title'); ?><span class="required" aria-required="true"> * </span></label>
                                                <input type="text" class="form-control" name="banner_title" value="<?php if (isset($banner_title)) echo $banner_title;elseif (isset($banner_detail['banner_title'])) echo $banner_detail['banner_title'] ?>" >
                                                <?php echo form_error('banner_title', '<em style="color:red">', '</em>'); ?>
                                            </div> 
                                            <div class="form-group col-md-offset-1 col-md-6">
                                                <label for="banner"><?php echo $this->lang->line('text_image'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                <input id="banner_image" type="file" class="file-input d-block" name="banner_image">
                                                <?php echo form_error('banner_image', '<em style="color:red">', '</em>'); ?>
                                                <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_270x500'); ?></p>    
                                                <?php if (isset($banner_detail['banner_image']) && file_exists($this->banner_image . $banner_detail['banner_image'])) { ?>
                                                    <br>
                                                    <img src ="<?php echo base_url() . $this->banner_image . "thumb/100x100_" . $banner_detail['banner_image'] ?>" >
                                                <?php } ?>
                                                <input type="hidden" id="file-input" name="old_banner_image"  value="<?php echo (isset($banner_detail['banner_image'])) ? $banner_detail['banner_image'] : ''; ?>" class="form-control-file">                                                   
                                                <input type="hidden" id="file-input" name="banner_id"  value="<?php echo (isset($banner_detail['banner_id'])) ? $banner_detail['banner_id'] : ''; ?>" class="form-control-file">                                                   
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="banner_link_type"><?php echo $this->lang->line('text_link_type'); ?><span class="required" aria-required="true"> * </span></label>
                                                <div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input id="app" name="banner_link_type" type="radio" class="custom-control-input" value='app' <?php
                                                        if (isset($banner_detail['banner_link_type']) && $banner_detail['banner_link_type'] == 'app') {
                                                            echo 'checked';
                                                        } elseif (isset($banner_link_type) && $banner_link_type == 'app') {
                                                            echo 'checked';
                                                        }
                                                        ?>>&nbsp;
                                                        <label class="custom-control-label" for="app"><?php echo $this->lang->line('text_app'); ?></label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input id="web" name="banner_link_type" type="radio" class="custom-control-input" value='web' <?php
                                                        if (isset($banner_detail['banner_link_type']) && $banner_detail['banner_link_type'] == 'web') {
                                                            echo 'checked';
                                                        } elseif (isset($banner_link_type) && $banner_link_type == 'web') {
                                                            echo 'checked';
                                                        }
                                                        ?> >&nbsp;
                                                        <label class="custom-control-label" for="web"><?php echo $this->lang->line('text_web'); ?></label>
                                                    </div>
                                                    <?php echo form_error('banner_link_type', '<em style="color:red">', '</em>'); ?>   
                                                </div>
                                            </div>    
                                            <div class="col-md-6 d-none" id="web-div">
                                                <div class="form-group">
                                                    <label for="web_banner_link"><?php echo $this->lang->line('text_link'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                    <input type="text" class="form-control" name="web_banner_link" value="<?php if (isset($banner_link) && $banner_link_type == 'web') echo $banner_link;elseif (isset($banner_detail['banner_link']) && $banner_detail['banner_link_type'] == 'web') echo $banner_detail['banner_link'] ?>">
                                                    <?php echo form_error('web_banner_link', '<em style="color:red">', '</em>'); ?>       
                                                </div>
                                            </div>
                                            <div class="col-md-6 d-none" id="app-div">
                                                <div class="form-group">
                                                    <label for="app_banner_link"><?php echo $this->lang->line('text_link'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <div>
                                                         <div class="custom-control custom-radio">
                                                            <input id="offer wall" name="app_banner_link" type="radio" class="custom-control-input" value='offer wall' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'offer wall') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'offer wall') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="offer wall">offer wall</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Refer and Earn" name="app_banner_link" type="radio" class="custom-control-input" value='Refer and Earn' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'Refer and Earn') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'Refer and Earn') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="Refer and Earn"><?php echo $this->lang->line('text_refer_earn'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Luckey Draw" name="app_banner_link" type="radio" class="custom-control-input" value='Luckey Draw' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'Luckey Draw') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'Luckey Draw') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Luckey Draw"><?php echo $this->lang->line('text_luckey_draw'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Buy Product" name="app_banner_link" type="radio" class="custom-control-input" value='Buy Product' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'Buy Product') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'Buy Product') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Buy Product"><?php echo $this->lang->line('text_buy_product'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Watch and Earn" name="app_banner_link" type="radio" class="custom-control-input" value='Watch and Earn' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'Watch and Earn') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'Watch and Earn') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Watch and Earn"><?php echo $this->lang->line('text_watch_earn'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="My Profile" name="app_banner_link" type="radio" class="custom-control-input" value='My Profile' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'My Profile') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'My Profile') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="My Profile"><?php echo $this->lang->line('text_my_profile'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="My Wallet" name="app_banner_link" type="radio" class="custom-control-input" value='My Wallet' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'My Wallet') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'My Wallet') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="My Wallet"><?php echo $this->lang->line('text_my_wallet'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="My Matches" name="app_banner_link" type="radio" class="custom-control-input" value='My Matches' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'My Matches') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'My Matches') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="My Matches"><?php echo $this->lang->line('text_my_matches'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="My Statics" name="app_banner_link" type="radio" class="custom-control-input" value='My Statics' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'My Statics') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'My Statics') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="My Statics"><?php echo $this->lang->line('text_my_statistics'); ?></label>
                                                        </div>

                                                        <div class="custom-control custom-radio">
                                                            <input id="My Referral" name="app_banner_link" type="radio" class="custom-control-input" value='My Referral' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'My Referral') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'My Referral') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="My Referral"><?php echo $this->lang->line('text_my_referrals'); ?></label>
                                                        </div>

                                                        <div class="custom-control custom-radio">
                                                            <input id="My Rewards" name="app_banner_link" type="radio" class="custom-control-input" value='My Rewards' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'My Rewards') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'My Rewards') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="My Rewards"><?php echo $this->lang->line('text_my_rewards'); ?></label>
                                                        </div>

                                                        <div class="custom-control custom-radio">
                                                            <input id="Announcement" name="app_banner_link" type="radio" class="custom-control-input" value='Announcement' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'Announcement') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'Announcement') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Announcement"><?php echo $this->lang->line('text_announcement'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Top Players" name="app_banner_link" type="radio" class="custom-control-input" value='Top Players' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'Top Players') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'Top Players') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Top Players"><?php echo $this->lang->line('text_top_players'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Leaderboard" name="app_banner_link" type="radio" class="custom-control-input" value='Leaderboard' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'Leaderboard') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'Leaderboard') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Leaderboard"><?php echo $this->lang->line('text_leaderboard'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="App Tutorials" name="app_banner_link" type="radio" class="custom-control-input" value='App Tutorials' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'App Tutorials') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'App Tutorials') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="App Tutorials"><?php echo $this->lang->line('text_app_tutorial'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="About us" name="app_banner_link" type="radio" class="custom-control-input" value='About us' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'About us') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'About us') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="About us"><?php echo $this->lang->line('text_about_us'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Customer Support" name="app_banner_link" type="radio" class="custom-control-input" value='Customer Support' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'Customer Support') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'Customer Support') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Customer Support"><?php echo $this->lang->line('text_customer_supports'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Terms and Condition" name="app_banner_link" type="radio" class="custom-control-input" value='Terms and Condition' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'Terms and Condition') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'Terms and Condition') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Terms and Condition"><?php echo $this->lang->line('text_terms_conditions'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input id="Game" name="app_banner_link" type="radio" class="custom-control-input" value='Game' <?php
                                                            if (isset($banner_detail['banner_link']) && $banner_detail['banner_link'] == 'Game') {
                                                                echo 'checked';
                                                            } elseif (isset($banner_link) && $banner_link == 'Game') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="Game"><?php echo $this->lang->line('text_game'); ?></label>
                                                        </div>
                                                        <?php echo form_error('app_banner_link', '<em style="color:red">', '</em>'); ?>       
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
                                                        elseif (isset($banner_detail['link_id']) && $banner_detail['link_id'] == $game->game_id)
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
                                            if ($this->system->demo_user == 1 && isset($banner_detail['banner_id']) && $banner_detail['banner_id'] <= 2) {
                                                echo 'disabled';
                                            }
                                            ?>>  
                                            <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>banner/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                  
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
<?php if ((isset($banner_link_type) && $banner_link_type == 'app') || (isset($banner_detail) && $banner_detail['banner_link_type'] == 'app')) { ?>
                $("#app-div").removeClass('d-none');
                $("#web-div").addClass('d-none');
<?php } else if ((isset($banner_link_type) && $banner_link_type == 'web') || (isset($banner_detail) && $banner_detail['banner_link_type'] == 'web')) { ?>
                $("#web-div").removeClass('d-none');
                $("#app-div").addClass('d-none');
<?php } if ((isset($banner_link) && $banner_link == 'Game') || (isset($banner_detail) && $banner_detail['banner_link'] == 'Game')) { ?>
                $("#game-div").removeClass('d-none');
<?php } ?>

            $('input[name="banner_link_type"]').change(function () {
                banner_link_type = $('input[name="banner_link_type"]:checked').val();
                if (banner_link_type == 'app') {
                    $("#app-div").removeClass('d-none');
                    $("#web-div").addClass('d-none');
                } else if (banner_link_type == 'web') {
                    $("#web-div").removeClass('d-none');
                    $("#app-div").addClass('d-none');
                    $("#game-div").addClass('d-none');
                }
            });
            $('input[name="app_banner_link"]').change(function () {
                app_banner_link = $('input[name="app_banner_link"]:checked').val();
                if (app_banner_link == 'Game') {
                    $("#game-div").removeClass('d-none');
                } else {
                    $("#game-div").addClass('d-none');
                }
            });
            $(document).ready(function () {
//                console.log($('input[name="banner_link_type"]:checked').val());

                $.validator.addMethod('filesize', function (value, element, arg) {
                    if ((element.files[0].size <= arg)) {
                        return true;
                    } else {
                        return false;
                    }
                }, '<?php echo $this->lang->line('err_image_size'); ?>');
                $("#validate").validate({
                    rules: {
                        'banner_title': {
                            required: true,
                        },
                        'banner_link': {
                            required: true,
                        },
                        'banner_link_type': {
                            required: true,
                        },
                        'app_banner_link': {
                            required: function () {
                                if ($('input[name="banner_link_type"]:checked').val() == "app") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                        },
                        'web_banner_link': {
                            required: function () {
                                if ($('input[name="banner_link_type"]:checked').val() == "web") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                            url: function () {
                                if ($('input[name="banner_link_type"]:checked').val() == "web") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                        },
                        'banner_image': {
                            required: function () {
                                if ($('input[name="old_banner_image"]').val() == "") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                            accept: "jpg|png|jpeg",
                        },
                        'game_id': {
                            required: function () {
                                if ($('input[name="app_banner_link"]:checked').val() == "Game") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                        },

                    },
                    messages: {
                        'banner_title': {
                            required: '<?php echo $this->lang->line('err_banner_title_req'); ?>',
                        },
                        'banner_link_type': {
                            required: '<?php echo $this->lang->line('err_banner_link_type_req'); ?>',
                        },
                        'app_banner_link': {
                            required: '<?php echo $this->lang->line('err_app_banner_link_req'); ?>',
                        },
                        'web_banner_link': {
                            required: '<?php echo $this->lang->line('err_web_banner_link_req'); ?>',
                            url: '<?php echo $this->lang->line('err_url_valid'); ?>',
                        },
                        'banner_image': {
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
                            error.insertAfter(element.parent().parent());
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