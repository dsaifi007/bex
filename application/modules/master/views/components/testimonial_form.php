<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'testimonial-form', 'id' => 'testimonialform'];
$btndata = ['name' => 'submit', 'id' => 'btn-testimonial', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success, '-1' => validation_errors()]); ?>
<?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link, $add_link)); ?>
            <div class="portlet-body form">
                <!-- BEGIN LOGIN FORM -->
                <?php
                echo form_open($form_action, $form_attributes, ['parent_id'=>0]);
                $input_client_name = ['name' => 'client_name', 'type' => 'text', 'id' => 'client_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('client_name', $item->client_name), 'required' => 'required', 'maxlength' => '255', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('testimonial_client_name_lbl').lbl_req(), 'client_name', ['class' => $label_cls]), form_input($input_client_name), ['inpt_grp_icon' => 'envelope']);
                //$input_content = ['name' => 'content', 'type' => 'text', 'id' => 'content', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('content', $item->content), 'placeholder' => ''];
                //echo form_input_wrapper(form_label($this->lang->line('testimonial_content_lbl').lbl_req(), 'content', ['class' => $label_cls]), form_input($input_content), ['inpt_grp_icon' => 'user']);
                $input_content = ['name' => 'content', 'id' => 'content', 'required' => 'required', 'class' => $input_cls.' summernote-editor'];
                echo form_input_wrapper(form_label($this->lang->line('testimonial_content_lbl').lbl_req(), 'content', ['class' => $label_cls]), form_textarea_editor($input_content, set_value('content', $item->content)), ['inpt_grp' => 0]);

                $testimonial_categories = add_selectbx_initial($testimonial_categories);
				$category_attr = ['id'=>'category_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('testimonial_category_lbl').lbl_req(), 'category_id', ['class' => $label_cls]), form_dropdown('category_id', $testimonial_categories, set_value('category_id', $item->category_id), $category_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                if(count($item_ordering_list) > 0) :
                    $item_ordering_attr = ['id'=>'item_ordering', 'class'=>'form-control select2'];
                    echo form_input_wrapper(form_label($this->lang->line('testimonial_item_order_lbl').lbl_req(), 'item_ordering', ['class' => $label_cls]), form_dropdown('item_ordering', $item_ordering_list, $item->item_ordering, $item_ordering_attr), ['inpt_grp_icon'=>'sort']);
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