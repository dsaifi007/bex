<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('lbl_req')) :
    function lbl_req($icon = '*') {
        return '<span class="required"> '.$icon.' </span>';
    }
endif;

if (!function_exists('form_input_wrapper')) :

    function form_input_wrapper($label = '', $input, $cnfg1 = '') {
        $html = '';
        $cnfg = [
            'wrap_div'=>0,
            'wrap_div_cls'=>'',
            'div_grp_cls' => 'form-group',
            'inpt_grp' => 1,
            'inpt_grp_lft' => 1,
            'inpt_grp_cls' => 'input-group',
            'inpt_grp_spn_cls' => 'input-group-addon',
            'inpt_grp_icon' => 'user',
            'inpt_div' => 0,
            'inpt_div_cls' => 'col-sm-12',
            'show_tooltip' => 0,
            'tooltip_title' => '',
            'tooltip_icon' => 'question'
        ];

        $cnfg = OverwriteArray($cnfg, $cnfg1);

        $html .= '<div class="' . $cnfg['div_grp_cls'] . '">';
        $html .= ($label != '') ? $label : '';
        $input = ($cnfg['show_tooltip']) ? '<i class="fa fa-' . $cnfg['tooltip_icon'] . ' tooltips" data-original-title="' . $cnfg['tooltip_title'] . '" data-container="body"></i>' . $input : $input;
        if ($cnfg['inpt_grp']) :
            $spn = '<span class="' . $cnfg['inpt_grp_spn_cls'] . '"><i class="fa fa-' . $cnfg['inpt_grp_icon'] . '"></i></span>';
            $input = ($cnfg['show_tooltip'] || $cnfg['inpt_grp_lft']) ? $spn . $input : $input . $spn;
            $input = '<div class="' . $cnfg['inpt_grp_cls'] . '">' . $input . '</div>';
        endif;
        $html .= ($cnfg['inpt_div']) ? '<div class="' . $cnfg['inpt_div_cls'] . '"></div>' : $input;
        $html .= '</div>';
        if($cnfg['wrap_div']) :
            $html = '<div class="'.$cnfg['wrap_div_cls'].'">'.$html.'</div>';
        endif;
        return $html;
    }

endif;

if (!function_exists('form_actions_wrapper')) :

    function form_actions_wrapper($actions, $cnfg1 = '') {
        $html = '';
        $cnfg = [
            'div_actns_cls' => 'form-actions',
            'left' => 1
        ];

        $cnfg = OverwriteArray($cnfg, $cnfg1);
        $position = ($cnfg['left']) ? ' left' : ' right';
        $html .= '<div class="' . $cnfg['div_actns_cls'] . $position . '">';
        $html .= $actions;
        $html .= '</div>';

        return $html;
    }

endif;

if (!function_exists('form_checkbox_wrapper')) :

    function form_checkbox_wrapper($label, $name, $items, $checked_val = '', $checkbox_class = '', $toggle_check_frmt = 0, $label_cls = '',$checkbox_option_class='', $required=1) {
        $grp_cls = ($toggle_check_frmt) ? 'checkbox-toggle' : 'checkbox-bird';
        $grp_cls_extra = ($checkbox_class != '') ? ' ' . $checkbox_class . '-grp-blk' : '';
        $nameID = str_replace(['[', ']'], '', $name);
        $html = '<div class="' . $grp_cls . $grp_cls_extra . '">';
         $html .= $label;
        foreach ($items as $bk => $bv) :
			$html .= '<div class=" '.$checkbox_option_class.'">';
            $chkbxID = $nameID . $bk;
            $checked = ($checked_val == $bk)? true:false;
            $chk_data = ['name'=>$name, 'id'=>$chkbxID, 'value'=>$bk, 'checked'=>$checked, 'class'=>$checkbox_class];
            if($required == 1) { $chk_data['required'] = 'required';}
            $html .= form_checkbox($chk_data);
            $html .= form_label($bv, $chkbxID, ['class' => $label_cls]);
			$html .= '</div>';
        endforeach;
        $html .= '</div>';

        return $html;
    }


endif;

if (!function_exists('form_radio_wrapper')) :

    function form_radio_wrapper($label, $name, $items, $checked_val = '', $radio_class = '', $wrap=0, $wrap_cls='col-md-4', $radio_option_class='', $required=1) {
        $html = '<div class="form-group form-md-radios blk-'.$radio_class.'">';
        $radio_label_attr = ['class' => $radio_class];
        $html .= $label;
        $html .= '<div class="md-radio-inline">';
        foreach ($items as $bk => $bv) :
            $html .= '<div class="md-radio '.$radio_option_class.'">';
            $radioID = $name . $bk;
            $radioChk = ($checked_val == $bk) ? TRUE : FALSE;
            $radio_data = ['name' => $name, 'id' => $radioID, 'value' => $bk, 'checked' => $radioChk, 'autocomplete' => 'off', 'class' => 'md-radiobtn ' . $radio_class];
            if($required == 1) { $radio_data['required'] = 'required';}
            $html .= form_radio($radio_data);
            $html .= form_label('<span></span><span class="check"></span><span class="box"></span> ' . $bv, $radioID, $radio_label_attr);
            $html .= '</div>';
        endforeach;
        $html .= '</div>';
        $html .= '</div>';
        if($wrap) : $html = '<div class="'.$wrap_cls.'">'.$html.'</div>'; endif;
        return $html;
    }


