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
                        <h1 class="h2"><?php echo $this->lang->line('text_image'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>image/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_image'); ?></strong></div>                                
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  enctype="multipart/form-data"  id="validate" novalidate="" method="POST" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>image/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>image/edit<?php } ?>">                                            
                                            <div class="row">
                                                <input  type="hidden" class="form-control" name="image_id" value="<?php if (isset($image_id)) echo $image_id;elseif (isset($image_detail['image_id'])) echo $image_detail['image_id'] ?>">                                                   
                                                <div class="form-group col-md-6">
                                                    <label for="image_title"><?php echo $this->lang->line('text_image_title'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="image_title" value="<?php if (isset($image_title)) echo $image_title;elseif (isset($image_detail['image_title'])) echo $image_detail['image_title'] ?>" >
                                                    <?php echo form_error('image_title', '<em style="color:red">', '</em>'); ?>
                                                </div>      
                                                <div class="form-group col-md-6">
                                                    <label for="image_name"><?php echo $this->lang->line('text_image'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                    <input id="image_name" type="file" class="file-input d-block" name="image_name" >
                                                    <?php echo form_error('image_name', '<em style="color:red">', '</em>'); ?>
                                                    <input type="hidden" id="file-input" name="old_image_name"  value="<?php echo (isset($image_detail['image_name'])) ? $image_detail['image_name'] : ''; ?>" class="form-control-file">                                                                                                      
                                                    <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_1000x500'); ?></p>    
                                                    <?php if (isset($image_detail['image_name']) && $image_detail['image_name'] != '' && file_exists($this->select_image . $image_detail['image_name'])) { ?>
                                                        <br>
                                                        <img src ="<?php echo base_url() . $this->select_image . "thumb/100x100_" . $image_detail['image_name'] ?>" >
                                                    <?php } ?>
                                                </div>                                                   
                                            </div>
                                            <div class="form-group text-center">
                                                <button class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit"  <?php
                                                if ($this->system->demo_user == 1 && isset($image_detail['image_id']) && $image_detail['image_id'] <= 2) {
                                                    echo 'disabled';
                                                }
                                                ?>>Submit</button>
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>image/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>   
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
                    image_title: {
                        required: true,
                    },
                    image_name: {
<?php if ($Action == $this->lang->line('text_action_add')) { ?>
                            required: true,
<?php } ?>
                        accept: "jpg|png|jpeg",
                        filesize : 2000000,
                    },

                },
                messages: {
                    image_title: {
                        required: '<?php echo $this->lang->line('err_image_title_req'); ?>'
                    },
                    image_name: {
                        required: '<?php echo $this->lang->line('err_image_req'); ?>',
                        accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
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