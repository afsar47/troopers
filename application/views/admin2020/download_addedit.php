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
                        <h1 class="h2"><?php echo $this->lang->line('text_howtoinstall'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>download/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_howtoinstall'); ?></strong></div>
                                <div class="card-body">
                                    <form method="POST" id="validate" enctype="multipart/form-data" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>download/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>download/edit<?php } ?>">                                                                                                                                 
                                        <div class="row">    
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="download_image"><?php echo $this->lang->line('text_howtoinstall'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                    <input id="download_image" type="file" class="file-input d-block" name="download_image">
                                                    <?php echo form_error('download_image', '<em style="color:red">', '</em>'); ?>
                                                    <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_270x500'); ?></p> 
                                                    <?php if (isset($download_detail['download_image']) && file_exists($this->download_image . $download_detail['download_image'])) { ?>
                                                        <br>
                                                        <img src ="<?php echo base_url() . $this->download_image . "thumb/100x100_" . $download_detail['download_image'] ?>" >
                                                    <?php } ?>                                                   
                                                    <input type="hidden" id="file-input" name="old_download_image"  value="<?php echo (isset($download_detail['download_image'])) ? $download_detail['download_image'] : ''; ?>" class="form-control-file">                                                   
                                                    <input type="hidden" id="file-input" name="download_id"  value="<?php echo (isset($download_detail['download_id'])) ? $download_detail['download_id'] : ''; ?>" class="form-control-file">                                                   
                                                </div>
                                            </div>                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="dp_order"><?php echo $this->lang->line('text_display_order'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                    <input id="dp_order" type="text" class="form-control" name="dp_order" value="<?php if (isset($dorder)) echo $dorder;elseif (isset($download_detail['dp_order'])) echo $download_detail['dp_order'] ?>">
                                                    <?php echo form_error('dp_order', '<em style="color:red">', '</em>'); ?>       
                                                </div>
                                            </div>
                                        </div>                                                                                                                                
                                        <div class="form-group text-center">
                                            <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary " <?php
                                            if ($this->system->demo_user == 1 && isset($download_detail['download_id']) && $download_detail['download_id'] <= 7) {
                                                echo 'disabled';
                                            }
                                            ?>> 
                                            <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>download/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                   
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
            'download_image': {
<?php if ($Action == $this->lang->line('text_action_add')) { ?>
                required: true,
<?php } ?>
            accept: "jpg|png|jpeg",
                    filesize: 2000000,
            },
                    'dp_order' : {
                    required: true,
                            number: true
                    }
            },
                    messages: {
                    'download_image': {
                    required: '<?php echo $this->lang->line('err_image_req'); ?>',
                            accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                    },
                            'dp_order' : {
                            required: '<?php echo $this->lang->line('err_dp_order_req'); ?>',
                                    number: '<?php echo $this->lang->line('err_number'); ?>',
                            }
                    },
                    errorPlacement: function (error, element)
                    {
                    if (element.is(":radio"))
                    {
                    error.insertAfter(element.parent());
                    } else
                    {
                    error.insertAfter(element);
                    }
                    },
            });
            });
        </script>
    </body>
</html>