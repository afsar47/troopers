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
                            <div class="bm-mdl-center bm-full-height pb-6">
                                <div class="content-section">
                                    <div class="bm-content-listing">
                                        <div class="row">
                                            <div class="col-12 text-center">                            
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
                                                                    <div class="col-md-1 col-sm-1 col-xs-1 bordered position" style="margin: 2px;color:#000;">
                                                                        <?php
                                                                        foreach ($match_position['result'] as $position) {
                                                                            if ($position['position'] == $i && $position['pubg_id'] != '') {
                                                                                $checked = 'checked disabled';
                                                                            }
                                                                        }
                                                                        ?> <?php echo $i; ?>
                                                                        <div class="custom-control custom-checkbox mb-3 text-black"> <input type="checkbox" id="position<?php echo $team . '_' . $i; ?>" <?php echo ($checked == '') ? "name='position[]'" : ""; ?>  value="<?php echo $team . '_' . $i; ?>" <?php echo $checked; ?> class="custom-control-input">
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
                                                            <div class="col-6 team-header text-center text-black" style="font-weight: 600;padding: 0;">Team</div>                                                    
                                                            <div class="col-3 team-header text-center text-black" style="font-weight: 600;padding: 0;"> A </div>
                                                            <div class="col-3 team-header text-center text-black" style="font-weight: 600;padding: 0;"> B </div>
                                                        </div>
                                                        <?php
                                                        $cnt = 1;
                                                        $loop = ceil($match['number_of_position'] / 2);
                                                        for ($x = 1; $x <= $loop; $x++) {
                                                            ?>
                                                            <div class="row">
                                                                <?php
//                                                            for ($z = 1; $z <= 2; $z++) {
                                                                if ($cnt > ceil($match['number_of_position'] / 2))
                                                                    break;
                                                                ?>                                                   
                                                                <div class="col-6 bordered text-black " style="font-weight: 600;">  
                                                                    <div class="player_name d-inline-block">
                                                                        <?php echo 'Team' . $cnt; ?>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                for ($j = 1; $j <= 2; $j++) {
                                                                    $checked = '';
                                                                    ?>
                                                                    <div class="col-3 bordered position">
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
//                                                            }
                                                                ?>
                                                            </div>
                                                            <?php
                                                        }
                                                    } elseif ($match['type'] == 'squad' || $match['type'] == 'Squad') {
                                                        ?>
                                                        <div class="row text-black">
                                                            <div class="col-1"></div>     
                                                            <div class="col-2 team-header text-center" style="font-weight: 600;margin: 1px;padding: 0;">Team</div>                                                    
                                                            <div class="col-2 team-header text-center" style="font-weight: 600;margin: 1px;padding: 0;"> A </div>
                                                            <div class="col-2 team-header text-center" style="font-weight: 600;margin: 1px;padding: 0;"> B </div>
                                                            <div class="col-2 team-header text-center" style="font-weight: 600;margin: 1px;padding: 0;"> C </div>
                                                            <div class="col-2 team-header text-center" style="font-weight: 600;margin: 1px;padding: 0;"> D </div>
                                                        </div>
                                                        <?php
                                                        $cnt = 1;
                                                        $loop = ceil($match['number_of_position'] / 4);
                                                        for ($x = 1; $x <= $loop; $x++) {
                                                            ?>
                                                            <div class="row">
                                                                <?php
                                                                if ($cnt > ceil($match['number_of_position'] / 4)) {
                                                                    break;
                                                                }
                                                                ?>
                                                                <div class="col-1"></div>     
                                                                <div class="col-2 text-center text-black" style="font-weight: 600;margin: 1px">     
                                                                    <div class="player_name d-inline-block">
                                                                        <?php echo 'Team' . $cnt; ?>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                for ($j = 1; $j <= 4; $j++) {
                                                                    $checked = '';
                                                                    ?>
                                                                    <div class="col-2 position bordered text-center" style="margin: 1px">
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
                                                                ?></div>
                                                            <?php
                                                        }
                                                    } elseif ($match['type'] == 'squad5' || $match['type'] == 'Squad5') {
                                                        ?>
                                                        <div class="row text-black">
                                                            <div class="col-2 team-header text-center" style="font-weight: 600;padding: 0;">Team</div>                                                    
                                                            <div class="col-2 team-header text-center" style="font-weight: 600;padding: 0;"> A </div>
                                                            <div class="col-2 team-header text-center" style="font-weight: 600;padding: 0;"> B </div>
                                                            <div class="col-2 team-header text-center" style="font-weight: 600;padding: 0;"> C </div>
                                                            <div class="col-2 team-header text-center" style="font-weight: 600;padding: 0;"> D </div>
                                                            <div class="col-2 team-header text-center" style="font-weight: 600;padding: 0;"> E </div>
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
                                                                    <div class="col-2 text-center text-black" style="font-weight: 600;">     
                                                                        <div class="player_name d-inline-block">
                                                                            <?php echo 'Team' . $cnt; ?>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    for ($j = 1; $j <= 5; $j++) {
                                                                        $checked = '';
                                                                        ?>
                                                                        <div class="col-2 position text-center bordered">
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
                </div>
            </div>
        </main>
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