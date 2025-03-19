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
                        <?php if (isset($btn)) { ?>
                            <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>tab_content/insert">
                                <i class="fa fa-plus"></i> <?php echo $btn; ?>
                            </a>
                        <?php } ?>
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
                        <div class="col-md-12" >
                            <form name="frmtab_contentlist" method="post" action="<?php echo base_url() . $this->path_to_view_admin ?>tab_content">
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
                                                <th><?php echo $this->lang->line('text_feature_tab_name'); ?></th>
                                                <th><?php echo $this->lang->line('text_content_title'); ?></th>
                                                <th><?php echo $this->lang->line('text_content_text'); ?></th>
                                                <th><?php echo $this->lang->line('text_content_icon'); ?></th>
                                                <th><?php echo $this->lang->line('text_actions'); ?></th>
                                                <th><?php echo $this->lang->line('text_view'); ?></th>
                                            </tr>   
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                <th><?php echo $this->lang->line('text_feature_tab_name'); ?></th>
                                                <th><?php echo $this->lang->line('text_content_title'); ?></th>
                                                <th><?php echo $this->lang->line('text_content_text'); ?></th>
                                                <th><?php echo $this->lang->line('text_content_icon'); ?></th>
                                                <th><?php echo $this->lang->line('text_actions'); ?></th>
                                                <th><?php echo $this->lang->line('text_view'); ?></th>
                                            </tr>   
                                        </tfoot>
                                    </table>
                                    <input type="hidden" name="action" />
                                    <input type="hidden" name="tab_contentid" />
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
    </body>
</html>