endif;

if(!function_exists('form_input_upload')) {
    function form_input_upload($label, $file_input, $file_instructions='', $is_image=0, $img_html='') {
        $html = '<div class="form-group">';
            $html .= $label;
            $html .= '<div class="input-group">';
            $html .= '<div class="fileinput fileinput-new" data-provides="fileinput">';
                $html .= ($is_image)? '<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">'.$img_html.'</div>':'';
                $html .= '<div>';
                    $html .= '<span class="btn red btn-outline btn-file">';
                        $html .= '<span class="fileinput-new">';
                            $html .= ($is_image)? ' Select Image ':' Select File ';
                        $html .= '</span>';
                        $html .= '<span class="fileinput-exists"> Change </span>';
                        $html .= form_upload($file_input);
                    $html .= '</span> ';
                    $html .= '<a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>';
                $html .= '</div>';
            $html .= '</div>';

            if($file_instructions !='') {
                $html .= '<div class="clearfix margin-top-10">';
                $html .= '<span class="label label-warning">INSTRUCTION! </span>';
                $html .= '<span>' . $file_instructions . '</span>';
                $html .= '</div>';
            }

            if($is_image) {
                $html .= '<div class="clearfix margin-top-10">';
                $html .= '<span class="label label-success">NOTE!</span>';
                $html .= ' Image preview only works in IE10+, FF3.6+, Safari6.0+, Chrome6.0+ and Opera11.1+. In older browsers the filename is shown instead.';
                $html .= '</div>';
            }
        $html .= '</div>';
        $html .= '</div>';

    return $html;
    }
}

if(!function_exists('form_question')) {
    function form_question($input_cls, $label_cls, $tips='', $key='', $frontend_options='', $field_type= '', $question='', $required=1, $image_dir_path, $parent_id =0) {
        $tips_label = ($tips) ? '<span data-toggle="collapse" data-target="#tips'.$key.'"> (Don’t know? click here for some tips) </span>' : '';

        if($field_type == 1) {    // dropdown
            //$name = ($parent_id == 0) ? 'options['.$key.']' : 'options['.$parent_id.']['.$key.']';
            $name = 'options['.$key.']';
            $options_attr = ['id'=>'options_'.$key, 'required'=>'required', 'class'=>'form-control select2'];
            if($required == 1) { $options_attr['required'] = 'required';}
            echo form_input_wrapper(form_label($question.$tips_label.lbl_req(), 'question', ['class' => $label_cls]), form_dropdown( $name, add_selectbx_initial($frontend_options), '', $options_attr), ['inpt_grp_icon'=>'dot-circle-o']);

        }else if($field_type == 2) {  // textbox
            //$name = ($parent_id == 0) ? 'options['.$key.']' : 'options['.$parent_id.']['.$key.']';   
            $name = 'options['.$key.']';
            $options_attr = ['name' => $name, 'type' => 'text', 'id' => 'options_'.$key, 'autocomplete' => 'off', 'class' => $input_cls, 'maxlength' => '50'];
            if($required == 1) { $options_attr['required'] = 'required';}
            echo form_input_wrapper(form_label($question.$tips_label.lbl_req(), 'question', ['class' => $label_cls]), form_input($options_attr), ['inpt_grp_icon' => 'user']);

        }else if($field_type == 3) {  // Radio
            //$name = ($parent_id == 0) ? 'options['.$key.']' : 'options['.$parent_id.']['.$key.']';
            $name = 'options['.$key.']';
            echo form_radio_wrapper(form_label($question.$tips_label.lbl_req(), 'options_'.$key, ['class' => $label_cls]), $name, $frontend_options, '', 'options', 0, 'col-md-12', 'col-md-6', $required);

        }else if($field_type == 4) {  // Image
            $options = [];
            foreach($frontend_options as $k1=>$v1){
                $image_options[$k1] = '<img width="100" height="100" src="'.$image_dir_path.$v1.'"/>';
            }  
            //$name = ($parent_id == 0) ? 'options['.$key.']' : 'options['.$parent_id.']['.$key.']';   
            $name = 'options['.$key.']';                  
            $image = preg_filter('/^/', $image_dir_path, $frontend_options);
            echo form_radio_wrapper(form_label($question.$tips_label.lbl_req(), 'options_'.$key, ['class' => $label_cls]), $name, $image_options, '', 'options', 0, 'col-md-12', 'col-md-6', $required);

        }else if($field_type == 5) {  // Checkbox
            //$name = ($parent_id == 0) ? 'options['.$key.'][]' : 'options['.$parent_id.']['.$key.'][]';
            $name = 'options['.$key.'][]';
            echo form_checkbox_wrapper(form_label($question.$tips_label.lbl_req(), 'options_'.$key, ['class' => $label_cls]), $name, $frontend_options, '', 'options', 0, '', 'col-md-6', $required);
        }
        echo '<div class="clearfix"></div>';     
    }
}