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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
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
                        <div class="col-lg-12">                                
                            <div class="row d-flex mb-3">
                                <div class="col-sm-6 m-auto text-lightpink" style="height: 100%;">
                                    <i class="fa fa-google-wallet" style="font-size: 50px;"></i>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <span class="d-block">Your Current Balance : <strong> <i><?php echo $this->functions->getPoint(); ?></i> <?php echo $member['join_money'] + $member['wallet_balance']; ?></strong></span>                                                
                                    <span class="d-block">Match entry fee per person's : <strong> <i><?php echo $this->functions->getPoint(); ?></i> <?php echo $match['entry_fee']; ?> </strong></span>      
                                    <span class="d-block">Total payable Amount : <strong> <i><?php echo $this->functions->getPoint(); ?></i> <?php echo $tot_payment = $match['entry_fee'] * count($positions); ?> </strong></span>      
                                </div>
                            </div>
                            <form action="<?php echo base_url() . $this->path_to_default . 'play/joinmatch'; ?>" method="post" name="position-form" id="position-form">
                                <table class="table tr-bordered bg-white box-shadow">
                                    <caption class="bg-lightgreen text-white text-center" style="caption-side: top;">Selected Position</caption>
                                    <tr class="bg-black text-white">
                                        <th>#</th>
                                        <th>Team</th>
                                        <th>Position</th>
                                        <th><?php echo $match['game_name'] . " Name"; ?></th>
                                    </tr>
                                    <?php
                                    $i = 0;
                                    foreach ($positions as $position) {
                                        $team = explode("_", $position)[0];
                                        $pos = explode("_", $position)[1];
                                        ?>
                                        <tr>
                                            <td><?php echo ++$i; ?><input type="hidden" name="no[]" value="<?php echo $i; ?>"></td>
                                            <td><input type="hidden" name="position_<?php echo $i; ?>[]" value="<?php echo $team; ?>"><?php echo 'Team ' . $team; ?></td>
                                            <td><input type="hidden" name="position_<?php echo $i; ?>[]" value="<?php echo $pos; ?>"><?php echo $pos; ?></td>
                                            <td><?php
                                                if ($i <= 1 && $pubg_id != '') {
//                                                    echo $pubg_id;
                                                    ?><input type="text" class="game_id" id="game_id_<?php echo $i; ?>" name="position_<?php echo $i; ?>[]" value="<?php echo $pubg_id; ?>" >
                                                <?php } else { ?>
                                                    <input type="text" class="game_id" id="game_id_<?php echo $i; ?>" name="position_<?php echo $i; ?>[]" value="" class="form-control-sm">
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
                                <a href="<?php echo base_url() . $this->path_to_default . 'play/matches/' . $match['game_id']; ?>" class="btn btn-primary"> Cancel</a>
                                <button type="submit" id="join_now" class="btn bg-lightgreen text-white" value="JOIN" name="submit" > JOIN </button>                              
                            </form>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
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