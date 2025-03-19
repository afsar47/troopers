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
                        <h1 class="h2">Member</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 dash-box">
                            <div class="bg-lightpink  small-box card card-sm-3">
                                <div class="card-icon ">
                                    <i class="fa fa-gamepad"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4><?php echo $this->lang->line('text_match_played'); ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <?php echo $tot_match_play['total_match']; ?>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--                        <div class="col-lg-3 col-md-6 dash-box">
                                                    <div class="bg-lightgreen  small-box card card-sm-3">
                                                        <div class="card-icon ">
                                                            <i class="fa fa-gamepad"></i>
                                                        </div>
                                                        <div class="card-wrap">
                                                            <div class="card-header">
                                                                <h4><?php echo $this->lang->line('text_total_kill'); ?></h4>
                                                            </div>
                                                            <div class="card-body">
                        <?php echo ($tot_kill['total_kill'] != '') ? $tot_kill['total_kill'] : '0'; ?>                                       
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>-->
                        <div class="col-lg-3 col-md-6 dash-box">
                            <div class="bg-lightgreen small-box card card-sm-3">
                                <div class="card-icon ">
                                    <i class="fa fa-gamepad"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4><?php echo $this->lang->line('text_total_win'); ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_win['total_win'])); ?>                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 dash-box">
                            <div class="bg-lightblue small-box card card-sm-3">
                                <div class="card-icon ">
                                    <i class="fa fa-gamepad"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4><?php echo $this->lang->line('text_win_balance'); ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_balance['wallet_balance'])); ?>                                     
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--                        <div class="col-lg-3 col-md-6 dash-box">
                                                    <div class="bg-lightgreen small-box card card-sm-3">
                                                        <div class="card-icon ">
                                                            <i class="fa fa-gamepad"></i>
                                                        </div>
                                                        <div class="card-wrap">
                                                            <div class="card-header">
                                                                <h4><?php echo $this->lang->line('text_join_balance'); ?></h4>
                                                            </div>
                                                            <div class="card-body">
                        <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $member_detail['join_money'])); ?>                                       
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>-->
                        <div class="col-lg-3 col-md-6 dash-box">
                            <div class="bg-lightpink small-box card card-sm-3">
                                <div class="card-icon ">
                                    <i class="fa fa-gamepad"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4><?php echo $this->lang->line('text_total_balance'); ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $member_detail['join_money'] + $member_detail['wallet_balance'])); ?>                                 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class=" col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_member_detail'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form method="POST" class="member_form" name="member_form" id="member_form" action="<?php echo base_url() . $this->path_to_view_admin ?>members/member_detail" enctype="multipart/form-data">
                                            <div class="row">
                                                <input type="hidden" class="form-control" name="member_id" value="<?php if (isset($member_id)) echo $member_id;elseif (isset($member_detail['member_id'])) echo $member_detail['member_id'] ?>">
                                                <div class="form-group col-md-6">
                                                    <label for="first_name"><?php echo $this->lang->line('text_first_name'); ?></label>
                                                    <input id="frist_name" type="text" class="form-control" name="first_name" value="<?php if (isset($first_name)) echo $first_name;elseif (isset($member_detail['first_name'])) echo $member_detail['first_name'] ?>" autofocus>
                                                    <?php echo form_error('first_name', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="last_name"><?php echo $this->lang->line('text_last_name'); ?></label>
                                                    <input id="last_name" type="text" class="form-control" name="last_name" value="<?php if (isset($last_name)) echo $last_name;elseif (isset($member_detail['last_name'])) echo $member_detail['last_name'] ?>">
                                                    <?php echo form_error('last_name', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="user_name"><?php echo $this->lang->line('text_user_name'); ?></label>
                                                    <input id="user_name" type="text" class="form-control" name="user_name" value="<?php if (isset($user_name)) echo $user_name;elseif (isset($member_detail['user_name'])) echo $member_detail['user_name'] ?>">
                                                    <?php echo form_error('user_name', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="password"><?php echo $this->lang->line('text_password'); ?></label>
                                                    <input id="password" type="password" class="form-control" name="password" value="">
                                                    <?php echo form_error('password', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                    
                                                <div class="form-group col-md-6">
                                                    <label for="referral_id"><?php echo $this->lang->line('text_referral_no'); ?></label>
                                                    <input id="referral_id" type="text" readonly="" class="form-control" name="referral_id" value="<?php if (isset($referral_id)) echo $referral_id;elseif (isset($member_detail['referral_no'])) echo $member_detail['referral_no'] ?>">
                                                    <?php echo form_error('referral_id', '<em style="color:red">', '</em>'); ?>
                                                </div>          
                                                <div class="form-group col-md-6">
                                                    <label for="email_id"><?php echo $this->lang->line('text_email'); ?></label>
                                                    <input id="email_id" type="email" class="form-control" name="email_id" value="<?php if (isset($email_id)) echo $email_id;elseif ($this->system->demo_user == 1 && isset($member_detail['email_id'])) echo $this->functions->mask_email($member_detail['email_id']);elseif (isset($member_detail['email_id'])) echo $member_detail['email_id']; ?>">
                                                    <?php echo form_error('email_id', '<em style="color:red">', '</em>'); ?>
                                                </div>     
                                                <!--                                                <div class="form-group col-md-6">
                                                                                                    <label for="country_id"><?php echo $this->lang->line('text_country'); ?></label>
                                                                                                    <select class="form-control" name="country_id" >
                                                                                                        <option value="">Select..</option>
                                                <?php
                                                foreach ($country_data as $country) {
                                                    ?>
                                                                                                                    <option value="<?php echo $country->country_id; ?>" <?php
                                                    if (isset($country_id) && $country_id == $country->country_id)
                                                        echo 'selected';
                                                    elseif (isset($member_detail['country_id']) && $member_detail['country_id'] == $country->country_id)
                                                        echo 'selected';
                                                    else
                                                        echo '';
                                                    ?>><?php echo $country->country_name; ?></option>
                                                    <?php
                                                }
                                                ?>
                                                                                                    </select>
                                                <?php echo form_error('country_id', '<em style="color:red">', '</em>'); ?>
                                                                                                </div>-->
                                                <div class="form-group col-md-2">
                                                    <label for="country_code"><?php echo $this->lang->line('text_country_code'); ?></label>
                                                    <select class="form-control" name="country_code" >
                                                        <option value="">Select..</option>
                                                        <?php
                                                        foreach ($country_data as $country) {
                                                            ?>
                                                            <option value="<?php echo $country->p_code; ?>" <?php
                                                            if (isset($country_code) && $country_code == $country->p_code)
                                                                echo 'selected';
                                                            elseif (isset($member_detail['country_code']) && $member_detail['country_code'] == $country->p_code)
                                                                echo 'selected';
                                                            else
                                                                echo '';
                                                            ?>><?php echo $country->p_code; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                    </select>
                                                    <?php echo form_error('country_code', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="mobile_no"><?php echo $this->lang->line('text_mobile_no'); ?></label>
                                                    <input id="mobile_no" type="text" class="form-control" name="mobile_no" value="<?php if (isset($mobile_no)) echo $mobile_no;elseif ($this->system->demo_user == 1 && isset($member_detail['mobile_no'])) echo str_replace(substr($member_detail['mobile_no'], 2, 6), $this->functions->stars($member_detail['mobile_no']), $member_detail['mobile_no']);elseif (isset($member_detail['mobile_no'])) echo $member_detail['mobile_no']; ?>">
                                                    <?php echo form_error('mobile_no', '<em style="color:red">', '</em>'); ?>
                                                </div>  
                                                <div class="form-group col-md-6">
                                                    <label for="dob"><?php echo $this->lang->line('text_date_birth'); ?></label>
                                                    <input id="dob" type="text" class="form-control datepicker" name="dob" value="<?php if (isset($dob)) echo $dob;elseif (isset($member_detail['dob'])) echo $member_detail['dob'] ?>">
                                                    <?php echo form_error('dob', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                
                                                <div class="form-group col-md-6">
                                                    <label for="profile_image"><?php echo $this->lang->line('text_profile_image'); ?></label>
                                                        <input type="file" id="profile_image" name="profile_image" class="form-control" >
                                                        <input type="hidden" id="file-input" name="old_profile_image"  value="<?php echo (isset($member_detail['profile_image'])) ? $member_detail['profile_image'] : ''; ?>" class="form-control-file">                                                                                                      
                                                        <?php echo form_error('profile_image', '<em style="color:red">', '</em>'); ?>                                                        
                                                        <?php echo form_error('home_sec_bnr_image', '<em style="color:red">', '</em>'); ?>
                                                        <?php if (isset($member_detail['profile_image']) && $member_detail['profile_image'] != '' && file_exists($this->profile_image . $member_detail['profile_image'])) { ?>
                                                            <br>
                                                            <img src ="<?php echo base_url() . $this->profile_image . "thumb/100x100_" . $member_detail['profile_image'] ?>" >
                                                        <?php } ?>
                                                </div> 
                                                <div class="form-group col-md-6">
                                                    <label for="gender"><?php echo $this->lang->line('text_gender'); ?></label>
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="male" name="gender" type="radio" class="custom-control-input" value="0" <?php
                                                            if (isset($member_detail['gender']) && $member_detail['gender'] == '') {
                                                                
                                                            } else if (isset($member_detail['gender']) && $member_detail['gender'] == 0) {
                                                                echo 'checked';
                                                            } elseif (isset($gender) && $gender == 0) {
                                                                echo 'checked';
                                                            }
                                                            ?>>
                                                            <label class="custom-control-label" for="male"><?php echo $this->lang->line('text_male'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="female" name="gender" type="radio" class="custom-control-input" value="1" <?php
                                                            if (isset($member_detail['gender']) && $member_detail['gender'] == '') {
                                                                
                                                            } else if (isset($member_detail['gender']) && $member_detail['gender'] == 1) {
                                                                echo 'checked';
                                                            } elseif (isset($gender) && $gender == 1) {
                                                                echo 'checked';
                                                            }
                                                            ?>>
                                                            <label class="custom-control-label" for="female"><?php echo $this->lang->line('text_female'); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                    if (isset($member_detail['pubg_id']) && $member_detail['pubg_id'] != '') {
                                                ?>  
                                                    <div class="form-group col-md-12">                                                    
                                                        <h5><?php echo $this->lang->line('text_game_id'); ?></h5>
                                                        <hr/>
                                                    </div>
                                                <?php
                                                    $pubg_ids = array();
                                                    $pubg_ids = @unserialize($member_detail['pubg_id']);
                                                    
                                                    if (!empty($pubg_ids)) {
                                                        foreach ($pubg_ids as $key => $pubg_id) {
                                                            $game = $this->members->getgameById($key);
                                                            if (isset($game['game_name'])) {
                                                                ?>
                                                                <div class="form-group col-md-6">
                                                                    <label for="pubg_id"><?php echo $game['game_name']; ?> ID</label>
                                                                    <input type="hidden" name="game_id[]" value="<?php echo $key; ?>">
                                                                    <input id="pubg_id" type="text" class="form-control" name="pubg_id[]" value="<?php echo $pubg_id; ?>" required >
                                                                    <?php echo form_error('pubg_id', '<em style="color:red">', '</em>'); ?>
                                                                </div>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>                                                 
                                            </div>
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_update'); ?>" name="update" <?php if ($this->system->demo_user == 1) echo "disabled"; ?> class="btn btn-primary ">
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>members/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                    
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class=" col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_wallet'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form method="POST" class="wallet_form" name="wallet_form" id="wallet_form" action="<?php echo base_url() . $this->path_to_view_admin ?>members/member_detail">
                                            <div class="row">
                                                <input type="hidden" class="form-control" name="member_id" value="<?php if (isset($member_id)) echo $member_id;elseif (isset($member_detail['member_id'])) echo $member_detail['member_id'] ?>">
                                                <div class="form-group col-md-6">
                                                    <label for="Amount"><?php echo $this->lang->line('text_amount'); ?></label>
                                                    <input id="amount" type="text" class="form-control numbers" name="amount" >
                                                    <?php echo form_error('amount', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="wallet"><?php echo $this->lang->line('text_wallet'); ?></label>
                                                    <select id="wallet" type="text" class="form-control" name="wallet" >
                                                        <option value=""><?php echo $this->lang->line('text_select_wallet'); ?></option>
                                                        <option value="join_money"><?php echo $this->lang->line('text_join_money'); ?></option>
                                                        <option value="wallet_balance"><?php echo $this->lang->line('text_win_money'); ?></option>
                                                    </select>
                                                    <?php echo form_error('wallet', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="comment"><?php echo $this->lang->line('text_comment'); ?></label>
                                                    <textarea id="comment" type="text" class="form-control" name="comment" ></textarea>
                                                    <?php echo form_error('comment', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="gender"><?php echo $this->lang->line('text_plus_minus'); ?></label>

                                                    <div class="form-group col-md-12"  style="padding-top: 10px;">
                                                        <span style="font-size : 20px;">
                                                            <input id="plus_minus1" value="+" type="radio" name="plus_minus" <?php
                                                            if (isset($plus_minus) && $plus_minus == '+') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;+   
                                                        </span>
                                                        <span style="margin-left: 20px;font-size : 20px;">
                                                            <input id="plus_minus2" value="-" type="radio"  name="plus_minus" <?php
                                                            if (isset($plus_minus) && $plus_minus == '-') {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;-
                                                        </span>                                                        
                                                    </div>
                                                    <?php echo form_error('plus_minus', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" <?php if ($this->system->demo_user == 1) echo "disabled"; ?> name="add_wallet" class="btn btn-primary "> 
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>members/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                   
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class=" col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_wallet_history'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="table-responsive">                                
                                            <table class="table table-bordered" id="wallet_tbl">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                        <th><?php echo $this->lang->line('text_deposit') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                        <th><?php echo $this->lang->line('text_withdraw') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                        <th><?php echo $this->lang->line('text_join_money') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                        <th><?php echo $this->lang->line('text_win_money') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                        <th><?php echo $this->lang->line('text_note'); ?></th>
                                                        <th><?php echo $this->lang->line('text_date'); ?></th>                                                                                      
                                                    </tr>                                                                                                              
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                        <th><?php echo $this->lang->line('text_deposit') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                        <th><?php echo $this->lang->line('text_withdraw') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                        <th><?php echo $this->lang->line('text_join_money') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                        <th><?php echo $this->lang->line('text_win_money') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                        <th><?php echo $this->lang->line('text_note'); ?></th>
                                                        <th><?php echo $this->lang->line('text_date'); ?></th>                                                                                                         
                                                    </tr>     
                                                </tfoot>
                                            </table>                                                             
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class=" col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_my_statistics'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="table-responsive">                                
                                            <table class="table table-bordered" id="states_tbl">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                        <th><?php echo $this->lang->line('text_match_info'); ?></th>
                                                        <th><?php echo $this->lang->line('text_paid') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                        <th><?php echo $this->lang->line('text_won') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                        <th><?php echo $this->lang->line('text_note'); ?></th>                                                                                                            
                                                    </tr>                                                                                                           
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                        <th><?php echo $this->lang->line('text_match_info'); ?></th>
                                                        <th><?php echo $this->lang->line('text_paid') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                        <th><?php echo $this->lang->line('text_won') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                        <th><?php echo $this->lang->line('text_note'); ?></th>                                                                                               
                                                    </tr>    
                                                </tfoot>
                                            </table>                                                             
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class=" col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_referral'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="table-responsive">                                
                                            <table class="table table-bordered" id="referral_tbl">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                        <th><?php echo $this->lang->line('text_player_name'); ?></th>
                                                        <th><?php echo $this->lang->line('text_earning') . ' (' . $this->functions->getPoint() . ')'; ?></th>   
                                                        <th><?php echo $this->lang->line('text_status'); ?></th>
                                                        <th><?php echo $this->lang->line('text_date'); ?></th>
                                                    </tr>                                                                                                              
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                        <th><?php echo $this->lang->line('text_player_name'); ?></th>
                                                        <th><?php echo $this->lang->line('text_earning') . ' (' . $this->functions->getPoint() . ')'; ?></th>   
                                                        <th><?php echo $this->lang->line('text_status'); ?></th>
                                                        <th><?php echo $this->lang->line('text_date'); ?></th>                                                                                           
                                                    </tr>     
                                                </tfoot>
                                            </table>                                                             
                                        </div>
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
            jQuery.validator.addMethod("noSpace", function (value, element) { //Code used for blank space Validation 
                return value.indexOf(" ") < 0 && value != "";
            }, "Please enter valid user name");
            $("#member_form").validate({
                rules: {
//                    first_name: {
//                        required: true,
//                    },
//                    last_name: {
//                        required: true,
//                    },
                    user_name: {
                        required: true,
                        noSpace: true,
//                        remote: "<?php echo base_url() . $this->path_to_view_admin . 'members/checkUserName/' . $this->uri->segment('4'); ?>",

                    },
                    password: {
                        minlength: 6,
                    },
                    pubg_id: {
//                        required: true,
                    },
                    email_id: {
//                        required: true,
                        email: true,
//                        remote: "<?php echo base_url() . $this->path_to_view_admin . 'members/checkEmail/' . $this->uri->segment('4'); ?>",
                    },
                    country_id: {
//                        required: true,
                    },
                    mobile_no: {
//                        required: true,
                        number: true,
                        maxlength: 15,
                        minlength: 8,
//                        remote: "<?php echo base_url() . $this->path_to_view_admin . 'members/checkMobile/' . $this->uri->segment('4'); ?>",
                    },
//                    dob: {
//                        required: true,
//                    },
                    gender: {
                        required: true,
                    },
                },
                messages: {
//                    first_name: {
//                        required: '<?php echo $this->lang->line('err_first_name_req'); ?>',
//                    },
//                    last_name: {
//                        required: '<?php echo $this->lang->line('err_last_name_req'); ?>',
//                    },
                    user_name: {
                        required: '<?php echo $this->lang->line('err_user_name_req'); ?>',
                        remote: '<?php echo $this->lang->line('err_user_name_exist'); ?>',
                    },
                    password: {
                        minlength: '<?php echo $this->lang->line('err_password_min'); ?>',
                    },
                    pubg_id: {
                        required: '<?php echo $this->lang->line('err_pubg_id_req'); ?>',
                    },
                    email_id: {
                        required: '<?php echo $this->lang->line('err_email_id_req'); ?>',
                        email: '<?php echo $this->lang->line('err_email_id_valid'); ?>',
                        remote: '<?php echo $this->lang->line('err_email_id_remote'); ?>',
                    },
                    mobile_no: {
                        required: '<?php echo $this->lang->line('err_mobile_no_req'); ?>',
                        number: '<?php echo $this->lang->line('err_mobile_no_number'); ?>',
                        remote: '<?php echo $this->lang->line('err_mobile_no_exist'); ?>',
                    },
                    country_id: {
                        required: '<?php echo $this->lang->line('err_country_id_req'); ?>',
                    },
                    dob: {
                        required: '<?php echo $this->lang->line('err_gender_req'); ?>',
                    },
                    gender: {
                        required: '<?php echo $this->lang->line('err_gender_req'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#wallet_form").validate({
                rules: {
                    'amount': {
                        required: true,
                        number: true,
                        max: function () {
                            if ($('#wallet').val() == "join_money" && $('input[name="plus_minus"]:checked').val() == '-') {
                                return <?php echo $member_detail['join_money']; ?>;
                            } else if ($('#wallet').val() == "wallet_balance" && $('input[name="plus_minus"]:checked').val() == '-') {
                                return <?php echo $member_detail['wallet_balance']; ?>;
                            }
                        }
                    },
                    'plus_minus': {
                        required: true,
                    },
                    wallet: {
                        required: true,
                    }
                },
                messages: {
                    'amount': {
                        required: '<?php echo $this->lang->line('err_amount_req'); ?>',
                        number: '<?php echo $this->lang->line('err_amount_number'); ?>',
                    },
                    'plus_minus': {
                        required: '<?php echo $this->lang->line('err_plus_minus_req'); ?>',
                    },
                    wallet: {
                        required: '<?php echo $this->lang->line('err_wallet_req'); ?>',
                    }
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        error.insertAfter(element.parent().parent());
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
        </script>
    </body>
</html>