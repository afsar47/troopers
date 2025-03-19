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
                                <a href="<?php echo base_url() . $this->path_to_default . 'play/select_position/' . $match['m_id']; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a>
                                <h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="row text-black p-2">
                                <div class="col-12">                                
                                    <div class="row d-flex mb-3">
                                        <div class="col-3 m-auto bm_text_lightgreen" style="height: 100%;">
                                            <i class="fa fa-google-wallet" style="font-size: 50px;"></i>
                                        </div>
                                        <div class="col-9 text-right">
                                            <span class="d-block"><?php echo $this->lang->line('text_your_curr_balance'); ?> : <strong> <i style=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $member['join_money'] + $member['wallet_balance']; ?></strong></span>                                                
                                            <span class="d-block"><?php echo $this->lang->line('text_match_entry_per_per'); ?> : <strong> <i style=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $match['entry_fee']; ?> </strong></span>      
                                            <span class="d-block"><?php echo $this->lang->line('text_tot_payable_amt'); ?> : <strong> <i style=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $tot_payment = $match['entry_fee'] * count($positions); ?> </strong></span>      
                                        </div>
                                    </div>
                                    <form action="<?php echo base_url() . $this->path_to_default . 'play/joinmatch'; ?>" class="profile-form" method="post" name="position-form" id="position-form">
                                        <table class="table tr-bordered bg-white box-shadow">
                                            <caption class="btn-green text-white text-center" style="caption-side: top;">Selected Position</caption>
                                            <tr class="thead-dark text-white">
                                                <th style="width:10%">#</th>
                                                <th style="width:25%"><?php echo $this->lang->line('text_team'); ?></th>
                                                <th style="width:15%"><?php echo $this->lang->line('text_position'); ?></th>
                                                <th style="width:50%"><?php echo $match['game_name'] . " " . $this->lang->line('text_name'); ?></th>
                                            </tr>
                                            <?php
                                            $i = 0;
                                            foreach ($positions as $position) {
                                                $team = explode("_", $position)[0];
                                                $pos = explode("_", $position)[1];
                                                ?>
                                                <tr>
                                                    <td><?php echo ++$i; ?><input type="hidden" name="no[]" value="<?php echo $i; ?>"></td>
                                                    <td><input type="hidden" name="position_<?php echo $i; ?>[]" value="<?php echo $team; ?>"><?php echo $this->lang->line('text_team') . ' ' . $team; ?></td>
                                                    <td><input type="hidden" name="position_<?php echo $i; ?>[]" value="<?php echo $pos; ?>"><?php echo $pos; ?></td>
                                                    <td><?php
                                                        if ($i <= 1 && $pubg_id != '') {
                                                            ?><input type="text" class="game_id form-control border-bottom rounded-0" id="game_id_<?php echo $i; ?>" name="position_<?php echo $i; ?>[]" value="<?php echo $pubg_id; ?>" >
                                                        <?php } else { ?>
                                                            <input type="text" class="game_id form-control border-bottom rounded-0" id="game_id_<?php echo $i; ?>" name="position_<?php echo $i; ?>[]" value="" class="form-control-sm">
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </table> 
                                        <input type="hidden" name="join_status" value="<?php echo $join_status; ?>">
                                        <input type="hidden" name="positions" value="<?php echo json_encode($positions); ?>">
                                        <input type="hidden" name="match_id" value="<?php echo $match['m_id']; ?>">
                                        <input type="hidden" name="game_name" value="<?php echo $match['game_name']; ?>">
                                        <input type="hidden" name="total_amount" value="<?php echo $tot_payment; ?>">
                                        <a href="<?php echo base_url() . $this->path_to_default . 'play/matches/' . $match['game_id']; ?>" class="btn btn-lightpink"> Cancel</a>
                                        <button type="submit" id="join_now" class="btn btn-lightgreen text-white text-uppercase" value="<?php echo $this->lang->line('text_btn_join'); ?>" name="submit" > <?php echo $this->lang->line('text_btn_join'); ?> </button>                              
                                    </form>
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
            var ajaxSent = false;

            $(document).ready(function () {
                $('form').submit(function (e) {
                    if (!ajaxSent) {
                        e.preventDefault();
                        var match_id = $("input[name='match_id']").val();
                        var game_name = $("input[name='game_name']").val();
                        var game_ids = new Array();
                        var ids = $('.game_id').map(function () {
                            return this.id;
                        }).get();
                        var id;
                        var j = 0;
                        for (id in ids) {
                            var game_id = $('#' + ids[id]).val();
                            if (game_id == '') {
                                toastr.error('please enter ' + game_name + ' name');
                                return;
                            }

                            if ( game_id.indexOf('"') > -1 || game_id.indexOf("'") > -1 ) {
                                toastr.error( "Quote not Allow in Pubg name" );
                                return false;
                            }

                            console.log(game_id);
                            game_ids[j] = game_id;
                            j++;
                        }
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url() . $this->path_to_default; ?>/play/checkGameId",
                            data: {game_ids: game_ids, match_id: match_id},
                            success: function funSuccess(response) {
                                if (response == 'true') {
                                    ajaxSent = true;
                                    $('form').unbind().submit();
                                    return false;
                                } else {
                                    toastr.error(response);
                                }
                            }
                        });
                    }
                });
                $('.game_id').change(function () {
                    ajaxSent = false;
                });
            });
        </script>
    </body>
</html>      