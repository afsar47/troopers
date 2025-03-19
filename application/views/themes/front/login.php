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
            <meta name="google-signin-client_id" content="<?php echo $this->system->google_client_id; ?>">
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

            </style>
        <?php } ?>

    </head>
    <body >
        <!--START TOP AREA-->
        <header class="top-area" id="home">
            <div class="header-top-area" id="scroolup">
                <!--MAINMENU AREA-->
                <?php $this->load->view($this->path_to_view_front . 'header_body'); ?>
                <!--END MAINMENU AREA END-->
            </div>
            <div class="page-header d-flex" style="background-image:url('<?php echo base_url() . $this->page_banner . $page['page_banner_image']; ?>')" >
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

                    <div class="col-md-12 content">
                        <section class="bm-light-bg text-dark">
                            <div class="container">
                                <div class="modal fade" id="myModal" role="dialog">
                                    <div class="modal-dialog">
                                        <form action="<?php echo base_url() ?>login/send_otp" method="POST" id="forgat-form">                                           
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"><?php echo $this->lang->line('text_reset_password'); ?></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php
                                                    if ($this->system->msg91_otp == '0')
                                                        echo $this->lang->line('err_email_req');
                                                    else
                                                        echo $this->lang->line('err_email_mobile_no_req');
                                                    ?>
                                                    <input type="text" id="email_mobile" name="email_mobile" class="form-control" placeholder="<?php
                                                    if ($this->system->msg91_otp == '0')
                                                        echo $this->lang->line('text_email_address');
                                                    else
                                                        echo $this->lang->line('text_email_or_mobile');
                                                    ?>">                            
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="forgot" class="btn btn-submit btn-lightpink"><?php echo $this->lang->line('text_send_otp'); ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal fade" id="mobileModal" role="dialog">
                                    <div class="modal-dialog">
                                        <form action="<?php echo base_url() ?>login" method="POST" id="verify-form">                                           
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
                                                    <div class="form-group">
                                                        <input type="text" id="referral_code_via" name="referral_code" class="form-control" placeholder="<?php echo $this->lang->line('text_enter_promo_code'); ?>"> 
                                                    </div>
                                                    <div id="recaptcha-container_via"></div>
                                                    <input type="hidden" id="user_name_via" name="user_name" class="form-control"> 
                                                    <input type="hidden" id="login_via" name="login_via" class="form-control"> 
                                                    <input type="hidden" id="member_id" name="member_id" class="form-control"> 
                                                    <!--<input type="hidden" id="user_name" name="user_name" class="form-control">--> 
                                                    <input type="hidden" id="g_id_via" name="g_id" class="form-control"> 
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('text_btn_close'); ?></button>
                                                    <button type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit_via" class="btn btn-submit btn-lightpink"><?php echo $this->lang->line('text_verify'); ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">                                      
                                    <div class="col-md-12 text-center"><?php echo $page['page_content']; ?></div>
                                    <div class="offset-md-3 col-md-6 col_right">
                                        <div class="card cnt-card">
                                            <div class="card-body">
                                                <form action="<?php echo base_url() ?>login/" method="post" id="login-form" novalidate>
                                                    <div class="form-group">
                                                        <label for="user_name"><?php echo $this->lang->line('text_user_name'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <input id="user_name" type="text" class="form-control" name="user_name" <?php
                                                        if ($this->system->demo_user == 1) {
                                                            echo 'value="demouser"';
                                                        }
                                                        ?> >
                                                               <?php echo form_error('user_name', '<em style="color:red">', '</em>'); ?> 
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password"><?php echo $this->lang->line('text_password'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <input id="password" type="password" class="form-control" name="password" <?php
                                                        if ($this->system->demo_user == 1) {
                                                            echo 'value="password"';
                                                        }
                                                        ?> >
                                                               <?php echo form_error('password', '<em style="color:red">', '</em>'); ?> 
                                                    </div>    
                                                    <div class="form-group">
                                                        <?php echo $this->lang->line('text_forgot_password'); ?> <a href="" id="forgot-modal" data-target="#myModal" data-toggle="modal"><?php echo $this->lang->line('text_reset_now'); ?></a>
                                                    </div>
                                                    <input type="submit" class="btn btn-submit btn-block btn-lg btn-lightpink mb-3 " name="login" value="<?php echo $this->lang->line('text_btn_submit'); ?>">
                                                    <?php if ($this->system->google_login == 'yes') { ?>
                                                        <div id="gSignInWrapper" class="mb-3 btn-submit "> 
                                                            <div id="customBtn" class="customGPlusSignIn">
                                                                <span class="icon"></span>
                                                                <span class="buttonText">Login With Google</span>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if ($this->system->fb_login == 'yes') { ?>
                                                        <div class="rounded btn-submit text-center rounded-5 mb-2" style="background-color: #1c76f2;height: 44px;padding-top: 2px;">
                                                            <div class="fb-login-button" data-size="large" data-button-type="login_with" data-layout="default" data-auto-logout-link="false" scope="public_profile, email" data-use-continue-as="false" data-width=""></div>
                                                        </div>
                                                        <div id="fb-root"></div>
                                                    <?php } ?>
                                                    <div class="form-group text-center">                                    
                                                        <?php echo $this->lang->line('text_not_acc'); ?> <a href="<?php echo base_url(); ?>register"><?php echo $this->lang->line('text_sign_up'); ?></a>
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
                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container_via');
                recaptchaVerifier.render();
                window.recaptchaVerifier.render().then(function (widgetId) {
                window.recaptchaWidgetId = widgetId;
                });
            </script>
        <?php } ?>
        <?php if ($this->system->fb_login == 'yes') { ?>
                                                                                                                            <!--<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v7.0&appId=<?php echo $this->system->fb_app_id; ?>&autoLogAppEvents=1" nonce="vYcaGpkh"></script>-->
            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v8.0" nonce="M8V5lVOi"></script>
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
                        url: "<?php echo base_url(); ?>login/login_google_fb",
                                data: {
                                user_name: response.first_name + ' ' + response.last_name,
                                        email_id: response.email,
                                        g_id: response.id,
                                        login_via: "FB"
                                },
                                type: 'post',
                                success: function (result) {
                                obj = JSON.parse(result);
                                console.log(obj);
                                if (obj.status == 'success') {
                                window.location.reload();
                                } else if (obj.member.mobile_no == '') {

                                $("#email_id_via").val(obj.member.email_id);
                                if (obj.member.new_user == 'Yes') {
                                $("#referral_code_via").prop("type", "text");
                                } else{
                                $("#referral_code_via").prop("type", "hidden");
                                }
                                $("#member_id").val(obj.member.member_id);
                                $("#user_name_via").val(obj.member.user_name);
                                //                                $("#user_name_via").val(response.first_name + ' ' + response.last_name);
                                $("#login_via").val('FB');
                                $("#g_id_via").val(response.id);
                                $("#mobileModal").modal('show');
                                //                                toastr.error("<?php echo $this->lang->line('err_acc_not_exist'); ?>");
                                //                                $('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>Error: Please try again later or login with password</div>').insertBefore($('div.account-login'));
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
            </script>
        <?php } ?>

        <?php if ($this->system->google_login == 'yes') { ?>
            <script src="https://cdn.rawgit.com/oauth-io/oauth-js/c5af4519/dist/oauth.js"></script>    
            <!-- <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
            <script src="https://apis.google.com/js/api:client.js"></script> -->
            <script src="https://apis.google.com/js/api.js"></script>
            
            <script>
                var googleUser = {};
                gapi.load('auth2', function(){
                auth2 = gapi.auth2.init({
                client_id: '<?php echo $this->system->google_client_id; ?>',
                        cookiepolicy: 'single_host_origin',
                        scope: 'profile email',
                        plugin_name: "chat"
                });
                attachSignin(document.getElementById('customBtn'));
                });
                function attachSignin(element) {

                var auth2 = gapi.auth2.getAuthInstance();
                auth2.attachClickHandler(element, {},
                        function(googleUser) {
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
                        }
                        $.ajax({
                        url: "<?php echo base_url(); ?>login/login_google_fb",
                                data: {
                                user_name: profile.getName(),
                                        email_id: profile.getEmail(),
                                        g_id: firebaseUser.uid,
                                        // g_id: profile.getId(),
                                        login_via: "Google"
                                },
                                type: 'post',
                                success: function (result) {
                                obj = JSON.parse(result);
                                console.log(obj);
                                if (obj.status == 'success') {
                                window.location.reload();
                                } else if (obj.member.mobile_no == '') {
                                $("#email_id_via").val(obj.member.email_id);
                                if (obj.member.new_user == 'Yes') {
                                $("#referral_code_via").prop("type", "text");
                                } else{
                                $("#referral_code_via").prop("type", "hidden");
                                }
                                $("#member_id").val(obj.member.member_id);
                                $("#user_name_via").val(obj.member.user_name);
                                //                                $("#user_name_via").val(response.first_name + ' ' + response.last_name);
                                $("#login_via").val('Google');
                                $("#g_id_via").val(firebaseUser.uid);
                                // $("#g_id_via").val(profile.getId());
                                $("#mobileModal").modal('show');
                                //                                toastr.error("<?php echo $this->lang->line('err_acc_not_exist'); ?>");
                                //                                $('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>Error: Please try again later or login with password</div>').insertBefore($('div.account-login'));
                                }
                                //                                if (obj == 'success') {
                                //                                window.location.reload();
                                //                                } else {
                                //                                toastr.error("<?php echo $this->lang->line('err_acc_not_exist'); ?>");
                                $('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>Error: Please try again later or login with password</div>').insertBefore($('div.account-login'));
                                //                                }

                                }
                        });
                        });
                        }, function(error) {
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
            </script>
        <?php } ?>
        <script>
            $(document).ready(function () {
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
                            remote: "<?php echo base_url(); ?>login/checkMobile",
                    },
//                    'email_id': {
//                    required: true,
//                            email: true,
//                            remote: {
//                            url:"<?php echo base_url(); ?>login/checkEmail",
//                                    data: {'login_via':function(){return $("#login_via").val()}, 'member_id':function(){return $("#member_id").val()}, },
//                                    async:false
//                            }
////                    remote: "<?php echo base_url(); ?>login/checkEmail/" + $("#login_via").val() + '/' + $("#member_id").val(),
//                    },
                    'referral_code': {
                    remote: "<?php echo base_url(); ?>login/checkReferralCode",
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
//                    'email_id': {
//                    required: "<?php echo $this->lang->line('err_email_id_req'); ?>",
//                            remote: "<?php echo $this->lang->line('err_email_id_exist'); ?>",
//                    },
                    'referral_code': {
                    remote: "<?php echo $this->lang->line('err_promo_code_valid'); ?>",
                    },
            }
            });
            $("#login-form").validate({
            rules: {
            'user_name': {
            required: true,
            },
                    'password': {
                    required: true,
                    },
            },
                    messages: {
                    'user_name': {
                    required: "<?php echo $this->lang->line('err_user_name_req'); ?>",
                    },
                            'password': {
                            required: "<?php echo $this->lang->line('err_password_req'); ?>",
                            },
                    },
            });
            $("#forgat-form").validate({
            rules: {
            'email_mobile': {
            required: true,
<?php if ($this->system->msg91_otp == '0') { ?>
                email: true,
<?php } ?>
            },
            },
                    messages: {
                    'email_mobile': {
                    required: "<?php echo $this->lang->line('err_email_req'); ?>",
<?php if ($this->system->msg91_otp == '0') { ?>
                        email: "<?php echo $this->lang->line('err_email_id_valid'); ?>",
<?php } ?>
                    },
                    },
            });
            });
        </script>
    </body> 
</html>

