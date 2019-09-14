<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'ingredient_csv_form', 'id' => 'ingredient_csv_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-ingredient-csv', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];

?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success, '-1' => validation_errors()]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link)); ?>
            <div class="portlet-body form">
                <!-- BEGIN LOGIN FORM -->
                <?php
                echo form_open_multipart($form_action, $form_attributes);

                $file_input = ['name'=>'ingredient_csv_file', 'id'=>'ingredient_csv_file', 'required'=>'required'];
                echo form_input_upload(form_label($this->lang->line('import_csv_lbl'), 'ingredient_csv_file', ['class' => $label_cls]).lbl_req(), $file_input, '', 0, '');

                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                        echo form_close();
                ?>       
            </div>
        </div>
    </div>
</div>