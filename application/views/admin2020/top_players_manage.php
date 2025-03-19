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
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><?php echo $this->lang->line('text_top_players'); ?></h1>
                    </div>
                    <?php foreach ($game_data as $game) { ?>
                        <h4 class="mt-3"><?php echo $game->game_name; ?></h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class=" table-responsive">
                                    <table class=" manage_tbl table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('text_position'); ?></th>
                                                <th><?php echo $this->lang->line('text_player_name'); ?></th>
                                                <th><?php echo $this->lang->line('text_winning') . '(' . $this->functions->getPoint() . ')'; ?></th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                            <?php
                                            $top_players = $this->topplayers->getTopPlayersByGame($game->game_id);
                                            $i = 1;
                                            foreach ($top_players as $top_player) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $i++; ?></td>                                                    
                                                    <td><a href="<?php echo base_url() . $this->path_to_view_admin . 'members/member_detail/' . $top_player->member_id; ?>"> <?php echo $top_player->user_name; ?></a></td>
                                                    <td><?php echo utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $top_player->t_win)); ?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th><?php echo $this->lang->line('text_position'); ?></th>
                                                <th><?php echo $this->lang->line('text_player_name'); ?></th>
                                                <th><?php echo $this->lang->line('text_winning') . '(' . $this->functions->getPoint() . ')'; ?></th>
                                            </tr>    
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>                    
                </div>
                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>
    </body>
</html>