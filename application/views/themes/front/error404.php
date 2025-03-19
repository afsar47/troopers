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
        <?php $this->load->view($this->path_to_view_front . 'header'); ?>
        <style>

            .error-404 {
                margin-top: 120px;
                margin-bottom: 70px;
                text-align: center;
            }
            .error-404 .error-code {
                /*bottom: 60%;*/
                color: #f07873;
                font-size: 96px;
                line-height: 100px;
                font-weight: bold;
            }
            .error-404 .error-desc {
                font-size: 12px;
                color: #647788;
            }
            .error-404 .m-b-10 {
                margin-bottom: 10px!important;
            }
            .error-404 .m-b-20 {
                margin-bottom: 20px!important;
            }
            .error-404 .m-t-20 {
                margin-top: 20px!important;
            }
            #page-content-wrapper {
                display: flex;
                -webkit-flex-direction: column;
                flex-direction: column;
            }
            #page-content-wrapper {
                -webkit-flex-grow: 1;
                flex-grow: 1;
            }
        </style>
    </head>
    <body >
        <header class="top-area" id="home">
            <div class="header-top-area" id="scroolup">
                <!--MAINMENU AREA-->
                <?php $this->load->view($this->path_to_view_front . 'header_body'); ?>
                <!--END MAINMENU AREA END-->
            </div>
        </header>
        <div class="container m-auto" id="page-content-wrapper">
            <div class="row align-items-center">
                <div class="col-md-12 text-center">
                    <div class="error-404">
                        <div class="error-code"><?php echo $this->lang->line('404'); ?> <i class="fa fa-warning"></i></div>
                        <h2 class="text-dark"><?php echo $this->lang->line('text_oops_404'); ?> </h2>
                        <p class="text-dark">
                            <?php echo $this->lang->line('text_oops_404_desc'); ?>
                        </p>
                        <div>
                            <!-- <a class=" login-detail-panel-button btn" href="http://vultus.de/"> -->
                            <a href="<?php echo base_url(); ?>" class="bm_button1"><span class="glyphicon glyphicon-home"></span> <?php echo $this->lang->line('text_back_to_home'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--FOOER AREA-->
        <?php $this->load->view($this->path_to_view_front . 'footer_body'); ?>
        <!--FOOER AREA END-->
        <?php $this->load->view($this->path_to_view_front . 'footer'); ?>
    </body> 

</html>
