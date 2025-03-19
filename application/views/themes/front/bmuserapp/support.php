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
                                <a href="<?php echo base_url() . $this->path_to_default . 'account/'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a><h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="bm-mdl-center bm-full-height pb-6">
                                <div class="content-section">
                                    <div class="bm-content-listing">   
                                        <div class="container support">
                                            <div class="row">
                                                <div class="col-12 text-dark">
                                                    <ul class="list-unstyled f-18">
                                                        <li class="py-3 align-middle border-bottom border-dark"><?php echo $this->lang->line('text_address'); ?>: <?php echo $this->system->company_address; ?></li>
                                                        <li class="py-3 align-middle border-bottom border-dark"><?php echo $this->lang->line('text_phone'); ?>: <?php echo $this->system->comapny_country_code . $this->system->comapny_phone; ?> <span class="float-right  bm_text_lightgreen"><a class="support bm_text_lightgreen" style="" href="tel:<?php echo $this->system->comapny_country_code . $this->system->comapny_phone; ?>"><i class="fa fa-2x fa-phone"></i></a><a class="support bm_text_lightgreen" target="_blank" href="<?php echo 'https://api.whatsapp.com/send?phone=' . $this->system->comapny_country_code . $this->system->comapny_phone; ?>"><i class="fa fa-2x fa-whatsapp ml-2"></i></a></span></li>
                                                        <li class="py-3 align-middle border-bottom border-dark"><?php echo $this->lang->line('text_email'); ?>: <?php echo $this->system->company_email; ?> <span class="float-right bm_text_lightgreen"><a class="support bm_text_lightgreen" href="mailto:<?php echo $this->system->company_email; ?>"><i class="fa fa-2x fa-envelope"></i></a></span></li>
                                                        <li class="py-3 align-middle border-bottom border-dark"><?php echo $this->lang->line('text_instagram'); ?>: <span class="float-right bm_text_lightgreen"><a class="support bm_text_lightgreen" target="_blank" href="https://www.instagram.com/<?php echo $this->system->insta_link; ?>"><i class="fa fa-2x fa-instagram"></i></a></span></li>
                                                        <li class="py-3 align-middle border-bottom border-dark"><?php echo $this->lang->line('text_street'); ?>: <?php echo $this->system->company_street; ?></li>
                                                        <li class="py-3 align-middle border-bottom border-dark"><?php echo $this->lang->line('text_support_time'); ?>: <?php echo $this->system->company_time; ?></li>
                                                    </ul>
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
    </body>
</html>