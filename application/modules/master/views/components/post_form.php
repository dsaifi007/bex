<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'post-form', 'id' => 'postform'];
$btndata = ['name' => 'submit', 'id' => 'btn-post', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$domain_list = add_selectbx_initial($domain_list);
$post_categories = add_selectbx_initial($post_categories);
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success, '-1' => validation_errors()]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link, $add_link)); ?>
            <div class="portlet-body form">
                <!-- BEGIN LOGIN FORM -->
                <?php
                echo form_open_multipart($form_action, $form_attributes, ['parent_id'=>$item->parent_id]);
                echo form_tabs_nav_layout(['general_nav'=>$this->lang->line('tab_general_lbl'), 'infodata_nav'=>$this->lang->line('tab_data_lbl'), 'infoimage_nav'=>$this->lang->line('tab_image_lbl')]);
                ?>
                <div class="tab-content">
                    <div class="tab-pane active" id="general_nav">
                        <?php
                        $input_post_name = ['name' => 'post_name', 'type' => 'text', 'id' => 'post_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('post_name', $item->post_name), 'required' => 'required', 'maxlength' => '255', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('post_name_lbl').lbl_req(), 'post_name', ['class' => $label_cls]), form_input($input_post_name), ['inpt_grp_icon' => 'envelope']);

                        $input_post_alias = ['name' => 'post_alias', 'type' => 'text', 'id' => 'post_alias', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('post_alias', $item->post_alias), 'placeholder' => '','maxlength' => '255'];
                        echo form_input_wrapper(form_label($this->lang->line('post_alias_lbl').lbl_req(), 'post_alias', ['class' => $label_cls]), form_input($input_post_alias), ['inpt_grp_icon' => 'user']);

                        $category_attr = ['id'=>'post_category_id', 'class'=>'form-control select2'];
                        echo form_input_wrapper(form_label($this->lang->line('post_category_lbl').lbl_req(), 'post_category_id', ['class' => $label_cls]), form_dropdown('post_category_id', $post_categories, set_value('post_category_id', $item->post_category_id), $category_attr), ['inpt_grp_icon'=>'dot-circle-o']);

                        $domain_attr = ['id'=>'domain_id', 'class'=>'form-control select2'];
                        echo form_input_wrapper(form_label($this->lang->line('post_domain_lbl').lbl_req(), 'domain_id', ['class' => $label_cls]), form_dropdown('domain_id', $domain_list, set_value('domain_id', $item->domain_id), $domain_attr), ['inpt_grp_icon'=>'dot-circle-o']);

                        if(count($item_ordering_list) > 0) :
                            $item_ordering_attr = ['id'=>'item_ordering', 'class'=>'form-control select2'];
                            echo form_input_wrapper(form_label($this->lang->line('post_item_order_lbl').lbl_req(), 'item_ordering', ['class' => $label_cls]), form_dropdown('item_ordering', $item_ordering_list, $item->item_ordering, $item_ordering_attr), ['inpt_grp_icon'=>'sort']);
                        endif;
                        echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                        ?>
                    </div>
                    <div class="tab-pane" id="infodata_nav">
                        <?php

                        $input_post_description = ['name' => 'description', 'type' => 'text', 'id' => 'description', 'autocomplete' => 'off', 'class' => $input_cls.' summernote-editor'];
                        echo form_input_wrapper(form_label($this->lang->line('post_description_lbl'), 'description', ['class' => $label_cls]),  form_textarea_editor($input_post_description, set_value('description', $item->description)), ['inpt_grp' => 0]);

                        $input_post_meta_title = ['name' => 'meta_title', 'type' => 'text', 'id' => 'meta_title_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('meta_title', $item->meta_title), 'maxlength' => '255', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('post_meta_title_lbl'), 'meta_title_id', ['class' => $label_cls]), form_input($input_post_meta_title), ['inpt_grp_icon' => 'star-o']);

                        $input_post_meta_keywords = ['name' => 'meta_keywords', 'id' => 'meta_keywords', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('meta_keywords', $item->meta_keywords), 'maxlength' => '500'];
                        echo form_input_wrapper(form_label($this->lang->line('post_meta_keywords_lbl'), 'meta_keywords', ['class' => $label_cls]), form_textarea($input_post_meta_keywords), ['inpt_grp' => 0]);

                        $input_post_meta_description = ['name' => 'meta_description', 'id' => 'meta_description', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('meta_description', $item->meta_description), 'maxlength' => '1000'];
                        echo form_input_wrapper(form_label($this->lang->line('post_meta_description_lbl'), 'meta_description', ['class' => $label_cls]), form_textarea($input_post_meta_description), ['inpt_grp' => 0]);

                        ?>
                    </div>

                    <div class="tab-pane" id="infoimage_nav">
                        <?php
                        $file_input = ['name'=>'post_image_file', 'id'=>'post_image_file'];
                        echo form_input_upload(form_label($this->lang->line('post_image_lbl'), 'post_image_file', ['class' => $label_cls]).lbl_req(), $file_input, '', 1, img($preview_image_url, FALSE, ['alt'=>$item->post_name]));
                        $input_image_width = ['name' => 'image_width', 'type' => 'text', 'id' => 'image_width', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('image_width', 0), 'maxlength' => '4', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('image_width_lbl'), 'image_width', ['class' => $label_cls]), form_input($input_image_width), ['inpt_grp_icon' => 'text-width']);
                        $input_image_height = ['name' => 'image_height', 'type' => 'text', 'id' => 'image_height', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('image_height', 0), 'maxlength' => '4', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('image_height_lbl'), 'image_height', ['class' => $label_cls]), form_input($input_image_height), ['inpt_grp_icon' => 'text-height']);
                        echo form_radio_wrapper(form_label($this->lang->line('image_resize_lbl'), 'image_resize', ['class' => $label_cls]), 'image_resize', $boolean_arr, 0, '');
                        ?>
                    </div>
                </div>

                <?php

                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>