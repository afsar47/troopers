<script src="<?php echo $this->admin_js; ?>jquery-3.4.1.slim.min.js" ></script>
<script src="<?php echo $this->admin_js; ?>jquery.min.js" ></script>
<script src="<?php echo $this->admin_js; ?>bootstrap.bundle.min.js" ></script>
<!--<script src="<?php echo $this->admin_js; ?>feather.min.js"></script>-->
<!--<script src="<?php echo $this->admin_js; ?>Chart.min.js"></script>-->
<!--<script src="<?php echo $this->admin_js; ?>dashboard.js"></script>-->
<script src="<?php echo $this->admin_js; ?>jquery-3.3.1.js" ></script>
<script src="<?php echo $this->admin_js; ?>jquery.dataTables.min.js" ></script>
<script src="<?php echo $this->admin_js; ?>dataTables.bootstrap4.min.js" ></script>

<script src="<?php echo $this->admin_js; ?>export/dataTables.buttons.min.js"></script>
<script src="<?php echo $this->admin_js; ?>export/buttons.bootstrap4.min.js" ></script>
<script src="<?php echo $this->admin_js; ?>export/jszip.min.js"></script>
<script src="<?php echo $this->admin_js; ?>export/pdfmake.min.js"></script>
<script src="<?php echo $this->admin_js; ?>export/vfs_fonts.js"></script>
<script src="<?php echo $this->admin_js; ?>export/buttons.html5.min.js"></script>
<script src="<?php echo $this->admin_js; ?>export/buttons.print.min.js"></script>


<script src="<?php echo $this->admin_js; ?>sweetalert2.all.min.js"></script>
<script src = "<?php echo $this->admin_js; ?>toastr.min.js" type = "text/javascript" ></script>
<?php if (isset($member_position)) { ?>
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script> 
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>-->
<?php } ?>
<script>
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
        $('#logo').toggleClass('hide-on-toggle');
        $('#m-logo').toggleClass('show-on-toggle');
    });
</script>
<?php if (isset($admin)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'admin_manage_datatable_js'); ?>    
    <script src="<?php echo $this->js; ?>admin.js" type="text/javascript"></script>

<?php } ?>
<?php if (isset($game)) { ?>
    <script src="<?php echo $this->js; ?>game.js" type="text/javascript"></script>
    <?php $this->load->view($this->path_to_view_admin . 'game_manage_datatable_js'); ?>    
<?php } ?>
<?php if (isset($courier)) { ?>
    <script src="<?php echo $this->js; ?>courier.js" type="text/javascript"></script>

    <?php $this->load->view($this->path_to_view_admin . 'courier_manage_datatable_js'); ?>
<?php } ?>
<?php if (isset($lottery)) { ?>
    <script src="<?php echo $this->js; ?>lottery.js" type="text/javascript"></script>
    <?php $this->load->view($this->path_to_view_admin . 'lottery_manage_datatable_js'); ?>
<?php } ?>
<?php if (isset($lottery_member)) { ?>
    <script src="<?php echo $this->js; ?>lottery.js" type="text/javascript"></script>
    <?php $this->load->view($this->path_to_view_admin . 'lottery_member_manage_datatable_js'); ?>
<?php } ?>
<?php if (isset($announcement)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'announcement_manage_datatable_js'); ?>
<?php } ?>
<?php if (isset($country)) { ?>

    <script src="<?php echo $this->js; ?>country.js" type="text/javascript"></script>

    <?php $this->load->view($this->path_to_view_admin . 'country_manage_datatable_js'); ?>
<?php } ?>
<?php if (isset($image)) { ?>
    <script src="<?php echo $this->js; ?>image.js" type="text/javascript"></script>
    <?php $this->load->view($this->path_to_view_admin . 'image_manage_datatable_js'); ?>
    <script>
    $(document).ready(function () {
        $('#manage_tbl').DataTable();
    });
    </script>
<?php } ?>
<?php if (isset($match)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'match_manage_datatable_js'); ?>
    <script src="<?php echo $this->js; ?>match.js" type="text/javascript"></script>

<?php } ?>
<?php if (isset($match_addedit) || isset($lottery_addedit)) { ?>    
    <script src="<?php echo $this->admin_js; ?>ckeditor/ckeditor.js"></script>    
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script> 
    <script src="<?php echo $this->admin_js; ?>jquery.datetimepicker.js"></script>
    <script  type="text/javascript">
    $('#datetimepicker1').datetimepicker({
        format: 'd/m/Y h:i a',
    });
    </script>
<?php } ?>
<?php if (isset($product)) { ?>
    <script src="<?php echo $this->admin_js; ?>ckeditor/ckeditor.js"></script>    
    <?php $this->load->view($this->path_to_view_admin . 'product_manage_datatable_js'); ?>
    <script src="<?php echo $this->js; ?>product.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($offer)) { ?>
    <script src="<?php echo $this->admin_js; ?>ckeditor/ckeditor.js"></script>    
    <?php $this->load->view($this->path_to_view_admin . 'offer_manage_datatable_js'); ?>
    <script src="<?php echo $this->js; ?>product.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($member)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'member_manage_datatable_js'); ?>
    <script src="<?php echo $this->js; ?>member.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($member_manage)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'member_wallet_detail_datatable_js'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>    
    <script>
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
    });
    </script>
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script>    
<?php } ?>
<?php if (isset($pages_manage)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'page_manage_datatable_js'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>    
    <script>
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
    });
    </script>
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script>    
<?php } ?>
<?php if (isset($tab_content)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'tab_content_manage_datatable_js'); ?>
    <script src="<?php echo $this->js; ?>tab_content.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($howtoplay)) { ?>
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script> 
    <?php $this->load->view($this->path_to_view_admin . 'howtoplay_manage_datatable_js'); ?>
