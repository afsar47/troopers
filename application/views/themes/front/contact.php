<!--CONTACT AREA-->
<section class="bm-section-padding bm-light-bg text-dark" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card cnt-card">
                    <div class="card-body">
                        <?php if (trim($this->system->company_email) != '') { ?>
                            <h4 class=""><i class="fa fa-envelope-o"></i> <?php echo $this->lang->line('text_email'); ?></h4>
                            <p class=""> <a href="mailto:<?php echo $this->system->company_email; ?>" > <?php echo $this->system->company_email; ?></a> </p>
                            <?php
                        }
                        if (trim($this->system->comapny_phone) != '') {
                            ?>
                            <h4 class=""><i class="fa fa-phone"></i> <?php echo $this->lang->line('text_contact_on'); ?></h4>
                            <p class=""><?php echo $this->system->comapny_country_code . $this->system->comapny_phone; ?> <br><?php echo $this->system->company_time; ?></p>
                        <?php } ?>
                    </div>
                </div>
                <?php if (trim($this->system->company_address) != '') { ?>
                    <div class="card cnt-card  mt-4">
                        <div class="card-body">
                            <h4 class=""><i class="fa fa-home"></i> <?php echo $this->lang->line('text_address'); ?></h4>
                            <p class=""> <?php echo $this->system->company_street; ?> <br><?php echo $this->system->company_address; ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-6 col_right">
                <div id="success">

                </div>
                <div class="card cnt-card">
                    <div class="card-body">
                        <form action="#" method="post" id="contact-form" novalidate>
                            <div class="form-group">
                                <label for="fname"><?php echo $this->lang->line('text_full_name'); ?>:<span class="required" aria-required="true"> * </span></label>
                                <input id="fname" type="text" class="form-control" name="fname">
                            </div>
                            <div class="form-group">
                                <label for="email"><?php echo $this->lang->line('text_email'); ?>:<span class="required" aria-required="true"> * </span></label>
                                <input id="email" type="email" class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <label for="subject"><?php echo $this->lang->line('text_subject'); ?>:<span class="required" aria-required="true"> * </span></label>
                                <input id="subject" type="text" class="form-control" name="subject">
                            </div>
                            <div class="form-group">
                                <label for="msg"><?php echo $this->lang->line('text_message'); ?>:<span class="required" aria-required="true"> * </span></label>
                                <textarea id="msg" rows="5" class="form-control" name="message"></textarea>
                            </div>
                            <input type="submit" class="btn btn-submit btn-block btn-lg btn-lightpink" name="submit" value="<?php echo $this->lang->line('text_btn_submit'); ?>">
                        </form> 
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<!--CONTACT AREA END-->
