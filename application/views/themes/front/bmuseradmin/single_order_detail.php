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
                        <h3><?php echo $order_data['order_no']; ?></h3>
                        <div class="btn-toolbar mb-2 mb-md-0">                          
                        </div>
                    </div>
                    <?php
                    if (!empty($order_data)) {
                        $product_img = '';
                        if (isset($order_data['product_image']) && $order_data['product_image'] != "") {
                            $product_img = base_url() . $this->product_image . 'thumb/1000x500_' . $order_data['product_image'];
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
                                            <h6 class="text-black"><?php echo $order_data['product_name']; ?></h6>
                                            <div class="row">
                                                <h6 class="col-12 pull-right text-info text-right"><?php echo $this->functions->getPoint() . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $order_data['product_price']); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-2 p-2" style="min-height: unset;">
                                    <h6 class="text-black mb-0"><?php echo $this->lang->line('text_name'); ?> : </h6>
                                    <div class="text-black mb-2">
                                        <?php
                                        $shipping_address = @unserialize($order_data['shipping_address']);
                                        echo $shipping_address['name'];
                                        ?>
                                    </div>
                                    <h6 class="text-black mb-0"><?php echo $this->lang->line('text_address'); ?> : </h6>
                                    <div class="text-black mb-2">
                                        <?php
                                        echo $shipping_address['address'];
                                        ?>
                                    </div>
                                    <?php if (isset($shipping_address['add_info']) && $shipping_address['add_info'] != '') { ?>
                                        <h6 class="text-black mb-0"><?php echo $this->lang->line('text_additional_info'); ?> : </h6>
                                        <div class="text-black mb-2">
                                            <?php
                                            echo $shipping_address['add_info'];
                                            ?>
                                        </div>
                                    <?php } ?>
                                    <h6 class="text-black mb-0"><?php echo $this->lang->line('text_order_date'); ?> : </h6>
                                    <div class="text-black mb-2">
                                        <?php echo date_format(date_create($order_data['created_date']), "d F Y"); ?>
                                    </div> 
                                    <h6 class="text-black mb-0"><?php echo $this->lang->line('text_status'); ?> : </h6>
                                    <div class="text-black mb-2">
                                        <?php echo $order_data['order_status']; ?>
                                    </div>
                                    <?php if ($order_data['tracking_id'] != 0) { ?>
                                        <h6 class="text-black mb-0"><?php echo $this->lang->line('text_track_order'); ?></h6>
                                        <div class="text-black mb-2">
                                            <?php echo "<a target='_blank' href='" . $order_data['courier_link'] . $order_data['tracking_id'] . "'>" . $order_data['courier_link'] . $order_data['tracking_id'] . "</a>"; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
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