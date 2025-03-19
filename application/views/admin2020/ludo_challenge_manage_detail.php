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
                    <div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <center><h1 class="h2"><?php echo $title; ?></h1></center>
                    </div>
                    <div class="row justify-content-center text-center mb-3">
                        <div class="col-sm-offset-3 col-sm-6">
                            <div class="card">
                              <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="image_div">
                                        <?php
                                            if($challenge_detail['profile_image'] != ''){
                                        ?>
                                            <img src="<?php echo base_url() . 'uploads/profile_image/thumb/100x100_' . $challenge_detail['profile_image']; ?>" class="rounded-circle">
                                        <?php
                                            } else {
                                        ?>
                                            <img src="<?php echo base_url() . $this->game_logo_image . "thumb/100x100_" . $challenge_detail['game_logo']; ?>" class="rounded-circle img-fluid">
                                        <?php
                                            }
                                        ?>
                                        </div>
                                        <br/>
                                        <h6><?php echo $challenge_detail['ludo_king_username']; ?></h6>
                                    </div>
                                    <div class="col-sm-6 text-center mt-3">
                                        <h6 class="auto_id_div"><?php echo $challenge_detail['auto_id']; ?></h6>
                                        has challenge for
                                        <h6 class="coin_div"><?php echo $challenge_detail['coin'] . ' Coins'; ?></h6>
                                        <?php if($challenge_detail['with_password']) { ?>
                                            <h6 class="coin_div"><?php echo 'Password: ' . $challenge_detail['challenge_password']; ?></h6>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="image_div">
                                        <?php
                                            if($challenge_detail['accepted_profile_image'] != '' || $challenge_detail['accepted_profile_image'] != null){
                                        ?>
                                            <img src="<?php echo base_url() . 'uploads/profile_image/thumb/100x100_' . $challenge_detail['accepted_profile_image']; ?>" class="rounded-circle">
                                        <?php
                                            } else {
                                        ?>
                                            <img src="<?php echo base_url() . $this->game_logo_image . "thumb/100x100_" . $challenge_detail['game_logo']; ?>" class="rounded-circle img-fluid">
                                        <?php
                                            }
                                        ?>
                                        </div>
                                        <br/>
                                        <?php
                                            if($challenge_detail['accepted_ludo_king_username'] != ''){
                                        ?>
                                        <h6><?php echo $challenge_detail['accepted_ludo_king_username'] ; ?></h6>
                                        <?php
                                            } else {
                                        ?>
                                        <h6>Waiting...</h6>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                              </div>
                              <div class="challenge_detail_footer card-footer text-white px-3 py-0">
                                    <div class="row">
                                        <div class="col-sm-4 bg-lightgreen py-3">
                                            Winning : <?php echo $challenge_detail['winning_price'] . ' Coins'; ?>
                                        </div>
                                        <div class="col-sm-4 bg-lightpink py-3">
                                            <?php
                                                if($challenge_detail['challenge_status'] == 1){
                                                    echo 'Activate';
                                                } elseif($challenge_detail['challenge_status'] == 2){
                                                    echo 'Canceled';
                                                } elseif($challenge_detail['challenge_status'] == 3){
                                                    echo 'Completed';
                                                } elseif($challenge_detail['challenge_status'] == 4){
                                                    echo 'Pending';
                                                }
                                            ?>
                                        </div>
                                        <div class="col-sm-4 bg-lightgreen py-3">
                                            <?php
                                                if($challenge_detail['room_code'] != ''){
                                            ?>
                                            Room Code : <?php echo $challenge_detail['room_code']; ?>
                                            <?php
                                                }else {
                                            ?>
                                            Room Code Not Updated !
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                              </div>
                              <?php
                                    if($challenge_detail['challenge_status'] =='3') {
                                ?>
                              <div class="challenge_detail_footer card-footer text-white px-3 py-0">
                                    <div class="row">
                                        <div class="col-sm-12 bg-lightpink py-3">
                                            <?php
                                                if($challenge_detail['winner_id'] == $challenge_detail['member_id']){
                                                    $winner = $challenge_detail['ludo_king_username'];   
                                                }
                                                
                                                if($challenge_detail['winner_id'] == $challenge_detail['accepted_member_id']){
                                                    $winner = $challenge_detail['accepted_ludo_king_username'];
                                                }
                                            ?>
                                            Winner : <?php echo $winner; ?>
                                        </div>
                                    </div>
                              </div>
                              <?php
                                    }
                              ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                       <center><h3 class="h3">RoomCode History</h3></center>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-sm-offset-3 col-sm-6">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Room Code</th>
                                        <th>Date Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($room_code_detail as $room_detail){
                                    ?>
                                        <tr>
                                            <td><?php echo $room_detail['room_code']; ?></td>
                                            <td><?php echo $room_detail['date_created']; ?></td>
                                        </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php
                        if($challenge_detail['room_code'] != '') {
                    ?>
                    <!--display uploded result-->
                    <div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                       <center><h3 class="h3"><?php echo $text_uploded_result; ?></h3></center>
                    </div>
                    
                    <div class="row justify-content-center">
                        <div class="col-sm-offset-3 col-sm-3">
                            <div class="card">
                              <div class="card-body text-center">
                                <div class="row">
                                    <div class="col-12">
                                        <h5><?php echo $challenge_detail['ludo_king_username']; ?></h5>
                                    </div>
                                    <hr/>
                                    <?php
                                        if(!empty($result_by_addedd_detail)) {
                                    ?>
                                    <div class="col-12">
                                        <?php 
                                            if($result_by_addedd_detail['result_status'] == 0){
                                        ?>
                                            <h6>Request : Win</h6>
                                        <?php
                                                if($result_by_addedd_detail['result_image'] != ''){
                                        ?>
                                            <img src="<?php echo $result_by_addedd_detail['result_image']; ?>" class="img-fluid"><br/><br/>
                                            <a class="btn btn-primary" targer="_blank" href="<?php echo base_url() . $this->path_to_view_admin . 'ludo_challenge/download_result/' . $result_by_addedd_detail['challenge_result_upload_id']; ?>"><?php echo $this->lang->line('text_download'); ?></a>
                                        <?php
                                                }
                                            }
                                        ?>
                                        <?php 
                                            if($result_by_addedd_detail['result_status'] == 1){
                                        ?>
                                            <h6>Request : Lost</h6>
                                        <?php
                                            }
                                        ?>
                                        <?php 
                                            if($result_by_addedd_detail['result_status'] == 2){
                                        ?>
                                            <h6>Request : Error</h6>
                                            <p><b>Reason : </b><?php echo $result_by_addedd_detail['reason']; ?></p> 
                                            <?php
                                                if($result_by_addedd_detail['result_image'] != ''){
                                            ?>
                                            <img src="<?php echo $result_by_addedd_detail['result_image']; ?>" class="img-fluid"><br/><br/>
                                            <a class="btn btn-primary" targer="_blank" href="<?php echo base_url() . $this->path_to_view_admin . 'ludo_challenge/download_result/' . $result_by_addedd_detail['challenge_result_upload_id']; ?>"><?php echo $this->lang->line('text_download'); ?></a>
                                            <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card">
                              <div class="card-body text-center">
                                <div class="row">
                                    <div class="col-12">
                                        <h5><?php echo $challenge_detail['accepted_ludo_king_username']; ?></h5>
                                    </div>
                                    <hr/>
                                    <?php
                                        if(!empty($result_by_accepted_detail)) {
                                    ?>
                                    <div class="col-12">
                                        <?php 
                                            if($result_by_accepted_detail['result_status'] == 0){
                                        ?>
                                            <h6>Request : Win</h6>
                                            <?php
                                                if($result_by_accepted_detail['result_image'] != ''){
                                            ?>
                                            <img src="<?php echo $result_by_accepted_detail['result_image']; ?>" class="img-fluid"><br/><br/>
                                            <a class="btn btn-primary" targer="_blank" href="<?php echo base_url() . $this->path_to_view_admin . 'ludo_challenge/download_result/' . $result_by_accepted_detail['challenge_result_upload_id']; ?>"><?php echo $this->lang->line('text_download'); ?></a>
                                        <?php
                                                }
                                            }
                                        ?>
                                        <?php 
                                            if($result_by_accepted_detail['result_status'] == 1){
                                        ?>
                                            <h6>Request : Lost</h6>
                                        <?php
                                            }
                                        ?>
                                        <?php 
                                            if($result_by_accepted_detail['result_status'] == 2){
                                        ?>
                                            <h6>Request : Error</h6>
                                            <p><b>Reason : </b><?php echo $result_by_accepted_detail['reason']; ?></p> 
                                            <?php
                                                if($result_by_accepted_detail['result_image'] != ''){
                                            ?>
                                            <img src="<?php echo $result_by_accepted_detail['result_image']; ?>" class="img-fluid"><br/><br/>
                                            <a class="btn btn-primary" targer="_blank" href="<?php echo base_url() . $this->path_to_view_admin . 'ludo_challenge/download_result//' . $result_by_accepted_detail['challenge_result_upload_id']; ?>"><?php echo $this->lang->line('text_download'); ?></a>
                                            <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    
                    <?php
                        if($challenge_detail['room_code'] != '' && $challenge_detail['challenge_status'] !='2' && $challenge_detail['challenge_status'] !='3') {
                            // if($challenge_detail['challenge_status'] =='4') {
                    ?>
                    <!--display uploded result-->
                    <div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                       <center><h3 class="h3"><?php echo $text_decide_result; ?></h3></center>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-sm-offset-3 col-sm-6">
                            <div class="card">
                              <div class="card-body">
                                    <form id="challenge-result-form"  enctype="multipart/form-data" method="POST" action="<?php echo base_url() . $this->path_to_view_admin ?>ludo_challenge/upload_result">
                                        <input type="hidden" name="ludo_challenge_id" value="<?php echo $challenge_detail['ludo_challenge_id']; ?>">
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <label class="col-md-3" for="challenge_status"><b><?php echo $this->lang->line('text_status'); ?><span class="required" aria-required="true"> * </span></b></label>                                                
                                                    <div class="col-md-9">
                                                        <select name="challenge_status" class="form-control challenge_status" required>
                                                            <option value="">Select status</option>
                                                            <option value="2">Canceled</option>
                                                            <option value="3">Compeleted</option>
                                                        </select>
                                                    </div>
                                                    <?php echo form_error('challenge_status', '<em style="color:red">', '</em>'); ?>
                                            </div>
                                            <div class="member_div col-md-12 form-group d-none">
                                                <label class="col-md-3" for="member_id"><b><?php echo $this->lang->line('text_choose_user'); ?><span class="required" aria-required="true"> * </span></b></label>                                                
                                                    <div class="col-md-9">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="added_user" name="member_id" type="radio" class="custom-control-input" value="<?php echo $challenge_detail['member_id']; ?>">&nbsp;
                                                            <label class="custom-control-label" for="added_user"><?php echo $challenge_detail['ludo_king_username']; ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input id="accepted_user" name="member_id" type="radio" class="custom-control-input" value="<?php echo $challenge_detail['accepted_member_id']; ?>" >&nbsp;
                                                            <label class="custom-control-label" for="accepted_user"><?php echo $challenge_detail['accepted_ludo_king_username']; ?></label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('member_id', '<em style="color:red">', '</em>'); ?>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <input type="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>" name="submit" class="btn btn-primary " <?php
                                                if ($this->system->demo_user == 1) {
                                                    echo 'disabled';
                                                }
                                                ?>>    
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>ludo_challenge/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                 
                                            </div>
                                        </div>
                                    </form>
                              </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    
                </div>
                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>
        <script>
            
            $('.challenge_status').on('change', function () {
                 var challenge_status = $(this).val();
                 
                 if(challenge_status == 3) {
                     
                     $('.member_div').removeClass('d-none');
                     
                     $("#challenge-result-form").validate({
                        rules: {
                            'member_id': {
                                required: true,
                            }
                        },
                        messages: {
                            'member_id': {
                                required: '<?php echo $this->lang->line('err_member_id'); ?>',
                            }
                        },
                        errorPlacement: function (error, element)
                        {
                            if (element.is(":radio"))
                            {
                                error.insertAfter(element.parent().parent());
                            } else
                            {
                                error.insertAfter(element);
                            }
                        },
                    });
                 } else {
                     $('.member_div').addClass('d-none');
                 }
            });

        </script>
    </body>
</html>