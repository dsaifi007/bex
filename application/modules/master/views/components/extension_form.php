<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'extension-form', 'id' => 'extensionform'];
$btndata = ['name' => 'submit', 'id' => 'btn-extension', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$add_link = ($add_btn)? $add_link:'';
$domain_list = add_selectbx_initial($domain_list);
$text_widgets = add_selectbx_initial($text_widgets);
$menu_types = add_selectbx_initial($menu_types);
$testimonial_categories = add_selectbx_initial($testimonial_categories);
$banner_categories = add_selectbx_initial($banner_categories);
$positions_list = add_selectbx_initial($positions_list);
$posts_list = add_selectbx_initial($posts_list);
$posts_categories = add_selectbx_initial($posts_categories);
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
                $domain_attr = ['id'=>'domain_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('ext_domain_lbl').lbl_req(), 'domain_id', ['class' => $label_cls]), form_dropdown('domain_id', $domain_list, set_value('domain_id', $url_domain_id), $domain_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                $input_ext_name = ['name' => 'ext_name', 'type' => 'text', 'id' => 'ext_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('ext_name', $item->ext_name), 'required' => 'required', 'maxlength' => '100', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('extension_name_lbl').lbl_req(), 'ext_name', ['class' => $label_cls]), form_input($input_ext_name), ['inpt_grp_icon' => 'envelope']);
				$input_ext_heading = ['name' => 'ext_heading', 'type' => 'text', 'id' => 'ext_heading', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('ext_heading', $item->ext_heading), 'required' => 'required', 'maxlength' => '255', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('ext_heading_lbl').lbl_req(), 'ext_heading', ['class' => $label_cls]), form_input($input_ext_heading), ['inpt_grp_icon' => 'envelope']);
				echo form_radio_wrapper(form_label($this->lang->line('ext_show_heading_lbl').lbl_req(), 'ext_show_heading', ['class' => $label_cls]), 'ext_show_heading', $boolean_arr, set_value('ext_show_heading', $item->ext_show_heading), '');
                echo form_radio_wrapper(form_label($this->lang->line('extension_type_lbl').lbl_req(), 'ext_type', ['class' => $label_cls]), 'ext_type', $extension_types, set_value('ext_type', $item->ext_type), 'ext-type');
                $text_widgets_attr = ['id'=>'text_widget_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('extension_module_lbl').lbl_req(), 'text_widget_id', ['class' => $label_cls]), form_dropdown('text_widget', $text_widgets, set_value('text_widget', $item->module_id), $text_widgets_attr), ['div_grp_cls'=>'form-group ext_module module-blk-1', 'inpt_grp_icon'=>'dot-circle-o']);
                $menutypes_attr = ['id'=>'menu_type_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('extension_module_lbl').lbl_req(), 'menu_type_id', ['class' => $label_cls]), form_dropdown('menu_type', $menu_types, set_value('menu_type', $item->module_id), $menutypes_attr), ['div_grp_cls'=>'form-group ext_module module-blk-2', 'inpt_grp_icon'=>'dot-circle-o']);
                $banner_categories_attr = ['id'=>'banner_category_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('extension_module_lbl').lbl_req(), 'banner_category_id', ['class' => $label_cls]), form_dropdown('banner_category', $banner_categories, set_value('banner_category', $item->module_id), $banner_categories_attr), ['div_grp_cls'=>'form-group ext_module module-blk-3', 'inpt_grp_icon'=>'dot-circle-o']);
                $testimonial_categories_attr = ['id'=>'testimonial_category_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('extension_module_lbl').lbl_req(), 'testimonial_category_id', ['class' => $label_cls]), form_dropdown('testimonial_category', $testimonial_categories, set_value('testimonial_category', $item->module_id), $testimonial_categories_attr), ['div_grp_cls'=>'form-group ext_module module-blk-4', 'inpt_grp_icon'=>'dot-circle-o']);
                $posts_categories_attr = ['id'=>'post_category_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('extension_module_lbl').lbl_req(), 'post_category_id', ['class' => $label_cls]), form_dropdown('post_category', $posts_categories, set_value('post_category', $item->module_id), $posts_categories_attr), ['div_grp_cls'=>'form-group ext_module module-blk-5', 'inpt_grp_icon'=>'dot-circle-o']);
                $posts_attr = ['id'=>'post_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('extension_module_lbl').lbl_req(), 'post_id', ['class' => $label_cls]), form_dropdown('post_id', $posts_list, set_value('post_id', $item->module_id), $posts_attr), ['div_grp_cls'=>'form-group ext_module module-blk-7', 'inpt_grp_icon'=>'dot-circle-o']);
                if($ext_acl_manage) {
                    $acl_level_attr = ['id' => 'acl_level_id', 'class' => 'form-control select2'];
                    echo form_input_wrapper(form_label($this->lang->line('ext_acl_level_lbl') . lbl_req(), 'acl_level_id', ['class' => $label_cls]), form_dropdown('acl_level_id', $acl_levels_list, set_value('acl_level_id', $item->acl_level_id), $acl_level_attr), ['inpt_grp_icon' => 'arrows']);
                    $usergroups_attr = ['id' => 'user_groups', 'class' => 'form-control select2-multiple', 'multiple' => 'multiple'];
                    echo form_input_wrapper(form_label($this->lang->line('extension_usergroups_lbl'), 'user_groups', ['class' => $label_cls]), form_multiselect('user_groups[]', $usergroups_list, set_value('user_groups', $item->user_groups), $usergroups_attr), ['inpt_grp_icon' => 'users']);
                }
                $positions_attr = ['id'=>'position_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('extension_position_lbl').lbl_req(), 'position_id', ['class' => $label_cls]), form_dropdown('position_id', $positions_list, set_value('position_id', $item->position_id), $positions_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                echo form_radio_wrapper(form_label($this->lang->line('extension_global_lbl').lbl_req(), 'is_ext_global', ['class' => $label_cls]), 'is_ext_global', $boolean_arr, set_value('is_ext_global', $item->is_ext_global), '');
                $selected_pages_input = ['name' => 'selected_pages', 'id' => 'selected_pages', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('selected_pages', $item->selected_pages)];
                echo form_input_wrapper(form_label($this->lang->line('extension_selected_pages_lbl'), 'selected_pages', ['class' => $label_cls]), form_textarea($selected_pages_input), ['inpt_grp' => 0]);
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>