<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'user-form', 'id' => 'userform'];
$btndata = ['name' => 'submit', 'id' => 'btn-user', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$add_link = ($add_btn)? $add_link:'';
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success, '-1' => validation_errors()]); ?>
<?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link, $add_link)); ?>
            <div class="portlet-body form">
                <!-- BEGIN LOGIN FORM -->
                <?php 
                echo form_open($form_action, $form_attributes);
                $input_user_fname = ['name' => 'first_name', 'type' => 'text', 'id' => 'first_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('first_name', $item->first_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('user_fname_lbl').lbl_req(), 'first_name', ['class' => $label_cls]), form_input($input_user_fname), ['inpt_grp_icon' => 'smile-o']);
                
                $input_user_mname = ['name' => 'middle_name', 'type' => 'text', 'id' => 'middle_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('middle_name', $item->middle_name), 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('user_mname_lbl'), 'middle_name', ['class' => $label_cls]), form_input($input_user_mname), ['inpt_grp_icon' => 'smile-o']);
                
                $input_user_lname = ['name' => 'last_name', 'type' => 'text', 'id' => 'last_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('last_name', $item->last_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('user_lname_lbl').lbl_req(), 'last_name', ['class' => $label_cls]), form_input($input_user_lname), ['inpt_grp_icon' => 'smile-o']);
                
                $input_user_email = ['name' => 'email', 'type' => 'text', 'id' => 'email', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('email', $item->email), 'required' => 'required', 'maxlength' => '150', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('user_email_lbl').lbl_req(), 'email', ['class' => $label_cls]), form_input($input_user_email), ['inpt_grp_icon' => 'envelope-o']);
                
                $input_user_display_name = ['name' => 'display_name', 'type' => 'text', 'id' => 'display_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('display_name', $item->display_name), 'required' => 'required', 'maxlength' => '25', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('user_display_name_lbl').lbl_req(), 'display_name', ['class' => $label_cls]), form_input($input_user_display_name), ['inpt_grp_icon' => 'meh-o']);
                
                $input_user_password = ['name' => 'password_hash', 'type' => 'password', 'id' => 'password_hash', 'autocomplete' => 'off', 'class' => $input_cls, 'value' =>'', 'maxlength' => '20', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('user_password_lbl').lbl_req(), 'password_hash', ['class' => $label_cls]), form_input($input_user_password), ['inpt_grp_icon' => 'lock']);
                
                $input_user_vpassword = ['name' => 'verify_password', 'type' => 'password', 'id' => 'verify_password', 'autocomplete' => 'off', 'class' => $input_cls, 'value' =>'', 'maxlength' => '20', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('user_vpassword_lbl').lbl_req(), 'verify_password', ['class' => $label_cls]), form_input($input_user_vpassword), ['inpt_grp_icon' => 'unlock']);
                
                $usergroups_attr = ['id'=>'user_groups', 'class'=>'form-control select2-multiple', 'multiple'=>'multiple'];
                echo form_input_wrapper(form_label($this->lang->line('user_usergroups_lbl').lbl_req(), 'user_groups', ['class' => $label_cls]), form_multiselect('user_groups[]', $usergroups_list, set_value('user_groups', $item->user_groups), $usergroups_attr), ['inpt_grp_icon'=>'users']);
                
                if($user_system_email_flag) : 
                    echo form_radio_wrapper(form_label($this->lang->line('user_receive_emails_lbl').lbl_req(), 'receive_system_emails', ['class' => $label_cls]), 'receive_system_emails', $boolean_arr, set_value('receive_system_emails', $item->receive_system_emails), 'success');
                endif;
                echo form_radio_wrapper(form_label($this->lang->line('user_block_lbl').lbl_req(), 'block', ['class' => $label_cls]), 'block', $boolean_arr, set_value('block', $item->block), 'success');
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'active', ['class' => $label_cls]), 'active', $boolean_arr, set_value('active', $item->active), 'success');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>