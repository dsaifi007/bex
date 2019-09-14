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
        $html .= ($cnfg['inpt_div']) ? '<div class="' . $cnfg['inpt_div_cls'] . '">'.$input.'</div>' : $input;
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
            'left' => 1,
            'wrap_div'=>0,
            'wrap_div_cls'=>''
        ];

        $cnfg = OverwriteArray($cnfg, $cnfg1);
        $position = ($cnfg['left']) ? ' left' : ' right';
        $html .= '<div class="' . $cnfg['div_actns_cls'] . $position . '">';
        $html .= $actions;
        $html .= '</div>';
        if($cnfg['wrap_div']) :
            $html = '<div class="'.$cnfg['wrap_div_cls'].'">'.$html.'</div>';
        endif;
        return $html;
    }

endif;

if (!function_exists('form_radio_wrapper')) :

    function form_radio_wrapper($label, $name, $items, $checked_val = '', $radio_class = '', $wrap=0, $wrap_cls='col-md-4') {
        $html = '<div class="form-group form-md-radios blk-'.$radio_class.'">';
        $radio_label_attr = ['class' => $radio_class];
        $html .= $label;
        $html .= '<div class="md-radio-inline">';
        foreach ($items as $bk => $bv) :
            $html .= '<div class="md-radio">';
            $radioID = $name . $bk;
            $radioChk = ($checked_val == $bk) ? TRUE : FALSE;
            $radio_data = ['name' => $name, 'id' => $radioID, 'value' => $bk, 'checked' => $radioChk, 'required'=> 'required', 'autocomplete' => 'off', 'class' => 'md-radiobtn ' . $radio_class];
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
    function form_input_upload($label, $file_input, $file_instructions='', $is_image=0, $img_html='', $image_instruction=1, $wrap_cls='') {
        $html = '<div class="form-group '.$wrap_cls.'">';
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

            if($image_instruction) {
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