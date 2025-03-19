<!--====== SCRIPTS JS ======-->
<!--script src="<?php //echo $this->template_js;                             ?>jquery-3.4.1.min.js"></script-->
<!--script src="<?php //echo $this->template_js;                             ?>jquery.validate.min.js"></script-->
<!--script src="<?php //echo $this->template_js;                             ?>bootstrap.min.js"></script-->
<!--script src="<?php //echo $this->template_js;                             ?>owl.carousel.min.js"></script-->
<!--script src="<?php //echo $this->template_js;                             ?>jquery.magnific-popup.min.js"></script-->
<!--script src="<?php //echo $this->template_js;                             ?>custom.js"></script-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.7.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

<?php if ($this->system->firebase_otp == 'yes' || $this->system->fb_login == 'yes' || $this->system->google_login == 'yes') { ?>
    <script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>
    <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
    <script>
       <?php echo $this->system->firebase_script; ?>
    </script>
<?php } ?>

<?php if ($this->system->fb_login == 'yes') { ?>
    <script src="//connect.facebook.net/en_US/sdk.js"></script>
<?php } ?>
<script>
<?php if ($this->system->fb_login == 'yes') { ?>
        window.onload = function ()
        {
            // initialize the library with your Facebook API key
            FB.init({
                appId: '<?php echo $this->system->fb_app_id; ?>',
                status: true,
                xfbml: true,
                version: 'v2.7'
            });
    <?php if (isset($register) || isset($login)) { ?>
                FB.getLoginStatus(function (response) {
                    console.log(response);
                    if (response && response.status === 'connected') {
                        FB.logout(function (response) {
                            console.log('fb User signed out.');
                        });
                    }
                });
    <?php } ?>
        }
<?php } ?>
<?php if ($this->system->google_login == 'yes') { ?>
        function onLoad() {
            gapi.load('auth2', function () {
                gapi.auth2.init();
            });
        }
<?php } ?>

    function logout() {
<?php if ($this->system->fb_login == 'yes') { ?>
            FB.getLoginStatus(function (response) {
                console.log(response);
                if (response && response.status === 'connected') {
                    FB.logout(function (response) {
                        console.log('fb User signed out.');
                    });
                }
            });
<?php } ?>
<?php if ($this->system->google_login == 'yes') { ?>
            //            var auth2 = gapi.auth2.getAuthInstance();
            //            console.log(auth2);
            //            auth2.signOut().then(function () {
            //                console.log('google User signed out.');
            //                auth2.disconnect();
            //            });
            //            auth2.disconnect();
<?php } ?>
<?php if ($this->system->firebase_otp == 'yes' || $this->system->fb_login == 'yes' || $this->system->google_login == 'yes') { ?>

            firebase.auth().signOut().then(function () {
                // Sign-out successful.
                console.log('firebase User signed out.');
            }).catch(function (error) {
                // An error happened.
            });
<?php } ?>
    }


    $(document).ready(function () {
        $('.owl-carousel').owlCarousel({
            <?php
                if($this->session->userdata('site_lang') && in_array($this->session->userdata('site_lang'),json_decode($this->system->rtl_supported_language,true))) {
            ?>
            rtl:true,
            <?php
                } else {
            ?>
            rtl:false,
            <?php
                }
            ?>            
            loop: true,
            margin: 10,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                600: {
                    items: 3,
                    nav: true
                },
                1000: {
                    items: 4,
                    nav: true,
                    loop: false,
                    margin: 20
                }
            }
        });
        $('.popup-link').magnificPopup({
            removalDelay: 300,
            type: 'image',
            callbacks: {
                beforeOpen: function () {
                    this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure ' + this.st.el.attr('data-effect'));
                },
                beforeClose: function () {
                    $('.mfp-figure').addClass('fadeOut');
                }
            },
            gallery: {
                enabled: true //enable gallery mode
            }
        });

        $("#contact-form").validate({
            rules: {
                'fname': {
                    required: true,
                },
                'email': {
                    required: true,
                },
                'subject': {
                    required: true,
                },
                'message': {
                    required: true,
                }
            },
            messages: {
                'fname': {
                    required: '<?php echo $this->lang->line('err_fname_req'); ?>',
                },
                'email': {
                    required: '<?php echo $this->lang->line('err_email_req'); ?>',
                },
                'subject': {
                    required: '<?php echo $this->lang->line('err_subject_req'); ?>',
                },
                'message': {
                    required: '<?php echo $this->lang->line('err_message_req'); ?>',
                }
            },
            errorPlacement: function (error, element)
            {
                error.insertAfter(element);
            },
        });
    });
    $(document).on('submit', '#contact-form', function () {
        $.ajax({
            url: '<?php echo base_url(); ?>page/contact',
            type: 'post',
            data: $(this).serialize(),
            success: function (data) {
                if (data)
                {
                    $("#success").html('<div class="alert alert-success"><?php echo $this->lang->line('text_succ_msg'); ?></div>');
                    $('#fname').val('');
                    $('#email').val('');
                    $('#phone').val('');
                    $('#subject').val('');
                    $('#msg').val('');
                } else {
                    $('#success').html('<div class="alert alert-danger"><?php echo $this->lang->line('text_err_msg'); ?></div>');
                }
            }
        });
        return false;
    });
</script>
<!--<script src="<?php echo $this->template_js; ?>cookit.js"></script>-->
<?php if (isset($register) || isset($login)) { ?>    
    <script src = "<?php echo $this->template_js; ?>toastr.min.js" type = "text/javascript" ></script>
    <script>
        var UIToastr = function () {
            return {
                init: function () {
                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "positionClass": "toast-top-right",
                        "showDuration": "1000",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
    <?php if ($this->session->flashdata('success')) { ?>
                        toastr.success("<?php echo $this->session->flashdata('success'); ?>");
    <?php } else if ($this->session->flashdata('notification')) { ?>
                        toastr.success("<?php echo $this->session->flashdata('notification'); ?>");
    <?php } else if ($this->session->flashdata('error')) { ?>
                        toastr.error("<?php echo $this->session->flashdata('error'); ?>");
    <?php } ?>
                }
            };
        }();

        jQuery(document).ready(function () {
            UIToastr.init();
        });
    </script>
<?php } ?>
<?php 
    if($this->system->footer_script != '') {
        echo $this->system->footer_script;
    }
?>