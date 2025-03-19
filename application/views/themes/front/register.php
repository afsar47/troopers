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
        <?php if ($this->system->google_login == 'yes') { ?>
        <!--            <meta name="google-signin-client_id" content="<?php echo $this->system->google_client_id; ?>">
                    <meta name="google-signin-cookiepolicy" content="single_host_origin">
                    <meta name="google-signin-scope" content="profile email">
                    <style type="text/css">
                        #customBtn {
                            display: inline-block;
                            background: white;
                            color: #444;
                            width: 100%;
                            border-radius: 5px;
                            border: thin solid #888;
                            box-shadow: 1px 1px 1px grey;
                            white-space: nowrap;
                            text-align: center;
                        }
                        #customBtn:hover {
                            cursor: pointer;
                        }
                        span.label {
                            font-family: serif;
                            font-weight: normal;
                        }
                        span.icon {
                            background: url('<?php echo $this->template_img; ?>g-normal.png') transparent 5px 50% no-repeat;
                            display: inline-block;
                            vertical-align: middle;
                            width: 42px;
                            height: 42px;
                        }
                        span.buttonText {
                            display: inline-block;
                            vertical-align: middle;
                            /*                padding-left: 42px;
                                            padding-right: 42px;*/
                            font-size: 14px;
                            font-weight: bold;

                            /* Use the Arial, Helvetica, sans-serif, Roboto font that is loaded in the <head> */
                            font-family: 'Arial, Helvetica, sans-serif, Roboto', sans-serif;
                        }

                    </style>-->
        <?php } ?>
    </head>
    <body>
        <!--START TOP AREA-->
        <header class="top-area" id="home">
            <div class="header-top-area" id="scroolup">
                <!--MAINMENU AREA-->
                <?php $this->load->view($this->path_to_view_front . 'header_body'); ?>
                <!--END MAINMENU AREA END-->
            </div>
            <div class="page-header d-flex" <?php if(!empty($page)) { ?>style="background-image:url('<?php echo base_url() . $this->page_banner . $page['page_banner_image']; ?>')" <?php } ?> >
                <div class="black-overlay"></div>
                <div class="container m-auto">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <h1 class="text-uppercase"><?php echo $page_title; ?></h1>
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
                <?php if(!empty($page)) { ?>
                    <div class="col-md-12 text-center"><?php echo $page['page_content']; ?></div>
                <?php } ?>
                    <div class="col-md-12 content">

                        <section class="bm-light-bg text-dark">
                            <div class="container">
                                <div class="modal fade" id="mobileModal" role="dialog">
                                    <div class="modal-dialog">
                                        <form action="<?php echo base_url() ?>register" method="POST" id="verify-form">                                           
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title text-capitalize"><?php echo $this->lang->line('text_enter_mobile_no'); ?></h6>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <select class="form-control" name="country_code">
                                                                <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                                <?php
                                                                foreach ($country as $c) {
                                                                    ?>
                                                                    <option value="<?php echo $c->p_code; ?>" <?php
                                                                    if (isset($country_code) && $country_code == $c->p_code)
                                                                        echo 'selected';
                                                                    ?>><?php echo $c->p_code . ' (' . $c->country_name . ')'; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="text" id="mobile_via" name="mobile_no" class="form-control" placeholder="<?php echo $this->lang->line('text_enter_mobile_no'); ?>">    
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="email_id_div">
                                                        <input type="text" id="email_id_via" name="email_id" class="form-control" placeholder="<?php echo $this->lang->line('text_enter_email'); ?>">    
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" id="referral_code_via" name="referral_code" class="form-control" placeholder="<?php echo $this->lang->line('text_enter_promo_code'); ?>"> 
                                                    </div>
                                                    <div id="recaptcha-container_via"></div>
                                                    <input type="hidden" id="user_name_via" name="user_name" class="form-control"> 
                                                    <input type="hidden" id="login_via" name="login_via" class="form-control"> 
                                                    <!--<input type="hidden" id="email_id_via" name="email_id" class="form-control">--> 
                                                    <input type="hidden" id="g_id_via" name="g_id" class="form-control"> 
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" id="close_btn" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('text_btn_close'); ?></button>
                                                    <button type="submit" id="submit_btn" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit_via" class="btn btn-submit btn-lightpink"><?php echo $this->lang->line('text_verify'); ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">            
                                    <div class="offset-md-3 col-md-6 col_right">
                                        <div class="card cnt-card">
                                            <div class="card-body">
                                                <form action="<?php echo base_url(); ?>register/" method="post" id="register-form" novalidate>
                                                    <div class="form-group">
                                                        <label for="user_name"><?php echo $this->lang->line('text_user_name'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <input id="user_name" type="text" class="form-control" name="user_name" value="<?= set_value('user_name') ?>">
                                                        <?php echo form_error('user_name', '<em style="color:red">', '</em>'); ?> 
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="country_code"><?php echo $this->lang->line('text_country_code'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <select class="form-control" name="country_code">
                                                            <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                            <?php
                                                            foreach ($country as $c) {
                                                                ?>
                                                                <option value="<?php echo $c->p_code; ?>" <?php
                                                                if (isset($country_code) && $country_code == $c->p_code)
                                                                    echo 'selected';
                                                                ?>><?php echo $c->p_code . ' (' . $c->country_name . ')'; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                        </select>
                                                        <?php echo form_error('country_code', '<em style="color:red">', '</em>'); ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="mobile_no"><?php echo $this->lang->line('text_mobile_no'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <input id="mobile_no" type="text" class="form-control" name="mobile_no" value="<?= set_value('mobile_no') ?>">
                                                        <?php echo form_error('mobile_no', '<em style="color:red">', '</em>'); ?> 
                                                    </div>       
                                                    <div class="form-group">
                                                        <label for="email_id"><?php echo $this->lang->line('text_email'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <input id="email_id" type="text" class="form-control" name="email_id" value="<?= set_value('email_id') ?>">
                                                        <?php echo form_error('email_id', '<em style="color:red">', '</em>'); ?> 
                                                    </div>  
                                                    <!--                                                    <div class="form-group">
                                                                                                            <label for="country_id"><?php echo $this->lang->line('text_country'); ?><span class="required" aria-required="true"> * </span></label>
                                                                                                            <select class="form-control" name="country_id">
                                                                                                                <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                    <?php
                                                    foreach ($country as $c) {
                                                        ?>
                                                                                                                            <option value="<?php echo $c->country_id; ?>" <?php
                                                        if (isset($country_id) && $country_id == $c->country_id)
                                                            echo 'selected';
                                                        ?>><?php echo $c->country_name . ' (' . $c->p_code . ')'; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                                                                            </select>
                                                    <?php echo form_error('country_id', '<em style="color:red">', '</em>'); ?>
                                                                                                        </div>-->
                                                    <div class="form-group">
                                                        <label for="referral_code"><?php echo $this->lang->line('text_promo_code'); ?></label>
                                                        <input id="referral_code" type="text" class="form-control" name="referral_code" <?php if (isset($sponsor)) echo 'readonly=""'; ?> value="<?php if (isset($sponsor)) echo $sponsor;elseif (isset($referral_code)) echo $referral_code; ?>">
                                                        <?php echo form_error('referral_code', '<em style="color:red">', '</em>'); ?> 
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password"><?php echo $this->lang->line('text_password'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <input id="password" type="password" class="form-control" name="password">
                                                        <?php echo form_error('password', '<em style="color:red">', '</em>'); ?> 
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="c_password"><?php echo $this->lang->line('text_confirm_password'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <input id="c_password" type="password" class="form-control" name="c_password">
                                                        <?php echo form_error('c_password', '<em style="color:red">', '</em>'); ?> 
                                                    </div>
                                                    <div id="recaptcha-container"></div>
                                                    <input type="submit" id="sign-in-button" class="btn btn-submit btn-block btn-lg btn-lightpink mb-3" name="register" value="<?php echo $this->lang->line('text_btn_submit')?>">
                                                    <?php if ($this->system->google_login == 'yes') { ?>
                                                        <!--                                                        <div id="gSignInWrapper" class="mb-3 btn-submit "> 
                                                                                                                    <div id="customBtn" class="customGPlusSignIn">
                                                                                                                        <span class="icon"></span>
                                                                                                                        <span class="buttonText">Login With Google</span>
                                                                                                                    </div>
                                                                                                                </div>-->
                                                    <?php } ?>
                                                    <?php if ($this->system->fb_login == 'yes') { ?>
                                                        <!--                                                        <div class="rounded btn-submit text-center rounded-5 mb-2" style="background-color: #1c76f2;height: 44px;padding-top: 2px;">
                                                                                                                    <div class="fb-login-button" data-size="large" data-button-type="login_with" data-layout="default" data-auto-logout-link="false" scope="public_profile, email" data-use-continue-as="false" data-width=""></div>
                                                                                                                </div>
                                                                                                                <div id="fb-root"></div>-->
                                                    <?php } ?>
                                                    <!--<button id="test"> test</button>-->
                                                    <div class="form-group text-center">                                    
                                                        You have an Account ? <a href="<?php echo base_url(); ?>login"><?php echo $this->lang->line('text_sign_in'); ?></a>
                                                    </div>
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
        <?php if ($this->system->firebase_otp == 'yes') { ?>
            <script type="text/javascript">
                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
                recaptchaVerifier.render();
                window.recaptchaVerifier.render().then(function (widgetId) {
                    window.recaptchaWidgetId = widgetId;
                });
                //                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container_via');
                //                recaptchaVerifier.render();
                //                window.recaptchaVerifier.render().then(function (widgetId) {
                //                    window.recaptchaWidgetId = widgetId;
                //                });

            </script>
        <?php } ?>
        <?php if ($this->system->fb_login == 'yes') { ?>
            <!--<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v7.0&appId=<?php echo $this->system->fb_app_id; ?>&autoLogAppEvents=1" nonce="vYcaGpkh"></script>-->
    <!--            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v8.0" nonce="M8V5lVOi"></script>
            <script src="//connect.facebook.net/en_US/sdk.js"></script>
            <script>
                FB.init({
                    appId: '<?php echo $this->system->fb_app_id; ?>',
                    status: true,
                    xfbml: true,
                    version: 'v2.7'
                });
                FB.Event.subscribe('auth.authResponseChange', checkLoginState);
                function checkLoginState(event) {
                    if (event.authResponse) {
                        var unsubscribe = firebase.auth().onAuthStateChanged(function (firebaseUser) {
                            console.log(firebaseUser);
                            unsubscribe();
                            if (!isUserEqualFB(event.authResponse, firebaseUser)) {
                                var credential = firebase.auth.FacebookAuthProvider.credential(
                                        event.authResponse.accessToken);
                                firebase.auth().signInWithCredential(credential).catch(function (error) {
                                    var errorCode = error.code;
                                    var errorMessage = error.message;
                                    var email = error.email;
                                    var credential = error.credential;
                                });
                            } else {
                            }
                            FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email'},
                                    function (response) {
                                        console.log(response);
                                        $.ajax({
                                            url: "<?php echo base_url(); ?>register/register_google_fb",
                                            data: {
                                                user_name: response.first_name + ' ' + response.last_name,
                                                email_id: response.email,
                                                g_id: response.id,
                                                login_via: "FB"
                                            },
                                            type: 'post',
                                            success: function (result) {
                                                obj = JSON.parse(result);
                                                if (obj == 'success') {
                                                    if (response.email == '') {
                                                        $("#email_id_div").addClass('d-none');
                                                    }
                                                    $("#email_id_via").val(response.email);
                                                    $("#user_name_via").val(response.first_name + ' ' + response.last_name);
                                                    $("#login_via").val('FB');
                                                    $("#g_id_via").val(response.id);
                                                    $("#mobileModal").modal('show');
                                                    //                                                    window.location.reload();
                                                } else {
                                                    toastr.error("<?php echo $this->lang->line('err_acc_exist'); ?>");
                                                }

                                            }
                                        });
                                    });
                        });
                    } else {
                        firebase.auth().signOut();
                    }
                }
                function isUserEqualFB(facebookAuthResponse, firebaseUser) {
                    if (firebaseUser) {
                        var providerData = firebaseUser.providerData;
                        for (var i = 0; i < providerData.length; i++) {
                            if (providerData[i].providerId === firebase.auth.FacebookAuthProvider.PROVIDER_ID &&
                                    providerData[i].uid === facebookAuthResponse.userID) {
                                return true;
                            }
                        }
                    }
                    return false;
                }
            </script>-->
        <?php } ?>

        <?php if ($this->system->google_login == 'yes') { ?>
        <!--            <script src="https://cdn.rawgit.com/oauth-io/oauth-js/c5af4519/dist/oauth.js"></script>    
                    <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
                    <script src="https://apis.google.com/js/api:client.js"></script>
                    <script>
                        var googleUser = {};
                        gapi.load('auth2', function () {
                            auth2 = gapi.auth2.init({
                                client_id: '<?php echo $this->system->google_client_id; ?>',
                                cookiepolicy: 'single_host_origin',
                                scope: 'profile email'
                            });
                            attachSignin(document.getElementById('customBtn'));
                        });
                        function attachSignin(element) {

                            var auth2 = gapi.auth2.getAuthInstance();
                            auth2.attachClickHandler(element, {},
                                    function (googleUser) {
                                        var profile = googleUser.getBasicProfile();
                                        var unsubscribe = firebase.auth().onAuthStateChanged(function (firebaseUser) {
                                            unsubscribe();
                                            if (!isUserEqualgoogle(googleUser, firebaseUser)) {
                                                var credential = firebase.auth.GoogleAuthProvider.credential(
                                                        googleUser.getAuthResponse().id_token);
                                                firebase.auth().signInWithCredential(credential).catch(function (error) {
                                                    var errorCode = error.code;
                                                    var errorMessage = error.message;
                                                    var email = error.email;
                                                    var credential = error.credential;
                                                });
                                            } else {
                                                console.log('User already signed-in Firebase.');
                                                return;
                                            }
                                            $.ajax({
                                                url: "<?php echo base_url(); ?>register/register_google_fb",
                                                data: {
                                                    user_name: profile.getName(),
                                                    email_id: profile.getEmail(),
                                                    g_id: profile.getId(),
                                                    login_via: "Google"
                                                },
                                                type: 'post',
                                                success: function (result) {
                                                    obj = JSON.parse(result);
                                                    if (obj == 'success') {
                                                        $("#email_id_via").val(profile.getEmail());
                                                        $("#user_name_via").val(profile.getName());
                                                        $("#login_via").val('Google');
                                                        $("#g_id_via").val(profile.getId());
                                                        $("#mobileModal").modal('show');
                                                        //                                                window.location.reload();
                                                    } else {
                                                        toastr.error("<?php echo $this->lang->line('err_acc_exist'); ?>");
                                                    }

                                                }
                                            });
                                        });
                                    }, function (error) {
                            });
                        }
                        function isUserEqualgoogle(googleUser, firebaseUser) {
                            if (firebaseUser) {
                                var providerData = firebaseUser.providerData;
                                for (var i = 0; i < providerData.length; i++) {
                                    if (providerData[i].providerId === firebase.auth.GoogleAuthProvider.PROVIDER_ID &&
                                            providerData[i].uid === googleUser.getBasicProfile().getId()) {
                                        return true;
                                    }
                                }
                            }
                            return false;
                        }
                    </script>-->
        <?php } ?>
        <script>
            $('#test').click(function () {
                $("#email_id_via").val('kd@gmail.com');
                $("#user_name_via").val('sdfg gdfgghdfh');
                $("#login_via").val('FB');
                $("#g_id_via").val('dsggdfgdfg');
                $("#mobileModal").modal('show');
            });
            $(document).ready(function () {
                jQuery.validator.addMethod("noSpace", function (value, element) { //Code used for blank space Validation 
                    return value.indexOf(" ") < 0 && value != "";
                }, "No spaces allowed in user name");
                $("#verify-form").validate({
                    rules: {
                        'country_code': {
                            required: true,
                        },
                        'mobile_no': {
                            required: true,
                            number: true,
                            maxlength: 15,
                            minlength: 7,
                            remote: "<?php echo base_url(); ?>register/checkMobile",
                        },
                        'email_id': {
                            required: true,
                            email: true,
                            remote: "<?php echo base_url(); ?>register/checkEmail",
                        },
                        'referral_code': {
                            remote: "<?php echo base_url(); ?>register/checkReferralCode",
                        },
                    }, messages: {
                        'country_code': {
                            required: "<?php echo $this->lang->line('err_country_code_req'); ?>",
                        },
                        'mobile_no': {
                            required: "<?php echo $this->lang->line('err_mobile_no_req'); ?>",
                            maxlength: "<?php echo $this->lang->line('err_mobile_no_max'); ?>",
                            minlength: "<?php echo $this->lang->line('err_mobile_no_min'); ?>",
                            remote: "<?php echo $this->lang->line('err_mobile_no_exist'); ?>",
                        },
                        'email_id': {
                            required: "<?php echo $this->lang->line('err_email_id_req'); ?>",
                            remote: "<?php echo $this->lang->line('err_email_id_exist'); ?>",
                        },
                        'referral_code': {
                            remote: "<?php echo $this->lang->line('err_promo_code_valid'); ?>",
                        },
                    }
                });
                $("#register-form").validate({
                    rules: {
                        'user_name': {
                            required: true,
                            remote: "<?php echo base_url(); ?>register/checkUsername",
                            noSpace: true,
                        },
                        'mobile_no': {
                            required: true,
                            number: true,
                            maxlength: 15,
                            minlength: 7,
                            remote: "<?php echo base_url(); ?>register/checkMobile",
                        },
                        'email_id': {
                            required: true,
                            email: true,
                            remote: "<?php echo base_url(); ?>register/checkEmail",
                        },
//                        'country_id': {
//                            required: true,
//                        },
                        'country_code': {
                            required: true,
                        },
                        'referral_code': {
                            remote: "<?php echo base_url(); ?>register/checkReferralCode",
                        },
                        'password': {
                            required: true,
                            minlength: 6,
                        },
                        'c_password': {
                            required: true,
                            equalTo: "#password"
                        },
                    },
                    messages: {
                        'user_name': {
                            required: "<?php echo $this->lang->line('err_user_name_req'); ?>",
                            remote: "<?php echo $this->lang->line('err_user_name_exist'); ?>",
                        },
                        'mobile_no': {
                            required: "<?php echo $this->lang->line('err_mobile_no_req'); ?>",
                            maxlength: "<?php echo $this->lang->line('err_mobile_no_max'); ?>",
                            minlength: "<?php echo $this->lang->line('err_mobile_no_min'); ?>",
                            remote: "<?php echo $this->lang->line('err_mobile_no_exist'); ?>",
                        },
                        'email_id': {
                            required: "<?php echo $this->lang->line('err_email_req'); ?>",
                            remote: "<?php echo $this->lang->line('err_email_id_exist'); ?>",
                        },
//                        'country_id': {
//                            required: "<?php echo $this->lang->line('err_country_req'); ?>",
//                        },
                        'country_code': {
                            required: "<?php echo $this->lang->line('err_country_code_req'); ?>",
                        },
                        'referral_code': {
                            remote: "<?php echo $this->lang->line('err_promo_code_valid'); ?>",
                        },
                        'password': {
                            required: "<?php echo $this->lang->line('err_password_req'); ?>",
                        },
                        'c_password': {
                            required: "<?php echo $this->lang->line('err_c_passowrd_req'); ?>",
                            equalTo: "<?php echo $this->lang->line('err_c_passowrd_equal'); ?>",
                        },
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });
            });
        </script>
    </body> 
</html>
