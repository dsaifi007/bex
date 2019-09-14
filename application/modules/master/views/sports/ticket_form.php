<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'tickets_list', 'id' => 'ticketform'];
$btndata = ['name' => 'submit', 'id' => 'btn-ticket', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$ticket_pricing_id = add_selectbx_initial($ticket_pricing_id);
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
                $input_ticket_number = ['name' => 'ticket_number', 'type' => 'text', 'id' => 'ticket_number', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('ticket_number', $item->ticket_number), 'required' => 'required', 'maxlength' => '30', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('ticket_number_lbl').lbl_req(), 'ticket_number', ['class' => $label_cls]), form_input($input_ticket_number), ['inpt_grp_icon' => 'envelope']);												

				$ticket_pricing_id_attr = ['id'=>'ticket_pricing_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('ticket_pricing_id_lbl').lbl_req(), 'ticket_pricing_id', ['class' => $label_cls]), form_dropdown('ticket_pricing_id', $ticket_pricing_id, set_value('ticket_pricing_id', $item->ticket_pricing_id), $ticket_pricing_id_attr), ['inpt_grp_icon'=>'dot-circle-o']);										
				echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>