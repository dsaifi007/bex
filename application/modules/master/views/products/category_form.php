<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'category_form', 'id' => 'category_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-category', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$category_type_list = add_selectbx_initial($category_type_list);
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
                $category_type_attr = ['id'=>'category_type_id', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('category_type_lbl').lbl_req(), 'category_type_id', ['class' => $label_cls]), form_dropdown('category_type_id', $category_type_list, set_value('category_type_id', $item->category_type_id), $category_type_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                /*$input_frontend_name = ['name' => 'frontend_name', 'type' => 'text', 'id' => 'frontend_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('frontend_name', $item->frontend_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('frontend_name_lbl').lbl_req(), 'frontend_name', ['class' => $label_cls]), form_input($input_frontend_name), ['inpt_grp_icon' => 'envelope']);*/
                $input_backend_name = ['name' => 'backend_name', 'type' => 'text', 'id' => 'backend_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('backend_name', $item->backend_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('backend_name_lbl').lbl_req(), 'backend_name', ['class' => $label_cls]), form_input($input_backend_name), ['inpt_grp_icon' => 'envelope']);
                /*$input_tips = ['name' => 'tips', 'type' => 'text', 'id' => 'tips', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('tips', $item->tips), 'maxlength' => '200', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('category_tips_lbl').lbl_req(), 'backend_name', ['class' => $label_cls]), form_input($input_tips), ['inpt_grp_icon' => 'envelope']);*/
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>