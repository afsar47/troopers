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
                        <h1 class="h2"><?php echo $this->lang->line('text_edit_howtoplay_setting'); ?></h1>

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
                                <div class="card-header"><strong><?php echo $this->lang->line('text_edit_howtoplay_setting'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form method="POST" action="<?php echo base_url() . $this->path_to_view_admin ?>how_to_play/" id="validate">                                           
                                            <div class="form-group">
                                                <label for="htp_title"><?php echo $this->lang->line('text_title'); ?></label>
                                                <input type="text" id="htp_title" name="htp_title" value="<?php echo $this->system->htp_title; ?>" class="form-control">                                                    
                                                <?php echo form_error('htp_title', '<em style="color:red">', '</em>'); ?>
                                            </div> 
                                            <div class="form-group">
                                                <label for="htp_text"><?php echo $this->lang->line('text_sub_title'); ?></label>
                                                <textarea id="htp_text" name="htp_text" rows="5" value="<?php echo $this->system->htp_text; ?>" class="form-control"><?php echo $this->system->htp_text; ?>  </textarea>                                                  
                                                <?php echo form_error('htp_text', '<em style="color:red">', '</em>'); ?>
                                            </div> 
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary " <?php
                                                if ($this->system->demo_user == 1) {
                                                    echo 'disabled';
                                                }
                                                ?>>   
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>how_to_play/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                 
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><?php echo $this->lang->line('text_howtoplay'); ?></h1>
                        <?php if (isset($btn)) { ?>
                            <a class="btn btn-sm btn-outline-secondary float-right" href="<?php echo base_url() . $this->path_to_view_admin; ?>how_to_play/insert">
                                <i class="fa fa-plus"></i> <?php echo $btn; ?>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3" >
                            <form name="frmhtpclist" method="post" action="<?php echo base_url() . $this->path_to_view_admin ?>how_to_play">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="manage_tbl">
                                        <thead>
                                            <tr>                                                
                                                <th colspan="8">
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
                                                <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                <th><?php echo $this->lang->line('text_edit_htp_content_title'); ?></th>
                                                <th><?php echo $this->lang->line('text_edit_htp_content_text'); ?></th>
                                                <th><?php echo $this->lang->line('text_edit_htp_content_image'); ?></th>
                                                <th><?php echo $this->lang->line('text_status'); ?></th>
                                                <th><?php echo $this->lang->line('text_date'); ?></th>
                                                <th><?php echo $this->lang->line('text_actions'); ?></th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                <th><?php echo $this->lang->line('text_edit_htp_content_title'); ?></th>
                                                <th><?php echo $this->lang->line('text_edit_htp_content_text'); ?></th>
                                                <th><?php echo $this->lang->line('text_edit_htp_content_image'); ?></th>
                                                <th><?php echo $this->lang->line('text_status'); ?></th>
                                                <th><?php echo $this->lang->line('text_date'); ?></th>
                                                <th><?php echo $this->lang->line('text_actions'); ?></th>
                                            </tr>   
                                        </tfoot>
                                    </table>
                                    <input type="hidden" name="action" />
                                    <input type="hidden" name="htpcid" />
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
                        'htp_title': {
                            required: true,
                        },
                        'htp_text': {
                            required: true,
                        },
                    },
                    messages: {
                        'htp_title': {
                            required: '<?php echo $this->lang->line('err_htp_title_req'); ?>',
                        },
                        'htp_text': {
                            required: '<?php echo $this->lang->line('err_htp_text_req'); ?>',
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