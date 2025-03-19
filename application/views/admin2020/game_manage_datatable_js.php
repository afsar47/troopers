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
                            columns: ['thead th:not(:first-child,:last-child)']
                        }
                    },
                    {
                        extend: 'csv',                                                              
                        title:'',                      
                        exportOptions: {
                            columns: ['thead th:not(:first-child,:last-child)']
                        }
                    },
                    {
                        extend: 'excel',                                                              
                        title:'',                      
                        exportOptions: {
                            columns: ['thead th:not(:first-child,:last-child)']
                        }
                    },
                    {
                        extend: 'pdf',                                                              
                        title:'',                      
                        exportOptions: {
                            columns: ['thead th:not(:first-child,:last-child)']
                        }
                    },
                    {
                        extend: 'copy',                                                              
                        title:'',                      
                        exportOptions: {
                            columns: ['thead th:not(:first-child,:last-child)']
                        }
                    },                    
                ],
                "ajax": {
                    url: '<?php echo base_url() . $this->path_to_view_admin ?>game/setDatatableGame', // json datasource
                    type: "post", // method  , by default get                   
                    error: function () {  // error handling
                        $("#manage_tbl").html("");
                        $("#manage_tbl").append('<tbody class=""><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#manage_tbl").css("display", "none");
                    }
                },
                "columnDefs": [{
                        "targets": [0,1,-1],
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

// Check all 
$('#checkall').click(function(){    
    if($(this).is(':checked')){
       $('.all_inputs').prop('checked', true);
    }else{
       $('.all_inputs').prop('checked', false);
    }
});

$('.multi_action').change(function(){

    if($(this).val() != ''){
        var ids_arr = [];

        $("input:checkbox[class=all_inputs]:checked").each(function () {
            ids_arr.push($(this).val());
        });
        
        if(ids_arr.length > 0){
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure to perform action ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var action = $('.multi_action').val();
                    $.ajax({
                        url: '<?php echo base_url() . $this->path_to_view_admin ?>game/multi_action', // json datasource                    
                        type: 'post',
                        data: {action: action,ids: ids_arr},
                        success: function(response){
                            if(response == ''){
                                location.reload();
                            } else {
                                toastr.error(response);
                                setTimeout(function(){
                                    location.reload();
                                }, 1500);
                            }
                        }
                    });
                }
            });
        } else {
            $('.multi_action').val('');
            toastr.error("<?php echo $this->lang->line('text_err_select_data_for_action')?>");
        }    
    }
});
</script>