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
                            <table class="table tr-bordered bg-white box-shadow">
                                <tbody>
                                    <tr class="bg-lightgreen text-white border-0">
                                        <th class=" border-0">User Name</th>
                                        <th class=" border-0">Total Referral</th>
                                    </tr>
                                    <?php foreach ($leaderbord as $leader) { ?>
                                        <tr>
                                            <td><?php echo $leader->user_name; ?></td>
                                            <td><?php echo $leader->tot_referral; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
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