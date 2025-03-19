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
                                <a href="<?php echo base_url() . $this->path_to_default . 'refer_earn'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a>
                                <p class="badge badge-light float-right f-18 text-black d-inline" id="tot_wallet"><?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', 0); ?></p>
                                <h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="bm-mdl-center bm-full-height">
                                <div class="tab-section single-game">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item" style="width: 50%;">
                                            <a class="nav-link text-uppercase active" data-toggle="tab" href="#ongoing"><?php echo $this->lang->line('text_ongoing'); ?></a>
                                        </li>
                                        <li class="nav-item" style="width: 50%;">
                                            <a class="nav-link text-uppercase " data-toggle="tab" href="#results"><?php echo $this->lang->line('text_results'); ?></a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="ongoing" class="container tab-pane active">
                                            <div class="content-section">
                                                <div class="bm-content-listing tournaments" >
                                                    <?php
                                                    if (!empty($ongoing_lottery_data)) {
                                                        foreach ($ongoing_lottery_data as $ongoing_lottery) {
                                                            if (isset($ongoing_lottery->image_name) && $ongoing_lottery->image_name != "") {
                                                                $ongoing_lottery_img = base_url() . $this->select_image . 'thumb/253x90_' . $ongoing_lottery->image_name;
                                                            } elseif (isset($ongoing_lottery->lottery_image) && $ongoing_lottery->lottery_image != "") {
                                                                $ongoing_lottery_img = base_url() . $this->lottery_image . 'thumb/1000x500_' . $ongoing_lottery->lottery_image;
                                                            }
                                                            ?>
                                                            <div class="card br-5 hide-over mb-3" style="min-height: unset;">
                                                                <span class="lottery-join"> <i><?php echo '<span style="">' . $this->functions->getPoint() . '</span>'; ?></i> <?php echo $ongoing_lottery->lottery_fees; ?> </span>
                                                                <a href="<?php echo base_url() . $this->path_to_default . 'lottery/lottery_detail/' . $ongoing_lottery->lottery_id; ?>"><img src="<?php echo $ongoing_lottery_img; ?>" class="img-fluid card-img-top"></a>
                                                                <div class="card-body">
                                                                    <h6 class="card-title mb-0"><a href="#" class="text-dark"><?php echo $ongoing_lottery->lottery_title . $this->lang->line('text_for_lottery_id') . $ongoing_lottery->lottery_id; ?></a></h6>                                                                    
                                                                    <div class="bm_text_lightpink mb-1"><?php echo $ongoing_lottery->lottery_time; ?></div>
                                                                    <div class="row">
                                                                        <div class="col-8">
                                                                            <?php
                                                                            $width = ($ongoing_lottery->total_joined / $ongoing_lottery->lottery_size) * 100;
                                                                            ?>
                                                                            <span class="text-primary"><?php echo $ongoing_lottery->total_joined . "/" . $ongoing_lottery->lottery_size; ?></span>
                                                                            <div class="progress" style="height:10px;">
                                                                                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $width; ?>%"> </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <?php if ($ongoing_lottery->join_status) { ?>
                                                                                <a style='cursor:auto;' class="btn btn-sm btn-block btn-lightgreen text-uppercase"> <i class="text-white"><?php echo '<span style="">' . $this->functions->getPoint() . '</span>'; ?></i> <?php echo $ongoing_lottery->lottery_fees; ?> <?php echo $this->lang->line('text_registered'); ?> </a>
                                                                            <?php } else if ($ongoing_lottery->total_joined >= $ongoing_lottery->lottery_size) { ?>
                                                                                <button disabled="" class="btn btn-sm btn-block btn-lightpink text-white text-uppercase"> <i class="text-white"><?php echo '<span style="">' . $this->functions->getPoint() . '</span>'; ?></i> <?php echo $ongoing_lottery->lottery_fees; ?> <?php echo $this->lang->line('text_register'); ?> > </button>
                                                                            <?php } else { ?> 
                                                                                <a href="<?php echo base_url() . $this->path_to_default . 'lottery/join/' . $ongoing_lottery->lottery_id; ?>" class="btn btn-sm btn-block btn-lightpink text-uppercase"> <i class="text-white"><?php echo '<span style="">' . $this->functions->getPoint() . '</span>'; ?></i> <?php echo $ongoing_lottery->lottery_fees; ?> <?php echo $this->lang->line('text_register'); ?> > </a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <?php
                                                        }
                                                    } else {
                                                        echo "<div class='col-md-12 text-center text-black'><strong>" . $this->lang->line('text_no_upcoming_lottery') . "</strong></div>";
                                                    }
                                                    ?>                               
                                                </div>

                                            </div>
                                        </div>
                                        <div id="results" class="container tab-pane fade">
                                            <div class="content-section">
                                                <div class="bm-content-listing tournaments" >
                                                    <?php
                                                    if (!empty($result_lottery_data)) {
                                                        foreach ($result_lottery_data as $result_lottery) {
                                                            if (isset($result_lottery->image_name) && $result_lottery->image_name != "") {
                                                                $result_lottery_img = base_url() . $this->select_image . 'thumb/253x90_' . $result_lottery->image_name;
                                                            } elseif (isset($result_lottery->lottery_image) && $result_lottery->lottery_image != "") {
                                                                $result_lottery_img = base_url() . $this->lottery_image . 'thumb/1000x500_' . $result_lottery->lottery_image;
                                                            }
                                                            ?>
                                                            <div class="card br-5 hide-over mb-3 p-2" style="min-height: unset;">
                                                                <a class="row" href="<?php echo base_url() . $this->path_to_default . 'lottery/lottery_detail/' . $result_lottery->lottery_id; ?>">
                                                                    <div class="col-md-6">
                                                                        <img src="<?php echo $result_lottery_img; ?>" class="img-fluid card-img-top">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <h6 class="mt-2 mb-0 text-black"><?php echo $result_lottery->lottery_title . $this->lang->line('text_for_lottery_id') . $result_lottery->lottery_id; ?></h6>
                                                                        <div class="text-black mb-1"><?php echo $this->lang->line('text_draw_on') . ' : ' . date_format(date_create($result_lottery->lottery_time), "d/m/Y"); ?></div>    
                                                                        <div class="text-black mb-1"><?php echo $this->lang->line('text_won_prize') . ' : ' . $result_lottery->lottery_prize; ?></div>                                                                            
                                                                        <div class="text-black mb-1"><?php echo $this->lang->line('text_won_by') . ' : ' . $result_lottery->user_name; ?></div>                                                                            
                                                                    </div>
                                                                </a>

                                                            </div>        
                                                            <?php
                                                        }
                                                    } else {
                                                        echo "<div class='col-md-12 text-center text-black'><strong>" . $this->lang->line('text_no_complete_lottery') . "</strong></div>";
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