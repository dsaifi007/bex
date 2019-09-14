<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'anode_pricing_form', 'id' => 'anode_pricing_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-anode-pricing', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$anode_type_list = add_selectbx_initial($anode_type_list);
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
                $input_anode_name = ['name' => 'anode_name', 'type' => 'text', 'id' => 'anode_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('anode_name', $item->anode_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('anode_name_lbl').lbl_req(), 'anode_name', ['class' => $label_cls]), form_input($input_anode_name), ['inpt_grp_icon' => 'envelope']);
                $anode_type_attr = ['id'=>'anode_type_id', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('anode_type_lbl').lbl_req(), 'anode_type_id', ['class' => $label_cls]), form_dropdown('anode_type_id', $anode_type_list, set_value('anode_type_id', $item->anode_type_id), $anode_type_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                $input_anode_price = ['name' => 'anode_price', 'type' => 'text', 'id' => 'anode_price', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('anode_price', $item->anode_price), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('anode_price_lbl').lbl_req(), 'anode_price', ['class' => $label_cls]), form_input($input_anode_price), ['inpt_grp_icon' => 'envelope']);
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>