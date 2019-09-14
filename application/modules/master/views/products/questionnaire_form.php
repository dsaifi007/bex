<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$column_attr = ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-4'];
$column_attr1 = ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-6'];
$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'questionnaire_form', 'id' => 'questionnaire_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-questionnaire', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$field_types_list = add_selectbx_initial($field_types_list);
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success, '-1' => validation_errors()]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link, $add_link)); ?>
            <div class="portlet-body form">
                <!-- BEGIN LOGIN FORM -->
                <?php
                echo form_open_multipart($form_action, $form_attributes);
				
				$input_question = ['name' => 'question', 'type' => 'text', 'id' => 'question', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('question', $item->question), 'maxlength'=>225, 'required' => 'required','placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('question_name_lbl').lbl_req(), 'question', ['class' => $label_cls]), form_input($input_question), ['inpt_grp_icon' => 'envelope']);
				
                $ques_type_list_attr = ['id'=>'ques_type', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('ques_type_lbl').lbl_req(), 'ques_type', ['class' => $label_cls]), form_dropdown('ques_type', $ques_type_arr, set_value('ques_type', $item->ques_type), $ques_type_list_attr), ['inpt_grp_icon'=>'dot-circle-o']);

				$field_types_list_attr = ['id'=>'field_type', 'required'=>'required', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('field_type_lbl').lbl_req(), 'field_type', ['class' => $label_cls]), form_dropdown('field_type', $field_types_list, set_value('field_type', $item->field_type), $field_types_list_attr), ['inpt_grp_icon'=>'dot-circle-o']);
				?>
				
				<div class="row" id="options_blk">
                <fieldset>
                    <div class="col-md-12">
					 <h3 class="form-section"><?php echo $this->lang->line('options_head_lbl');?></h3>
						
                        <?php
                            if(count($options_list) > 0){
                                $inc=0;
                                foreach($options_list as $k => $v){
									if($item->field_type == 4){
                                       $image_path = (isset($v->frontend_option) && $v->frontend_option != '') ? $image_dir_path.$v->frontend_option : $preview_image_url;
                                    }else{
                                        $image_path = $preview_image_url;
                                    }
									
								   echo '<div class="repeat-blk-options">';

                                    $category_attr = ['id'=>'category_id', 'class'=>'form-control cat-dropdown'];
                                    echo form_input_wrapper(form_label($this->lang->line('category_option_lbl').lbl_req(), 'category_id', ['class' => $label_cls]), form_dropdown('category_id['.$k.']', add_selectbx_initial($categories_list), set_value('category_id['.$k.']', (isset($v->category_id) ? $v->category_id : '')), $category_attr ), ['inpt_grp_icon'=>'dot-circle-o']+ ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-6 category_options']);

                                    $input_backend_option = ['name' => 'backend_option['.$k.']', 'type' => 'text', 'autocomplete' => 'off', 'class' => $input_cls. ' backend_option', 'value' => set_value('backend_option['.$k.']', (isset($v->backend_option) ? $v->backend_option : '')) , 'maxlength' => '100', 'placeholder' => '', 'required' => 'required'];

                                    echo form_input_wrapper(form_label($this->lang->line('backend_option_lbl').lbl_req(), 'backend_option', ['class' => $label_cls]), form_input($input_backend_option), ['inpt_grp_icon' => 'envelope']+ ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-6 backend_options']);

                                    $input_frontend_option = ['name' => 'frontend_option['.$k.']', 'type' => 'text', 'autocomplete' => 'off', 'class' => $input_cls. ' frontend_option', 'value' => set_value('frontend_option['.$k.']', (isset($v->frontend_option) ? $v->frontend_option : '')) , 'maxlength' => '100', 'placeholder' => '', 'required' => 'required'];

                                    echo form_input_wrapper(form_label($this->lang->line('frontend_option_lbl').lbl_req(), 'frontend_option', ['class' => $label_cls]), form_input($input_frontend_option), ['inpt_grp_icon' => 'envelope']+ ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-6 frontend_options']);

                                    $file_input = ['name'=>'option_image_file[]', 'id'=>'option_image_file'];
                                    echo form_input_upload(form_label($this->lang->line('option_image_lbl'), 'option_image_file', ['class' => $label_cls]).lbl_req(), $file_input, '', 1, img($image_path, FALSE, []), 0, 'image-options');
									
                                    echo '</div>';

                                    $inc++;    
                                } 
                            }       
                        ?>
                    </div>
                    <div class="col-md-12">
                        <div class="portlet-body form">
                            <?php
                            $add_btn_options_data = ['name' => 'add_options', 'id' => 'add_options', 'content' => $this->lang->line('options_add_btn_lbl'), 'type' => 'button', 'class' => 'btn btn-inline btn-success btn-primary-outline'];
                            $remove_btn_options_data = ['name' => 'remove_options', 'id' => 'remove_options', 'content' => $this->lang->line('options_remove_btn_lbl'), 'type' => 'button', 'class' => 'btn btn-inline btn-danger btn-danger-outline'];
                            echo form_input_wrapper('', form_button($add_btn_options_data) . form_button($remove_btn_options_data), ['inpt_grp' => 0]);
                            ?>
                        </div>
                    </div>
                    </fieldset>
                </div>
				
				<?php
                $input_tips = ['name' => 'tips', 'id' => 'tips', 'class' => $input_cls.' summernote-editor', 'required' => 'required'];
                echo form_input_wrapper(form_label($this->lang->line('question_tips_lbl').lbl_req(), 'tips', ['class' => $label_cls]), form_textarea_editor($input_tips, set_value('tips', $item->tips)),['inpt_grp' => 0]);

				$ques_parent_attr = ['id'=>'parent_id', 'class'=>'form-control select2'];
                echo form_input_wrapper(form_label($this->lang->line('question_item_parent_lbl').lbl_req(), 'parent_id', ['class' => $label_cls]), form_dropdown('parent_id', $parent_list, set_value('parent_id', $item->parent_id), $ques_parent_attr), ['inpt_grp_icon'=>'street-view']);
				
				if(count($item_ordering_list) > 0) : 
                    $item_ordering_attr = ['id'=>'item_ordering', 'class'=>'form-control select2'];
                    echo form_input_wrapper(form_label($this->lang->line('question_item_order_lbl').lbl_req(), 'item_ordering', ['class' => $label_cls]), form_dropdown('item_ordering', $item_ordering_list, $item->item_ordering, $item_ordering_attr), ['inpt_grp_icon'=>'sort']);
                endif;
								
				echo form_radio_wrapper(form_label($this->lang->line('required_lbl').lbl_req(), 'required', ['class' => $label_cls]), 'required', $boolean_arr, set_value('required', $item->required), '');
				
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
				
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>