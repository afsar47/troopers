<!-- Screenshot -->
<section class="bm-section-padding bm-light-bg text-dark" id="howtoinstall">
    <h6 class="text-center bm_section_title text-uppercase"><?php echo $this->lang->line('text_how_to_install'); ?></h6>
    <p class="bm_section_subtitle text-center bm_mb30"><?php echo $this->lang->line('text_app_ss_desc'); ?></p>
    <div class="container">
        <div class="row">
            <div class="owl-carousel owl-theme popup-images py-4">
                <?php 
                foreach ($downloads as $download) {
                    if (file_exists($this->download_image . "thumb/336x600_" . $download->download_image)) {
                        ?>
                        <div class="item">
                            <a href="<?php echo base_url() . $this->download_image . $download->download_image; ?>" class="popup-link">
                                <img src ="<?php echo base_url() . $this->download_image . "thumb/336x600_" . $download->download_image; ?>" >
                            </a>
                        </div>
                    <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>

        