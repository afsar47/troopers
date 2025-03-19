<script src="<?php echo $this->default_js; ?>jquery-3.4.1.min.js"></script>
<script src="<?php echo $this->default_js; ?>popper.min.js"></script>
<script src="<?php echo $this->default_js; ?>bootstrap.min.js"></script>
<script src="<?php echo $this->default_js; ?>jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php echo $this->default_js; ?>custom.js"></script>
<script src="<?php echo $this->default_js; ?>toastr.min.js"></script>
<script src="<?php echo $this->default_js; ?>jquery.validate.js"></script> 
<?php if ($this->system->firebase_otp == 'yes' || $this->system->fb_login == 'yes' || $this->system->google_login == 'yes') { ?>
    <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>

    <script>
       <?php echo $this->system->firebase_script; ?>
    </script>
    
<?php } ?>
<?php if ($this->system->google_login == 'yes') { ?>
    <script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>    
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
            var auth2 = gapi.auth2.getAuthInstance();
            auth2.signOut().then(function () {
                console.log('google User signed out.');
                auth2.disconnect();
            });
            auth2.disconnect();
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

    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
        $('#logo').toggleClass('hide-on-toggle');
        $('#m-logo').toggleClass('show-on-toggle');
    });
</script>
<?php if (isset($profilesetting)) { ?>
    <script src="<?php echo $this->template_js; ?>sweetalert2.all.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo $this->default_js; ?>passtrength.js"></script> 
    <script  type="text/javascript">
    $('#datetimepicker1').datepicker({
        format: 'mm/dd/yyyy',
    });
    </script>
<?php } ?>
<?php
    if(isset($tron_qr)) {
?>
    <script src="<?php echo $this->template_js; ?>jquery.qrcode.min.js"></script> 
<?php
    }
?>
<script>
    $(document).ready(function () {
        reload_header_wallet();
    });
    function reload_header_wallet() {
        $.getJSON("<?php echo base_url() . $this->path_to_default; ?>account/wallet", function (result) {
            tot_wallet = parseFloat(result['join_money']) + parseFloat(result['wallet_balance']);
            $("#tot_wallet").html('<span style=""><?php echo $this->functions->getPoint(); ?> </span>' + tot_wallet.toFixed(<?php echo $this->functions->getCurrencyDecimal($this->system->currency); ?>));
        });
    }


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