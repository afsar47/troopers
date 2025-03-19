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
                                <a href="<?php echo base_url() . $this->path_to_default . 'account'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a>
                                <h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="bm-mdl-center bm-full-height pb-6">
                                <div class="content-section">
                                    <div class="static-table">
                                        <table class="table table-responsive bg-white">                                                    
                                            <thead class="btn-green">
                                                <tr>
                                                    <th scope="col" style="width: 2%;">#</th>
                                                    <th scope="col" style="width: 50%;"><?php echo $this->lang->line('text_match_info'); ?></th>
                                                    <th scope="col" style="width: 20%;"><?php echo $this->lang->line('text_paid'); ?></th>
                                                    <th scope="col" style="width: 20%;"><?php echo $this->lang->line('text_won'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-black">
                                                <?php
                                                $i= 0;
                                                foreach ($statistic_data as $statistic) { ?>
                                                    <tr>
                                                        <td style="width: 2%;"><?php echo ++$i;?></td>
                                                        <td style="width: 50%;"><?php echo $statistic->match_name;?><br><?php echo $statistic->date_craeted;?></td>
                                                        <td style="width: 20%;"><?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $statistic->entry_fee));?></td>
                                                        <td style="width: 20%;"><?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $statistic->total_win));?></td>
                                                       
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
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