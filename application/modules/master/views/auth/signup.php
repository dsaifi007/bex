<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$label_cls = 'control-label visible-ie8 visible-ie9';
$input_cls = 'form-control form-control-solid placeholder-no-fix';
$form_attributes = array('class' => 'signup-form', 'id' => 'signupform');
$btndata = ['name'=> 'submit', 'id'=>'btn-signup-form', 'content'=> '<span class="ladda-label">'.$this->lang->line('signup_btn').'</span>','value'=> 'submit','type'=> 'submit','class'=> 'btn green uppercase ladda-button', 'data-style'=>'zoom-out', 'data-size'=>'s'];

?>
<!-- BEGIN LOGO -->
<div class="logo">
	<?php echo anchor(base_url(), img(media_url().'assets/pages/img/logo-big.png', FALSE, ['alt'=>$this->lang->line('site_name')]), ['title'=>$this->lang->line('site_name')]); ?>
</div>
<!-- END LOGO -->

<div class="content">
<!-- BEGIN SIGNUP FORM -->
<?php 
               echo form_open($form_action, $form_attributes);
              echo heading($this->lang->line('signup_heading'), 3, ['class'=>'form-title font-green']);echo display_message_info(['0' => $error, '1' => $success, '-1' => validation_errors()]);
               $input_user_fname = ['name' => 'first_name', 'type' => 'text', 'id' => 'first_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => '',  'maxlength' => '50', 'placeholder' => $this->lang->line('first_name_label')];
                echo form_input_wrapper(form_label($this->lang->line('user_fname_lbl').lbl_req(), 'first_name', ['class' => $label_cls]), form_input($input_user_fname), ['inpt_grp_icon' => 'smile-o']);
                
                $input_user_mname = ['name' => 'middle_name', 'type' => 'text', 'id' => 'middle_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => '', 'maxlength' => '50', 'placeholder' =>  $this->lang->line('middle_name_label')];
                echo form_input_wrapper(form_label($this->lang->line('user_mname_lbl'), 'middle_name', ['class' => $label_cls]), form_input($input_user_mname), ['inpt_grp_icon' => 'smile-o']);
                
                $input_user_lname = ['name' => 'last_name', 'type' => 'text', 'id' => 'last_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => '',  'maxlength' => '50', 'placeholder' =>  $this->lang->line('last_name_label')];
                echo form_input_wrapper(form_label($this->lang->line('user_lname_lbl').lbl_req(), 'last_name', ['class' => $label_cls]), form_input($input_user_lname), ['inpt_grp_icon' => 'smile-o']);
                
                $input_user_email = ['name' => 'email', 'type' => 'text', 'id' => 'email', 'autocomplete' => 'off', 'class' => $input_cls, 'value' =>'',  'maxlength' => '150', 'placeholder' =>$this->lang->line('email_label')];
                echo form_input_wrapper(form_label($this->lang->line('user_email_lbl').lbl_req(), 'email', ['class' => $label_cls]), form_input($input_user_email), ['inpt_grp_icon' => 'envelope-o']);
                
                $input_user_password = ['name' => 'password_hash', 'type' => 'password', 'id' => 'password_hash', 'autocomplete' => 'off', 'class' => $input_cls, 'value' =>'', 'maxlength' => '20', 'placeholder' => $this->lang->line('password_label')];
                echo form_input_wrapper(form_label($this->lang->line('user_password_lbl').lbl_req(), 'password_hash', ['class' => $label_cls]), form_input($input_user_password), ['inpt_grp_icon' => 'lock']);
                
                $input_user_vpassword = ['name' => 'verify_password', 'type' => 'password', 'id' => 'verify_password', 'autocomplete' => 'off', 'class' => $input_cls, 'value' =>'', 'maxlength' => '20', 'placeholder' =>$this->lang->line('confirm_password_label')];
                echo form_input_wrapper(form_label($this->lang->line('user_vpassword_lbl').lbl_req(), 'verify_password', ['class' => $label_cls]), form_input($input_user_vpassword), ['inpt_grp_icon' => 'unlock']);
                
                echo form_actions_wrapper(form_button($btndata).anchor($login_link, $this->lang->line('login_link_label'), 'class="forget-password" id="login"'));
                echo form_close();
                ?>
<!-- END SIGNUP FORM -->
</div>
<div class="copyright"><?php echo $this->lang->line('site_copyright'); ?></div>