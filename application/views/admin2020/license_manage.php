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

                    <?php if (YES != 'yes') { ?>
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2"><?php echo $this->lang->line('text_license_setting');?></h1>
                        </div>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                                <span><?php echo $this->session->flashdata('error'); ?></span>
                            </div>
                        <?php } ?>
                        <?php if ($this->system->purchase_code_msg != '') { ?>
                            <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                                <span><?php echo $this->system->purchase_code_msg; ?></span>
                            </div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('notification')) { ?>
                            <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                                <span><?php echo $this->session->flashdata('notification'); ?></span>
                            </div>
                        <?php } ?>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card bg-light text-dark">
                                    <div class="card-header"><strong><?php echo $this->lang->line('text_license_setting');?></strong></div>
                                    <div class="card-body">
                                        <div class="col-md-12">
                                            <form class="needs-validation"  id="license-form"  enctype="multipart/form-data" novalidate="" method="POST" action="<?php echo base_url() . $this->path_to_view_admin ?>license">     
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="purchase_code"><?php echo $this->lang->line('text_purchasecode');?><span class="required" aria-required="true"> * </span><i class="fa fa-question-circle modal-div" style="cursor:pointer" data-toggle="modal" data-target="#myModal" ></i></label><br>
                                                        <input id="purchase_code" type="text" class="form-control" name="purchase_code" value="<?php echo $this->system->purchase_code; ?>">   <br>                                                
                                                        <?php echo form_error('purchase_code', '<em style="color:red">', '</em>'); ?>
                                                    </div>                                                                                                                                                  
                                                </div>
                                                <div class="form-group text-center">
                                                    <input type="submit" value="<?php echo $this->lang->line('text_btn_submit');?>" name="license_submit" class="btn btn-primary "  <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>    
                                                    <!--<a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>appsetting/" name="cancel">Cancel</a>-->                                                 
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal" id="myModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Find your purchase code</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><b>Follow below steps to find your puchase code.</b></p>
                                        <p>1. Open download section from your codecanyon account. </p>
                                        <p>2. Find product that you have purchased. </p>
                                        <p>3. Click on download button of that product. </p>
                                        <p>4. Then from dropdown, Click on "License certificate &amp; purchase code (PDF)". It will download PDF file.</p>
                                        <p>5. From that file, "Item Purchase Code" is your purchase code.</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php } else { ?>

                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2"><?php echo $this->lang->line('text_license_info');?></h1>
                        </div>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                                <span><?php echo $this->session->flashdata('error'); ?></span>
                            </div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('notification')) { ?>
                            <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                                <span><?php echo $this->session->flashdata('notification'); ?></span>
                            </div>
                        <?php } ?>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card bg-light text-dark">
                                    <div class="card-header"><strong><?php echo $this->lang->line('text_license_info');?> | <a href="https://cutt.ly/PLFZenO" target="_blank">NULLED :: Web Community</a></strong></div>
                                    <div class="card-body">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label for="purchase_code"><strong><?php echo $this->lang->line('text_purchasecode');?> : </strong></label>
                                                </div>     
                                                <div class="form-group col-md-6">
                                                    <span><?php echo str_replace(substr($this->system->purchase_code, 5, 26), $this->functions->stars($this->system->purchase_code), $this->system->purchase_code); ?></span>                                
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label for="purchase_code"><strong><?php echo $this->lang->line('text_installed_url');?> : </strong></label>
                                                </div>     
                                                <div class="form-group col-md-6">
                                                    <span><?php echo $this->system->purchase_domain; ?></span>                                
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6 offset-md-2">
                                                    <a href="<?php echo base_url() . $this->path_to_view_admin . 'license/remove_license'; ?>" class="btn btn-primary <?php
                                                       if ($this->system->demo_user == 1) {
                                                           echo 'd-none';
                                                       }
                                                       ?>"><?php echo $this->lang->line('text_deactivate');?></a>
                                                </div>     

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>                    
                </div>

                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>
        <script>
            $("#license-form").validate({
                rules: {
                    'purchase_code': {
                        required: true,
                    },
                },
                messages: {
                    'purchase_code': {
                        required: '<?php echo $this->lang->line('err_purchase_code_req'); ?>',
                    },
                }
                ,
                errorPlacement: function (error, element)
                {
                    if (element.is(":file"))
                    {
                        element.parent().append(error);
                    } else if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
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