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
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Favicons -->
        <title><?php
            if (isset($title)) {
                echo $title;
            } else {
                echo "Battle Mania";
            }
            ?></title>
        <!-- Bootstrap core CSS -->
        <link href="<?php echo $this->admin_css; ?>bootstrap.min.css" rel="stylesheet" >
        <!-- Favicons -->
        <link rel="icon" href="<?php echo base_url() . $this->company_favicon . "thumb/40x40_" . $this->system->company_favicon ?>" />
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }
            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
            .error{
                color: red;
            }
        </style>
        <link href="<?php echo $this->admin_css; ?>signin.css" rel="stylesheet">
    </head>
    <body>  

    <form class="needs-validation form-signin text-white text-center"  id="change-password-form" novalidate="" method="POST" action="<?php echo base_url() . $this->path_to_view_admin ?>login/forgot_change_pass">
        <img class="mb-4" src="<?php echo base_url() . $this->company_image . "thumb/189x40_" . $this->system->company_logo ?>" alt="">
                <?php if ($this->session->flashdata('error')) { ?>
                        <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                            <span><?php echo $this->session->flashdata('error'); ?></span>
                        </div>
                    <?php } ?>
                <?php if ($this->session->flashdata('user')) { ?>
                                <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                                    <span><?php echo $this->session->flashdata('user'); ?></span>
                                </div>
                <?php } ?>
                <?php if ($this->session->flashdata('notification')) { ?>
                                <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                                    <span><?php echo $this->session->flashdata('notification'); ?></span>
                                </div>
                <?php } ?>
        <h1 class="h3 mb-3 font-weight-normal text-white"><?php echo $this->lang->line('text_changepassword'); ?></h1>
        <div class="row text-left">            
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
    </body>
    <script src="<?php echo $this->admin_js; ?>jquery.min.js" ></script>
    <script src="<?php echo $this->admin_js; ?>bootstrap.bundle.min.js" ></script>
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script> 
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
                    new_password: {
                        required: true,
                    },
                    c_passowrd: {
                        required: true,
                        equalTo: "#new_password",
                    },
                },
                messages: {                    
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
        </script>
</html>