<?php } ?>
<?php if (isset($currency)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'currency_manage_datatable_js'); ?>
    <script src="<?php echo $this->js; ?>currency.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($features)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'features_manage_datatable_js.php'); ?>
<?php } ?>
<?php if (isset($withdraw)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'withdraw_manage_datatable_js'); ?>    
    <script src="<?php echo $this->js; ?>withdraw.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($order)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'order_manage_datatable_js'); ?>    
    <script src="<?php echo $this->js; ?>order.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($withdraw_method)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'withdraw_method_manage_datatable_js'); ?>    
    <script src="<?php echo $this->js; ?>withdraw_method.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($withdraw_method_addedit)) { ?>
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script> 
<?php } ?>
<?php if (isset($license)) { ?>
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script> 
<?php } ?>
<?php if (isset($pgorder)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'pgorder_manage_datatable_js'); ?>   
    <script src="<?php echo $this->js; ?>pgorder.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($register_referral)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'register_referral_manage_datatable_js'); ?>    
<?php } ?>
<?php if (isset($referral)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'referral_manage_datatable_js'); ?>    
<?php } ?>
<?php if (isset($topplayers)) { ?>
    <script>
    $(document).ready(function () {
        $('.manage_tbl').DataTable({
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
        });
    });
    </script>        
<?php } ?>
<?php if (isset($leaderboard)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'leaderboard_manage_datatable_js'); ?>    
<?php } ?>
<?php if (isset($appsetting)) { ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
            <script>
                $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip(
                    {container:'body', trigger: 'hover', placement:"bottom"}
                );
            });
            </script> -->
    <?php $this->load->view($this->path_to_view_admin . 'appupload_manage_datatable_js'); ?>
    <script src="<?php echo $this->js; ?>matchmap.js" type="text/javascript"></script>
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script> 
    <script src="<?php echo $this->admin_js; ?>ckeditor/ckeditor.js"></script>    
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<?php } ?>
<?php if (isset($screenshots)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'screenshot_manage_datatable_js'); ?>
    <script src="<?php echo $this->js; ?>screenshot.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($slider)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'slider_manage_datatable_js'); ?>
    <script src="<?php echo $this->js; ?>slider.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($banner)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'banner_manage_datatable_js'); ?>
    <script src="<?php echo $this->js; ?>banner.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($download)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'download_manage_datatable_js'); ?>
    <script src="<?php echo $this->js; ?>download.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($youtube)) { ?>
    <?php $this->load->view($this->path_to_view_admin . 'youtube_manage_datatable_js'); ?>
    <script src="<?php echo $this->js; ?>youtube.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($custom_notification)) { ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>    
<?php } ?>
<?php if (isset($privacy_policy) || isset($termscondition) || isset($htp_addedit) || isset($order_detail) || isset($slider_addedit) || isset($banner_addedit) || isset($tab_content_addedit) || isset($announcement_addedit) || isset($features) || isset($homeheader) || isset($features_addedit) || isset($refundcancellation) || isset($gamerules) || isset($custom_notification) || isset($matchmap_addedit) || isset($currency_addedit) || isset($currency) || isset($country_addedit) || isset($profilesetting) || isset($change_password) || isset($pgdetail) || isset($download_addedit) || isset($youtube_addedit) || isset($game_addedit) || isset($courier_addedit) || isset($image_addedit) || isset($page_addedit) || isset($product_addedit)) { ?>
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script> 
    <script src="<?php echo $this->admin_js; ?>ckeditor/ckeditor.js"></script>    
<?php } ?>
<!--<script src="<?php echo $this->admin_js; ?>ckeditor.js"></script>--> 
<?php if (isset($ludo_challenge)) { ?>
    <script src="<?php echo $this->js; ?>ludo_challenge.js" type="text/javascript"></script>

    <?php $this->load->view($this->path_to_view_admin . 'ludo_challenge_manage_datatable_js'); ?>
    <script src="<?php echo $this->admin_js; ?>jquery.validate.js"></script> 
<?php } ?>
<?php if (isset($change_password)) { ?>
    <script src="<?php echo $this->admin_js; ?>passtrength.js"></script>    
<?php } ?>
<?php if ($this->system->demo_user == 1) { ?>
    <script>
    $(document).ready(function () {
        $("#page-content-wrapper .container-fluid").prepend("<div class='alert alert-success mt-2'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><button class='close' data-close='alert'></button><strong>Welcome to BattleMania Admin Demo. We have disabled update and delete function for default records in demo version. So don't worry, Everthing will work fine in live version. If you want to test then you can add your own records. </strong></div>");
    });
    </script>
<?php } ?>
