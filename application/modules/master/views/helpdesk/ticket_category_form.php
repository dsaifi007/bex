<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'form-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'category-form', 'id' => 'categoryform'];
$btndata = ['name' => 'submit', 'id' => 'btn-category', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
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

                $input_category_name = ['name' => 'category_name', 'type' => 'text', 'id' => 'category_name_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('category_name', $item->category_name), 'required' => 'required', 'maxlength' => '150', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('categories_name_lbl').lbl_req(), 'category_name', ['class' => $label_cls]), form_input($input_category_name), ['inpt_grp_icon' => 'star-o']);
                //$input_category_description = ['name' => 'category_description', 'id' => 'category_description', 'autocomplete' => 'off', 'class' => $input_cls.' summernote-editor'];
                //echo form_input_wrapper(form_label($this->lang->line('categories_desc_lbl'), 'category_description', ['class' => $label_cls]), form_textarea_editor($input_category_description, set_value('category_description', $item->category_description)), ['inpt_grp' => 0]);
                $chk_parent_attr = ['id'=>'parent_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('categories_parent_lbl').lbl_req(), 'parent_id', ['class' => $label_cls]), form_dropdown('parent_id', $parent_list, set_value('parent_id', $item->parent_id), $chk_parent_attr), ['inpt_grp_icon'=>'street-view']);
                if(count($item_ordering_list) > 0) :
                    $item_ordering_attr = ['id'=>'item_ordering', 'class'=>'form-control select2'];
                    echo form_input_wrapper(form_label($this->lang->line('categories_item_order_lbl').lbl_req(), 'item_ordering', ['class' => $label_cls]), form_dropdown('item_ordering', $item_ordering_list, $item->item_ordering, $item_ordering_attr), ['inpt_grp_icon'=>'sort']);
                endif;
                echo form_radio_wrapper(lbl_req() . form_label($this->lang->line('active_label'), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>
