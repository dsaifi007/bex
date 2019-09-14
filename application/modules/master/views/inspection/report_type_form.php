<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'report_type_form', 'id' => 'report_type_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-report-type', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$drive_type_list = add_selectbx_initial($drive_type_list);

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
                
                $input_type_name = ['name' => 'type_name', 'type' => 'text', 'id' => 'type_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('type_name', $item->type_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('report_type_name_lbl').lbl_req(), 'type_name', ['class' => $label_cls]), form_input($input_type_name), ['inpt_grp_icon' => 'envelope']);

                echo form_radio_wrapper(form_label($this->lang->line('report_type_featured_lbl').lbl_req(), 'featured', ['class' => $label_cls]), 'featured', $boolean_arr, set_value('featured', $item->featured), 'featured');

                $drive_type_attr = ['id'=>'drive_type_id', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('report_type_drive_type_lbl').lbl_req(), 'drive_type_id', ['class' => $label_cls]), form_dropdown('drive_type_id', $drive_type_list, set_value('drive_type_id', $item->drive_type_id), $drive_type_attr), ['inpt_grp_icon'=>'dot-circle-o']);

                $input_no_of_drives = ['name' => 'no_of_drives', 'type' => 'text', 'id' => 'no_of_drives', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('no_of_drives', $item->no_of_drives), 'required' => 'required', 'maxlength' => '4', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('report_type_no_of_drives_lbl').lbl_req(), 'no_of_drives', ['class' => $label_cls]), form_input($input_no_of_drives), ['inpt_grp_icon' => 'envelope']);

                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');

                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>