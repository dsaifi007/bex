<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'ticket-priority-form', 'id' => 'ticketpriorityform'];
$btndata = ['name' => 'submit', 'id' => 'btn-ticket-priority', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success, '-1' => validation_errors()]); ?>
<?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link, $add_link)); ?>
            <div class="portlet-body form">
                <!-- BEGIN LOGIN FORM -->
                <?php
                echo form_open($form_action, $form_attributes, ['parent_id'=>$item->parent_id]);
                $input_ticket_priority = ['name' => 'ticket_priority', 'type' => 'text', 'id' => 'ticket_priority', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('ticket_priority', $item->ticket_priority), 'maxlength' => '40', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('ticket_priority_lbl').lbl_req(), 'ticket_priority', ['class' => $label_cls]), form_input($input_ticket_priority), ['inpt_grp_icon' => 'user']);
                if(count($item_ordering_list) > 0) :
                    $item_ordering_attr = ['id'=>'item_ordering', 'class'=>'form-control select2'];
                    echo form_input_wrapper(form_label($this->lang->line('ticket_priority_item_order_lbl').lbl_req(), 'item_ordering', ['class' => $label_cls]), form_dropdown('item_ordering', $item_ordering_list, $item->item_ordering, $item_ordering_attr), ['inpt_grp_icon'=>'sort']);
                endif;
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>