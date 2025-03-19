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
                        <h1 class="h2"><?php echo $this->lang->line('text_product'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>product/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_product'); ?></strong></div>                                
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  id="validate"  enctype="multipart/form-data" novalidate="" method="POST" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>product/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>product/edit<?php } ?>">     
                                            <div class="row">
                                                <input  type="hidden" class="form-control" name="product_id" value="<?php if (isset($product_id)) echo $product_id;elseif (isset($product_detail['product_id'])) echo $product_detail['product_id'] ?>">                                                   
                                                <div class="form-group col-md-6">
                                                    <label for="product_name"><?php echo $this->lang->line('text_product_name'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="product_name" value="<?php if (isset($product_name)) echo $product_name;elseif (isset($product_detail['product_name'])) echo $product_detail['product_name'] ?>" >
                                                    <?php echo form_error('product_name', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                      
                                                <div class="form-group col-md-6">
                                                    <label for="product_image"><?php echo $this->lang->line('text_image'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                    <input id="product_image" type="file" class="file-input d-block" name="product_image" >
                                                    <?php echo form_error('product_image', '<em style="color:red">', '</em>'); ?>
                                                    <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_1000x500'); ?></p> 
                                                    <input type="hidden" id="file-input" name="old_product_image"  value="<?php echo (isset($product_detail['product_image'])) ? $product_detail['product_image'] : ''; ?>" class="form-control-file">                                                                                                      
                                                    <?php if (isset($product_detail['product_image']) && $product_detail['product_image'] != '' && file_exists($this->product_image . $product_detail['product_image'])) { ?>
                                                        <br>
                                                        <img src ="<?php echo base_url() . $this->product_image . "thumb/100x100_" . $product_detail['product_image'] ?>" >
                                                    <?php } ?>
                                                </div>  
                                                <div class="form-group col-md-6">
                                                    <label for="product_actual_price"><?php echo $this->lang->line('text_product_actual_price'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="product_actual_price" value="<?php if (isset($product_actual_price)) echo $product_actual_price;elseif (isset($product_detail['product_actual_price'])) echo $product_detail['product_actual_price'] ?>" >
                                                    <?php echo form_error('product_actual_price', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                                <div class="form-group col-md-6">
                                                    <label for="product_selling_price"><?php echo $this->lang->line('text_product_selling_price'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="product_selling_price" value="<?php if (isset($product_selling_price)) echo $product_selling_price;elseif (isset($product_detail['product_selling_price'])) echo $product_detail['product_selling_price'] ?>" >
                                                    <?php echo form_error('product_selling_price', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                                <div class="form-group col-md-12">
                                                    <label for="product_short_description"><?php echo $this->lang->line('text_product_short_desc'); ?></label>
                                                    <textarea type="text"  class="form-control" name="product_short_description" ><?php
                                                        if (isset($product_short_description))
                                                            echo $product_short_description;elseif (isset($product_detail['product_short_description']))
                                                            echo $product_detail['product_short_description'];
                                                        ?></textarea>
                                                    <?php echo form_error('product_short_description', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label for="product_description"><?php echo $this->lang->line('text_product_desc'); ?></label>
                                                    <textarea type="text"  class="form-control ckeditor" id="editor1" name="product_description" ><?php
                                                        if (isset($product_description))
                                                            echo $product_description;elseif (isset($product_detail['product_description']))
                                                            echo $product_detail['product_description'];
                                                        ?></textarea>
                                                    <?php echo form_error('product_description', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                            </div>
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary " <?php
                                                if ($this->system->demo_user == 1 && isset($product_detail['product_id']) && $product_detail['product_id'] <= 2) {
                                                    echo 'disabled';
                                                }
                                                ?>>    
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>product" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                 
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>
        <script>
            $.validator.addMethod('filesize', function (value, element, arg) {
            if ((element.files[0].size <= arg)) {
            return true;
            } else {
            return false;
            }
            }, '<?php echo $this->lang->line('err_image_size'); ?>');
            $("#validate").validate({
            rules: {
            product_name: {
            required: true,
            },
                    product_image: {
<?php if ($Action == $this->lang->line('text_action_add')) { ?>
                        required: true,
<?php } ?>
                    accept: "jpg|png|jpeg",
//                        filesize : 2000000,
                    },
                    product_actual_price: {
                    required: true,
                            number:true,
                    },
                    product_selling_price: {
                    required: true,
                            number:true,
                    },
                    product_short_description: {
                    required: true,
                    },
                    product_description: {
                    required: function (textarea) {
                    CKEDITOR.instances[textarea.id].updateElement();
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                    return editorcontent.length === 0;
                    }
                    }
            },
                    messages: {
                    product_name: {
                    required: '<?php echo $this->lang->line('err_product_name_req'); ?>',
                    },
                            product_image: {
                            required: '<?php echo $this->lang->line('err_image_req'); ?>',
                                    accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                            },
                            product_actual_price: {
                            required: '<?php echo $this->lang->line('err_actual_price_req'); ?>',
                                    number: '<?php echo $this->lang->line('err_actual_price_number'); ?>',
                            },
                            product_selling_price: {
                            required: '<?php echo $this->lang->line('err_selling_price_req'); ?>',
                                    number:'<?php echo $this->lang->line('err_selling_price_number'); ?>',
                            },
                            product_short_description: {
                            required: '<?php echo $this->lang->line('err_short_desc_req'); ?>',
                            },
                            product_description: {
                            required: '<?php echo $this->lang->line('err_desc_req'); ?>',
                            },
                    },
                    errorPlacement: function (error, element)
                    {
                    if (element.is(":radio"))
                    {
                    element.parent().append(error);
                    } else if (element.is("textarea"))
                    {
                    element.parent().append(error);
                    } else
                    {
                    error.insertAfter(element);
                    }
                    },
            });
        </script>
    </body>
</html>