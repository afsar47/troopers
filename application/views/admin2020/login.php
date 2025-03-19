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

        <?php
            if($this->session->userdata('site_lang') && in_array($this->session->userdata('site_lang'),json_decode($this->system->rtl_supported_language,true))) {        
        ?>
            <link href="<?php echo $this->admin_css; ?>bootstrap_rtl.min.css" rel="stylesheet">            
        <?php  
            } else {
        ?>
            <link href="<?php echo $this->admin_css; ?>bootstrap.min.css" rel="stylesheet" >
        <?php     
            }
        ?>
        
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
    <!-- forgot modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <form action="<?php echo base_url() . $this->path_to_view_admin ?>login/send_otp" method="POST" id="forgat-form">                                           
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo $this->lang->line('text_reset_password'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <label><?php echo $this->lang->line('err_email_req'); ?></label>
                        <input type="text" id="email_mobile" name="email_mobile" class="form-control" placeholder="<?php echo $this->lang->line('text_email_address');?>">                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="forgot" class="btn btn-submit btn-primary"><?php echo $this->lang->line('text_send_otp'); ?></button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    <!-- //forgot modal -->

        <form class="form-signin text-center" id="validate-form" method="POST" action="<?php echo base_url() . $this->path_to_view_admin ?>login/checkdata" novalidate="">
                   
                    

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
            <h1 class="h3 mb-3 font-weight-normal text-white"><?php echo $this->lang->line('text_please_sign_in'); ?></h1>
            <div class="text-left">
                <input type="text" id="name" class="form-control" placeholder="<?php echo $this->lang->line('text_user_name'); ?>" name="name" autocomplete="off" value="<?php echo isset($login_data) ? 'admin' : ''; ?>">
                <input type="password" id="inputPassword" class="form-control mt-3" placeholder="<?php echo $this->lang->line('text_password'); ?>" name="password" autocomplete="off" value="<?php echo isset($login_data) ? 'password' : ''; ?>">
            </div>
            <div class="form-group text-white mt-2">
                <?php echo $this->lang->line('text_forgot_password'); ?> <a href="" id="forgot-modal" data-target="#myModal" data-toggle="modal"><?php echo $this->lang->line('text_reset_now'); ?></a>
            </div>
            <button class="btn btn-lg btn-primary btn-block mt-3" name="submit" type="submit"  value="submit"><?php echo $this->lang->line('text_sign_in'); ?></button>
            
            <div class="mt-5 mb-3  text-white"><?php echo $this->system->copyright_text; ?></div>
        </form>
    </body>
    <script src="<?php echo $this->admin_js; ?>jquery.min.js" ></script>
    <script src="<?php echo $this->admin_js; ?>bootstrap.bundle.min.js" ></script>
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script> 
    <script>
        $("#validate-form").validate({
            rules: {
                name: {
                    required: true,
                },
                password: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "<?php echo $this->lang->line('err_username_req'); ?>",
                },
                password: {
                    required: "<?php echo $this->lang->line('err_password_req'); ?>",
                },
            }
            ,
            errorPlacement: function (error, element)
            {
                if (element.is(":radio"))
                {
                    element.parent().parent().append(error);
                } else
                {
                    error.insertAfter(element);
                }
            },
        });

        $("#forgat-form").validate({
            rules: {
                'email_mobile': {
                    required: true,
                    email: true,
                },
            },
                    messages: {
                    'email_mobile': {
                        required: "<?php echo $this->lang->line('err_email_req'); ?>",
                            email: "<?php echo $this->lang->line('err_email_id_valid'); ?>",
                        },
                    },
            });

    </script>
</html>