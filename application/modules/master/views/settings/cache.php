<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'cache-form', 'id' => 'cacheform'];
$btndata = ['name' => 'submit', 'id' => 'btn-cache', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label'), ''); ?>
            <div class="portlet-body">
                <?php
                echo form_open($form_action, $form_attributes);
                echo form_radio_wrapper(form_label($this->lang->line('cache_title_lbl'), 'cache_type', ['class' => $label_cls]), 'cache_type', $cache_type_list, 1, 'cache_type');
                echo form_actions_wrapper(form_button($btndata));
                echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>