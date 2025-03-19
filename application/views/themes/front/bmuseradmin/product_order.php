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
                    <div class="row d-flex mb-3">
                        <div class="col-3 m-auto text-lightgreen" style="height: 100%;">
                            <i class="fa fa-google-wallet" style="font-size: 50px;"></i>
                        </div>
                        <div class="col-9 text-right">
                            <span class="d-block"><?php echo $this->lang->line('text_your_curr_balance'); ?> : <strong> <i style=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $member['join_money'] + $member['wallet_balance']; ?></strong></span>                                                
                            <span class="d-block"><?php echo $this->lang->line('text_tot_payable_amt'); ?> : <strong> <i style=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $product['product_selling_price']; ?> </strong></span>      
                        </div>
                    </div>
                    <form action="<?php echo base_url() . $this->path_to_default . 'product/order'; ?>" class="profile-form" method="post" name="order-form" id="order-form">
                        <div class="form-group row">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <div class="col-12">
                                <label for="full_name"><?php echo $this->lang->line('text_full_name'); ?> </label>
                                <input type="text" id="full_name" name="full_name" class="form-control" >
                                <?php echo form_error('full_name', '<em style="color:red">', '</em>'); ?>
                            </div>
                            <div class="col-12">
                                <label for="address"><?php echo $this->lang->line('text_address'); ?> </label>
                                <textarea id="address" name="address" class="form-control" ></textarea>
                                <?php echo form_error('address', '<em style="color:red">', '</em>'); ?>
                            </div>
                            <div class="col-12">
                                <label for="address"><?php echo $this->lang->line('text_additional_info'); ?> </label>
                                <textarea id="add_info" name="add_info" class="form-control" ></textarea>
                                <?php echo form_error('address', '<em style="color:red">', '</em>'); ?>
                            </div>
                        </div>
                        <button type="submit" id="buy_now" class="btn btn-primary text-white" value="buy_now" name="submit" > <?php echo $this->lang->line('text_buy_now'); ?> </button>                              
                    </form>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
        <script>
            $(document).ready(function () {
                $("#order-form").validate({
                    rules: {
                        full_name: {
                            required: true,
                        },
                        address: {
                            required: true,
                        },
                    },
                    messages: {
                        full_name: {
                            required: "<?php echo $this->lang->line('err_full_name_req'); ?>",
                        },
                        address: {
                            required: "<?php echo $this->lang->line('err_address_req'); ?>",
                        },
                    },
                });
            });
        </script>
    </body>
</html>