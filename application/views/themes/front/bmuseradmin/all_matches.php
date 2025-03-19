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
        <style>
            .modal-div{
                cursor: pointer;
            }
        </style>
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
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">                                                                       
                            <div class="modal-content">
                                <div class="modal-header bg-lightgreen text-white">
                                    <div class="modal-title d-block">
                                        <h5 class="mb-0"><?php echo $this->lang->line('text_prize_pool'); ?></h5>
                                        <p class="mb-0" id="match-name"></p>
                                    </div>
                                    <a href="" class="text-white" data-dismiss="modal">x</a>
                                </div>
                                <div class="modal-body" id="modal-body">
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mt-3">
                                <div class="card-body dashboard-tabs p-0 bg-lightgray" id="tabs-1">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link " id="onGoing-tab" data-toggle="tab" href="#onGoing" role="tab" aria-controls="OnGoing" aria-selected="true"><?php echo $this->lang->line('text_ongoing'); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" id="upcoming-tab" data-toggle="tab" href="#upcoming" role="tab" aria-controls="Upcoming" aria-selected="false"><?php echo $this->lang->line('text_upcoming'); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="results-tab" data-toggle="tab" href="#results" role="tab" aria-controls="Results" aria-selected="false"><?php echo $this->lang->line('text_results'); ?></a>
                                        </li>
                                    </ul>
                                    <div class="tab-content py-0 px-0">
                                        <div class="tab-pane fade show" id="onGoing" role="tabpanel" aria-labelledby="onGoing-tab">
                                            <div class="d-flex flex-wrap justify-content-xl-between">
                                                <div class="border-md-right flex-grow-1 p-3 item">
                                                    <div class="row">
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
                                                                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                                                    <div class="tour-card card br-5 overflow-hidden">
                                                                        <a href="<?php echo base_url() . $this->path_to_default . 'play/match_detail/' . $ongoing_match->m_id; ?>"><img src="<?php echo $ongoing_match_img; ?>" class="img-fluid card-img-top"></a>
                                                                        <div class="card-body">

                                                                            <span class="badge bg-lightpink p-2 text-white"><?php echo $ongoing_match->type ?> </span>
                                                                            <span class="badge bg-lightblue p-2 text-white"><?php echo $ongoing_match->MAP ?> </span>
                                                                            <h6 class="card-title mt-3"><i class="fa fa fa-bomb"></i> <?php echo $ongoing_match->match_name . $this->lang->line('text_for_macth_id') . $ongoing_match->m_id; ?></h6>
                                                                            <?php if ($ongoing_match->room_description && $ongoing_match->join_status == true) {
                                                                                ?>
                                                                                <a href="<?php echo base_url() . $this->path_to_default . 'play/match_detail/' . $ongoing_match->m_id; ?>" class="row bg-lightgreen text-white px-2 "><?php echo $this->lang->line('text_click_for_id_pass'); ?></a>
                                                                            <?php } ?>
                                                                            <div class="row border-bottom">

                                                                                <?php
                                                                                $width = ($ongoing_match->no_of_player / $ongoing_match->number_of_position) * 100;
                                                                                ?>
                                                                                <div class="col-8 m-auto">
                                                                                    <div class="progress" style="height:5px;" >
                                                                                        <div class="progress-bar progress-bar-striped bm-bg-lightpink" style="width:<?php echo $width; ?>%; height:5px; border:1px solid #f07873"></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-4 text-center">
                                                                                    <div class="bm-card-info-item"><span class="text-secondary "><?php echo $ongoing_match->no_of_player . "/" . $ongoing_match->number_of_position; ?></span></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row border-bottom mb-3">
                                                                                <div class="col-md-4">
                                                                                    <div class="bm-card-table-item text-center">                                                
                                                                                        <span class="bm-card-table-item-default text-lightgreen"><?php echo $ongoing_match->match_time; ?></span>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" value="<?php echo $ongoing_match->match_name . $this->lang->line('text_for_macth_id') . $ongoing_match->m_id; ?>">
                                                                                <div class="col-md-4 modal-div" <?php
                                                                                if ($ongoing_match->prize_description != '') {
                                                                                    echo " data-target='#myModal' data-toggle='modal' data-id='" . $ongoing_match->prize_description . "'";
                                                                                }
                                                                                ?>>
                                                                                    <div class="bm-card-table-item text-center">
                                                                                        <span class="bm-card-table-item-default text-primary text-uppercase"><?php echo $this->lang->line('text_prize_pool'); ?></span>
                                                                                        <span class="bm-card-table-item-default text-primary"><i class=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $ongoing_match->win_prize; ?></span>
                                                                                        <?php
                                                                                        if ($ongoing_match->prize_description != '') {
                                                                                            echo "<i class='fa fa-angle-down'></i>";
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <div class="bm-card-table-item text-center">
                                                                                        <span class="bm-card-table-item-default text-lightblue text-uppercase"><?php echo $this->lang->line('text_per_kill'); ?></span><br>
                                                                                        <span class="bm-card-table-item-default text-lightblue"><i class="text-lightblue"><?php echo $this->functions->getPoint(); ?></i> <?php echo $ongoing_match->per_kill; ?></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <a href="<?php echo $ongoing_match->match_url; ?>" target="_blank" class="btn btn-sm btn-block btn-primary"><i class="text-white"><?php echo $this->functions->getPoint(); ?></i> <?php echo $ongoing_match->entry_fee . $this->lang->line('text_Spactate'); ?></a>
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                                <?php
                                                            }
                                                        } else {
                                                            echo "<div class='col-md-12 text-center'><strong>" . $this->lang->line('text_no_live_macth') . "</strong></div>";
                                                        }
                                                        ?>   
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                                            <div class="d-flex flex-wrap justify-content-xl-between">                                                
                                                <div class=" border-md-right flex-grow-1 p-3 item">
                                                    <div class="row">
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
                                                                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                                                    <div class="tour-card card br-5 overflow-hidden">
                                                                        <?php if ($upcoming_match->pin_match == '1') { ?>
                                                                            <span class="pin-match"><i class="fa fa-thumb-tack text-primary" aria-hidden="true"></i></span>
                                                                        <?php } ?>
                                                                        <a href="<?php echo base_url() . $this->path_to_default . 'play/match_detail/' . $upcoming_match->m_id; ?>"><img src="<?php echo $upcoming_match_img; ?>" class="img-fluid card-img-top" ></a>
                                                                        <div class="card-body">

                                                                            <span class="badge bg-lightpink p-2 text-white"><?php echo $upcoming_match->type ?> </span>
                                                                            <span class="badge bg-lightblue p-2 text-white"><?php echo $upcoming_match->MAP ?> </span>
                                                                            <h6 class="card-title mt-3"><i class="fa fa fa-bomb"></i> <?php echo $upcoming_match->match_name . $this->lang->line('text_for_macth_id') . $upcoming_match->m_id; ?></h6>
                                                                            <?php if ($upcoming_match->room_description && $upcoming_match->join_status == true) {
                                                                                ?>
                                                                                <a href="<?php echo base_url() . $this->path_to_default . 'play/match_detail/' . $upcoming_match->m_id; ?>" class="row bg-lightgreen text-white px-2 "><?php echo $this->lang->line('text_click_for_id_pass'); ?></a>
                                                                            <?php } ?>
                                                                            <div class="row border-bottom">

                                                                                <?php
                                                                                $width = ($upcoming_match->no_of_player / $upcoming_match->number_of_position) * 100;
                                                                                ?>
                                                                                <div class="col-8 m-auto">
                                                                                    <div class="progress" style="height:5px;" >
                                                                                        <div class="progress-bar progress-bar-striped bm-bg-lightpink" style="width:<?php echo $width; ?>%; height:5px; border:1px solid #f07873"></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-4 text-center">
                                                                                    <div class="bm-card-info-item"><span class="text-secondary "><?php echo $upcoming_match->no_of_player . "/" . $upcoming_match->number_of_position; ?></span></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row border-bottom mb-3">
                                                                                <div class="col-md-4">
                                                                                    <div class="bm-card-table-item text-center">                                                
                                                                                        <span class="bm-card-table-item-default text-lightgreen"><?php echo $upcoming_match->match_time; ?></span>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" value="<?php echo $upcoming_match->match_name . $this->lang->line('text_for_macth_id') . $upcoming_match->m_id; ?>">
                                                                                <div class="col-md-4 modal-div" <?php
                                                                                if ($upcoming_match->prize_description != '') {
                                                                                    echo " data-target='#myModal' data-toggle='modal' data-id='" . $upcoming_match->prize_description . "'";
                                                                                }
                                                                                ?>>
                                                                                    <div class="bm-card-table-item text-center">
                                                                                        <span class="bm-card-table-item-default text-primary text-uppercase"><?php echo $this->lang->line('text_prize_pool'); ?></span>
                                                                                        <span class="bm-card-table-item-default text-primary"><i class=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $upcoming_match->win_prize; ?></span>
                                                                                        <?php
                                                                                        if ($upcoming_match->prize_description != '') {
                                                                                            echo "<i class='fa fa-angle-down'></i>";
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <div class="bm-card-table-item text-center">
                                                                                        <span class="bm-card-table-item-default text-lightblue text-uppercase"><?php echo $this->lang->line('text_per_kill'); ?></span><br>
                                                                                        <span class="bm-card-table-item-default text-lightblue"><i class="text-lightblue"><?php echo $this->functions->getPoint(); ?></i> <?php echo $upcoming_match->per_kill; ?></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <?php if ($upcoming_match->join_status) { ?>
                                                                                <a style='cursor:auto;' class="btn btn-sm btn-block bg-lightgreen text-white text-uppercase"> <i class="text-white"><?php echo $this->functions->getPoint(); ?></i> <?php echo $upcoming_match->entry_fee; ?> <?php echo $this->lang->line('text_btn_joined'); ?> </a>
                                                                            <?php } else if ($upcoming_match->no_of_player >= $upcoming_match->number_of_position) { ?>
                                                                                <button disabled="" class="btn btn-sm btn-block bg-primary text-white text-uppercase"> <i class="text-white"><?php echo $this->functions->getPoint(); ?></i> <?php echo $upcoming_match->entry_fee; ?> <?php echo $this->lang->line('text_btn_join'); ?> > </button>
                                                                            <?php } else { ?> 
                                                                                <a href="<?php echo base_url() . $this->path_to_default . 'play/select_position/' . $upcoming_match->m_id; ?>" class="btn btn-sm btn-block bg-primary text-white text-uppercase"> <i class="text-white"><?php echo $this->functions->getPoint(); ?></i> <?php echo $upcoming_match->entry_fee; ?> <?php echo $this->lang->line('text_btn_join'); ?> > </a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                                <?php
                                                            }
                                                        } else {
                                                            echo "<div class='col-md-12 text-center'><strong>" . $this->lang->line('text_no_upcoming_macth') . "</strong></div>";
                                                        }
                                                        ?> 
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="tab-pane fade show " id="results" role="tabpanel" aria-labelledby="results-tab">
                                            <div class="d-flex flex-wrap justify-content-xl-between">                                                
                                                <div class=" border-md-right flex-grow-1 p-3 item">
                                                    <div class="row">
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
                                                                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                                                    <div class="tour-card card br-5 overflow-hidden">
                                                                        <a href="<?php echo base_url() . $this->path_to_default . 'play/match_detail/' . $result_match->m_id; ?>"><img src="<?php echo $result_match_img; ?>" class="img-fluid card-img-top" ></a>
                                                                        <div class="card-body">
                                                                            <span class="badge bg-lightpink p-2 text-white"><?php echo $result_match->type ?> </span>
                                                                            <span class="badge bg-lightblue p-2 text-white"><?php echo $result_match->MAP ?> </span>
                                                                            <h6 class="card-title mt-3"><i class="fa fa-bomb"></i> <?php echo $result_match->match_name . $this->lang->line('text_for_macth_id') . $result_match->m_id; ?></h6>
                                                                            <div class="row border-bottom">

                                                                                <?php
                                                                                $width = ($result_match->no_of_player / $result_match->number_of_position) * 100;
                                                                                ?>
                                                                                <div class="col-8 m-auto">
                                                                                    <div class="progress" style="height:5px;" >
                                                                                        <div class="progress-bar progress-bar-striped bm-bg-lightpink" style="width:<?php echo $width; ?>%; height:5px; border:1px solid #f07873"></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-4 text-center">
                                                                                    <div class="bm-card-info-item"><span class="text-secondary "><?php echo $result_match->no_of_player . "/" . $result_match->number_of_position; ?></span></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row border-bottom mb-3">
                                                                                <div class="col-md-4">
                                                                                    <div class="bm-card-table-item text-center">                                                
                                                                                        <span class="bm-card-table-item-default text-lightgreen"><?php echo $result_match->match_time; ?></span>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" value="<?php echo $result_match->match_name . $this->lang->line('text_for_macth_id') . $result_match->m_id; ?>">
                                                                                <div class="col-md-4 modal-div" <?php
                                                                                if ($result_match->prize_description != '') {
                                                                                    echo " data-target='#myModal' data-toggle='modal' data-id='" . $result_match->prize_description . "'";
                                                                                }
                                                                                ?>>
                                                                                    <div class="bm-card-table-item text-center">
                                                                                        <span class="bm-card-table-item-default text-primary text-uppercase"><?php echo $this->lang->line('text_prize_pool'); ?></span>
                                                                                        <span class="bm-card-table-item-default text-primary"><i class=""><?php echo $this->functions->getPoint(); ?></i> <?php echo $result_match->win_prize; ?></span>
                                                                                        <?php
                                                                                        if ($result_match->prize_description != '') {
                                                                                            echo "<i class='fa fa-angle-down'></i>";
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <div class="bm-card-table-item text-center">
                                                                                        <span class="bm-card-table-item-default text-lightblue text-uppercase"><?php echo $this->lang->line('text_per_kill'); ?></span><br>
                                                                                        <span class="bm-card-table-item-default text-lightblue"><i class="text-lightblue"><?php echo $this->functions->getPoint(); ?></i> <?php echo $result_match->per_kill; ?></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-6 mb-1">   
                                                                                    <a href="<?php echo $result_match->match_url; ?>" target="_blank" class="btn btn-sm btn-block btn-primary"> <?php echo $this->lang->line('text_watch_macth'); ?></a>
                                                                                </div>
                                                                                <div class="col-md-6">   
                                                                                    <?php if ($result_match->join_status) { ?>
                                                                                        <a href="" class="btn btn-sm btn-block bg-lightgreen text-white"> <i class="text-white"><?php echo $this->functions->getPoint(); ?></i> <?php echo $result_match->entry_fee; ?> <?php echo $this->lang->line('text_btn_joined'); ?></a>
                                                                                    <?php } else { ?>
                                                                                        <a href="" class="btn btn-sm btn-block bg-primary text-white"> <i class="text-white"><?php echo $this->functions->getPoint(); ?></i> <?php echo $result_match->entry_fee; ?> <?php echo $this->lang->line('text_btn_not_joined'); ?></a>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                                <?php
                                                            }
                                                        } else {
                                                            echo "<div class='col-md-12 text-center'><strong>" . $this->lang->line('text_no_complete_macth') . "</strong></div>";
                                                        }
                                                        ?> 
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <br>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
        <script>
            $('.modal-div').on('click', function (e) {
                var data_id = $(this).data("id");
                $("#modal-body").html(data_id);
            });
        </script>
    </body>
</html>