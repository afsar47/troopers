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
        <?php $this->load->view($this->path_to_view_admin . 'header'); ?>
    </head>
    <body>
        <?php $this->load->view($this->path_to_view_admin . 'header_body'); ?>
        <div class="d-flex" id="wrapper">
            <?php $this->load->view($this->path_to_view_admin . 'sidebar'); ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><?php echo $this->lang->line('text_order') . ' - #' . $order->order_no; ?></h1>
                    </div>
                    <?php if ($this->session->flashdata('notification')) { ?>
                        <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                            <span><?php echo $this->session->flashdata('notification'); ?></span>
                        </div>
                    <?php } ?>
                    <?php if ($this->session->flashdata('error')) { ?>
                        <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                            <span><?php echo $this->session->flashdata('error'); ?></span>
                        </div>
                    <?php } ?>
                    <div class="row" style="display: flex;">
                        <div class="col-md-8 mb-3">
                            <div class="card bg-light text-dark" style="height:100%;">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_shipping_detail'); ?></strong></div>
                                <div class="card-body">
                                    <?php $shipping_address = @unserialize($order->shipping_address); ?>
                                    <div>
                                        <b><?php echo $this->lang->line('text_user'); ?> : </b>
                                        <span><a href="<?php echo base_url() . $this->path_to_view_admin . 'members/member_detail/' . $order->member_id; ?>"> <?php echo $order->user_name; ?></a></span>
                                    </div>
                                    <div>
                                        <b><?php echo $this->lang->line('text_name'); ?> : </b>
                                        <span><?php echo $shipping_address['name']; ?></span>
                                    </div>
                                    <div>
                                        <b><?php echo $this->lang->line('text_address'); ?> : </b>
                                        <span><?php echo $shipping_address['address']; ?></span>
                                    </div>
                                    <?php if (isset($shipping_address['add_info']) && $shipping_address['add_info'] != '') { ?>
                                        <div>
                                            <b><?php echo $this->lang->line('text_additional_info'); ?> : </b>
                                            <span><?php echo $shipping_address['add_info']; ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_tracking_info'); ?></strong></div>
                                <div class="card-body">
                                    <form method="POST" class="track_form" name="track_form" id="track_form" action="<?php echo base_url() . $this->path_to_view_admin ?>order/order_detail">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <input id="orders_id" type="hidden" class="form-control" name="orders_id" value="<?php if (isset($orders_id)) echo $orders_id;elseif (isset($order->orders_id)) echo $order->orders_id ?>">
                                                <label for="courier_id"><?php echo $this->lang->line('text_courier'); ?></label>
                                                <select class="form-control" name="courier_id">
                                                    <option value="">Select..</option>
                                                    <?php
                                                    foreach ($courier_data as $courier) {
                                                        ?>
                                                        <option value="<?php echo $courier->courier_id; ?>" <?php
                                                        if (isset($courier_id) && $courier_id == $courier->courier_id)
                                                            echo 'selected';
                                                        elseif (isset($order->courier_id) && $order->courier_id == $courier->courier_id)
                                                            echo 'selected';
                                                        else
                                                            echo '';
                                                        ?>><?php echo $courier->courier_name; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                </select>
                                                <?php echo form_error('courier_id', '<em style="color:red">', '</em>'); ?>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="tracking_id"><?php echo $this->lang->line('text_tracking_id'); ?></label>
                                                <input id="tracking_id" type="text" class="form-control" name="tracking_id" value="<?php if (isset($tracking_id)) echo $tracking_id;elseif (isset($order->tracking_id)) echo $order->tracking_id ?>">
                                                <?php echo form_error('tracking_id', '<em style="color:red">', '</em>'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" ><?php echo $this->lang->line('text_btn_submit'); ?></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_product'); ?></strong></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img src="<?php echo base_url() . $this->product_image . "thumb/100x100_" . $order->product_image; ?>" class="img-responsive">
                                        </div>
                                        <div class="col-md-8">
                                            <div>
                                                <b><?php echo $this->lang->line('text_product_name'); ?> : </b>
                                                <span><?php echo $order->product_name; ?></span>
                                            </div>
                                            <div>
                                                <b><?php echo $this->lang->line('text_product_price'); ?> : </b>
                                                <span><?php echo $this->functions->getPoint() . ' ' . $order->product_price; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>
            </div>
            <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>
            <script>
                $("#track_form").validate({
                    rules: {
                        courier_id: {
                            required: true,
                        },
                        tracking_id: {
                            required: true,
                        },
                    },
                    messages: {
                        courier_id: {
                            required: '<?php echo $this->lang->line('err_courier_id_req'); ?>',
                        },
                        tracking_id: {
                            required: '<?php echo $this->lang->line('err_tracking_id_req'); ?>',
                        },
                    },
                    errorPlacement: function (error, element)
                    {
                        if (element.is(":radio"))
                        {
                            element.parent().parent().append(error);
                        } else
                        {
                            error.insertAfter(element);
                        }
                    },
                });
            </script>
    </body>
</html>