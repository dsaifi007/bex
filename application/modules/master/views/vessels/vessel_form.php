<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$column_attr = ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-4'];
$column_attr1 = ['wrap_div' => 1, 'wrap_div_cls' => 'col-md-6'];
$label_cls = 'control-label';
$input_cls = 'form-control';
$form_attributes = ['class' => 'vessel_form', 'id' => 'vessel_form'];
$btndata = ['name' => 'submit', 'id' => 'btn-vessel', 'content' => '<span class="ladda-label">' . $this->lang->line('submit_btn') . '</span>', 'value' => 'submit', 'type' => 'submit', 'class' => 'btn blue uppercase ladda-button', 'data-style' => 'zoom-out', 'data-size' => 's'];

$users_list = add_selectbx_initial($users_list);
$vessel_type_list = add_selectbx_initial($vessel_type_list);
$style_list = add_selectbx_initial($style_list);
$manufacturer_list = add_selectbx_initial($manufacturer_list);
$drive_type_list = add_selectbx_initial($drive_type_list);
$public_address_list = add_selectbx_initial($public_address_list);

?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success, '-1' => validation_errors()]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link, $add_link)); ?>
                <div class="portlet-body form">
                <!-- BEGIN LOGIN FORM -->
                    <?php
                    echo form_open($form_action, $form_attributes);?>
                    <h3 class="form-section">
                        <?php echo $this->lang->line('vessel_basic_info_heading'); ?>
                    </h3>
                    <div class="row">
                        <?php                         
                            $input_vessel_name = ['name' => 'vessel_name', 'type' => 'text', 'id' => 'vessel_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('vessel_name', $item->vessel_name), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                            echo form_input_wrapper(form_label($this->lang->line('vessel_name_lbl').lbl_req(), 'vessel_name', ['class' => $label_cls]), form_input($input_vessel_name),['inpt_grp_icon' => 'envelope']+ $column_attr1);

                            $users_attr = ['id'=>'user_id', 'required'=>'required', 'class'=>'form-control select2'];
                            echo form_input_wrapper(form_label($this->lang->line('vessel_user_type_lbl').lbl_req(), 'user_id', ['class' => $label_cls]), form_dropdown('user_id', $users_list, set_value('user_id', $item->user_id), $users_attr), ['inpt_grp_icon'=>'dot-circle-o']+ $column_attr1);

                            if(isset($item->user_id) && $item->user_id != 0){
                                echo anchor(site_url($user_list_link . '/edit/' . encodeData($item->user_id)) ,'Edit User', array('class'=>'btn sbold green pull-right'));
                            }
                        ?>
                    </div>
                    <div class="row">
                    <?php
                        $type_attr = ['id'=>'vessel_type_id', 'required'=>'required', 'class'=>'form-control select2'];
                        echo form_input_wrapper(form_label($this->lang->line('vessel_type_lbl').lbl_req(), 'vessel_type_id', ['class' => $label_cls]), form_dropdown('vessel_type_id', $vessel_type_list, set_value('vessel_type_id', $item->vessel_type_id), $type_attr), ['inpt_grp_icon'=>'dot-circle-o']+ $column_attr);
                        $style_attr = ['id'=>'style_id', 'required'=>'required', 'class'=>'form-control select2'];
                        echo form_input_wrapper(form_label($this->lang->line('vessel_style_lbl').lbl_req(), 'style_id', ['class' => $label_cls]), form_dropdown('style_id', $style_list, set_value('style_id', $item->style_id), $style_attr), ['inpt_grp_icon'=>'dot-circle-o']+ $column_attr);
                        $manufacturer_attr = ['id'=>'manufacturer_id', 'required'=>'required', 'class'=>'form-control select2'];
                        echo form_input_wrapper(form_label($this->lang->line('vessel_manufacturer_lbl').lbl_req(), 'manufacturer_id', ['class' => $label_cls]), form_dropdown('manufacturer_id', $manufacturer_list, set_value('manufacturer_id', $item->manufacturer_id), $manufacturer_attr), ['inpt_grp_icon'=>'dot-circle-o']+ $column_attr);
                    ?>
                    </div>
                    <div class="row">
                    <?php
                        $input_loa = ['name' => 'loa', 'type' => 'text', 'id' => 'loa', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('loa', $item->loa), 'required' => 'required', 'maxlength' => '4', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('vessel_loa_lbl').lbl_req(), 'loa', ['class' => $label_cls]), form_input($input_loa), ['inpt_grp_icon' => 'envelope']+$column_attr);
                        $drive_type_attr = ['id'=>'drive_type_id', 'required'=>'required', 'class'=>'form-control select2'];
                        echo form_input_wrapper(form_label($this->lang->line('vessel_drive_type_lbl').lbl_req(), 'drive_type_id', ['class' => $label_cls]), form_dropdown('drive_type_id', $drive_type_list, set_value('drive_type_id', $item->drive_type_id), $drive_type_attr), ['inpt_grp_icon'=>'dot-circle-o']+ $column_attr);
                        $input_no_of_drives = ['name' => 'no_of_drives', 'type' => 'text', 'id' => 'no_of_drives', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('no_of_drives', $item->no_of_drives), 'required' => 'required', 'maxlength' => '4', 'placeholder' => ''];
                        echo form_input_wrapper(form_label($this->lang->line('vessel_no_of_drives_lbl').lbl_req(), 'no_of_drives', ['class' => $label_cls]), form_input($input_no_of_drives), ['inpt_grp_icon' => 'envelope']+$column_attr);
                    ?>    
                    </div>
                    <h3 class="form-section">
                        <?php echo $this->lang->line('vessel_location_heading'); ?>
                    </h3>
                    <div class="row">    
                        <?php
                            echo form_radio_wrapper(form_label($this->lang->line('vessel_location_type_lbl').lbl_req(), 'location_type', ['class' => $label_cls]), 'location_type', $location_type_arr, set_value('location_type', $item->location_type), 'location-type', 1, 'col-md-12');
                        ?>
                    </div>    
                    <div class="row">  
                        <?php
                            $public_address_attr = ['id'=>'public_address_location', 'required'=>'required', 'class'=>'form-control select2'];
                            echo form_input_wrapper(form_label($this->lang->line('vessel_location_type_public_address_lbl').lbl_req(), 'location', ['class' => $label_cls]), form_dropdown('location[public_address]', $public_address_list, set_value('location', (isset($item->location->public_address) ? $item->location->public_address :'')), $public_address_attr), ['div_grp_cls'=>'form-group public-address-blk','inpt_grp_icon'=>'dot-circle-o']+['wrap_div' => 1, 'wrap_div_cls' => 'col-md-12']);
                        ?>
                    </div>    
                    <div class="manual-address-blk">
                        <div class="row">
                        <?php
                            $input_address = ['name' => 'location[address]', 'type' => 'text', 'id' => 'address', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('location[address]', (isset($item->location->address) ? $item->location->address :'')), 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                            echo form_input_wrapper(form_label($this->lang->line('vessel_address_lbl').lbl_req(), 'address', ['class' => $label_cls]), form_input($input_address), ['inpt_grp_icon' => 'crosshairs']+$column_attr1);
                            $input_zip_code = ['name' => 'location[zip_code]', 'type' => 'text', 'id' => 'zip_code', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('location[zip_code]', (isset($item->location->zip_code) ? $item->location->zip_code :'')), 'required' => 'required', 'maxlength' => '8', 'placeholder' => ''];
                            echo form_input_wrapper(form_label($this->lang->line('vessel_zip_code_lbl').lbl_req(), 'zip_code', ['class' => $label_cls]), form_input($input_zip_code), ['inpt_grp_icon' => 'crosshairs']+$column_attr1);

                        ?>

                        </div>
                        <div class="row">
                            <?php
                                $input_city = ['name' => 'location[city]', 'type' => 'text', 'id' => 'city', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('location[city]', (isset($item->location->city) ? $item->location->city :'')), 'readonly'=>true, 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                                echo form_input_wrapper(form_label($this->lang->line('vessel_city_lbl').lbl_req(), 'city', ['class' => $label_cls]), form_input($input_city), ['inpt_grp_icon' => 'crosshairs']+$column_attr1);

                                $input_state_name = ['name' => 'location[state_name]', 'type' => 'text', 'id' => 'state_name', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('location[state_name]', (isset($item->location->state_name) ? $item->location->state_name :'')), 'readonly'=>true, 'required' => 'required', 'maxlength' => '50', 'placeholder' => ''];
                                echo form_input_wrapper(form_label($this->lang->line('vessel_state_name_lbl').lbl_req(), 'state_name', ['class' => $label_cls]), form_input($input_state_name), ['inpt_grp_icon' => 'crosshairs']+$column_attr1);

                            ?>

                        </div>
                        <div class="row">
                            <?php
                                $input_state_prefix = ['name' => 'location[state_prefix]', 'type' => 'text', 'id' => 'state_prefix', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('location[state_prefix]', (isset($item->location->state_prefix) ? $item->location->state_prefix :'')), 'readonly'=>true, 'required' => 'required', 'maxlength' => '5', 'placeholder' => ''];
                                echo form_input_wrapper(form_label($this->lang->line('vessel_state_prefix_lbl').lbl_req(), 'state_prefix', ['class' => $label_cls]), form_input($input_state_prefix), ['inpt_grp_icon' => 'crosshairs']+$column_attr1);

                                $input_country_code = ['name' => 'location[country_code]', 'type' => 'text', 'id' => 'country_code', 'autocomplete' => 'off', 'class' => $input_cls, 'value' => set_value('location[country_code]',(isset($item->location->country_code) ? $item->location->country_code :'')), 'readonly'=>true, 'required' => 'required', 'maxlength' => '3', 'placeholder' => ''];
                                echo form_input_wrapper(form_label($this->lang->line('vessel_country_code_lbl').lbl_req(), 'country_code', ['class' => $label_cls]), form_input($input_country_code), ['inpt_grp_icon' => 'crosshairs']+$column_attr1);
                            ?>
                        </div>
                    </div>  
                    <h3 class="form-section">
                        <?php echo $this->lang->line('vessel_schedule_heading'); ?>
                    </h3>
                    <div class="row">    
                       <?php 
                             echo form_radio_wrapper(form_label($this->lang->line('vessel_schedule_type_lbl').lbl_req(), 'schedule_type', ['class' => $label_cls]), 'schedule_type', $schedule_type_arr, set_value('schedule_type', $item->schedule_type), 'schedule-type', 1, 'col-md-12');
                        ?> 
                    </div>     
                    <div class="row">    
                        <?php
                            $schedule_attr = ['id'=>'schedule_id', 'class'=>'form-control select2'];
                            echo form_input_wrapper(form_label($this->lang->line('vessel_schedule_lbl'), 'schedule_id', ['class' => $label_cls]), form_dropdown('schedule_id', add_selectbx_initial($schedule_map_list), set_value('schedule_id', $item->schedule_id), $schedule_attr), ['inpt_grp_icon'=>'dot-circle-o']+ $column_attr1);

                            $input_next_cleaning_date = ['name' => 'next_cleaning_date', 'type' => 'text', 'id' => 'next_cleaning_date', 'autocomplete' => 'off', 'class' => $input_cls.' datepicker-input', 'value' => set_value('next_cleaning_date', $item->next_cleaning_date), 'readonly'=>'true', 'placeholder' => ''];
                            echo form_input_wrapper(form_label($this->lang->line('vessel_next_cleaning_date_lbl'), 'next_cleaning_date', ['class' => $label_cls]), form_input($input_next_cleaning_date), ['inpt_grp_icon' => 'calendar']+$column_attr1);
                            $custom_cleaning_date = (in_array($item->schedule_type, [1,2])) ? $item->next_cleaning_date : '';
                            
                            echo form_hidden('custom_cleaning_date', $custom_cleaning_date);    

                        ?>
                    </div> 
                    <h3 class="form-section">
                        <?php echo $this->lang->line('vessel_price_heading'); ?>
                    </h3>
                     <div class="row">
                   
                    <?php
                        foreach($price_type_list as $k=> $v) {
                            if(!isset($price_type_map_list[$k])) { continue; }
                            $id = str_replace(' ', '_', strtolower($v->type_name)).'_id';
                            $cls= ($v->display_type == 1) ? 'select2-multiple' : 'select2';
                            $custom_cls = (in_array($id, array('old_prices_id', 'new_prices_id'))) ? ' old_new_price ' : '';
                            $form = ($v->display_type == 1) ? 'form_multiselect' : 'form_dropdown';
                            $final_price_list = ($v->display_type == 1) ? $price_type_map_list[$k] : add_selectbx_initial($price_type_map_list[$k]);
                            $price_attr = ['id'=>$id, 'class'=>'form-control '.$cls.$custom_cls, 'required'=>'required'];
                            if($v->display_type == 1) { $price_attr['multiple'] = 'multiple';}
                            $name = ($v->display_type == 1) ? 'price_id['.$k.'][]' : 'price_id['.$k.']';
                            $value = (isset($item->vessel_prices[$k])) ? $item->vessel_prices[$k] : '' ;
                                
                            echo form_input_wrapper(form_label($v->type_name.lbl_req(), $id, ['class' => $label_cls]),$form($name, $final_price_list , set_value($name, $value) , $price_attr), ['inpt_grp_icon'=>'dot-circle-o']+['wrap_div' => 1, 'wrap_div_cls' => 'col-md-12']);        

                            echo form_hidden('price_type['.$k.']', $k);              
                        }
                    ?>
                    </div>
                    <div class="row">
                     <?php
                        echo form_radio_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), 'published', $boolean_arr, set_value('published', $item->published), '', 1, 'col-md-12');
                     ?>   
                    </div>
                    <?php
                        echo form_actions_wrapper(form_button($btndata) . display_form_links($list_link, $this->lang->line('cancel_btn'), '', 'default'));
                        echo form_close();
                    ?>
                </div>
                <!-- END LOGIN FORM -->
            </div>
        </div>
    </div>
</div>

<?php 
$schedule_array = [];
foreach($schedule_list as $v){
    $schedule_array[$v->id] = $v->range_to;
}
$schedule_arr_encode = json_encode($schedule_array,true);
$final_schedule_arr = preg_replace('/"([^"]+)"\s*:\s*/', '$1:', $schedule_arr_encode);
?>
<script type="text/javascript" language="javascript">
    var schedule_arr = {};
    schedule_arr = <?php echo $final_schedule_arr;?>;  
</script>
