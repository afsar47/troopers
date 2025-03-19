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
    <body oncontextmenu="return:false;">
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
                    <div class="row">
                        <?php
                        if (!empty($product_data)) {
                            foreach ($product_data as $product) {
                                if (isset($product->image_name) && $product->image_name != "") {
                                    $product_img = base_url() . $this->select_image . 'thumb/253x90_' . $product->image_name;
                                } elseif (isset($product->product_image) && $product->product_image != "") {
                                    $product_img = base_url() . $this->product_image . 'thumb/253x90_' . $product->product_image;
                                }
                                ?>
                                <div class="col-lg-4 col-md-6">
                                    <a href="<?php echo base_url() . $this->path_to_default . 'product/product_detail/' . $product->product_id; ?>">
                                        <div class="card card-sm-3 mb-4" style="">
                                            <img src="<?php echo $product_img; ?>" class="img-responsive" style="width: 100%;">
                                            <div class="p-1">
                                                <h6 class="text-dark"><?php
                                                    if (strlen($product->product_name) <= 50)
                                                        echo $product->product_name;
                                                    else
                                                        echo substr($product->product_name, 0, 50) . '...';
                                                    ?></h6>
                                                <div class="row">
                                                    <h6 class="col-6 text-info"><strike><?php echo  $this->functions->getPoint() . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $product->product_actual_price); ?></strike></h6>
                                                    <h6 class="col-6 pull-right text-info text-right"><?php echo $this->functions->getPoint() . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $product->product_selling_price); ?></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<div class='col-md-12 text-center text-black'><strong>No Products </strong></div>";
                        }
                        ?>    
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
    </body>
</html>