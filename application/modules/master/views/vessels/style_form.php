<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'style_form', 'id' => 'styleform'];
$btndata = ['name' => 'submit', 'id' => 'btn-style', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
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
                $input_type_name = ['name' => 'style_name', 'type' => 'text', 'id' => 'style_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('style_name', $item->style_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('style_name_lbl').lbl_req(), 'style_name', ['class' => $label_cls]), form_input($input_type_name), ['inpt_grp_icon' => 'envelope']);
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>