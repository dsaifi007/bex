<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'ticket-form', 'id' => 'ticketform'];
$btndata = ['name' => 'submit', 'id' => 'btn-ticket', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$status_list = add_selectbx_initial($status);
$categories_list = add_selectbx_initial($categories_list);
$ticket_priorities = add_selectbx_initial($ticket_priorities);
$product_list = add_selectbx_initial($product_list);
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
				$input_ticket_subject = ['name' => 'ticket_subject', 'type' => 'text', 'id' => 'ticket_subject', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('ticket_subject', $item->ticket_subject), 'maxlength' => '50', 'placeholder' => ''];
				echo form_input_wrapper(form_label($this->lang->line('ticket_subject_lbl').lbl_req(), 'ticket_subject', ['class' => $label_cls]), form_input($input_ticket_subject), ['inpt_grp_icon' => 'rocket']);
                //print_r($status);
				//die();
				
				$status_attr = ['id'=>'status_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('ticket_status_lbl').lbl_req(), 'status_id', ['class' => $label_cls]), form_dropdown('status_id', $status_list, set_value('status_id', $item->status_id), $status_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                
				$categories_attr = ['id'=>'category_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('ticket_category_lbl').lbl_req(), 'category_id', ['class' => $label_cls]), form_dropdown('category_id', $categories_list, set_value('category_id', $item->category_id), $categories_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                
				//print_r($priorities_list);
				//die();
				
				$priorities_attr = ['id'=>'priority_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('ticket_priority_lbl').lbl_req(), 'priority_id', ['class' => $label_cls]), form_dropdown('priority_id', $ticket_priorities, set_value('priority_id', $item->priority_id), $priorities_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                
				$product_attr = ['id'=>'product_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('ticket_product_lbl').lbl_req(), 'product_id', ['class' => $label_cls]), form_dropdown('product_id', $product_list, set_value('product_id', $item->product_id), $product_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                
				echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>