<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'anode_type_form', 'id' => 'anode_type_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-anode-type', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$currency_code_list = add_selectbx_initial($currency_codes_list);
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
                $input_type_name = ['name' => 'anode_type_name', 'type' => 'text', 'id' => 'anode_type_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('anode_type_name', $item->anode_type_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('anode_type_name_lbl').lbl_req(), 'anode_type_name', ['class' => $label_cls]), form_input($input_type_name), ['inpt_grp_icon' => 'envelope']);
                $currency_code_attr = ['id'=>'currency_code', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('currency_code_lbl').lbl_req(), 'currency_code', ['class' => $label_cls]), form_dropdown('currency_code', $currency_code_list, set_value('currency_code', $item->currency_code), $currency_code_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>