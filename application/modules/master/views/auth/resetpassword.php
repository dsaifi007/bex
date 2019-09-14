<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$label_cls = 'control-label visible-ie8 visible-ie9';
$input_cls = 'form-control form-control-solid placeholder-no-fix';
$form_attributes = array('class' => 'reset-password-form', 'id' => 'resetpasswordform');
$btndata = ['name' => 'submit', 'id' => 'btn-resetpassword-form', 'content' => '<span class="ladda-label">' . $this->lang->line('forgot_pwd_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn green uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
?>
<!-- BEGIN LOGO -->
<div class="logo">
    <?php echo anchor(base_url(), img(media_url() . 'assets/pages/img/logo-big.png', FALSE, ['alt' => $this->lang->line('site_name')]), ['title' => $this->lang->line('site_name')]); ?>
</div>
<!-- END LOGO -->

<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <?php
    echo form_open($form_action, $form_attributes);
    echo heading($this->lang->line('forgot_pwd_heading'), 3, ['class' => 'form-title font-green']);
    echo display_message_info(['0' => $error, '1' => $success]);
    echo display_message_notes($this->lang->line('suggestion_reset_label'), 'info');
    $input_email = ['name' => 'email', 'autocomplete' => 'off', 'type' => 'email', 'id' => 'email-id', 'class' => $input_cls, 'value' => set_value('email'), 'required' => 'required', 'maxlength' => '150', 'placeholder' => $this->lang->line('email_label')];
    echo form_input_wrapper(form_label($this->lang->line('email_label').lbl_req(), 'email', ['class' => $label_cls]), form_input($input_email), ['inpt_grp_icon' => 'envelope']);
    $input_token = ['name' => 'token_no', 'autocomplete' => 'off', 'type' => 'text', 'id' => 'token_no-id', 'class' => $input_cls, 'value' => set_value('token'), 'required' => 'required', 'maxlength' => $this->config->item('reset_pwd_token_length'), 'placeholder' => $this->lang->line('resetpwd_token_label')];
    echo form_input_wrapper(form_label($this->lang->line('resetpwd_token_label').lbl_req(), 'token_no', ['class' => $label_cls]), form_input($input_token), ['inpt_grp_icon' => 'bullseye']);
    $input_user_password = ['name' => 'password_hash', 'type' => 'password', 'id' => 'password_hash', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => '', 'required' => 'required', 'maxlength' => '20', 'placeholder' => $this->lang->line('reset_password_lbl')];
    echo form_input_wrapper(form_label($this->lang->line('reset_password_lbl').lbl_req(), 'password_hash', ['class' => $label_cls]), form_input($input_user_password), ['inpt_grp_icon' => 'lock']);
    $input_user_vpassword = ['name' => 'verify_password', 'type' => 'password', 'id' => 'verify_password', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => '', 'required' => 'required', 'maxlength' => '20', 'placeholder' => $this->lang->line('reset_vpassword_lbl')];
    echo form_input_wrapper(form_label($this->lang->line('reset_vpassword_lbl').lbl_req(), 'verify_password', ['class' => $label_cls]), form_input($input_user_vpassword), ['inpt_grp_icon' => 'unlock']);
    echo form_actions_wrapper(form_button($btndata) . anchor($login_link, $this->lang->line('login_link_label'), 'class="forget-password" id="login"'));
    echo form_close();
    ?>
    <!-- END LOGIN FORM -->
</div>
<div class="copyright"><?php echo $this->lang->line('site_copyright'); ?></div>