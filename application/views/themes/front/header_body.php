<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark shadow fixed-top nav-bg">
    <div class="container">
        <a class="navbar-brand" href="<?php echo base_url(); ?>">
            <img src="<?php echo base_url() . $this->company_image . "thumb/189x40_" . $this->system->company_logo ?>" alt="logo"/>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <!-- <li class="nav-item <?php if ($this->uri->segment('1') == 'home') echo "active"; ?> ">
                    <a class="nav-link" href="<?php echo base_url(); ?>home">Home</a>
                </li> -->
                <?php
                $pages = $this->functions->getAllPages();
                if (!empty($pages) && count($pages) > 0) {
                    foreach ($pages as $page) {
                        $page_slug = $page->page_slug;
                        $parent_menu = $this->functions->getAllChild($page->page_id);
                        ?>
                        <li class="nav-item <?php
                        if ($this->uri->segment('3') == $page_slug)
                            echo "active";
                        if (is_array($parent_menu) && count($parent_menu) > 0)
                            echo " dropdown";
                        ?>">
                                <?php if (is_array($parent_menu) && count($parent_menu) > 0) { ?>
                                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"><?php echo $page->page_menutitle; ?></a>
                                <div class="dropdown-menu header-dd-menu">
                                    <a class="dropdown-item <?php if ($this->uri->segment('3') == $page_slug) echo "active"; ?>" href="<?php echo base_url() . "page/index/" . $page_slug; ?>"  ><?php echo $page->page_menutitle; ?></a>
                                    <?php foreach ($parent_menu as $pmenu) { ?>
                                        <a class="dropdown-item <?php if ($this->uri->segment('3') == $pmenu->page_slug) echo "active"; ?>" href="<?php echo base_url() . "page/index/" . $pmenu->page_slug; ?>"><?php echo $pmenu->page_menutitle; ?></a>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <a class="nav-link" href="<?php echo base_url() . "page/index/" . $page_slug; ?>"><?php echo $page->page_menutitle; ?></a>
                            <?php } ?>
                        </li>                           
                        <?php
                    }
                }
                ?>
                <li class="nav-item dropdown <?php if ($this->uri->segment(1) == 'register' || $this->uri->segment(1) == 'login') echo "active"; ?>">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"><?php echo $this->lang->line('text_account'); ?></a>
                    <div class="dropdown-menu header-dd-menu">
                        <?php if ($this->session->userdata('front_logged_in') !== true) { ?>
                            <a class="dropdown-item <?php if ($this->uri->segment(1) == 'login') echo "active"; ?>" href="<?php echo base_url() ?>login"><?php echo $this->lang->line('text_login'); ?></a>
                            <a class="dropdown-item <?php if ($this->uri->segment(1) == 'register') echo "active"; ?>" href="<?php echo base_url() ?>register"><?php echo $this->lang->line('text_register'); ?></a>
                        <?php }else { ?> 
                            <a class="dropdown-item <?php if ($this->uri->segment(1) == 'dashboard') echo "active"; ?>" href="<?php echo base_url() . $this->path_to_default; ?>account"><?php echo $this->lang->line('text_my_dashboard'); ?></a>
                            <a class="dropdown-item <?php if ($this->uri->segment(1) == 'login') echo "active"; ?>" href="<?php echo base_url() ?>login/logout" onclick="logout();"><?php echo $this->lang->line('text_logout'); ?></a>
                        <?php } ?>
                    </div>
                </li>               
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"><?php echo $this->lang->line('text_language'); ?></a>
                    <div class="dropdown-menu header-dd-menu">   
                        <?php 
                            foreach(json_decode($this->system->supported_language) as $key => $value) {
                        ?>                     
                            <a class="dropdown-item <?php if ($this->session->userdata('site_lang')) { if ($this->session->userdata('site_lang') == $value) echo "active"; } elseif ($value == 'english') { echo "active"; } ?>" href="<?php echo base_url() ?>LanguageSwitcher/switchLang?lang=<?php echo $value; ?>"><?php echo ucfirst($value); ?></a>                                              
                        <?php
                            }
                        ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>