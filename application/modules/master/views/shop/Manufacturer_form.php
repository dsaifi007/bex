<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'manufacturer-form', 'id' => 'manufacturerform'];
$btndata = ['name' => 'submit', 'id' => 'btn-manufacturer', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
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
				$input_manufacturer = ['name' => 'manufacturer', 'type' => 'text', 'id' => 'manufacturer', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('manufacturer', $item->manufacturer), 'maxlength' => '100', 'placeholder' => ''];
				echo form_input_wrapper(form_label($this->lang->line('shop_manufacture_lbl').lbl_req(), 'manufacturer', ['class' => $label_cls]), form_input($input_manufacturer), ['inpt_grp_icon' => 'rocket']);
                $stores_attr = ['id'=>'stores_id', 'class'=>'form-control select2-multiple', 'multiple'=>'multiple'];
                echo form_input_wrapper(form_label($this->lang->line('shop_manufacture_stores_lbl').lbl_req(), 'stores_id', ['class' => $label_cls]), form_multiselect('stores[]', $stores_list, set_value('stores', $item->stores), $stores_attr), ['inpt_grp_icon'=>'users']);
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>