<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'stadium-form', 'id' => 'stadiumform'];
$btndata = ['name' => 'submit', 'id' => 'btn-stadium', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$countries_list = add_selectbx_initial($countries_list);
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
                echo form_tabs_nav_layout(['general_nav'=>$this->lang->line('tab_general_lbl'), 'infodata_nav'=>$this->lang->line('tab_data_lbl'), 'infoimage_nav'=>$this->lang->line('tab_image_lbl')]);
                ?>
                <div class="tab-content">
                    <div class="tab-pane active" id="general_nav">
                        <?php
                        //echo form_open($form_action, $form_attributes);
                        $input_stadium_name = ['name' => 'stadium_name', 'type' => 'text', 'id' => 'stadium_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('stadium_name', $item->stadium_name), 'required' => 'required', 'maxlength' => '100', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('stadium_name_lbl').lbl_req(), 'stadium_name', ['class' => $label_cls]), form_input($input_stadium_name), ['inpt_grp_icon' => 'envelope']);
                        $country_attr = ['id'=>'country_code_id', 'class'=>'form-control select2'];
                        echo form_input_wrapper(form_label($this->lang->line('stadium_country_code_lbl').lbl_req(), 'country_code_id', ['class' => $label_cls]), form_dropdown('country_code', $countries_list, set_value('country_code', $item->country_code), $country_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                        $input_zipcode = ['name' => 'zipcode', 'type' => 'text', 'id' => 'zipcode_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('zipcode', $item->zipcode), 'required' => 'required', 'maxlength' => '10', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('stadium_zipcode_lbl').lbl_req(), 'zipcode_id', ['class' => $label_cls]), form_input($input_zipcode), ['inpt_grp_icon' => 'envelope']);
                        $input_city = ['name' => 'city', 'type' => 'text', 'id' => 'city_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('city', $item->city), 'required' => 'required', 'maxlength' => '80', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('stadium_city_lbl').lbl_req(), 'city_id', ['class' => $label_cls]), form_input($input_city), ['inpt_grp_icon' => 'envelope']);
        				$input_state = ['name' => 'state', 'type' => 'text', 'id' => 'state_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('state', $item->state), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('stadium_state_lbl').lbl_req(), 'state_id', ['class' => $label_cls]), form_input($input_state), ['inpt_grp_icon' => 'envelope']);
                        $input_state_code = ['name' => 'state_code', 'type' => 'text', 'id' => 'state_code_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('state_code', $item->state_code), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('stadium_state_code_lbl').lbl_req(), 'state_code_id', ['class' => $label_cls]), form_input($input_state_code), ['inpt_grp_icon' => 'envelope']);
                        echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');

                        ?>
                  </div>
                    <div class="tab-pane" id="infodata_nav">
                        <?php
                        $description = ['name' => 'description', 'type' => 'text', 'id' => 'description', 'autocomplete' => 'off', 'class' => $input_cls.' summernote-editor'];
                        echo form_input_wrapper(form_label($this->lang->line('stadium_description_lbl'), 'description', ['class' => $label_cls]),  form_textarea_editor($description, set_value('description', $item->description)), ['inpt_grp' => 0]);
                        ?>
                    </div>
                    <div class="tab-pane" id="infoimage_nav">
                        <?php
                        $file_input = ['name'=>'item_image', 'id'=>'item_image'];
                        echo form_input_upload(form_label($this->lang->line('stadium_image_lbl'), 'item_image', ['class' => $label_cls]), $file_input, '', 1, img($preview_image_url, FALSE, ['alt'=>$item->stadium_name]));
                        

                        $input_image_width = ['name' => 'image_width', 'type' => 'text', 'id' => 'image_width', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('image_width', 0), 'maxlength' => '4', 'placeholder' => ''];
                        

                        echo form_input_wrapper(form_label($this->lang->line('image_width_lbl'), 'image_width', ['class' => $label_cls]), form_input($input_image_width), ['inpt_grp_icon' => 'text-width']);
                        $input_image_height = ['name' => 'image_height', 'type' => 'text', 'id' => 'image_height', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('image_height', 0), 'maxlength' => '4', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('image_height_lbl'), 'image_height', ['class' => $label_cls]), form_input($input_image_height), ['inpt_grp_icon' => 'text-height']);
                        echo form_radio_wrapper(form_label($this->lang->line('image_resize_lbl'), 'image_resize', ['class' => $label_cls]), 'image_resize', $boolean_arr, 0, '');
                        ?>
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
</div>