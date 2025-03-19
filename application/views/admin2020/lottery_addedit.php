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
                        <h1 class="h2"><?php echo $this->lang->line('text_lottery'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>lottery/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_lottery'); ?></strong></div>                                
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  enctype="multipart/form-data"  id="validate" novalidate="" method="POST" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>lottery/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>lottery/edit<?php } ?>">                                            
                                            <div class="row">
                                                <input  type="hidden" class="form-control" name="lottery_id" value="<?php if (isset($lottery_id)) echo $lottery_id;elseif (isset($lottery_detail['lottery_id'])) echo $lottery_detail['lottery_id'] ?>">                                                   
                                                <div class="form-group col-md-6">
                                                    <label for="lottery_title"><?php echo $this->lang->line('text_title'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="lottery_title" value="<?php if (isset($lottery_title)) echo $lottery_title;elseif (isset($lottery_detail['lottery_title'])) echo $lottery_detail['lottery_title'] ?>" >
                                                    <?php echo form_error('lottery_title', '<em style="color:red">', '</em>'); ?>
                                                </div>      
                                                <div class="form-group col-md-6">
                                                    <label for="lottery_time"><?php echo $this->lang->line('text_time'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="lottery_time" id="datetimepicker1" value="<?php if (isset($lottery_time)) echo $lottery_time;elseif (isset($lottery_detail['lottery_time'])) echo $lottery_detail['lottery_time'] ?>" >
                                                    <?php echo form_error('lottery_time', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="lottery_fees"><?php echo $this->lang->line('text_fees'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="lottery_fees" value="<?php if (isset($lottery_fees)) echo $lottery_fees;elseif (isset($lottery_detail['lottery_fees'])) echo $lottery_detail['lottery_fees'] ?>" >
                                                    <?php echo form_error('lottery_fees', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="lottery_prize"><?php echo $this->lang->line('text_prize'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="lottery_prize" value="<?php if (isset($lottery_prize)) echo $lottery_prize;elseif (isset($lottery_detail['lottery_prize'])) echo $lottery_detail['lottery_prize'] ?>" >
                                                    <?php echo form_error('lottery_prize', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="lottery_size"><?php echo $this->lang->line('text_size'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="lottery_size" value="<?php if (isset($lottery_size)) echo $lottery_size;elseif (isset($lottery_detail['lottery_size'])) echo $lottery_detail['lottery_size'] ?>" >
                                                    <?php echo form_error('lottery_size', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-5">
                                                    <label for="lottery_image"><?php echo $this->lang->line('text_browse_image'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                    <input id="lottery_image" type="file" class="file-input d-block" name="lottery_image" >
                                                    <?php echo form_error('lottery_image', '<em style="color:red">', '</em>'); ?>
                                                    <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_1000x500'); ?></p>    
                                                    <input type="hidden" id="file-input" name="old_lottery_image"  value="<?php echo (isset($lottery_detail['lottery_image'])) ? $lottery_detail['lottery_image'] : ''; ?>" class="form-control-file">                                                                                                      
                                                    <?php if (isset($lottery_detail['lottery_image']) && $lottery_detail['lottery_image'] != '' && file_exists($this->lottery_image . $lottery_detail['lottery_image'])) { ?>
                                                        <br>
                                                        <img src ="<?php echo base_url() . $this->lottery_image . "thumb/100x100_" . $lottery_detail['lottery_image'] ?>" >
                                                    <?php } ?>
                                                </div>
                                                <div class="form-group col-md-1 m-auto text-center"><b><u><?php echo $this->lang->line('text_or'); ?></u></b></div>
                                                <div class="form-group col-md-6">
                                                    <label for="image_id"><?php echo $this->lang->line('text_select_image'); ?> <span class="required" aria-required="true"> * </span></label>
                                                    <select class="form-control" name="image_id">
                                                        <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                        <?php
                                                        foreach ($images as $image) {
                                                            ?>
                                                            <option value="<?php echo $image->image_id; ?>" <?php
                                                            if (isset($image_id) && $image_id == $image->image_id)
                                                                echo 'selected';
                                                            elseif (isset($lottery_detail['image_id']) && $lottery_detail['image_id'] == $image->image_id)
                                                                echo 'selected';
                                                            else
                                                                echo '';
                                                            ?>><?php echo $image->image_title; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                    </select>
                                                    <?php echo form_error('image_id', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label for="lottery_rules"><?php echo $this->lang->line('text_rules'); ?></label>
                                                    <textarea type="text"  class="form-control ckeditor" id="editor1" name="lottery_rules" ><?php
                                                        if (isset($lottery_rules))
                                                            echo $lottery_rules;elseif (isset($lottery_detail['lottery_rules']))
                                                            echo $lottery_detail['lottery_rules'];
                                                        ?></textarea>
                                                    <?php echo form_error('lottery_rules', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                            </div>
                                            <div class="form-group text-center">
                                                <button class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" <?php
                                                if ($this->system->demo_user == 1 && isset($lottery_detail['lottery_id']) && $lottery_detail['lottery_id'] <= 2) {
                                                    echo 'disabled';
                                                }
                                                ?>><?php echo $this->lang->line('text_btn_submit'); ?></button>
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>lottery/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>   
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
        <script  type="text/javascript">
            $('#datetimepicker1').datetimepicker({
                format: 'Y-m-d H:i:s',
            });
            $.validator.addMethod('filesize', function (value, element, arg) {
                if ((element.files[0].size <= arg)) {
                    return true;
                } else {
                    return false;
                }
            }, '<?php echo $this->lang->line('err_image_size'); ?>');
            $("#validate").validate({
                rules: {
                    lottery_title: {
                        required: true,
                    },
                    lottery_image: {
                        required: function () {
                            if ($('select[name="image_id"]').val() == "" && $('input[name="old_lottery_image"]').val() == "") {
                                return true;
                            } else {
                                return false;
                            }
                        },
                        accept: "jpg|png|jpeg",
//                        filesize : 2000000,
                    },
                    lottery_time: {
                        required: true,
                    },
                    lottery_rules: {
                        required: function (textarea) {
                            CKEDITOR.instances[textarea.id].updateElement();
                            var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                            return editorcontent.length === 0;
                        }
                    },
                    lottery_fees: {
                        required: true,
                        number: true,
                    },
                    lottery_prize: {
                        required: true,
                        number: true,
                    },
                    lottery_size: {
                        required: true,
                        number: true,
                    },
                },
                messages: {
                    lottery_title: {
                        required: '<?php echo $this->lang->line('err_lottery_title_req'); ?>',
                    },
                    lottery_image: {
                        required: '<?php echo $this->lang->line('err_image_req'); ?>',
                        accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                    },
                    lottery_time: {
                        required: '<?php echo $this->lang->line('err_lottery_time_req'); ?>',
                    },
                    lottery_rules: {
                        required: '<?php echo $this->lang->line('err_lottery_rules_req'); ?>',
                    },
                    lottery_fees: {
                        required: '<?php echo $this->lang->line('err_lottery_fees_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                    },
                    lottery_prize: {
                        required: '<?php echo $this->lang->line('err_lottery_prize_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
                    },
                    lottery_size: {
                        required: '<?php echo $this->lang->line('err_lottery_size_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number'); ?>',
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
        </script>
    </body>
</html>