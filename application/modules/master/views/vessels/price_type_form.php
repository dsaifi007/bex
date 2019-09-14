<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'price_type_form', 'id' => 'price_type_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-price-type', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$currency_code_list = add_selectbx_initial($currency_codes_list);
$display_type_list = add_selectbx_initial($display_type_list);
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success, '-1' => validation_errors()]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link, $add_link)); ?>
            <div class="portlet-body form">
                <!-- BEGIN LOGIN FORM -->
                <?php
                echo form_open($form_action, $form_attributes,['parent_id'=>$item->parent_id]);
                $input_type_name = ['name' => 'type_name', 'type' => 'text', 'id' => 'type_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('type_name', $item->type_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('price_type_name_lbl').lbl_req(), 'type_name', ['class' => $label_cls]), form_input($input_type_name), ['inpt_grp_icon' => 'envelope']);
                $currency_code_attr = ['id'=>'currency_code', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('currency_code_lbl').lbl_req(), 'currency_code', ['class' => $label_cls]), form_dropdown('currency_code', $currency_code_list, set_value('currency_code', $item->currency_code), $currency_code_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                $display_type_attr = ['id'=>'display_type', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('display_type_lbl').lbl_req(), 'display_type', ['class' => $label_cls]), form_dropdown('display_type', $display_type_list, set_value('display_type', $item->display_type), $display_type_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                if(count($item_ordering_list) > 0) :
                    $item_ordering_attr = ['id'=>'item_ordering', 'class'=>'form-control select2'];
                    echo form_input_wrapper(form_label($this->lang->line('price_item_ordering_lbl').lbl_req(), 'item_ordering', ['class' => $label_cls]), form_dropdown('item_ordering', $item_ordering_list, $item->item_ordering, $item_ordering_attr), ['inpt_grp_icon'=>'sort']);
                endif;
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>