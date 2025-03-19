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
                                <a href="<?php echo base_url() . $this->path_to_default . 'account/'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a><h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="bm-mdl-center bm-full-height">
                                <div class="tab-section ">
                                    <ul class="nav nav-tabs">
                                        <?php
                                        $i = 0;
                                        foreach ($top_players['games'] as $top_player) {
                                            $i++;
                                            ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php if ($i == 1) echo 'active'; ?>" data-toggle="tab" href="#<?php echo preg_replace('/[^A-Za-z0-9.-]/', '', $top_player->game_name); ?>"><img style="width:40px;" src="<?php echo base_url() . $this->game_logo_image . "thumb/100x100_" . $top_player->game_logo; ?>"></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="tab-content">
                                        <?php
                                        $i = 0;
                                        foreach ($top_players['games'] as $top_player) {
                                            $i++;
                                            ?>
                                            <div id="<?php echo preg_replace('/[^A-Za-z0-9.-]/', '', $top_player->game_name); ?>" class="container tab-pane fade <?php if ($i == 1) echo ' active show'; ?>">
                                                <div class="content-section">
                                                    <div class="bm-mdl-center bm-full-height pb-6">
                                                        <div class="content-section">
                                                            <div class="static-table">
                                                                <table class="table table-responsive bg-white">  
                                                                    <caption class="btn-lightpink text-white text-center mt-1" style="caption-side: top;"><?php echo $top_player->game_name; ?></caption>
                                                                    <thead class="btn-green">
                                                                        <tr>
                                                                            <th scope="col"><?php echo $this->lang->line('text_user_name'); ?></th>
                                                                            <th scope="col"><?php echo $this->lang->line('text_winning'); ?></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="text-black">
                                                                        <?php foreach ($top_players['players'][$top_player->game_name] as $player) { ?>
                                                                            <tr>
                                                                                <td><?php echo $player->user_name; ?></td>
                                                                                <td><?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $player->winning)); ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php foreach ($top_players['games'] as $top_player) { ?>
                                <!--                                <div class="bm-mdl-center bm-full-height pb-6">
                                                                    <div class="content-section">
                                                                        <div class="static-table">
                                                                            <table class="table table-responsive bg-white">  
                                                                                <caption class="btn-lightpink text-white text-center mt-1" style="caption-side: top;"><?php echo $top_player->game_name; ?></caption>
                                                                                <thead class="btn-green">
                                                                                    <tr>
                                                                                        <th scope="col">User Name</th>
                                                                                        <th scope="col">Wining</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody class="text-black">
                                <?php foreach ($top_players['players'][$top_player->game_name] as $player) { ?>
                                                                                                                <tr>
                                                                                                                    <td><?php echo $player->user_name; ?></td>
                                                                                                                    <td><?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $player->winning)); ?></td>
                                                                                                                </tr>
                                <?php } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>  -->
                            <?php } ?>
                        </div>
                    </div>
                    <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?> 
                </div>
            </div>
        </main>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>        
    </body>
</html>