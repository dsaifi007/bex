<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'menu-type-form', 'id' => 'menutypeform'];
$btndata = ['name' => 'submit', 'id' => 'btn-menu-type', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
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
                $input_menu_type_title = ['name' => 'title', 'type' => 'text', 'id' => 'title', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('title', $item->title), 'required' => 'required', 'maxlength' => '100', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('menu_type_title_lbl').lbl_req(), 'title', ['class' => $label_cls]), form_input($input_menu_type_title), ['inpt_grp_icon' => 'cog']);
                
                $input_menu_type_slug = ['name' => 'menu_type_slug', 'type' => 'text', 'id' => 'menu_type_slug', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('menu_type_slug', $item->menu_type_slug), 'required' => 'required', 'maxlength' => '100', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('menu_type_slug_lbl').lbl_req(), 'menu_type_slug', ['class' => $label_cls]), form_input($input_menu_type_slug), ['inpt_grp_icon' => 'anchor']);
               
                $domain_attr = ['id'=>'domain_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('menu_type_domain_lbl').lbl_req(), 'domain_id', ['class' => $label_cls]), form_dropdown('domain_id', $domains_list, set_value('domain_id', $item->domain_id), $domain_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                
                $input_description = ['name' => 'description', 'required'=> 'required', 'id' => 'description', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('description', $item->description), 'maxlength' => '255'];
                echo form_input_wrapper(form_label($this->lang->line('menu_type_description_lbl').lbl_req(), 'description', ['class' => $label_cls]), form_textarea($input_description), ['inpt_grp' => 0]);
                
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>