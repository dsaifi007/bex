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

if (!function_exists('form_radio_wrapper')) :

    function form_radio_wrapper($label, $name, $items, $checked_val = '', $radio_class = '', $wrap=0, $wrap_cls='col-md-4') {
        //$btncls = [0=>'danger', 1=>'success', 2=>'info', 3=>'primary'];
        $html = '<div class="form-group blk-'.$radio_class.'">';
        $html .= $label;
            $html .= '<div class="btn-group" data-toggle="buttons">';
                foreach ($items as $bk => $bv) : $active_cls = '';
                    $radioID = $name . $bk;
                    //$radio_label_attr['class'] .= ' btn-'.$btncls[$bk];
                    if($checked_val == $bk) :
                        $radioChk = TRUE; $active_cls = 'active ';
                    else : 
                        $radioChk = FALSE;
                    endif;
                    
                    $radio_data = ['name' => $name, 'id' => $radioID, 'value' => $bk, 'checked' => $radioChk, 'required'=> 'required', 'autocomplete' => 'off', 'class' => 'md-radiobtn ' . $radio_class];
                    $html .= form_label(form_radio($radio_data). $bv, $radioID, ['class' => 'btn btn-default '.$active_cls.$radio_class]);
                endforeach;
            $html .= '</div>';
        $html .= '</div>';
        
        if($wrap) : $html = '<div class="'.$wrap_cls.'">'.$html.'</div>'; endif; 
        return $html;
    }


endif;

if (!function_exists('form_checkbox_wrapper')) :

    function form_checkbox_wrapper($label, $name, $items, $checked_val = '', $checkbox_class = '', $toggle_check_frmt = 0, $label_cls = '') {
        $grp_cls = ($toggle_check_frmt) ? 'checkbox-toggle' : 'checkbox-bird';
        $grp_cls_extra = ($checkbox_class != '') ? ' ' . $checkbox_class . '-grp-blk' : '';
        $nameID = str_replace(['[', ']'], '', $name);
        $html = '<div class="' . $grp_cls . $grp_cls_extra . '">';
        foreach ($items as $bk => $bv) :
            $chkbxID = $nameID . $bk;
            $checked = ($checked_val == $bk)? true:false;
            $chk_data = ['name'=>$name, 'id'=>$chkbxID, 'value'=>$bk, 'checked'=>$checked, 'class'=>$checkbox_class];
            $html .= form_checkbox($chk_data);
            $html .= form_label($bv, $chkbxID, ['class' => $label_cls]);
        endforeach;
        $html .= '</div>';

        return $html;
    }


endif;