<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'domain-form', 'id' => 'domainform'];
$btndata = ['name' => 'submit', 'id' => 'btn-domain', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
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

                $input_title = ['name' => 'title', 'type' => 'text', 'id' => 'domain_title', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('title', $item->title), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('domain_title_lbl').lbl_req(), 'domain_title', ['class' => $label_cls]), form_input($input_title), ['inpt_grp_icon' => 'dot-circle-o']);
                $input_slug = ['name' => 'slug', 'type' => 'text', 'id' => 'domain_slug', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('slug', $item->slug), 'required' => 'required', 'maxlength' => '25', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('domain_slug_lbl').lbl_req(), 'domain_slug', ['class' => $label_cls]), form_input($input_slug), ['inpt_grp_icon' => 'cog']);                
                $input_url = ['name' => 'url', 'type' => 'text', 'id' => 'domain_url', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('url', $item->url), 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('domain_url_lbl'), 'domain_url', ['class' => $label_cls]), form_input($input_url), ['inpt_grp_icon' => 'link']);
                
                $usergroups_attr = ['id' => 'user_groups', 'required'=>'required', 'class' => 'form-control select2-multiple', 'multiple' => 'multiple'];
                echo form_input_wrapper(form_label($this->lang->line('domain_usergroups_lbl').lbl_req(), 'user_groups', ['class' => $label_cls]), form_multiselect('user_groups[]', $usergroups_list, set_value('user_groups', $item->user_groups), $usergroups_attr), ['inpt_grp_icon' => 'users']);
                echo form_radio_wrapper(form_label($this->lang->line('domain_is_down_lbl').lbl_req(), 'is_down', ['class' => $label_cls]), 'is_down', $boolean_arr, set_value('is_down', $item->is_down), 'success');
                $input_down_message = ['name' => 'down_message', 'id' => 'domain_down_message', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('down_message', $item->down_message), 'maxlength' => '500'];
                echo form_input_wrapper(form_label($this->lang->line('domain_down_message_lbl'), 'domain_down_message', ['class' => $label_cls]), form_textarea($input_down_message), ['inpt_grp' => 0]);
                
                echo form_radio_wrapper(form_label($this->lang->line('domain_display_notice_lbl').lbl_req(), 'display_notice', ['class' => $label_cls]), 'display_notice', $boolean_arr, set_value('display_notice', $item->display_notice), 'success');
                $input_notice_message = ['name' => 'notice_message', 'id' => 'domain_notice_message', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('notice_message', $item->notice_message), 'maxlength' => '500'];
                echo form_input_wrapper(form_label($this->lang->line('domain_notice_message_lbl'), 'domain_notice_message', ['class' => $label_cls]), form_textarea($input_notice_message), ['inpt_grp' => 0]);
                
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>