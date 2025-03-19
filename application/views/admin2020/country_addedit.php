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
                        <h1 class="h2"><?php echo $this->lang->line('text_country'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>country/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_country'); ?></strong></div>                                
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  enctype="multipart/form-data"  id="validate" novalidate="" method="POST" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>country/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>country/edit<?php } ?>">                                            
                                            <div class="row">
                                                <input  type="hidden" class="form-control" name="country_id" value="<?php if (isset($country_id)) echo $country_id;elseif (isset($country_detail['country_id'])) echo $country_detail['country_id'] ?>">                                                   
                                                <div class="form-group col-md-6">
                                                    <label for="country_name"><?php echo $this->lang->line('text_country_name'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="country_name" value="<?php if (isset($country_name)) echo $country_name;elseif (isset($country_detail['country_name'])) echo $country_detail['country_name'] ?>" >
                                                    <?php echo form_error('country_name', '<em style="color:red">', '</em>'); ?>
                                                </div>       
                                                <div class="form-group col-md-6">
                                                    <label for="p_code"><?php echo $this->lang->line('text_country_code'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="p_code" value="<?php if (isset($p_code)) echo $p_code;elseif (isset($country_detail['p_code'])) echo $country_detail['p_code'] ?>" >
                                                    <?php echo form_error('p_code', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                    
                                            </div> 
                                            <div class="form-group text-center">
                                                <button class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" <?php
                                                if ($this->system->demo_user == 1 && isset($country_detail['country_id']) && $country_detail['country_id'] <= 230) {
                                                    echo 'disabled';
                                                }
                                                ?>>Submit</button>
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>country/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>   
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
                    country_name: {
                        required: true,
                    },
                    p_code: {
                        required: true,
                    },
                },
                messages: {
                    country_name: {
                        required: '<?php echo $this->lang->line('err_country_name_req'); ?>',
                    },
                    p_code: {
                        required: '<?php echo $this->lang->line('err_p_code_req'); ?>',
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