<div class="bm-mdl-footer text-white">
    <nav class="navbar navbar-expand">
        <ul class="navbar-nav">
            <li class="nav-item text-center <?php if ($this->uri->segment('2') == 'refer_earn') echo 'active'; ?>">
                <a href="<?php echo base_url() . $this->path_to_default; ?>refer_earn/" class="nav-link f-18"><?php echo '<div style="max-height: 18px;">' . $this->functions->getPoint() . '</div>' . $this->lang->line('text_earn'); ?></a>
            </li>
            <li class="nav-item text-center <?php if ($this->uri->segment('2') == 'play') echo 'active'; ?>">
                <a href="<?php echo base_url() . $this->path_to_default; ?>play/" class="nav-link f-18"><i class="fa fa-gamepad d-block"></i><?php echo $this->lang->line('text_play'); ?></a>
            </li>
            <li class="nav-item text-center <?php if ($this->uri->segment('2') == 'account') echo 'active'; ?>">
                <a href="<?php echo base_url() . $this->path_to_default; ?>account" class="nav-link f-18"><i class="fa fa-user d-block"></i><?php echo $this->lang->line('text_account'); ?></a>
            </li>
        </ul>
    </nav>
</div>