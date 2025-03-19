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
                        <h1 class="h2"><?php echo $this->lang->line('text_profile_setting'); ?></h1>
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
                    <div class="row mb-4" id="profile_setting">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_profile_setting'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form method="POST" enctype="multipart/form-data" action="<?php echo base_url() . $this->path_to_view_admin ?>profilesetting/" id="profile-form" >                                           
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="username"><?php echo $this->lang->line('text_user_name'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control"  name="username" value="<?php if (isset($username)) echo $username;elseif (isset($profile_detail['name'])) echo $profile_detail['name'] ?>">
                                                    <?php echo form_error('username', '<em style="color:red">', '</em>'); ?>
                                                    <input type="hidden" name="userid" value="<?php echo (isset($profile_detail['id'])) ? $profile_detail['id'] : '' ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="useremail"><?php echo $this->lang->line('text_email'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="last_name" type="text" class="form-control" name="useremail" value="<?php if (isset($useremail)) echo $useremail;elseif (isset($profile_detail['email'])) echo $profile_detail['email'] ?>">
                                                    <?php echo form_error('useremail', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                
                                            </div>                                                                                    
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="profile_submit" class="btn btn-primary" <?php
                                                if ($this->system->demo_user == 1) {
                                                    echo 'disabled';
                                                }
                                                ?>>                                                    
                                            </div>                                                  
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4" id="companysetting">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_company_setting'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form method="POST" enctype="multipart/form-data" action="<?php echo base_url() . $this->path_to_view_admin ?>profilesetting/" id="contactus-form" >                                           
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="company_logo"><?php echo $this->lang->line('text_logo'); ?></label><br>
                                                    <input type="file" class=""  name="company_logo" ><br>
                                                    <input type="hidden" id="file-input" name="old_company_logo"  value="<?php echo (isset($this->system->company_logo)) ? $this->system->company_logo : ''; ?>" class="form-control-file">                                                                                                      
                                                    <?php echo form_error('company_logo', '<em style="color:red">', '</em>'); ?>
                                                    <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_189x40'); ?></p> 
                                                    <?php echo form_error('home_sec_bnr_image', '<em style="color:red">', '</em>'); ?>
                                                    <?php if (isset($this->system->company_logo) && $this->system->company_logo != '' && file_exists($this->company_image . $this->system->company_logo)) { ?>
                                                        <br>
                                                        <img src ="<?php echo base_url() . $this->company_image . "thumb/189x40_" . $this->system->company_logo ?>" >
                                                    <?php } ?>  
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="company_favicon"><?php echo $this->lang->line('text_favicon'); ?></label><br>
                                                    <input type="file" class="" name="company_favicon" ><br>
                                                    <?php echo form_error('company_favicon', '<em style="color:red">', '</em>'); ?>
                                                    <input type="hidden" id="file-input" name="old_company_favicon"  value="<?php echo (isset($this->system->company_favicon)) ? $this->system->company_favicon : ''; ?>" class="form-control-file">                                                                                                      
                                                    <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_40x40'); ?></p>                                                        
                                                    <?php echo form_error('company_favicon', '<em style="color:red">', '</em>'); ?>
                                                    <?php if (isset($this->system->company_favicon) && $this->system->company_favicon != '' && file_exists($this->company_favicon . $this->system->company_favicon)) { ?>
                                                        <br>
                                                        <img src ="<?php echo base_url() . $this->company_favicon . "thumb/40x40_" . $this->system->company_favicon ?>" >
                                                    <?php } ?>  
                                                </div>                                                
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="company_name"><?php echo $this->lang->line('text_company_name'); ?></label>
                                                    <input type="text" class="form-control"  name="company_name" value="<?php if (isset($company_name)) echo $company_name;elseif (isset($this->system->company_name)) echo $this->system->company_name ?>">
                                                    <?php echo form_error('company_name', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="company_email"><?php echo $this->lang->line('text_email'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="last_name" type="text" class="form-control" name="company_email" value="<?php if (isset($company_email)) echo $company_email;elseif (isset($this->system->company_email)) echo $this->system->company_email ?>">
                                                    <?php echo form_error('company_email', '<em style="color:red">', '</em>'); ?>
                                                </div>  
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="comapny_country_code"><?php echo $this->lang->line('text_country_code'); ?></label>
                                                    <select class="form-control" name="comapny_country_code" >
                                                        <option value="">Select..</option>
                                                        <?php
                                                        foreach ($country_data as $country) {
                                                            ?>
                                                            <option value="<?php echo $country->p_code; ?>" <?php
                                                            if (isset($comapny_country_code) && $comapny_country_code == $country->p_code)
                                                                echo 'selected';
                                                            elseif (isset($this->system->comapny_country_code) && $this->system->comapny_country_code == $country->p_code)
                                                                echo 'selected';
                                                            else
                                                                echo '';
                                                            ?>><?php echo $country->p_code; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                    </select>
                                                    <?php echo form_error('comapny_country_code', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="comapny_phone"><?php echo $this->lang->line('text_phone'); ?></label>
                                                    <input type="text" class="form-control"  name="comapny_phone" value="<?php if (isset($comapny_phone)) echo $comapny_phone;elseif (isset($this->system->comapny_phone)) echo $this->system->comapny_phone ?>">
                                                    <?php echo form_error('comapny_phone', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="company_street"><?php echo $this->lang->line('text_street'); ?></label>
                                                    <textarea id="company_street" rows="3" class="form-control " name="company_street" ><?php if (isset($company_street)) echo $company_street;elseif (isset($this->system->company_street)) echo $this->system->company_street ?></textarea>
                                                    <?php echo form_error('company_street', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                                <div class="form-group col-md-6">
                                                    <label for="company_address"><?php echo $this->lang->line('text_address'); ?></label>
                                                    <textarea id="company_address" rows="3" class="form-control " name="company_address" ><?php if (isset($company_address)) echo $company_address;elseif (isset($this->system->company_address)) echo $this->system->company_address ?></textarea>
                                                    <?php echo form_error('company_address', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="company_time"><?php echo $this->lang->line('text_support_time'); ?></label>
                                                    <input type="text" class="form-control"  name="company_time" value="<?php if (isset($company_time)) echo $company_time;elseif (isset($this->system->company_time)) echo $this->system->company_time ?>">
                                                    <?php echo form_error('company_time', '<em style="color:red">', '</em>'); ?>
                                                </div>  

                                            </div> 
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="copyright_text"><?php echo $this->lang->line('text_copyright_text'); ?></label>
                                                    <textarea class="form-control ckeditor" id="editor1" name="copyright_text" ><?php if (isset($copyright_text)) echo $copyright_text;elseif (isset($this->system->copyright_text)) echo $this->system->copyright_text ?></textarea>
                                                    <?php echo form_error('copyright_text', '<em style="color:red">', '</em>'); ?>
                                                </div>  
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label "><?php echo $this->lang->line('text_about'); ?></label>
                                                        <div class="">
                                                            <textarea class="form-control ckeditor"  name="company_about" id="company_about" placeholder="Enter Page Content">
                                                                <?php
                                                                if (isset($company_about)):
                                                                    echo $company_about;
                                                                elseif (isset($this->system->company_about)):
                                                                    echo $this->system->company_about;
                                                                endif;
                                                                ?>
                                                            </textarea>
                                                            <?php echo form_error('company_about', '<em style="color:red">', '</em>'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                                                                     
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="contact_submit" class="btn btn-primary " <?php
                                                if ($this->system->demo_user == 1) {
                                                    echo 'disabled';
                                                }
                                                ?>>                                                    
                                            </div>                                                  
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4" id="companysetting">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_social_setting'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form method="POST"  action="<?php echo base_url() . $this->path_to_view_admin ?>profilesetting/" id="social-form" >                                           
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="fb_link"><?php echo $this->lang->line('text_fb_link'); ?></label>
                                                    <input type="text" class="form-control"  name="fb_link" value="<?php if (isset($fb_link)) echo $fb_link;elseif (isset($this->system->fb_link)) echo $this->system->fb_link ?>">
                                                    <?php echo form_error('fb_link', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="insta_link"><?php echo $this->lang->line('text_insta_link'); ?></label>
                                                    <input type="text" class="form-control"  name="insta_link" value="<?php if (isset($insta_link)) echo $insta_link;elseif (isset($this->system->insta_link)) echo $this->system->insta_link ?>">
                                                    <?php echo form_error('insta_link', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                                                               
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="twitter_link"><?php echo $this->lang->line('text_twitter_link'); ?></label>
                                                    <input type="text" class="form-control"  name="twitter_link" value="<?php if (isset($twitter_link)) echo $twitter_link;elseif (isset($this->system->twitter_link)) echo $this->system->twitter_link ?>">
                                                    <?php echo form_error('twitter_link', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="google_link"><?php echo $this->lang->line('text_gp_link'); ?></label>
                                                    <input type="text" class="form-control"  name="google_link" value="<?php if (isset($google_link)) echo $google_link;elseif (isset($this->system->google_link)) echo $this->system->google_link ?>">
                                                    <?php echo form_error('google_link', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                                                             
                                            </div>                                
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="social_submit" class="btn btn-primary " <?php
                                                       if ($this->system->demo_user == 1) {
                                                           echo 'disabled';
                                                       }
                                                       ?>>                                                    
                                            </div>                                                  
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4" id="companysetting">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_changepassword'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  id="change-password-form" novalidate="" method="POST" action="<?php echo base_url() . $this->path_to_view_admin ?>profilesetting">
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <label for="old_password"><?php echo $this->lang->line('text_old_password');?></label>
                                                    <input id="old_password" type="password" class="form-control" name="old_password" value="<?php if (isset($old_password)) echo $old_password; ?>">
                                                    <?php echo form_error('old_password', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label for="new_password"><?php echo $this->lang->line('text_new_password');?></label>
                                                    <input id="new_password" type="password" class="form-control" name="new_password" value="<?php if (isset($new_password)) echo $new_password; ?>">
                                                    <?php echo form_error('new_password', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label for="c_passowrd"><?php echo $this->lang->line('text_confirm_password');?></label>
                                                    <input id="c_passowrd" type="password" class="form-control" name="c_passowrd" value="<?php if (isset($c_passowrd)) echo $c_passowrd; ?>">
                                                    <?php echo form_error('c_passowrd', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                                                               
                                            </div>                                                                                                                                                                  
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_update');?>" name="password_update" class="btn btn-primary " <?php
                                                if ($this->system->demo_user == 1) {
                                                    echo 'disabled';
                                                }
                                                ?>>                                                    
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
            $(document).ready(function ($) {
                $('#new_password').passtrength({
                    minChars: 4,
                    passwordToggle: true,
                    tooltip: true
                });
            });
            $("#change-password-form").validate({
                rules: {
                    old_password: {
                        required: true,
                    },
                    new_password: {
                        required: true,
                    },
                    c_passowrd: {
                        required: true,
                        equalTo: "#new_password",
                    },
                },
                messages: {
                    old_password: {
                        required: '<?php echo $this->lang->line('err_old_password_req'); ?>',
                    },
                    new_password: {
                        required: '<?php echo $this->lang->line('err_new_password_req'); ?>',
                    },
                    c_passowrd: {
                        required: '<?php echo $this->lang->line('err_c_passowrd_req'); ?>',
                        equalTo: '<?php echo $this->lang->line('err_c_passowrd_equal'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#profile-form").validate({
                rules: {
                    username: {
                        required: true,
                    },
                    useremail: {
                        required: true,
                    }
                },
                messages: {
                    username: {
                        required: '<?php echo $this->lang->line('err_username_req'); ?>',
                    },
                    useremail: {
                        required: '<?php echo $this->lang->line('err_useremail_req'); ?>',
                    }
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#social-form").validate({
                rules: {
                    fb_link: {
                        url: true,
                    },
                    insta_link: {
                        url: true,
                    },
                    twitter_link: {
                        url: true,
                    },
                    google_link: {
                        url: true,
                    },
                },
                messages: {
                    fb_link: {
                        url: '<?php echo $this->lang->line('err_fb_link_valid'); ?>',
                    },
                    insta_link: {
                        url: '<?php echo $this->lang->line('err_insta_link_valid'); ?>',
                    },
                    twitter_link: {
                        url: '<?php echo $this->lang->line('err_twitter_link_valid'); ?>',
                    },
                    google_link: {
                        url: '<?php echo $this->lang->line('err_google_link_valid'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#contactus-form").validate({
                rules: {
                    company_name: {
//                        required: true,
                    },
                    comapny_phone: {
                        number: true,
                    },
                    comapny_country_code: {
//                        required: true,
//                        required: function () {
//                            if ($('input[name="comapny_phone"]').val() != "") {
//                                return true;
//                            } else {
//                                return false;
//                            }
//                        }
                    },
                    company_email: {
                        required: true,
                        email: true,
                    },
                    company_time: {
//                        required: true,
                    },
                    copyright_text: {
//                        required: function (textarea) {
//                            CKEDITOR.instances[textarea.id].updateElement();
//                            var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
//                            return editorcontent.length === 0;
//                        }
                    }
                },
                messages: {
                    company_name: {
                        required: '<?php echo $this->lang->line('err_company_name_valid'); ?>',
                    },
                    comapny_phone: {
                        required: '<?php echo $this->lang->line('err_mobile_no_number'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                    },
                    comapny_country_code: {
                        required: '<?php echo $this->lang->line('err_p_code_req'); ?>',
                    },
                    company_email: {
                        required: '<?php echo $this->lang->line('err_email_id_req'); ?>',
                        email: '<?php echo $this->lang->line('err_email_id_valid'); ?>',
                    },
                    company_street: {
                        required: '<?php echo $this->lang->line('err_company_street_valid'); ?>',
                    },
                    company_address: {
                        required: '<?php echo $this->lang->line('err_company_address_valid'); ?>',
                    },
                    company_time: {
                        required: '<?php echo $this->lang->line('err_company_time_valid'); ?>',
                    },
                    copyright_text: {
                        required: '<?php echo $this->lang->line('err_copyright_text_valid'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
        </script>
    </body>
</html>