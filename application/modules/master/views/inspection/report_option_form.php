<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'report_option_form', 'id' => 'report_option_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-report-option', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$anode_list = add_selectbx_initial($anode_list);
$report_type_list = add_selectbx_initial($report_type_list);
$field_type_list = add_selectbx_initial($field_type_list);
//d($item);
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

                echo form_radio_wrapper(form_label($this->lang->line('report_option_type_lbl').lbl_req(), 'option_type', ['class' => $label_cls]), 'option_type', $option_type_arr, set_value('option_type', $item->option_type), 'option-type');
                ?>

                <div id="field_type_blk">
                <?php
                    $field_type_attr = ['id'=>'field_type', 'required'=>'required', 'class'=>'form-control select2'];
                    echo form_input_wrapper(form_label($this->lang->line('report_option_field_type_lbl').lbl_req(), 'field_type', ['class' => $label_cls]), form_dropdown('field_type', $field_type_list, set_value('field_type', $item->field_type), $field_type_attr ), ['inpt_grp_icon'=>'dot-circle-o']);
                ?>
                </div>

                <?php
                $input_option_name = ['name' => 'option_name', 'type' => 'text', 'id' => 'option_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('option_name', $item->option_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('report_option_name_lbl').lbl_req(), 'option_name', ['class' => $label_cls]), form_input($input_option_name), ['inpt_grp_icon' => 'envelope']);

                $option_parent_attr = ['id'=>'parent_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('report_option_parent_lbl').lbl_req(), 'parent_id', ['class' => $label_cls]), form_dropdown('parent_id', $parent_list, set_value('parent_id', $item->parent_id), $option_parent_attr), ['inpt_grp_icon'=>'street-view']);

                $report_type_attr = ['id'=>'report_type_id', 'class'=>'form-control select2', 'required'=>'required'];
                echo form_input_wrapper(form_label($this->lang->line('report_type_lbl').lbl_req(), 'report_type_id', ['class' => $label_cls]), form_dropdown('report_type_id', $report_type_list, set_value('report_type_id', $item->report_type_id), $report_type_attr), ['inpt_grp_icon'=>'street-view']);
                 
                ?>
                <div id="anode_blk">
                <?php
                    $anode_attr = ['id'=>'anode_id', 'required'=>'required', 'class'=>'form-control select2'];
                    echo form_input_wrapper(form_label($this->lang->line('report_option_anode_type_lbl').lbl_req(), 'anode_id', ['class' => $label_cls]), form_dropdown('anode_id', $anode_list, set_value('anode_id', $item->anode_id), $anode_attr ), ['inpt_grp_icon'=>'dot-circle-o']);
                ?>
                </div>
                <?php
                if(count($item_ordering_list) > 0) : 
                    $item_ordering_attr = ['id'=>'item_ordering', 'class'=>'form-control select2'];
                    echo form_input_wrapper(form_label($this->lang->line('report_option_name_order_lbl').lbl_req(), 'item_ordering', ['class' => $label_cls]), form_dropdown('item_ordering', $item_ordering_list, $item->item_ordering, $item_ordering_attr), ['inpt_grp_icon'=>'sort']);
                endif;

                echo form_radio_wrapper(form_label($this->lang->line('report_option_notes_label').lbl_req(), 'notes_required', ['class' => $label_cls]), 'notes_required', $boolean_arr, set_value('notes_required', $item->notes_required), 'notes-required');

                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), 'success');

                ?>

                <div class="row" id="option_values_blk">
                <fieldset>
                    <div class="col-md-12">
                        <h3 class="form-section"><?php echo $this->lang->line('report_option_values_lbl');?></h3>
                            <?php
                                if(count($option_values_list) > 0){
                                    $inc=0;
                                    foreach($option_values_list as $k => $v){
                                        $input_option_value = ['name' => 'option_value['.$k.']', 'type' => 'text', 'autocomplete' => 'off', 'class' => $input_cls. ' options_value_cls', 'value' => set_value('option_value['.$k.']', (isset($v->value) ? $v->value : '')) , 'maxlength' => '100', 'placeholder' => '', 'required' => 'required'];

                                        echo form_input_wrapper(form_label($this->lang->line('report_value_lbl').lbl_req(), 'option_value', ['class' => $label_cls]), form_input($input_option_value), ['inpt_grp_icon' => 'envelope', 'wrap_div'=>1, 'wrap_div_cls'=>'repeat-blk-option-values']);
                                        $inc++;    
                                    } 
                                }       
                            ?>
                    </div>
                    <div class="col-md-12">
                        <div class="portlet-body form">
                            <?php
                            $add_btn_report_option_value_data = ['name' => 'add_option_value', 'id' => 'add_option_value', 'content' => $this->lang->line('report_option_value_add_btn_lbl'), 'type' => 'button', 'class' => 'btn btn-inline btn-success btn-primary-outline'];
                            $remove_btn_report_option_value_data = ['name' => 'remove_secondary_contact', 'id' => 'remove_option_value', 'content' => $this->lang->line('report_option_value_remove_btn_lbl'), 'type' => 'button', 'class' => 'btn btn-inline btn-danger btn-danger-outline'];
                            echo form_input_wrapper('', form_button($add_btn_report_option_value_data) . form_button($remove_btn_report_option_value_data), ['inpt_grp' => 0]);
                            ?>
                        </div>
                    </div>
                    </fieldset>
                </div>

                <?php
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>