<!DOCTYPE html>
<?php
    if($this->session->userdata('site_lang') && in_array($this->session->userdata('site_lang'),json_decode($this->system->rtl_supported_language,true))) {
        $dir = 'rtl';
    } else {
        $dir = 'ltr';
    }
?>
<html dir='<?php echo $dir; ?>'>
    <head>
        <?php $this->load->view($this->path_to_view_admin . 'header'); ?>
    </head>
    <body>
        <?php $this->load->view($this->path_to_view_admin . 'header_body'); ?>
        <div class="d-flex" id="wrapper">
            <?php $this->load->view($this->path_to_view_admin . 'sidebar'); ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><?php echo $title; ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>page/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header">
                                    <strong><?php echo $title; ?></strong>
                                </div>                                
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <!-- BEGIN FORM-->
                                        <form action="<?php echo base_url() . $this->path_to_view_admin ?>page" method="post" enctype="multipart/form-data" class="needs-validation" id="validate">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label "><?php echo $this->lang->line('text_title'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <div class="">
                                                            <input type="hidden" name="page_id" value="<?php
                                                            if (isset($entry)) {
                                                                echo $entry['page_id'];
                                                            }
                                                            ?>">
                                                            <input type="hidden" name="page_slug" value="<?php
                                                            if (isset($entry)) {
                                                                echo $entry['page_slug'];
                                                            }
                                                            ?>">
                                                            <input type="text" class="form-control" name="page_title" value="<?php
                                                            if (isset($entry)) {
                                                                echo $entry['page_title'];
                                                            } elseif (isset($page_title)) {
                                                                echo $page_title;
                                                            }
                                                            ?>">
                                                                   <?php echo form_error('page_title', '<em style="color:red">', '</em>'); ?>
                                                        </div>
                                                    </div>
                                                </div>                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label "><?php echo $this->lang->line('text_page_menu_title'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <div class="">
                                                            <input type="text" class="form-control" name="page_menutitle" value="<?php
                                                            if (isset($entry)) {
                                                                echo $entry['page_menutitle'];
                                                            } elseif (isset($page_menutitle)) {
                                                                echo $page_menutitle;
                                                            }
                                                            ?>" <?php
                                                                   if (isset($entry)) {
                                                                       if ($entry['page_slug'] == 'about-us' || $entry['page_slug'] == 'home' || $entry['page_slug'] == 'contact' || $entry['page_slug'] == 'how_to_install' || $entry['page_slug'] == 'terms_conditions') { {
//                                                                               echo "readonly";
                                                                           }
                                                                       }
                                                                   }
                                                                   ?> >
                                                                   <?php echo form_error('page_menutitle', '<em style="color:red">', '</em>'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/span-->                                                        
                                            </div>
                                            <?php /*
                                              <div class="row">
                                              <div class="col-md-6">
                                              <div class="form-group">
                                              <label class="control-label ">Browser Title <span class="required" aria-required="true"> * </span></label>
                                              <div class="">
                                              <input type="text" class="form-control" name="page_browsertitle" value="<?php
                                              if (isset($entry)) {
                                              echo $entry['page_browsertitle'];
                                              } elseif (isset($page_browsertitle)) {
                                              echo $page_browsertitle;
                                              }
                                              ?>">
                                              <?php echo form_error('page_browsertitle', '<em style="color:red">', '</em>'); ?>
                                              </div>
                                              </div>
                                              </div>
                                              <!--/span-->
                                              <div class="col-md-6">
                                              <div class="form-group">
                                              <label class="control-label ">Meta Title <span class="required" aria-required="true"> * </span></label>
                                              <div class="">
                                              <textarea class="form-control" name="page_metatitle" id="page_metatitle" ><?php
                                              if (isset($entry)):echo $entry['page_metatitle'];
                                              elseif (isset($page_metatitle)):echo $page_metatitle;
                                              endif;
                                              ?></textarea>
                                              <?php echo form_error('page_metatitle', '<em style="color:red">', '</em>'); ?>
                                              </div>
                                              </div>
                                              </div>
                                              <!--/span-->
                                              </div>
                                             */ ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label "><?php echo $this->lang->line('text_meta_keyword'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <div class="">
                                                            <textarea class="form-control" name="page_metakeyword" id="page_metakeyword" ><?php
                                                                if (isset($entry)):echo $entry['page_metakeyword'];
                                                                elseif (isset($page_metakeyword)):echo $page_metakeyword;
                                                                endif;
                                                                ?></textarea>
                                                            <?php echo form_error('page_metakeyword', '<em style="color:red">', '</em>'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/span-->                                                        

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label "><?php echo $this->lang->line('text_meta_description'); ?><span class="required" aria-required="true"> * </span></label>
                                                        <div class="">
                                                            <textarea class="form-control" name="page_metadesc" id="page_metadesc"><?php
                                                                if (isset($entry)):echo $entry['page_metadesc'];
                                                                elseif (isset($page_metadesc)):echo $page_metadesc;
                                                                endif;
                                                                ?></textarea>
                                                            <?php echo form_error('page_metadesc', '<em style="color:red">', '</em>'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/span-->                                                        
                                            </div>
                                            <div class="row">                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="page_baner"><?php echo $this->lang->line('text_page_banner_image'); ?></label><br>
                                                        <?php
                                                        if (isset($entry) && $entry['page_slug'] == 'home') {
                                                            echo "<b>" . $this->lang->line('text_page_note') . "</b>";
                                                        } else {
                                                            ?>
                                                            <input id="page_baner" type="file" class="file-input d-block" name="page_baner" >
                                                            <?php echo form_error('page_baner', '<em style="color:red">', '</em>'); ?>
                                                            <p><b><?php echo $this->lang->line('text_image_note'); ?> : </b> <?php echo $this->lang->line('text_image_note_1920x500'); ?></p>  
                                                            <input type="hidden" id="file-input" name="old_page_baner"  value="<?php echo (isset($entry['page_banner_image'])) ? $entry['page_banner_image'] : ''; ?>" class="form-control-file">                                                                                                      
                                                            <?php if (isset($entry['page_banner_image']) && $entry['page_banner_image'] != '' && file_exists($this->page_banner . $entry['page_banner_image'])) { ?>
                                                                <br>
                                                                <img src ="<?php echo base_url() . $this->page_banner . "thumb/100x100_" . $entry['page_banner_image'] ?>" >
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>      
                                                </div>
                                                <div class="col-md-6 m-auto">
                                                    <div class="form-group">
                                                        <input type="checkbox" id="addmenu" name="addmenu" value="1" <?php
                                                        if (isset($addmenu) && $addmenu == 1)
                                                            echo 'checked';elseif (isset($entry) && $entry['add_to_menu'] == 1)
                                                            echo 'checked';
                                                        ?>   >
                                                        <label for="addmenu" class="control-label "><?php echo $this->lang->line('text_page_add_menu'); ?><span class="required" aria-required="true"></span></label>                                                        
                                                    </div>
                                                </div>

                                                <div class="col-md-6 m-auto">
                                                    <div class="form-group">
                                                        <input type="checkbox" id="addfooter" name="addfooter" value="1" <?php
                                                        if (isset($addfooter) && $addfooter == 1)
                                                            echo 'checked';elseif (isset($entry) && $entry['add_to_footer'] == 1)
                                                            echo 'checked';
                                                        ?>   >
                                                        <label for="addfooter" class="control-label "><?php echo $this->lang->line('text_page_add_footer'); ?><span class="required" aria-required="true"></span></label>                                                        
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="pageorder" class="control-label "><?php echo $this->lang->line('text_page_order'); ?><span class="required" aria-required="true">*</span></label>  
                                                        <input type="text" class="form-control" name="pageorder" value="<?php
                                                        if (isset($entry)) {
                                                            echo $entry['page_order'];
                                                        } elseif (isset($pageorder)) {
                                                            echo $pageorder;
                                                        }
                                                        ?>">
                                                               <?php echo form_error('pageorder', '<em style="color:red">', '</em>'); ?>                                                      
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group d-none" id="parent_menu">
                                                        <label for="parent"><?php echo $this->lang->line('text_parent_menu'); ?></label><br>
                                                        <select class="form-control" name="parent" id="parent">
                                                            <option value="0">Main</option>
                                                            <?php foreach ($main_menu as $menu) {
                                                                ?>
                                                                <option value="<?php echo $menu->page_id; ?>" <?php if (isset($parent) && $parent == $menu->page_id) echo 'selected';elseif (isset($entry['parent']) && $entry['parent'] == $menu->page_id) echo 'selected'; ?>>-- <?php echo $menu->page_title; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error('parent', '<em style="color:red">', '</em>'); ?>    
                                                    </div>      
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label "><?php echo $this->lang->line('text_page_content'); ?></label>
                                                        <div class="">
                                                            <textarea class="form-control ckeditor" name="page_content" id="page_metadesc" >
                                                                <?php
                                                                if (isset($entry)):
                                                                    echo $entry['page_content'];
                                                                elseif (isset($page_content)):
                                                                    echo $page_content;
                                                                endif;
                                                                ?>
                                                            </textarea>
                                                            <?php echo form_error('page_content', '<em style="color:red">', '</em>'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/span--> 

                                                <!--/span-->
                                            </div>
                                            <div class="form-group text-center">
                                                <button class="btn btn-primary" type="submit" value="<?php echo $action; ?>" name="submit" <?php
                                                if ($this->system->demo_user == 1 && isset($entry) && in_array($entry['page_id'], $this->page_id_array)) {
                                                    echo 'disabled';
                                                }
                                                ?>><?php echo $this->lang->line('text_btn_submit'); ?></button>
                                                <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>page/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- END CONTENT -->
                <!-- END CONTAINER -->
                <!-- BEGIN FOOTER -->
                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>
                <!-- END FOOTER -->
            </div>
            <!-- END CONTENT BODY -->
        </div>
        <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>
        <script>
            $(document).ready(function () {
                $.validator.addMethod('filesize', function (value, element, arg) {
                    if ((element.files[0].size <= arg)) {
                        return true;
                    } else {
                        return false;
                    }
                }, '<?php echo $this->lang->line('err_image_size'); ?>');
                $("#validate").validate({
                    rules: {
                        page_title: {
                            required: true,
                        },
                        page_menutitle: {
                            required: true,
                        },
                        page_metakeyword: {
                            required: true,
                        },
                        page_metadesc: {
                            required: true,
                        },
//                        status: {
//                            required: true,
//                        },
                        page_baner: {
                            accept: 'jpg|jpeg|png',
//                            filesize: 2000000,
                        },
                        pageorder: {
                            required: true,
                            number: true
                        }
                    },
                    messages: {
                        page_title: {
                            required: '<?php echo $this->lang->line('err_page_title_req'); ?>',
                        },
                        page_menutitle: {
                            required: '<?php echo $this->lang->line('err_page_menutitle_req'); ?>',
                        },
                        page_metakeyword: {
                            required: '<?php echo $this->lang->line('err_page_metakeyword_req'); ?>',
                        },
                        page_metadesc: {
                            required: '<?php echo $this->lang->line('err_page_metadesc_req'); ?>',
                        },
//                        status: {
//                            required: '<?php echo $this->lang->line('err_status_req'); ?>',
//                        },
                        page_baner: {
                            accept: '<?php echo $this->lang->line('err_image_accept'); ?>',
                        },
                        pageorder: {
                            required: '<?php echo $this->lang->line('err_pageorder_req'); ?>',
                            number: '<?php echo $this->lang->line('err_number'); ?>',
                        }
                    }
                    ,
                    errorPlacement: function (error, element)
                    {
//                        if (element.is(":radio"))
//                        {
//                            error.insertAfter(element.parent());
//                        } else
                        {
                            error.insertAfter(element);
                        }
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                if ($('#addmenu').is(":checked")) {
                    $('#parent_menu').addClass('d-block');
                    $('#parent_menu').removeClass('d-none');
                }
                $('#addmenu').change(function () {
                    if ($(this).is(":checked")) {
                        $('#parent_menu').addClass('d-block');
                        $('#parent_menu').removeClass('d-none');
                    } else {
                        $('#parent_menu').removeClass('d-block');
                        $('#parent_menu').addClass('d-none');
                    }
                });
            });
        </script>
    </body>

</html>