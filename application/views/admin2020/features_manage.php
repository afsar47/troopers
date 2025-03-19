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
                        <h1 class="h2"><?php echo $this->lang->line('text_features');?></h1>                        
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
                                <div class="card-header"><strong><?php echo $this->lang->line('text_features_setting');?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form method="POST" action="<?php echo base_url() . $this->path_to_view_admin ?>features/" id="validate">                                           
                                            <div class="form-group">
                                                <label for="features_title"><?php echo $this->lang->line('text_section_title');?><span class="required" aria-required="true"> * </span></label>
                                                <input type="text" id="features_title" name="features_title" value="<?php echo $this->system->features_title; ?>" class="form-control">                                                    
                                                <?php echo form_error('features_title', '<em style="color:red">', '</em>'); ?>
                                            </div> 
                                            <div class="form-group">
                                                <label for="features_text"><?php echo $this->lang->line('text_section_sub_title');?><span class="required" aria-required="true"> * </span></label>
                                                <textarea id="features_text" name="features_text" rows="5" value="<?php echo $this->system->features_text; ?>" class="form-control"><?php echo $this->system->features_text; ?>  </textarea>                                                  
                                                <?php echo form_error('features_text', '<em style="color:red">', '</em>'); ?>
                                            </div> 
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit');?>" name="submit_features" class="btn btn-primary " <?php if($this->system->demo_user == 1){ echo 'disabled';}?>>
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>features/" name="cancel"><?php echo $this->lang->line('text_btn_cancel');?></a>                                                
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><?php echo $this->lang->line('text_features_tab');?></h1>
                        <?php if (isset($btn)) { ?>
                            <a class="btn btn-sm btn-outline-secondary float-right" href="<?php echo base_url() . $this->path_to_view_admin; ?>features/insert">
                                <i class="fa fa-plus"></i> <?php echo $btn; ?>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3" >
                            <form name="frmfeatureslist" method="post" action="<?php echo base_url() . $this->path_to_view_admin ?>features">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="manage_tbl">
                                        <thead>
                                            <tr>                                                
                                                <th colspan="10">
                                                    <label><?php echo $this->lang->line('text_action_perform'); ?></label>
                                                    <select class="multi_action form-control d-inline w-auto ml-2">
                                                        <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                        <option value="delete"><?php echo $this->lang->line('text_delete'); ?></option>
                                                        <option value="change_publish"><?php echo $this->lang->line('text_change_status'); ?></option>
                                                    </select>
                                                </th>                                                
                                            </tr> 
                                            <tr>
                                                <th><input type="checkbox" class='checkall' id='checkall'> </th>
                                                <th><?php echo $this->lang->line('text_sr_no');?></th>
                                                <th><?php echo $this->lang->line('text_feature_tab_name');?></th>
                                                <th><?php echo $this->lang->line('text_feature_tab_title');?></th>
                                                <th><?php echo $this->lang->line('text_feature_tab_text');?></th>
                                                <th><?php echo $this->lang->line('text_feature_tab_image');?></th>
                                                <th><?php echo $this->lang->line('text_feature_tab_image_position');?></th>
                                                <th><?php echo $this->lang->line('text_status');?></th>
                                                <th><?php echo $this->lang->line('text_date');?></th>
                                                <th><?php echo $this->lang->line('text_actions');?></th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th><?php echo $this->lang->line('text_sr_no');?></th>
                                                <th><?php echo $this->lang->line('text_feature_tab_name');?></th>
                                                <th><?php echo $this->lang->line('text_feature_tab_title');?></th>
                                                <th><?php echo $this->lang->line('text_feature_tab_text');?></th>
                                                <th><?php echo $this->lang->line('text_feature_tab_image');?></th>
                                                <th><?php echo $this->lang->line('text_feature_tab_image_position');?></th>
                                                <th><?php echo $this->lang->line('text_status');?></th>
                                                <th><?php echo $this->lang->line('text_date');?></th>
                                                <th><?php echo $this->lang->line('text_actions');?></th>
                                            </tr>   
                                        </tfoot>
                                    </table>
                                    <input type="hidden" name="action" />
                                    <input type="hidden" name="fid" />
                                    <input type="hidden" name="publish" /> 
                                </div>
                            </form>
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
                        'features_title': {
                            required: true,
                        },
                        'features_text': {
                            required: true,
                        }
                    },
                    messages: {
                        'features_title': {
                            required: '<?php echo $this->lang->line('err_features_title_req'); ?>',
                        },
                        'features_text': {
                            required: '<?php echo $this->lang->line('err_features_text_req'); ?>',
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