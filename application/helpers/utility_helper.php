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

define('SKEY', '^{%f5NFC_7pSaj^Q');
define('enc_IV', openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-ECB')));

/**
 * CodeIgniter UTILITY Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Harshit Gupta
 * @link		http://codeigniter.com/user_guide/helpers/utility_helper.html
 */
// ------------------------------------------------------------------------

if (!function_exists('media_url')) {

    function media_url($config_item = 'theme_path') {
        static $ci = NULL;
        if($ci === NULL) { $ci = &get_instance(); }
        return base_url() . $ci->config->item($config_item);
    }

}

if (!function_exists('media_path')) {

    function media_path($config_item = 'theme_path') {
        static $ci = NULL;
        if($ci === NULL) { $ci = &get_instance(); }
        return FCPATH . $ci->config->item($config_item);
    }

}

if (!function_exists('OverwriteArray')) {

    function OverwriteArray($arr1, $arr2)
    {
        if (is_array($arr2)) {
            array_walk($arr1, function (&$n, $k, $mixed) {
                $n = (isset($mixed[$k])) ? $mixed[$k] : $n;
            }, $arr2);
        }
        return $arr1;
    }

}

if (!function_exists('setAppURL')) {

    function setAppURL($url)
    {
        static $ci = NULL;
        if ($ci === NULL) {
            $ci = &get_instance();
        }
        return ($ci->config->item('current_app') == $ci->config->item('front_app'))? $url:$ci->config->item('current_app') . '/' . $url;
    }

}

if (!function_exists('email_subject_frmt')) {

    function email_subject_frmt($subject = [], $seprator = ' | ')
    {
        return implode($seprator, $subject);
    }

}

if(!function_exists('encodeData')) {
    function encodeData($data)
    {
        static $ci = NULL;
        if ($ci === NULL) {
            $ci = &get_instance();
        }
        return $ci->encrypt->encodeData($data);
    }
}

if(!function_exists('decodeData')) {
    function decodeData($data)
    {
        static $ci = NULL;
        if ($ci === NULL) {
            $ci = &get_instance();
        }
        return $ci->encrypt->decodeData($data);
    }
}

if (!function_exists('safe_b64encode')) {

    function safe_b64encode($string)
    {
        return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($string));
    }

}

if (!function_exists('safe_b64decode')) {

    function safe_b64decode($string)
    {
        $string = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($string) % 4;
        if ($mod4) {
            $string .= substr('====', $mod4);
        }
        return base64_decode($string);
    }

}

if (!function_exists('encode')) {

    function encode($value='') {
        if (!$value) { return false; }
        return trim(safe_b64encode(openssl_encrypt($value,"AES-128-ECB", SKEY, 1, enc_IV)));
    }

}

if (!function_exists('decode')) {

    function decode($value='') {
        if (!$value) { return false; }
        return trim(openssl_decrypt(safe_b64decode($value), "AES-128-ECB", SKEY, 1, enc_IV));
    }
}

if (!function_exists('unauthorized_access')) {

    function unauthorized_access()
    {
        redirect(setAppURL('errors/error403'));
    }

}

if (!function_exists('arrange_post_data')) {

    function arrange_post_data($post, $fields)
    {
        $new_data = [];
        array_walk($fields, function (&$n, $k, $mixed) use (&$new_data) {
            if (isset($mixed[$k])) { $new_data[$k] = $mixed[$k]; }
        }, $post);
        return $new_data;
    }

}

if (!function_exists('pattern_search_arr')) {

    function pattern_search_arr($pattern, $input) {
        return preg_grep($pattern, $input);
    }

}

if (!function_exists('buildTree')) {

    function buildTree($elements, $parentId = 0, $parentRes = [], $inc = -1, $res = [], $type = 'object')
    {
        if (!array_key_exists($parentId, $parentRes)) {
            $inc++;
            $parentRes[$parentId] = $inc;
        }
        if (isset($elements[$parentId])) {
            if ($type != 'object') {
                foreach ($elements[$parentId] as $v) {
                    $v['sep_cnt'] = $inc;
                    $res[$v['id']] = $v;
                    $res = buildTree($elements, $v['id'], $parentRes, $inc, $res, $type);
                }
            }
            else {
                foreach ($elements[$parentId] as $v) {
                    $v->sep_cnt = $inc;
                    $res[$v->id] = $v;
                    $res = buildTree($elements, $v->id, $parentRes, $inc, $res, $type);
                }
            }
        }
        return $res;
    }

}

