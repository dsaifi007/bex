<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$label_cls = 'control-label visible-ie8 visible-ie9';
$input_cls = 'form-control form-control-solid placeholder-no-fix';
$form_attributes = array('class' => 'forgot-password-form', 'id' => 'forgotpasswordform');
$btndata = ['name'=> 'submit', 'id'=>'btn-forgotpassword-form', 'content'=> '<span class="ladda-label">'.$this->lang->line('forgot_pwd_btn').'</span>','value'=> 'submit','type'=> 'submit','class'=> 'btn green uppercase ladda-button', 'data-style'=>'zoom-out', 'data-size'=>'s'];
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
echo heading($this->lang->line('forgot_pwd_heading'), 3, ['class'=>'form-title font-green']); 
echo display_message_info(['0' => $error, '1' => $success]);
echo display_message_notes($this->lang->line('suggestion_label'), 'info');	
$input_email = ['name'=>'email', 'autocomplete'=>'off', 'type'=>'email', 'id'=>'email-id','class'=> $input_cls,'value'=>set_value('email'),'required'=>'required','maxlength'=>'150','placeholder'=>$this->lang->line('email_label')]; 
echo form_input_wrapper(form_label($this->lang->line('email_label').lbl_req(), 'email', ['class'=> $label_cls]), form_input($input_email), ['inpt_grp_icon' => 'envelope']);

echo form_actions_wrapper(form_button($btndata).anchor($login_link, $this->lang->line('login_link_label'), 'class="forget-password" id="login"')); 
echo form_close(); 
?>
<!-- END LOGIN FORM -->
</div>
<div class="copyright"><?php echo $this->lang->line('site_copyright'); ?></div>