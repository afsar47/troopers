<script type="text/javascript">
    var TableDatatablesManaged = function () {
        var initTable1 = function () {
            var table = $('#manage_tbl');
            table.DataTable({
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "dom": "<'row mb-2 float-right' <'col-md-12'B>><'clearfix'><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",                
                buttons: [
                    {
                        extend: 'print',  
                        title:'',                      
                        exportOptions: {
                            columns: ['thead th:not(:first-child)']
                        }
                    },
                    {
                        extend: 'csv',                                                              
                        title:'',                      
                        exportOptions: {
                            columns: ['thead th:not(:first-child)']
                        }
                    },
                    {
                        extend: 'excel',                                                              
                        title:'',                      
                        exportOptions: {
                            columns: ['thead th:not(:first-child)']
                        }
                    },
                    {
                        extend: 'pdf',                                                              
                        title:'',                      
                        exportOptions: {
                            columns: ['thead th:not(:first-child)']
                        }
                    },
                    {
                        extend: 'copy',                                                              
                        title:'',                      
                        exportOptions: {
                            columns: ['thead th:not(:first-child)']
                        }
                    },                    
                ],
                "ajax": {
                    url: '<?php echo base_url() . $this->path_to_view_admin ?>lottery/setDatatableLotteryMember/<?php echo $this->uri->segment('4');?>', // json datasource
                    type: "post", // method  , by default get                   
                    error: function () {  // error handling
                        $("#manage_tbl").html("");
                        $("#manage_tbl").append('<tbody class=""><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#manage_tbl").css("display", "none");
                    }
                },
                "columnDefs": [{
                        "targets": [0,-1],
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