<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$label_cls = 'control-label visible-ie8 visible-ie9';
$input_cls = 'form-control form-control-solid placeholder-no-fix';
$form_attributes = array('class' => 'login-form', 'id' => 'loginform');
$btndata = ['name'=> 'submit', 'id'=>'btn-login-form', 'content'=> '<span class="ladda-label">'.$this->lang->line('login_btn').'</span>','value'=> 'submit','type'=> 'submit','class'=> 'btn green uppercase ladda-button', 'data-style'=>'zoom-out', 'data-size'=>'s'];

?>
<!-- BEGIN LOGO -->
<div class="logo">
    <?php echo anchor(base_url(), img(media_url().'assets/pages/img/logo-big.png', FALSE, ['alt'=>$this->lang->line('site_name')]), ['title'=>$this->lang->line('site_name')]); ?>
</div>
<!-- END LOGO -->

<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <?php
    echo form_open($form_action, $form_attributes);
    echo heading($this->lang->line('login_heading'), 3, ['class'=>'form-title font-green']);
    echo display_message_info(['0' => $error, '1' => $success]);

    $input_email = ['name'=>'email', 'autocomplete'=>'off', 'type'=>'email', 'id'=>'email-id','class'=> $input_cls,'value'=>set_value('email'),'required'=>'required','maxlength'=>'150','placeholder'=>$this->lang->line('email_label')];
    echo form_input_wrapper(form_label($this->lang->line('email_label').lbl_req(), 'email', ['class'=> $label_cls]), form_input($input_email), ['inpt_grp_icon' => 'envelope']);

    $input_pwd = ['name'=>'password', 'autocomplete'=>'off', 'type'=>'password', 'id'=>'password-id','class'=> $input_cls,'value'=>set_value('password'),'required'=>'required','maxlength'=>'20','placeholder'=>$this->lang->line('password_label')];
    echo form_input_wrapper(form_label($this->lang->line('password_label').lbl_req(), 'password', ['class'=> $label_cls]), form_input($input_pwd), ['inpt_grp_icon' => 'lock']);

    echo form_actions_wrapper(form_button($btndata).anchor($forgot_pwd_link, $this->lang->line('forgot_pwd_label'), 'class="forget-password" id="forget-password"'));
    echo form_close();
    ?>
    <?php if($this->config->item('allow_user_registration')) { ?>
    <div class="create-account">
        <p><?php echo anchor($register_link, $this->lang->line('register_account_lbl'),['id'=>"register-btn", 'class'=>'uppercase']); ?></p>
    </div>
    <?php } ?>
</div>
<div class="copyright"><?php echo $this->lang->line('site_copyright'); ?></div>