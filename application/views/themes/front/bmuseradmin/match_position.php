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
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h3><?php echo $breadcrumb_title; ?></h3>
                        <div class="btn-toolbar mb-2 mb-md-0">                          
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-lg-12 text-center">                            
                            <form method="post" name="select-position-form" id="select-position-form" action="<?php echo base_url() . $this->path_to_default . 'play/select_position'; ?>">
                                <input type="hidden" name="match_id" value="<?php echo $match['m_id']; ?>">
                                <input type="hidden" name="type" value="<?php echo $match['type']; ?>">
                                <input type="hidden" name="pubg_id" value="<?php echo $match_position['pubg_id']; ?>">
                                <?php
                                if ($match['type'] == 'solo' || $match['type'] == 'Solo') {
                                    $i = 1;
                                    $cnt = 0;
                                    $team = 1;
                                    $loop = ceil($match['number_of_position'] / 8);
                                    for ($x = 1; $x <= $loop; $x++) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 col-xs-2 "></div>
                                            <?php
                                            for ($j = 1; $j <= 8; $j++) {
                                                $checked = '';
                                                $cnt++;
                                                if ($cnt > $match['number_of_position']) {
                                                    break;
                                                }
                                                ?>
                                                <div class="col-md-1 col-sm-1 col-xs-1 bordered position" style="margin: 2px">
                                                    <?php
                                                    foreach ($match_position['result'] as $position) {
                                                        if ($position['position'] == $i && $position['pubg_id'] != '') {
                                                            $checked = 'checked disabled';
                                                        }
                                                    }
                                                    ?> 
                                                    <div class="custom-control custom-checkbox mb-3">
                                                        <input type="checkbox" id="position<?php echo $team . '_' . $i; ?>" <?php echo ($checked == '') ? "name='position[]'" : ""; ?>  value="<?php echo $team . '_' . $i; ?>" <?php echo $checked; ?> class="custom-control-input">
                                                        <label class="custom-control-label" for="position<?php echo $team . '_' . $i; ?>" > </label>
                                                    </div>
                                                </div>
                                                <?php
                                                $i++;
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                } elseif ($match['type'] == 'duo' || $match['type'] == 'Duo') {
                                    ?>
                                    <div class="row">
                                        <?php for ($x = 1; $x <= 4; $x++) { ?>
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;padding: 0;">Team</div>                                                    
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;padding: 0;"> A </div>
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;padding: 0;"> B </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    $cnt = 1;
                                    $loop = ceil($match['number_of_position'] / 8);
                                    for ($x = 1; $x <= $loop; $x++) {
                                        ?>
                                        <div class="row">
                                            <?php
                                            for ($z = 1; $z <= 4; $z++) {
                                                if ($cnt > ceil($match['number_of_position'] / 2))
                                                    break;
                                                ?>                                                   
                                                <div class="col-md-1 col-sm-1 col-xs-1 bordered " style="font-weight: 600;">  
                                                    <div class="player_name d-inline-block">
                                                        <?php echo 'Team' . $cnt; ?>
                                                    </div>
                                                </div>
                                                <?php
                                                for ($j = 1; $j <= 2; $j++) {
                                                    $checked = '';
                                                    ?>
                                                    <div class="col-md-1 col-sm-1 col-xs-1 bordered position">
                                                        <div class="team_name p-3 pull-left border"> <?php echo ($j == 1) ? 'A' : 'B'; ?></div>
                                                        <div class="player_name d-inline-block">
                                                            <?php
                                                            foreach ($match_position['result'] as $position) {
                                                                if ($position['team'] == $cnt && $position['position'] == $j && $position['pubg_id'] != '') {
                                                                    $checked = 'checked disabled';
                                                                }
                                                            }
                                                            ?>
                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input type="checkbox" id="position<?php echo $cnt . '_' . $j; ?>" <?php echo ($checked == '') ? "name='position[]'" : ""; ?>  value="<?php echo $cnt . '_' . $j; ?>" <?php echo $checked; ?> class="custom-control-input">
                                                                <label class="custom-control-label" for="position<?php echo $cnt . '_' . $j; ?>" > </label>
                                                            </div>
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
                                } elseif ($match['type'] == 'squad' || $match['type'] == 'Squad') {
                                    ?>
                                    <div class="row">
                                        <?php
                                        for ($x = 1; $x <= 2; $x++) {
                                            if ($x % 2 == 1) {
                                                ?>
                                                <div class="col-md-1 col-sm-1 col-xs-1"></div>     
                                            <?php } ?>
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;margin: 1px;padding: 0;">Team</div>                                                    
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;margin: 1px;padding: 0;"> A </div>
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;margin: 1px;padding: 0;"> B </div>
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;margin: 1px;padding: 0;"> C </div>
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;margin: 1px;padding: 0;"> D </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    $cnt = 1;
                                    $loop = ceil($match['number_of_position'] / 4);
                                    for ($x = 1; $x <= $loop; $x++) {
                                        ?>
                                        <div class="row">
                                            <?php
                                            for ($z = 1; $z <= 2; $z++) {
                                                if ($cnt > ceil($match['number_of_position'] / 4)) {
                                                    break;
                                                }
                                                if ($cnt % 2 == 1) {
                                                    ?>
                                                    <div class="col-md-1 col-sm-1 col-xs-1"></div>     
                                                <?php } ?>       
                                                <div class="col-md-1 col-sm-1 col-xs-1 text-center" style="font-weight: 600;margin: 1px">     
                                                    <div class="player_name d-inline-block">
                                                        <?php echo 'Team' . $cnt; ?>
                                                    </div>
                                                </div>
                                                <?php
                                                for ($j = 1; $j <= 4; $j++) {
                                                    $checked = '';
                                                    ?>
                                                    <div class="col-md-1 col-sm-1 col-xs-1 position bordered text-center" style="margin: 1px">
                                                        <div class="team_name p-3 pull-left border"> <?php if ($j == 1) echo 'A';elseif ($j == 2) echo 'B';elseif ($j == 3) echo 'C';elseif ($j == 4) echo 'D'; ?></div>
                                                        <div class="player_name d-inline-block">
                                                            <?php
                                                            foreach ($match_position['result'] as $position) {
                                                                if ($position['team'] == $cnt && $position['position'] == $j && $position['pubg_id'] != '') {
                                                                    $checked = 'checked disabled';
                                                                }
                                                            }
                                                            ?>
                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input type="checkbox" id="position<?php echo $cnt . '_' . $j; ?>" <?php echo ($checked == '') ? "name='position[]'" : ""; ?> value="<?php echo $cnt . '_' . $j; ?>" <?php echo $checked; ?> class="custom-control-input">
                                                                <label class="custom-control-label" for="position<?php echo $cnt . '_' . $j; ?>" > </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <?php
                                                $cnt++;
                                            }
                                            ?></div>
                                        <?php
                                    }
                                } elseif ($match['type'] == 'squad5' || $match['type'] == 'Squad5') {
                                    ?>
                                    <div class="row">
                                        <?php
                                        for ($x = 1; $x <= 2; $x++) {
                                            ?>
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;padding: 0;">Team</div>                                                    
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;padding: 0;"> A </div>
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;padding: 0;"> B </div>
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;padding: 0;"> C </div>
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;padding: 0;"> D </div>
                                            <div class="col-md-1 col-sm-1 col-xs-1 team-header text-center" style="font-weight: 600;padding: 0;"> E </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    $cnt = 1;
                                    $loop = ceil($match['number_of_position'] / 5);
                                    for ($x = 1; $x <= $loop; $x++) {
                                        ?>
                                        <div class="row">
                                            <?php
                                            for ($z = 1; $z <= 2; $z++) {
                                                if ($cnt > ceil($match['number_of_position'] / 5)) {
                                                    break;
                                                }
                                                ?>
                                                <div class="col-md-1 col-sm-1 col-xs-1 text-center" style="font-weight: 600;">     
                                                    <div class="player_name d-inline-block">
                                                        <?php echo 'Team' . $cnt; ?>
                                                    </div>
                                                </div>
                                                <?php
                                                for ($j = 1; $j <= 5; $j++) {
                                                    $checked = '';
                                                    ?>
                                                    <div class="col-md-1 col-sm-1 col-xs-1 position text-center bordered">
                                                        <div class="team_name p-3 pull-left border"> <?php if ($j == 1) echo 'A';elseif ($j == 2) echo 'B';elseif ($j == 3) echo 'C';elseif ($j == 4) echo 'D';elseif ($j == 5) echo 'E'; ?></div>
                                                        <div class="player_name d-inline-block">
                                                            <?php
                                                            foreach ($match_position['result'] as $position) {
                                                                if ($position['team'] == $cnt && $position['position'] == $j && $position['pubg_id'] != '') {
                                                                    $checked = 'checked disabled';
                                                                }
                                                            }
                                                            ?>
                                                            <div class="custom-control custom-checkbox mb-3"> 
                                                                <input type="checkbox" id="position<?php echo $cnt . '_' . $j; ?>" <?php echo ($checked == '') ? "name='position[]'" : ""; ?>  value="<?php echo $cnt . '_' . $j; ?>" <?php echo $checked; ?> class="custom-control-input">
                                                                <label class="custom-control-label" for="position<?php echo $cnt . '_' . $j; ?>" > </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                $cnt++;
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <input type="submit" id="join_now" class="btn btn-sm bg-primary text-white mt-2" value="<?php echo $this->lang->line('text_btn_join_now')?>" name="submit" >
                            </form>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
        <script>
            $(document).ready(function () {

                $("input[name='position[]']").click(function () {
                    if ($(this).is(":checked")) {
//                        $(this).attr('disabled', 'true');
//                        $("input[name='position[]']").attr('disabled', 'true');
//                        $(this).attr('disabled', !$(this).attr('disabled'));
//                        $(this).attr('enabled', 'true');
                    } else {
//                        $("input[name='position[]']").attr('disabled', !$("input[name='position[]']").attr('disabled'));
//                        $("#join_now").attr('disabled', 'true');
                    }
                });
                $("#select-position-form").validate({
                    rules: {
                        'position': {
                            required: function () {
                                if ($("input[name='position[]']:checked").val() != "") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                        },
                    },
                    messages: {
                        'position': {
                            required: "Please select position",
                        },
                    },
                });
            });
        </script>
    </body>
</html>