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
                    <?php
                    if (isset($product['image_name']) && $product['image_name'] != "") {
                        $product_img = base_url() . $this->select_image . 'thumb/1000x500_' . $product['image_name'];
                    } elseif (isset($product['product_image']) && $product['product_image'] != "") {
                        $product_img = base_url() . $this->product_image . 'thumb/1000x500_' . $product['product_image'];
                    }
                    ?>
                    <div class="bm-mdl-center bm-full-height">
                        <div class="bm-content-listing">
                            <div class="col-md-6">
                                <div class="match-info">
                                    <img src="<?php echo $product_img; ?>" alt="product_img" width="100%">                                
                                </div>
                                <div class="px-3" style="position: relative;top: -20px;">
                                    <div class="card mb-2 p-2" style="min-height: unset;">
                                        <h6 class="text-black"><?php echo $product['product_short_description']; ?></h6>
                                        <div class="text-black"><?php echo $product['product_name']; ?></div>
                                        <div class="row">
                                            <h6 class="col-6 text-info"><strike><?php echo $this->functions->getPoint() . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $product['product_actual_price']); ?></strike></h6>
                                            <h6 class="col-6 pull-right text-info text-right"><?php echo $this->functions->getPoint() . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $product['product_selling_price']); ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-2 p-2" style="min-height: unset;">
                                <h6 class="text-black">Description</h6>
                                <div class="text-black">
                                    <?php echo $product['product_description']; ?>
                                </div>
                            </div>
                            <a class="btn btn-sm btn-primary" href="<?php echo base_url() . $this->path_to_default . 'product/order/' . $product['product_id']; ?>"> BUY NOW </a>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
    </body>
</html>