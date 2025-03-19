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
                    <div class="row d-flex">
                        <div class="col-md-6 mb-3">
                            <div class="card text-dark border-0 rounded box-shadow">
                                <div class="card-header bg-lightgreen text-white text-center border-0 text-capitalize"><strong><?php echo $this->lang->line('text_total_balance'); ?></strong></div>
                                <div class="card-body border-0 bg-lightgreenlight rounded-bottom">
                                    <div class="row">
                                        <div class="col-6 sm-text-center">
                                            <h5><?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $member['join_money'] + $member['wallet_balance'])); ?></h5>
                                            <div><?php echo $this->lang->line('text_win_money'); ?> : <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $member['wallet_balance'])); ?></div>
                                            <div><?php echo $this->lang->line('text_join_money'); ?> : <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $member['join_money'])); ?></div>
                                        </div>
                                        <div class="col-6 text-right">
                                            <div><a href="<?php echo base_url() . $this->path_to_default . 'wallet/addmoney'; ?>" class="btn btn-primary mb-1 px-4"> <?php echo $this->lang->line('text_action_add'); ?></a></div>
                                            <div><a href="<?php echo base_url() . $this->path_to_default . 'wallet/withdraw'; ?>" class="btn btn-primary px-2"> <?php echo $this->lang->line('text_withdraw'); ?></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card text-dark border-0 rounded box-shadow text-center" style="height: 100%">
                                <div class="card-header bg-lightgreen text-white border-0"><strong><?php echo $this->lang->line('text_earnings'); ?></strong></div>
                                <div class="card-body border-0 bg-lightgreenlight rounded-bottom align-middle">
                                    <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_play['total_win'])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3" >
                            <div class="card text-dark border-0 rounded box-shadow m-auto text-center" style="height: 100%">
                                <div class="card-header bg-lightgreen text-white border-0"><strong><?php echo $this->lang->line('text_payouts'); ?></strong></div>
                                <div class="card-body border-0 bg-lightgreenlight rounded-bottom align-middle">
                                    <?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $tot_withdraw['tot_withdraw'])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_wallet_history'); ?></strong></div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="manage_tbl">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                    <th><?php echo $this->lang->line('text_transaction_no'); ?></th>
                                                    <th><?php echo $this->lang->line('text_note'); ?></th>
                                                    <th><?php echo $this->lang->line('text_amount') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                    <th><?php echo $this->lang->line('text_join_money') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                    <th><?php echo $this->lang->line('text_win_money') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                    <th><?php echo $this->lang->line('text_date'); ?></th>
                                                </tr>   
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                    <th><?php echo $this->lang->line('text_transaction_no'); ?></th>
                                                    <th><?php echo $this->lang->line('text_note'); ?></th>
                                                    <th><?php echo $this->lang->line('text_amount') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                    <th><?php echo $this->lang->line('text_join_money') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                    <th><?php echo $this->lang->line('text_win_money') . ' (' . $this->functions->getPoint() . ')'; ?></th>
                                                    <th><?php echo $this->lang->line('text_date'); ?></th>
                                                </tr>      
                                            </tfoot>
                                        </table>
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
        <script type="text/javascript">
            var TableDatatablesManaged = function () {
                var initTable1 = function () {
                    var table = $('#manage_tbl');
                    table.DataTable({
                        "autoWidth": false,
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: '<?php echo base_url() . $this->path_to_default ?>wallet/setDatatableWallet', // json datasource
                            type: "post", // method  , by default get                   
                            error: function () {  // error handling
                                $("#manage_tbl").html("");
                                $("#manage_tbl").append('<tbody class=""><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                $("#manage_tbl").css("display", "none");
                            }
                        },
                        "columnDefs": [{
                                "targets": [0, 1, 3],
                                "orderable": false
                            }],
                        order: []

                    });
                }
                return {
                    init: function () {
                        if (!jQuery().dataTable) {
                            return;
                        }
                        initTable1();
                    }
                };
            }();
            jQuery(document).ready(function () {
                TableDatatablesManaged.init();
            });
        </script>
    </body>
</html>