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
                                <p class="badge badge-light float-right f-18 text-black d-inline" id="tot_wallet"><?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', 0); ?></p>
                                <h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="bm-mdl-center bm-full-height p-1">
                                <!--                                <div class="row">
                                                                    <div class="col-md-12">-->
                                <?php
                                if (!empty($product_data)) {
                                    foreach ($product_data as $product) {
                                        if (isset($product->image_name) && $product->image_name != "") {
                                            $product_img = base_url() . $this->select_image . 'thumb/253x90_' . $product->image_name;
                                        } elseif (isset($product->product_image) && $product->product_image != "") {
                                            $product_img = base_url() . $this->product_image . 'thumb/253x90_' . $product->product_image;
                                        }
                                        ?>
                                        <div class="product-card">
                                            <a href="<?php echo base_url() . $this->path_to_default . 'product/product_detail/' . $product->product_id; ?>"><img src="<?php echo $product_img; ?>" class="img-fluid card-img-top"></a>
                                            <div class="card-body p-2">
                                                <h6 class="card-title mb-0 text-black"><?php if(strlen($product->product_name) <= 22) echo $product->product_name; else echo substr($product->product_name,0,22).'...'; ?></h6>           
                                                <div class="row">
                                                    <div class="col-6 text-info"><strike><?php echo '<span style="">' . $this->functions->getPoint() . '</span>' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $product->product_actual_price); ?></strike></div>
                                                    <div class="col-6 pull-right text-info text-right"><?php echo '<span style="">' . $this->functions->getPoint() . '</span>' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $product->product_selling_price); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo "<div class='col-md-12 text-center text-black'><strong>" . $this->lang->line('text_no_products') . "</strong></div>";
                                }
                                ?>    
                                <!--                                    </div>
                                                                </div>-->
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