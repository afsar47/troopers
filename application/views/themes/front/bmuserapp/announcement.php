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
                                <a href="<?php echo base_url() . $this->path_to_default . 'play'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a>
                                <p class="badge badge-light float-right f-18 text-black d-inline" id="tot_wallet"><?php echo $this->functions->getPoint() . ' ' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', 0); ?></p>
                                <h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <?php foreach ($announcement_data as $announcement) { ?>
                                <div class="btn btn-sm btn-white shadow m-2 d-block text-left">
                                    <div>
                                        <?php echo $announcement->announcement_desc; ?></div>
                                </div>
                            <?php } ?>
                            <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
                        </div>
                    </div>
                    <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
                </div>
            </div>
        </main>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
    </body>
</html>