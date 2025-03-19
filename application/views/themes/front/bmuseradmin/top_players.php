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
                    </div> 
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mt-3">
                                <div class="card-body dashboard-tabs p-0 bg-lightgray" id="tabs-1">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <?php
                                        $i = 0;
                                        foreach ($top_players['games'] as $top_player) {
                                            $i++;
                                            ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php if ($i == 1) echo 'active'; ?>" id="onGoing-tab" data-toggle="tab" href="#<?php echo preg_replace('/[^A-Za-z0-9.-]/', '', $top_player->game_name); ?>" role="tab" aria-controls="OnGoing" aria-selected="true"><img style="width:40px;" src="<?php echo base_url() . $this->game_logo_image . "thumb/100x100_" . $top_player->game_logo; ?>"></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="tab-content py-0 px-0">
                                        <?php
                                        $i = 0;
                                        foreach ($top_players['games'] as $top_player) {
                                            $i++;
                                            ?>
                                            <div class="tab-pane fade  <?php if ($i == 1) echo ' active show'; ?>" id="<?php echo preg_replace('/[^A-Za-z0-9.-]/', '', $top_player->game_name); ?>" role="tabpanel" aria-labelledby="onGoing-tab">
                                                <div class="d-flex flex-wrap justify-content-xl-between">
                                                    <div class="border-md-right flex-grow-1 p-3 item">
                                                        <div class="">
                                                            <table class="table tr-bordered bg-white box-shadow rounded-bottom">
                                                                <caption class="bg-lightpink text-white text-center rounded-top" style="caption-side: top;"><?php echo $top_player->game_name; ?></caption>
                                                                <tr class="bg-black text-white border-0">
                                                                    <th class=" border-0">User Name</th>
                                                                    <th class=" border-0">Wining</th>
                                                                </tr>
                                                                <?php foreach ($top_players['players'][$top_player->game_name] as $player) { ?>
                                                                    <tr>
                                                                        <td><?php echo $player->user_name; ?></td>
                                                                        <td><?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $player->winning)); ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>    
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>
                                        <?php } ?>
                                    </div>
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