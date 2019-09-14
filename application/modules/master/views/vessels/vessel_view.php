<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$input_arr = ['inpt_grp' => 0, 'wrap_div' => 1, 'inpt_div' => 1, 'inpt_div_cls' => 'semibold form-view-mode-input form-view-mode-input-md'];
$input_wrap_col4 = $input_arr + ['wrap_div_cls' => 'col-md-4'];
$input_wrap_col6 = $input_arr + ['wrap_div_cls' => 'col-md-6'];
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link)); ?>
            <div class="portlet-body form">
                <h3 class="form-section">
                    <?php echo $this->lang->line('vessel_basic_info_heading'); ?>
                </h3>
                <div class="row">
                <?php
                    echo form_input_wrapper(form_label($this->lang->line('vessel_name_lbl').lbl_req(), 'vessel_name', ['class' => $label_cls]), $item->vessel_name, $input_wrap_col4);
                    echo form_input_wrapper(form_label($this->lang->line('vessel_user_type_lbl').lbl_req(), 'user_id', ['class' => $label_cls]), $users_list[$item->user_id], $input_wrap_col4);
                    echo form_input_wrapper(form_label($this->lang->line('vessel_type_lbl').lbl_req(), 'away_team_id', ['class' => $label_cls]), $vessel_type_list[$item->vessel_type_id], $input_wrap_col4);
                ?>
                </div>
                <div class="row">
                <?php
                    echo form_input_wrapper(form_label($this->lang->line('vessel_style_lbl').lbl_req(), 'style_id', ['class' => $label_cls]), $style_list[$item->style_id], $input_wrap_col4);
                    echo form_input_wrapper(form_label($this->lang->line('vessel_manufacturer_lbl').lbl_req(), 'manufacturer_id', ['class' => $label_cls]), $manufacturer_list[$item->manufacturer_id], $input_wrap_col4);
                    echo form_input_wrapper(form_label($this->lang->line('vessel_drive_type_lbl').lbl_req(), 'drive_type_id', ['class' => $label_cls]), $drive_type_list[$item->drive_type_id], $input_wrap_col4);
                ?>
                </div>
                <div class="row">
                <?php
                    echo form_input_wrapper(form_label($this->lang->line('vessel_loa_lbl').lbl_req(), 'loa', ['class' => $label_cls]), $item->loa, $input_wrap_col4);
                    echo form_input_wrapper(form_label($this->lang->line('vessel_no_of_drives_lbl').lbl_req(), 'no_of_drives', ['class' => $label_cls]), $item->no_of_drives, $input_wrap_col4);
                     echo form_input_wrapper(form_label($this->lang->line('active_label').lbl_req(), 'published', ['class' => $label_cls]), $boolean_arr[$item->published], $input_wrap_col4);
                ?>
                </div>
                <h3 class="form-section">
                    <?php echo $this->lang->line('vessel_location_heading'); ?>
                </h3>
                <div class="row">
                <?php
                    echo form_input_wrapper(form_label($this->lang->line('vessel_location_type_lbl').lbl_req(), 'location_type', ['class' => $label_cls]), $location_type_arr[$item->location_type], $input_wrap_col6);

                    echo form_input_wrapper(form_label($this->lang->line('vessel_location_lbl').lbl_req(), 'location_type', ['class' => $label_cls]), $location, $input_wrap_col6);
                ?>
                </div>
                <h3 class="form-section">
                    <?php echo $this->lang->line('vessel_schedule_heading'); ?>
                </h3>
                <div class="row">
                <?php
                    echo form_input_wrapper(form_label($this->lang->line('vessel_schedule_type_lbl').lbl_req(), 'schedule_type', ['class' => $label_cls]), $schedule_type_arr[$item->schedule_type], $input_wrap_col4);

                    echo form_input_wrapper(form_label($this->lang->line('vessel_schedule_lbl').lbl_req(), 'schedule_id', ['class' => $label_cls]), (($item->schedule_id) ? $schedule_list[$item->schedule_id]->schedule_name : ''), $input_wrap_col4);

                    echo form_input_wrapper(form_label($this->lang->line('vessel_next_cleaning_date_lbl').lbl_req(), 'next_cleaning_date', ['class' => $label_cls]),  formatDateTime($item->next_cleaning_date, $this->display_date_frmt), $input_wrap_col4);
                ?>
                </div>
                <h3 class="form-section">
                    <?php echo $this->lang->line('vessel_price_heading'); ?>
                </h3>
                <div class="row">
                <?php
                    $value= '';
                    foreach($price_type_list as $k=> $v) {
                        if(!isset($price_type_map_list[$k])) { continue; }
                        $id = str_replace(' ', '_', strtolower($v->type_name)).'_id';
                        if(isset($item->vessel_prices[$k])){
                           if($v->display_type == 1){
                                if(count($item->vessel_prices[$k]) > 0){
                                    foreach($item->vessel_prices[$k] as $k1=>$v1){
                                        $multi_price[] = $price_list[$item->vessel_prices[$k][$k1]]->price_label .' - '. $currency_codes_list[$price_type_list[$k]->currency_code]['code'].nbs().$price_list[$item->vessel_prices[$k][$k1]]->price ;
                                    }
                                    $value = implode(' <br>',$multi_price);
                                }
                            }else {
                                $value = $price_list[$item->vessel_prices[$k][0]]->price_label .' - '.$currency_codes_list[$price_type_list[$k]->currency_code]['code'].nbs(). $price_list[$item->vessel_prices[$k][0]]->price;
                            } 
                        }
                        
                        echo form_input_wrapper(form_label($v->type_name .lbl_req(), $id, ['class' => $label_cls]), $value , $input_wrap_col4);        
     
                    }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>