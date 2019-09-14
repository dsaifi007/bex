<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'banner-form', 'id' => 'bannerform'];
$btndata = ['name' => 'submit', 'id' => 'btn-banner', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$banner_categories_list = add_selectbx_initial($banner_categories_list);
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success, '-1' => validation_errors()]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link, $add_link)); ?>
            <div class="portlet-body form">
                <!-- BEGIN LOGIN FORM -->
                <?php
                echo form_open_multipart($form_action, $form_attributes, ['parent_id'=>0]);
                echo form_tabs_nav_layout(['general_nav'=>$this->lang->line('tab_general_lbl'), 'infodata_nav'=>$this->lang->line('tab_data_lbl'), 'infoimage_nav'=>$this->lang->line('tab_image_lbl')]); ?>
                <div class="tab-content">
                    <div class="tab-pane active" id="general_nav">
                        <?php
                        $input_banner_name = ['name' => 'banner_name', 'type' => 'text', 'id' => 'banner_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('banner_name', $item->banner_name), 'required' => 'required', 'maxlength' => '150', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('banner_name_lbl').lbl_req(), 'banner_name', ['class' => $label_cls]), form_input($input_banner_name), ['inpt_grp_icon' => 'envelope']);
                        $category_attr = ['id'=>'category_id', 'class'=>'form-control select2'];
                        echo form_input_wrapper(form_label($this->lang->line('banner_category_lbl').lbl_req(), 'category_id', ['class' => $label_cls]), form_dropdown('category_id', $banner_categories_list, set_value('category_id', $item->category_id), $category_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                        $input_click_url = ['name' => 'click_url', 'type' => 'text', 'id' => 'click_url', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('click_url', $item->click_url), 'placeholder' => '', 'maxlength' => '350'];
                        echo form_input_wrapper(form_label($this->lang->line('banner_click_url_lbl'), 'click_url', ['class' => $label_cls]), form_input($input_click_url), ['inpt_grp_icon' => 'link']);
                        if(count($item_ordering_list) > 0) :
                            $item_ordering_attr = ['id'=>'item_ordering', 'class'=>'form-control select2'];
                            echo form_input_wrapper(form_label($this->lang->line('banner_item_order_lbl').lbl_req(), 'item_ordering', ['class' => $label_cls]), form_dropdown('item_ordering', $item_ordering_list, $item->item_ordering, $item_ordering_attr), ['inpt_grp_icon'=>'sort']);
                        endif;
                        echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                        ?>
                    </div>
                    <div class="tab-pane" id="infodata_nav">
                        <?php
                        $input_banner_description = ['name' => 'description', 'id' => 'description', 'autocomplete' => 'off', 'class' => $input_cls.' summernote-editor'];
                        echo form_input_wrapper(form_label($this->lang->line('banner_description_lbl'), 'description', ['class' => $label_cls]), form_textarea_editor($input_banner_description, set_value('description', $item->description)), ['inpt_grp' => 0]);
                        ?>
                    </div>
                    <div class="tab-pane" id="infoimage_nav">

                        <?php
                        $file_input = ['name'=>'banner_image_file', 'id'=>'banner_image_file'];
                        echo form_input_upload(form_label($this->lang->line('banner_image_lbl'), 'banner_image_file', ['class' => $label_cls]).lbl_req(), $file_input, '', 1, img($preview_image_url, FALSE, ['alt'=>$item->banner_name]));
                        $input_banner_width = ['name' => 'banner_width', 'type' => 'text', 'id' => 'banner_width', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('banner_width', $item->banner_width), 'maxlength' => '4', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('banner_width_lbl'), 'banner_width', ['class' => $label_cls]), form_input($input_banner_width), ['inpt_grp_icon' => 'text-width']);
                        $input_banner_height = ['name' => 'banner_height', 'type' => 'text', 'id' => 'banner_height', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('banner_height', $item->banner_height), 'maxlength' => '4', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('banner_height_lbl'), 'banner_height', ['class' => $label_cls]), form_input($input_banner_height), ['inpt_grp_icon' => 'text-height']);
                        echo form_radio_wrapper(form_label($this->lang->line('banner_image_resize_lbl'), 'banner_image_resize', ['class' => $label_cls]), 'banner_image_resize', $boolean_arr, 0, '');
                        ?>

                    </div>
                </div>
                <?php echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>