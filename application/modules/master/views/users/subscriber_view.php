<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;

$label_cls = 'control-label';
$input_cls = 'form-control';
$input_arr = ['inpt_grp' => 0, 'wrap_div' => 1, 'inpt_div' => 1, 'inpt_div_cls' => 'semibold form-view-mode-input form-view-mode-input-md'];
$input_wrap_col4 = $input_arr + ['wrap_div_cls' => 'col-md-4'];
$input_wrap_col6 = $input_arr + ['wrap_div_cls' => 'col-md-6'];
$input_wrap_col12 = $input_arr + ['wrap_div_cls' => 'col-md-12'];
$supported_image = array(
    'gif',
    'jpg',
    'jpeg',
    'png',
    'JPG',
    'JPEG'
);

?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_head_links($list_link)); ?>
            <div class="portlet-body form">
                <h3 class="form-section">
                    <?php echo $this->lang->line('subscriber_info_heading'); ?>
                </h3>
                <div class="row">
                <?php
                    echo form_input_wrapper(form_label($this->lang->line('user_name_lbl').lbl_req(), 'name', ['class' => $label_cls]), $item->name, $input_wrap_col6);
                    echo form_input_wrapper(form_label($this->lang->line('user_email_lbl').lbl_req(), 'email', ['class' => $label_cls]), $item->email, $input_wrap_col6);
                ?>
                </div>
                <h3 class="form-section">
                    <?php echo $this->lang->line('subscriber_form_info_heading'); ?>
                </h3>
                <?php
                    $form_data = json_decode($item->form_data);
                    $arr = [];
                    $list = [];
                    if($form_data){
                        foreach($form_data->options as $k=>$v){
                            if(is_object($v)){
                                foreach($v as $k1=>$v1 ){
                                    $arr['question'] = $question_list[$k1]->question;
                                    $arr['options'] = $options_list[$v1]->frontend_option;
                                    $arr['field_type'] = $question_list[$k1]->field_type;
                                    $list[$k1] = $arr;
                                } 
                            }else if(is_array($v)){
                                foreach($v as $k2=>$v2 ){
                                    $multi_option[]= $options_list[$v2]->frontend_option;
                                }
                                $arr['question'] = $question_list[$k]->question;
                                $arr['options'] = implode('<br>', $multi_option);
                                $arr['field_type'] = $question_list[$k]->field_type;
                                $list[$k] = $arr;
                            }else{
                                $arr['question'] = $question_list[$k]->question;
                                $arr['options'] = $options_list[$v]->frontend_option;
                                $arr['field_type'] = $question_list[$k]->field_type;
                                $list[$k] = $arr;
                            } 
                        }
                    }

                    echo '<div class="row">';
                    if(count($list) > 0){
                        foreach($list as $k=>$v){
                            echo form_input_wrapper(form_label((isset($v['question']) ? $v['question'] : '').lbl_req(), 'options', ['class' => $label_cls]), ((in_array(strtolower(pathinfo($v['options'], PATHINFO_EXTENSION)), $supported_image)) ? '<img src="'.$image_dir_path.$v['options'].'" width="100" height="100">' : $v['options']), $input_wrap_col12);
                        }    
                    }
                    // Toxic question 
                    echo form_input_wrapper(form_label($this->lang->line('toxic_question_label').lbl_req(), 'options', ['class' => $label_cls]), $toxic_backend[$item->toxic], $input_wrap_col12);

                    echo '</div>';    
                ?>
            </div>
        </div>
    </div>
</div>