if (!function_exists('treeOrder')) {

    function treeOrder($items, $parent_key = 'parent_id', $half_tree = 0, $type = 'object')
    {
        $resArr = [];
        if (count($items) > 0) {
            if ($type != 'object') {
                array_walk($items, function (&$n, $k, $mixed) use (&$resArr) {
                    $resArr[$n[$mixed]][$n['id']] = $n;
                }, $parent_key);
            }
            else {
                array_walk($items, function (&$n, $k, $mixed) use (&$resArr) {
                    $resArr[$n->$mixed][$n->id] = $n;
                }, $parent_key);
            }
            $items = ($half_tree) ? $resArr : buildTree($resArr);
        }
        return $items;
    }

}

if (!function_exists('get_all_children')) :

    function get_all_children($stack, $cat_id, $parent_key = 'parent_id', $type = 'object', $all_child = array())
    {
        if ($type != 'object') {
            foreach ($stack as $k => $v) {
                if ($v[$parent_key] == $cat_id) {
                    $all_child[] = $v['id'];
                    $all_child = get_all_children($stack, $v['id'], $parent_key, $type, $all_child);
                }
            }
        }
        else {
            foreach ($stack as $k => $v) {
                if ($v->$parent_key == $cat_id) {
                    $all_child[] = $v->id;
                    $all_child = get_all_children($stack, $v->id, $parent_key, $type, $all_child);
                }
            }
        }
        return $all_child;
    }

endif;

if (!function_exists('getParentsList')) {

    function getParentsList($items, $key, $include_root = 0, $first_child_label = 'Root', $type = 'object')
    {
        $parent_list = ($include_root) ? [0 => $first_child_label] : [];
        if (count($items) > 0) {
            if ($type != 'object') {
                array_walk($items, function (&$n, $k, $mixed) use (&$parent_list) {
                    $parent_list[$k] = ($n['sep_cnt'] > 0) ? repeater('&nbsp;', 3 * $n['sep_cnt']) . repeater('---', 1) . ' ' . $n[$mixed] : $n[$mixed];
                }, $key);
            }
            else {
                array_walk($items, function (&$n, $k, $mixed) use (&$parent_list) {
                    $parent_list[$k] = ($n->sep_cnt > 0) ? repeater('&nbsp;', 3 * $n->sep_cnt) . repeater('---', 1) . ' ' . $n->$mixed : $n->$mixed;
                }, $key);
            }
        }

        return $parent_list;
    }

}

if (!function_exists('wrap_arr_items')) {

    function wrap_arr_items($items, $str = "'")
    {
        array_walk($items, function (&$n, $k, $mixed) {
            //$n = str_pad($n, strlen($n)+2, $mixed, STR_PAD_BOTH);
            $n = $mixed . $n . $mixed;
        }, $str);
        return $items;
    }

}

if (!function_exists('FormattedResultList')) :

    function FormattedResultList($items, $key, $seprator = ' | ', $type = 'object') {
        $list = [];
        $key = explode('.', $key);
        if (count($items) > 0) {
            if ($type != 'object') :
                array_walk($items, function(&$n, $k, $mixed) use(&$list, &$seprator) {
                    foreach ($mixed as $mv) : $temp[] = $n[$mv];
                    endforeach;
                    $list[$k] = implode($seprator, $temp);
                    unset($temp);
                }, $key);
            else :
                array_walk($items, function(&$n, $k, $mixed) use(&$list, &$seprator) {
                    foreach ($mixed as $mv) : $temp[] = $n->$mv;
                    endforeach;
                    $list[$k] = implode($seprator, $temp);
                    unset($temp);
                }, $key);
            endif;
        }
        return $list;
    }

endif;


if (!function_exists('FormattedResultListOnIndex')) {

    function FormattedResultListOnIndex($items, $key, $seprator = ' | ', $index = 'id', $type = 'object')
    {
        $list = [];
        $key = explode('.', $key);
        if (count($items) > 0) {
            if ($type != 'object') :
                array_walk($items, function (&$n, $k, $mixed) use (&$list, &$seprator, &$index) {
                    foreach ($mixed as $mv) : $temp[] = $n[$mv];
                    endforeach;
                    $list[$n[$index]] = implode($seprator, $temp);
                    unset($temp);
                }, $key);
            else :
                array_walk($items, function (&$n, $k, $mixed) use (&$list, &$seprator, &$index) {
                    foreach ($mixed as $mv) : $temp[] = $n->$mv;
                    endforeach;
                    $list[$n->$index] = implode($seprator, $temp);
                    unset($temp);
                }, $key);
            endif;
        }
        return $list;
    }

}

