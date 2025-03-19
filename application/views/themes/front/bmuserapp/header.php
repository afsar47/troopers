<title><?php
    if (isset($title)) {
        echo $title . " | " . $this->system->company_name;
    } else {
        echo $this->system->company_name;
    }
    ?></title>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="google-signin-client_id" content="<?php echo $this->system->google_client_id;?>">
<meta name="google-signin-cookiepolicy" content="single_host_origin">
<meta name="google-signin-scope" content="profile email">
<!-- Favicons -->
<link rel="icon" href="<?php echo base_url() . $this->company_favicon . "thumb/40x40_" . $this->system->company_favicon ?>" />
<title><?php
    if (isset($title)) {
        echo $title . " | " . $this->system->company_name;
    } else {
        echo $this->system->company_name;
    }
    ?></title>
<?php
    if($this->session->userdata('site_lang') && in_array($this->session->userdata('site_lang'),json_decode($this->system->rtl_supported_language,true))) {        
?>
    <link href="<?php echo $this->template_css; ?>bootstrap_rtl.min.css" rel="stylesheet">   
<?php  
    } else {
?>
    <link href="<?php echo $this->default_css; ?>bootstrap.min.css" rel="stylesheet" />
<?php     
    }
?>

<link href="<?php echo $this->default_css; ?>animate.css" rel="stylesheet" />
<link href="<?php echo $this->default_css; ?>fontawesome/css/font-awesome.min.css" rel="stylesheet" />
<!-- Own Stylesheets -->
<link href="<?php echo $this->default_css; ?>style.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo $this->default_css; ?>jquery.mCustomScrollbar.css" />

<!--<link rel="stylesheet" href="<?php echo $this->default_css; ?>toastme.css"/>-->
<link rel="stylesheet" href="<?php echo $this->default_css; ?>toastr.min.css"/>


<?php if (isset($profilesetting)) { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->default_css; ?>passtrength.css"/>
    <?php
        if($this->session->userdata('site_lang') && in_array($this->session->userdata('site_lang'),json_decode($this->system->rtl_supported_language,true))) {        
    ?>    
    <?php     
        }
    ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />  
<?php } ?>