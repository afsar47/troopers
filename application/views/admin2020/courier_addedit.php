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
                        <h1 class="h2"><?php echo $this->lang->line('text_courier'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>courier/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_courier'); ?></strong></div>                                
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  enctype="multipart/form-data"  id="validate" novalidate="" method="POST" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>courier/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>courier/edit<?php } ?>">                                            
                                            <div class="row">
                                                <input  type="hidden" class="form-control" name="courier_id" value="<?php if (isset($courier_id)) echo $courier_id;elseif (isset($courier_detail['courier_id'])) echo $courier_detail['courier_id'] ?>">                                                   
                                                <div class="form-group col-md-6">
                                                    <label for="courier_name"><?php echo $this->lang->line('text_courier_name'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="courier_name" value="<?php if (isset($courier_name)) echo $courier_name;elseif (isset($courier_detail['courier_name'])) echo $courier_detail['courier_name'] ?>" >
                                                    <?php echo form_error('courier_name', '<em style="color:red">', '</em>'); ?>
                                                </div>      
                                                <div class="form-group col-md-6">
                                                    <label for="courier_link"><?php echo $this->lang->line('text_courier_link'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="courier_link" value="<?php if (isset($courier_link)) echo $courier_link;elseif (isset($courier_detail['courier_link'])) echo $courier_detail['courier_link'] ?>" >
                                                    <?php echo form_error('courier_link', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group text-center">
                                                <button class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit"<?php
                                                if ($this->system->demo_user == 1 && isset($courier_detail['courier_id']) && $courier_detail['courier_id'] <= 2) {
                                                    echo 'disabled';
                                                }
                                                ?>><?php echo $this->lang->line('text_btn_submit'); ?></button>
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>courier/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>   
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
//            CKEDITOR.editorConfig = function (config) {
//                config.language = 'es';
//                config.uiColor = '#F7B42C';
//                config.height = 300;
//                config.toolbarCanCollapse = true;
//
//            };
//            CKEDITOR.replace('editor1');

            $.validator.addMethod('filesize', function (value, element, arg) {
                if ((element.files[0].size <= arg)) {
                    return true;
                } else {
                    return false;
                }
            }, '<?php echo $this->lang->line('err_image_size'); ?>');
            $("#validate").validate({
                rules: {
                    courier_name: {
                        required: true,
                    },
                    courier_link: {
                        required: true,
                        url: true,
                    },
                },
                messages: {
                    courier_name: {
                        required: '<?php echo $this->lang->line('err_courier_name_req'); ?>',
                    },
                    courier_link: {
                        required: '<?php echo $this->lang->line('err_courier_link_req'); ?>',
                        url: '<?php echo $this->lang->line('err_courier_link_valid'); ?>',
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