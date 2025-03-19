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
                        <div class="btn-toolbar mb-2 mb-md-0">                          
                        </div>
                    </div>
                    <ul class="list-unstyled f-18">
                        <li class="py-3 align-middle border-bottom border-dark">Address: <?php echo $this->system->company_address; ?></li>
                        <li class="py-3 align-middle border-bottom border-dark">Phone: <?php echo $this->system->comapny_phone; ?> <span class="float-right  bm_text_lightgreen"><a class="support bm_text_lightgreen" style="" href="tel:<?php echo $this->system->comapny_phone; ?>"><i class="fa fa-2x fa-phone"></i></a><a class="support bm_text_lightgreen" target="_blank" href="<?php echo 'https://api.whatsapp.com/send?phone=' . $this->system->comapny_phone; ?>"><i class="fa fa-2x fa-commenting ml-2"></i></a></span></li>
                        <li class="py-3 align-middle border-bottom border-dark">Email: <?php echo $this->system->company_email; ?> <span class="float-right bm_text_lightgreen"><a class="support bm_text_lightgreen" href="mailto:<?php echo $this->system->company_email; ?>"><i class="fa fa-2x fa-envelope"></i></a></span></li>
                        <li class="py-3 align-middle border-bottom border-dark">Instagram: <span class="float-right bm_text_lightgreen"><a class="support bm_text_lightgreen" target="_blank" href="https://www.instagram.com/<?php echo $this->system->insta_link; ?>"><i class="fa fa-2x fa-instagram"></i></a></span></li>
                        <li class="py-3 align-middle border-bottom border-dark">Street: <?php echo $this->system->company_street; ?></li>
                        <li class="py-3 align-middle border-bottom border-dark">Time: <?php echo $this->system->company_time; ?></li>
                    </ul>

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