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
                    <div class="modal fade" id="myModal" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="" method="POST" id="forgat-form">                                           
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><?php echo $this->lang->line('text_otp'); ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $this->lang->line('text_enter_otp'); ?>
                                        <input type="text" id="otp" name="otp" class="form-control" placeholder="enter otp">    
                                        <span class="otp-error d-none error"><?php echo $this->lang->line('err_otp_correct'); ?></span>
                                        <input type="hidden" id="otp_mobile" name="otp_mobile" class="form-control">                     
                                        <input type="hidden" id="otp_country_code" name="otp_country_code" class="form-control">                           
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="close_btn" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('text_btn_close'); ?></button>
                                        <input type="button" id="verify_otp" value="<?php echo $this->lang->line('text_verify'); ?>" name="verify_otp" class="btn btn-primary" >
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mb-4" id="profile_setting">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_edit_profile'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form method="POST" enctype="multipart/form-data" action="<?php echo base_url() . $this->path_to_default ?>profile/" id="profile-form" >                                           
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="first_name"><?php echo $this->lang->line('text_first_name'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control"  name="first_name" value="<?php if (isset($first_name)) echo $first_name;elseif (isset($profile_detail['first_name'])) echo $profile_detail['first_name'] ?>">
                                                    <?php echo form_error('first_name', '<em style="color:red">', '</em>'); ?>
                                                    <input type="hidden" name="member_id" value="<?php echo (isset($profile_detail['member_id'])) ? $profile_detail['member_id'] : '' ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="last_name"><?php echo $this->lang->line('text_last_name'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="last_name" type="text" class="form-control" name="last_name" value="<?php if (isset($last_name)) echo $last_name;elseif (isset($profile_detail['last_name'])) echo $profile_detail['last_name'] ?>">
                                                    <?php echo form_error('last_name', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="user_name"><?php echo $this->lang->line('text_user_name'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" readonly name="user_name" value="<?php if (isset($user_name)) echo $user_name;elseif (isset($profile_detail['user_name'])) echo $profile_detail['user_name'] ?>">
                                                    <?php echo form_error('user_name', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="email_id"><?php echo $this->lang->line('text_email_address'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="email_id" type="text" readonly class="form-control" name="email_id" value="<?php if (isset($email_id)) echo $email_id;elseif (isset($profile_detail['email_id'])) echo $profile_detail['email_id'] ?>">
                                                    <?php echo form_error('email_id', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <!--                                                <div class="form-group col-md-6">
                                                                                                    <label for="country_id"><?php echo $this->lang->line('text_country'); ?><span class="required" aria-required="true"> * </span></label>
                                                                                                    <select class="form-control" name="country_id" >
                                                                                                        <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                <?php
                                                foreach ($country_data as $country) {
                                                    ?>
                                                                                                                        <option value="<?php echo $country->country_id; ?>" <?php
                                                    if (isset($country_id) && $country_id == $country->country_id)
                                                        echo 'selected';
                                                    elseif (isset($profile_detail['country_id']) && $profile_detail['country_id'] == $country->country_id)
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
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label for="country_code"><?php echo $this->lang->line('text_country_code'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <select class="form-control" name="country_code" >
                                                        <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                        <?php
                                                        foreach ($country_data as $country) {
                                                            ?>
                                                            <option value="<?php echo $country->p_code; ?>" <?php
                                                            if (isset($country_code) && $country_code == $country->p_code)
                                                                echo 'selected';
                                                            elseif (isset($profile_detail['country_code']) && $profile_detail['country_code'] == $country->p_code)
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
                                                    <label for="mobile_no"><?php echo $this->lang->line('text_mobile_no'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="hidden" class="form-control"  name="old_mobile_no" value="<?php if (isset($mobile_no)) echo $mobile_no;elseif (isset($profile_detail['mobile_no'])) echo $profile_detail['mobile_no'] ?>">                                                  
                                                    <input type="text" class="form-control"  name="mobile_no" <?php if ($this->system->firebase_otp == 'yes') echo 'onchange="change_mobile_no(this.value)"'; ?> value="<?php if (isset($mobile_no)) echo $mobile_no;elseif (isset($profile_detail['mobile_no'])) echo $profile_detail['mobile_no'] ?>">
                                                    <span class="mobile-no-error d-none error"><?php echo $this->lang->line('err_mobile_no_exist'); ?></span>
                                                    <?php echo form_error('mobile_no', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <?php if ($this->system->firebase_otp == 'yes') { ?>
                                                    <div class="form-group col-md-4" id="recaptcha-container"></div>
                                                    <div class="form-group col-md-1 mt-4 d-none verify-btn text-white"><a class="btn btn-primary" id="otp-modal"><?php echo $this->lang->line('text_verify'); ?></a></div>
                                                <?php } ?>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="dob"><?php echo $this->lang->line('text_date_birth'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="datetimepicker1" type="text" class="form-control datepicker" autocomplete="off" name="dob" value="<?php if (isset($dob)) echo $dob;elseif (isset($profile_detail['dob'])) echo $profile_detail['dob'] ?>">
                                                    <?php echo form_error('dob', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="gender"><?php echo $this->lang->line('text_gender'); ?></label>
                                                    <div class="custom-control custom-radio">
                                                        <input id="male" name="gender" type="radio" class="custom-control-input" value="0" <?php
                                                        if (isset($profile_detail['gender']) && $profile_detail['gender'] == '') {
                                                            
                                                        } else if (isset($profile_detail['gender']) && $profile_detail['gender'] == 0) {
                                                            echo 'checked';
                                                        } elseif (isset($gender) && $gender == 0) {
                                                            echo 'checked';
                                                        }
                                                        ?>>
                                                        <label class="custom-control-label" for="male"><?php echo $this->lang->line('text_male'); ?></label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input id="female" name="gender" type="radio" class="custom-control-input" value="1" <?php
                                                        if (isset($profile_detail['gender']) && $profile_detail['gender'] == '') {
                                                            
                                                        } else if (isset($profile_detail['gender']) && $profile_detail['gender'] == 1) {
                                                            echo 'checked';
                                                        } elseif (isset($gender) && $gender == 1) {
                                                            echo 'checked';
                                                        }
                                                        ?>>
                                                        <label class="custom-control-label" for="female"><?php echo $this->lang->line('text_female'); ?></label>
                                                    </div>
                                                </div>                                                
                                            </div> 
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                        <label for="profile_image"><?php echo $this->lang->line('text_profile_image'); ?></label>
                                                        <input type="file" id="profile_image" name="profile_image" class="form-control" >
                                                        <input type="hidden" id="file-input" name="old_profile_image"  value="<?php echo (isset($profile_detail['profile_image'])) ? $profile_detail['profile_image'] : ''; ?>" class="form-control-file">                                                                                                      
                                                        <?php echo form_error('profile_image', '<em style="color:red">', '</em>'); ?>                                                        
                                                        <?php echo form_error('home_sec_bnr_image', '<em style="color:red">', '</em>'); ?>
                                                        <?php if (isset($profile_detail['profile_image']) && $profile_detail['profile_image'] != '' && file_exists($this->profile_image . $profile_detail['profile_image'])) { ?>
                                                            <br>
                                                            <img src ="<?php echo base_url() . $this->profile_image . "thumb/100x100_" . $profile_detail['profile_image'] ?>" >
                                                        <?php } ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="user_template "><?php echo $this->lang->line('text_web_template'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <select id="user_template" name="user_template" class="form-control">
                                                        <option value="" ><?php echo $this->lang->line('text_select'); ?></option>

                                                        <option value="bmuseradmin" <?php if (isset($profile_detail['user_template']) && $profile_detail['user_template'] == 'bmuseradmin') echo 'selected';?>><?php echo $this->lang->line('text_user_admin'); ?></option>
                                                        <option value="bmuserapp" <?php if (isset($profile_detail['user_template']) && $profile_detail['user_template'] == 'bmuserapp') echo 'selected';?>><?php echo $this->lang->line('text_user_mobile'); ?></option>
                                                    </select>
                                                    <?php echo form_error('user_template', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                            </div>                                                                                   
                                            <div class="form-group text-center">
                                                <button type="submit" value="<?php echo $this->lang->line('text_update_profile'); ?>" <?php if ($this->member->front_member_username == 'demouser' && $this->system->demo_user == 1) echo 'disabled'; ?> name="profile_submit" class="btn btn-primary"><?php echo $this->lang->line('text_update_profile'); ?></button>                                                                                              
                                            </div>                                                  
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4" id="profile_setting">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_changepassword'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form method="POST" enctype="multipart/form-data" action="<?php echo base_url() . $this->path_to_default ?>profile/" id="change-password-form" >                                           
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <label for="old_password"><?php echo $this->lang->line('text_old_password'); ?></label>
                                                    <input id="old_password" type="password" class="form-control" name="old_password" value="<?php if (isset($old_password)) echo $old_password; ?>">
                                                    <?php echo form_error('old_password', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label for="new_password"><?php echo $this->lang->line('text_new_password'); ?></label>
                                                    <input id="new_password" type="password" class="form-control" name="new_password" value="<?php if (isset($new_password)) echo $new_password; ?>">
                                                    <?php echo form_error('new_password', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label for="c_passowrd"><?php echo $this->lang->line('text_confirm_password'); ?></label>
                                                    <input id="c_passowrd" type="password" class="form-control" name="c_passowrd" value="<?php if (isset($c_passowrd)) echo $c_passowrd; ?>">
                                                    <?php echo form_error('c_passowrd', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                            </div>                                                                                    
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_changepassword'); ?>" <?php if ($this->member->front_member_username == 'demouser' && $this->system->demo_user == 1) echo 'disabled'; ?> name="change_password" class="btn btn-primary">                                                    
                                            </div>                                                  
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
        <script>
            var cnt = 0;
            $("#close_btn").click(function () {
                $("input[name='mobile_no']").val($("input[name='old_mobile_no']").val());
                $("button[name='profile_submit']").removeAttr('disabled');
            });
            $("#verify_otp").click(function () {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url() . $this->path_to_default; ?>/profile/verifyOTP',
                    data: {
                        'otp': $("input[name='otp']").val(),
                        'otp_mobile': $("input[name='otp_mobile']").val(),
                        'otp_country_code': $("input[name='otp_country_code']").val(),
                    },
                    success: function (response) {
                        obj = JSON.parse(response);
                        if (obj != true) {
                            $(".otp-error").removeClass('d-none');
                        } else {
                            $("input[name='old_mobile_no']").val($("input[name='mobile_no']").val());
                            $("#myModal").modal('hide');
                            $("button[name='profile_submit']").removeAttr('disabled');
                        }
                    }
                });
            });
            $("#otp-modal").click(function () {
                if ($("#g-recaptcha-response").val() != '') {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url() . $this->path_to_default; ?>/profile/sendOTP',
                        data: {
                            'mobile_no': $("input[name='mobile_no']").val(),
                            'country_code': $("select[name=country_code]").val(),
                            'grecaptcha_response': $("#g-recaptcha-response").val()
                        },
                        success: function (response) {
                            obj = JSON.parse(response);
                            if (obj != true) {
//                                alert(obj);
                            } else {
                                jQuery.noConflict();
                                $("#otp_mobile").val($("input[name='mobile_no']").val());
                                $("#otp_country_code").val($("select[name=country_code]").val());
                                $('.error').addClass('d-none');
                                $("#myModal").modal('show');
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '<?php echo $this->lang->line('text_oops'); ?>',
                        text: '<?php echo $this->lang->line('text_err_captcha'); ?>',
                    });
                }
            });
            function change_mobile_no(val) {
                if ($("input[name='old_mobile_no']").val() != val) {
                    $.ajax({
                        type: 'get',
                        url: '<?php echo base_url() . $this->path_to_default; ?>/profile/checkMobile',
                        data: {
                            mobile_no: val,
                        },
                        success: function (response) {
                            obj = JSON.parse(response)
                            if (obj != true) {
                                $('.mobile-no-error').removeClass('d-none');
                            } else {
                                cnt++;
                                if (cnt == 1) {
                                    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
                                    recaptchaVerifier.render();
                                    window.recaptchaVerifier.render().then(function (widgetId) {
                                        window.recaptchaWidgetId = widgetId;
                                    });
                                }
                                $("#recaptcha-container").removeClass('d-none');
                                $(".verify-btn").removeClass('d-none');
                                $('.mobile-no-error').addClass('d-none');
                            }
                            $("button[name='profile_submit']").attr('disabled', 'disabled');
                        }
                    });
                } else {
                    $("#recaptcha-container").addClass('d-none');
                    $(".verify-btn").addClass('d-none');
                    $("button[name='profile_submit']").removeAttr('disabled');
                }
            }

            $(document).ready(function () {
                $('#new_password').passtrength({
                    minChars: 4,
                    passwordToggle: true,
                    tooltip: true
                });
                jQuery.validator.addMethod("noSpace", function (value, element) { //Code used for blank space Validation 
                    return value.indexOf(" ") < 0 && value != "";
                }, "No spaces allowed in user name");
                $("#profile-form").validate({
                    rules: {
                        'first_name': {
                            required: true,
                        },
                        'last_name': {
                            required: true,
                        },
                        'user_name': {
                            required: true,
                            noSpace: true,
                        },
                        'country_id': {
//                            required: true,
                        },
                        'mobile_no': {
                            required: true,
                            number: true,
                            maxlength: 15,
                            minlength: 7,
                            //                            remote: "<?php echo base_url() . $this->path_to_default . "profile/checkMobile"; ?>",
                        },
                        'email_id': {
                            required: true,
                            email: true,
                            //                            remote: "<?php echo base_url() . $this->path_to_default . "profile/checkEmail"; ?>",
                        },
                        'user_template': {
                            required: true,
                        },
                    },
                    messages: {
                        'first_name': {
                            required: "<?php echo $this->lang->line('err_first_name_req'); ?>",
                        },
                        'last_name': {
                            required: "<?php echo $this->lang->line('err_last_name_req'); ?>",
                        },
                        'user_name': {
                            required: "<?php echo $this->lang->line('err_user_name_req'); ?>",
                        },
                        'country_id': {
                            required: "<?php echo $this->lang->line('err_country_req'); ?>",
                        },
                        'mobile_no': {
                            required: "<?php echo $this->lang->line('err_mobile_no_req'); ?>",
                            maxlength: "<?php echo $this->lang->line('err_mobile_no_min'); ?>",
                            minlength: "<?php echo $this->lang->line('err_mobile_no_max'); ?>",
                            remote: "<?php echo $this->lang->line('err_mobile_no_exist'); ?>",
                        },
                        'email_id': {
                            required: "<?php echo $this->lang->line('err_email_req'); ?>",
                            email: "<?php echo $this->lang->line('err_email_id_valid'); ?>",
                            remote: "<?php echo $this->lang->line('err_email_id_exist'); ?>",
                        },
                        'user_template': {
                            required: '<?php echo $this->lang->line('err_user_template_req'); ?>',
                        },
                    },
                });
                $("#change-password-form").validate({
                    rules: {

                        old_password: {
                            required: true,
                        },
                        new_password: {
                            required: true,
                            minlength: 6,
                        },
                        c_passowrd: {
                            required: true,
                            equalTo: "#new_password",
                        },
                    },
                    messages: {
                        old_password: {
                            required: "<?php echo $this->lang->line('err_old_password_req'); ?>",
                        },
                        new_password: {
                            required: "<?php echo $this->lang->line('err_new_password_req'); ?>",
                        },
                        c_passowrd: {
                            required: "<?php echo $this->lang->line('err_c_passowrd_req'); ?>",
                            equalTo: "<?php echo $this->lang->line('err_c_passowrd_equal'); ?>"
                        },
                    },
                });
            });
        </script>
    </body>
</html>