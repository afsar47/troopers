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
                        <h1 class="h2"><?php echo $this->lang->line('text_payment'); ?></h1>                        
                    </div>
                    <?php if ($this->session->flashdata('notification')) { ?>
                        <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                            <span><?php echo $this->session->flashdata('notification'); ?></span>
                        </div>
                    <?php } ?>
                    <?php if ($this->session->flashdata('error')) { ?>
                        <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><button class="close" data-close="alert"></button>
                            <span><?php echo $this->session->flashdata('error'); ?></span>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12 mt-3" >
                            <form name="frmpaymentlist" method="post" action="<?php echo base_url() . $this->path_to_view_admin ?>pgdetail">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="manage_tbl">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                <th><?php echo $this->lang->line('text_payment_name'); ?></th>
                                                <th><?php echo $this->lang->line('text_status'); ?></th>
                                                <th><?php echo $this->lang->line('text_actions'); ?></th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($pgdetail as $pg) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $pg->payment_name; ?></td>
                                                    <td>
                                                        <?php
                                                        if ($this->system->demo_user == 1) {
                                                            if ($pg->status == '1') {
                                                                echo '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top">Active</span>';
                                                            } else {
                                                                echo '<span class="badge badge-danger" data-original-title="Publish" data-placement="top">Inactive</span>';
                                                            }
                                                        } else {
                                                            if ($pg->status == '1') {
                                                                echo '<span class="badge badge-success" data-original-title="UnPublish" data-placement="top"   style="cursor: pointer" onClick="javascript: changePublishStatus(document.frmpaymentlist,' . $pg->id . ',0);">Active <i class="fa fa-pencil"></i></span>';
                                                            } else {
                                                                echo '<span class="badge badge-danger" data-original-title="Publish" data-placement="top"   style="cursor: pointer" border="0" onClick="javascript: changePublishStatus(document.frmpaymentlist,' . $pg->id . ',1);">Inactive <i class="fa fa-pencil"></i></span>';
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <!--<td><?php echo date_format(date_create($pg->created_date), 'd-m-Y'); ?></td>-->
                                                    <td><a href="<?php echo base_url() . $this->path_to_view_admin . 'pgdetail/edit/' . $pg->id; ?>" style="font-size:18px;" ><i class="fa fa-edit"></i></a></td>
                                                </tr>
                                            <?php }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" name="action" />
                                <input type="hidden" name="paymentid" />
                                <input type="hidden" name="publish" /> 
                            </form>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>
        <script>
            function changePublishStatus(frm, p_id, status)
            {
                with (frm)
                {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Are you sure to change status?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            frm.paymentid.value = p_id;
                            frm.action.value = "change_publish";
                            frm.publish.value = status;
                            frm.submit();
                        }
                    });
                }
            }

            $(document).ready(function () {
                $("#validate").validate({
                    rules: {
                        'payment': {
                            required: true,
                        },
                    },
                    messages: {
                        'payment': {
                            required: "Please select payment",
                        },
                    },
                    errorPlacement: function (error, element)
                    {
                        if (element.is(":file"))
                        {
                            error.insertAfter(element.parent());
                        } else
                        {
                            error.insertAfter(element);
                        }
                    },
                });
            });
        </script>
    </body>
</html>