<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'newsletter-subscriber-form', 'id' => 'newslettersubscriberform'];
$btndata = ['name' => 'submit', 'id' => 'btn-newsletter-subscriber', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
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
                $input_subs_email = ['name' => 'subs_email', 'type' => 'text', 'id' => 'subs_email', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('subs_email', $item->subs_email), 'required' => 'required', 'maxlength' => '255', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('subs_email_lbl').lbl_req(), 'subs_email', ['class' => $label_cls]), form_input($input_subs_email), ['inpt_grp_icon' => 'envelope']);
                $input_subs_name = ['name' => 'subs_name', 'type' => 'text', 'id' => 'subs_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('subs_name', $item->subs_name), 'maxlength' => '100', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('subs_name_lbl'), 'subs_name', ['class' => $label_cls]), form_input($input_subs_name), ['inpt_grp_icon' => 'user']);
				echo form_radio_wrapper(form_label($this->lang->line('subs_subscribe_lbl').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>