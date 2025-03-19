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
                        <h1 class="h2"><?php echo $this->lang->line('text_main_banner_setting'); ?></h1>                        
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_main_banner_setting'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form method="POST" enctype="multipart/form-data" action="<?php echo base_url() . $this->path_to_view_admin ?>homeheader/" id="validate">                                           
                                            <div class="form-group">
                                                <label for="home_sec_title"><?php echo $this->lang->line('text_title'); ?><span class="required" aria-required="true"> * </span></label>
                                                <input type="text" id="home_sec_title" name="home_sec_title" value="<?php echo $this->system->home_sec_title; ?>" class="form-control">                                                    
                                                <?php echo form_error('home_sec_title', '<em style="color:red">', '</em>'); ?>
                                            </div> 
                                            <div class="form-group">
                                                <label for="home_sec_text"><?php echo $this->lang->line('text_sub_title'); ?><span class="required" aria-required="true"> * </span></label>
                                                <textarea id="home_sec_text" name="home_sec_text" rows="5" value="<?php echo $this->system->home_sec_text; ?>" class="form-control"><?php echo $this->system->home_sec_text; ?>  </textarea>                                                  
                                                <?php echo form_error('home_sec_text', '<em style="color:red">', '</em>'); ?>
                                            </div> 
                                            <div class="form-group">
                                                <label for="home_sec_btn"><?php echo $this->lang->line('text_button_text'); ?><span class="required" aria-required="true"> * </span></label>
                                                <input type="text" id="home_sec_btn" name="home_sec_btn" value="<?php echo $this->system->home_sec_btn; ?>" class="form-control">                                                    
                                                <?php echo form_error('home_sec_btn', '<em style="color:red">', '</em>'); ?>
                                            </div> 
                                            <div class="form-group">
                                                <label for="home_sec_bnr_image"><?php echo $this->lang->line('text_main_banner_image'); ?></label><br>
                                                <input id="home_sec_bnr_image" type="file" class="file-input d-block" name="home_sec_bnr_image" >
                                                <?php echo form_error('home_sec_bnr_image', '<em style="color:red">', '</em>'); ?>
                                                <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_1920x500'); ?></p>  
                                                <input type="hidden" id="file-input" name="old_home_sec_bnr_image"  value="<?php echo (isset($this->system->home_sec_bnr_image)) ? $this->system->home_sec_bnr_image : ''; ?>" class="form-control-file">                                                                                                      
                                                <?php if (isset($this->system->home_sec_bnr_image) && $this->system->home_sec_bnr_image != '' && file_exists($this->page_banner . $this->system->home_sec_bnr_image)) { ?>
                                                    <br>
                                                    <img src ="<?php echo base_url() . $this->page_banner . "thumb/100x100_" . $this->system->home_sec_bnr_image ?>" >
                                                <?php } ?>                                                  
                                            </div> 
                                            <div class="form-group">
                                                <label for="home_sec_side_image"><?php echo $this->lang->line('text_image'); ?></label><br>
                                                <input id="home_sec_side_image" type="file" class="file-input d-block" name="home_sec_side_image" >
                                                <?php echo form_error('home_sec_side_image', '<em style="color:red">', '</em>'); ?>
                                                <input type="hidden" id="file-input" name="old_home_sec_side_image"  value="<?php echo (isset($this->system->home_sec_side_image)) ? $this->system->home_sec_side_image : ''; ?>" class="form-control-file">                                                                                                      
                                                <?php if (isset($this->system->home_sec_side_image) && $this->system->home_sec_side_image != '' && file_exists($this->screenshot_image . $this->system->home_sec_side_image)) { ?>
                                                    <br>
                                                    <img src ="<?php echo base_url() . $this->screenshot_image . "thumb/100x100_" . $this->system->home_sec_side_image ?>" >
                                                <?php } ?>                                                      
                                            </div> 
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary "<?php
                                                if ($this->system->demo_user == 1) {
                                                    echo 'disabled';
                                                }
                                                ?>>
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>homeheader/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
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
            $(document).ready(function () {
                $.validator.addMethod('filesize', function (value, element, arg) {
                    if ((element.files[0].size <= arg)) {
                        return true;
                    } else {
                        return false;
                    }
                }, "Image size exceeds 2MB.");
                $("#validate").validate({
                    rules: {
                        'home_sec_title': {
                            required: true,
                        },
                        'home_sec_text': {
                            required: true,
                        },
                        'home_sec_btn': {
                            required: true,
                        },
                        'home_sec_bnr_image': {
                            accept: 'jpg|jpeg|png',
//                            filesize: 2000000,
                        },
                        'home_sec_side_image': {
                            accept: 'jpg|jpeg|png',
//                            filesize: 2000000,
                        }
                    },
                    messages: {
                        'home_sec_title': {
                            required: '<?php echo $this->lang->line('err_home_sec_title_req'); ?>',
                        },
                        'home_sec_text': {
                            required: '<?php echo $this->lang->line('err_home_sec_text_req'); ?>',
                        },
                        'home_sec_btn': {
                            required: '<?php echo $this->lang->line('err_home_sec_btn_req'); ?>',
                        },
                        'home_sec_bnr_image': {
                            accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                        },
                        'home_sec_side_image': {
                            accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
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