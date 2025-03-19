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
                        <h1 class="h2"><?php echo $this->lang->line('text_app_tutorial'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>youtube/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_app_tutorial'); ?></strong></div>                                   
                                <div class="card-body">
                                    <form method="POST" id="validate" enctype="multipart/form-data" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>youtube/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>youtube/edit<?php } ?>">                                                                                                                                 
                                        <div class="row">
                                            <input id="member_id" type="hidden" class="form-control" name="youtube_link_id" value="<?php if (isset($youtube_link_id)) echo $youtube_link_id;elseif (isset($youtube_detail['youtube_link_id'])) echo $youtube_detail['youtube_link_id'] ?>">
                                            <div class="form-group col-md-6">
                                                <label for="youtube_link_title"><?php echo $this->lang->line('text_app_tutorial_title'); ?><span class="required" aria-required="true"> * </span></label>
                                                <input type="text" id="youtube_link_title" class="form-control" name="youtube_link_title" value="<?php if (isset($youtube_link_title)) echo $youtube_link_title;elseif (isset($youtube_detail['youtube_link_title'])) echo $youtube_detail['youtube_link_title'] ?>">
                                                <?php echo form_error('youtube_link_title', '<em style="color:red">', '</em>'); ?>
                                            </div>       
                                            <div class="form-group col-md-6">
                                                <label for="youtube_link"><?php echo $this->lang->line('text_app_tutorial_link'); ?><span class="required" aria-required="true"> * </span></label>
                                                <input id="youtube_link" type="text" class="form-control" name="youtube_link" value="<?php if (isset($youtube_link)) echo $youtube_link;elseif (isset($youtube_detail['youtube_link'])) echo $youtube_detail['youtube_link'] ?>">
                                                <?php echo form_error('youtube_link', '<em style="color:red">', '</em>'); ?>
                                            </div>                                                
                                        </div>   

                                        <div class="form-group text-center">
                                            <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary "> 
                                            <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>youtube/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
                                        </div>
                                    </form>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>

            </div>
            <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>
            <script>
                $("#validate").validate({
                    rules: {
                        'youtube_link': {
                            required: true,
                            url: true,
                        },
                        'youtube_link_title': {
                            required: true,
                        }
                    },
                    messages: {
                        'youtube_link': {
                            required: '<?php echo $this->lang->line('err_youtube_link_req'); ?>',
                            url: '<?php echo $this->lang->line('err_url_valid'); ?>',
                        },
                        'youtube_link_title': {
                            required: '<?php echo $this->lang->line('err_youtube_link_title_req'); ?>',
                        }
                    },
                    errorPlacement: function (error, element)
                    {
                        if (element.is(":file"))
                        {
                            error.insertAfter(element.parent());
                        } else
                        {
                            error.insertAfter(element);
                        }
                    },
                });
            </script>
    </body>
</html>