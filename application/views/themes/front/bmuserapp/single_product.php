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
                                <a href="<?php echo base_url() . $this->path_to_default . 'product'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a>
                                <h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <?php
                            if (isset($product['image_name']) && $product['image_name'] != "") {
                                $product_img = base_url() . $this->select_image . 'thumb/1000x500_' . $product['image_name'];
                            } elseif (isset($product['product_image']) && $product['product_image'] != "") {
                                $product_img = base_url() . $this->product_image . 'thumb/1000x500_' . $product['product_image'];
                            }
                            ?>
                            <div class="bm-mdl-center bm-full-height tab-pane">
                                <div class="content-section">
                                    <div class="bm-content-listing tournaments">
                                        <div class="match-info">
                                            <img src="<?php echo $product_img; ?>" alt="product_img" width="100%">                                
                                        </div>
                                        <div class="px-3" style="position: relative;top: -20px;">
                                            <div class="card mb-2 p-2" style="min-height: unset;">
                                                <h6 class="text-black"><?php echo $product['product_short_description']; ?></h6>
                                                <div class="text-black"><?php echo $product['product_name']; ?></div>
                                                <div class="row">
                                                    <h6 class="col-6 text-info"><strike><?php echo '<span style="">' . $this->functions->getPoint() . '</span>' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $product['product_actual_price']); ?></strike></h6>
                                                    <h6 class="col-6 pull-right text-info text-right"><?php echo '<span style="">' . $this->functions->getPoint() . '</span>' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $product['product_selling_price']); ?></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-3 p-2" style="min-height: unset;">
                                            <h6 class="text-black"><?php echo $this->lang->line('text_description'); ?></h6>
                                            <div class="text-black">
                                                <?php echo $product['product_description']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bm-mdl-footer text-white">
                                <a  class="btn btn-sm btn-block f-18 btn-lightpink text-uppercase" href="<?php echo base_url() . $this->path_to_default . 'product/order/' . $product['product_id']; ?>"> <?php echo $this->lang->line('text_buy_now'); ?> </a>
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