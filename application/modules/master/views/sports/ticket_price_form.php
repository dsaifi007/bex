<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'tickets_list', 'id' => 'ticketform'];
$btndata = ['name' => 'submit', 'id' => 'btn-ticket', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$stadium_stand_id = add_selectbx_initial($stadium_stand_id);
$league_id = add_selectbx_initial($league_id);
$match_id= add_selectbx_initial($match_id);
$stadium_id = add_selectbx_initial($stadium_id);
$currency_code = dropdown_country_code($currency_code);
$league_type_id = add_selectbx_initial($league_type_id);
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
				$currency_code_attr = ['id'=>'currency_code', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('currency_code_lbl').lbl_req(), 'currency_code', ['class' => $label_cls]), form_dropdown('currency_code', $currency_code, set_value('currency_code', $item->currency_code), $currency_code_attr), ['inpt_grp_icon'=>'dot-circle-o']);
				
				
				$input_ticket_pricing = ['name' => 'ticket_pricing', 'type' => 'text', 'id' => 'ticket_pricing', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('ticket_pricing', $item->ticket_pricing), 'required' => 'required', 'maxlength' => '30', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('ticket_pricing_id_lbl').lbl_req(), 'ticket_pricing', ['class' => $label_cls]), form_input($input_ticket_pricing), ['inpt_grp_icon' => 'envelope']);
								
				$stadium_attr = ['id'=>'stadium_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('stadium_id_lbl').lbl_req(), 'stadium_id', ['class' => $label_cls]), form_dropdown('stadium_id', $stadium_id, set_value('stadium_id', $item->stadium_id), $stadium_attr), ['inpt_grp_icon'=>'dot-circle-o']);
				

				$stadium_stand_attr = ['id'=>'stadium_stand_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('stadium_stand_id_lbl').lbl_req(), 'stadium_stand_id', ['class' => $label_cls]), form_dropdown('stadium_stand_id', $stadium_stand_id, set_value('stadium_stand_id', $item->stadium_stand_id), $stadium_stand_attr), ['inpt_grp_icon'=>'dot-circle-o']);
				
				$match_attr = ['id'=>'match_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('match_id_lbl').lbl_req(), 'match_id', ['class' => $label_cls]), form_dropdown('match_id', $match_id, set_value('match_id', $item->match_id), $match_attr), ['inpt_grp_icon'=>'dot-circle-o']);
		
				
				$league_attr = ['id'=>'league_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('league_id_lbl').lbl_req(), 'league_id', ['class' => $label_cls]), form_dropdown('league_id', $league_id, set_value('league_id', $item->league_id), $league_attr), ['inpt_grp_icon'=>'dot-circle-o']);
		
				$league_type_attr = ['id'=>'league_type_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('league_type_id_lbl').lbl_req(), 'league_type_id', ['class' => $label_cls]), form_dropdown('league_type_id', $league_type_id, set_value('league_type_id', $item->league_type_id), $league_type_attr), ['inpt_grp_icon'=>'dot-circle-o']);

				echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>