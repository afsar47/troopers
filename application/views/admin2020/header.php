<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<!-- Favicons -->
<!--<link rel="icon" href="<?php echo $this->admin_img; ?>fevicon.png" />-->
<link rel="icon" href="<?php echo base_url() . $this->company_favicon . "thumb/40x40_" . $this->system->company_favicon ?>" />
<title><?php
    if (isset($title)) {
        echo $title . " | " . $this->system->company_name;
    } else {
        echo $this->system->company_name;
    }
    ?></title>
<!-- Bootstrap core CSS -->
        <?php
            if($this->session->userdata('site_lang') && in_array($this->session->userdata('site_lang'),json_decode($this->system->rtl_supported_language,true))) {        
        ?>
            <link href="<?php echo $this->admin_css; ?>bootstrap_rtl.min.css" rel="stylesheet">                     
        <?php  
            } else {
        ?>
            <link href="<?php echo $this->admin_css; ?>bootstrap.min.css" rel="stylesheet" >
        <?php     
            }
        ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo $this->admin_css; ?>dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="<?php echo $this->admin_css; ?>toastr.min.css" rel="stylesheet" type="text/css" />
<!-- Custom styles for this template -->
<link href="<?php echo $this->admin_css; ?>style.css" rel="stylesheet">
    <link href="<?php echo $this->admin_css; ?>toastr.min.css" rel="stylesheet" type="text/css" />
<?php if (isset($match_addedit) || isset($member_manage) || isset($pages_manage) || isset($page_addedit) || isset($lottery_addedit)) {
    ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->admin_css; ?>jquery.datetimepicker.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />   
<?php } ?>
<?php if (isset($change_password)) {
    ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->admin_css; ?>passtrength.css"/>

<?php } ?>
<?php if (isset($appsetting) || isset($custom_notification)) {
    ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" rel="stylesheet">	
<?php } ?>
