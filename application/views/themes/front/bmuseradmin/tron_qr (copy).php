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
                    <div class="row d-flex justify-content-md-center">
                        <div class="col-md-6">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $breadcrumb_title; ?></strong></div>
                                <div class="card-body text-center">
                                    <form method="POST" enctype="multipart/form-data" action="<?php echo base_url() . $this->path_to_default ?>wallet/tron_qr/" id="tron_qr-form" >                                           
                                        <div class="row">                                            
                                            <div class="form-group col-md-12">
                                                <input type="hidden" name="order_id" value="<?php if (isset($order_id)) echo $order_id; ?>" readonly>
                                                <input type="hidden" name="payment_method" value="<?php if (isset($payment_method)) echo $payment_method; ?>" readonly>
                                                <div class="mb-2"><?php echo $this->lang->line('text_tron_scan_note');?></div>
                                                <h5 id="timer"></h5>                                                
                                            </div> 

                                            <div class="form-group col-md-12">
                                                <div id="qrcode"></div>
                                                <div class="input-group mt-4">
                                                    <input id="contract_address" class="border h-auto rounded-0 form-control text-center" value="<?php if (isset($wallet_address)) echo $wallet_address; ?>" readonly>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-lightpink copy" type="button" onclick="copyToClipBoard()"><?php echo $this->lang->line('text_copy_to_clipboard'); ?></button>  
                                                    </div>
                                                </div>                                               
                                            </div>       
                                        </div>                                          
                                        <div class="form-group text-center">
                                            <button type="submit" value="<?php echo $this->lang->line('text_add_money'); ?>" name="add_money" class="btn btn-lightpink"><?php echo $this->lang->line('text_next'); ?></button>
                                            <a href="<?php echo base_url() . $this->path_to_default ?>wallet/tron_qr/<?php echo $order_id?>" class="btn btn-secondary"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                                                 
                                        </div> 
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_default . 'footer_body'); ?>
            </div>            
        </div>
        <?php $this->load->view($this->path_to_view_default . 'footer'); ?>        
        <script>
            function startTimer(duration) {                
                var timer = duration, minutes, seconds;
                var end = setInterval(function () {
                    
                    minutes = Math.floor(timer / 60);
                    seconds = timer % 60;

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;                    
                    
                    document.getElementById('timer').innerHTML = minutes + ':' + seconds;

                    if (--timer < 0) {
                        window.location = "<?php echo base_url() . $this->path_to_default ?>wallet";  
                        clearInterval(end);                      
                    }
                }, 1000);
            }
                
            function copyToClipBoard() {
                var copyText = $("#contract_address");
                copyText.select();                           
                document.execCommand("copy");                             
            }

            $(document).ready(function () {
                $('#qrcode').qrcode('<?php echo $wallet_address; ?>');

                var duration = 900; // 15 minute                
                startTimer(duration);

            });
        </script>
    </body>
</html>