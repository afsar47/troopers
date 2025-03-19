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
                        <h1 class="h2"><?php echo $this->lang->line('text_features'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>tab_content/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_features'); ?></strong></div>
                                <div class="card-body">
                                    <form method="POST" id="validate" enctype="multipart/form-data" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>tab_content/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>tab_content/edit<?php } ?>">                                                                                                                                 
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <input id="content_title" type="hidden" class="form-control" name="ftc_id" value="<?php if (isset($tab_content_detail['ftc_id'])) echo $tab_content_detail['ftc_id'] ?>">                                                                                              
                                                <label for="features_tab_id"><?php echo $this->lang->line('text_feature_tab_name'); ?> <span class="required" aria-required="true"> * </span></label><br>
                                                <select class="form-control" name="features_tab_id" id="features_tab_id">
                                                    <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                    <?php foreach ($feature_tab as $feature) {
                                                        ?>
                                                        <option value="<?php echo $feature->f_id; ?>" <?php if (isset($content_title) && $content_title == $feature->f_id) echo 'selected';elseif (isset($tab_content_detail['features_tab_id']) && $tab_content_detail['features_tab_id'] == $feature->f_id) echo 'selected'; ?>><?php echo $feature->f_tab_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error('features_tab_id', '<em style="color:red">', '</em>'); ?>                                                
                                            </div> 
                                            <div class="form-group col-md-6">
                                                <label for="content_title"><?php echo $this->lang->line('text_content_title'); ?> <span class="required" aria-required="true"> * </span></label><br>
                                                <input id="content_title" type="text" class="form-control" name="content_title" value="<?php if (isset($content_title)) echo $content_title;elseif (isset($tab_content_detail['content_title'])) echo $tab_content_detail['content_title'] ?>">
                                                <?php echo form_error('content_title', '<em style="color:red">', '</em>'); ?>                                                
                                            </div> 
                                        </div>  
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="content_text"><?php echo $this->lang->line('text_content_text'); ?> <span class="required" aria-required="true"> * </span></label><br>
                                                <textarea id="content_text" type="text" class="form-control" name="content_text"><?php if (isset($content_text)) echo $content_text;elseif (isset($tab_content_detail['content_text'])) echo $tab_content_detail['content_text'] ?></textarea>
                                                <?php echo form_error('content_text', '<em style="color:red">', '</em>'); ?>                                                
                                            </div> 
                                            <div class="form-group col-md-6">
                                                <label for="content_icon"><?php echo $this->lang->line('text_content_icon'); ?> <span class="required" aria-required="true"> * </span><?php echo $this->lang->line('text_note_tab'); ?></label><br>
                                                <input id="content_icon" type="text" class="form-control" name="content_icon" value="<?php
                                                if (isset($content_icon))
                                                    echo $content_icon;elseif (isset($tab_content_detail['content_icon']))
                                                    echo $tab_content_detail['content_icon'];
                                                else
                                                    echo 'fa fa-';
                                                ?>">                    
                                                <?php echo form_error('content_icon', '<em style="color:red">', '</em>'); ?>                                                
                                            </div> 
                                        </div>  
                                        <div class="form-group text-center">
                                            <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary " <?php
                                            if ($this->system->demo_user == 1 && isset($tab_content_detail['ftc_id']) && $tab_content_detail['ftc_id'] <= 14) {
                                                echo 'disabled';
                                            }
                                            ?>> 
                                            <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>tab_content/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                   
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
                $("#validate").validate({
                    rules: {
                        'features_tab_id': {
                            required: true,
                        },
                        'content_title': {
                            required: true,
                        },
                        'content_text': {
                            required: true,
                        },
                        'content_icon': {
                            required: true,
                            accept: "[a-zA-Z\-\s]"
                        },
                    },
                    messages: {
                        'features_tab_id': {
                            required: '<?php echo $this->lang->line('err_features_tab_id_req'); ?>',
                        },
                        'content_title': {
                            required: '<?php echo $this->lang->line('err_content_title_req'); ?>',
                        },
                        'content_text': {
                            required: '<?php echo $this->lang->line('err_content_text_req'); ?>',
                        },
                        'content_icon': {
                            required: '<?php echo $this->lang->line('err_content_icon_req'); ?>',
                            accept: '<?php echo $this->lang->line('err_content_icon_only_text'); ?>',
                        },
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
            });
        </script>
    </body>
</html>