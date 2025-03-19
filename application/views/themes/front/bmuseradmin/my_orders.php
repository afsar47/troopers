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
                        <div class="btn-toolbar mb-2 mb-md-0">                          
                        </div>
                    </div>                
                    <div class="row">
                        <?php
                        if (!empty($order_data)) {
                            foreach ($order_data as $order) {
                                $product_img = '';
                                if (isset($order->product_image) && $order->product_image != "") {
                                    $product_img = base_url() . $this->product_image . 'thumb/253x90_' . $order->product_image;
                                }
                                ?>
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <div class="card br-5 hide-over mb-3 p-2" style="min-height: unset;">
                                        <div class="row" style="">
                                            <div class="col-md-5 m-auto" style="height: 100%;">
                                                <a href="<?php echo base_url() . $this->path_to_default . 'product/order_detail/' . $order->orders_id; ?>" >
                                                    <img src="<?php echo $product_img; ?>" class="img-fluid card-img-top">
                                                </a>
                                            </div>
                                            <div class="col-md-7">
                                                <h6 class="card-title mt-2 mb-0 "><?php echo $order->order_no; ?></h6>
                                                <div class="mb-1 text-secondary"><?php echo $this->lang->line('text_price'); ?> : <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $order->product_price)); ?></div>    
        <!--                                                <div class="mb-1 text-secondary"><?php echo $this->lang->line('text_buyer_name'); ?> : <?php
                                                $shipping_address = unserialize($order->shipping_address);
                                                echo $shipping_address['full_name'];
                                                ?></div>    
                                                <div class="mb-1 text-secondary"><?php echo $this->lang->line('text_shipping_address'); ?> : <?php
                                                echo $shipping_address['address'];
                                                ?></div>             -->
                                                <div class="mb-1 text-secondary"><?php echo $this->lang->line('text_order_date'); ?> : <?php echo date_format(date_create($order->created_date), "d F Y"); ?></div>                                              

                                                <div class="mb-1 text-secondary"><?php echo $this->lang->line('text_status'); ?> : <?php echo $order->order_status; ?></div>                                              
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <?php
                            }
                        } else {
                            echo "<div class='col-md-12 text-center'><strong>" . $this->lang->line('text_order_empty') . "</strong></div>";
                        }
                        ?> 
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
        <script>

        </script>
    </body>
</html>