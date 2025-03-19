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
                                                <form action="<?php echo base_url(); ?>login/change_password" method="post" id="changepassword-form" novalidate>
                                                    <div class="form-group">
                                                        <label for="password"><?php echo $this->lang->line('text_new_password'); ?></label>                                                        
                                                        <input type="password" id="new_password" class="form-control" name="new_password" placeholder="<?php echo $this->lang->line('text_enter_new_password'); ?>">
                                                        <?php echo form_error('new_password', '<em style="color:red">', '</em>'); ?> 
                                                    </div>  
                                                    <div class="form-group">
                                                        <label for="confirm_password"><?php echo $this->lang->line('text_confirm_password'); ?></label>                                                        
                                                        <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="<?php echo $this->lang->line('text_enter_confirm_password'); ?>">
                                                        <?php echo form_error('confirm_password', '<em style="color:red">', '</em>'); ?> 
                                                    </div>  
                                                    <!--<div><span id="timer"></span><a class="d-none" id="resend-otp" href="<?php echo base_url(); ?>register/verfiy"> Resend OTP</a></div>-->                                                                                                                                                      
                                                    <input type="submit" class="btn btn-submit btn-block btn-lg btn-lightpink" name="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>">                                                    
                                                </form> 
                                            </div>
                                        </div>
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
            $(document).ready(function () {
                $("#changepassword-form").validate({
                    rules: {
                        'new_password': {
                            required: true,
                            minlength: 6,
                        },
                        'confirm_password': {
                            required: true,
                            equalTo: "#new_password"
                        }
                    },
                    messages: {
                        'new_password': {
                            required: "<?php echo $this->lang->line('err_new_password_req'); ?>",
                            minlength: <?php echo $this->lang->line('err_password_min'); ?>",
                        },
                        'confirm_password': {
                            required: "<?php echo $this->lang->line('err_c_passowrd_req'); ?>",
                            equalTo: "<?php echo $this->lang->line('err_c_passowrd_equal'); ?>",
                        },
                    },
                });
            });
        </script>
    </body> 
</html>
