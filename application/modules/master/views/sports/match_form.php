<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'matches_list', 'id' => 'matchform'];
$btndata = ['name' => 'submit', 'id' => 'btn-match', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$stadiums_list = add_selectbx_initial($stadiums_list);
$leagues_list = add_selectbx_initial($leagues_list);
$teams_list = add_selectbx_initial($teams_list);
$league_types_list = add_selectbx_initial($league_types_list);
$match_types_list = add_selectbx_initial($match_types_list);
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

                $league_type_attr = ['id'=>'league_type_id', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('match_league_type_lbl').lbl_req(), 'league_type_id', ['class' => $label_cls]), form_dropdown('league_type_id', $league_types_list, set_value('league_type_id', $item->league_type_id), $league_type_attr), ['inpt_grp_icon'=>'dot-circle-o']);
                $league_attr = ['id'=>'league_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('match_league_lbl').lbl_req(), 'league_id', ['class' => $label_cls]), form_dropdown('league_id', $leagues_list, set_value('league_id', $item->league_id), $league_attr), ['inpt_grp_icon'=>'dot-circle-o']);

                $home_team_attr = ['id'=>'home_team_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('match_home_team_lbl').lbl_req(), 'home_team_id', ['class' => $label_cls]), form_dropdown('home_team_id', $teams_list, set_value('home_team_id', $item->home_team_id), $home_team_attr), ['inpt_grp_icon'=>'dot-circle-o']);

                $away_team_attr = ['id'=>'away_team_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('match_away_team_lbl').lbl_req(), 'away_team_id', ['class' => $label_cls]), form_dropdown('away_team_id', $teams_list, set_value('away_team_id', $item->away_team_id), $away_team_attr), ['inpt_grp_icon'=>'dot-circle-o']);

				$input_match_timing = ['name' => 'match_timing', 'type' => 'text', 'readonly'=>'true', 'id' => 'match_timing_id', 'autocomplete' => 'off', 'class' => $input_cls.' datepicker-input', 'value' => set_value('match_timing', $item->match_timing), 'required' => 'required', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('match_timing_lbl').lbl_req(), 'match_timing', ['class' => $label_cls]), form_input($input_match_timing), ['inpt_grp_icon' => 'calendar']);
				
				$stadium_attr = ['id'=>'stadium_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('match_stadium_lbl').lbl_req(), 'stadium_id', ['class' => $label_cls]), form_dropdown('stadium_id', $stadiums_list, set_value('stadium_id', $item->stadium_id), $stadium_attr), ['inpt_grp_icon'=>'dot-circle-o']);

                $match_type_attr = ['id'=>'match_type_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('match_type_lbl').lbl_req(), 'match_type_id', ['class' => $label_cls]), form_dropdown('match_type_id', $match_types_list, set_value('match_type_id', $item->match_type_id), $match_type_attr), ['inpt_grp_icon'=>'dot-circle-o']);

                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>