<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'acl-access-action-function-form', 'id' => 'accessactionfunctionform'];
$btndata = ['name' => 'submit', 'id' => 'btn-access-action-function', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
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
                $input_acl_access_action_function = ['name' => 'function_name', 'type' => 'text', 'id' => 'function_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('function_name', $item->function_name), 'required' => 'required', 'maxlength' => '100', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('access_action_function_lbl').lbl_req(), 'function_name', ['class' => $label_cls]), form_input($input_acl_access_action_function), ['inpt_grp_icon' => 'cog']);
                
                $input_acl_access_action_function_controller = ['name' => 'controller', 'type' => 'text', 'id' => 'controller', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('controller', $item->controller), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('controller_lbl').lbl_req(), 'controller', ['class' => $label_cls]), form_input($input_acl_access_action_function_controller), ['inpt_grp_icon' => 'cog']);
                
                $input_acl_access_action_function_method = ['name' => 'method', 'type' => 'text', 'id' => 'method', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('method', $item->method), 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('method_lbl'), 'method', ['class' => $label_cls]), form_input($input_acl_access_action_function_method), ['inpt_grp_icon' => 'cog']);
                
                $acl_action_attr = ['id'=>'action_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('access_action_lbl').lbl_req(), 'action_id', ['class' => $label_cls]), form_dropdown('action_id', $acl_actions_list, set_value('action_id', $item->action_id), $acl_action_attr), ['inpt_grp_icon'=>'users']);
                
                
                $acl_level_attr = ['id'=>'acl_level_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('access_level_lbl').lbl_req(), 'acl_level_id', ['class' => $label_cls]), form_dropdown('acl_level_id', $acl_levels_list, set_value('acl_level_id', $item->acl_level_id), $acl_level_attr), ['inpt_grp_icon'=>'users']);
                
                $domain_attr = ['id'=>'domain_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('domain_lbl').lbl_req(), 'domain_id', ['class' => $label_cls]), form_dropdown('domain_id', $domains_list, set_value('domain_id', $item->domain_id), $domain_attr), ['inpt_grp_icon'=>'users']);
                
                $usergroups_attr = ['id'=>'user_groups', 'class'=>'form-control select2-multiple', 'multiple'=>'multiple'];
                echo form_input_wrapper(form_label($this->lang->line('usergroups_lbl'), 'user_groups', ['class' => $label_cls]), form_multiselect('user_groups[]', $usergroups_list, set_value('user_groups', $item->user_groups), $usergroups_attr), ['inpt_grp_icon'=>'users']);
                
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>