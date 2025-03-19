<!-- Footer -->
<footer class="bm-section-padding bm-dark-bg" id="footer">
    <div class="container">
                <?php
                    $pages = $this->functions->getFooterPages();
                    if (count($pages) > 0) {
                ?>
                <div class="row justify-content-center">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                    <ul class="list-unstyled list-inline social text-center mb-0">
                
                <?php
                        foreach ($pages as $page) {
                            $page_slug = $page->page_slug;                            
                ?>                    
                    <li class="list-inline-item"><a class="nav-link" href="<?php echo base_url() . "page/index/" . $page_slug; ?>"><?php echo $page->page_menutitle; ?></a></li>                    
                <?php 
                        }                     
                ?>
                </ul>
                    </div>
                    </div>
                <?php
                    }
                ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <ul class="list-unstyled list-inline social text-center">
                    <?php if($this->system->fb_link != ""){ ?>
                        <li class="list-inline-item"><a href="<?php echo $this->system->fb_link; ?>"><i class="fa fa-facebook"></i></a> </li>
                    <?php } ?>
                    <?php if($this->system->twitter_link != ""){ ?>
                        <li class="list-inline-item"><a href="<?php echo $this->system->twitter_link; ?>"><i class="fa fa-twitter"></i></a> </li>
                    <?php } ?>
                    <?php if($this->system->insta_link != ""){ ?>
                        <li class="list-inline-item"><a href="<?php echo $this->system->insta_link; ?>"><i class="fa fa-instagram"></i></a> </li>
                    <?php } ?>
                    <?php if($this->system->google_link != ""){ ?>
                        <li class="list-inline-item"><a href="<?php echo $this->system->google_link; ?>"><i class="fa fa-google-plus"></i></a> </li>
                    <?php } ?>
                </ul>
            </div>
            </hr>
        </div>
        <div class="row copyright">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center text-white">
                <?php echo $this->system->company_about; ?>
                <p class="h6"><?php echo $this->system->copyright_text; ?></p>
            </div>
            </hr>
        </div>
    </div>
</footer>