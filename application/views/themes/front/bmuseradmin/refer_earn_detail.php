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
        <?php $this->load->view($this->path_to_view_default . 'header'); ?>        
    </head>
    <body>
        <?php $this->load->view($this->path_to_view_default . 'header_body'); ?>
        <div class="d-flex" id="wrapper">
            <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">                   
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h3><?php echo $breadcrumb_title; ?></h3>
                    </div>                     
                    <div class="row">   
                        <div class="offset-md-3 col-md-6">
                            <div class="card text-center box-shadow">
                                <div class="card-body">
                                    <h4 class="text-lightgreen text-uppercase mb-3">Refer More To Earn More</h4>
                                    <p class="mb-4"><?php echo $this->system->referandearn_description; ?></p>
                                    <h6 class="text-lightgreen text-uppercase mb-3">Your Referral Code</h6>
                                    <h6 id="refer-code" onclick="copyToClipboard('#refer-code')" style="cursor:pointer;"><?php echo $this->member->front_member_username; ?><i class="fa fa-copy ml-3"></i></h6>                                           
                                    <span class="copied text-white bg-black rounded px-2" style="position: absolute;left: 35%;z-index: 10;"></span>

                                    <img src="<?php echo $this->default_img . 'refer_earn.jpeg'; ?>" style="width: 100%" class="img-responsive mt-3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
        <script>
            function copyToClipboard(element) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).text()).select();
                document.execCommand("copy");
                $(".copied").text("Copied to clipboard").show().fadeOut(1200);
                $temp.remove();
            }
        </script>        
    </body>
</html>