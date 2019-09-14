<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'price_form', 'id' => 'price_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-price', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$price_type_list = add_selectbx_initial($price_type_list);
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
                $input_price_label = ['name' => 'price_label', 'type' => 'text', 'id' => 'price_label', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('price_label', $item->price_label), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('price_name_lbl').lbl_req(), 'price_label', ['class' => $label_cls]), form_input($input_price_label), ['inpt_grp_icon' => 'envelope']);
                $price_type_attr = ['id'=>'price_type_id', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('price_type_lbl').lbl_req(), 'price_type_id', ['class' => $label_cls]), form_dropdown('price_type_id', $price_type_list, set_value('price_type_id', $item->price_type_id), $price_type_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                $input_price = ['name' => 'price', 'type' => 'text', 'id' => 'price', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('price', $item->price), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('price_lbl').lbl_req(), 'price', ['class' => $label_cls]), form_input($input_price), ['inpt_grp_icon' => 'envelope']);
                $input_description_label = ['name' => 'description', 'type' => 'text', 'id' => 'description', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('description', $item->description), 'required' => 'required', 'maxlength' => '100', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('description_lbl').lbl_req(), 'description', ['class' => $label_cls]), form_input($input_description_label), ['inpt_grp_icon' => 'envelope']);
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>