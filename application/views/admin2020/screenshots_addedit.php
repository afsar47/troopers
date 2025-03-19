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
                        <h1 class="h2"><?php echo $this->lang->line('text_screenshots'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>screenshots/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_screenshots'); ?></strong></div>
                                <div class="card-body">
                                    <form method="POST" id="validate" enctype="multipart/form-data" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>screenshots/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>screenshots/edit<?php } ?>">                                                                                                                                 
                                        <div class="row">
                                            <div class="form-group col-md-offset-1 col-md-6">
                                                <label for="screenshot"><?php echo $this->lang->line('text_add_screenshot'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                <input id="screenshot" type="file" class="file-input d-block" name="screenshot">
                                                <?php echo form_error('screenshot', '<em style="color:red">', '</em>'); ?>
                                                <p><b>Note : </b> Upload 270x500 size of image for better view.</p>    
                                                <?php if (isset($screenshot_detail['screenshot']) && file_exists($this->screenshot_image . $screenshot_detail['screenshot'])) { ?>
                                                    <br>
                                                    <img src ="<?php echo base_url() . $this->screenshot_image . "thumb/100x100_" . $screenshot_detail['screenshot'] ?>" >
                                                <?php } ?>
                                                <input type="hidden" id="file-input" name="old_screenshot"  value="<?php echo (isset($screenshot_detail['screenshot'])) ? $screenshot_detail['screenshot'] : ''; ?>" class="form-control-file">                                                   
                                                <input type="hidden" id="file-input" name="screenshots_id"  value="<?php echo (isset($screenshot_detail['screenshots_id'])) ? $screenshot_detail['screenshots_id'] : ''; ?>" class="form-control-file">                                                   
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="dp_order"><?php echo $this->lang->line('text_display_order'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                    <input id="dp_order" type="text" class="form-control" name="dp_order" value="<?php if (isset($dp_order)) echo $dp_order;elseif (isset($screenshot_detail['dp_order'])) echo $screenshot_detail['dp_order'] ?>">
                                                    <?php echo form_error('dp_order', '<em style="color:red">', '</em>'); ?>       
                                                </div>
                                            </div>
                                        </div>                                             
                                        <div class="form-group text-center">
                                            <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary " <?php if ($this->system->demo_user == 1 && isset($screenshot_detail['screenshots_id']) && $screenshot_detail['screenshots_id'] <= 8) {
                                                        echo 'disabled';
                                                    } ?>>  
                                            <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>screenshots/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                  
                                        </div>
                                    </form>
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
            $(document).ready(function () {
            $.validator.addMethod('filesize', function (value, element, arg) {
            if ((element.files[0].size <= arg)) {
            return true;
            } else {
            return false;
            }
            }, '<?php echo $this->lang->line('err_image_size'); ?>');
            $("#validate").validate({
            rules: {
            'dp_order': {
            required: true,
                    number: true
            },
                    'screenshot': {
<?php if ($Action == $this->lang->line('text_action_add')) { ?>
                        required: true,
<?php } ?>
                    accept: "jpg|png|jpeg",
                            filesize: 2000000,
                    },
            },
                    messages: {
                    'dp_order': {
                    required: '<?php echo $this->lang->line('err_dp_order_req'); ?>', 
                            number: '<?php echo $this->lang->line('err_number'); ?>',
                    },
                            'screenshot': {
                            required: '<?php echo $this->lang->line('err_image_req'); ?>',
                                    accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                            },
                    },
                    errorPlacement: function (error, element)
                    {
                    error.insertAfter(element);
                    },
            });
            });
        </script>
    </body>
</html>