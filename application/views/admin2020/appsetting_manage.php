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
                        <h1 class="h2"><?php echo $this->lang->line('text_appsetting'); ?></h1>
                    </div>
                    <?php if ($this->session->flashdata('notification')) { ?>
                        <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                            <span><?php echo $this->session->flashdata('notification'); ?></span>
                        </div>
                    <?php } ?>
                    <?php if ($this->session->flashdata('error')) { ?>
                        <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                            <span><?php echo $this->session->flashdata('error'); ?></span>
                        </div>
                    <?php } ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_appupload'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  id="app-upload-form"  enctype="multipart/form-data" novalidate="" method="POST" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>appsetting<?php } ?>">     
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label for="app_upload"><?php echo $this->lang->line('text_app'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                    <input id="app_upload" type="file" class="file-input" name="app_upload">   <br>                                                
                                                    <?php echo form_error('app_upload', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                  
                                                <div class="form-group col-md-3">
                                                    <label for="app_version"><?php echo $this->lang->line('text_version_code'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="app_version" type="text" class="form-control" name="app_version">                                                   
                                                    <?php echo form_error('app_version', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class=" form-group col-md-3">
                                                    <label for="force_update"><?php echo $this->lang->line('text_force_update'); ?><span class="required" aria-required="true"> * </span></label>                                                
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="force_update_yes" name="force_update" type="radio" class="custom-control-input" value="Yes">&nbsp;
                                                            <label class="custom-control-label" for="force_update_yes"><?php echo $this->lang->line('text_yes'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="force_update_no" name="force_update" type="radio" class="custom-control-input" value="No" >&nbsp;
                                                            <label class="custom-control-label" for="force_update_no"><?php echo $this->lang->line('text_no'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('force_update', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class=" form-group col-md-3">                                              
                                                    <div class="custom-control custom-checkbox">
                                                        <input id="force_logged_out" name="force_logged_out" type="checkbox" class="custom-control-input" value="Yes">&nbsp;
                                                        <label class="custom-control-label" for="force_logged_out"><?php echo $this->lang->line('text_logout_all_user'); ?></label>
                                                    </div>                                                    
                                                </div>
                                                <div class="form-group col-12">
                                                    <label for="app_description"><?php echo $this->lang->line('text_app_desc'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <textarea id="app_description" type="text" class="form-control ckeditor" id="editor1" name="app_description"></textarea>                                                   
                                                    <?php echo form_error('app_description', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary " <?php
                                                if ($this->system->demo_user == 1) {
                                                    echo 'disabled';
                                                }
                                                ?>>    
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>appsetting/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                 
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_appupload'); ?></strong></div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div class="card card-primary">
                                            <div class="card-header"><h4><?php echo $this->lang->line('text_appupload'); ?></h4></div>
                                            <div class="card-body">
                                                <form name="frmappuploadlist" method="post" action="<?php echo base_url() . $this->path_to_view_admin ?>appsetting">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" id="manage_tbl">
                                                            <thead>
                                                                <tr>
                                                                    <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_app'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_version_code'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_force_update'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_logout'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_link'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_date'); ?></th>
                                                                </tr>   
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_app'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_version_code'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_force_update'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_logout'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_link'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_date'); ?></th>
                                                                </tr>   
                                                            </tfoot>
                                                        </table>
                                                        <input type="hidden" name="action" />
                                                        <input type="hidden" name="appuploadid" />
                                                        <input type="hidden" name="publish" /> 
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--                    <div class="row mb-4">
                                            <div class="col-md-12">
                                                <div class="card bg-light text-dark">
                                                    <div class="card-header"><strong>Announcement</strong></div>
                                                    <div class="card-body">
                                                        <div class="col-md-12">
                                                            <form class="needs-validation"  id="announcement-form"  enctype="multipart/form-data" novalidate="" method="POST" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>appsetting<?php } ?>">     
                                                                <div class="row">
                                                                    <div class="form-group col-12">
                                                                        <label for="announcement_desc">Announcement<span class="required" aria-required="true"> * </span></label>
                                                                        <textarea id="announcement_desc" type="text" class="form-control" name="announcement_desc"></textarea>                                                   
                    <?php echo form_error('announcement_desc', '<em style="color:red">', '</em>'); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group text-center">
                                                                    <input type="submit" value="Submit" name="announcement_submit" class="btn btn-primary ">    
                                                                    <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>appsetting/" name="cancel">Cancel</a>                                                 
                                                                </div>
                                                            </form>
                                                            <form name="frmannouncementlist" method="post" action="<?php echo base_url() . $this->path_to_view_admin ?>appsetting">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered" id="manage_tbl1">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Sr No.</th>
                                                                                <th>Announcement</th>
                                                                                <th>Date</th>
                                                                                <th>Action</th>
                                                                            </tr>   
                                                                        </thead>
                                                                        <tbody>
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <th>Sr No.</th>
                                                                                <th>Announcement</th>
                                                                                <th>Date</th>
                                                                                <th>Action</th>
                                                                            </tr>   
                                                                        </tfoot>
                                                                    </table>
                                                                    <input type="hidden" name="action" />
                                                                    <input type="hidden" name="announcementid" />
                                                                    <input type="hidden" name="publish" /> 
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_other_setting'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  id="app-form"  enctype="multipart/form-data" novalidate="" method="POST" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>appsetting<?php } ?>">     
                                            <h6><b><?php echo $this->lang->line('text_referral_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="active_referral"><?php echo $this->lang->line('text_active_referral'); ?><span class="required" aria-required="true"> * </span></label>                                                
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="yes" name="active_referral" type="radio" class="custom-control-input" value="1" <?php
                                                            if (isset($active_referral) && $active_referral == 1) {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->active_referral) && $this->system->active_referral == 1) {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="yes"><?php echo $this->lang->line('text_yes'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="no" name="active_referral" type="radio" class="custom-control-input" value="0" <?php
                                                            if (isset($active_referral) && $active_referral == 0) {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->active_referral) && $this->system->active_referral == 0) {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="no"><?php echo $this->lang->line('text_no'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('active_referral', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                
                                            </div>
                                            <div class="row referral-div <?php
                                                if (isset($active_referral) && $active_referral == 0)
                                                    echo 'd-none';
                                                elseif (isset($this->system->active_referral) && $this->system->active_referral == 0)
                                                    echo 'd-none';
                                                ?>">
                                                <div class="form-group col-md-6">
                                                    <label for="referral"><?php echo $this->lang->line('text_main_user'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="referral" type="number" name="referral" class="form-control" value="<?php if (isset($referral)) echo $referral;elseif (isset($this->system->referral)) echo $this->system->referral; ?>">                                              
                                                    <?php echo form_error('referral', '<em style="color:red">', '</em>'); ?>
                                                </div>  
                                                <div class="form-group col-md-6">
                                                    <label for="referral_level1"><?php echo $this->lang->line('text_referral_user'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="referral_level1" type="number" class="form-control" name="referral_level1" value="<?php if (isset($referral_level1)) echo $referral_level1;elseif (isset($this->system->referral_level1)) echo $this->system->referral_level1; ?>">                                                   
                                                    <?php echo form_error('referral_level1', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="referral_min_paid_fee"><?php echo $this->lang->line('text_referral_min_paid_fee'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="referral_min_paid_fee" type="number" class="form-control" name="referral_min_paid_fee" value="<?php if (isset($referral_min_paid_fee)) echo $referral_min_paid_fee;elseif (isset($this->system->referral_min_paid_fee)) echo $this->system->referral_min_paid_fee; ?>">                                                   
                                                    <?php echo form_error('referral_min_paid_fee', '<em style="color:red">', '</em>'); ?>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="referandearn_description  "><?php echo $this->lang->line('text_refer_earn_desc'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <textarea id="referandearn_description" type="text" class="form-control" name="referandearn_description"><?php if (isset($referandearn_description)) echo $referandearn_description;elseif (isset($this->system->referandearn_description)) echo $this->system->referandearn_description; ?></textarea>
                                                    <?php echo form_error('referandearn_description', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                </div>
                                            <!--                                            <h6><b>Default Game Rules</b></h6>
                                                                                        <hr>
                                                                                        <div class="row">                                               
                                                                                            <div class="form-group col-12">
                                                                                                <label for="game_rules">Game Rules</label>
                                                                                                <textarea id="game_rules" type="text"  class="form-control ckeditor" name="game_rules" ><?php if (isset($game_rules)) echo $game_rules;elseif (isset($this->system->game_rules)) echo $this->system->game_rules ?></textarea>
                                            <?php echo form_error('game_rules', '<em style="color:red">', '</em>'); ?>
                                                                                            </div>  
                                                                                        </div>-->
                                            <h6><b><?php echo $this->lang->line('text_match_url_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">                                               
                                                <div class="form-group col-12">
                                                    <label for="match_url"><?php echo $this->lang->line('text_match_url'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <textarea id="match_url" type="text"  class="form-control" name="match_url" ><?php if (isset($match_url)) echo $match_url;elseif (isset($this->system->match_url)) echo $this->system->match_url ?></textarea>
                                                    <?php echo form_error('match_url', '<em style="color:red">', '</em>'); ?>
                                                </div>  
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_share_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="share_description "><?php echo $this->lang->line('text_share_desc'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <textarea id="share_description" type="text" name="share_description" class="form-control"><?php if (isset($share_description)) echo $share_description;elseif (isset($this->system->share_description)) echo $this->system->share_description; ?></textarea>                                         
                                                    <?php echo form_error('share_description', '<em style="color:red">', '</em>'); ?>
                                                </div>   
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_challenge_win_coin_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="coin_under_hundrade "><?php echo $this->lang->line('text_coin_under_hundrade'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="coin_under_hundrade" type="text" name="coin_under_hundrade" class="form-control" value="<?php if (isset($coin_under_hundrade)) echo $coin_under_hundrade;elseif (isset($this->system->coin_under_hundrade)) echo $this->system->coin_under_hundrade; ?>">
                                                    <?php echo form_error('coin_under_hundrade', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                                <div class="form-group col-md-6">
                                                    <label for="coin_up_to_hundrade "><?php echo $this->lang->line('text_coin_up_to_hundrade'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="coin_up_to_hundrade" type="text" name="coin_up_to_hundrade" class="form-control" value="<?php if (isset($coin_up_to_hundrade)) echo $coin_up_to_hundrade;elseif (isset($this->system->coin_up_to_hundrade)) echo $this->system->coin_up_to_hundrade; ?>">
                                                    <?php echo form_error('coin_up_to_hundrade', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_withdraw_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="min_withdrawal "><?php echo $this->lang->line('text_min_withdrawal'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="min_withdrawal" type="text" name="min_withdrawal" class="form-control" value="<?php if (isset($min_withdrawal)) echo $min_withdrawal;elseif (isset($this->system->min_withdrawal)) echo $this->system->min_withdrawal; ?>">
                                                    <?php echo form_error('min_withdrawal', '<em style="color:red">', '</em>'); ?>
                                                </div>  
                                                <div class="form-group col-md-6">
                                                    <label for="min_require_balance_for_withdrawal"><?php echo $this->lang->line('text_min_require_balance_for_withdrawal'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="min_require_balance_for_withdrawal" type="text" name="min_require_balance_for_withdrawal" class="form-control" value="<?php if (isset($min_require_balance_for_withdrawal)) echo $min_require_balance_for_withdrawal;elseif (isset($this->system->min_require_balance_for_withdrawal)) echo $this->system->min_require_balance_for_withdrawal; ?>">
                                                    <?php echo form_error('min_require_balance_for_withdrawal', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_deposit_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="min_addmoney "><?php echo $this->lang->line('text_min_deposit'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="min_addmoney" type="text" name="min_addmoney" class="form-control" value="<?php if (isset($min_addmoney)) echo $min_addmoney;elseif (isset($this->system->min_addmoney)) echo $this->system->min_addmoney; ?>">
                                                    <?php echo form_error('min_addmoney', '<em style="color:red">', '</em>'); ?>
                                                </div>   
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_place_point_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="place_point_show"><?php echo $this->lang->line('text_place_point_show'); ?><span class="required" aria-required="true"> * </span></label>                                                
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="place_point_show_yes" name="place_point_show" type="radio" class="custom-control-input" value="yes" <?php
                                                            if (isset($place_point_show) && $place_point_show == 'yes') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->place_point_show) && $this->system->place_point_show == 'yes') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="place_point_show_yes"><?php echo $this->lang->line('text_yes'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="place_point_show_no" name="place_point_show" type="radio" class="custom-control-input" value="no" <?php
                                                            if (isset($place_point_show) && $place_point_show == 0) {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->place_point_show) && $this->system->place_point_show == 'no') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="place_point_show_no"><?php echo $this->lang->line('text_no'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('active_referral', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_smtp_mail_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="smtp_host"><?php echo $this->lang->line('text_smtp_host'); ?><span class="required" aria-required="true"> * </span></label><br>   
                                                    <input id="smtp_host" type="text" name="smtp_host" class="form-control" value="<?php if (isset($smtp_host)) echo $smtp_host;elseif (isset($this->system->smtp_host)) echo $this->system->smtp_host; ?>">
                                                    <?php echo form_error('smtp_host', '<em style="color:red">', '</em>'); ?>
                                                </div>                                            
                                                <div class="form-group col-md-6">
                                                    <label for="smtp_user "><?php echo $this->lang->line('text_smtp_user'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="smtp_user" type="text" name="smtp_user" class="form-control" value="<?php if (isset($smtp_user)) echo $smtp_user;elseif (isset($this->system->smtp_user)) echo $this->system->smtp_user; ?>">
                                                    <?php echo form_error('smtp_user', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                                <div class="form-group col-md-6">
                                                    <label for="smtp_pass "> <?php echo $this->lang->line('text_smtp_pass'); ?> <span class="required" aria-required="true"> * </span></label>
                                                    <input id="smtp_pass" type="text" name="smtp_pass" class="form-control" value='<?php if (isset($smtp_pass)) echo $smtp_pass;elseif ($this->system->demo_user == 1 && isset($this->system->smtp_pass)) echo urldecode(str_replace($this->system->smtp_pass, $this->functions->stars_smtp_pass($this->system->smtp_pass), $this->system->smtp_pass));elseif (isset($this->system->smtp_pass)) echo urldecode($this->system->smtp_pass); ?>'>
                                                    <?php echo form_error('smtp_pass', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                 
                                                <div class="form-group col-md-6">
                                                    <label for="smtp_port "> <?php echo $this->lang->line('text_smtp_port'); ?> <span class="required" aria-required="true"> * </span></label>
                                                    <input id="smtp_port" type="text" name="smtp_port" class="form-control" value="<?php if (isset($smtp_port)) echo $smtp_port;elseif (isset($this->system->smtp_port)) echo $this->system->smtp_port; ?>">
                                                    <?php echo form_error('smtp_port', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                                <div class="form-group col-md-6">
                                                    <label for="smtp_secure "><?php echo $this->lang->line('text_smtp_secure'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="smtp_secure_tls" name="smtp_secure" type="radio" class="custom-control-input" value="tls" <?php
                                                            if (isset($smtp_secure) && $smtp_secure == 'tls') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->smtp_secure) && $this->system->smtp_secure == 'tls') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="smtp_secure_tls">TLS</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="smtp_secure_ssl" name="smtp_secure" type="radio" class="custom-control-input" value="ssl" <?php
                                                            if (isset($smtp_secure) && $smtp_secure == 'ssl') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->smtp_secure) && $this->system->smtp_secure == 'ssl') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="smtp_secure_ssl">SSL</label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('smtp_secure', '<em style="color:red">', '</em>'); ?>
                                                </div>  
                                            </div>
                                            <!-- <h6><b><?php echo $this->lang->line('text_select_web_user_template'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="user_template "><?php echo $this->lang->line('text_web_template'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <select id="user_template" name="user_template" class="form-control">
                                                        <option value="" ><?php echo $this->lang->line('text_select'); ?></option>

                                                        <option value="bmuseradmin" <?php if (isset($user_template) && $user_template == 'bmuseradmin') echo 'selected';elseif (isset($this->system->user_template) && $this->system->user_template == 'bmuseradmin') echo 'selected'; ?>><?php echo $this->lang->line('text_user_admin'); ?></option>
                                                        <option value="bmuserapp" <?php if (isset($user_template) && $user_template == 'bmuserapp') echo 'selected';elseif (isset($this->system->user_template) && $this->system->user_template == 'bmuserapp') echo 'selected'; ?>><?php echo $this->lang->line('text_user_mobile'); ?></option>
                                                    </select>
                                                    <?php echo form_error('user_template', '<em style="color:red">', '</em>'); ?>
                                                </div>   
                                            </div> -->
                                            <h6><b><?php echo $this->lang->line('text_one_signal_notification_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="one_signal_notification"><?php echo $this->lang->line('text_one_signal_notification'); ?><span class="required" aria-required="true"> * </span></label><br>   
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="one_signal_notification_yes" name="one_signal_notification" type="radio" class="custom-control-input" value="1" <?php
                                                            if (isset($one_signal_notification) && $one_signal_notification == '1') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->one_signal_notification) && $this->system->one_signal_notification == '1') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="one_signal_notification_yes"><?php echo $this->lang->line('text_yes'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="one_signal_notification_no" name="one_signal_notification" type="radio" class="custom-control-input" value="0" <?php
                                                            if (isset($one_signal_notification) && $one_signal_notification == '0') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->one_signal_notification) && $this->system->one_signal_notification == '0') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="one_signal_notification_no"><?php echo $this->lang->line('text_no'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('one_signal_notification', '<em style="color:red">', '</em>'); ?>

                                                </div>
                                            </div>
                                            <div class="row one_signal_notification-div <?php
                                            if ((isset($one_signal_notification) && $one_signal_notification == '0'))
                                                echo 'd-none';
                                            elseif (isset($this->system->one_signal_notification) && $this->system->one_signal_notification == '0')
                                                echo 'd-none';
                                            ?>">
                                                <div class="form-group col-md-12">
                                                    <label for="app_id "> <?php echo $this->lang->line('text_server_key'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="app_id" type="text" name="app_id" class="form-control" value="<?php if (isset($app_id)) echo $app_id;elseif (isset($this->system->app_id)) echo $this->system->app_id; ?>">
                                                    <?php echo form_error('app_id', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                                <!-- <div class="form-group col-md-6">
                                                    <label for="rest_api_key "><?php echo $this->lang->line('text_rest_api_key'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="rest_api_key" type="text" name="rest_api_key" class="form-control" value="<?php if (isset($rest_api_key)) echo $rest_api_key;elseif (isset($this->system->rest_api_key)) echo $this->system->rest_api_key; ?>">
                                                    <?php echo form_error('rest_api_key', '<em style="color:red">', '</em>'); ?>
                                                </div>  -->
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_admin_user_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="admin_user "><?php echo $this->lang->line('text_admin_user'); ?><span class="required" aria-required="true"> * </span><span data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('tooltip_admin_user'); ?>"><i class="fa fa-question-circle"></i></span></label>
                                                    <select class="form-control" name="admin_user">
                                                        <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                        <?php
                                                        foreach ($users_data as $users) {
                                                            ?>
                                                            <option value="<?php echo $users->member_id; ?>" <?php
                                                            if (isset($admin_user) && $admin_user == $users->member_id)
                                                                echo 'selected';
                                                            elseif (isset($this->system->admin_user) && $this->system->admin_user == $users->member_id)
                                                                echo 'selected';
                                                            else
                                                                echo '';
                                                            ?>><?php echo $users->user_name; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                    </select>
                                                    <?php echo form_error('admin_user', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                                <div class="form-group col-md-6">
                                                    <label for="admin_profit ">Admin Profit<span class="required" aria-required="true"> * </span></label>
                                                    <div class="input-group mb-3">
                                                        <input id="admin_profit" type="text" name="admin_profit" class="form-control" value="<?php if (isset($admin_profit)) echo $admin_profit;elseif (isset($this->system->admin_profit)) echo $this->system->admin_profit; ?>">                                                        
                                                        <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('admin_profit', '<em style="color:red">', '</em>'); ?>
                                                </div>   
                                            </div>                                                                                        
                                            <h6><b><?php echo $this->lang->line('text_msg91_otp_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="msg91_otp "><?php echo $this->lang->line('text_msg91_otp'); ?><span class="required" aria-required="true"> * </span><span data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('tooltip_msg91'); ?>"><i class="fa fa-question-circle"></i></span></label>
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="msg91_otp_yes" name="msg91_otp" type="radio" class="custom-control-input" value="1" <?php
                                                            if (isset($msg91_otp) && $msg91_otp == '1') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->msg91_otp) && $this->system->msg91_otp == '1') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="msg91_otp_yes"><?php echo $this->lang->line('text_yes'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="msg91_otp_no" name="msg91_otp" type="radio" class="custom-control-input" value="0" <?php
                                                            if (isset($msg91_otp) && $msg91_otp == '0') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->msg91_otp) && $this->system->msg91_otp == '0') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="msg91_otp_no"><?php echo $this->lang->line('text_no'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('msg91_otp', '<em style="color:red">', '</em>'); ?>
                                                </div>   
                                            </div>
                                            <div class="row msg91_otp-div <?php
                                            if ((isset($msg91_authkey) && $msg91_authkey == '0'))
                                                echo 'd-none';
                                            elseif ((isset($this->system->msg91_authkey) && $this->system->msg91_authkey == '0') || ($this->system->msg91_authkey == ''))
                                                echo 'd-none';
                                            ?>">
                                                <div class="form-group col-md-6">
                                                    <label for="msg91_authkey "><?php echo $this->lang->line('text_msg91_auth_key'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="msg91_authkey" type="text" name="msg91_authkey" class="form-control" value="<?php if (isset($msg91_authkey)) echo $msg91_authkey;elseif (isset($this->system->msg91_authkey)) echo $this->system->msg91_authkey; ?>">
                                                    <?php echo form_error('msg91_authkey', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                                <div class="form-group col-md-6">
                                                    <label for="msg91_sender "><?php echo $this->lang->line('text_msg91_sender'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="msg91_sender" type="text" name="msg91_sender" class="form-control" value="<?php if (isset($msg91_sender)) echo $msg91_sender;elseif (isset($this->system->msg91_sender)) echo $this->system->msg91_sender; ?>">
                                                    <?php echo form_error('msg91_sender', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                                <div class="form-group col-md-6">
                                                    <label for="msg91_route "><?php echo $this->lang->line('text_msg91_route'); ?>e<span class="required" aria-required="true"> * </span></label>
                                                    <input id="msg91_route" type="text" name="msg91_route" class="form-control" value="<?php if (isset($msg91_route)) echo $msg91_route;elseif (isset($this->system->msg91_route)) echo $this->system->msg91_route; ?>">
                                                    <?php echo form_error('msg91_route', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_under_maintenance_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="under_maintenance "><?php echo $this->lang->line('text_under_maintenance_mode'); ?><span class="required" aria-required="true"> * </span><span data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('tooltip_under_maintenance'); ?>"><i class="fa fa-question-circle"></i></span></label>
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="under_maintenance_yes" name="under_maintenance" type="radio" class="custom-control-input" value="1" <?php
                                                            if (isset($under_maintenance) && $under_maintenance == '1') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->under_maintenance) && $this->system->under_maintenance == '1') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="under_maintenance_yes"><?php echo $this->lang->line('text_yes'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="under_maintenance_no" name="under_maintenance" type="radio" class="custom-control-input" value="0" <?php
                                                            if (isset($under_maintenance) && $under_maintenance == '0') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->under_maintenance) && $this->system->under_maintenance == '0') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="under_maintenance_no"><?php echo $this->lang->line('text_no'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('under_maintenance', '<em style="color:red">', '</em>'); ?>
                                                </div>   
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_login_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="firebase_otp "><?php echo $this->lang->line('text_firebase_otp'); ?><span class="required" aria-required="true"> * </span>
                                                    <a href="https://youtu.be/dgJqXrRF6k8" target="_blank" style="color:#000;"><i class="fa fa-question-circle "></i></a>
                                                    </label>
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="firebase_otp_yes" name="firebase_otp" type="radio" class="custom-control-input" value="yes" <?php
                                                            if (isset($firebase_otp) && $firebase_otp == 'yes') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->firebase_otp) && $this->system->firebase_otp == 'yes') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="firebase_otp_yes"><?php echo $this->lang->line('text_yes'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="firebase_otp_no" name="firebase_otp" type="radio" class="custom-control-input" value="no" <?php
                                                            if (isset($firebase_otp) && $firebase_otp == 'no') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->firebase_otp) && $this->system->firebase_otp == 'no') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="firebase_otp_no"><?php echo $this->lang->line('text_no'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('firebase_otp', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="fb_login "><?php echo $this->lang->line('text_fb_login'); ?><span class="required" aria-required="true"> * </span><a href="https://youtu.be/ud-V4-IAjlA" target="_blank" style="color:#000;"><i class="fa fa-question-circle "></i></a></label>
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="fb_login_yes" name="fb_login" type="radio" class="custom-control-input" value="yes" <?php
                                                            if (isset($fb_login) && $fb_login == 'yes') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->fb_login) && $this->system->fb_login == 'yes') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="fb_login_yes"><?php echo $this->lang->line('text_yes'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="fb_login_no" name="fb_login" type="radio" class="custom-control-input" value="no" <?php
                                                            if (isset($fb_login) && $fb_login == 'no') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->fb_login) && $this->system->fb_login == 'no') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="fb_login_no"><?php echo $this->lang->line('text_no'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('fb_login', '<em style="color:red">', '</em>'); ?>
                                                </div>   
                                                <div class="form-group col-md-6 fb_app_id_div <?php
                                                if ((isset($fb_login) && $fb_login != 'yes'))
                                                    echo 'd-none';
                                                elseif (isset($this->system->fb_login) && $this->system->fb_login != 'yes')
                                                    echo 'd-none';
                                                ?>">
                                                    <label for="fb_app_id"><?php echo $this->lang->line('text_facebook_app_id'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="fb_app_id" type="text" class="form-control" name="fb_app_id" value="<?php if (isset($fb_app_id)) echo $fb_app_id;elseif (isset($this->system->fb_app_id)) echo $this->system->fb_app_id; ?>">                                                   
                                                    <?php echo form_error('fb_app_id', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="google_login "><?php echo $this->lang->line('text_google_login'); ?><span class="required" aria-required="true"> * </span>
                                                    <a href="https://youtu.be/7qtytQk1m7c" target="_blank" style="color:#000;"><i class="fa fa-question-circle "></i></a>
                                                    </label>
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="google_login_yes" name="google_login" type="radio" class="custom-control-input" value="yes" <?php
                                                            if (isset($google_login) && $google_login == 'yes') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->google_login) && $this->system->google_login == 'yes') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="google_login_yes"><?php echo $this->lang->line('text_yes'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="google_login_no" name="google_login" type="radio" class="custom-control-input" value="no" <?php
                                                            if (isset($google_login) && $google_login == 'no') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->google_login) && $this->system->google_login == 'no') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="google_login_no"><?php echo $this->lang->line('text_no'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('google_login', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6 google_client_id_div <?php
                                                if ((isset($google_login) && $google_login != 'yes'))
                                                    echo 'd-none';
                                                elseif (isset($this->system->google_login) && $this->system->google_login != 'yes')
                                                    echo 'd-none';
                                                ?>">
                                                    <label for="google_client_id  "><?php echo $this->lang->line('text_gogole_client_id'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="google_client_id" type="text" class="form-control" name="google_client_id" value="<?php if (isset($google_client_id)) echo $google_client_id;elseif (isset($this->system->google_client_id)) echo $this->system->google_client_id; ?>">
                                                    <?php echo form_error('google_client_id', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <div class="firebase_api_key_div <?php
                                            if ((isset($google_login) && $google_login == 'no') && (isset($fb_login) && $fb_login == 'no') && (isset($firebase_otp) && $firebase_otp == 'no'))
                                                echo 'd-none';
                                            elseif ((isset($this->system->google_login) && $this->system->google_login == 'no') && (isset($this->system->fb_login) && $this->system->fb_login == 'no') && (isset($this->system->firebase_otp) && $this->system->firebase_otp == 'no'))
                                                echo 'd-none';
                                            ?>">
                                                <div class="form-group col-md-12">
                                                    <label for="firebase_api_key"><?php echo $this->lang->line('text_firebase_api_key'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="firebase_api_key" type="text" name="firebase_api_key" class="form-control" value="<?php if (isset($firebase_api_key)) echo $firebase_api_key;elseif (isset($this->system->firebase_api_key)) echo $this->system->firebase_api_key; ?>">                                              
                                                    <?php echo form_error('firebase_api_key', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="firebase_script"><?php echo $this->lang->line('text_firebase_script'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <textarea id="firebase_script" type="text" name="firebase_script" class="form-control"><?php if (isset($firebase_script)) echo $firebase_script;elseif (isset($this->system->firebase_script)) echo $this->system->firebase_script; ?></textarea>                                         
                                                    <?php echo form_error('firebase_script', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_ads_setting'); ?></b></h6>
                                            <hr>      
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="watch_earn_description"><?php echo $this->lang->line('text_watch_earn_description'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <textarea id="watch_earn_description" type="text" name="watch_earn_description" class="form-control"><?php if (isset($watch_earn_description)) echo $watch_earn_description;elseif (isset($this->system->watch_earn_description)) echo $this->system->watch_earn_description; ?></textarea>                                         
                                                    <?php echo form_error('watch_earn_description', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="watch_earn_note"><?php echo $this->lang->line('text_watch_earn_note'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <textarea id="watch_earn_note" type="text" name="watch_earn_note" class="form-control"><?php if (isset($watch_earn_note)) echo $watch_earn_note;elseif (isset($this->system->watch_earn_note)) echo $this->system->watch_earn_note; ?></textarea>                                         
                                                    <?php echo form_error('watch_earn_note', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="watch_ads_per_day"><?php echo $this->lang->line('text_watch_ads_per_day'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="watch_ads_per_day" type="text" name="watch_ads_per_day" class="form-control" value="<?php if (isset($watch_ads_per_day)) echo $watch_ads_per_day;elseif (isset($this->system->watch_ads_per_day)) echo $this->system->watch_ads_per_day; ?>">                                              
                                                    <?php echo form_error('watch_ads_per_day', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="point_on_watch_ads"><?php echo $this->lang->line('text_point_on_watch_ads'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="point_on_watch_ads" type="text" name="point_on_watch_ads" class="form-control" value="<?php if (isset($point_on_watch_ads)) echo $point_on_watch_ads;elseif (isset($this->system->point_on_watch_ads)) echo $this->system->point_on_watch_ads; ?>">                                              
                                                    <?php echo form_error('point_on_watch_ads', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_banner_ads_setting'); ?></b></h6>
                                            <hr>      
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="watch_earn_description"><?php echo $this->lang->line('text_banner_ads_show'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="banner_ads_show_yes" name="banner_ads_show" type="radio" class="custom-control-input" value="yes" <?php
                                                            if (isset($banner_ads_show) && $banner_ads_show == 'yes') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->banner_ads_show) && $this->system->banner_ads_show == 'yes') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="banner_ads_show_yes"><?php echo $this->lang->line('text_yes'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="banner_ads_show_no" name="banner_ads_show" type="radio" class="custom-control-input" value="no" <?php
                                                            if (isset($banner_ads_show) && $banner_ads_show == 'no') {
                                                                echo 'checked';
                                                            } elseif (isset($this->system->banner_ads_show) && $this->system->banner_ads_show == 'no') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="banner_ads_show_no"><?php echo $this->lang->line('text_no'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('banner_ads_show', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_timezone_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="timezone "><?php echo $this->lang->line('text_timezone'); ?><span class="required" aria-required="true"> * </span></label>                                                   
                                                    <select class="form-control" name="timezone">
                                                        <?php
                                                            $OptionsArray = timezone_identifiers_list();                                                         
                                                            foreach($OptionsArray as $key => $value) {
                                                        ?>
                                                            <option value="<?php echo $value; ?>" <?php if($this->system->timezone == $value) echo 'selected'; ?>><?php echo $value; ?></option>
                                                        <?php
                                                            }
                                                        ?>                                                       
                                                    </select>                                                    
                                                    <?php echo form_error('timezone', '<em style="color:red">', '</em>'); ?>
                                                </div>   
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_language_setting'); ?></b></h6>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="language "><?php echo $this->lang->line('text_language'); ?><span class="required" aria-required="true"> * </span></label>                                                   
                                                    <select class="language" name="language[]" multiple> 
                                                        <option value="" disabled>Select One</option>                                                       
                                                        <?php
                                                            foreach($languages as $key => $value) {
                                                        ?>
                                                            <option value="<?php echo $key . '---' . $value; ?>" <?php if(stripos($this->system->supported_language,$value) !== false) { echo 'selected'; } ?>><?php echo $value; ?></option>                                                       
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>                                                    
                                                    <?php echo form_error('language', '<em style="color:red">', '</em>'); ?>
                                                </div>   
                                            </div>
                                            <h6><b><?php echo $this->lang->line('text_script'); ?></b></h6>
                                            <hr>      
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="footer_script"><?php echo $this->lang->line('text_footer_script'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <textarea id="footer_script" type="text" name="footer_script" class="form-control"><?php if (isset($footer_script)) echo $footer_script;elseif (isset($this->system->footer_script)) echo $this->system->footer_script; ?></textarea>                                         
                                                    <?php echo form_error('footer_script', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="other_submit" class="btn btn-primary " <?php
                                                if ($this->system->demo_user == 1) {
                                                    echo 'disabled';
                                                }
                                                ?>>                                                    
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>appsetting/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                 
                                            </div>
                                        </form>
                                    </div>
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

            $('input[type=radio][name=active_referral]').on('change', function () {
                if ($(this).val() == '1') {
                    $(".referral-div").removeClass("d-none");
                } else {
                    $('input[name=referral]').val('');
                    $('input[name=referral_level1]').val('');
                    $('input[name=referral_min_paid_fee]').val('');
                    $('#referandearn_description').text(''); 
                    $(".referral-div").addClass("d-none");
                }
            });
            $('input[type=radio][name=one_signal_notification]').on('change', function () {
                if ($(this).val() == '1') {
                    $(".one_signal_notification-div").removeClass("d-none");
                } else {
                    $('input[name=app_id]').val('');
                    // $('input[name=rest_api_key]').val('');
                    $(".one_signal_notification-div").addClass("d-none");
                }
            });
            $('input[type=radio][name=fb_login]').on('change', function () {
                if ($('input[name="firebase_otp"]:checked').val() == "yes" || $('input[name="google_login"]:checked').val() == "yes" || $('input[name="fb_login"]:checked').val() == "yes") {
                    $(".firebase_api_key_div").removeClass("d-none");
                } else {
                    $(".firebase_api_key_div").addClass("d-none");
                }
                if ($(this).val() == 'yes') {
                    $(".fb_app_id_div").removeClass("d-none");
                } else {
                    $('input[name=fb_app_id]').val('');
                    $(".fb_app_id_div").addClass("d-none");
                }
            });
            $('input[type=radio][name=google_login]').on('change', function () {
                if ($('input[name="firebase_otp"]:checked').val() == "yes" || $('input[name="google_login"]:checked').val() == "yes" || $('input[name="fb_login"]:checked').val() == "yes") {
                    $(".firebase_api_key_div").removeClass("d-none");
                } else {
                    $(".firebase_api_key_div").addClass("d-none");
                }
                if ($(this).val() == 'yes') {
                    $(".google_client_id_div").removeClass("d-none");
                } else {
                    $('input[name=google_client_id]').val('');
                    $(".google_client_id_div").addClass("d-none");
                }
            });
            $('input[type=radio][name=firebase_otp]').on('change', function () {
                if ($('input[name="firebase_otp"]:checked').val() == "yes" || $('input[name="google_login"]:checked').val() == "yes" || $('input[name="fb_login"]:checked').val() == "yes") {
                    $(".firebase_api_key_div").removeClass("d-none");
                } else {
                    $(".firebase_api_key_div").addClass("d-none");
                }
            });
            $('input[type=radio][name=msg91_otp]').on('change', function () {
                if ($(this).val() == '1') {
                    $(".msg91_otp-div").removeClass("d-none");
                } else {
                    $('input[name=msg91_authkey]').val('');
                    $('input[name=msg91_sender]').val('');
                    $('input[name=msg91_route]').val('');
                    $(".msg91_otp-div").addClass("d-none");
                }
            });
            $("#app-upload-form").validate({
                rules: {
                    'app_upload': {
                        required: true,
                        accept: "apk",
                    },
                    'force_update': {
                        required: true,
                    },
                    'app_version': {
                        required: true,
                    },
                    'app_description': {
                        required: function (textarea) {
                            CKEDITOR.instances[textarea.id].updateElement();
                            var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                            return editorcontent.length === 0;
                        }
                    }
                },
                messages: {
                    'app_upload': {
                        required: '<?php echo $this->lang->line('err_app_upload_req'); ?>',
                        accept: '<?php echo $this->lang->line('err_app_upload_valid'); ?>',
                    },
                    'force_update': {
                        required: '<?php echo $this->lang->line('err_force_update_req'); ?>',
                    },
                    'app_version': {
                        required: '<?php echo $this->lang->line('err_app_version_req'); ?>',
                    },
                    'app_description': {
                        required: '<?php echo $this->lang->line('err_app_desc_req'); ?>',
                    }
                }
                ,
                errorPlacement: function (error, element)
                {
                    if (element.is(":file"))
                    {
                        element.parent().append(error);
                    } else if (element.is(":radio"))
                    {
                        element.parent().parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#app-form").validate({
                rules: {
                    'active_referral': {
                        required: true,
                    },
                    'referral': {
                        required: function () {
                            if ($('input[name="active_referral"]:checked').val() == "1") {
                                return true;
                            } else {
                                return false;
                            }
                        },                        
                        number: true,
                    },
                    'referral_level1': {
                        required: function () {
                            if ($('input[name="active_referral"]:checked').val() == "1") {
                                return true;
                            } else {
                                return false;
                            }
                        },
                        number: true,
                    },
                    'referral_min_paid_fee': {
                        required: function () {
                            if ($('input[name="active_referral"]:checked').val() == "1") {
                                return true;
                            } else {
                                return false;
                            }
                        },
                        number: true,
                    },
                    referandearn_description: {
                        required: function () {
                            if ($('input[name="active_referral"]:checked').val() == "1") {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    match_url: {
                        required: true,
                        url: true,
                    },
                    share_description: {
                        required: true,
                    },
                    // user_template: {
                    //     required: true,
                    // },
                    min_withdrawal: {
                        required: true,
                        number: true,
                    },
                    min_require_balance_for_withdrawal: {
                        required: true,
                        number: true,
                    },
                    min_addmoney: {
                        required: true,
                        number: true,
                    },
                    one_signal_notification: {
                        required: true,
                    },
                    app_id: {
                        required: function () {
                            if ($('input[name="one_signal_notification"]:checked').val() == "1") {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    // rest_api_key: {
                    //     required: function () {
                    //         if ($('input[name="one_signal_notification"]:checked').val() == "1") {
                    //             return true;
                    //         } else {
                    //             return false;
                    //         }
                    //     }
                    // },
                    firebase_otp: {
                        required: true,
                    },
                    fb_login: {
                        required: true,
                    },
                    google_login: {
                        required: true,
                    },
                    firebase_api_key: {
                        required: function () {
                            if ($('input[name="firebase_otp"]:checked').val() == "yes" || $('input[name="google_login"]:checked').val() == "yes" || $('input[name="fb_login"]:checked').val() == "yes") {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    firebase_script: {
                        required: function () {
                            if ($('input[name="firebase_otp"]:checked').val() == "yes" || $('input[name="google_login"]:checked').val() == "yes" || $('input[name="fb_login"]:checked').val() == "yes") {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    google_client_id: {
                        required: function () {
                            if ($('input[name="google_login"]:checked').val() == "yes") {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    fb_app_id: {
                        required: function () {
                            if ($('input[name="fb_login"]:checked').val() == "yes") {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    msg91_otp: {
                        required: true,
                    },
                    msg91_authkey: {
                        required: function () {
                            if ($('input[name="msg91_otp"]:checked').val() == "1") {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    msg91_sender: {
                        required: function () {
                            if ($('input[name="msg91_otp"]:checked').val() == "1") {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    msg91_route: {
                        required: function () {
                            if ($('input[name="msg91_otp"]:checked').val() == "1") {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    under_maintenance: {
                        required: true,
                    },
                    admin_user: {
                        required: true,
                    },
                    admin_profit: {
                        required: true,
                        number: true,
                        min: 0,
                        max:100
                    },
                    smtp_host: {
                        required: true,
                    },
                    smtp_user: {
                        required: true,
                    },
                    smtp_pass: {
                        required: true,
                    },
                    smtp_port: {
                        required: true,
                    },
                    smtp_secure: {
                        required: true,
                    },
                    watch_ads_per_day: {
                        required: true,
                        number: true,
                    },
                    point_on_watch_ads: {
                        required: true,
                        number: true,
                        digits: true
                    },
                    banner_ads_show: {
                        required: true,
                    },
                    coin_under_hundrade: {
                        required: true,
                        number: true,
                        min: 0,
                        max:100
                    },
                    coin_up_to_hundrade: {
                        required: true,
                        number: true,
                        min: 0,
                        max:100
                    },
                    'language[]': {
                        required: true,
                    },
                },
                messages: {
                    coin_under_hundrade: {
                        required: '<?php echo $this->lang->line('err_amount_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        min: '<?php echo $this->lang->line('err_min_coin_req'); ?>',
                        max: '<?php echo $this->lang->line('err_max_coin_req'); ?>',
                    },
                    coin_up_to_hundrade: {
                        required: '<?php echo $this->lang->line('err_amount_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        min: '<?php echo $this->lang->line('err_min_coin_req'); ?>',
                        max: '<?php echo $this->lang->line('err_max_coin_req'); ?>',
                    },
                    'active_referral': {
                        required: '<?php echo $this->lang->line('err_active_referral_req'); ?>',
                    },
                    'referral': {
                        required: '<?php echo $this->lang->line('err_referral_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                    },
                    'referral_level1': {
                        required: '<?php echo $this->lang->line('err_referral_level1_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                    },
                    'referral_min_paid_fee': {
                        required: '<?php echo $this->lang->line('err_referral_min_paid_fee_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                    },
                    referandearn_description: {
                        required: '<?php echo $this->lang->line('err_referandearn_desc_req'); ?>',
                    },
                    match_url: {
                        required: '<?php echo $this->lang->line('err_match_url_req'); ?>',
                        url: '<?php echo $this->lang->line('err_match_url_valid'); ?>',
                    },
                    share_description: {
                        required: '<?php echo $this->lang->line('err_share_desc_req'); ?>',
                    },
                    // user_template: {
                    //     required: '<?php echo $this->lang->line('err_user_template_req'); ?>',
                    // },
                    min_withdrawal: {
                        required: '<?php echo $this->lang->line('err_min_withdrawal_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                    },
                    min_require_balance_for_withdrawal: {
                        required: '<?php echo $this->lang->line('err_amount_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                    },
                    min_addmoney: {
                        required: '<?php echo $this->lang->line('err_min_addmoney_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                    },
                    //                    'one_signal_notification': {
                    //                        required: '<?php echo $this->lang->line('err_one_signal_req'); ?>',
                    //                    },
                    app_id: {
                        required: '<?php echo $this->lang->line('err_server_key_req'); ?>',
                    },
                    // rest_api_key: {
                    //     required: '<?php echo $this->lang->line('err_rest_api_key_req'); ?>',
                    // },
                    firebase_otp: {
                        required: '<?php echo $this->lang->line('err_firebase_otp_req'); ?>',
                    },
                    fb_login: {
                        required: '<?php echo $this->lang->line('err_fb_login_req'); ?>',
                    },
                    google_login: {
                        required: '<?php echo $this->lang->line('err_google_login_req'); ?>',
                    },
                    firebase_api_key: {
                        required: '<?php echo $this->lang->line('err_firebase_api_key_req'); ?>',
                    },
                    firebase_script: {
                        required: '<?php echo $this->lang->line('err_firebase_script_req'); ?>',
                    },
                    google_client_id: {
                        required: '<?php echo $this->lang->line('err_google_client_id_req'); ?>',
                    },
                    fb_app_id: {
                        required: '<?php echo $this->lang->line('err_fb_app_id_req'); ?>',
                    },
                    msg91_otp: {
                        required: '<?php echo $this->lang->line('err_msg91_otp_req'); ?>',
                    },
                    msg91_authkey: {
                        required: '<?php echo $this->lang->line('err_msg91_authkey_req'); ?>',
                    },
                    msg91_sender: {
                        required: '<?php echo $this->lang->line('err_msg91_sender_req'); ?>',
                    },
                    msg91_route: {
                        required: '<?php echo $this->lang->line('err_msg91_route_req'); ?>',
                    },
                    under_maintenance: {
                        required: '<?php echo $this->lang->line('err_under_maintenance_req'); ?>',
                    },
                    admin_user: {
                        required: '<?php echo $this->lang->line('err_admin_user_req'); ?>',
                    },
                    admin_profit : {
                        required: '<?php echo $this->lang->line('err_amount_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        min: 'Profit Should be greater than equle to 0.',
                        max: 'Profit Should be less than equle to 100.',
                    },
                    smtp_host: {
                        required: '<?php echo $this->lang->line('err_smtp_host_req'); ?>',
                    },
                    smtp_user: {
                        required: '<?php echo $this->lang->line('err_smtp_user_req'); ?>',
                    },
                    smtp_pass: {
                        required: '<?php echo $this->lang->line('err_smtp_pass_req'); ?>',
                    },
                    smtp_port: {
                        required: '<?php echo $this->lang->line('err_smtp_port_req'); ?>',
                    },
                    smtp_secure: {
                        required: '<?php echo $this->lang->line('err_smtp_secure_req'); ?>',
                    },
                    watch_ads_per_day: {
                        required: '<?php echo $this->lang->line('err_watch_ads_per_day_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                    },
                    point_on_watch_ads: {
                        required: '<?php echo $this->lang->line('err_point_on_watch_ads_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                        digits: '<?php echo $this->lang->line('err_digits'); ?>',
                    },
                    banner_ads_show: {
                        required: '<?php echo $this->lang->line('err_banner_ads_show_req'); ?>',
                    },
                    'language[]': {
                        required: '<?php echo $this->lang->line('err_language_req'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });

            $('.language').multiselect({
                maxHeight: 200,
                nonSelectedText: 'Select Language',
                buttonWidth: '100%',
                maxWidth: '100%',
                width:'100%',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,  
                buttonClass: 'btn btn-outline-dark',                                               
                templates: {
                    filter: '<li class="multiselect-item multiselect-filter"><input class="form-control multiselect-search" type="text" /></li>',                    
                }         
            });

        </script>           	
    </body>
</html>