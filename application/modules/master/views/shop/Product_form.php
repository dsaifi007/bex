<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'text-widget-form', 'id' => 'productform'];
$btndata = ['name' => 'submit', 'id' => 'btn-product', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
//$domain_list = add_selectbx_initial($domain_list);
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success, '-1' => validation_errors()]); ?>
			<?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link, $add_link)); ?>
            <div class="portlet-body form">
                <!-- BEGIN LOGIN FORM -->
                <?php
                echo form_open_multipart($form_action, $form_attributes);
                echo form_tabs_nav_layout(['general_nav'=>$this->lang->line('tab_general_lbl'), 'infodata_nav'=>$this->lang->line('tab_data_lbl'), 'infoimage_nav'=>$this->lang->line('tab_image_lbl')]); ?>
                <div class="tab-content">
                    <div class="tab-pane active" id="general_nav">
                <?php
                $input_product_name = ['name' => 'product_name', 'type' => 'text', 'id' => 'product_name_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('product_name', $item->product_name), 'required' => 'required', 'maxlength' => '255', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('product_name_lbl').lbl_req(), 'product_name', ['class' => $label_cls]), form_input($input_product_name), ['inpt_grp_icon' => 'star-o']);                

				$input_product_sku = ['name' => 'product_sku', 'type' => 'text', 'id' => 'product_sku', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('product_sku', $item->product_sku), 'required' => 'required', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('product_sku_lbl').lbl_req(), 'product_sku', ['class' => $label_cls]), form_input($input_product_sku), ['inpt_grp_icon' => 'star-o']);
                
				$input_product_model = ['name' => 'product_model_no', 'type' => 'text', 'id' => 'product_model_no', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('product_model_no', $item->product_model_no), 'required' => 'required', 'maxlength' => '30', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('product_model_no_lbl').lbl_req(), 'product_model', ['class' => $label_cls]), form_input($input_product_model), ['inpt_grp_icon' => 'star-o']);

                $categories_list = add_selectbx_initial($categories_list);
                $product_attr = ['id'=>'product_category_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('product_category_lbl').lbl_req(), 'product_category_id', ['class' => $label_cls]), form_dropdown('product_category_id', $categories_list, set_value('product_category_id', $item->product_category_id), $product_attr), ['inpt_grp_icon'=>'dot-circle-o']);

                $manufacturer_list = add_selectbx_initial($manufacturer_list);
                $product_attr = ['id'=>'product_manfacturer_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('product_manfacturer_lbl').lbl_req(), 'product_manfacturer_id', ['class' => $label_cls]), form_dropdown('product_manfacturer_id', $manufacturer_list, set_value('product_manfacturer_id', $item->product_manfacturer_id), $product_attr), ['inpt_grp_icon'=>'dot-circle-o']);


                $input_product_weight = ['name' => 'product_weight', 'type' => 'text', 'id' => 'product_weight', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('product_weight', $item->product_weight), 'required' => 'required', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('product_weight_lbl'), 'product_weight', ['class' => $label_cls]), form_input($input_product_weight), ['inpt_grp_icon' => 'star-o']);


                $input_product_quality_certification = ['name' => 'product_quality_certification', 'type' => 'text', 'id' => 'product_quality_certification', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('product_quality_certification', $item->product_quality_certification), 'required' => 'required', 'maxlength' => '15', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('product_quality_certification_lbl').lbl_req(), 'product_quality_certification', ['class' => $label_cls]), form_input($input_product_quality_certification), ['inpt_grp_icon' => 'star-o']);                
				

				echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');
                ?>
                    </div>
                    <div class="tab-pane" id="infodata_nav">
                        <?php
                        $input_product_description = ['name' => 'product_description', 'id' => 'product_description', 'autocomplete' => 'off', 'class' => $input_cls.' summernote-editor'];
                        echo form_input_wrapper(form_label($this->lang->line('product_desc_lbl').lbl_req(), 'product_description', ['class' => $label_cls]), form_textarea_editor($input_product_description, set_value('product_description', $item->product_description)), ['inpt_grp' => 0]);

                        ?>
                    </div>
                    <div class="tab-pane" id="infoimage_nav">
                        <?php
                        $file_input = ['name'=>'product_image_file', 'id'=>'product_image_file'];
                        echo form_input_upload(form_label($this->lang->line('product_image_lbl'), 'product_image_file', ['class' => $label_cls]), $file_input, '', 1, img($preview_image_url, FALSE, ['alt'=>$item->product_name]));
                        $input_product_image_width = ['name' => 'image_width', 'type' => 'text', 'id' => 'image_width', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('image_width', 0), 'maxlength' => '4', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('product_image_width_lbl'), 'image_width', ['class' => $label_cls]), form_input($input_product_image_width), ['inpt_grp_icon' => 'text-width']);
                        $input_product_image_height = ['name' => 'image_height', 'type' => 'text', 'id' => 'image_height', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('image_height', 0), 'maxlength' => '4', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('product_image_height_lbl'), 'image_height', ['class' => $label_cls]), form_input($input_product_image_height), ['inpt_grp_icon' => 'text-height']);
                        echo form_radio_wrapper(form_label($this->lang->line('product_image_resize_lbl'), 'product_image_resize', ['class' => $label_cls]), 'product_image_resize', $boolean_arr, 0, '');
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