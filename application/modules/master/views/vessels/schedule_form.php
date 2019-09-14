<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'schedule_form', 'id' => 'schedule_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-schedule', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
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
                $input_schedule_name = ['name' => 'schedule_name', 'type' => 'text', 'id' => 'schedule_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('schedule_name', $item->schedule_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('schedule_name_lbl').lbl_req(), 'schedule_name', ['class' => $label_cls]), form_input($input_schedule_name), ['inpt_grp_icon' => 'envelope']);
                $input_range_from = ['name' => 'range_from', 'type' => 'text', 'id' => 'range_from', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('range_from', $item->range_from), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('schedule_range_from_lbl').lbl_req(), 'range_from', ['class' => $label_cls]), form_input($input_range_from), ['inpt_grp_icon' => 'envelope']);
                $input_range_to = ['name' => 'range_to', 'type' => 'text', 'id' => 'range_to', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('range_to', $item->range_to), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('schedule_range_to_lbl').lbl_req(), 'range_to', ['class' => $label_cls]), form_input($input_range_to), ['inpt_grp_icon' => 'envelope']);
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>