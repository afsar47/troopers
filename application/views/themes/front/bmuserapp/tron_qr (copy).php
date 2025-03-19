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
        <main class="bm-full-width bm-full-height">
            <div class="container-fluid">
                <div class="row d-flex">
                    <div class="col-xl-4 col-left">
                        <div class="bm-modal">
                            <div class="bm-mdl-header">
                                <a href="<?php echo base_url() . $this->path_to_default . 'wallet/'; ?>" class="text-white"><i class="fa fa-2x fa-long-arrow-left"></i></a><h4 class="m-0 d-inline align-bottom">&nbsp;&nbsp;<?php echo $breadcrumb_title; ?></h4>                            
                            </div>
                            <div class="bm-mdl-center bm-full-height pb-6">
                                <div class="content-section">
                                    <div class="bm-content-listing">   
                                        <div class="profile-content text-black">
                                            <form method="POST" class="container profile-form mt-2 text-center" action="<?php echo base_url() . $this->path_to_default ?>wallet/tron_qr/" id="tron_qr-form" >
                                                <div class="form-group row">
                                                    <div class="col-12 mt-4">                                                        
                                                        <input type="hidden" name="order_id" value="<?php if (isset($order_id)) echo $order_id; ?>" readonly>
                                                        <input type="hidden" name="payment_method" value="<?php if (isset($payment_method)) echo $payment_method; ?>" readonly>                                                                                                                
                                                        <div class="mb-2"><?php echo $this->lang->line('text_tron_scan_note');?></div>
                                                        <h5 id="timer"></h5>
                                                    </div>                                                      
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-12">                                                        
                                                        <div id="qrcode"></div>

                                                        <div class="input-group mt-4">
                                                            <input id="contract_address" class="border h-auto rounded-0 form-control text-center" value="<?php if (isset($wallet_address)) echo $wallet_address; ?>" readonly>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-lightpink copy" type="button" data-toggle="popover" data-container="body" data-placement="bottom" data-content="Copied" onclick="copyToClipBoard()"><?php echo $this->lang->line('text_copy_to_clipboard'); ?></button>  
                                                            </div>
                                                        </div>
                                                    </div>                                                      
                                                </div>                                                                                                                                              
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                        <button type="submit" value="<?php echo $this->lang->line('text_add_money'); ?>" name="add_money" class="btn btn-lightpink"><?php echo $this->lang->line('text_next'); ?></button>                                                        
                                                    </div>                                            
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>                       
                        </div>
                    </div>
                    <?php $this->load->view($this->path_to_view_default . 'sidebar'); ?>
                </div>
            </div>            
        </main>
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
                $('.copy').popover('show');

                setTimeout(function () {
                    $('.copy').popover('hide');
                }, 2000);            
            }

            $(document).ready(function () {
                $('#qrcode').qrcode('<?php echo $wallet_address; ?>');

                var duration = 900; // 15 minute                
                startTimer(duration);

            });  
                             
        </script>
    </body>
</html>