if (!function_exists('FormattedResultListOnIndexHierarchical')) {

    function FormattedResultListOnIndexHierarchical($items, $key, $seprator = ' | ', $index = 'id', $type = 'object')
    {
        $list = [];
        if (count($items) > 0) {
            $items = array_values($items);
            if ($type != 'object') :
                array_walk($items, function (&$n, $k, $mixed) use (&$list, &$seprator, &$index) {
                    $list[$n[$index]] = implode($seprator, [$k + 1, $n[$mixed]]);
                }, $key);
            else :
                array_walk($items, function (&$n, $k, $mixed) use (&$list, &$seprator, &$index) {
                    $list[$n->$index] = implode($seprator, [$k + 1, $n->$mixed]);
                }, $key);
            endif;
        }
        return $list;
    }

}



if (!function_exists('getFilesizeUnitValue')) :

    function getFilesizeUnitValue($value, $filesize_arr = []) {
        $filesize_arr = (count($filesize_arr) > 0) ? $filesize_arr : ['TB' => pow(1024, 4), 'GB' => pow(1024, 3), 'MB' => pow(1024, 2), 'KB' => 1024];
        $inc = 1;
        $tot = count($filesize_arr);
        foreach ($filesize_arr as $k => $v) :
            if ($value * 2 >= $v) :
                $value = number_format($value / $v, 1, '.', '') . nbs() . $k;
                break;
            endif;
            if ($tot == $inc) :
                $value = number_format($value / 1024, 1, '.', '') . ' KB';
            endif;
            $inc++;
        endforeach;
        return $value;
    }

endif;


if (!function_exists('acl_content_arr_wrap')) :

    function acl_content_arr_wrap($items, $type = 'object') {
        $list = [];
        if (count($items) > 0) {
            if ($type != 'object') :
                array_walk($items, function(&$n, $k) {
                    $key = ($n['parent_id'] > 0) ? $n['content_title'] . $n['parent_id'] : $n['content_title'];
                    $list[$key] = $n;
                });
            else :
                array_walk($items, function(&$n, $k) use(&$list) {
                    $key = ($n->parent_id > 0) ? $n->content_title . $n->parent_id : $n->content_title;
                    $list[$key] = $n;
                });
            endif;
        }
        return $list;
    }

endif;

if (!function_exists('acl_access_functions_arr_wrap')) {
    function acl_access_functions_arr_wrap($items, $acl_lvl_arr)
    {
        $list = [];
        if (count($items) > 0) {
            $acl_lvl_arr = json_decode(base64_decode($acl_lvl_arr), 1);
            foreach ($items as $k => $v) {
                $method = ($v->method != '') ? $v->method : 0;
                $list[strtolower($method . $v->function_name . $v->acl_action)]['user_groups'] = (count($v->user_groups) > 0) ? array_intersect($v->user_groups, $acl_lvl_arr[$v->acl_level_id]['user_groups']) : $acl_lvl_arr[$v->acl_level_id]['user_groups'];
            }
        }
        return $list;
    }
}

if (!function_exists('formatPhoneNo')) {

    function formatPhoneNo($phone_no, $country = 'US')
    {
        switch ($country) :
            default :
                $new_phone_no = sprintf("(%s) %s-%s", substr($phone_no, 0, 3), substr($phone_no, 3, 3), substr($phone_no, 6, 4));
                break;
        endswitch;

        return $new_phone_no;
    }

}

if(!function_exists('getMonthsList')) {
    function getMonthsList()
    {
        $months = [];
        foreach (range(1, 12) as $v) {
            $months[$v] = date('F', mktime(0, 0, 0, $v, 1, date('Y')));
        }
        return $months;
    }
}

if(!function_exists('getYearsList')) {
    function getYearsList($limit=10) {
        $date = new DateTime();
        $currentYear = $date->format('Y');
        $date->add(new DateInterval('P'.$limit.'Y'));
        $expYear = [];
        foreach(range($currentYear, $date->format('Y')) as $val) :
            $expYear[$val] = $val;
        endforeach;
        return $expYear;
    }
}

