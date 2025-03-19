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
                        <h1 class="h2"><?php echo $this->lang->line('text_one_signal_notification'); ?></h1>
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
                                <div class="card-header"><strong><?php echo $this->lang->line('text_one_signal_notification'); ?></strong></div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <form method="POST" id="validate" enctype="multipart/form-data" action="<?php echo base_url() . $this->path_to_view_admin ?>custom_notification/">     
                                            <div class="row">   
                                                <div class="form-group col-6">
                                                    <label for="send_to"><?php echo $this->lang->line('text_send_to'); ?></label>
                                                     <select name="send_to" class="form-control send_to">
                                                        <option value="all" <?php if(isset($send_to) && $send_to == 'all') echo 'selected'; ?>><?php echo $this->lang->line('text_all_user'); ?></option>
                                                        <option value="single_member" <?php if(isset($send_to) && $send_to == 'single_member') echo 'selected'; ?>><?php echo $this->lang->line('text_single_member'); ?></option>
                                                        <option value="multi_member" <?php if(isset($send_to) && $send_to == 'multi_member') echo 'selected'; ?>><?php echo $this->lang->line('text_multi_member'); ?></option> 
                                                         <option value="match" <?php if(isset($send_to) && $send_to == 'match') echo 'selected'; ?>>Match</option> 
                                                     </select>
                                                </div>
                                                
                                                <div class="form-group col-6 <?php if($send_to != 'match') echo 'd-none'; ?> match">
                                                    <label for="match">Select Match</label>
                                                    <select name="match" class="form-control select_box">    
                                                        <option value=""><?php echo $this->lang->line('text_select'); ?></option>                                                    
                                                        <?php
                                                            foreach($game_list as $gem){
                                                        ?>
                                                        <option value="<?php echo $gem['m_id']?>" <?php if(isset($game_list) && $game_list == $gem['m_id']) echo 'selected'; ?>><?php echo $gem['match_name']; ?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                    <?php echo form_error('match', '<em style="color:red">', '</em>'); ?> 
                                                </div> 
                                                
                                                <div class="form-group col-6 <?php if($send_to != 'single_member') echo 'd-none'; ?> single_member">
                                                    <label for="member"><?php echo $this->lang->line('text_member'); ?></label>
                                                    <select name="member" class="form-control select_box">    
                                                        <option value=""><?php echo $this->lang->line('text_select'); ?></option>                                                    
                                                        <?php
                                                            foreach($member_list as $mem){
                                                        ?>
                                                        <option value="<?php echo $mem['player_id']?>" <?php if(isset($member) && $member == $mem['player_id']) echo 'selected'; ?>><?php echo $mem['user_name']; ?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                    <?php echo form_error('member', '<em style="color:red">', '</em>'); ?> 
                                                </div> 
                                                <div class="form-group col-6 <?php if($send_to != 'multi_member') echo 'd-none'; ?> multi_member">
                                                    <label for="member"><?php echo $this->lang->line('text_member'); ?></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">From</span>
                                                        </div>
                                                        <input type="number" id="sendtype-2-input" name="multi_member_from" class="form-control text-center" value="<?php if (isset($multi_member_from)) echo $multi_member_from; ?>">
                                                        <div class="input-group-prepend">
                                                         <span class="input-group-text">to</span>
                                                         </div>
                                                        <input type="number" id="sendtype-2-input" name="multi_member_to" class="form-control text-center" value="<?php if (isset($multi_member_to)) echo $multi_member_to; ?>">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Members</span>
                                                        </div>
                                                    </div>                                                    
                                                    <?php echo form_error('multi_member_from', '<em style="color:red">', '</em>'); ?><br/> 
                                                    <?php echo form_error('multi_member_to', '<em style="color:red">', '</em>'); ?> 
                                                </div>                                            
                                                <div class="form-group col-12">
                                                    <label for="notification_title"><?php echo $this->lang->line('text_title'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <input id="game_rules" type="text" <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'readonly';
                                                    }
                                                    ?> class="form-control " name="notification_title" value="<?php if (isset($notification_title)) echo $notification_title;
                                                           else echo 'Welcome to ' . $company_name; ?>" >
                                                    <?php echo form_error('notification_title', '<em style="color:red">', '</em>'); ?> 
                                                </div>                                                
                                                <div class="form-group col-12">
                                                    <label for="message"><?php echo $this->lang->line('text_message'); ?><span class="required" aria-required="true"> * </span></label>
                                                    <textarea id="message" type="text"  <?php
                                                    if ($this->system->demo_user == 1) {
                                                        echo 'readonly';
                                                    }
                                                    ?> class="form-control" name="message" ><?php if (isset($message)) echo $message;
                                                              else echo $company_name . ' push notification test'; ?></textarea>
                                                    <?php echo form_error('message', '<em style="color:red">', '</em>'); ?> 
                                                </div>                                                  
                                                <div class="form-group col-6">
                                                    <label for="notification_image"><?php echo $this->lang->line('text_image'); ?></label><br>
                                                    <input id="image" type="file" class="file-input d-block" name="notification_image" >
                                                    <?php echo form_error('notification_image', '<em style="color:red">', '</em>'); ?>                                                    
                                                </div>   
                                            </div>  
                                            <div class="form-group text-center">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary ">   
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>custom_notification/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                    
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

            $('.send_to').change(function(){
                var send_to = $(this).val();
                if(send_to == 'all'){
                    $('.single_member').addClass('d-none');
                    $('.multi_member').addClass('d-none');
                    $('.match').addClass('d-none');
                    $('.select_box').val('');
                    $('.select_box').multiselect("refresh");
                } else if(send_to == 'single_member') {
                    $('.single_member').removeClass('d-none');
                    $('.multi_member').addClass('d-none');
                    $('.match').addClass('d-none');
                    $('select[name="multi_member"]').val('');
                    $('.select_box').multiselect("refresh");
                } else if(send_to == 'multi_member') {
                    $('.single_member').addClass('d-none');
                    $('.multi_member').removeClass('d-none');   
                    $('.match').addClass('d-none');
                    $('select[name="member"]').val('');
                    $('.select_box').multiselect("refresh");
                } else if(send_to == 'match') {
                    
                    $('.single_member').addClass('d-none');
                    $('.multi_member').addClass('d-none'); 
                     $('.match').removeClass('d-none'); 
                    $('select[name="member"]').val('');
                    $('.select_box').multiselect("refresh");
                    
                }
                
            });
          
            $('.select_box').multiselect({
                maxHeight: 200,
                nonSelectedText: 'Select Member',
                buttonWidth: '100%',
                maxWidth: '100%',
                width:'100%',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,  
                buttonClass: 'btn btn-outline-dark',                                               
                templates: {
                    filter: '<li class="multiselect-item multiselect-filter"><input class="form-control multiselect-search" type="text" /></li>',                    
                }         
            });
           

            $("#validate11").validate({
                rules: {
                    notification_title: {
                        required: true,
                    },
                    message: {
                        required: true,
                    },                    
                    notification_image: {
                        accept: "jpg|png|jpeg",
//                        filesize: 2000000,
                    },
                },
                messages: {
                    notification_title: {
                        required: '<?php echo $this->lang->line('err_notification_title_req'); ?>',
                    },
                    message: {
                        required: '<?php echo $this->lang->line('err_message_req'); ?>',
                    },                    
                    notification_image: {
                        accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                    },
                }
                ,
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