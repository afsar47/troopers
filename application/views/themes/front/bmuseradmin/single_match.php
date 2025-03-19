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
        <?php $this->load->view($this->path_to_view_default . 'header'); ?>
    </head>
    <body>
        <?php $this->load->view($this->path_to_view_default . 'header_body'); ?>

        <div class="d-flex" id="wrapper">
            <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">
                            <form method="POST" id="modal-frm" action="">                                           
                                <div class="modal-content">
                                    <div class="modal-header " style="border-bottom: 1px solid lightgray">
                                        <h4 class="modal-title"><?php echo $this->lang->line('text_edit_player_name'); ?></h4>
                                        <span class="pull-right view ml-auto mr-1 my-auto d-none modal-div" id="edit"><i class="fa fa-edit"></i></span>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="match_join_member_id" name="match_join_member_id" class="form-control">
                                        <input type="hidden" id="match_id" name="match_id" value="<?php echo $this->uri->segment('4'); ?>" class="form-control">   
                                        <?php echo $this->lang->line('text_player_name'); ?>
                                        <input type="text" id="pubg_id" name="pubg_id" class="form-control">
                                    </div>
                                    <div class="modal-footer" style="border-top: 1px solid lightgray">
                                        <button type="submit" id="submit_btn" value="change_player_name" name="submit" class="btn btn-primary"><?php echo $this->lang->line('text_btn_save'); ?></button>
                                        <button type="button" id="close_btn" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('text_btn_close'); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h3><?php echo $breadcrumb_title; ?></h3>
                        <div class="btn-toolbar mb-2 mb-md-0">                          
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-lg-12">                            
                            <?php
                            if (isset($match['match_banner']) && $match['match_banner'] != "") {
                                $result_match_img = base_url() . $this->match_banner_image . 'thumb/1000x500_' . $match['match_banner'];
                            } else {
                                $result_match_img = base_url() . $this->game_image . 'thumb/1000x500_' . $match['game_image'];
                            }
                            ?>
                            <img src="<?php echo $result_match_img; ?>" class="img-fluid img-responsive" >
                            <?php if ($match['match_status'] == 2) { ?>
                                <div class="card my-3">
                                    <div class="card-body dashboard-tabs p-0 bg-lightgray" >
                                        <div class="border-md-right flex-grow-1 p-3">
                                            <h6 class="text-lightgreen"><?php echo $match['match_name'] . $this->lang->line('text_for_macth_id') . $match['m_id']; ?></h6>
                                            <span class="d-inline-block bg-white rounded px-2 mx-1 mb-2 py-1 box-shadow"><?php echo $this->lang->line('text_organised_on'); ?> : <strong> <?php echo $match['match_time']; ?> </strong></span>                                   
                                            <span class="d-inline-block bg-white rounded px-2 mx-1 mb-2 py-1 box-shadow"><?php echo $this->lang->line('text_winning_prize'); ?> : <strong><i style="color:#000;"> <?php echo $this->functions->getPoint(); ?></i> <?php echo $match['win_prize']; ?> </strong></span>                                                
                                            <span class="d-inline-block bg-white rounded px-2 mx-1 mb-2 py-1 box-shadow"><?php echo $this->lang->line('text_per_kill'); ?> : <strong> <i style="color:#000;"><?php echo $this->functions->getPoint(); ?></i> <?php echo $match['per_kill']; ?> </strong></span>      
                                            <span class="d-inline-block bg-white rounded px-2 mx-1 mb-2 py-1 box-shadow"><?php echo $this->lang->line('text_entry_fee'); ?> : <strong> <i style="color:#000;"><?php echo $this->functions->getPoint(); ?></i> <?php echo $match['entry_fee']; ?> </strong></span>      
                                        </div>
                                        <div class="col-lg-12">
                                            <table class="table tr-bordered bg-white box-shadow">
                                                <caption class="bg-lightgreen text-white text-center" style="caption-side: top;"><?php echo $this->lang->line('text_winner'); ?></caption>
                                                <tr class="bg-black text-white">
                                                    <th>#</th>
                                                    <th><?php echo $this->lang->line('text_player_name'); ?></th>
                                                    <th><?php echo $this->lang->line('text_kills'); ?></th>
                                                    <th><?php echo $this->lang->line('text_winning'); ?></th>
                                                </tr>
                                                <?php
                                                $i = 0;
                                                foreach ($match_result_data['match_winner'] as $match_winner) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo ++$i; ?></td>
                                                        <td><?php echo $match_winner->user_name; ?></td>
                                                        <td><?php echo $match_winner->killed; ?></td>
                                                        <td><?php echo $match_winner->total_win; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </table>

                                            <table class="table tr-bordered bg-white box-shadow">
                                                <caption class="bg-lightgreen text-white text-center" style="caption-side: top;"><?php echo $this->lang->line('text_full_result'); ?></caption>
                                                <tr class="bg-black text-white">
                                                    <th>#</th>
                                                    <th><?php echo $this->lang->line('text_player_name'); ?></th>
                                                    <th><?php echo $this->lang->line('text_kills'); ?></th>
                                                    <th><?php echo $this->lang->line('text_winning'); ?></th>
                                                </tr>
                                                <?php
                                                $i = 0;
                                                foreach ($match_result_data['full_result'] as $full_result) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo ++$i; ?></td>
                                                        <td><?php echo $full_result->user_name; ?></td>
                                                        <td><?php echo $full_result->killed; ?></td>
                                                        <td><?php echo $full_result->total_win; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="card my-3">
                                    <div class="card-body dashboard-tabs p-0 bg-lightgray" id="tabs-1">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="Description" aria-selected="true"><?php echo $this->lang->line('text_description'); ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="join_member-tab" data-toggle="tab" href="#join_member" role="tab" aria-controls="join_member" aria-selected="false"><?php echo $this->lang->line('text_joined_member'); ?></a>
                                            </li>
                                        </ul>
                                        <div class="tab-content py-0 px-0">
                                            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                                <div class="d-flex flex-wrap justify-content-xl-between">
                                                    <div class="border-md-right flex-grow-1 p-3">
                                                        <h6 class="text-lightgreen"><?php echo $match['match_name'] . $this->lang->line('text_for_macth_id') . $match['m_id']; ?></h6>
                                                        <span class="d-inline-block bg-white rounded px-2 mx-1 mb-2 py-1 box-shadow"><?php echo $this->lang->line('text_team'); ?> : <strong> <?php echo $match['type']; ?></strong></span>                                                
                                                        <span class="d-inline-block bg-white rounded px-2 mx-1 mb-2 py-1 box-shadow"><?php echo $this->lang->line('text_entry_fee'); ?> : <strong> <i style="color:#000;"><?php echo $this->functions->getPoint(); ?></i> <?php echo $match['entry_fee']; ?> </strong></span>      
                                                        <span class="d-inline-block bg-white rounded px-2 mx-1 mb-2 py-1 box-shadow"><?php echo $this->lang->line('text_match_type'); ?> : <strong> <?php echo ($match['match_type'] == 0) ? $this->lang->line('text_paid') : $this->lang->line('text_free'); ?> </strong></span>
                                                        <span class="d-inline-block bg-white rounded px-2 mx-1 mb-2 py-1 box-shadow"><?php echo $this->lang->line('text_map'); ?> : <strong> <?php echo $match['MAP']; ?> </strong></span>
                                                        <span class="d-inline-block bg-white rounded px-2 mx-1 mb-2 py-1 box-shadow"><?php echo $this->lang->line('text_match_schedule'); ?> : <strong> <?php echo $match['match_time']; ?> </strong></span>                                                        
                                                        <?php                                                        
                                                        if (!empty($join_member_data)) {
                                                            ?>
                                                            <table class="table tr-bordered bg-white box-shadow">
                                                                <tr class="bg-lightgreen text-white">
                                                                    <th><?php echo $this->lang->line('text_team'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_position'); ?></th>
                                                                    <th><?php echo $this->lang->line('text_player_name'); ?></th>
                                                                </tr>
                                                                <?php
                                                                $i = 0;
                                                                foreach ($join_member_data as $join_member) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $this->lang->line('text_team') . $join_member->team; ?></td>
                                                                        <td><?php echo $join_member->position; ?></td>
                                                                        <td>
                                                                            <input type="hidden" name="match_join_member_id" value="<?php echo $join_member->match_join_member_id; ?>">
                                                                            <div class="modal-div" data-toggle="modal" data-target="#myModal" data-id="<?php echo $join_member->pubg_id; ?>"> <?php echo $join_member->pubg_id; ?><i style="padding-left:10px;color: #000;" class="fa fa-pencil"></i></div>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </table>
                                                        <?php } ?>
                                                        <?php if ($match['join_status'] && $match['room_description']) {
                                                            ?>
                                                            <span class="copied text-white bg-black rounded px-2" style="position: absolute;left: 10%;top: 25%;z-index: 10;"></span>
                                                            <h6 class="text-lightgreen"><?php echo $this->lang->line('text_room_details'); ?></h6>
                                                            <?php echo $match['room_description']; ?>
                                                            <?php
                                                        }
                                                        ?>
                                                        <h6 class="text-lightgreen"><?php echo $this->lang->line('text_prize_details'); ?></h6>
                                                        <span class="d-inline-block bg-white rounded px-2 mx-1 mb-2 py-1 box-shadow"><?php echo $this->lang->line('text_winning_prize'); ?> : <strong> <i style="color:#000;"><?php echo $this->functions->getPoint(); ?></i> <?php echo $match['win_prize']; ?> </strong></span>                                                
                                                        <span class="d-inline-block bg-white rounded px-2 mx-1 mb-2 py-1 box-shadow"><?php echo $this->lang->line('text_per_kill'); ?> : <strong> <i style="color:#000;"><?php echo $this->functions->getPoint(); ?></i> <?php echo $match['per_kill']; ?> </strong></span>      
                                                        <?php if ($match['match_sponsor'] != '') { ?>
                                                            <h6 class="text-lightgreen mt-3"><?php echo $this->lang->line('text_match_sponser'); ?></h6>
                                                            <?php echo $match['match_sponsor']; ?>

                                                        <?php } ?>
                                                        <h6 class="text-lightgreen"><?php echo $this->lang->line('text_about_match'); ?></h6>
                                                        <?php echo $match['match_desc']; ?>
                                                        <?php 
                                                            if ($match['join_status']) {
                                                        ?>
                                                        <h6 class="text-lightgreen"><?php echo $this->lang->line('text_match_private_description'); ?></h6>
                                                        <?php
                                                                echo $match['match_private_desc'];                                                                                                                                                                            
                                                            }
                                                        ?>
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="tab-pane fade show " id="join_member" role="tabpanel" aria-labelledby="join_member-tab">
                                                <div class="d-flex flex-wrap justify-content-xl-between"> 
                                                    <div class="border-md-right flex-grow-1 p-3">
                                                        <div class="col-lg-12">
                                                            <table class="table tr-bordered">
                                                                <?php
                                                                $i = 0;
                                                                foreach ($match_participate_data as $match_participate) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo ++$i; ?></td>
                                                                        <td><strong><?php echo $match_participate->pubg_id; ?></strong></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php if ($match['match_status'] == 3) {
                                        ?>
                                        <div class="col-md-6">   
                                            <a href="<?php echo $match['match_url']; ?>" target="_blank" class="btn btn-sm btn-block btn-primary"> <?php echo $this->lang->line('text_Spactate'); ?></a>
                                        </div>
                                    <?php } elseif ($match['match_status'] == 1) {
                                        ?>

                                        <div class="col-md-6">   
                                            <?php if ($match['join_status']) { ?>
                                                <a style='cursor:auto;' class="btn btn-sm bg-primary text-white"><?php echo $this->lang->line('text_already_joined'); ?></a>
                                            <?php } else { ?>
                                                <a <?php echo ($match['no_of_player'] >= $match['number_of_position']) ? '' : "href='" . base_url() . $this->path_to_default . 'play/select_position/' . $match['m_id'] . "'"; ?> <?php if ($match['no_of_player'] >= $match['number_of_position']) echo "style='cursor:auto;'"; ?> class="btn btn-sm bg-primary text-white"> <?php echo $this->lang->line('text_btn_join'); ?> </a>

                                            <?php } ?>
                                        </div>
                                        <?php
                                    }
                                    ?> 
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
        <script>
            $(".modal-div").css("cursor", "pointer");
            $(".modal-div").click(function () {
                $(".modal-div").css("cursor", "pointer");
                var val = $(this).data('id');
                match_join_member_id = $(this).prev('input[name="match_join_member_id"]').val();
                $('#match_join_member_id').val(match_join_member_id);
                $('#pubg_id').val(val);
                $('.error').removeClass('error');
                $('label.error').remove();
            });
            function copyToClipboard(element) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).text()).select();
                document.execCommand("copy");
                $(".copied").text("Copied to clipboard").show().fadeOut(1200);
                $temp.remove();
            }
            $("#modal-frm").validate({
                rules: {
                    pubg_id: {
                        required: true,
                    },
                },
                messages: {
                    pubg_id: {
                        required: "<?php echo $this->lang->line('err_pubg_id_req'); ?>",
                    },
                },
            });
            
        </script>    
    </body>
</html>