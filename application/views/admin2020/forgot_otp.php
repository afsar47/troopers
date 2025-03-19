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
       <!-- MAIN SECTION -->
       <form class="form-signin text-center text-white" action="<?php echo base_url() . $this->path_to_view_admin; ?>login/verfiy_OTP" method="post" id="otp-form" novalidate>
        <img class="mb-4" src="<?php echo base_url() . $this->company_image . "thumb/189x40_" . $this->system->company_logo ?>" alt="">
        <h1 class="h3 mb-3 font-weight-normal text-white"><?php echo $this->lang->line('text_otp'); ?></h1>
            <div class="form-group">                                                                     
                <input id="otp" type="text" class="form-control" name="otp" value="<?= set_value('otp') ?>" placeholder="<?php echo $this->lang->line('text_enter_otp'); ?>">
                <?php echo form_error('otp', '<em style="color:red">', '</em>'); ?> 
            </div>      
            <!--<div><span id="timer"></span><a class="d-none" id="resend-otp" href="<?php echo base_url(); ?>register/verfiy"> Resend OTP</a></div>-->                                                                                                                                                      
            <input type="submit" class="btn btn-submit btn-block btn-lg btn-primary" name="send" value="<?php echo $this->lang->line('text_btn_submit'); ?>">                                                    
        </form> 
        <!-- END MAIN SECTION -->
    </body>
    <script src="<?php echo $this->admin_js; ?>jquery.min.js" ></script>
    <script src="<?php echo $this->admin_js; ?>bootstrap.bundle.min.js" ></script>
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script> 
    <script>
            let timerOn = true;
            function timer(remaining) {

                var m = Math.floor(remaining / 60);
                var s = remaining % 60;
                m = m < 10 ? '0' + m : m;
                s = s < 10 ? '0' + s : s;
                document.getElementById('timer').innerHTML = m + ':' + s;
                remaining -= 1;
                if (remaining >= 0 && timerOn) {
                    setTimeout(function () {
                        timer(remaining);
                    }, 1000);
                    return;
                }
                if (!timerOn) {
                    // Do validate stuff here
                    return;
                }
                // Do timeout stuff here
                $("#resend-otp").removeClass("d-none");
            }
            $(document).ready(function () {
                timer(60);
                jQuery.validator.addMethod("noSpace", function (value, element) { //Code used for blank space Validation 
                    return value.indexOf(" ") < 0 && value != "";
                }, "No spaces allowed in user name");
                $("#otp-form").validate({
                    rules: {
                        'otp': {
                            required: true,
                            remote: "<?php echo base_url() . $this->path_to_view_admin; ?>login/checkOTP",
                        },
                    },
                    messages: {
                        'otp': {
                            required: "<?php echo $this->lang->line('err_otp_req'); ?>",
                            remote: "<?php echo $this->lang->line('err_otp_remote'); ?>",
                        },
                    },
                });
            });
        </script>
</html>