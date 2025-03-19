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
                        <h1 class="h2"><?php echo $this->lang->line('text_game'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>game/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_game'); ?></strong></div>                                
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  enctype="multipart/form-data"  id="validate" novalidate="" method="POST" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>game/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>game/edit<?php } ?>">                                            
                                            <div class="row">
                                                <input  type="hidden" class="form-control" name="game_id" value="<?php if (isset($game_id)) echo $game_id;elseif (isset($game_detail['game_id'])) echo $game_detail['game_id'] ?>">                                                   
                                                <div class="form-group col-md-6">
                                                    <label for="game_name"><?php echo $this->lang->line('text_game_name'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="game_name" value="<?php if (isset($game_name)) echo $game_name;elseif (isset($game_detail['game_name'])) echo $game_detail['game_name'] ?>" >
                                                    <?php echo form_error('game_name', '<em style="color:red">', '</em>'); ?>
                                                </div>      
                                                <div class="form-group col-md-6">
                                                    <label for="package_name"><?php echo $this->lang->line('text_package_name'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="package_name" value="<?php if (isset($package_name)) echo $package_name;elseif (isset($game_detail['package_name'])) echo $game_detail['package_name'] ?>" >
                                                    <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> Package name show <a href="<?php echo base_url() . $this->system->admin_photo . '/note.png'; ?>" target="_blank">here</a>.</p> 
                                                    <?php echo form_error('package_name', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="game_image"><?php echo $this->lang->line('text_image'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                    <input id="game_image" type="file" class="file-input d-block" name="game_image" >
                                                    <?php echo form_error('game_image', '<em style="color:red">', '</em>'); ?>
                                                    <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_1000x500'); ?></p>    
                                                    <input type="hidden" id="file-input" name="old_game_image"  value="<?php echo (isset($game_detail['game_image'])) ? $game_detail['game_image'] : ''; ?>" class="form-control-file">                                                                                                      
                                                    <?php if (isset($game_detail['game_image']) && $game_detail['game_image'] != '' && file_exists($this->game_image . $game_detail['game_image'])) { ?>
                                                        <br>
                                                        <img src ="<?php echo base_url() . $this->game_image . "thumb/100x100_" . $game_detail['game_image'] ?>" >
                                                    <?php } ?>
                                                </div>   
                                                <div class="form-group col-md-6">
                                                    <label for="game_logo"><?php echo $this->lang->line('text_logo'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                    <input id="game_logo" type="file" class="file-input d-block" name="game_logo" >
                                                    <?php echo form_error('game_logo', '<em style="color:red">', '</em>'); ?>
                                                    <input type="hidden" id="file-input" name="old_game_logo"  value="<?php echo (isset($game_detail['game_logo'])) ? $game_detail['game_logo'] : ''; ?>" class="form-control-file">                                                                                                      
                                                    <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_40x40'); ?></p>                                                        
                                                    <?php if (isset($game_detail['game_logo']) && $game_detail['game_logo'] != '' && file_exists($this->game_logo_image . $game_detail['game_logo'])) { ?>
                                                        <br>
                                                        <img src ="<?php echo base_url() . $this->game_logo_image . "thumb/100x100_" . $game_detail['game_logo'] ?>" >
                                                    <?php } ?>
                                                </div>   
                                                <div class="form-group col-md-6">
                                                    <label for="game_type"><?php echo $this->lang->line('text_game_type'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="game_type_yes" name="game_type" type="radio" class="custom-control-input" value="1" <?php
                                                            if (isset($game_type) && $game_type == '1') {
                                                                echo 'checked';
                                                            } elseif (isset($game_detail['game_type']) && $game_detail['game_type'] == '1') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="game_type_yes"><?php echo $this->lang->line('text_user_challenge'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="game_type_no" name="game_type" type="radio" class="custom-control-input" value="0" <?php
                                                            if (isset($game_type) && $game_type == '0') {
                                                                echo 'checked';
                                                            } elseif (isset($game_detail['game_type']) && $game_detail['game_type'] == '0') {
                                                                echo 'checked';
                                                            } elseif ($Action == $this->lang->line('text_action_add')) {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="game_type_no"><?php echo $this->lang->line('text_normal'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('game_type', '<em style="color:red">', '</em>'); ?>
                                                </div>
												<div class="form-group col-md-6 div_id_prefix <?php
                                                if ((isset($game_type) && $game_type == '0') || (isset($game_detail['game_type']) && $game_detail['game_type'] == '0') || $Action == $this->lang->line('text_action_add'))
                                                    echo 'd-none';                                                
                                                ?>">
                                                    <label for="id_prefix"><?php echo $this->lang->line('text_id_prefix'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="id_prefix" value="<?php if (isset($id_prefix)) echo $id_prefix;elseif (isset($game_detail['id_prefix'])) echo $game_detail['id_prefix'] ?>" >
                                                    <?php echo form_error('id_prefix', '<em style="color:red">', '</em>'); ?>
                                                </div>                                               
                                                <!-- <div class="form-group col-md-6">
                                                    <label for="banned"><?php echo $this->lang->line('text_banned'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="banned_yes" name="banned" type="radio" class="custom-control-input" value="1" <?php
                                                            if (isset($banned) && $banned == '1') {
                                                                echo 'checked';
                                                            } elseif ($game_detail['banned'] == '1') {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="banned_yes"><?php echo $this->lang->line('text_yes'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="banned_no" name="banned" type="radio" class="custom-control-input" value="0" <?php
                                                            if (isset($banned) && $banned == '0') {
                                                                echo 'checked';
                                                            } elseif ($game_detail['banned'] == '0') {
                                                                echo 'checked';
                                                            } elseif ($Action == $this->lang->line('text_action_add')) {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="banned_no"><?php echo $this->lang->line('text_no'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('banned', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                                                                 -->
                                                <div class="form-group col-12">
                                                    <label for="game_rules"><?php echo $this->lang->line('text_game_rules'); ?></label>
                                                    <textarea type="text"  class="form-control ckeditor" id="editor1" name="game_rules" ><?php
                                                        if (isset($game_rules))
                                                            echo $game_rules;elseif (isset($game_detail['game_rules']))
                                                            echo $game_detail['game_rules'];
                                                        ?></textarea>
                                                    <?php echo form_error('game_rules', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                            </div>
                                            <div class="form-group text-center">
                                                <button class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('text_btn_submit');?>" name="submit"<?php
                                                if ($this->system->demo_user == 1 && isset($game_detail['game_id']) && $game_detail['game_id'] <= 2) {
                                                    echo 'disabled';
                                                }
                                                ?>><?php echo $this->lang->line('text_btn_submit'); ?></button>
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>game/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>   
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
//            CKEDITOR.editorConfig = function (config) {
//                config.language = 'es';
//                config.uiColor = '#F7B42C';
//                config.height = 300;
//                config.toolbarCanCollapse = true;
//
//            };
//            CKEDITOR.replace('editor1');

            $.validator.addMethod('filesize', function (value, element, arg) {
            if ((element.files[0].size <= arg)) {
            return true;
            } else {
            return false;
            }
            }, '<?php echo $this->lang->line('err_image_size'); ?>');
            $("#validate").validate({
            rules: {
            game_name: {
            required: true,
            },
                    game_image: {
<?php if ($Action == $this->lang->line('text_action_add')) { ?>
                        required: true,
<?php } ?>
                    accept: "jpg|png|jpeg",
//                        filesize : 2000000,
                    },
                    game_logo: {
<?php if ($Action == $this->lang->line('text_action_add')) { ?>
                        required: true,
<?php } ?>
                    accept: "jpg|png|jpeg",
//                        filesize : 2000000,
                    },
                    package_name: {
                    required: true,
                    },
                    game_rules: {
                    required: function (textarea) {
                    CKEDITOR.instances[textarea.id].updateElement();
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                    return editorcontent.length === 0;
                    }
                    },
                    id_prefix: {
                        required: function () {
                            if ($('input[name="game_type"]:checked').val() == "1") {
                                return true;
                            } else {
                                return false;
                            }
                        },
						accept: "[a-zA-Z\s]"
                    },
            },
                    messages: {
                    game_name: {
                    required: '<?php echo $this->lang->line('err_game_name_req'); ?>',
                    },
                            game_image: {
                            required: '<?php echo $this->lang->line('err_image_req'); ?>',
                                    accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                            },
                            game_logo: {
                            required: '<?php echo $this->lang->line('err_image_req'); ?>',
                                    accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                            },
                            package_name: {
                            required: '<?php echo $this->lang->line('err_package_name_req'); ?>',
                            },
                            game_rules: {
                            required: '<?php echo $this->lang->line('err_game_rules_req'); ?>',
                            },
                            id_prefix: {
							required: '<?php echo $this->lang->line('err_id_prefix_req'); ?>',
							accept: 'Enter only Text',
							},
                    },
                    errorPlacement: function (error, element)
                    {
                    if (element.is(":radio"))
                    {
                    element.parent().append(error);
                    } else if (element.is("textarea"))
                    {
                    element.parent().append(error);
                    } else
                    {
                    error.insertAfter(element);
                    }
                    },
            });

            $('input[type=radio][name=game_type]').on('change', function () {                
                if ($(this).val() == '1') {
                    $(".div_id_prefix").removeClass("d-none");
                } else {
                    $('input[name=id_prefix]').val('');
                    $(".div_id_prefix").addClass("d-none");
                }
            });
        </script>
    </body>
</html>