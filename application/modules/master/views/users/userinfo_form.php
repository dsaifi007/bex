<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$column_attr = ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-4'];
$column_attr1 = ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-6'];
$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'userinfo-form', 'id' => 'userinfoform'];
$btndata = ['name' => 'submit', 'id' => 'btn-userinfo', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
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
                echo form_tabs_nav_layout(['infouser_nav'=>$this->lang->line('tab_user_lbl'), 'infovessel_nav'=>$this->lang->line('tab_vessel_lbl')]);
                ?>
                <div class="tab-content">
                    <div class="tab-pane active" id="infouser_nav">
                        <h3 class="form-section"><?php echo $this->lang->line('user_personal_info_heading'); ?></h3>
                        <div class="row">
                        <?php
                        $input_user_fname = ['name' => 'first_name', 'type' => 'text', 'id' => 'first_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('first_name', $item->first_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('user_fname_lbl').lbl_req(), 'first_name', ['class' => $label_cls]), form_input($input_user_fname), ['inpt_grp_icon' => 'smile-o']+$column_attr);
                        
                        $input_user_mname = ['name' => 'middle_name', 'type' => 'text', 'id' => 'middle_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('middle_name', $item->middle_name), 'maxlength' => '50', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('user_mname_lbl'), 'middle_name', ['class' => $label_cls]), form_input($input_user_mname), ['inpt_grp_icon' => 'smile-o']+ $column_attr);
                        
                        $input_user_lname = ['name' => 'last_name', 'type' => 'text', 'id' => 'last_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('last_name', $item->last_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('user_lname_lbl').lbl_req(), 'last_name', ['class' => $label_cls]), form_input($input_user_lname), ['inpt_grp_icon' => 'smile-o']+ $column_attr);
                        ?>
                        </div>
                        <div class="row">
                        <?php
                        $input_user_email = ['name' => 'email', 'type' => 'text', 'id' => 'email', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('email', $item->email), 'required' => 'required', 'maxlength' => '150', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('user_email_lbl').lbl_req(), 'email', ['class' => $label_cls]), form_input($input_user_email), ['inpt_grp_icon' => 'envelope-o']+ $column_attr1);
                        
                        $input_user_display_name = ['name' => 'display_name', 'type' => 'text', 'id' => 'display_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('display_name', $item->display_name), 'required' => 'required', 'maxlength' => '25', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('user_display_name_lbl').lbl_req(), 'display_name', ['class' => $label_cls]), form_input($input_user_display_name), ['inpt_grp_icon' => 'meh-o']+ $column_attr1);
                        ?>
                        </div>
                        <h3 class="form-section"><?php echo $this->lang->line('user_address_info_heading'); ?></h3>
                        <div class="row">
                            <?php
                            $input_address = ['name' => 'address', 'type' => 'text', 'id' => 'address_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('address', $item->address), 'required' => 'required', 'maxlength' => '255', 'placeholder' => ''];
                            echo form_input_wrapper(lbl_req() . form_label($this->lang->line('user_address_lbl'), 'address_id', ['class' => $label_cls]), form_input($input_address), ['inpt_grp_icon' => 'home'] + $column_attr1);

                            $input_address1 = ['name' => 'address1', 'type' => 'text', 'id' => 'address1_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('address1', $item->address1), 'maxlength' => '255', 'placeholder' => ''];
                            echo form_input_wrapper(form_label($this->lang->line('user_address1_lbl'), 'address1_id', ['class' => $label_cls]), form_input($input_address1), ['inpt_grp_icon' => 'home'] + $column_attr1);

                            ?>

                        </div>
                        <div class="row">
                            <?php
                            $input_address2 = ['name' => 'address2', 'type' => 'text', 'id' => 'address2_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('address2', $item->address2), 'maxlength' => '255', 'placeholder' => ''];
                            echo form_input_wrapper(form_label($this->lang->line('user_address2_lbl'), 'address2_id', ['class' => $label_cls]), form_input($input_address2), ['inpt_grp_icon' => 'home'] + $column_attr1);

                            $input_zipcode = ['name' => 'zipcode', 'type' => 'text', 'id' => 'zipcode_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('zipcode', $item->zipcode), 'required' => 'required', 'maxlength' => '10', 'placeholder' => ''];
                            echo form_input_wrapper(lbl_req() . form_label($this->lang->line('user_zipcode_lbl'), 'zipcode_id', ['class' => $label_cls]), form_input($input_zipcode), ['inpt_grp_icon' => 'dot-circle-o'] + $column_attr1);

                            ?>

                        </div>
                        <div class="row">
                            <?php
                            $input_city = ['name' => 'city', 'type' => 'text', 'id' => 'city_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('city', $item->city), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                            echo form_input_wrapper(lbl_req() . form_label($this->lang->line('user_city_lbl'), 'city_id', ['class' => $label_cls]), form_input($input_city), ['inpt_grp_icon' => 'road'] + $column_attr1);

                            $input_state = ['name' => 'state', 'type' => 'text', 'id' => 'state_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('state', $item->state), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                            echo form_input_wrapper(lbl_req() . form_label($this->lang->line('user_state_lbl'), 'state_id', ['class' => $label_cls]), form_input($input_state), ['inpt_grp_icon' => 'crosshairs'] + $column_attr1);

                            ?>

                        </div>
                        <div class="row">
                            <?php

                            $input_state_code = ['name' => 'state_code', 'type' => 'text', 'id' => 'state_code_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('state_code', $item->state_code), 'required' => 'required', 'maxlength' => '10', 'placeholder' => ''];
                            echo form_input_wrapper(lbl_req() . form_label($this->lang->line('user_state_code_lbl'), 'state_code_id', ['class' => $label_cls]), form_input($input_state_code), ['inpt_grp_icon' => 'crosshairs'] + $column_attr);


                            $input_country_code = ['name' => 'country_code', 'type' => 'text', 'id' => 'country_code_id', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('country_code', $item->country_code), 'required' => 'required', 'readonly'=>true, 'maxlength' => '10', 'placeholder' => ''];
                            echo form_input_wrapper(lbl_req() . form_label($this->lang->line('user_country_code_lbl'), 'country_code_id', ['class' => $label_cls]), form_input($input_country_code), ['inpt_grp_icon' => 'crosshairs'] + $column_attr);


                            $input_contact_phone = ['name' => 'phone', 'type' => 'text', 'id' => 'phone_id', 'autocomplete' => 'off', 'class' => $input_cls . ' usphone', 'value' => set_value('phone', $item->phone), 'required' => 'required', 'maxlength' => '20', 'placeholder' => ''];
                            echo form_input_wrapper(lbl_req() . form_label($this->lang->line('user_phone_lbl'), 'phone_id', ['class' => $label_cls]), form_input($input_contact_phone), ['inpt_grp_icon' => 'phone'] + $column_attr);

                            ?>

                        </div>
                        <h3 class="form-section"><?php echo $this->lang->line('user_account_settings_heading'); ?></h3>
                        <div class="row">
                        <?php
                        $input_user_password = ['name' => 'password_hash', 'type' => 'password', 'id' => 'password_hash', 'autocomplete' => 'off', 'class' => $input_cls, 'value' =>'', 'maxlength' => '20', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('user_password_lbl').lbl_req(), 'password_hash', ['class' => $label_cls]), form_input($input_user_password), ['inpt_grp_icon' => 'lock']+$column_attr1);
                        
                        $input_user_vpassword = ['name' => 'verify_password', 'type' => 'password', 'id' => 'verify_password', 'autocomplete' => 'off', 'class' => $input_cls, 'value' =>'', 'maxlength' => '20', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('user_vpassword_lbl').lbl_req(), 'verify_password', ['class' => $label_cls]), form_input($input_user_vpassword), ['inpt_grp_icon' => 'unlock']+$column_attr1);
                        ?>
                        </div>
                        <div class="row">
                            <?php
                        $usergroups_attr = ['id'=>'user_groups', 'class'=>'form-control select2-multiple', 'multiple'=>'multiple'];
                        echo form_input_wrapper(form_label($this->lang->line('user_usergroups_lbl').lbl_req(), 'user_groups', ['class' => $label_cls]), form_multiselect('user_groups[]', $usergroups_list, set_value('user_groups', $item->user_groups), $usergroups_attr), ['inpt_grp_icon'=>'users']+['wrap_div' => 1, 'wrap_div_cls' => 'col-md-12']);
                        ?>
                        </div>
                        <div class="row">
                        <?php
                        if($userinfo_system_email_flag) :
                            echo form_radio_wrapper(form_label($this->lang->line('user_receive_emails_lbl').lbl_req(), 'receive_system_emails', ['class' => $label_cls]), 'receive_system_emails', $boolean_arr, set_value('receive_system_emails', $item->receive_system_emails), 'success', 1);
                        endif;
                        echo form_radio_wrapper(form_label($this->lang->line('user_block_lbl').lbl_req(), 'block', ['class' => $label_cls]), 'block', $boolean_arr, set_value('block', $item->block), 'success', 1);
                        echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'active', ['class' => $label_cls]), 'active', $boolean_arr, set_value('active', $item->active), 'success', 1);
                        ?>
                        </div>
                        <?php
                        echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                        echo form_close();
                        ?>
                        <!-- END LOGIN FORM -->
                    </div>
                    <div class="tab-pane" id="infovessel_nav">
                        <?php require_once 'vessel_info.php';?>
                    </div>
                </div>        
            </div>
        </div>
    </div>
</div>