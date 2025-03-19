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
                        <h1 class="h2"><?php echo $this->lang->line('text_howtoplay'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>how_to_play/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_howtoplay'); ?></strong></div>
                                <div class="card-body">
                                    <form method="POST" id="validate" enctype="multipart/form-data" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>how_to_play/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>how_to_play/edit<?php } ?>">                                                                                                                                 
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="htp_content_title"><?php echo $this->lang->line('text_edit_htp_content_title'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                <input id="htp_content_title" type="text" class="form-control" name="htp_content_title" value="<?php if (isset($htp_content_title)) echo $htp_content_title;elseif (isset($htp_content_detail['htp_content_title'])) echo $htp_content_detail['htp_content_title'] ?>"> 
                                                <?php echo form_error('htp_content_title', '<em style="color:red">', '</em>'); ?>                                                
                                                <input type="hidden" name="htp_content_id"  value="<?php echo (isset($htp_content_detail['htp_content_id'])) ? $htp_content_detail['htp_content_id'] : ''; ?>" class="form-control-file">                                                   
                                            </div> 
                                            <div class="form-group col-md-6">
                                                <label for="htp_content_image"><?php echo $this->lang->line('text_edit_htp_content_image'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                <input id="htp_content_image" type="file" class="file-input d-block " name="htp_content_image">
                                                <?php echo form_error('htp_content_image', '<em style="color:red">', '</em>'); ?>
                                                <input type="hidden" id="file-input" name="old_htp_content_image"  value="<?php echo (isset($htp_content_detail['htp_content_image'])) ? $htp_content_detail['htp_content_image'] : ''; ?>" class="form-control-file">                                                                                                      
                                                <?php if (isset($htp_content_detail['htp_content_image']) && $htp_content_detail['htp_content_image'] != '' && file_exists($this->screenshot_image . $htp_content_detail['htp_content_image'])) { ?>
                                                    <br>
                                                    <img src ="<?php echo base_url() . $this->screenshot_image . "thumb/100x100_" . $htp_content_detail['htp_content_image'] ?>" >
                                                <?php } ?>                                               
                                            </div>   
                                            <div class="form-group col-12">
                                                <label for="htp_content_text"><?php echo $this->lang->line('text_edit_htp_content_text'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                <textarea id="htp_content_text" type="text" class="form-control ckeditor" id="editor1" name="htp_content_text" value="<?php if (isset($htp_content_text)) echo $htp_content_text;elseif (isset($htp_content_detail['htp_content_text'])) echo $htp_content_detail['htp_content_text'] ?>"><?php if (isset($htp_content_text)) echo $htp_content_text;elseif (isset($htp_content_detail['htp_content_text'])) echo $htp_content_detail['htp_content_text'] ?></textarea>                                   
                                                <?php echo form_error('htp_content_text', '<em style="color:red">', '</em>'); ?>                                                
                                            </div>
                                        </div>  
                                        <div class="row">

                                            <div class="form-group col-md-6">
                                                <label for="htp_order"><?php echo $this->lang->line('text_edit_htp_content_order'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                <input  id="htp_order" type="text" class="form-control" name="htp_order" value="<?php if (isset($htp_order)) echo $htp_order;elseif (isset($htp_content_detail['htp_order'])) echo $htp_content_detail['htp_order'] ?>">                                       
                                                <?php echo form_error('htp_order', '<em style="color:red">', '</em>'); ?>                                                
                                            </div>                                       
                                        </div>
                                        <div class="form-group text-center">
                                            <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary " <?php if($this->system->demo_user == 1 && isset($htp_content_detail['htp_content_id']) && $htp_content_detail['htp_content_id'] <= 3){ echo 'disabled';}?>>
                                            <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>how_to_play/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                 
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
            'htp_content_title': {
            required: true,
            },
                    'htp_content_text': {
                    required: function (textarea) {
                    CKEDITOR.instances[textarea.id].updateElement();
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                    return editorcontent.length === 0;
                    }
                    },
                    'htp_content_image':{
<?php if ($Action == $this->lang->line('text_action_add')) { ?>
                        required:true,
<?php } ?>
                    accept: 'jpg|jpeg|png',
                            filesize: 2000000,
                    },
                    'htp_order':{
                    required:true,
                            number:true
                    }
            },
                    messages: {
                    'htp_content_title': {
                    required: '<?php echo $this->lang->line('err_htp_content_title_req'); ?>',
                    },
                            'htp_content_text': {
                            required: '<?php echo $this->lang->line('err_htp_content_text_req'); ?>',
                            },
                            'htp_content_image':{
                            required:'<?php echo $this->lang->line('err_image_req'); ?>',
                                    accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                            },
                            'htp_order':{
                            required:'<?php echo $this->lang->line('err_dp_order_req'); ?>',
                                    number:'<?php echo $this->lang->line('err_number'); ?>',
                            }
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
            });
        </script>
    </body>
</html>