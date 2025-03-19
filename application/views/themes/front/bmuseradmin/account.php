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
                        <div class="col-lg-3 col-md-6 dash-box">
                            <a href="#">
                                <div class="bg-lightgreen small-box card card-sm-3">
                                    <div class="card-icon ">
                                        <i class="fa fa-gamepad"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4><?php echo $this->lang->line('text_matches_played'); ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <?php echo ($tot_play['total_match'] == '' || $tot_play['total_match'] == null) ? '0' : $tot_play['total_match']; ?>                                        
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 dash-box">
                            <a href="#">
                                <div class="bg-lightblue small-box card card-sm-3">
                                    <div class="card-icon ">
                                        <i class="fa fa-credit-card"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4><?php echo $this->lang->line('text_total_killed'); ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <?php echo ($tot_play['total_kill'] == '' || $tot_play['total_kill'] == null) ? '0' : $tot_play['total_kill']; ?>                                        
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 dash-box">
                            <a href="#">
                                <div class="bg-lightpink small-box card card-sm-3">
                                    <div class="card-icon ">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4><?php echo $this->lang->line('text_amount_won'); ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_play['total_win'])); ?>                                        
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 dash-box">
                            <a href="#">
                                <div class="bg-lightgreen small-box card card-sm-3">
                                    <div class="card-icon ">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4><?php echo $this->lang->line('text_balance'); ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_balance['join_money'] + $tot_balance['wallet_balance'])); ?>                                        
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>
        <script>
            function copyToClipboard(element) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).text()).select();
                document.execCommand("copy");
                $(".copied").text("Copied to clipboard").show().fadeOut(1200);
                $temp.remove();
            }
        </script>
    </body>
</html>