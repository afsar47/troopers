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
                            <div class="col-lg-4 col-md-6">
                                <div class="box-shadow p-2 mb-2">
                                    <strong class="my-1"><?php echo $app_tutorial->youtube_link_title; ?></strong>
                                    <iframe class="border-0" height="100%" width="100%" src="https://www.youtube.com/embed/<?php echo $id; ?>"></iframe>
                                </div>
                                <a href="<?php echo $app_tutorial->youtube_link; ?>" target="_blank" class="d-block bg-lightgreen p-2 rounded text-white text-center"> Play in Youtube</a>   

                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>       
    </body>
</html>