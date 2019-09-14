<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$column_attr = ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-4'];
$column_attr1 = ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-6'];
$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'ingredient_form', 'id' => 'ingredient_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-ingredient', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];

$categories_list = add_selectbx_initial($categories_list);

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
                $input_ingredient_name = ['name' => 'ingredient_name', 'type' => 'text', 'id' => 'ingredient_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('ingredient_name', $item->ingredient_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                echo form_input_wrapper(form_label($this->lang->line('ingredient_name_lbl').lbl_req(), 'ingredient_name', ['class' => $label_cls]), form_input($input_ingredient_name), ['inpt_grp_icon' => 'envelope']);
				?>
				<div class="row" id="rating_category_blk">
                <fieldset>
                    <div class="col-md-12">
					 <h3 class="form-section"><?php echo $this->lang->line('rating_category_lbl');?></h3>
						
                        <?php
                            if(count($rating_category_list) > 0){
                                $inc=0;
                                foreach($rating_category_list as $k => $v){
								   echo '<div class="repeat-blk-rating-category">';

									$category_attr = ['id'=>'category_id', 'required'=>'required', 'class'=>'form-control select2 cat-dropdown'];
									echo form_input_wrapper(form_label($this->lang->line('category_lbl').lbl_req(), 'category_id', ['class' => $label_cls]), form_dropdown('category_id['.$k.']', $categories_list, set_value('category_id['.$k.']', (isset($v->category_id) ? $v->category_id : '')), $category_attr ), ['inpt_grp_icon'=>'dot-circle-o']+ $column_attr1);
				
                                    $input_rating = ['name' => 'rating['.$k.']', 'type' => 'text', 'autocomplete' => 'off', 'class' => $input_cls. ' rating_cls', 'value' => set_value('rating['.$k.']', (isset($v->rating) ? $v->rating : '')) , 'maxlength' => '100', 'placeholder' => '', 'required' => 'required'];

                                    echo form_input_wrapper(form_label($this->lang->line('rating_lbl').lbl_req(), 'rating', ['class' => $label_cls]), form_input($input_rating), ['inpt_grp_icon' => 'envelope']+ $column_attr1);

                                    echo '</div>';
                                    $inc++;    
                                } 
                            }       
                        ?>
                    </div>
                    <div class="col-md-12">
                        <div class="portlet-body form">
                            <?php
                            $add_btn_rating_category_data = ['name' => 'add_rating_category', 'id' => 'add_rating_category', 'content' => $this->lang->line('rating_category_add_btn_lbl'), 'type' => 'button', 'class' => 'btn btn-inline btn-success btn-primary-outline'];
                            $remove_btn_rating_category_data = ['name' => 'remove_rating_category', 'id' => 'remove_rating_category', 'content' => $this->lang->line('rating_category_remove_btn_lbl'), 'type' => 'button', 'class' => 'btn btn-inline btn-danger btn-danger-outline'];
                            echo form_input_wrapper('', form_button($add_btn_rating_category_data) . form_button($remove_btn_rating_category_data), ['inpt_grp' => 0]);
                            ?>
                        </div>
                    </div>
                    </fieldset>
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