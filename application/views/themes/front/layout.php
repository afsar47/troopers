<!doctype html>
<?php
    if($this->session->userdata('site_lang') && in_array($this->session->userdata('site_lang'),json_decode($this->system->rtl_supported_language,true))) {
        $dir = 'rtl';
    } else {
        $dir = 'ltr';
    }
?>
<html dir='<?php echo $dir; ?>'>
    <head>
        <?php $this->load->view($this->path_to_view_front . 'header');?>
    </head>
    <body >
    <?php
        if($content['page_slug']=='home'){ 
            $this->load->view($this->path_to_view_front.'home');
        }else{ ?>
        <!--START TOP AREA-->
        <header class="top-area" id="home">
            <div class="header-top-area" id="scroolup">
                <!--MAINMENU AREA-->
                <?php $this->load->view($this->path_to_view_front . 'header_body');?>
                <!--END MAINMENU AREA END-->
            </div>
            <div class="page-header d-flex" style="background-image:url('<?php echo  base_url() . $this->page_banner . $page_banner_image; ?>')" >
                <div class="black-overlay"></div>
                <div class="container m-auto">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <h1 class="text-uppercase"><?php echo $page_menutitle; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!--END TOP AREA-->
        <?php
         if(isset($includefile)){ 
             $this->load->view($this->path_to_view_front.$includefile);         
         } else {         
        if($page_content != ""){
        ?>
            <!-- MAIN SECTION -->
            <section class="bm-section-padding about-section bm-light-bg text-dark">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 content">
                            <?php echo $page_content; ?>
                        </div>
                    </div>                
                </div>
            </section>
            <!-- END MAIN SECTION -->
            <?php } 
                }
            }
            ?>
             <!--FOOER AREA-->
        <?php $this->load->view($this->path_to_view_front . 'footer_body');?>
        <!--FOOER AREA END-->
        <?php $this->load->view($this->path_to_view_front . 'footer');?>

        <?php
            if($content['page_slug']=='home' && $this->system->demo_user == 1) {
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
        <script>
            bootbox.alert({            
                message: "<center><p class='text-secondary my-5'><?php echo $this->lang->line('text_licence_note');?></p></center>",
                buttons: {
                    ok: {
                        label: 'Ok',
                        className: 'btn-lightpink',
                        callback: function(){}
                    },            
                }
            });        
        </script>
        <?php
            }
        ?>

    </body> 
        
</html>
