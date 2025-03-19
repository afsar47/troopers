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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.css" />
    </head>
    <body>
        <?php $this->load->view($this->path_to_view_admin . 'header_body'); ?>

        <div class="d-flex" id="wrapper">
            <?php $this->load->view($this->path_to_view_admin . 'sidebar'); ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><?php echo!empty($match_detail) ? $this->lang->line('text_match') . '#' . $match_detail['m_id'] : $this->lang->line('text_match'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>matches/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_match'); ?></strong></div>
                                <?php if ($Action == $this->lang->line('text_action_edit')) { ?>   
                                    <div class="card-body">
                                        <form method="POST" id="validate-form" autocomplete="off" action="<?php if ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>matches/edit<?php } ?>">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="hidden" class="form-control" name="m_id" value="<?php if (isset($m_id)) echo $m_id;elseif (isset($match_detail['m_id'])) echo $match_detail['m_id'] ?>">
                                                        <label for="room_description"><?php echo $this->lang->line('text_room_description'); ?> <span class="required" aria-required="true"> * </span></label>
                                                        <textarea type="text"  class="form-control ckeditor" name="room_description" ><?php
                                                            if (isset($room_description))
                                                                echo $room_description;elseif (isset($match_detail['room_description']))
                                                                echo $match_detail['room_description'];
                                                            ?></textarea>
                                                        <?php echo form_error('room_description', '<em style="color:red">', '</em>'); ?>                                                
                                                    </div>
                                                </div>                                           
                                            </div>                                           
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group text-center">
                                                        <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="id_submit" class="btn btn-primary " <?php
                                                        if ($this->system->demo_user == 1 && isset($match_detail['m_id']) && $match_detail['m_id'] <= 7) {
                                                            echo 'disabled';
                                                        }
                                                        ?>>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form class="needs-validation"  enctype="multipart/form-data" id="validate" novalidate="" method="POST" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>matches/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>matches/edit<?php } ?>">
                                            <?php if ($Action == $this->lang->line('text_action_add')) { ?>

                                            <?php } ?>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="game_id"><?php echo $this->lang->line('text_game'); ?> <span class="required" aria-required="true"> * </span></label>
                                                    <select class="form-control" name="game_id" onchange="game_change(this.value);" >
                                                        <option value="">Select..</option>
                                                        <?php
                                                        foreach ($games as $game) {
                                                            ?>
                                                            <option value="<?php echo $game->game_id; ?>" <?php
                                                            if (isset($game_id) && $game_id == $game->game_id)
                                                                echo 'selected';
                                                            elseif (isset($match_detail['game_id']) && $match_detail['game_id'] == $game->game_id)
                                                                echo 'selected';
                                                            else
                                                                echo '';
                                                            ?>><?php echo $game->game_name; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                    </select>
                                                    <?php echo form_error('game_id', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <input  type="hidden" class="form-control" name="m_id" value="<?php if (isset($m_id)) echo $m_id;elseif (isset($match_detail['m_id'])) echo $match_detail['m_id'] ?>">                                                   
                                                <div class="form-group col-md-6">
                                                    <label for="match_name"><?php echo $this->lang->line('text_match_name'); ?> <span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="match_name" value="<?php if (isset($match_name)) echo $match_name;elseif (isset($match_detail['match_name'])) echo $match_detail['match_name'] ?>" >
                                                    <?php echo form_error('match_name', '<em style="color:red">', '</em>'); ?>
                                                </div>       
                                                <div class="form-group col-md-6">
                                                    <label for="match_url"><?php echo $this->lang->line('text_match_url'); ?> <span class="required" aria-required="true"> * </span></label>
                                                    <input  type="text" class="form-control" name="match_url" value="<?php
                                                    if (isset($match_url))
                                                        echo $match_url;elseif (isset($match_detail['match_url']))
                                                        echo $match_detail['match_url'];
                                                    else
                                                        echo $this->system->match_url;
                                                    ?>">
                                                            <?php echo form_error('match_url', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="match_time"><?php echo $this->lang->line('text_match_schedule'); ?> <span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" id="datetimepicker1" name="match_time" autocomplete="off" value="<?php if (isset($match_time)) echo $match_time;elseif (isset($match_detail['match_time'])) echo $match_detail['match_time'] ?>">
                                                    <?php echo form_error('match_time', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="win_prize"><?php echo $this->lang->line('text_prize_pool') . '(%)'; ?> <span class="required" aria-required="true"> * </span></label>
                                                    <input  type="text" class="form-control" id="win_prize" name="win_prize" value="<?php if (isset($win_prize)) echo $win_prize;elseif (isset($match_detail['win_prize'])) echo $match_detail['win_prize'] ?>">
                                                    <?php echo form_error('win_prize', '<em style="color:red">', '</em>'); ?>
                                                    Note: Enter Price between 0 to <?php echo 100 - $this->system->admin_profit?> in Percentage.
                                                </div>                                                
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="per_kill"><?php echo $this->lang->line('text_per_kill') . '(%)'; ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="per_kill" type="text" class="form-control" name="per_kill" value="<?php if (isset($per_kill)) echo $per_kill;elseif (isset($match_detail['per_kill'])) echo $match_detail['per_kill'] ?>" readonly>
                                                    <?php echo form_error('per_kill', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                
                                                <div class="form-group col-md-6">
                                                    <label for="type" ><?php echo $this->lang->line('text_team'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <select  class="form-control" name="type" >
                                                        <option value=""><?php echo $this->lang->line('text_select'); ?></option>
                                                        <option value="Solo" <?php if (isset($type) && $type == 'Solo') echo 'selected';elseif (isset($match_detail['type']) && $match_detail['type'] == 'Solo') echo 'selected'; ?>><?php echo $this->lang->line('text_solo'); ?></option>
                                                        <option value="Duo" <?php if (isset($type) && $type == 'Duo') echo 'selected';elseif (isset($match_detail['type']) && $match_detail['type'] == 'Duo') echo 'selected'; ?>><?php echo $this->lang->line('text_duo'); ?></option>
                                                        <option value="Squad" <?php if (isset($type) && $type == 'Squad') echo 'selected';elseif (isset($match_detail['type']) && $match_detail['type'] == 'Squad') echo 'selected'; ?>><?php echo $this->lang->line('text_squad'); ?></option>
                                                        <option value="Squad5" <?php if (isset($type) && $type == 'Squad5') echo 'selected';elseif (isset($match_detail['type']) && $match_detail['type'] == 'Squad5') echo 'selected'; ?>><?php echo $this->lang->line('text_squad5'); ?></option>
                                                    </select>
                                                    <?php echo form_error('type', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div>  
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="entry_fee"><?php echo $this->lang->line('text_entry_fee'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" id="entry_fee" class="form-control" name="entry_fee" value="<?php if (isset($entry_fee)) echo $entry_fee;elseif (isset($match_detail['entry_fee'])) echo $match_detail['entry_fee'] ?>">
                                                    <?php echo form_error('entry_fee', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="number_of_position"><?php echo $this->lang->line('text_total_player'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="number_of_position" value="<?php if (isset($number_of_position)) echo $number_of_position;elseif (isset($match_detail['number_of_position'])) echo $match_detail['number_of_position'] ?>">
                                                    <?php echo form_error('number_of_position', '<em style="color:red">', '</em>'); ?>
                                                </div>                                                
                                            </div>  
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="MAP"><?php echo $this->lang->line('text_map'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input type="text" class="form-control" name="MAP" value="<?php if (isset($MAP)) echo $MAP;elseif (isset($match_detail['MAP'])) echo $match_detail['MAP'] ?>">
                                                    <?php echo form_error('MAP', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                                <div class="col-md-6 mb-3 d-none">
                                                    <label for="match_type"><?php echo $this->lang->line('text_match_type'); ?><span class="required" aria-required="true"> * </span></label><br>
                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="free" name="match_type" type="radio" class="custom-control-input" value="0" <?php
                                                            if (isset($match_detail['match_type']) && $match_detail['match_type'] == 0) {
                                                                echo 'checked';
                                                            } elseif (isset($match_type) && $match_type == 0) {
                                                                echo 'checked';
                                                            } else {
                                                                echo 'checked';
                                                            }
                                                            ?>>&nbsp;
                                                            <label class="custom-control-label" for="free"><?php echo $this->lang->line('text_free'); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="paid" name="match_type" type="radio" class="custom-control-input" value="1" <?php
                                                            if (isset($match_detail['match_type']) && $match_detail['match_type'] == 1) {
                                                                echo 'checked';
                                                            } elseif (isset($match_type) && $match_type == 1) {
                                                                echo 'checked';
                                                            }
                                                            ?> >&nbsp;
                                                            <label class="custom-control-label" for="paid"><?php echo $this->lang->line('text_paid'); ?></label>
                                                        </div>
                                                    </div>
                                                </div>                                                
                                            </div>                                               
                                            <div class="row">
                                                <div class="form-group col-md-5">
                                                    <label for="match_banner"><?php echo $this->lang->line('text_browse_banner'); ?></label><br>
                                                    <input id="match_banner" type="file" class="file-input d-block" name="match_banner" > 
                                                    <?php echo form_error('match_banner', '<em style="color:red">', '</em>'); ?>
                                                    <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_1000x500'); ?></p>    
                                                    <input type="hidden" id="file-input" name="old_match_banner"  value="<?php echo (isset($match_detail['match_banner'])) ? $match_detail['match_banner'] : ''; ?>" class="form-control-file">                                                                                                      
                                                    <?php if (isset($match_detail['match_banner']) && $match_detail['match_banner'] != '' && file_exists($this->match_banner_image . $match_detail['match_banner'])) { ?>
                                                        <br>
                                                        <img src ="<?php echo base_url() . $this->match_banner_image . "thumb/100x100_" . $match_detail['match_banner'] ?>" >
                                                    <?php } ?>
                                                </div>
                                                <div class="form-group col-md-1 m-auto text-center"><b><u><?php echo $this->lang->line('text_or'); ?></u></b></div>
                                                <div class="form-group col-md-6">
                                                    <label for="image_id"><?php echo $this->lang->line('text_select_banner'); ?></label>
                                                    <select id="id_select2_example" name="image_id" style="width:100%">
                                                        <option value="" data-img_src=""><?php echo $this->lang->line('text_select'); ?></option>
                                                        <?php
                                                        foreach ($images as $image) {
                                                            ?>
                                                            <option value="<?php echo $image->image_id; ?>" <?php
                                                            if (isset($image_id) && $image_id == $image->image_id)
                                                                echo 'selected';
                                                            elseif (isset($match_detail['image_id']) && $match_detail['image_id'] == $image->image_id)
                                                                echo 'selected';
                                                            else
                                                                echo '';
                                                            ?> data-img_src="<?php echo base_url() . $this->select_image . "thumb/100x100_" . $image->image_name ?>"><?php echo $image->image_title; ?><img src ="<?php echo base_url() . $this->select_image . "thumb/100x100_" . $image->image_name ?>" ></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                    </select>
                                                    <?php echo form_error('image_id', '<em style="color:red">', '</em>'); ?>
                                                </div>
                                            </div> 
                                            <div class="row">                                               
                                                <div class="form-group col-12">
                                                    <label for="prize_description"><?php echo $this->lang->line('text_prize_description'); ?></label>
                                                    <textarea type="text"  class="form-control ckeditor" name="prize_description" ><?php
                                                        if (isset($prize_description))
                                                            echo $prize_description;elseif (isset($match_detail['prize_description']))
                                                            echo $match_detail['prize_description'];
                                                        ?></textarea>
                                                    <?php echo form_error('prize_description', '<em style="color:red">', '</em>'); ?>
                                                </div>  
                                            </div>  
                                            <div class="row">                                               
                                                <div class="form-group col-12">
                                                    <label for="match_sponsor"><?php echo $this->lang->line('text_match_sponsor'); ?></label>
                                                    <textarea type="text"  class="form-control ckeditor" name="match_sponsor" ><?php
                                                        if (isset($match_sponsor))
                                                            echo $match_sponsor;elseif (isset($match_detail['match_sponsor']))
                                                            echo $match_detail['match_sponsor'];
                                                        ?></textarea>
                                                    <?php echo form_error('match_sponsor', '<em style="color:red">', '</em>'); ?>
                                                </div>  
                                            </div> 
                                            <div class="row">                                               
                                                <div class="form-group col-12">
                                                    <label for="match_desc"><?php echo $this->lang->line('text_match_description'); ?></label>
                                                    <textarea type="text"  class="form-control ckeditor" id="match_desc"  name="match_desc" ><?php
                                                        if (isset($match_desc))
                                                            echo $match_desc;elseif (isset($match_detail['match_desc']))
                                                            echo $match_detail['match_desc'];
                                                        else
                                                            echo $this->system->game_rules;
                                                        ?></textarea>
                                                    <?php echo form_error('match_desc', '<em style="color:red">', '</em>'); ?>
                                                </div>  
                                            </div>                                             
                                            <div class="row">                                               
                                                <div class="form-group col-12">
                                                    <label for="match_private_desc"><?php echo $this->lang->line('text_match_private_description'); ?></label>
                                                    <textarea type="text"  class="form-control ckeditor" id="match_private_desc"  name="match_private_desc" ><?php
                                                        if (isset($match_private_desc))
                                                            echo $match_private_desc;elseif (isset($match_detail['match_private_desc']))
                                                            echo $match_detail['match_private_desc'];
                                                        else
                                                            echo $this->system->game_rules;
                                                        ?></textarea>
                                                    <?php echo form_error('match_private_desc', '<em style="color:red">', '</em>'); ?>
                                                </div>  
                                            </div>
                                            <div class="form-group text-center">
                                                <button class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" <?php
                                                if ($this->system->demo_user == 1 && isset($match_detail['m_id']) && $match_detail['m_id'] <= 7) {
                                                    echo 'disabled';
                                                }
                                                ?> ><?php echo $this->lang->line('text_btn_submit'); ?></button>
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>matches/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.js"></script>
        <script>
            function game_change(game_id) {
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url() . $this->path_to_view_admin; ?>/game/getGameRules/" + game_id,
                    success: function funSuccess(response) {
                        obj = JSON.parse(response);
                        CKEDITOR.instances['match_desc'].setData(obj.game_rules);
                        $("textarea[name='match_desc']").val(obj.game_rules);
                    }
                });
            }
            $.validator.addMethod('filesize', function (value, element, arg) {
                if ((element.files[0].size <= arg)) {
                    return true;
                } else {
                    return false;
                }
            }, '<?php echo $this->lang->line('err_image_size'); ?>');
            $("#validate-form").validate({
                rules: {
                    room_description: {
                        required: function (textarea) {
                            CKEDITOR.instances[textarea.id].updateElement();
                            var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                            return editorcontent.length === 0;
                        }
                    },                   
                },
                messages: {
                    room_description: {
                        required: "Please enter Room description",
                    },                   
                }
                ,
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });
            $("#validate").validate({
                rules: {
                    match_name: {
                        required: true,
                    },
                    match_url: {
                        required: true,
                        url: true,
                    },
                    match_time: {
                        required: true,
                    },
                    win_prize: {
                        required: true,
                    },
                    per_kill: {
                        required: true,
                    },
                    type: {
                        required: true,
                    },
                    entry_fee: {
                        required: true,
                    },                    
                    MAP: {
                        required: true,
                    },
                    game_id: {
                        required: true,
                    },
                    match_type: {
                        required: true,
                    },
                    number_of_position: {
                        required: true,
                        number: true,
                        min: 1,
                    },
                    match_banner: {
                        accept: "jpg|png|jpeg",
//                        filesize: 2000000,
                    },
                    match_desc: {
                        required: function (textarea) {
                            CKEDITOR.instances[textarea.id].updateElement();
                            var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                            return editorcontent.length === 0;
                        }
                    },
                },
                messages: {
                    match_name: {
                        required: '<?php echo $this->lang->line('err_match_name_req'); ?>',
                    },
                    match_url: {
                        required: '<?php echo $this->lang->line('err_match_url_req'); ?>',
                        url: '<?php echo $this->lang->line('err_match_url_valid'); ?>',
                    },
                    match_time: {
                        required: '<?php echo $this->lang->line('err_match_time_req'); ?>',
                    },
                    win_prize: {
                        required: '<?php echo $this->lang->line('err_win_prize_req'); ?>',
                    },
                    per_kill: {
                        required: '<?php echo $this->lang->line('err_per_kill_req'); ?>',
                    },
                    type: {
                        required: '<?php echo $this->lang->line('err_type_req'); ?>',
                    },
                    entry_fee: {
                        required: '<?php echo $this->lang->line('err_entry_fee_req'); ?>',
                    },                    
                    MAP: {
                        required: '<?php echo $this->lang->line('err_MAP_req'); ?>',
                    },
                    game_id: {
                        required: '<?php echo $this->lang->line('err_game_id_req'); ?>',
                    },
                    match_type: {
                        required: '<?php echo $this->lang->line('err_match_type_req'); ?>',
                    },
                    number_of_position: {
                        required: '<?php echo $this->lang->line('err_number_of_position_req'); ?>',
                        number: '<?php echo $this->lang->line('err_number_of_position_number'); ?>',
                        min: '<?php echo $this->lang->line('err_number_of_position_min1'); ?>',
                    },
                    match_banner: {
                        required: '<?php echo $this->lang->line('err_image_req'); ?>',
                        accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                    },
                    match_desc: {
                        required: '<?php echo $this->lang->line('err_match_desc_req'); ?>',
                    },
                }
                ,
                errorPlacement: function (error, element)
                {
                    if (element.is(":radio"))
                    {
                        element.parent().parent().parent().append(error);
                    } else if (element.is("textarea"))
                    {
                        element.parent().append(error);
                    } else
                    {
                        error.insertAfter(element);
                    }
                },
            });

            $('#entry_fee').keyup(function(){
                var entry_fee = $(this).val();

                if(entry_fee == 0 || entry_fee == '') {
                    $("#free").prop("checked", true);
                } else {
                    $("#paid").prop("checked", true);
                }
            })

            $( "#win_prize" ).blur(function() {                
                price_percent_check();
            });
            
            function price_percent_check() {
                var win_prize = $('#win_prize').val();
                
                if(win_prize != ''){                    
                    $.ajax({
                            url: '<?php echo base_url() . $this->path_to_view_admin ?>matches/price_percent_check',
                            type: 'post',
                            data: {'win_prize':win_prize},
                            success: function(data) {                    
                                $('#per_kill').val(data);                                        
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                }
            }

        </script>

<script type="text/javascript">
    function custom_template(obj){
            var data = $(obj.element).data();
            var text = $(obj.element).text();
            if(data && data['img_src']){
                img_src = data['img_src'];
                template = $("<div style=\"width:100%;clear: both;\" ><div style=\"vertical-align:middle;float:left;font-size:14pt\" ><img src=\"" + img_src + "\" style=\"display: block;height:50px;width:50px\"/></div><div style=\"vertical-align:middle;\" >&nbsp;&nbsp;" + text + "</div></div><div style=\"clear: both;\"></div>");
                return template;
            }
        }
    var options = {
        'templateSelection': custom_template,
        'templateResult': custom_template,
        'placeholder': "Select a Banner",
    }
    $('#id_select2_example').select2(options);    

</script>
<style>
    .select2-container--default .select2-selection--single{
        height:53px;
    }
</style>
    </body>
</html>