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
        <main class="bm-full-width bm-full-height">
            <div class="container-fluid">
                <div class="row d-flex">
                    <div class="col-xl-4 col-left">
                        <div class="bm-modal">
                            <div class="bm-mdl-header">
                                <a href="<?php echo base_url() . $this->path_to_default . 'refer_earn'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a>
                                
                                <?php
                                    if($this->session->userdata('site_lang') && in_array($this->session->userdata('site_lang'),json_decode($this->system->rtl_supported_language,true))) {
                                        $drop_dir = '';
                                    } else {
                                        $drop_dir = 'dropleft';
                                    }
                                ?>

                                <div class="dropdown <?php echo $drop_dir; ?> d-inline">
                                    <a href="#" class="float-right text-white " data-toggle="dropdown">
                                        <i class="fa fa-2x fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="<?php echo base_url() . $this->path_to_default; ?>referrals/">My Referral</a>
                                        <a class="dropdown-item" href="<?php echo base_url() . $this->path_to_default; ?>leaderbord/">Leaderboard</a>
                                        <a class="dropdown-item" href="<?php echo base_url() . $this->path_to_default; ?>terms/">Terms & Conditions</a>
                                    </div>
                                </div>
                            </div>
                            <div class="bm-mdl-center bm-full-height pb-6 bg-white">
                                <div class="content-section">
                                    <div class="bm-content-listing">   
                                        <div class="container support">
                                            <div class="row">
                                                <div class="col-12 text-dark text-center">
                                                    <h4 class="bm_text_lightgreen"> REFER MORE TO EARN MORE</h4>
                                                    <p><?php echo $this->system->referandearn_description; ?></p>
                                                    <br>
                                                    <p class="bm_text_lightgreen mb-2"> YOUR REFERRAL CODE</p>
                                                    <h5 id="refer-code" onclick="copyToClipboard('#refer-code')" style="cursor:pointer;"><?php echo $this->member->front_member_username; ?><i class="fa fa-copy ml-3"></i></h5>                                           
                                                    <br>
                                                    <span class="copied text-white bg-black rounded px-2" style="position: absolute;left: 35%;z-index: 10;"></span>

                                                    <img src="<?php echo $this->default_img; ?>how_refer_earn.jpg" alt='refer_earn' class="img-fluid img-responsive">                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                       
                        </div>
                    </div>
                    <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
                </div>
            </div>
        </main>
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