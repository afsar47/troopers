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
                        <h1 class="h2"><?php echo $this->lang->line('text_features_tab'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>features/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_features_tab'); ?></strong></div>
                                <div class="card-body">
                                    <form method="POST" id="validate" enctype="multipart/form-data" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>features/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>features/edit<?php } ?>">                                                                                                                                 
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="f_tab_name"><?php echo $this->lang->line('text_feature_tab_name'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                <input id="f_tab_name" type="text" class="form-control" name="f_tab_name" value="<?php if (isset($f_tab_name)) echo $f_tab_name;elseif (isset($features_detail['f_tab_name'])) echo $features_detail['f_tab_name'] ?>">
                                                <?php echo form_error('f_tab_name', '<em style="color:red">', '</em>'); ?>                                                
                                                <input type="hidden" name="f_id"  value="<?php echo (isset($features_detail['f_id'])) ? $features_detail['f_id'] : ''; ?>" class="form-control-file">                                                   
                                            </div> 
                                            <div class="form-group col-md-6">
                                                <label for="f_tab_title"><?php echo $this->lang->line('text_feature_tab_title'); ?></label><br>
                                                <input id="f_tab_title" type="text" class="form-control" name="f_tab_title" value="<?php if (isset($f_tab_title)) echo $f_tab_title;elseif (isset($features_detail['f_tab_title'])) echo $features_detail['f_tab_title'] ?>">
                                                <?php echo form_error('f_tab_title', '<em style="color:red">', '</em>'); ?>                                                
                                            </div> 
                                        </div>  
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="f_tab_text"><?php echo $this->lang->line('text_feature_tab_text'); ?></label><br>
                                                <textarea id="f_tab_text" type="text" class="form-control" name="f_tab_text" value="<?php if (isset($f_tab_text)) echo $f_tab_text;elseif (isset($features_detail['f_tab_text'])) echo $features_detail['f_tab_text'] ?>"><?php if (isset($f_tab_text)) echo $f_tab_text;elseif (isset($features_detail['f_tab_text'])) echo $features_detail['f_tab_text'] ?></textarea>
                                                <?php echo form_error('f_tab_text', '<em style="color:red">', '</em>'); ?>                                                
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="f_tab_img_position"><?php echo $this->lang->line('text_feature_tab_image_position'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                <select id="f_tab_img_position"  name="f_tab_img_position" class="form-control">
                                                    <option value=""> <?php echo $this->lang->line('text_select'); ?> </option>
                                                    <option value="left" <?php echo (isset($features_detail['f_tab_img_position']) && $features_detail['f_tab_img_position'] == 'left') ? 'selected' : ''; ?>><?php echo $this->lang->line('text_left'); ?></option>
                                                    <option value="center" <?php echo (isset($features_detail['f_tab_img_position']) && $features_detail['f_tab_img_position'] == 'center') ? 'selected' : ''; ?>><?php echo $this->lang->line('text_center'); ?></option>
                                                    <option value="right" <?php echo (isset($features_detail['f_tab_img_position']) && $features_detail['f_tab_img_position'] == 'right') ? 'selected' : ''; ?>><?php echo $this->lang->line('text_right'); ?></option>
                                                </select>
                                                <?php echo form_error('f_tab_img_position', '<em style="color:red">', '</em>'); ?>                                                
                                            </div>                                            
                                        </div>  
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="f_tab_img"><?php echo $this->lang->line('text_feature_tab_image'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                <input id="f_tab_img" type="file" class="file-input d-block" name="f_tab_img" >
                                                <?php echo form_error('f_tab_img', '<em style="color:red">', '</em>'); ?>
                                                <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_320x620'); ?></p>
                                                <input type="hidden" id="file-input" name="old_f_tab_img"  value="<?php echo (isset($features_detail['f_tab_image'])) ? $features_detail['f_tab_image'] : ''; ?>" class="form-control-file">                                                                                                      
                                                <?php if (isset($features_detail['f_tab_image']) && $features_detail['f_tab_image'] != '' && file_exists($this->screenshot_image . $features_detail['f_tab_image'])) { ?>
                                                    <br>
                                                    <img src ="<?php echo base_url() . $this->screenshot_image . "thumb/100x100_" . $features_detail['f_tab_image'] ?>" >
                                                <?php } ?>                                               
                                            </div>   
                                            <div class="form-group col-md-6">
                                                <label for="f_tab_order"><?php echo $this->lang->line('text_display_order'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                <input id="f_tab_order" type="text" class="form-control" name="f_tab_order" value="<?php if (isset($f_tab_order)) echo $f_tab_order;elseif (isset($features_detail['f_tab_order'])) echo $features_detail['f_tab_order'] ?>">
                                                <?php echo form_error('f_tab_order', '<em style="color:red">', '</em>'); ?>                                                
                                            </div>                                        
                                        </div>
                                        <div class="form-group text-center">
                                            <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary " <?php
                                            if ($this->system->demo_user == 1 && isset($features_detail['f_id']) && $features_detail['f_id'] <= 8) {
                                                echo 'disabled';
                                            }
                                            ?>>   
                                            <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>features/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                 
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
            'f_tab_name': {
            required: true,
            },
                    'f_tab_img_position': {
                    required: true,
                    },
                    'f_tab_img':{
<?php if ($Action == $this->lang->line('text_action_add')) { ?>
                        required: true,
<?php } ?>
                    accept: 'jpg|jpeg|png',
                            filesize: 2000000,
                    },
                    'f_tab_order':{
                    required:true,
                    }
            },
                    messages: {
                    'f_tab_name': {
                    required: '<?php echo $this->lang->line('err_f_tab_name_req'); ?>',
                    },
                            'f_tab_img_position': {
                            required: '<?php echo $this->lang->line('err_f_tab_img_position_req'); ?>',
                            },
                            'f_tab_img':{
                            required: '<?php echo $this->lang->line('err_image_req'); ?>',
                                    accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                            },
                            'f_tab_order':{
                            required: '<?php echo $this->lang->line('err_dp_order_req'); ?>',
                            }
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