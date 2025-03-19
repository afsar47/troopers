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
                        <div class="col-md-12 mb-4">
                            <div class="card text-dark border-0 rounded" style="height: 100%">
                                <div class="card-header bg-lightgreen text-white border-0 text-center"><strong>My Referrals Summary</strong></div>
                                <div class="card-body box-shadow border-0">
                                    <div class="row">        
                                        <div class="col-md-6 text-center">
                                            Referrals <br>
                                            <?php echo $tot_referral['total_ref']; ?>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            Earnings <br>
                                            <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_earnings['total_earning'])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <table class="table tr-bordered bg-white box-shadow rounded-bottom">
                                <caption class="bg-lightgreen text-white text-center rounded-top" style="caption-side: top;">Winner</caption>
                                <tr class="bg-black text-white">
                                    <th>Date</th>
                                    <th>Player Name</th>
                                    <th>Status</th>
                                </tr>
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
                                    echo " <tr class='text-center'><td colspan='3'>No Referrals Found</td></tr>";
                                }
                                ?>
                            </table>                            
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>        
    </body>
</html>