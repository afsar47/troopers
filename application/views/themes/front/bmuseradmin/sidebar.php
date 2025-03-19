<div id="sidebar-wrapper" class="border-right">
    <ul class="nav flex-column list-group list-group-flush">
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'account') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>account/">
                <i class="fa fa-user"></i>
                <?php echo $this->lang->line('text_account'); ?> <span class="sr-only">(current)</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'play' && $this->uri->segment('3') != 'my_match') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>play/">
                <i class="fa fa-gamepad"></i>
                <?php echo $this->lang->line('text_play'); ?> 
            </a>
        </li>        
        <!--        <li class="nav-item">
                    <a class="nav-link <?php if ($this->uri->segment('2') == 'lottery') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>lottery/">
                        <i class="fa fa-ticket"></i>
                        Lottery 
                    </a>
                </li>-->
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('3') == 'my_match') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>play/my_match/">
                <i class="fa fa-gamepad"></i>               
                <?php echo $this->lang->line('text_my_matches'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('3') == 'my_orders') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>product/my_orders/">
                <i class="fa fa-first-order"></i>
                <?php echo $this->lang->line('text_my_orders'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'wallet') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>wallet/">
                <i class="fa fa-money"></i>
                <?php echo $this->lang->line('text_my_wallet'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'statistics') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>statistics/">
                <i class="fa fa-bar-chart"></i>
                <?php echo $this->lang->line('text_my_statistics'); ?>
            </a>
        </li>        
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'refer_earn') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>refer_earn/">
                <i class="fa fa-product-hunt"></i>
                <?php echo $this->lang->line('text_earn'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'announcement') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>announcement/">
                <i class="fa fa-bullhorn"></i>
                <?php echo $this->lang->line('text_announcement'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'referrals') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>referrals/">
                <i class="fa fa-users"></i>
                <?php echo $this->lang->line('text_my_referrals'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'topplayers') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>topplayers/">
                <i class="fa fa-star"></i>
                <?php echo $this->lang->line('text_top_players'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'leaderbord') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>leaderbord/">
                <i class="fa fa-leanpub"></i>
                <?php echo $this->lang->line('text_leaderboard'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($this->uri->segment('2') == 'apptutorial') echo 'active'; ?>" href="<?php echo base_url() . $this->path_to_default; ?>apptutorial/">
                <i class="fa fa-question-circle"></i>
                <?php echo $this->lang->line('text_app_tutorial'); ?>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>page/index/about-us">
                <i class="fa fa-info-circle"></i>
                <?php echo $this->lang->line('text_about_us'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>page/index/contact">
                <i class="fa fa-headphones"></i>
                <?php echo $this->lang->line('text_customer_supports'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>page/index/terms_conditions">
                <i class="fa fa-file-text-o"></i>
                <?php echo $this->lang->line('text_terms_conditions'); ?>
            </a>
        </li>

    </ul>
</div>