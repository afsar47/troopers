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
        <main class="bm-full-width bm-full-height">
            <div class="container-fluid">
                <div class="row d-flex">
                    <div class="col-xl-4 col-left">
                        <div class="bm-modal">
                            <div class="bm-mdl-header">
                                <a href="<?php echo base_url() . $this->path_to_default . 'play/matches/' . $match['game_id']; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a>
                                <h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="modal fade" id="myModal" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="" method="POST" id="modal-frm" >                                           
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title text-black"><?php echo $this->lang->line('text_edit_player_name'); ?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" id="match_join_member_id" name="match_join_member_id" class="form-control">
                                                <input type="hidden" id="match_id" name="match_id" value="<?php echo $this->uri->segment('4'); ?>" class="form-control">                                            
                                                <?php echo $this->lang->line('text_player_name'); ?>
                                                <input type="text" id="pubg_id" name="pubg_id" class="form-control">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" id="submit_btn" value="change_player_name" name="submit" class="btn btn-primary"><?php echo $this->lang->line('text_btn_save'); ?></button>
                                                <button type="button" id="close_btn" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('text_btn_close'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php
                            if (isset($match['image_name']) && $match['image_name'] != "") {
                                $result_match_img = base_url() . $this->select_image . 'thumb/253x90_' . $match['image_name'];
                            } elseif (isset($match['match_banner']) && $match['match_banner'] != "") {
                                $result_match_img = base_url() . $this->match_banner_image . 'thumb/1000x500_' . $match['match_banner'];
                            } else {
                                $result_match_img = base_url() . $this->game_image . 'thumb/1000x500_' . $match['game_image'];
                            }
                            ?>
                            <?php if ($match['match_status'] == 2) { ?>
                                <div class="bm-mdl-center bm-full-height pb-6">
                                    <div class="content-section">
                                        <div class="bm-content-listing">
                                            <div class="match-info">
                                                <img src="<?php echo $result_match_img; ?>" alt="match-banner" width="100%">                                
                                            </div>

                                            <div class="match-result">
                                                <h6 class="bm_text_lightgreen mt-3"><?php echo $match['match_name'] . $this->lang->line('text_for_macth_id') . $match['m_id']; ?></h6>
                                                <span class="btn btn-sm btn-white shadow m-1"><?php echo $this->lang->line('text_organised_on'); ?> : <strong> <?php echo $match['match_time']; ?> </strong></span>
                                                <span class="btn btn-sm btn-white shadow m-1"><?php echo $this->lang->line('text_winning_prize'); ?> : <strong><?php echo $match['win_prize'] . '(%)'; ?> </strong></span>
                                                <span class="btn btn-sm btn-white shadow m-1"><?php echo $this->lang->line('text_per_kill'); ?> : <strong> <?php echo $match['per_kill'] . '(%)'; ?> </strong></span>     
                                                <span class="btn btn-sm btn-white shadow m-1"><?php echo $this->lang->line('text_entry_fee'); ?> : <strong> <i style=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $match['entry_fee']; ?> </strong></span>      
                                                <div class="winner mt-3 shadow-sm">
                                                    <span class="btn-green rounded-top p-10 text-center f-18 btn-block"><?php echo $this->lang->line('text_winner'); ?></span>
                                                    <table class="table table-responsive">                                                    
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col"><?php echo $this->lang->line('text_player_name'); ?></th>
                                                                <th scope="col"><?php echo $this->lang->line('text_kills'); ?></th>
                                                                <th scope="col"><?php echo $this->lang->line('text_winning'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-black">
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
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="f-result mt-3 shadow-sm">
                                                    <span class="btn-green rounded-top p-10 text-center f-18 btn-block"><?php echo $this->lang->line('text_full_result'); ?></span>
                                                    <table class="table table-responsive">                                                    
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col"><?php echo $this->lang->line('text_player_name'); ?></th>
                                                                <th scope="col"><?php echo $this->lang->line('text_kills'); ?></th>
                                                                <th scope="col"><?php echo $this->lang->line('text_winning'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-black">
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
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="bm-mdl-center bm-full-height">
                                    <div class="bm-content-listing">
                                        <div class="match-info">
                                            <img src="<?php echo $result_match_img; ?>" alt="match-banner" width="100%">                                
                                        </div>
                                        <div class="tab-section game-info">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link active text-uppercase" data-toggle="tab" href="#description"><?php echo $this->lang->line('text_description'); ?></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link text-uppercase" data-toggle="tab" href="#joinmember"><?php echo $this->lang->line('text_joined_member'); ?></a>
                                                </li>
                                            </ul>
                                            <div class="tab-content ongoing-match">
                                                <div id="description" class="container tab-pane active">
                                                    <div class="content-section">
                                                        <div class=" bm-content-listing text-black" >
                                                            <h6 class="bm_text_lightgreen"><?php echo $match['match_name'] . $this->lang->line('text_for_macth_id') . $match['m_id']; ?></h6>
                                                            <span class="btn btn-sm btn-white shadow m-1"><?php echo $this->lang->line('text_team'); ?> : <strong><?php echo $match['type']; ?></strong> </span>
                                                            <span class="btn btn-sm btn-white shadow m-1"><?php echo $this->lang->line('text_entry_fee'); ?> : <strong><i style=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $match['entry_fee']; ?></strong>  </span>
                                                            <span class="btn btn-sm btn-white shadow m-1"><?php echo $this->lang->line('text_match_type'); ?> : <strong><?php echo ($match['match_type'] == 0) ? $this->lang->line('text_paid') : $this->lang->line('text_free'); ?></strong> </span>
                                                            <span class="btn btn-sm btn-white shadow m-1"><?php echo $this->lang->line('text_map'); ?> : <strong><?php echo $match['MAP']; ?></strong> </span>
                                                            <span class="btn btn-sm btn-white shadow m-1"><?php echo $this->lang->line('text_match_schedule'); ?> : <strong><?php echo $match['match_time']; ?></strong> </span>                                                           
                                                            <?php                                                            
                                                            if (!empty($join_member_data)) {
                                                                ?>
                                                                <div class="f-result mt-3 shadow-sm rounded-0">
                                                                    <table class="table table-responsive">                                                    
                                                                        <thead class="btn-green">
                                                                            <tr>
                                                                                <th><?php echo $this->lang->line('text_team'); ?></th>
                                                                                <th><?php echo $this->lang->line('text_position'); ?></th>
                                                                                <th><?php echo $this->lang->line('text_player_name'); ?></th>
                                                                            </tr>
                                                                        </thead>    
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
                                                                                    <?php // echo $join_member->pubg_id; ?>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </table>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if ($match['join_status'] && $match['room_description']) {
                                                                ?>
                                                                <span class="copied text-white bg-black rounded px-2" style="position: absolute;left: 35%;top: 30%;z-index: 10;"></span>
                                                                <h6 class="bm_text_lightgreen"><?php echo $this->lang->line('text_room_details'); ?></h6>
                                                                <?php echo $match['room_description']; ?>
                                                            <?php
                                                            }
                                                            ?>
                                                            <h6 class="bm_text_lightgreen mt-3"><?php echo $this->lang->line('text_prize_details'); ?></h6>
                                                            <span class="btn btn-sm btn-white shadow m-1"><?php echo $this->lang->line('text_winning_prize'); ?> : <strong><?php echo $match['win_prize'] . '(%)'; ?></strong></span>
                                                            <span class="btn btn-sm btn-white shadow m-1"><?php echo $this->lang->line('text_per_kill'); ?> : <strong><?php echo $match['per_kill'] . '(%)'; ?></strong></span>
                                                            <?php if ($match['match_sponsor'] != '') { ?>
                                                                <h6 class="bm_text_lightgreen mt-3"><?php echo $this->lang->line('text_match_sponser'); ?></h6>
                                                                <span> <?php echo $match['match_sponsor']; ?></span>
                                                                <br>
                                                            <?php } ?>
                                                            <h6 class="bm_text_lightgreen mt-3"><?php echo $this->lang->line('text_about_match'); ?></h6>
                                                            <span><?php echo $match['match_desc']; ?></span>
                                                            <?php 
                                                                if ($match['join_status']) {
                                                            ?>
                                                            <h6 class="bm_text_lightgreen mt-3"><?php echo $this->lang->line('text_match_private_description'); ?></h6>
                                                            <?php
                                                                    echo $match['match_private_desc'];                                                                                                                                                                            
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="joinmember" class="container tab-pane p-0">
                                                    <div class="content-section">
                                                        <div class="bm-content-listing text-black" >
                                                            <ul class="list-unstyled member-list">
                                                                <?php
                                                                $i = 0;
                                                                foreach ($match_participate_data as $match_participate) {
                                                                    ?>
                                                                    <li><?php echo ++$i . ' . <strong class="pl-3">' . $match_participate->pubg_id . '</strong>'; ?></li>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($match['match_status'] == 3) { ?>
                                    <div class="bm-mdl-footer text-white">
                                        <a href="<?php echo $match['match_url']; ?>" target="_blank" class="btn btn-sm btn-block f-18 btn-lightpink text-uppercase"> <?php echo $this->lang->line('text_Spactate'); ?>  </a>
                                    </div>
                                <?php } else if ($match['match_status'] == 1) { ?>
                                    <div class="bm-mdl-footer text-white">
                                        <?php if ($match['join_status']) { ?>
                                            <a style='cursor:auto;'  class="btn btn-sm btn-block f-18 btn-lightpink text-uppercase"> <?php echo $this->lang->line('text_already_joined'); ?> </a>
                                        <?php } else { ?>
                                            <a <?php echo ($match['no_of_player'] >= $match['number_of_position']) ? '' : "href='" . base_url() . $this->path_to_default . 'play/select_position/' . $match['m_id'] . "'"; ?> <?php if ($match['no_of_player'] >= $match['number_of_position']) echo "style='cursor:auto;'"; ?> class="btn btn-sm btn-block f-18 btn-lightpink text-uppercase">  <?php echo $this->lang->line('text_btn_join'); ?> </a>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                        </div>
                    </div>                    
                    <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
                </div>
            </div>
        </main>
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
                $("#myModal").modal('show');
                $(".modal-backdrop").addClass('d-none');
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

            $('#pubg_id').on('blur',function(e){
                if ( $('#pubg_id').val().indexOf('"') > -1 || $('#pubg_id').val().indexOf("'") > -1 ) {
                    $('#pubg_id').val('');
                    toastr.error( "Quote not Allow in Pubg name" );
                    return false;
                }
            });
        </script>
    </body>
</html>