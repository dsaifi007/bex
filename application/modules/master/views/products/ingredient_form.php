<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$column_attr = ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-4'];
$column_attr1 = ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-6'];
$column_attr2 = ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-3'];
$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'ingredient_form', 'id' => 'ingredient_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-ingredient', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
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
                $input_ingredient_name = ['name' => 'ingredient_name', 'type' => 'text', 'id' => 'ingredient_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('ingredient_name', $item->ingredient_name), 'required' => 'required', 'maxlength' => '250', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('ingredient_name_lbl').lbl_req(), 'ingredient_name', ['class' => $label_cls]), form_input($input_ingredient_name), ['inpt_grp_icon' => 'envelope']);
				?>
				<div class="row">
					<div class="col-md-12">
					<h3 class="form-section"><?php echo $this->lang->line('rating_category_lbl');?></h3>
						<fieldset>
							<div class ="col-md-6">
							<?php 
								if($skin_type_list){
									foreach($skin_type_list as $k=>$type){
										$input_skin_type = ['type' => 'text', 'autocomplete' => 'off', 'class' => $input_cls. ' skin_type_cls', 'value' => $type , 'maxlength' => '100', 'placeholder' => '', 'required' => 'required', 'readonly' => true];

										echo form_input_wrapper(form_label($this->lang->line('skin_type_lbl').lbl_req(), 'skin_type', ['class' => $label_cls]), form_input($input_skin_type), ['inpt_grp_icon' => 'envelope']+ $column_attr1);
										
										echo form_hidden('category_id['.$k.']', $k);
										
										$input_rating = ['name' => 'rating['.$k.']', 'type' => 'text', 'autocomplete' => 'off', 'class' => $input_cls. ' rating_cls', 'value' => set_value('rating['.$k.']', (isset($item->ingredient_rating[$k]) ? $item->ingredient_rating[$k] : '')) , 'maxlength' => '100', 'placeholder' => '', 'required' => 'required'];

										echo form_input_wrapper(form_label($this->lang->line('rating_lbl').lbl_req(), 'rating', ['class' => $label_cls]), form_input($input_rating), ['inpt_grp_icon' => 'envelope']+ $column_attr1);
									}
								}
							?>
						</div>	
						<div class ="col-md-6">
							<?php
								if($skin_concern_list){
									foreach($skin_concern_list as $k1=>$concern){
										$input_skin_concern = ['type' => 'text', 'autocomplete' => 'off', 'class' => $input_cls. ' skin_concern_cls', 'value' =>$concern , 'maxlength' => '100', 'placeholder' => '', 'required' => 'required' ,'readonly'=>true];

										echo form_input_wrapper(form_label($this->lang->line('skin_concern_lbl').lbl_req(), 'skin_concern', ['class' => $label_cls]), form_input($input_skin_concern), ['inpt_grp_icon' => 'envelope']+ $column_attr1);
										
										echo form_hidden('category_id['.$k1.']', $k1);
										
										$input_rating = ['name' => 'rating['.$k1.']', 'type' => 'text', 'autocomplete' => 'off', 'class' => $input_cls. ' rating_cls', 'value' => set_value('rating['.$k1.']', (isset($item->ingredient_rating[$k1]) ? $item->ingredient_rating[$k1] : '')) , 'maxlength' => '100', 'placeholder' => '', 'required' => 'required'];

										echo form_input_wrapper(form_label($this->lang->line('rating_lbl').lbl_req(), 'rating', ['class' => $label_cls]), form_input($input_rating), ['inpt_grp_icon' => 'envelope']+ $column_attr1);
									}
								}
							?>
						</div>	
					</fieldset>		
					</div>	
				</div>
			
				<?php
                echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');
                echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                echo form_close();
                ?>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>