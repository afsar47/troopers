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
                        <h1 class="h2"><?php echo $this->lang->line('text_changepassword');?></h1>
                    </div>

                    <div class="row" >
                        <div class="offset-md-3 col-md-6">
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
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_changepassword');?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  id="change-password-form" novalidate="" method="POST" action="<?php echo base_url() . $this->path_to_view_admin ?>changepassword">
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <label for="old_password"><?php echo $this->lang->line('text_old_password');?></label>
                                                    <input id="old_password" type="password" class="form-control" name="old_password" value="<?php if (isset($old_password)) echo $old_password; ?>">
                                                    <?php echo form_error('old_password', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label for="new_password"><?php echo $this->lang->line('text_new_password');?></label>
                                                    <input id="new_password" type="password" class="form-control" name="new_password" value="<?php if (isset($new_password)) echo $new_password; ?>">
                                                    <?php echo form_error('new_password', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label for="c_passowrd"><?php echo $this->lang->line('text_confirm_password');?></label>
                                                    <input id="c_passowrd" type="password" class="form-control" name="c_passowrd" value="<?php if (isset($c_passowrd)) echo $c_passowrd; ?>">
                                                    <?php echo form_error('c_passowrd', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                                                               
                                            </div>                                                                                                                                                                  
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_update');?>" name="password_update" class="btn btn-primary " <?php
                                                if ($this->system->demo_user == 1) {
                                                    echo 'disabled';
                                                }
                                                ?>>                                                    
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
            $(document).ready(function ($) {
                $('#new_password').passtrength({
                    minChars: 4,
                    passwordToggle: true,
                    tooltip: true
                });
            });
            $("#change-password-form").validate({
                rules: {
                    old_password: {
                        required: true,
                    },
                    new_password: {
                        required: true,
                    },
                    c_passowrd: {
                        required: true,
                        equalTo: "#new_password",
                    },
                },
                messages: {
                    old_password: {
                        required: '<?php echo $this->lang->line('err_old_password_req'); ?>',
                    },
                    new_password: {
                        required: '<?php echo $this->lang->line('err_new_password_req'); ?>',
                    },
                    c_passowrd: {
                        required: '<?php echo $this->lang->line('err_c_passowrd_req'); ?>',
                        equalTo: '<?php echo $this->lang->line('err_c_passowrd_equal'); ?>',
                    },
                },
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
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