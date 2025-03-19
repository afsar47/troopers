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
                        <h1 class="h2"><?php echo $this->lang->line('text_admin'); ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php if (isset($btn)) { ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>admin/">
                                    <i class="fa fa-eye"></i> <?php echo $btn; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light text-dark">
                                <div class="card-header"><strong><?php echo $this->lang->line('text_admin'); ?></strong></div>                                
                                <div class="card-body">
                                    <div class="col-md-12">
                                    <form method="POST"  enctype="multipart/form-data" action="<?php if ($Action == $this->lang->line('text_action_add')) { ?><?php echo base_url() . $this->path_to_view_admin ?>admin/insert<?php } elseif ($Action == $this->lang->line('text_action_edit')) { ?><?php echo base_url() . $this->path_to_view_admin ?>admin/edit<?php } ?>">
                                            <?php if ($Action == $this->lang->line('text_action_add')) { ?>
                                            <?php } ?>
                                            <div class="row">
                                                <input  type="hidden" class="form-control" name="admin_id" value="<?php if (isset($admin_id)) echo $admin_id;elseif (isset($admin_detail['id'])) echo $admin_detail['id'] ?>">                                                   
                                                <div class="form-group col-6">
                                                    <label for="name"><?php echo $this->lang->line('text_name'); ?></label>
                                                    <input type="text" class="form-control" name="name" value="<?php if (isset($name)) echo $name;elseif (isset($admin_detail['name'])) echo $admin_detail['name'] ?>" >
                                                    <?php echo form_error('name', '<em style="color:red">', '</em>'); ?>
                                                </div>       

                                                <div class="form-group col-6">
                                                    <label for="email"><?php echo $this->lang->line('text_email'); ?></label>
                                                    <input type="text" class="form-control" name="email" value="<?php if (isset($email)) echo $email;elseif (isset($admin_detail['email'])) echo $admin_detail['email'] ?>" >
                                                    <?php echo form_error('email', '<em style="color:red">', '</em>'); ?>
                                                </div> 
                                                <?php if ($Action == $this->lang->line('text_action_add')) { ?>
                                                <div class="form-group col-6">
                                                    <label for="password"><?php echo $this->lang->line('text_password'); ?></label>
                                                    <input type="password" class="form-control" name="password" value="<?php if (isset($password)) echo $password;?>" >
                                                    <?php echo form_error('password', '<em style="color:red">', '</em>'); ?>
                                                </div>   
                                                <?php
                                                    }
                                                ?>                                             
                                            </div>
                                            <hr/>

                                            <div class="row justify-content-md-center">
                                            <div class="form-group col-6">
                                            <h4 for="name"><?php echo $this->lang->line('text_permission'); ?></h4>                                            
                                            <table class="table table-striped">
                                            <?php                                                                                            
                                                foreach($permissions as $row){
                                                    if($row['parent_status'] == 'parent'){                                                        
                                            ?>
                                            <tr>
                                                <td>
                                                    <b><?php echo ucfirst($row['name']); ?></b>
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="permission[]"  value="<?php echo $row['permission_id']; ?>" <?php
                                                        if (isset($permission) && in_array($row['permission_id'],$permission))
                                                            echo 'checked';
                                                        elseif (isset($admin_detail['permission']) && in_array($row['permission_id'],json_decode($admin_detail['permission'],true)))
                                                            echo 'checked';
                                                        else
                                                            echo ''; ?> />
                                                </td>
                                            </tr>
                                                                                
                                                    <?php                                                          
                                                        $sub_data = $this->db->query('select * from permission where parent_status = "'. $row['permission_id'] .'"')->result_array();
                                                        
                                                        foreach($sub_data as $row1){                                                            
                                                    ?>
                                                    <tr>
                                                        <td class="pl-5">
                                                            <?php echo ucfirst($row1['name']); ?>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="permission[]"  value="<?php echo $row1['permission_id']; ?>" <?php
                                                                if (isset($permission) && in_array($row1['permission_id'],$permission))
                                                                    echo 'checked';
                                                                elseif (isset($admin_detail['permission']) && in_array($row1['permission_id'],json_decode($admin_detail['permission'],true)))
                                                                    echo 'checked';
                                                                else
                                                                    echo ''; ?> />
                                                        </td>
                                                    </tr> 
                                                <?php                                                           
                                                        }
                                                    }
                                                }
                                            ?>   
                                            </table> 
                                            </div>
                                            </div>
                                            
                                            <br/>
                                            <div class="form-group text-center">
                                                <input type="submit" value="Submit" name="submit" class="btn btn-primary " <?php 
                                                if ($this->system->demo_user == 1) {
                                                    echo 'disabled';
                                                }
                                                ?>>                                                    
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>        
    </body>
</html>