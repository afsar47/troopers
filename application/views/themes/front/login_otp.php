<!doctype html>
<?php
    if($this->session->userdata('site_lang') && in_array($this->session->userdata('site_lang'),json_decode($this->system->rtl_supported_language,true))) {
        $dir = 'rtl';
    } else {
        $dir = 'ltr';
    }
?>
<html dir='<?php echo $dir; ?>'>
    <head>
        <?php $this->load->view($this->path_to_view_front . 'header'); ?>
        <style>
            #clockdiv{ 
                font-family: sans-serif; 
                color: #fff; 
                display: inline-block; 
                font-weight: 100; 
                text-align: center; 
                font-size: 30px; 
                margin-bottom: 30px;
            } 
            #clockdiv > div{ 
                padding: 10px; 
                border-radius: 3px; 
                background: #eecd03; 
                display: inline-block; 
            } 
            #clockdiv div > span{ 
                padding: 15px; 
                border-radius: 3px; 
                background: #2e353b; 
                display: inline-block; 
            } 
            .smalltext{ 
                padding-top: 5px; 
                font-size: 16px; 
            } 
        </style>
    </head>
    <body >
        <!--START TOP AREA-->
        <header class="top-area" id="home">
            <div class="header-top-area" id="scroolup">
                <!--MAINMENU AREA-->
                <?php $this->load->view($this->path_to_view_front . 'header_body'); ?>
                <!--END MAINMENU AREA END-->
            </div>
            <div class="page-header d-flex" style="" >
                <div class="black-overlay"></div>
                <div class="container m-auto">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <h1 class="text-uppercase"><?php echo $page_menutitle; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!--END TOP AREA-->
        <!-- MAIN SECTION -->
        <section class="bm-section-padding about-section bm-light-bg text-dark">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 content">

                        <section class="bm-light-bg text-dark">
                            <div class="container">
                                <div class="row">                                            

                                    <div class="offset-md-3 col-md-6 col_right">
                                        <div class="card cnt-card">
                                            <div class="card-body">
                                                <form action="<?php echo base_url(); ?>login/verfiyvia_OTP" method="post" id="otp-form" novalidate>
                                                    <div class="form-group">
                                                        <label for="otp"><?php echo $this->lang->line('text_otp'); ?></label>                                                        
                                                        <input id="otp" type="text" class="form-control" name="otp" value="<?= set_value('otp') ?>" placeholder="<?php echo $this->lang->line('text_enter_otp'); ?>">
                                                        <?php echo form_error('otp', '<em style="color:red">', '</em>'); ?> 
                                                    </div>      
                                                    <div><span id="timer"></span><a class="d-none" id="resend-otp" href="<?php echo base_url(); ?>register/verfiy"> <?php echo $this->lang->line('text_resend_otp'); ?></a></div>                                                                                                                                                      
                                                    <input type="submit" class="btn btn-submit btn-block btn-lg btn-lightpink" name="send" value="<?php echo $this->lang->line('text_btn_submit'); ?>">                                                    
                                                </form> 
                                            </div>
                                        </div>
                                        <div id="recaptcha-container"></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>                
            </div>
        </section>
        <!-- END MAIN SECTION -->
        <!--FOOER AREA-->
        <?php $this->load->view($this->path_to_view_front . 'footer_body'); ?>
        <!--FOOER AREA END-->
        <?php $this->load->view($this->path_to_view_front . 'footer'); ?>
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
                    return;
                }
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
//                            remote: "<?php echo base_url(); ?>register/checkOTP",
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
    </body> 
</html>
