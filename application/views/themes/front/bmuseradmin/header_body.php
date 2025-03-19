
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
            <a class="navbar-brand brand-logo" href="<?php echo base_url(); ?>">
                <img class="hide-on-mobile" src="<?php echo base_url() . $this->company_image . "thumb/189x40_" . $this->system->company_logo ?>" id="logo" alt="logo">
                <img class="show-on-mobile" style="display:none" src="<?php echo base_url() . $this->company_favicon . "thumb/40x40_" . $this->system->company_favicon ?>" id="m-logo" alt="logo">
            </a>
        </div> 
        <button class="btn " id="menu-toggle" type="button" >
            <span class="fa fa-align-left text-white"></span>
        </button>       
    </div>
    <div class="dropdown d-flex justify-content-center">
        <small class="btn text-capitalize text-white" id="tot_wallet"><?php echo '<span style="">' . $this->functions->getPoint() . '</span> ' . sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', 0); ?></small>
        <button type="button" class="btn text-capitalize text-white dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-user-circle-o"></i><span class="hide-on-mobile"> <?php echo $this->member->front_member_username; ?></span>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="<?php echo base_url() . $this->path_to_default; ?>profile/"><i class="fa fa-user"></i> <?php echo $this->lang->line('text_my_profile'); ?></a>
            <a class="dropdown-item" href="<?php echo base_url(); ?>login/logout" onclick="logout();"><i class="fa fa-key"></i> <?php echo $this->lang->line('text_logout'); ?></a>
        </div>
    </div>
</nav>
