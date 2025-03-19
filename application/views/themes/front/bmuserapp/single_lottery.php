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
                                <a href="<?php echo base_url() . $this->path_to_default . 'lottery'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a>
                                <h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <?php
                            if (isset($lottery['image_name']) && $lottery['image_name'] != "") {
                                $result_lottery_img = base_url() . $this->select_image . 'thumb/253x90_' . $lottery['image_name'];
                            } elseif (isset($lottery['lottery_image']) && $lottery['lottery_image'] != "") {
                                $result_lottery_img = base_url() . $this->lottery_image . 'thumb/1000x500_' . $lottery['lottery_image'];
                            }
                            ?>
                            <div class="bm-mdl-center bm-full-height">
                                <div class="bm-content-listing">
                                    <div class="match-info">
                                        <img src="<?php echo $result_lottery_img; ?>" alt="match-banner" width="100%">                                
                                    </div>
                                    <div class="tab-section game-info">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a class="nav-link text-uppercase active" data-toggle="tab" href="#description"><?php echo $this->lang->line('text_description'); ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link text-uppercase" data-toggle="tab" href="#joinmember"><?php echo $this->lang->line('text_joined_member'); ?></a>
                                            </li>
                                        </ul>                                            
                                        <div class="tab-content ongoing-match">
                                            <div id="description" class="container tab-pane active">
                                                <div class="content-section">
                                                    <div class=" bm-content-listing text-black" >
                                                        <h6 class="bm_text_lightgreen"><?php echo $lottery['lottery_title'] . $this->lang->line('text_for_lottery_id') . $lottery['lottery_id']; ?></h6>                                                            
                                                        <table class="table table-borderless">                                                    
                                                            <tr class=" border-0">
                                                                <td style="vertical-align: middle;"><i class="fa fa-clock-o" style="font-size: 25px;"></i></td>
                                                                <td><?php echo $this->lang->line('text_result_on'); ?> :<br><?php echo $lottery['lottery_time']; ?></td>
                                                            </tr>
                                                            <tr class=" border-0">
                                                                <td style="vertical-align: middle;"><i class="fa fa-trophy" style="font-size: 25px;"></i></td>
                                                                <td><?php echo $this->lang->line('text_play_for'); ?> : <br><?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . $lottery['lottery_prize']; ?></td>
                                                            </tr>
                                                            <tr class=" border-0">
                                                                <td style="vertical-align: middle;"><i class="fa fa-ticket" style="font-size: 25px;"></i></td>
                                                                <td><?php echo $this->lang->line('text_fees'); ?> :<br><?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . $lottery['lottery_fees']; ?></td>
                                                            </tr>
                                                        </table>
                                                        <h6 class="bm_text_lightgreen mt-3"><?php echo $this->lang->line('text_about_lottery'); ?></h6>
                                                        <span><?php echo $lottery['lottery_rules']; ?></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div id="joinmember" class="container tab-pane p-0">
                                                <div class="content-section">
                                                    <div class="bm-content-listing text-black" >
                                                        <ul class="list-unstyled member-list">
                                                            <?php
                                                            $i = 0;
                                                            foreach ($lottery_participate_data as $lottery_participate) {
                                                                ?>
                                                                <li ><?php
                                                                    echo ++$i . ' . ' . $lottery_participate->user_name;
                                                                    if ($lottery_participate->status == '1' || $lottery_participate->status == 1)
                                                                        echo " <b>(" . $this->lang->line('text_winner') . ")</b>";
                                                                    ?></li>
                                                                <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($lottery['lottery_status'] == 1) { ?>
                                <div class="bm-mdl-footer text-white">
                                    <?php if ($lottery['join_status']) { ?>
                                        <a style='cursor:auto;'  class="btn btn-sm btn-block f-18 btn-lightpink text-uppercase"> <?php echo $this->lang->line('text_already_registered'); ?> </a>
                                    <?php } else { ?>
                                        <a <?php echo ($lottery['total_joined'] >= $lottery['lottery_size']) ? '' : "href='" . base_url() . $this->path_to_default . 'lottery/join/' . $lottery['lottery_id'] . "'"; ?> <?php if ($lottery['total_joined'] >= $lottery['lottery_size']) echo "style='cursor:auto;'"; ?> class="btn btn-sm btn-block f-18 btn-lightpink text-uppercase"> <?php echo $this->lang->line('text_register'); ?> </a>
                                    <?php } ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
                </div>
            </div>
        </main>
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