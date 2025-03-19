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
                                <a href="<?php echo base_url() . $this->path_to_default . 'account'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a><h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="bm-mdl-center bm-full-height pb-6">
                                <div class="content-section">
                                    <div class="bm-content-listing text-black">   
                                        <?php
                                        foreach ($app_tutorials as $app_tutorial) {
                                            $url = $app_tutorial->youtube_link;
                                            preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
                                            if (!empty($matches)) {
                                                $id = $matches[1];
                                            } else {
                                                $id = substr($url, -11);
                                            }
                                            $width = '800px';
                                            $height = '450px';
                                            ?>
                                            <h6><?php echo $app_tutorial->youtube_link_title; ?></h6>
                                            <iframe width="475" height="315" src="https://www.youtube.com/embed/<?php echo $id; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            <a target="_blank" href="<?php echo $app_tutorial->youtube_link; ?>" class="btn btn-block btn-green mt-2">PLAY IN YOUTUBE</a>
                                        <?php } ?>                                        
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