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
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $breadcrumb_title; ?></strong></div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="manage_tbl">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                    <th><?php echo $this->lang->line('text_match_info'); ?></th>
                                                    <th><?php echo $this->lang->line('text_paid').' ('.$this->functions->getPoint() .')'; ?></th>
                                                    <th><?php echo $this->lang->line('text_won').' ('.$this->functions->getPoint() .')'; ?></th>
                                                    <th><?php echo $this->lang->line('text_date'); ?></th>
                                                </tr>   
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                    <th><?php echo $this->lang->line('text_match_info'); ?></th>
                                                    <th><?php echo $this->lang->line('text_paid').' ('.$this->functions->getPoint() .')'; ?></th>
                                                    <th><?php echo $this->lang->line('text_won').' ('.$this->functions->getPoint() .')'; ?></th>
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
                            url: '<?php echo base_url() . $this->path_to_default ?>statistics/setDatatableStates', // json datasource
                            type: "post", // method  , by default get                   
                            error: function () {  // error handling
                                $("#manage_tbl").html("");
                                $("#manage_tbl").append('<tbody class=""><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                $("#manage_tbl").css("display", "none");
                            }
                        },
                        "columnDefs": [{
                                "targets": [0, 3],
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