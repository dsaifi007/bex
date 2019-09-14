<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'zipcode-form', 'id' => 'zipcodeform'];
$btndata = ['name' => 'submit', 'id' => 'btn-zipcode', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
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
                $input_acl_action = ['name' => 'zip_code', 'type' => 'text', 'id' => 'zip_code', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('zip_code', $item->zip_code), 'required' => 'required', 'maxlength' => '8', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('zip_zipcode_lbl').lbl_req(), 'zip_code', ['class' => $label_cls]), form_input($input_acl_action), ['inpt_grp_icon' => 'crosshairs']);
                $input_acl_action = ['name' => 'city', 'type' => 'text', 'id' => 'city', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('city', $item->city), 'readonly'=>true, 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('zip_city_lbl').lbl_req(), 'city', ['class' => $label_cls]), form_input($input_acl_action), ['inpt_grp_icon' => 'crosshairs']);
                $input_acl_action = ['name' => 'county', 'type' => 'text', 'id' => 'county', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('county', $item->county), 'readonly'=>true, 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('zip_county_lbl'), 'county', ['class' => $label_cls]), form_input($input_acl_action), ['inpt_grp_icon' => 'crosshairs']);
                $input_acl_action = ['name' => 'state_name', 'type' => 'text', 'id' => 'state_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('state_name', $item->state_name), 'readonly'=>true, 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('zip_state_name_lbl').lbl_req(), 'state_name', ['class' => $label_cls]), form_input($input_acl_action), ['inpt_grp_icon' => 'crosshairs']);
                $input_acl_action = ['name' => 'state_prefix', 'type' => 'text', 'id' => 'state_prefix', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('state_prefix', $item->state_prefix), 'readonly'=>true, 'required' => 'required', 'maxlength' => '5', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('zip_state_prefix_lbl').lbl_req(), 'state_prefix', ['class' => $label_cls]), form_input($input_acl_action), ['inpt_grp_icon' => 'crosshairs']);
                $input_acl_action = ['name' => 'lat', 'type' => 'text', 'id' => 'lat', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('lat', $item->lat), 'readonly'=>true, 'required' => 'required', 'maxlength' => '25', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('zip_lat_lbl').lbl_req(), 'lat', ['class' => $label_cls]), form_input($input_acl_action), ['inpt_grp_icon' => 'crosshairs']);
                $input_acl_action = ['name' => 'lon', 'type' => 'text', 'id' => 'state_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('lon', $item->lon), 'readonly'=>true, 'required' => 'required', 'maxlength' => '25', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('zip_lon_lbl').lbl_req(), 'lon', ['class' => $label_cls]), form_input($input_acl_action), ['inpt_grp_icon' => 'crosshairs']);
                $input_acl_action = ['name' => 'country_code', 'type' => 'text', 'id' => 'country_code', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('country_code', $item->country_code), 'readonly'=>true, 'required' => 'required', 'maxlength' => '3', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('zip_country_code_lbl').lbl_req(), 'country_code', ['class' => $label_cls]), form_input($input_acl_action), ['inpt_grp_icon' => 'crosshairs']);
                
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>