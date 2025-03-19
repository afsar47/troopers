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
                            <?php
                            if (isset($lottery['image_name']) && $lottery['image_name'] != "") {
                                $result_lottery_img = base_url() . $this->select_image . 'thumb/253x90_' . $lottery['image_name'];
                            } elseif (isset($lottery['lottery_image']) && $lottery['lottery_image'] != "") {
                                $result_lottery_img = base_url() . $this->lottery_image . 'thumb/1000x500_' . $lottery['lottery_image'];
                            }
                            ?>
                            <img src="<?php echo $result_lottery_img; ?>" class="img-fluid img-responsive" >
                            <div class="card my-3">
                                <div class="card-body dashboard-tabs p-0 bg-lightgray" id="tabs-1">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="Description" aria-selected="true">Description</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="join_member-tab" data-toggle="tab" href="#join_member" role="tab" aria-controls="join_member" aria-selected="false">Joined Member</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content py-0 px-0">
                                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                            <div class="d-flex flex-wrap justify-content-xl-between">
                                                <div class="border-md-right flex-grow-1 p-3">
                                                    <h6 class="text-lightgreen"><?php echo $lottery['lottery_title'] . ' - Lottery #' . $lottery['lottery_id']; ?></h6>
                                                    <table class="table table-borderless">                                                    
                                                        <tr class=" border-0">
                                                            <td style="vertical-align: middle;"><i class="fa fa-clock-o" style="font-size: 25px;"></i></td>
                                                            <td>Result On :<br><?php echo $lottery['lottery_time']; ?></td>
                                                        </tr>
                                                        <tr class=" border-0">
                                                            <td style="vertical-align: middle;"><i class="fa fa-trophy" style="font-size: 25px;"></i></td>
                                                            <td>Play For : <br><?php echo $this->functions->getPoint() . ' ' . $lottery['lottery_prize']; ?></td>
                                                        </tr>
                                                        <tr class=" border-0">
                                                            <td style="vertical-align: middle;"><i class="fa fa-ticket" style="font-size: 25px;"></i></td>
                                                            <td>Fees :<br><?php echo $this->functions->getPoint() . ' ' . $lottery['lottery_fees']; ?></td>
                                                        </tr>
                                                    </table>
                                                    <h6 class="text-lightgreen mt-3">About Lottery</h6>
                                                    <span><?php echo $lottery['lottery_rules']; ?></span>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="tab-pane fade show " id="join_member" role="tabpanel" aria-labelledby="join_member-tab">
                                            <div class="d-flex flex-wrap justify-content-xl-between"> 
                                                <div class="border-md-right flex-grow-1 p-3">
                                                    <div class="col-lg-12">
                                                        <table class="table tr-bordered">
                                                            <?php
                                                            $i = 0;
                                                            foreach ($lottery_participate_data as $lottery_participate) {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo ++$i; ?></td>
                                                                    <td><strong><?php echo $lottery_participate->user_name;
                                                            if ($lottery_participate->status == '1' || $lottery_participate->status == 1)
                                                                echo " <b>(Winner)</b>";
                                                            ?></strong></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>

<?php if ($lottery['lottery_status'] == 1) {
    ?>

                                <div class="col-md-6">   
                                    <?php if ($lottery['join_status']) { ?>
                                        <a style='cursor:auto;' class="btn btn-sm bg-primary text-white">ALREADY REGISTERED</a>
                                    <?php } else { ?>
                                        <a <?php echo ($lottery['total_joined'] >= $lottery['lottery_size']) ? '' : "href='" . base_url() . $this->path_to_default . 'lottery/join/' . $lottery['lottery_id'] . "'"; ?> <?php if ($lottery['total_joined'] >= $lottery['lottery_size']) echo "style='cursor:auto;'"; ?> class="btn btn-sm bg-primary text-white"> REGISTER </a>
                                <?php } ?>
                                </div>
                                <?php
                            }
                            ?> 
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