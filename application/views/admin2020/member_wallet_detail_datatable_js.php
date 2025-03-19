<script type="text/javascript">
    var TableDatatablesManaged = function () {
        var initTable1 = function () {
            var table = $('#wallet_tbl');
            table.DataTable({
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "dom": "<'row mb-2 float-right' <'col-md-12'B>><'clearfix'><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",                
                buttons: [
                    {
                        extend: 'print',  
                        title:'',                                              
                    },
                    {
                        extend: 'csv',                                                              
                        title:'',                                              
                    },
                    {
                        extend: 'excel',                                                              
                        title:'',                                              
                    },
                    {
                        extend: 'pdf',                                                              
                        title:'',                                              
                    },
                    {
                        extend: 'copy',                                                              
                        title:'',                                              
                    },                    
                ],
                "ajax": {
                    url: '<?php echo base_url() . $this->path_to_view_admin ?>members/setDatatableMemberWallet/<?php echo $this->uri->segment('4');?>', // json datasource
                    type: "post", // method  , by default get                   
                    error: function () {  // error handling
                        $("#manage_tbl").html("");
                        $("#manage_tbl").append('<tbody class=""><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#manage_tbl").css("display", "none");
                    }
                },
                "columnDefs": [{
                        "targets": [0],
                        "orderable": false
                    }],
                order: []

            });
        }
        var initTable2 = function () {
            var table1 = $('#states_tbl');
            table1.DataTable({
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "dom": "<'row mb-2 float-right' <'col-md-12'B>><'clearfix'><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",                
                buttons: [
                    {
                        extend: 'print',  
                        title:'',                                              
                    },
                    {
                        extend: 'csv',                                                              
                        title:'',                                              
                    },
                    {
                        extend: 'excel',                                                              
                        title:'',                                              
                    },
                    {
                        extend: 'pdf',                                                              
                        title:'',                                              
                    },
                    {
                        extend: 'copy',                                                              
                        title:'',                                              
                    },                    
                ],
                "ajax": {
                    url: '<?php echo base_url() . $this->path_to_view_admin ?>members/setDatatableMemberStates/<?php echo $this->uri->segment('4');?>', // json datasource
                    type: "post",                  
                    error: function () {  
                        $("#manage_tbl").html("");
                        $("#manage_tbl").append('<tbody class=""><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#manage_tbl").css("display", "none");
                    }
                },
                "columnDefs": [{
                        "targets": [0],
                        "orderable": false
                    }],
                order: []

            });
        }
        var initTable3 = function () {
            var table2 = $('#referral_tbl');
            table2.DataTable({
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "dom": "<'row mb-2 float-right' <'col-md-12'B>><'clearfix'><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",                
                buttons: [
                    {
                        extend: 'print',  
                        title:'',                                              
                    },
                    {
                        extend: 'csv',                                                              
                        title:'',                                              
                    },
                    {
                        extend: 'excel',                                                              
                        title:'',                                              
                    },
                    {
                        extend: 'pdf',                                                              
                        title:'',                                              
                    },
                    {
                        extend: 'copy',                                                              
                        title:'',                                              
                    },                    
                ],
                "ajax": {
                    url: '<?php echo base_url() . $this->path_to_view_admin ?>members/setDatatableMemberReferral/<?php echo $this->uri->segment('4');?>', // json datasource
                    type: "post",  
                    error: function () {  
                        $("#manage_tbl").html("");
                        $("#manage_tbl").append('<tbody class=""><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#manage_tbl").css("display", "none");
                    }
                },
                "columnDefs": [{
                        "targets": [0],
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
                initTable2();
                initTable3();
            }
        };
    }();      
    jQuery(document).ready(function () {
        TableDatatablesManaged.init();
    });
</script>