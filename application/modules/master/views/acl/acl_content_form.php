<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'acl-content-form', 'id' => 'aclcontentform'];
$btndata = ['name' => 'submit', 'id' => 'btn-acl-content', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
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
                $input_acl_content_content_title = ['name' => 'content_title', 'type' => 'text', 'id' => 'content_title', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('content_title', $item->content_title), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('acl_content_title_lbl').lbl_req(), 'content_title', ['class' => $label_cls]), form_input($input_acl_content_content_title), ['inpt_grp_icon' => 'cog']);
                
                $acl_level_attr = ['id'=>'acl_level_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('access_level_lbl').lbl_req(), 'acl_level_id', ['class' => $label_cls]), form_dropdown('acl_level_id', $acl_levels_list, set_value('acl_level_id', $item->acl_level_id), $acl_level_attr), ['inpt_grp_icon'=>'bars']);
                
                $domain_attr = ['id'=>'domain_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('content_domain_lbl').lbl_req(), 'domain_id', ['class' => $label_cls]), form_dropdown('domain_id', $domains_list, set_value('domain_id', $item->domain_id), $domain_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                
                $usergroups_attr = ['id'=>'user_groups', 'class'=>'form-control select2-multiple', 'multiple'=>'multiple'];
                echo form_input_wrapper(form_label($this->lang->line('usergroups_lbl'), 'user_groups', ['class' => $label_cls]), form_multiselect('user_groups[]', $usergroups_list, set_value('user_groups', $item->user_groups), $usergroups_attr), ['inpt_grp_icon'=>'users']);
                
                $input_description = ['name' => 'description', 'required'=> 'required', 'id' => 'description', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('description', $item->description), 'maxlength' => '200'];
                echo form_input_wrapper(form_label($this->lang->line('content_description_lbl').lbl_req(), 'description', ['class' => $label_cls]), form_textarea($input_description), ['inpt_grp' => 0]);
                
                echo form_radio_wrapper(form_label($this->lang->line('acl_content_type_lbl').lbl_req(), 'content_type', ['class' => $label_cls]), 'content_type', $content_type_arr, set_value('content_type', $item->content_type), 'content_type');
                
                $content_parent_attr = ['id'=>'parent_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('acl_parent_id_lbl').lbl_req(), 'parent_id', ['class' => $label_cls]), form_dropdown('parent_id', $content_parent_list, set_value('parent_id', $item->parent_id), $content_parent_attr), ['div_grp_cls'=>'form-group content_parent_id_blk', 'inpt_grp_icon'=>'arrows']);
                
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>