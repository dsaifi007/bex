<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Template Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Harshit Gupta
 * @link		http://codeigniter.com/user_guide/helpers/utility_helper.html
 */
// ------------------------------------------------------------------------


if (!function_exists('display_message_info')) :

    function display_message_info($message = []) {
        $html = '';
        foreach ($message as $k => $v) :
            if (!$v) : continue;
            endif;
            $alertCls = ($k == '1') ? 'alert-success' : 'alert-danger';
            $html .= '<div class="alert ' . $alertCls . '">';
            $html .= '<button class="close" data-close="alert"></button>';
            $html .= '<span class="message">' . $v . '</span>';
            $html .= '</div>';
        endforeach;
        return $html;
    }

endif;

if(!function_exists('display_portlet_title')) :
    function display_portlet_title($title='', $add_btn='', $icon_class='settings') {
        $html = '';
        $html .= '<div class="portlet-title">';
        $html .= '<div class="caption font-dark">';
        $html .= '<i class="font-green icon-'.$icon_class.'"></i>';
        $html .= '<span class="font-green caption-subject bold uppercase">'.$title.'</span>';
        $html .= '</div>';
        $html .= '<div class="tools">';
        $html .= ($add_btn)? '<div>'.$add_btn.'</div>':'';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
endif;


if (!function_exists('display_message_notes')) :

    function display_message_notes($message, $note_cls = 'success', $heading = '', $heading_level='4') {
        $html = '<div class="note note-'.$note_cls.'">';
        $html .= ($heading)? heading($heading, $heading_level, ['class'=>'block']):'';
        $html .= '<p>'.$message.'</p>';
        $html .= '</div>';
        return $html;
    }

endif;

if (!function_exists('display_status_btn')) :

    function display_status_btn($status = 0, $label_arr = [0 => 'Inactive', 1 => 'Active']) {
        $btncls = [0 => 'danger', 1 => 'success', 2 => 'info'];
        return '<span class="label label-sm label-' . $btncls[$status] . '">' . $label_arr[$status] . '</span>';
    }

endif;

if (!function_exists('display_form_links')) :

    function display_form_links($link, $label, $icon_type = 'plus', $btncls_extra = 'sbold green') {
        //$btncls_extra = ($btncls_extra)? $btncls_extra:'sbold green';
        $icon_blk = ($icon_type) ? ' <i class="fa fa-' . $icon_type . '"></i>' : '';
        return anchor(site_url($link), $label . $icon_blk, ['class' => 'btn ' . $btncls_extra, 'role' => 'button']);
    }

endif;

if (!function_exists('display_form_head_links')) :
    function display_form_head_links($list_link='', $add_link='', $cnfg1 = '') {
        $html = [];
        $cnfg = [
            'lstlnk_title' => 'Back to List',
            'lstlnk_icon' => 'arrow-up',
            'lstlnk_btncls' => 'sbold red-pink',
            'adlnk_title' => 'Add New',
            'adlnk_icon' => 'plus',
            'adlnk_btncls'=> 'sbold green',
            'btn_spaces'=>1
        ];
        $cnfg = OverwriteArray($cnfg, $cnfg1);
        if($list_link != '') { $html[] = display_form_links($list_link, $cnfg['lstlnk_title'], $cnfg['lstlnk_icon'], $cnfg['lstlnk_btncls']); }
        if($add_link != '') { $html[] = display_form_links($add_link, $cnfg['adlnk_title'], $cnfg['adlnk_icon'], $cnfg['adlnk_btncls']); }
        return implode(nbs($cnfg['btn_spaces']), $html);
    }
endif;

if (!function_exists('display_to_do_list')) :

    function display_to_do_list($list, $cnfg1 = '') {
        $html = '';
        $cnfg = [
            'blk_cls' => 'under-table',
            'blk_col_cls' => '',
            'li_col_cls' => '',
            'lettercase' => ''
            //'lettercase' => 'uppercase'
        ];
        $cnfg = OverwriteArray($cnfg, $cnfg1);
        $colors = ['yellow-gold', 'red-pink', 'blue', 'green-dark', 'yellow-crusta', 'green-jungle', 'yellow', 'red-haze', 'green-meadow', 'blue-madison'];
        shuffle($colors);
        $inc = 0;

        $html .= '<div class="mt-element-list ' . $cnfg['blk_cls'] . '">';
        $html .= '<div class="mt-list-container list-todo ' . $cnfg['blk_col_cls'] . '">';
        $html .= '<ul>';
        foreach ($list as $k => $v) :
            $html .= '<li class="mt-list-item ' . $cnfg['li_col_cls'] . '">';
            $html .= '<div class="list-todo-item ' . $colors[$inc] . '">';
            $html .= '<a class="list-toggle-container font-white">';
            $html .= '<div class="list-toggle done ' . $cnfg['lettercase'] . '">';
            $html .= '<div class="list-toggle-title bold">' . $v . '</div>';
            //$html .= ($v['count'])? '<div class="badge badge-default pull-right bold">'.$v['count'].'</div>':'';
            $html .= '</div>';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '</li>';
            if ($inc == (count($colors) - 1)) : $inc = -1;
            endif;
            $inc++;
        endforeach;
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

endif;

if (!function_exists('display_label_list')) {

    function display_label_list($list, $shuffle = 1, $cnfg1 = '')
    {
        $html = [];
        $cnfg = [
            'ul_cls' => '',
            'lbl_cls' => 'label-sm',
            'lettercase' => 'uppercase'
        ];
        $cnfg = OverwriteArray($cnfg, $cnfg1);
        $colors = ['yellow-gold', 'red-pink', 'blue', 'green-dark', 'yellow-crusta', 'green-jungle', 'yellow', 'red-haze', 'green-meadow', 'blue-madison'];
        if ($shuffle) {
            shuffle($colors);
        }
        $inc = 0;
        foreach ($list as $k => $v) {
            $html[] = '<span class="label ' . $cnfg['lbl_cls'] . ' bg-' . $colors[$inc] . ' ' . $cnfg['lettercase'] . '">' . $v . '</span>';
            if ($inc == (count($colors) - 1)) {
                $inc = -1;
            }
            $inc++;
        }
        return ul($html, ['class' => 'label_list ' . $cnfg['ul_cls']]);
    }

}

if (!function_exists('display_sidebar_navigation')) {

    function display_sidebar_navigation($items, $app_name, $access_levels, $usergroups, $all_items = [])
    {
        $current_url = current_url();
        $html = '';
        if (count($all_items) < 1) {
            $all_items = $items;
            $items = $items[0];
        }
        if (count($items) > 0) {
            foreach ($items as $k => $v) {
                if ((!array_key_exists($v->acl_level_id, $access_levels)) || count(array_intersect($access_levels[$v->acl_level_id]->user_groups, $usergroups)) < 1) {
                    continue;
                }
                if (count($v->user_groups) > 0) {
                    if (count(array_intersect($v->user_groups, $usergroups)) < 1) {
                        continue;
                    }
                }
                $parent = $arrow = $open = $active = $selected = $href = $toggle = '';
                if ($v->menu_url != '') {
                    $href = ($v->item_type == 2) ? $v->menu_url : $app_name . '/' . $v->menu_url;
                    if (substr($current_url, -strlen($v->menu_url)) === $v->menu_url || (strpos($current_url, $v->menu_url . '/add') !== false || strpos($current_url, $v->menu_url . '/edit') !== false || strpos($current_url, $v->menu_url . '/view') !== false)) {
                        $selected = '<span class="selected"></span>';
                        $open = ' open';
                        $active = ' active open';
                    }

                }
                if (isset($all_items[$v->id])) {
                    $parent = 'parent-item';
                    $toggle = ' nav-toggle';
                    $arrow = '<span class="arrow' . $open . '"></span>';
                }
                $icon = ($v->menu_icon != '') ? '<i class="icon-' . $v->menu_icon . '"></i>' : '';
                if ($v->item_type == 1) {
                    $html .= '<li class="heading">';
                    $html .= heading($v->menu_title, 3, array('class' => 'uppercase'));
                } else {
                    $html .= '<li class="nav-item ' . $parent . $active . '">';
                    $link_text = $icon . '<span class="title">' . $v->menu_title . '</span>' . $selected . $arrow;
                    $target = ($v->browser_nav) ? 'blank' : 'self';
                    $html .= ($href) ? anchor($href, $link_text, ['class' => 'nav-link' . $toggle, 'target' => '_' . $target]) : '<a href="javascript:;" class="nav-link' . $toggle . '">' . $link_text . '</a>';
                    if (isset($all_items[$v->id])) {
                        $html .= '<ul class="sub-menu">';
                        $html .= display_sidebar_navigation($all_items[$v->id], $app_name, $access_levels, $usergroups, $all_items);
                        $html .= '</ul>';
                    }
                }
                $html .= '</li>';
            }
        }
        return $html;
    }


}

if(!function_exists('form_tabs_nav_layout')) {
    function form_tabs_nav_layout($items, $extra_ul_cls = '', $extra_li_cls='') {
        $html = '';
        if(count($items) > 0) {
            $inc = 0;
            $html .= '<ul class="nav nav-tabs'.$extra_ul_cls.'">';
            foreach($items as $k => $v) {
                $active_cls = ($inc == 0)? 'active ':'';
                $html .= '<li class="'.$active_cls.$extra_li_cls.'"><a id="'.$k.'_lnk" href="#'.$k.'" data-toggle="tab">'.$v.'</a></li>';
                $inc++;
            }
            $html .= '</ul>';
        }

        return $html;
    }
}

if(!function_exists('display_view_mode_modal_block')) {
    function display_view_mode_modal_block($modal_id='responsive-view-mode', $width='760') {
        $html = '<div id="'.$modal_id.'" class="modal container fade" tabindex="-1" data-width="'.$width.'">';
            $html .= '<div class="modal-header">';
                $html .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>';
            $html .= '</div>';
            $html .= '<div class="modal-body"> ';
            $html .= '</div>';
        $html .= '</div>';
    return $html;
    }
}