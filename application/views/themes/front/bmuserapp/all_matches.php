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
                                <a href="<?php echo base_url() . $this->path_to_default . 'play'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a>
                                <p class="badge badge-light float-right f-18 text-black d-inline" id="tot_wallet"><?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', 0); ?></p>
                                <h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="bm-mdl-center bm-full-height">
                                <div class="tab-section single-game">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link text-uppercase" data-toggle="tab" href="#ongoing"><?php echo $this->lang->line('text_ongoing'); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-uppercase active" data-toggle="tab" href="#upcoming"><?php echo $this->lang->line('text_upcoming'); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-uppercase" data-toggle="tab" href="#results"><?php echo $this->lang->line('text_results'); ?></a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="ongoing" class="container tab-pane fade">
                                            <div class="content-section">
                                                <div class="bm-content-listing tournaments">
                                                    <?php
                                                    if (!empty($ongoing_match_data)) {
                                                        foreach ($ongoing_match_data as $ongoing_match) {
                                                            if (isset($ongoing_match->image_name) && $ongoing_match->image_name != "") {
                                                                $ongoing_match_img = base_url() . $this->select_image . 'thumb/253x90_' . $ongoing_match->image_name;
                                                            } elseif (isset($ongoing_match->match_banner) && $ongoing_match->match_banner != "") {
                                                                $ongoing_match_img = base_url() . $this->match_banner_image . 'thumb/1000x500_' . $ongoing_match->match_banner;
                                                            } else {
                                                                $ongoing_match_img = base_url() . $this->game_image . 'thumb/1000x500_' . $tournament['game_image'];
                                                            }
                                                            ?>
                                                            <div class="card br-5 hide-over mb-3">
                                                                <a href="<?php echo base_url() . $this->path_to_default . 'play/match_detail/' . $ongoing_match->m_id; ?>"><img src="<?php echo $ongoing_match_img; ?>" class="img-fluid card-img-top"></a>
                                                                <div class="card-body">
                                                                    <a href="#" class="btn btn-sm btn-lightpink"><?php echo $ongoing_match->type ?></a>
                                                                    <a href="#" class="btn btn-sm btn-primary ml-1"><?php echo $ongoing_match->MAP ?></a>
                                                                    <h6 class="card-title mt-2"><i class="fa fa-bomb"></i><a href="#" class="text-dark"><?php echo $ongoing_match->match_name . $this->lang->line('text_for_macth_id') . $ongoing_match->m_id; ?></a></h6>
                                                                    <?php if ($ongoing_match->room_description && $ongoing_match->join_status == true) {
                                                                        ?>
                                                                        <a href="<?php echo base_url() . $this->path_to_default . 'play/match_detail/' . $ongoing_match->m_id; ?>" class="row btn-green text-white px-3"><?php echo $this->lang->line('text_click_for_id_pass'); ?></a>
                                                                    <?php } ?>
                                                                    <table class="card-table table text-center mt-3">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="bm_text_lightpink "><?php echo $ongoing_match->match_time; ?></span>
                                                                                </td>
                                                                                <td>
                                                                                    <?php if ($ongoing_match->prize_description != '') { ?>
                                                                                        <input type="hidden" value="<?php echo $ongoing_match->match_name . $this->lang->line('text_for_macth_id') . $ongoing_match->m_id; ?>">
                                                                                        <input type="hidden" value="<?php echo $ongoing_match->prize_description; ?>">
                                                                                        <a href="#" data-id="<?php echo $ongoing_match->prize_description; ?>" class="prize_modal pt-2 btn-block bm_text_lightgreen align-middle">PRIZE POOL <br/><?php echo $ongoing_match->win_prize . '(%)'; ?><i class="fa fa-angle-down bm_text_lightgreen"></i></a>
                                                                                    <?php } else { ?>
                                                                                        <span class="bm_text_lightgreen text-uppercase"><?php echo $this->lang->line('text_prize_pool'); ?> <br/><?php echo $ongoing_match->win_prize . '(%)'; ?></span>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <span class="text-primary text-uppercase"><?php echo $this->lang->line('text_per_kill'); ?> <br/> <?php echo $ongoing_match->per_kill . '(%)'; ?></span>
                                                                                </td>
                                                                            </tr>                                                                
                                                                        </tbody>
                                                                    </table>
                                                                    <a href="<?php echo $ongoing_match->match_url; ?>" target="_blank" class="btn btn-sm btn-block btn-lightpink text-uppercase"> <i class="text-white"><?php echo '<span style="">' . $this->functions->getPoint() . '</span>'; ?></i> <?php echo $ongoing_match->entry_fee . $this->lang->line('text_Spactate'); ?>  </a>
                                                                </div>
                                                            </div>         
                                                            <?php
                                                        }
                                                    } else {
                                                        echo "<div class='col-md-12 text-center text-black'><strong>" . $this->lang->line('text_no_live_macth') . "</strong></div>";
                                                    }
                                                    ?>   
                                                </div>
                                            </div>
                                        </div>
                                        <div id="upcoming" class="container tab-pane active">
                                            <div class="content-section">
                                                <div class="bm-content-listing tournaments" >
                                                    <?php
                                                    if (!empty($upcoming_match_data)) {
                                                        foreach ($upcoming_match_data as $upcoming_match) {
                                                            if (isset($upcoming_match->image_name) && $upcoming_match->image_name != "") {
                                                                $upcoming_match_img = base_url() . $this->select_image . 'thumb/253x90_' . $upcoming_match->image_name;
                                                            } elseif (isset($upcoming_match->match_banner) && $upcoming_match->match_banner != "") {
                                                                $upcoming_match_img = base_url() . $this->match_banner_image . 'thumb/1000x500_' . $upcoming_match->match_banner;
                                                            } else {
                                                                $upcoming_match_img = base_url() . $this->game_image . 'thumb/1000x500_' . $tournament['game_image'];
                                                            }
                                                            ?>
                                                            <div class="card br-5 hide-over mb-3">
                                                                <?php if ($upcoming_match->pin_match == '1') { ?>
                                                                    <span class="pin-match"> <i class="fa fa-thumb-tack bm_text_lightpink" aria-hidden="true"></i> </span>
                                                                <?php } ?>
                                                                <a href="<?php echo base_url() . $this->path_to_default . 'play/match_detail/' . $upcoming_match->m_id; ?>"><img src="<?php echo $upcoming_match_img; ?>" class="img-fluid card-img-top"></a>
                                                                <div class="card-body">
                                                                    <a href="#" class="btn btn-sm btn-lightpink"><?php echo $upcoming_match->type ?></a>
                                                                    <a href="#" class="btn btn-sm btn-primary ml-1"><?php echo $upcoming_match->MAP ?> </a>
                                                                    <h6 class="card-title mt-2"><i class="fa fa-bomb"></i><a href="#" class="text-dark"><?php echo $upcoming_match->match_name . $this->lang->line('text_for_macth_id') . $upcoming_match->m_id; ?></a></h6>
                                                                    <?php if ($upcoming_match->room_description && $upcoming_match->join_status == true) {
                                                                        ?>
                                                                        <a href="<?php echo base_url() . $this->path_to_default . 'play/match_detail/' . $upcoming_match->m_id; ?>" class="row btn-green text-white px-3 "><?php echo $this->lang->line('text_click_for_id_pass');?></a>
                                                                    <?php } ?>
                                                                    <table class="card-table table text-center mt-3">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="bm_text_lightpink "><?php echo $upcoming_match->match_time; ?></span>
                                                                                </td>
                                                                                <td>
                                                                                    <?php if ($upcoming_match->prize_description != '') { ?>
                                                                                        <input type="hidden" value="<?php echo $upcoming_match->match_name . $this->lang->line('text_for_macth_id') . $upcoming_match->m_id; ?>">
                                                                                        <input type="hidden" value="<?php echo $upcoming_match->prize_description; ?>">
                                                                                        <a href="#" data-id="<?php echo $upcoming_match->prize_description; ?>" class="prize_modal pt-2 btn-block bm_text_lightgreen align-middle">PRIZE POOL <br/> <?php echo $upcoming_match->win_prize . '(%)'; ?><i class="fa fa-angle-down bm_text_lightgreen"></i></a>
                                                                                    <?php } else { ?>
                                                                                        <span class="bm_text_lightgreen text-uppercase"><?php echo $this->lang->line('text_prize_pool'); ?> <br/> <?php echo $upcoming_match->win_prize . '(%)'; ?></span>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <span class="text-primary text-uppercase"><?php echo $this->lang->line('text_per_kill'); ?> <br/><?php echo $upcoming_match->per_kill . '(%)'; ?></span>
                                                                                </td>
                                                                            </tr>                                                                
                                                                        </tbody>
                                                                    </table>
                                                                    <div class="row">
                                                                        <div class="col-8">
                                                                            <?php
                                                                            $width = ($upcoming_match->no_of_player / $upcoming_match->number_of_position) * 100;
                                                                            ?>
                                                                            <span class="text-black"><?php echo $upcoming_match->no_of_player . "/" . $upcoming_match->number_of_position; ?></span>
                                                                            <div class="progress" style="height:10px;">
                                                                                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $width; ?>%"> </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <?php if ($upcoming_match->join_status) { ?>
                                                                                <a style='cursor:auto;' class="btn btn-sm btn-block btn-lightgreen text-uppercase"> <i class="text-white"><?php echo '<span style="">' . $this->functions->getPoint() . '</span>'; ?></i> <?php echo $upcoming_match->entry_fee; ?> <?php echo $this->lang->line('text_btn_joined'); ?> </a>
                                                                            <?php } else if ($upcoming_match->no_of_player >= $upcoming_match->number_of_position) { ?>
                                                                                <button disabled="" class="btn btn-sm btn-block btn-lightpink text-white text-uppercase"> <i class="text-white"><?php echo '<span style="">' . $this->functions->getPoint() . '</span>'; ?></i> <?php echo $upcoming_match->entry_fee; ?> <?php echo $this->lang->line('text_btn_join'); ?> > </button>
                                                                            <?php } else { ?> 
                                                                                <a href="<?php echo base_url() . $this->path_to_default . 'play/select_position/' . $upcoming_match->m_id; ?>" class="btn btn-sm btn-block btn-lightpink text-uppercase"> <i class="text-white"><?php echo '<span style="">' . $this->functions->getPoint() . '</span>'; ?></i> <?php echo $upcoming_match->entry_fee; ?> <?php echo $this->lang->line('text_btn_join'); ?> > </a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <?php
                                                        }
                                                    } else {
                                                        echo "<div class='col-md-12 text-center text-black'><strong>" . $this->lang->line('text_no_upcoming_macth') . "</strong></div>";
                                                    }
                                                    ?>                               
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal" id="prizemodal" tabindex="-1" role="dialog" aria-labelledby="prizemodalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header btn-green text-center">
                                                        <h5 class="modal-title text-uppercase"><?php echo $this->lang->line('text_prize_pool'); ?></h5>
                                                        <p></p>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body text-black">                                                       

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="results" class="container tab-pane fade">
                                            <div class="content-section">
                                                <div class="bm-content-listing tournaments" >
                                                    <?php
                                                    if (!empty($result_match_data)) {
                                                        foreach ($result_match_data as $result_match) {
                                                            if (isset($result_match->image_name) && $result_match->image_name != "") {
                                                                $result_match_img = base_url() . $this->select_image . 'thumb/253x90_' . $result_match->image_name;
                                                            } elseif (isset($result_match->match_banner) && $result_match->match_banner != "") {
                                                                $result_match_img = base_url() . $this->match_banner_image . 'thumb/1000x500_' . $result_match->match_banner;
                                                            } else {
                                                                $result_match_img = base_url() . $this->game_image . 'thumb/1000x500_' . $tournament['game_image'];
                                                            }
                                                            ?>
                                                            <div class="card br-5 hide-over mb-3">
                                                                <a href="<?php echo base_url() . $this->path_to_default . 'play/match_detail/' . $result_match->m_id; ?>"><img src="<?php echo $result_match_img; ?>" class="img-fluid card-img-top"></a>
                                                                <div class="card-body">
                                                                    <a href="#" class="btn btn-sm btn-lightpink"><?php echo $result_match->type ?> </a>
                                                                    <a href="#" class="btn btn-sm btn-primary ml-1"><?php echo $result_match->MAP ?></a>
                                                                    <h6 class="card-title mt-2"><i class="fa fa-bomb"></i><a href="#" class="text-dark"> <?php echo $result_match->match_name . $this->lang->line('text_for_macth_id') . $result_match->m_id; ?></a></h6>

                                                                    <table class="card-table table text-center mt-3">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="bm_text_lightpink "><?php echo $result_match->match_time; ?></span>
                                                                                </td>
                                                                                <td>
                                                                                    <?php if ($result_match->prize_description != '') { ?>
                                                                                        <input type="hidden" value="<?php echo $result_match->match_name . $this->lang->line('text_for_macth_id') . $result_match->m_id; ?>">
                                                                                        <input type="hidden" value="<?php echo $result_match->prize_description; ?>">
                                                                                        <a href="#" class="prize_modal pt-2 btn-block bm_text_lightgreen align-middle text-uppercase"><?php echo $this->lang->line('text_prize_pool'); ?> <br/><?php echo $result_match->win_prize . '(%)'; ?><i class="fa fa-angle-down bm_text_lightgreen"></i></a>
                                                                                    <?php } else { ?>
                                                                                        <span class="bm_text_lightgreen"><?php echo $this->lang->line('text_prize_pool'); ?> <br/> <?php echo $result_match->win_prize . '(%)'; ?></span>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <span class="text-primary text-uppercase"><?php echo $this->lang->line('text_per_kill'); ?> <br/> <?php echo $result_match->per_kill . '(%)'; ?></span>
                                                                                </td>
                                                                            </tr>                                                                
                                                                        </tbody>
                                                                    </table>
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <a href="<?php echo $result_match->match_url; ?>" class="btn btn-sm btn-block btn-lightpink text-uppercase"> <?php echo $this->lang->line('text_watch_macth'); ?>  </a>
                                                                        </div>
                                                                        <div class="col-6">   
                                                                            <?php if ($result_match->join_status) { ?>
                                                                                <a href="" class="btn btn-sm btn-block btn-lightgreen text-white"> <i class="text-white"><?php echo '<span style="">' . $this->functions->getPoint() . '</span>'; ?></i> <?php echo $result_match->entry_fee; ?> <?php echo $this->lang->line('text_btn_joined'); ?></a>
                                                                            <?php } else { ?>
                                                                                <a href="" class="btn btn-sm btn-block btn-lightpink"> <i class="text-white"><?php echo '<span style="">' . $this->functions->getPoint() . '</span>'; ?></i> <?php echo $result_match->entry_fee; ?> <?php echo $this->lang->line('text_btn_not_joined'); ?></a>
                                                                            <?php } ?>
                                                                        </div>                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>        
                                                            <?php
                                                        }
                                                    } else {
                                                        echo "<div class='col-md-12 text-center text-black'><strong>" . $this->lang->line('text_no_complete_macth') . "</strong></div>";
                                                    }
                                                    ?> 
                                                </div>
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
    </body>
</html>