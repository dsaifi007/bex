<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'order-form', 'id' => 'orderform'];
$btndata = ['name' => 'submit', 'id' => 'btn-order-status', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$input_arr = ['inpt_grp' => 0, 'wrap_div' => 1, 'inpt_div' => 1, 'inpt_div_cls' => 'semibold form-view-mode-input form-view-mode-input-md'];
$input_wrap_col4 = $input_arr + ['wrap_div_cls' => 'col-md-4'];
$input_wrap_col6 = $input_arr + ['wrap_div_cls' => 'col-md-6'];
$input_wrap_col12 = $input_arr + ['wrap_div_cls' => 'col-md-12'];

$order_status_list = add_selectbx_initial($order_status_list);
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success, '-1' => validation_errors()]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link)); ?>
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase"> 
                    <?php echo $this->lang->line('order_label').'#';?> 
                    <?php echo $item->id;?>
                        <span class="hidden-xs">|<?php echo formatDateTime($item->created_on, $this->display_date_full_frmt)?> </span>
                    </span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN EDIT FORM -->
                <?php echo form_open($form_action, $form_attributes); ?>

                <div class="row">
                <?php
                    $order_attr = ['id'=>'order_status_id', 'class'=>'form-control select2'];
                    echo form_input_wrapper(form_label($this->lang->line('order_order_status_lbl').lbl_req(), 'order_status_id', ['class' => $label_cls]), form_dropdown('order_status_id', $order_status_list , set_value('order_status_id', $item->order_status_id), $order_attr), ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-12', 'inpt_grp_icon'=>'dot-circle-o']);

                    $input_order_total = ['name' => '   total_price', 'type' => 'text', 'id' => 'total_price', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('total_price', $item->total_price), 'required' => 'required', 'maxlength' => '50', 'placeholder' => '', 'readonly' => 'readonly'];
                    echo form_input_wrapper(form_label($this->lang->line('order_total_price_label').lbl_req(), 'order_status', ['class' => $label_cls]), form_input($input_order_total), ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-12', 'inpt_grp_icon'=>'dot-circle-o']); 
                ?>
                </div>
                <div class="row">
                    <?php
                    echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'), ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-12']);
                    ?>
                </div>
                <?php echo form_close(); ?>
                <!-- END EDIT FORM -->
            </div>
        </div>
    </div>
</div>