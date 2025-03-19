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
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <form method="POST" id="modal-frm">                                           
                    <div class="modal-content">
                        <div class="modal-header " style="border-bottom: 1px solid lightgray">
                            <h4 class="modal-title">Join Member</h4>
                            <span class="pull-right view ml-auto mr-1 my-auto d-none modal-div" id="edit"><i class="fa fa-edit"></i></span>
                            <a class="pull-right view my-auto d-none" style="cursor: pointer;" id="delete" onClick="blankPosition();" ><i class="fa fa-trash-o"></i></a>
                        </div>
                        <div class="modal-body">
                            <div class="addedit d-none">
                                <input type="hidden" id="match_join_member_id" name="match_join_member_id" class="form-control">
                                <input type="hidden" id="team" name="team" class="form-control">
                                <input type="hidden" id="position" name="position" class="form-control">
                                <input type="hidden" id="match_id" name="match_id" value="<?php echo $this->uri->segment('4'); ?>" class="form-control">
                                <!--                                User Name
                                                                <input type="text" id="user_name" name="user_name" class="form-control">-->
                                <!--<br>-->
                                <?php echo $this->lang->line('text_game_id'); ?>
                                <input type="text" id="pubg_id" name="pubg_id" class="form-control">
                            </div>
                            <div class="view d-none"> 
                                <input type="hidden" id="view_match_join_member_id" class="form-control">
                                <!--                                User Name : 
                                                                <strong id="view_user_name"></strong>-->
                                <!--<br>-->
                                <?php echo $this->lang->line('text_game_id'); ?>
                                <strong id="view_pubg_id"></strong>
                            </div>
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid lightgray">
                            <button type="submit" id="submit_btn" value="joinnow" name="submit" class="btn btn-primary addedit d-none"><?php echo $this->lang->line('text_btn_join_now'); ?></button>
                            <button type="button" id="close_btn" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('text_btn_close'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="d-flex" id="wrapper">
            <?php $this->load->view($this->path_to_view_admin . 'sidebar'); ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><?php echo $match_detail['match_name'] . ' - ' . $this->lang->line('text_match') . '#' . $match_detail['m_id']; ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">                       
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_member_position'); ?></strong></div>
                                <div class="card-body" style="margin: 10px;text-align: center">
                                    <?php
                                    if ($match_type['type'] == 'solo' || $match_type['type'] == 'Solo') {
                                        $i = 1;
                                        $cnt = 0;
                                        $loop = ceil($match_type['number_of_position'] / 8);
                                        for ($x = 1; $x <= $loop; $x++) {
                                            ?>
                                            <div class="row">
                                                <div class="col-md-2 col-sm-2 col-xs-2 "></div>
                                                <?php
                                                for ($j = 1; $j <= 8; $j++) {
                                                    $cnt++;
                                                    if ($cnt > $match_type['number_of_position']) {
                                                        break;
                                                    }
                                                    ?>
                                                    <div class="col-md-1 col-sm-1 col-xs-1 bordered position pt-3" style="margin: 2px">
                                                        <?php
                                                        $position_match = FALSE;
                                                        $match_join_member_id = 0;
//                                                        $user_name = '';
                                                        foreach ($positions as $position) {
                                                            if ($position->position == $i) {
                                                                $position_match = $position->pubg_id;
                                                                $match_join_member_id = $position->match_join_member_id;
//                                                                $user_name = $position->user_name;
                                                            }
                                                        }
                                                        ?>
                                                        <input type="hidden" name="pubg_id" value="<?php echo $position_match; ?>">
                                                        <!--<input type="hidden" name="user_name" value="<?php echo $user_name; ?>">-->
                                                        <input type="hidden" name="match_join_member_id" value="<?php echo $match_join_member_id; ?>">
                                                        <?php
                                                        if ($position_match) {
                                                            ?>
                                                            <div style="" class="modal-div" data-toggle="modal" data-target="#myModal" data-id="<?php echo '1_' . $cnt . '_view'; ?>"><?php echo $position_match; ?></div>
                                                             <!--<a href="<?php echo base_url() . $this->path_to_view_admin . 'matches/delete_join_member/' . $match_join_member_id; ?>" onclick="return confirm('Are you sure to blank this position?')"><?php echo $position_match; ?></a>-->
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <div style="min-height: 50px;" class="modal-div" data-toggle="modal" data-target="#myModal" data-id="<?php echo '1_' . $cnt . '_add'; ?>"></div>
                                                            <?php
                                                        }
                                                        ?>                                                            
                                                    </div>
                                                    <?php
                                                    $i++;
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                    } elseif ($match_type['type'] == 'duo' || $match_type['type'] == 'Duo') {
                                        ?>
                                        <div class="row">
                                            <?php for ($x = 1; $x <= 4; $x++) { ?>
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;padding: 0;"><?php echo $this->lang->line('text_team'); ?></div>                                                    
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;padding: 0;"> <?php echo $this->lang->line('text_team_A'); ?></div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;padding: 0;"> <?php echo $this->lang->line('text_team_B'); ?></div>
                                            <?php } ?>
                                        </div>
                                        <?php
                                        $cnt = 1;
                                        $loop = ceil($match_type['number_of_position'] / 8);
                                        for ($x = 1; $x <= $loop; $x++) {
                                            ?>
                                            <div class="row">
                                                <?php
                                                for ($z = 1; $z <= 4; $z++) {
                                                    if ($cnt > ceil($match_type['number_of_position'] / 2))
                                                        break;
                                                    ?>                                                   
                                                    <div class="col-md-1 col-sm-1 col-xs-1 bordered " style="font-weight: 600;">  
                                                        <div class="player_name d-inline-block">
                                                            <?php echo 'Team' . $cnt; ?>
                                                        </div>
                                                    </div>
                                                    <?php for ($j = 1; $j <= 2; $j++) { ?>
                                                        <div class="col-md-1 col-sm-1 col-xs-1 bordered position">
                                                            <div class="team_name p-3 pull-left border"> <?php echo ($j == 1) ? 'A' : 'B'; ?></div>
                                                            <div class="player_name d-block">
                                                                <?php
                                                                $position_match = FALSE;
                                                                $match_join_member_id = 0;
//                                                                $user_name = '';
                                                                foreach ($positions as $position) {
                                                                    if ($position->team == $cnt && $position->position == $j) {
                                                                        $position_match = $position->pubg_id;
                                                                        $match_join_member_id = $position->match_join_member_id;
//                                                                        $user_name = $position->user_name;
                                                                    }
                                                                }
                                                                ?>
                                                                <input type="hidden" name="pubg_id" value="<?php echo $position_match; ?>">
                                                                <!--<input type="hidden" name="user_name" value="<?php echo $user_name; ?>">-->
                                                                <input type="hidden" name="match_join_member_id" value="<?php echo $match_join_member_id; ?>">
                                                                <?php
                                                                if ($position_match) {
                                                                    ?>
                                                                    <div style="" class="modal-div" data-toggle="modal" data-target="#myModal" data-id="<?php echo $cnt . '_' . $j . '_view'; ?>"><?php echo $position_match; ?></div>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <div style="min-height: 50px;" class="modal-div" data-toggle="modal" data-target="#myModal" data-id="<?php echo $cnt . '_' . $j . '_add'; ?>"></div>
                                                                    <?php
                                                                }
                                                                ?> 
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>                                                
                                                    <?php
                                                    $cnt++;
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                    } elseif ($match_type['type'] == 'squad' || $match_type['type'] == 'Squad') {
                                        ?>
                                        <div class="row">
                                            <?php
                                            for ($x = 1; $x <= 2; $x++) {
                                                if ($x % 2 == 1) {
                                                    ?>
                                                    <div class="col-md-1 col-sm-1 col-xs-1"></div>     
                                                <?php } ?>
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;margin: 1px;padding: 0;"><?php echo $this->lang->line('text_team'); ?></div>                                                    
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;margin: 1px;padding: 0;"><?php echo $this->lang->line('text_team_A'); ?></div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;margin: 1px;padding: 0;"><?php echo $this->lang->line('text_team_B'); ?></div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;margin: 1px;padding: 0;"><?php echo $this->lang->line('text_team_C'); ?></div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;margin: 1px;padding: 0;"><?php echo $this->lang->line('text_team_D'); ?></div>
                                            <?php } ?>
                                        </div>
                                        <?php
                                        $cnt = 1;
                                        $loop = ceil($match_type['number_of_position'] / 4);
                                        for ($x = 1; $x <= $loop; $x++) {
                                            ?>
                                            <div class="row">
                                                <?php
                                                for ($z = 1; $z <= 2; $z++) {
                                                    if ($cnt > ceil($match_type['number_of_position'] / 4)) {
                                                        break;
                                                    }
                                                    if ($cnt % 2 == 1) {
                                                        ?>
                                                        <div class="col-md-1 col-sm-1 col-xs-1"></div>     
                                                    <?php } ?>       
                                                    <div class="col-md-1 col-sm-1 col-xs-1 bordered" style="font-weight: 600;margin: 1px">     
                                                        <div class="player_name d-inline-block">
                                                            <?php echo 'Team' . $cnt; ?>
                                                        </div>
                                                    </div>
                                                    <?php for ($j = 1; $j <= 4; $j++) { ?>
                                                        <div class="col-md-1 col-sm-1 col-xs-1 bordered position" style="margin: 1px">
                                                            <div class="team_name p-3 pull-left border"> <?php if ($j == 1) echo 'A';elseif ($j == 2) echo 'B';elseif ($j == 3) echo 'C';elseif ($j == 4) echo 'D'; ?></div>
                                                            <div class="player_name d-block">
                                                                <?php
                                                                $position_match = FALSE;
                                                                $match_join_member_id = 0;
//                                                                $user_name = '';
                                                                foreach ($positions as $position) {
                                                                    if ($position->team == $cnt && $position->position == $j) {
                                                                        $position_match = $position->pubg_id;
                                                                        $match_join_member_id = $position->match_join_member_id;
//                                                                        $user_name = $position->user_name;
                                                                    }
                                                                }
                                                                ?>
                                                                <input type="hidden" name="pubg_id" value="<?php echo $position_match; ?>">
                                                                <!--<input type="hidden" name="user_name" value="<?php echo $user_name; ?>">-->
                                                                <input type="hidden" name="match_join_member_id" value="<?php echo $match_join_member_id; ?>">
                                                                <?php
                                                                if ($position_match) {
                                                                    ?>
                                                                    <div style="" class="modal-div" data-toggle="modal" data-target="#myModal" data-id="<?php echo $cnt . '_' . $j . '_view'; ?>"><?php echo $position_match; ?></div>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <div style="min-height: 50px;" class="modal-div" data-toggle="modal" data-target="#myModal" data-id="<?php echo $cnt . '_' . $j . '_add'; ?>"></div>
                                                                    <?php
                                                                }
                                                                ?> 
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                    <?php
                                                    $cnt++;
                                                }
                                                ?></div>
                                            <?php
                                        }
                                    } elseif ($match_type['type'] == 'squad5' || $match_type['type'] == 'Squad5') {
                                        ?>
                                        <div class="row">
                                            <?php
                                            for ($x = 1; $x <= 2; $x++) {
                                                ?>
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;padding: 0;"><?php echo $this->lang->line('text_team'); ?></div>                                                    
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;padding: 0;"><?php echo $this->lang->line('text_team_A'); ?></div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;padding: 0;"><?php echo $this->lang->line('text_team_B'); ?></div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;padding: 0;"><?php echo $this->lang->line('text_team_C'); ?></div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;padding: 0;"><?php echo $this->lang->line('text_team_D'); ?></div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 team-header" style="text-align: center;font-weight: 600;padding: 0;"><?php echo $this->lang->line('text_team_E'); ?></div>
                                            <?php } ?>
                                        </div>
                                        <?php
                                        $cnt = 1;
                                        $loop = ceil($match_type['number_of_position'] / 5);
                                        for ($x = 1; $x <= $loop; $x++) {
                                            ?>
                                            <div class="row">
                                                <?php
                                                for ($z = 1; $z <= 2; $z++) {
                                                    if ($cnt > ceil($match_type['number_of_position'] / 5)) {
                                                        break;
                                                    }
                                                    ?>
                                                    <div class="col-md-1 col-sm-1 col-xs-1 bordered" style="font-weight: 600;">     
                                                        <div class="player_name d-inline-block">
                                                            <?php echo 'Team' . $cnt; ?>
                                                        </div>
                                                    </div>
                                                    <?php for ($j = 1; $j <= 5; $j++) { ?>
                                                        <div class="col-md-1 col-sm-1 col-xs-1 bordered position">
                                                            <div class="team_name p-3 pull-left border"> <?php if ($j == 1) echo 'A';elseif ($j == 2) echo 'B';elseif ($j == 3) echo 'C';elseif ($j == 4) echo 'D';elseif ($j == 5) echo 'E'; ?></div>
                                                            <div class="player_name d-block">
                                                                <?php
                                                                $position_match = FALSE;
                                                                $match_join_member_id = 0;
//                                                                $user_name = '';
                                                                foreach ($positions as $position) {
                                                                    if ($position->team == $cnt && $position->position == $j) {
                                                                        $position_match = $position->pubg_id;
                                                                        $match_join_member_id = $position->match_join_member_id;
//                                                                        $user_name = $position->user_name;
                                                                    }
                                                                }
                                                                ?>
                                                                <input type="hidden" name="pubg_id" value="<?php echo $position_match; ?>">
                                                                <!--<input type="hidden" name="user_name" value="<?php echo $user_name; ?>">-->
                                                                <input type="hidden" name="match_join_member_id" value="<?php echo $match_join_member_id; ?>">
                                                                <?php
                                                                if ($position_match) {
                                                                    ?>
                                                                    <div style="" class="modal-div" data-toggle="modal" data-target="#myModal" data-id="<?php echo $cnt . '_' . $j . '_view'; ?>"><?php echo $position_match; ?></div>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <div style="min-height: 50px;" class="modal-div" data-toggle="modal" data-target="#myModal" data-id="<?php echo $cnt . '_' . $j . '_add'; ?>"></div>
                                                                    <?php
                                                                }
                                                                ?> 
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                    <?php
                                                    $cnt++;
                                                }
                                                ?></div>
                                            <?php
                                        }
                                    }
                                    ?>                         
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
            function blankPosition(link) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you sure to blank this position?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = link;
                    }
                });
            }
            $(document).ready(function () {
                $(".modal-div").css("cursor", "pointer");
                $(".modal-div").click(function () {
                    $(".modal-div").css("cursor", "pointer");
                    var val = $(this).data('id');
                    val = val.split('_');
                    $('label .error').css('display', 'none');
                    $('#team').val(val[0]);
                    $('#position').val(val[1]);
                    match_join_member_id = $(this).prev('input[name="match_join_member_id"]').val();
//                    user_name = $(this).prev().prev().val();
                    pubg_id = $(this).prev().prev().val();
                    if (val[2] == 'add') {
//                        $('#user_name').removeAttr("readonly");
//                        $('#user_name').val('');
                        $('#pubg_id').val('');
                        $('#match_join_member_id').val('');
                        $('.view').addClass("d-none");
                        $('.addedit').removeClass("d-none");
                    } else if (val[2] == 'edit') {
//                        $('#user_name').val('');
                        $('#pubg_id').val('');
                        match_join_member_id = $(".modal-body .view #view_match_join_member_id").val();
                        pubg_id = $(".modal-body .view #view_pubg_id").html();
//                        user_name = $(".modal-body .view #view_user_name").html();
//                        $('#user_name').attr("readonly", "readonly");
//                        $('#user_name').val(user_name);
                        $('#pubg_id').val(pubg_id);
                        $('#match_join_member_id').val(match_join_member_id);
                        $('.addedit').removeClass("d-none");
                        $('.view').addClass("d-none");
                    } else if (val[2] == 'view') {
                        $('#view_pubg_id').html('');
//                        $('#view_user_name').html('');
                        $('#view_match_join_member_id').html('');
                        $("#view_match_join_member_id").val(match_join_member_id);
                        $("#view_pubg_id").html(pubg_id);
//                        $("#view_user_name").html(user_name);
                        $("#delete").attr("onclick", "blankPosition('<?php echo base_url() . $this->path_to_view_admin . 'matches/delete_join_member/'; ?>" + match_join_member_id + "')");
//                        $("#delete").attr("href", "<?php echo base_url() . $this->path_to_view_admin . 'matches/delete_join_member/'; ?>" + match_join_member_id);
                        $('.addedit').addClass("d-none");
                        $('.view').removeClass("d-none");
                        $("#edit").attr("data-id", val[0] + "_" + val[2] + "_edit");
                        $(".modal-div").css("cursor", "pointer");
                    }
                });
                $("#modal-frm").validate({
                    rules: {
                        pubg_id: {
                            required: true,
                        },
//                        user_name: {
//                            required: true,
//                            remote: "<?php echo base_url() . $this->path_to_view_admin; ?>matches/checkUsername",
//                        },
                    },
                    messages: {
                        pubg_id: {
                            required: "Please enter Pubg ID",
                        },
//                        user_name: {
//                            required: "Please enter User name",
//                            remote: 'User name not exist'
//                        },
                    },
                    submitHandler: function (form) {
//                        $("#submit_btn").attr("disabled", true);
<?php if ($this->system->admin_user != 0) { ?>
                            if ($("#match_join_member_id").val() == '') {
                                
                                $.ajax({
                                    url: '<?php echo base_url() . $this->path_to_view_admin; ?>matches/getMemberDetail',
                                    data: {"member_id": <?php echo $this->system->admin_user; ?>},
                                    success: function (res) {
                                        var member = JSON.parse(res);
                                        $.ajax({
                                            type: 'post',
                                            url: '<?php echo base_url() . 'api/join_match_process'; ?>',
                                            data: ({teamposition: [{pubg_id: $("#pubg_id").val(), team: $("#team").val(), position: $("#position").val()}], match_id: $("#match_id").val(), member_id: <?php echo $this->system->admin_user; ?>, submit: 'joinnow'}),
                                            "Content-Type": "application/json",
                                            headers: {
                                                "Authorization": "Bearer " + member.api_token
                                            },
                                            success: function (res) {
                                                var obj = JSON.parse(res);
                                                if (!obj.status)
                                                {
                                                    $("#submit_btn").attr("disabled", false);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: '<?php echo $this->lang->line('text_oops'); ?>',
                                                        text: obj.message,
                                                    });
                                                } else {
                                                    //                                            $('#myModal').modal('hide');
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: '<?php echo $this->lang->line('text_success'); ?>',
                                                        text: obj.message,
                                                    }).then((result) => {
                                                        window.location.href = "<?php echo base_url() . $this->path_to_view_admin; ?>matches/member_position/<?php echo $this->uri->segment('4'); ?>";
                                                                                                });
                                                                                            }
                                                                                        }
                                                                                    });
                                                                                }
                                                                            });
                                                                        } else {
                                                                            $.ajax({
                                                                                type: 'post',
                                                                                url: '<?php echo base_url() . $this->path_to_view_admin; ?>matches/edit_member_join',
                                                                                data: {"user_name": $("#user_name").val(), "match_join_member_id": $("#match_join_member_id").val(), pubg_id: $("#pubg_id").val(), match_id: $("#match_id").val()},
                                                                                success: function (res) {
                                                                                    var obj = JSON.parse(res);
                                                                                    if (!obj.status)
                                                                                    {
                                                                                        $("#submit_btn").attr("disabled", false);
                                                                                        Swal.fire({
                                                                                            icon: 'error',
                                                                                            title: '<?php echo $this->lang->line('text_oops'); ?>',
                                                                                            text: obj.message,
                                                                                        });
                                                                                    } else {
                                                                                        Swal.fire({
                                                                                            icon: 'success',
                                                                                            title: '<?php echo $this->lang->line('text_success'); ?>',
                                                                                            text: obj.message,
                                                                                        }).then((result) => {
                                                                                            window.location.href = "<?php echo base_url() . $this->path_to_view_admin; ?>matches/member_position/<?php echo $this->uri->segment('4'); ?>";

                                                                                                                            });
                                                                                                                        }
                                                                                                                    }
                                                                                                                });
                                                                                                            }
    <?php
} else {
    $this->session->set_flashdata('error', 'Please select admin user!');
    ?>
                                                                                                            window.location.href = "<?php echo base_url() . $this->path_to_view_admin; ?>appsetting";
<?php } ?>
                                                                                                    }
                                                                                                });
                                                                                            });
        </script>
    </body>
</html>