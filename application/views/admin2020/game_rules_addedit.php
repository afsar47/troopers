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
                        <h1 class="h2">Game Rules</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">                      
                        </div>
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
                                <div class="card-header"><strong>Game Rules</strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  id="validate" novalidate="" method="POST" action="<?php echo base_url() . $this->path_to_view_admin ?>gamerules/">
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <label for="game_rules">Game Rules<span class="required" aria-required="true"> * </span></label>
                                                    <textarea id="game_rules" type="text"  class="form-control ckeditor" name="game_rules" ><?php if (isset($game_rules)) echo $game_rules;elseif (isset($this->system->game_rules)) echo $this->system->game_rules ?></textarea>
                                                    <?php echo form_error('game_rules', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                            </div>
                                            <div class="form-group text-center">
                                                <button class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('text_btn_submit');?>" name="submit" >Submit</button>
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

                    game_rules: {
                        required: true,
                    },
                },
                messages: {
                    game_rules: {
                        required: "Please enter game rules",
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