if(!function_exists('formatDateTime')) {
    function formatDateTime($date = '', $format = 'Y-m-d')
    {
        $format = explode('||', $format);
        $newdate = (isset($format[2]))? new DateTime($date, new DateTimeZone($format[2])):new DateTime($date);
        if(isset($format[1])) { $newdate->setTimezone(new DateTimeZone($format[1])); }
        return ($newdate->getTimestamp()) ? $newdate->format($format[0]) : '';
    }
}

if (!function_exists('authenticate_acl_action')) {

    function authenticate_acl_action($actionlist, $method, $function, $action, $logged_usergrps, $redirect = 0)
    {
        $success = 0;
        $key = strtolower($method . $function . $action);
        if (isset($actionlist[$key])) {
            if (count(array_intersect($logged_usergrps, $actionlist[$key]['user_groups'])) < 1) {
                if ($redirect) {
                    unauthorized_access();
                }
            } else {
                $success = 1;
            }
        }

        return $success;
    }

}

if (!function_exists('authenticate_acl_action_item_level')) {

    function authenticate_acl_action_item_level($acl_lvl_arr, $acl_lvl_id, $item_usergroups, $logged_usergrps, $redirect = 0)
    {
        $success = 1;
        $item_usr_grps_new = (count($item_usergroups) > 0) ? array_intersect($acl_lvl_arr[$acl_lvl_id]->user_groups, $item_usergroups) : $acl_lvl_arr[$acl_lvl_id]->user_groups;
        if(count($item_usr_grps_new) > 0) {
            $success = count(array_intersect($logged_usergrps, $item_usr_grps_new));
            if ($redirect && !$success) {
                unauthorized_access();
            }
        }
        return $success;
    }

}

if(!function_exists('build_postvar')) {
    function build_postvar($vars = [])
    {
        if (isset($_POST)) {
            if (count($_POST) > 0) {
                array_walk($vars, function (&$v, $k) {
                    if (!isset($_POST[$k])) { $_POST[$k] = $v; }
                });
            }
        }
    }
}

if(!function_exists('destroy_postvar')) {
    function destroy_postvar($vars = [])
    {
        array_walk($vars, function (&$v, $k) {
            if (isset($_POST[$v])) {
                unset($_POST[$v]);
            }
        });
    }
}

if(!function_exists('jsonAjax')) {
    function jsonAjax($data, $type = 0, $type_arr = 1)
    {
        return ($type) ? json_decode($data, $type_arr) : json_encode($data);
    }
}

if(!function_exists('text_wrapping')) {
    function text_wrapping($data, $max, $start = 0)
    {
        $data = strip_tags($data);
        return (strlen($data) > $max) ? substr($data, $start, $max) . '...' : $data;
    }
}

if(!function_exists('add_selectbx_initial')) {
    function add_selectbx_initial($arr, $initial=[''=>'Please Select']) {
        $arr = $initial+$arr;
        //ksort($arr);
        return $arr;
    }
}

if(!function_exists('convert_content_relative_paths')) {
    function convert_content_relative_paths($content) {
        $doc = new DOMDocument();
        $doc->loadHTML($content);
        $tags_list = ['img'=>'src', 'a'=>'href'];
        foreach($tags_list as $k => $v) {
            $tags = $doc->getElementsByTagName($k);
            if (count($tags) > 0) {
                foreach ($tags as $tag) {
                    if (parse_url($tag->getAttribute($v), PHP_URL_SCHEME) == NULL) {
                        $tag->setAttribute($v, base_url() . $tag->getAttribute($v));
                    }
                }
            }
        }
        return $doc->saveHTML();
    }
}

if (!function_exists('FormattedCommonOperations')) {

    function FormattedCommonOperations($items, $keys, $operations='', $type = 'object')
    {
        $keys = explode('.', $keys);
        $operations = explode('.', $operations);
        if (count($items) > 0) {
            if ($type != 'object') :
                array_walk($items, function (&$n, $k, $mixed) use(&$operations) {
                    foreach($mixed as $v) {
                        foreach($operations as $opp) {
                            $n[$v] = $opp($n[$v]);
                        }
                    }
                }, $keys);
            else :
                array_walk($items, function (&$n, $k, $mixed) use(&$operations) {
                    foreach($mixed as $v) {
                        foreach($operations as $opp) {
                            $n->$v = $opp($n->$v);
                        }
                    }
                }, $keys);
            endif;
        }
        return $items;
    }

}

if(!function_exists('apply_number_format')) {
    function apply_number_format($number) {
        return number_format($number, 2, '.', '');
    }
}

function d($a) {
    echo '<pre>';
    print_r($a);
    echo '</pre>';
    exit();
}
