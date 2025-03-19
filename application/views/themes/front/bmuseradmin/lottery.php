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
                        <div class="col-lg-12">
                            <div class="card mt-3">
                                <div class="card-body dashboard-tabs p-0 bg-lightgray" id="tabs-1">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="ongoing-tab" data-toggle="tab" href="#ongoing" role="tab" aria-controls="Upcoming" aria-selected="false">Ongoing</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="results-tab" data-toggle="tab" href="#results" role="tab" aria-controls="Results" aria-selected="false">Results</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content py-0 px-0">                                        
                                        <div class="tab-pane fade show active" id="ongoing" role="tabpanel" aria-labelledby="ongoing-tab">
                                            <div class="d-flex flex-wrap justify-content-xl-between">                                                
                                                <div class=" border-md-right flex-grow-1 p-3 item">
                                                    <div class="row">
                                                        <?php
                                                        if (!empty($ongoing_lottery_data)) {
                                                            foreach ($ongoing_lottery_data as $ongoing_lottery) {
                                                                if (isset($ongoing_lottery->image_name) && $ongoing_lottery->image_name != "") {
                                                                    $ongoing_lottery_img = base_url() . $this->select_image . 'thumb/253x90_' . $ongoing_lottery->image_name;
                                                                } elseif (isset($ongoing_lottery->lottery_image) && $ongoing_lottery->lottery_image != "") {
                                                                    $ongoing_lottery_img = base_url() . $this->lottery_image . 'thumb/1000x500_' . $ongoing_lottery->lottery_image;
                                                                }
                                                                ?>
                                                                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                                                    <div class="tour-card card br-5 overflow-hidden">
                                                                        <span class="join-lottery"> <i style="color:#000;"><?php echo $this->functions->getPoint(); ?></i> <?php echo $ongoing_lottery->lottery_fees; ?> </span>
                                                                        <a href="<?php echo base_url() . $this->path_to_default . 'lottery/lottery_detail/' . $ongoing_lottery->lottery_id; ?>"><img src="<?php echo $ongoing_lottery_img; ?>" class="img-fluid card-img-top" ></a>                                                                     
                                                                        <div class="card-body">
                                                                            <h6 class="card-title"><?php echo $ongoing_lottery->lottery_title . ' - Lottery #' . $ongoing_lottery->lottery_id; ?></h6>
                                                                            <div class="bm_text_lightpink mb-1"><?php echo $ongoing_lottery->lottery_time; ?></div>                                                                           
                                                                            <div class="row">
                                                                                <?php
                                                                                $width = ($ongoing_lottery->total_joined / $ongoing_lottery->lottery_size) * 100;
                                                                                ?>
                                                                                <div class="col-8 m-auto">
                                                                                    <div class="progress" style="height:5px;" >
                                                                                        <div class="progress-bar progress-bar-striped bm-bg-lightpink" style="width:<?php echo $width; ?>%; height:5px; border:1px solid #f07873"></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-4 text-center">
                                                                                    <div class="bm-card-info-item"><span class="text-secondary "><?php echo $ongoing_lottery->total_joined . "/" . $ongoing_lottery->lottery_size; ?></span></div>
                                                                                </div>
                                                                            </div>
                                                                            <?php if ($ongoing_lottery->join_status) { ?>
                                                                                <a style='cursor:auto;' class="btn btn-sm btn-block bg-lightgreen text-white"> <i class="text-white"><?php echo $this->functions->getPoint(); ?></i> <?php echo $ongoing_lottery->lottery_fees; ?> REGISTERED </a>
                                                                            <?php } else if ($ongoing_lottery->total_joined >= $ongoing_lottery->lottery_size) { ?>
                                                                                <button disabled="" class="btn btn-sm btn-block bg-primary text-white"> <i class="text-white"><?php echo $this->functions->getPoint(); ?></i> <?php echo $ongoing_lottery->lottery_fees; ?> REGISTER > </button>
                                                                            <?php } else { ?> 
                                                                                <a href="<?php echo base_url() . $this->path_to_default . 'lottery/join/' . $ongoing_lottery->lottery_id; ?>" class="btn btn-sm btn-block bg-primary text-white"> <i class="text-white"><?php echo $this->functions->getPoint(); ?></i> <?php echo $ongoing_lottery->lottery_fees; ?> REGISTER > </a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <?php
                                                            }
                                                        } else {
                                                            echo "<div class='col-md-12 text-center'><strong>No Upcoming Lottery Fonud</strong></div>";
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
                                                        if (!empty($result_lottery_data)) {
                                                            foreach ($result_lottery_data as $result_lottery) {

                                                                if (isset($result_lottery->image_name) && $result_lottery->image_name != "") {
                                                                    $result_lottery_img = base_url() . $this->select_image . 'thumb/253x90_' . $result_lottery->image_name;
                                                                } elseif (isset($result_lottery->lottery_image) && $result_lottery->lottery_image != "") {
                                                                    $result_lottery_img = base_url() . $this->lottery_image . 'thumb/1000x500_' . $result_lottery->lottery_image;
                                                                }
                                                                ?>
                                                                <div class="col-md-6 col-sm-12 mb-3">
                                                                    <div class="card br-5 hide-over mb-3 p-2" style="min-height: unset;">
                                                                        <a class="row" style="text-decoration:none" href="<?php echo base_url() . $this->path_to_default . 'lottery/lottery_detail/' . $result_lottery->lottery_id; ?>">
                                                                            <div class="col-md-6">
                                                                                <img src="<?php echo $result_lottery_img; ?>" class="img-fluid card-img-top">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <h6 class="mt-2 mb-0 text-dark"><?php echo $result_lottery->lottery_title . ' - Lottery #' . $result_lottery->lottery_id; ?></h6>
                                                                                <div class="mb-1 text-secondary">Draw On : <?php echo date_format(date_create($result_lottery->lottery_time), "d/m/Y"); ?></div>    
                                                                                <div class="mb-1 text-secondary">Won Prize : <?php echo $result_lottery->lottery_prize; ?></div>                                                                            
                                                                                <div class="mb-1 text-secondary">Won By : <?php echo $result_lottery->user_name; ?></div>                                                                            
                                                                            </div>
                                                                        </a>

                                                                    </div> 
                                                                </div>
                                                                <?php
                                                            }
                                                        } else {
                                                            echo "<div class='col-md-12 text-center'><strong>No Complete Lottery Fonud</strong></div>";
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
    </body>
</html>