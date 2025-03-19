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
                                <a href="<?php echo base_url() . $this->path_to_default . 'account'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a>
                                <p class="badge badge-light float-right f-18 text-black d-inline" id="tot_wallet"><?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', 0); ?></p>
                                <h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="bm-mdl-center bm-full-height">
                                <div class="bm-content-listing tournaments" >
                                    <?php
                                    if (!empty($order_data)) {
                                        foreach ($order_data as $order) {
                                            $product_img = '';
                                            if (isset($order->product_image) && $order->product_image != "") {
                                                $product_img = base_url() . $this->product_image . 'thumb/253x90_' . $order->product_image;
                                            }
                                            ?>
                                            <div class="card br-5 hide-over mb-3 p-2" style="min-height: unset;">
                                                <a class="row" href="<?php echo base_url() . $this->path_to_default . 'product/order_detail/' . $order->orders_id; ?>">
                                                    <div class="col-md-5 m-auto" style="height: 100%;">
                                                        <img src="<?php echo $product_img; ?>" class="img-fluid card-img-top">
                                                    </div>
                                                    <div class="col-md-7">
                                                        <h6 class="mt-2 mb-0 text-black"><?php echo $order->order_no; ?></h6>
                                                        <div class="text-black mb-1"><?php echo $this->lang->line('text_price'); ?> : <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $order->product_price)); ?></div>    
        <!--                                                        <div class="text-black mb-1"><?php echo $this->lang->line('text_buyer_name'); ?> : <?php
                                                        $shipping_address = unserialize($order->shipping_address);
                                                        echo $shipping_address['full_name'];
                                                        ?></div>                                                                            
                                                        <div class="text-black mb-1"><?php echo $this->lang->line('text_shipping_address'); ?> : <?php
                                                        echo $shipping_address['address'];
                                                        ?></div>     -->
                                                        <div class="text-black mb-1"><?php echo $this->lang->line('text_order_date'); ?> : <?php echo date_format(date_create($order->created_date), "d F Y"); ?></div>           
                                                        <div class="text-black mb-1"><?php echo $this->lang->line('text_status'); ?> : <?php echo $order->order_status; ?></div>           
                                                    </div>
                                                </a>
                                            </div>        
                                            <?php
                                        }
                                    } else {
                                        echo "<div class='col-md-12 text-center text-black'><strong>" . $this->lang->line('text_order_empty') . "</strong></div>";
                                    }
                                    ?> 
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