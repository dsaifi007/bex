<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'product_form', 'id' => 'product_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-product', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];
$input_arr = ['inpt_grp' => 0, 'wrap_div' => 1, 'inpt_div' => 1, 'inpt_div_cls' => 'semibold form-view-mode-input form-view-mode-input-md'];
$brands_name_list = add_selectbx_initial($brand_name);

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
                echo form_tabs_nav_layout(['general_nav'=>$this->lang->line('tab_general_lbl'), 'infoimage_nav'=>$this->lang->line('tab_image_lbl')]);
				?>

                <div class="tab-content">
                    <div class="tab-pane active" id="general_nav">
                    <?php 
                     $brand_name_list_attr = ['id'=>'brands_id', 'required'=>'required', 'class'=>'form-control select2'];
                    echo form_input_wrapper(form_label($this->lang->line('brand_name_lbl').lbl_req(), 'brands_id', ['class' => $label_cls]), form_dropdown('brands_id', $brands_name_list, set_value('brands_id', $item->brands_id), $brand_name_list_attr), ['inpt_grp_icon'=>'bitcoin']);
                        $input_product_name = ['name' => 'product_name', 'type' => 'text', 'id' => 'product_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('product_name', $item->product_name), 'required' => 'required', 'maxlength' => '150', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('product_name_lbl').lbl_req(), 'product_name', ['class' => $label_cls]), form_input($input_product_name), ['inpt_grp_icon' => 'envelope']);
        				
						$input_product_url = ['name' => 'product_url', 'type' => 'text', 'id' => 'product_url', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('product_url', $item->product_url), 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('product_url_lbl').lbl_req(), 'product_url', ['class' => $label_cls]), form_input($input_product_url), ['inpt_grp_icon' => 'envelope']);
						
        				$input_product_desc = ['name' => 'description', 'id' => 'description', 'class' => $input_cls.' summernote-editor', 'value' => set_value('description', $item->description)];
                        echo form_input_wrapper(form_label($this->lang->line('product_desc_lbl').lbl_req(), 'description', ['class' => $label_cls]), form_textarea_editor($input_product_desc),['inpt_grp' => 0]);
                        $indgrnd=explode(' ', trim(strip_tags($item->description)));
                        // if match with ingredient                 
                       $match_ing=array_intersect($indgrnd, $ingredients_list);
                       // different keywords not matched at ingredient
                        $foundIngradints=array();
                        foreach ($ingradiants_data as $ingrad) {
                            $ingrArr=explode(',', trim($ingrad['ingredients_name']));
                            $trimedIngrArr=array_map('trim',$ingrArr);
                            $matchedData=array_intersect($trimedIngrArr, $indgrnd);                       
                            if(count($matchedData)){
                              $foundIngradints[]=$ingrad;
                          }
                      }
                      $str='';
                      $inc=1;
                      foreach ($foundIngradints as $ingrd_value) {
                        if(!strpos($str,$ingrd_value['category_type_name']))
                        {
                            $br=($inc==1)?'':'<br>';
                            $str.= $br."<mark>".$ingrd_value['category_type_name']."=".$ingrd_value['backend_name']."</mark>";
                        }
                        else
                        {
                            $str.='<mark>,'.$ingrd_value['backend_name']."</mark>";
                        }
                        $inc++;
                       }
                       if($item->description)echo $str;


                        $input_product_ingredients = ['name' => 'ingredients', 'id' => 'ingredients', 'class' => $input_cls, 'value' => set_value('ingredients', $item->ingredients)];
                        echo form_input_wrapper(form_label($this->lang->line('product_ingredients_lbl').lbl_req(), 'ingredients', ['class' => $label_cls]), form_textarea($input_product_ingredients),['inpt_grp' => 0]);
                        $filter_ingrnd=array_map('trim',array_filter(explode(',',str_replace("*", "", $item->ingredients))));                    
                        $ingredients_list = Modules::run('/products/ingredients/utilityList');
                        foreach ($filter_ingrnd as $value) {
                            if(in_array($value, $ingredients_list))
                            {
                                //echo $value.",&nbsp";
                                $match_ingrnd[]=$value;
                            }
                        }

                        if(!empty($match_ing) || !empty($match_ingrnd)):
                        $ingrd_diff=array_diff($match_ing, $match_ingrnd); 
                        endif;
                        if(!empty($ingrd_diff)):         
                        echo "<br><b style='float:right;position: relative;
        top: -290px;'>Ingredients Found in Database=".$ing_diff=implode(', ',  $ingrd_diff)."</b>";
                        endif;

                        $ingrnd=(!empty($match_ingrnd))?implode(', ' , $match_ingrnd):'';                       
                        if(!empty($ingrnd)):
                        echo form_input_wrapper(form_label($this->lang->line('ingredients_found_lbl'), '', ['class' => $label_cls]), "<mark>".$ingrnd."</mark>", $input_arr);
                        endif;
                        //echo "<mark>".implode(", &nbsp;&nbsp;", $match_ingrnd)."</mark>";
                        //d($match_ingrnd);
                       /* if($item->ingredient_id):
                            foreach ($item->ingredient_id as $ingredient_id){
                                $ingredient[] = $ingredients_list[$ingredient_id];
                            }
                        echo form_input_wrapper(form_label($this->lang->line('ingredients_found_lbl'), '', ['class' => $label_cls]), "<mark>".implode(', &nbsp;&nbsp; ' , $ingredient)."</mark>", $input_arr);
                        endif;*/
                        
                        echo form_radio_wrapper(form_label($this->lang->line('product_toxic_lbl').lbl_req(), 'toxic', ['class' => $label_cls]), 'toxic', $boolean_arr, set_value('toxic', $item->toxic), '');

                        echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '');

                    ?>
                    </div>
                    <div class="tab-pane" id="infoimage_nav">

                        <?php
                        $file_input = ['name'=>'product_image_file', 'id'=>'product_image_file'];
                        echo form_input_upload(form_label($this->lang->line('product_image_lbl'), 'product_image_file', ['class' => $label_cls]).lbl_req(), $file_input, '', 1, img($preview_image_url, FALSE, ['alt'=>$item->product_name]));
                        $input_product_image_width = ['name' => 'product_image_width', 'type' => 'text', 'id' => 'product_image_width', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('product_image_width', $item->product_image_width), 'maxlength' => '4', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('product_image_width_lbl'), 'product_image_width', ['class' => $label_cls]), form_input($input_product_image_width), ['inpt_grp_icon' => 'text-width']);
                        $input_product_image_height = ['name' => 'product_image_height', 'type' => 'text', 'id' => 'product_image_height', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('product_image_height', $item->product_image_height), 'maxlength' => '4', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('product_image_height_lbl'), 'product_image_height', ['class' => $label_cls]), form_input($input_product_image_height), ['inpt_grp_icon' => 'text-height']);
                        echo form_radio_wrapper(form_label($this->lang->line('product_image_resize_lbl'), 'product_image_resize', ['class' => $label_cls]), 'product_image_resize', $boolean_arr, 0, '');
                        ?>

                    </div>
                </div>    
                <?php 
                    echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                        echo form_close();
                ?>       
            </div>
        </div>
    </div>
</div>