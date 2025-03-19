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
                        <h1 class="h2"><?php echo $this->lang->line('text_announcement'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>announcement/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_announcement'); ?></strong></div>                                
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  id="validate"  enctype="multipart/form-data" novalidate="" method="POST" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>announcement/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>announcement/edit<?php } ?>">     
                                            <div class="row">
                                                <input  type="hidden" class="form-control" name="announcement_id" value="<?php if (isset($announcement_id)) echo $announcement_id;elseif (isset($announcement_detail['announcement_id'])) echo $announcement_detail['announcement_id'] ?>">                                                   

                                                <div class="form-group col-12">
                                                    <label for="announcement_desc"><?php echo $this->lang->line('text_announcement'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <textarea id="announcement_desc" type="text" class="form-control" name="announcement_desc"><?php if (isset($announcement_detail)) echo $announcement_detail['announcement_desc']; ?></textarea>                                                   
                                                    <?php echo form_error('announcement_desc', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary " <?php
                                                if ($this->system->demo_user == 1 && isset($announcement_detail['announcement_id']) && $announcement_detail['announcement_id'] <= 2) {
                                                    echo 'disabled';
                                                }
                                                ?>>    
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>announcement" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                 
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
            $("#validate").validate({
                rules: {
                    announcement_desc: {
                        required: true,
                    },
                },
                messages: {
                    announcement_desc: {
                        required: '<?php echo $this->lang->line('err_announcement_req'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
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