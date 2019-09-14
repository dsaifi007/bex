<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'menu-item-form', 'id' => 'menuitemform'];
$btndata = ['name' => 'submit', 'id' => 'btn-menu-item', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];

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
                $input_menu_item_title = ['name' => 'menu_title', 'type' => 'text', 'id' => 'menu_title', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('menu_title', $item->menu_title), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('menu_item_title_lbl').lbl_req(), 'menu_title', ['class' => $label_cls]), form_input($input_menu_item_title), ['inpt_grp_icon' => 'cog']);
                
                echo form_radio_wrapper(form_label($this->lang->line('menu_item_type_lbl').lbl_req(), 'item_type', ['class' => $label_cls]), 'item_type', $item_type_arr, set_value('item_type', $item->item_type), 'item-type');
                
                $input_menu_item_url = ['name' => 'menu_url', 'type' => 'text', 'id' => 'menu_url', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('menu_url', $item->menu_url), 'maxlength' => '150', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('menu_item_url_lbl').lbl_req(), 'menu_url', ['class' => $label_cls]), form_input($input_menu_item_url), ['div_grp_cls'=>'form-group menu-url-blk', 'inpt_grp_icon' => 'link']);
                
                $menu_types_attr = ['id'=>'menu_type_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('menu_item_menu_type_lbl').lbl_req(), 'menu_type_id', ['class' => $label_cls]), form_dropdown('menu_type_id', $menu_types_list, set_value('menu_type_id', $item->menu_type_id), $menu_types_attr), ['inpt_grp_icon'=>'support']);
                
                $menu_parent_attr = ['id'=>'parent_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('menu_item_parent_lbl').lbl_req(), 'parent_id', ['class' => $label_cls]), form_dropdown('parent_id', $parent_list, set_value('parent_id', $item->parent_id), $menu_parent_attr), ['inpt_grp_icon'=>'street-view']);
                
                if($menu_item_acl_manage) :  
    
                $acl_level_attr = ['id'=>'acl_level_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('menu_item_acl_level_lbl').lbl_req(), 'acl_level_id', ['class' => $label_cls]), form_dropdown('acl_level_id', $acl_levels_list, set_value('acl_level_id', $item->acl_level_id), $acl_level_attr), ['inpt_grp_icon'=>'arrows']);
                
                $usergroups_attr = ['id'=>'user_groups', 'class'=>'form-control select2-multiple', 'multiple'=>'multiple'];
                echo form_input_wrapper(form_label($this->lang->line('menu_item_usergroups_lbl'), 'user_groups', ['class' => $label_cls]), form_multiselect('user_groups[]', $usergroups_list, set_value('user_groups', $item->user_groups), $usergroups_attr), ['inpt_grp_icon'=>'users']);
                
                endif;
                
                if(count($item_ordering_list) > 0) : 
                    $item_ordering_attr = ['id'=>'item_ordering', 'class'=>'form-control select2'];
                    echo form_input_wrapper(form_label($this->lang->line('menu_item_order_lbl').lbl_req(), 'item_ordering', ['class' => $label_cls]), form_dropdown('item_ordering', $item_ordering_list, $item->item_ordering, $item_ordering_attr), ['inpt_grp_icon'=>'sort']);
                endif;
                
                $input_menu_item_icon = ['name' => 'menu_icon', 'type' => 'text', 'id' => 'menu_icon', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('menu_icon', $item->menu_icon), 'maxlength' => '25', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('menu_item_icon_lbl'), 'menu_icon', ['class' => $label_cls]), form_input($input_menu_item_icon), ['inpt_grp_icon' => 'star-o']);
                echo form_radio_wrapper(form_label($this->lang->line('menu_item_browser_nav_lbl').lbl_req(), 'browser_nav', ['class' => $label_cls]), 'browser_nav', $browser_nav_arr, set_value('browser_nav', $item->browser_nav), 'browser-nav');
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>