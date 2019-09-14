<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'cleaning_schedule_form', 'id' => 'cleaning_schedule_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-cleaning-schedule', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];

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
                $divers_attr = ['id'=>'diver_id', 'required'=>'required', 'class'=>'form-control select2-multiple diver_id', 'multiple'=>'multiple' ,'maxlength'=> 2];
                echo form_input_wrapper(form_label($this->lang->line('vessel_cleaning_divers_lbl').lbl_req(), 'diver_id', ['class' => $label_cls]), form_multiselect('diver_id[]', $divers_list, set_value('diver_id', $item->diver_id), $divers_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                $input_notes = ['name' => 'notes', 'type' => 'text', 'id' => 'notes', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('notes', $item->notes), 'required' => 'required', 'maxlength' => '500', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('vessel_cleaning_notes_lbl').lbl_req(), 'notes', ['class' => $label_cls]), form_input($input_notes), ['inpt_grp_icon' => 'envelope']);
                $assigned_diver_attr = ['id'=>'assigned_to', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('vessel_cleaning_assigned_to_lbl').lbl_req(), 'assigned_to', ['class' => $label_cls]), form_dropdown('assigned_to', add_selectbx_initial($divers_list), set_value('assigned_to', $item->assigned_to), $assigned_diver_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                $status_attr = ['id'=>'status_id', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('vessel_cleaning_schedule_status_lbl').lbl_req(), 'status_id', ['class' => $label_cls]), form_dropdown('status_id', add_selectbx_initial($schedule_status_list), set_value('status_id', $item->status_id), $status_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>