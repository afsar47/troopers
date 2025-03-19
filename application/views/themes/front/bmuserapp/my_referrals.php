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
                                    <div class="bm-content-listing">
                                        <div class="sumery bg-white rounded-bottom shadow-sm">
                                            <span class="btn-green rounded-top p-10 text-center f-18 btn-block text-uppercase"><?php echo $this->lang->line('text_my_referral_summary'); ?></span>
                                            <div class="container row  text-black p-3 text-center">
                                                <div class="col-6">
                                                    <?php echo $this->lang->line('text_referrals'); ?> <br>
                                                    <?php echo $tot_referral['total_ref']; ?>
                                                </div>
                                                <div class="col-6">
                                                    <?php echo $this->lang->line('text_earnings'); ?> <br>
                                                    <?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_earnings['total_earning'])); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="sumery mt-3 bg-white rounded-bottom shadow-sm">
                                            <span class="btn-green rounded-top p-10 text-center f-18 btn-block text-uppercase"><?php echo $this->lang->line('text_my_referral_list'); ?></span>
                                            <table class="table table-responsive text-center">                                                    
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th scope="col"><?php echo $this->lang->line('text_date'); ?></th>
                                                        <th scope="col"><?php echo $this->lang->line('text_player_name'); ?></th>
                                                        <th scope="col"><?php echo $this->lang->line('text_status'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-black">
                                                    <?php
                                                    $i = 0;
                                                    if (!empty($my_referrals)) {
                                                        foreach ($my_referrals as $my_referral) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $my_referral->created_date; ?></td>
                                                                <td><?php echo $my_referral->user_name; ?></td>
                                                                <td><?php
                                                                    if ($my_referral->member_status == '1' && $my_referral->member_package_upgraded == '1') {
                                                                        echo "Rewarded";
                                                                    } else if ($my_referral->member_status == '1') {
                                                                        echo "Registered";
                                                                    } else {
                                                                        echo "Inactive";
                                                                    }
                                                                    ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        echo " <tr class='text-center'><td colspan='3'>" . $this->lang->line('text_no_referral_found') . "</td></tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
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