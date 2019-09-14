<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' =>'league-form', 'id' =>'leagueform'];
$btndata = ['name' => 'submit', 'id' => 'btn-league', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];

$domains_list = add_selectbx_initial($domains_list);
$league_types_list = add_selectbx_initial($league_types_list);
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
                $domain_attr = ['id'=>'domain_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('league_domain_lbl').lbl_req(), 'domain_id', ['class' => $label_cls]), form_dropdown('domain_id', $domains_list, set_value('domain_id', $item->domain_id), $domain_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                $league_type_attr = ['id'=>'league_type_id', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('league_league_type_id_lbl').lbl_req(), 'league_type_id', ['class' => $label_cls]), form_dropdown('league_type_id', $league_types_list, set_value('league_type_id', $item->league_type_id), $league_type_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                $country_attr = ['id'=>'country_codes_id', 'class'=>'form-control select2-multiple', 'required'=>'required', 'multiple'=>'multiple'];
                echo form_input_wrapper(form_label($this->lang->line('tb_hd_league_countries').lbl_req(), 'country_codes_id', ['class' => $label_cls]), form_multiselect('country_codes[]', $countries_list, set_value('country_codes', $item->country_codes), $country_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                $teams_attr = ['id'=>'teams_id', 'class'=>'form-control select2-multiple', 'required'=>'required', 'multiple'=>'multiple'];
                echo form_input_wrapper(form_label($this->lang->line('league_teams_lbl').lbl_req(), 'teams_id', ['class' => $label_cls]), form_multiselect('teams[]', $teams_list, set_value('teams', $item->teams), $teams_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                $input_league_name = ['name' => 'league_name', 'type' => 'text', 'id' => 'league_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('league_name', $item->league_name),  'maxlength' => '255', 'placeholder' => ''];
				echo form_input_wrapper(form_label($this->lang->line('league_name_lbl').lbl_req(), 'league_name', ['class' => $label_cls]), form_input($input_league_name), ['inpt_grp_icon' => 'envelope']);
				$starts_on = ['name' => 'starts_on', 'type' => 'text', 'id' => 'starts_on', 'autocomplete' => 'off', 'class' => $input_cls.' datepicker-input', 'value' => set_value('starts_on', $item->starts_on), 'readonly'=>'true', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('league_starts_on_lbl').lbl_req(), 'starts_on', ['class' => $label_cls]), form_input($starts_on), ['inpt_grp_icon' => 'calendar']);
				$ends_on = ['name' => 'ends_on', 'type' => 'text', 'id' => 'ends_on', 'autocomplete' => 'off', 'class' => $input_cls.' datepicker-input', 'value' => set_value('ends_on', $item->ends_on), 'readonly'=>'true', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('league_ends_on_lbl').lbl_req(), 'ends_on', ['class' => $label_cls]), form_input($ends_on), ['inpt_grp_icon' => 'calendar']);
				echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>