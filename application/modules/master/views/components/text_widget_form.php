<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'text-widget-form', 'id' => 'textwidgetform'];
$btndata = ['name' => 'submit', 'id' => 'btn-ticket-department', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$domain_list = add_selectbx_initial($domain_list);
$add_link = ($add_btn)? $add_link:'';
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
				$input_widget_name = ['name' => 'widget_name', 'type' => 'text', 'id' => 'widget_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('widget_name', $item->widget_name), 'maxlength' => '100', 'placeholder' => ''];
				echo form_input_wrapper(form_label($this->lang->line('widget_name_lbl').lbl_req(), 'widget_name', ['class' => $label_cls]), form_input($input_widget_name), ['inpt_grp_icon' => 'rocket']);
				$input_widget_text = ['name' => 'widget_text', 'type' => 'text', 'id' => 'widget_text', 'autocomplete' => 'off', 'class' => $input_cls.' summernote-editor'];
				echo form_input_wrapper(form_label($this->lang->line('widget_text_lbl').lbl_req(), 'widget_text', ['class' => $label_cls]),  form_textarea_editor($input_widget_text, set_value('widget_text', $item->widget_text)), ['inpt_grp' => 0]);
                $domain_attr = ['id'=>'domain_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('widget_domain_lbl').lbl_req(), 'domain_id', ['class' => $label_cls]), form_dropdown('domain_id', $domain_list, set_value('domain_id', $item->domain_id), $domain_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>