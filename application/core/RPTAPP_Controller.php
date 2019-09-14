<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class RPTAPP_Controller extends MX_Controller {

    public $boolean_arr = [];
    public $display_date_frmt;
    public $display_date_full_frmt;
    public $current_app = '';
    public $master_app = '';
    protected $default_view = 'index';
    protected $app_cache;
    protected $apc_cache_on = 0;
    public $app_module_path = '';
    public $th_custom_js_path = '';
    public $th_custom_css_path = '';
    protected $is_admin = 0;
    protected $app_home_url = '';

    protected $tbl;
    protected $enable_acl_btn = 1;
    protected $acl_btn_list = [];

    public $userdata;

    public function __construct() {
        parent::__construct();
        date_default_timezone_set($this->config->item('time_reference'));
        $this->boolean_arr = $this->config->item('boolean_arr');
        $this->display_date_frmt = $this->config->item('display_date_format');
        $this->display_date_full_frmt = $this->config->item('display_date_format_full');
    }

    public function getVal($property) {
        return (property_exists($this, $property)) ? $this->$property : '';
    }

    protected function validateFormToken($post_token) {
        if ($post_token != false && $this->security->get_csrf_hash() != $post_token) :
            redirect($this->setAppURL('errors/error404'));
        endif;
    }

    protected function setCachingEnv() {
        if($this->cache->memcached->is_supported() && $this->config->item('apc_cache')) :
            $this->app_cache = $this->cache->memcached;
            $this->apc_cache_on = 1;
        elseif($this->cache->apc->is_supported() && $this->config->item('apc_cache')) :
            $this->app_cache = $this->cache->apc;
            $this->apc_cache_on = 1;
        else : $this->app_cache = $this->cache->file;
        endif;
        //$this->app_cache = $this->cache->file;
    }

    protected function BuildFormValidationEnv() {
        //$this->validateFormToken($this->input->post($this->config->item('csrf_token_name')));
        $this->load->library('form_validation');
    }

    protected function BuildContentEnv($env = ['form', 'form_elements']) {

        foreach ($env as $v) :
            switch ($v) :
                case 'view_form' :
                    $this->load->helper('form');
                    $this->load->helper($this->config->item('default_theme') . '_form');
                    break;
                case 'table' :
                    $this->load->library('table');
                    $this->minify->add_js($this->config->item('js_datatables'), 'pageplg');
                    $this->minify->add_css($this->config->item('css_datatables'), 'pageplg');
                    break;
                case 'form' :
                    $this->load->helper('form');
                    $this->load->helper($this->config->item('default_theme') . '_form');
                    //$this->load->library('form_builder');
                    $this->minify->add_js($this->config->item('js_jquery_validation'), 'pageplg');
                    $this->minify->add_js($this->config->item('js_form_fileinput'), 'pageplg');
                    $this->minify->add_css($this->config->item('css_form_fileinput'), 'pageplg');
                    $this->minify->add_js($this->config->item('th_media_custom_js') . 'formajax.js', 'page');
                    break;
                case 'form_elements' :
                    $this->minify->add_js($this->config->item('js_form_elements'), 'pageplg');
                    $this->minify->add_css($this->config->item('css_form_elements'), 'pageplg');
                    break;
                case 'datepicker' :
                    $this->minify->add_js($this->config->item('js_datepicker'), 'pageplg');
                    $this->minify->add_css($this->config->item('css_datepicker'), 'pageplg');
                    break;
                case 'daterangepicker' :
                    $this->minify->add_js($this->config->item('js_daterangepicker'), 'pageplg');
                    $this->minify->add_css($this->config->item('css_daterangepicker'), 'pageplg');
                    break;
                case 'modal' :
                    $this->minify->add_js($this->config->item('js_bootstrap_modal'), 'pageplg');
                    $this->minify->add_css($this->config->item('css_bootstrap_modal'), 'pageplg');
                    break;
                case 'email' :
                    $this->load->library('email');
                    $this->email->initialize($this->config->item('email_setup'));
                    break;
                case 'editor' :
                    $this->minify->add_js($this->config->item('js_editor_summernote'), 'pageplg');
                    $this->minify->add_css($this->config->item('css_editor_summernote'), 'pageplg');
                    break;
            endswitch;
        endforeach;
    }

    protected function setApplicationEnv() {
        $this->current_app = $this->config->item('current_app');
        $this->master_app = $this->config->item('master_app');
        $this->app_module_path = $this->config->item('global_module_path') . $this->current_app . '/';
        $this->th_custom_js_path = $this->config->item('th_media_custom_js') . $this->current_app . '/';
        $this->th_custom_css_path = $this->config->item('th_media_custom_css') . $this->current_app . '/';
        $this->app_home_url = $this->setAppURL();
        $this->setCachingEnv();
    }

    protected function displayView($data) {
        $data['is_admin'] = $this->is_admin;
        $this->load->view($this->current_app . '/' . $this->default_view, $data);
    }

    protected function getIsAdminVal() {
        return $this->is_admin;
    }

    private function BuildCacheKey($key) {
        return $this->config->item('caching_prefix') . $key;
    }

    protected function getCacheVal($key) {
        return $this->app_cache->get($this->BuildCacheKey($key));
    }

    protected function setCacheVal($key, $data, $ttl = 0) {
        $ttl = (!$ttl) ? $this->config->item('cache_short_ttl') : $ttl;
        $this->app_cache->save($this->BuildCacheKey($key), $data, $ttl);
    }

    protected function getKey_FilterArr($data) {
        return hash('sha256', serialize($data));
    }

    protected function deleteCacheProcess($key) {
        $key = $this->BuildCacheKey($key);
        if (substr($key, -1) == '*') :
            $key = str_replace('*', '', $key);
            $cache_info = $this->app_cache->cache_info();
            if ($this->apc_cache_on) :
                array_walk($cache_info['cache_list'], function(&$n, $k, $mixed) use(&$key) {
                    if (strpos($n['info'], $key) !== false) : $mixed->delete($n['info']);
                    endif;
                }, $this->app_cache);
            else :
                $matched_keys = pattern_search_arr('/^' . $key . '/', array_keys($cache_info));
                if (count($matched_keys) > 0) :
                    array_walk($matched_keys, function(&$n, $k, $mixed) {
                        $mixed->delete($n);
                    }, $this->app_cache);
                endif;
            endif;
        else :
            $this->app_cache->delete($key);
        endif;
    }

    protected function deleteCache($keys, $cache_type='db') {
        if (is_array($keys)) : foreach ($keys as $vv) : $this->deleteCacheProcess($cache_type.'_'.$vv);
            endforeach;
        else : $this->deleteCacheProcess($cache_type.'_'.$keys);
        endif;
    }

    protected function basicCacheKey($key, $prefix='position_') {
        $cacheKey = $prefix.$key;
        $cacheKey .= $this->config->item('cache_key_separator');
        return $cacheKey;
    }

    protected function cachekey_cnfg($cnfg) {
        $cacheKey = 'db_'.$cnfg['table'];
        $cacheKey .= $this->config->item('cache_key_separator');
        $cacheKey .= $this->getKey_FilterArr([$cnfg['filterArr'], $cnfg['hooks']]);
        return $cacheKey;
    }

    protected function _loadModelEnv($cnfg1 = '') {
        $cnfg = [
            'model_name' => $this->model_name,
            'model_var' => $this->model,
            'flag' => 'is_model'
        ];
        $cnfg = OverwriteArray($cnfg, $cnfg1);
        $modelname = $cnfg['flag'];
        if (!$this->$modelname) :
            $this->load->model($cnfg['model_name'], $cnfg['model_var']);
            $this->$modelname = 1;
        endif;
    }

    protected function getItemsCache($cnfg1 = '') {
        $cnfg = [
            'filterArr' => [],
            'model' => $this->{$this->model},
            'table' => $this->tbl,
            'hooks' => [],
            'cache_life' => 'cache_week_ttl'
        ];
        $cnfg = OverwriteArray($cnfg, $cnfg1);
        $cacheKey = $this->cachekey_cnfg($cnfg);

        if (!$items = $this->getCacheVal($cacheKey)) :
            $cnfg['model']->setItems($cnfg['filterArr']);
            $items = $cnfg['model']->data_items;
            if (count($cnfg['hooks']) > 0) :
                array_walk($cnfg['hooks'], function(&$n) use(&$items) {
                    if (strpos($n, '(')) :
                        preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\.,= ]+)\))?/', $n, $matches);
                        $params = array_merge([$items], explode(',', $matches[3]));
                        $items = (function_exists($matches[1])) ? call_user_func_array($matches[1], $params) : $items;
                    else :
                        $items = (function_exists($n)) ? $n($items) : $items;
                    endif;
                });
            endif;
            $this->setCacheVal($cacheKey, $items, $this->config->item($cnfg['cache_life']));
        endif;
        return $items;
    }

    public function get_Items($cnfg = '', $mcnfg = '') {
        $this->_loadModelEnv($mcnfg);
        return $this->getItemsCache($cnfg);
    }

    protected function setAppURL($url = '') {
        return ($this->current_app == $this->config->item('front_app'))? $url:$this->current_app . '/' . $url;
    }

    protected function unauthorized_access($err='error401') {
        redirect($this->current_app . '/' . 'errors/'.$err);
    }

    protected function getEmailBody($data) {
        return $this->load->view($this->current_app . '/email_general.php', $data, TRUE);
    }

    protected function SendEmail($to = [], $subject, $email_data, $cc = [], $bcc = []) {
        $this->email->clear();
        $this->email->from($this->config->item('from_email'), $this->config->item('from_name'));
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->bcc($bcc);
        $this->email->subject($subject);
        $this->email->message($this->getEmailBody($email_data));
        return $this->email->send();
    }

    protected function loadLanguage($lang) {
        $this->lang->load($this->current_app . '/' . $lang);
    }

    protected function sanitizePostData($fields) {
        if (isset($_POST['submit'])) :
            array_walk($fields, function(&$n) {
                $_POST[$n] = array_unique(array_filter($_POST[$n]));
            });
        endif;
    }

    protected function encodeData($data) {
        return $this->encrypt->encodeData($data);
    }

    protected function decodeData($data) {
        return $this->encrypt->decodeData($data);
    }

    protected function setACL_Btns() {
        $aclbtns = [];
        if($this->enable_acl_btn && count($this->acl_btn_list) > 0) {
            foreach($this->acl_btn_list as $k => $v) {
                $aclbtns[$k] = $this->authenticate_acl_action($v['mthd'], $v['func'], $v['actn'], $v['rdrct']);
            }
        }
    return $aclbtns;
    }

    protected function authenticate_acl_action($method='', $function, $action, $redirect = 0) {
        $success = 0;
        $method = ($method === '')? $this->router->fetch_method():$method;
        $key = strtolower($method . $function . $action);
        //d($this->hook_data->access_functions);
        if (isset($this->hook_data->access_functions[$key])) :
            if (count(array_intersect($this->userdata->user_groups, $this->hook_data->access_functions[$key]['user_groups'])) < 1) :
                if ($redirect) : $this->unauthorized_access();
                endif;
            else : $success = 1;
            endif;
        endif;
        return $success;
    }

    protected function authenticate_acl_action_item_level($acl_lvl_id, $item_usergroups, $redirect = 0) {
        $success = 1;
        $item_usr_grps_new = (count($item_usergroups) > 0) ? array_intersect($this->hook_data->access_levels_arr[$acl_lvl_id]->user_groups, $item_usergroups) : $this->hook_data->access_levels_arr[$acl_lvl_id]->user_groups;
        if(count($item_usr_grps_new) > 0) {
            $success = count(array_intersect($this->userdata->user_groups, $item_usr_grps_new));
            if ($redirect && !$success) : $this->unauthorized_access();
            endif;
        }
        return $success;
    }

    protected function BuildAjaxGridEnv($search_cols = [], $order_cols = []) {
        $data = [];
        $rowdata = [];
        if ($this->input->is_ajax_request()) :
            $search = trim($this->input->post('search')['value']);
            $filters = [];
            if ($search != '' && count($search_cols) > 0) :
                foreach ($search_cols as $cv) :
                    $filters[$cv] = $search;
                endforeach;
                $filters['group_start']['or_like'] = $filters;
            endif;
            if(count($this->grid_settings['fixed_filters']) > 0) { $filters += $this->grid_settings['fixed_filters']; }
            $items = $this->get_Items(['filterArr' => ['db_Filters' => $filters, 'db_Groups'=>['a.id'], 'db_Select' => ['a.id']]]);
            //if (count($items) > 0) :
                //$all_item = current($items);
                $order = $this->input->post('order')[0];
                $filter_arr = ['db_Filters' => $filters, 'db_Limit' => [$this->input->post('length'), $this->input->post('start')]];
                if(is_numeric($order['column'])) { $filter_arr['db_Order'] = [$order_cols[$order['column']] => $order['dir']]; }
                $items1 = $this->get_Items(['filterArr' => $filter_arr]);
                $num2 = count($items1);
                if($num2 > 0) :
                    $rowdata = $this->AjaxGridRecordsList($items1);
                endif;
                $data = [
                    'draw' => $this->input->post('draw'),
                    'recordsTotal' => count($items),
                    'recordsFiltered' => count($items),
                    'data' => $rowdata
                ];
            //endif;
        endif;
        
        echo jsonAjax($data);
        exit();
    }

    protected function getModuleData($extension_type, $module_id, $acl_level_id) {
        switch($extension_type) {
            case '1':
                $module_data = Modules::run($this->master_app . '/components/text_widgets/utilityList', ['select'=>['a.id', 'a.widget_text'], 'filters'=>['where'=>['a.published'=>1, 'b.published'=>1, 'a.domain_id'=>$this->hook_data->curr_domain->id, 'a.id'=>$module_id]], 'hooks'=>['FormattedCommonOperations(widget_text,convert_content_relative_paths)', 'FormattedResultList(widget_text)']]);
                break;
            case '2' :
                $module_data = Modules::run($this->master_app . '/settings/menu_items/utilityList', ['filters'=>['where'=>['a.published'=>1, 'c.published'=>1, 'd.published'=>1, 'e.published'=>1, 'a.menu_type_id'=>$module_id, 'c.domain_id'=>$this->hook_data->curr_domain->id]]]);
                break;
            case '3':
                $module_data = Modules::run($this->master_app . '/components/banners/utilityList', ['filters'=>['where'=>['a.published'=>1, 'b.published'=>1, 'c.published'=>1, 'b.domain_id'=>$this->hook_data->curr_domain->id, 'a.category_id'=>$module_id]]]);
                break;
            case '4' :
                $module_data = Modules::run($this->master_app . '/components/testimonials/utilityList', ['filters'=>['where'=>['a.published'=>1, 'b.published'=>1, 'c.published'=>1, 'b.domain_id'=>$this->hook_data->curr_domain->id, 'a.category_id'=>$module_id]]]);
                break;
            case '5' :
                $module_data = [];
                $post_cat_data = Modules::run($this->master_app . '/components/posts_categories/utilityList', 1, ['select'=>['a.id', 'a.category_name', 'a.category_slug', 'c.description', 'c.cat_image', 'c.meta_title', 'c.meta_keywords', 'c.meta_description'], 'hooks'=>[], 'filters'=>['where'=>['a.published'=>1, 'b.published'=>1, 'a.domain_id'=>$this->hook_data->curr_domain->id, 'a.id'=>$module_id]]]);
                if(count($post_cat_data) > 0) {
                    $module_data['category_data'] = $post_cat_data;
                    $module_data['posts_data'] = Modules::run($this->master_app . '/components/posts/utilityList', ['select'=>['a.id', 'a.post_name', 'a.post_alias', 'c.description', 'c.post_image', 'c.meta_title', 'c.meta_keywords', 'c.meta_description'], 'hooks'=>[], 'filters'=>['where'=>['a.published'=>1, 'b.published'=>1, 'd.published'=>1, 'a.domain_id'=>$this->hook_data->curr_domain->id, 'a.post_category_id'=>key($post_cat_data)]]]);
                }
                break;
            case '6' :
                $module_data = ['custom_block'];
                break;
            case '7' :
                $module_data = Modules::run($this->master_app . '/components/posts/utilityList', ['select'=>['a.id', 'a.post_name', 'a.post_alias', 'c.description', 'c.post_image', 'c.meta_title', 'c.meta_keywords', 'c.meta_description'], 'hooks'=>[], 'filters'=>['where'=>['a.published'=>1, 'b.published'=>1, 'd.published'=>1, 'a.domain_id'=>$this->hook_data->curr_domain->id, 'a.id'=>$module_id]]]);
                break;
        }

        return $module_data;
    }

    protected function getExtensionData($extension_position) {

        $ext_data = ['data_display'=>0, 'content'=>0, 'heading'=>''];
        $extension_data = Modules::run($this->master_app . '/components/extensions/utilityList', ['filters'=>['where'=>['a.published'=>1, 'b.published'=>1, 'c.published'=>1, 'a.domain_id'=>$this->hook_data->curr_domain->id, 'c.position_name'=>$extension_position]]]);
        if(count($extension_data) < 1) {
            return $ext_data;
        }
        $extension_data = current($extension_data);
        if(!$this->authenticate_acl_action_item_level($extension_data->acl_level_id, $extension_data->user_groups)) {
            return $ext_data;
        }

        if(!$extension_data->is_ext_global && count($extension_data->selected_pages) > 0) {
            if(!isset($extension_data->selected_pages[$this->router->fetch_class()])) {
                return $ext_data;
            }
            if(count($extension_data->selected_pages[$this->router->fetch_class()]) > 0) {
                if(!in_array($this->router->fetch_method(), $extension_data->selected_pages[$this->router->fetch_class()])) {
                    return $ext_data;
                }
            }
        }

        $module_data = $this->getModuleData($extension_data->ext_type, $extension_data->module_id, $extension_data->acl_level_id);
        $ext_data = (count($module_data) > 0)? ['data_display'=>1, 'content'=>$module_data, 'heading'=>($extension_data->ext_show_heading)? $extension_data->ext_heading:'']:$ext_data;

        return $ext_data;
    }

    protected function renderExtensionData($extension_position, $cnfg1=[]) {
        $cnfg = [
            'load_view' => 1,
            'view_name' => '',
            'do_cache'=> 1,
            'cache_prefix' => $this->config->item('cache_positions_prefix'),
            'cache_life' => 'cache_week_ttl'
        ];
        $cnfg = OverwriteArray($cnfg, $cnfg1);

        if($cnfg['load_view']) {
            $item_val = '';
            $view_name = ($cnfg['view_name'])? $cnfg['view_name']:'positions/'.$extension_position;
            if($this->config->item('cache_positions') && $cnfg['do_cache']) {
                $cache_key = $this->basicCacheKey($this->current_app.'_'.$extension_position, $cnfg['cache_prefix']);
                $item_val = $this->getCacheVal($cache_key);
                if(!$item_val) {
                    $item_val = $this->load->view($this->current_app . '/' . $view_name, ['extData'=>$this->getExtensionData($extension_position)], TRUE);
                    $this->setCacheVal($cache_key, $item_val, $this->config->item($cnfg['cache_life']));
                }
                return $item_val;
            }
            else {
                return $this->load->view($this->current_app . '/' . $view_name, ['extData'=>$this->getExtensionData($extension_position)], TRUE);
            }
        }
        else {
            return $this->getExtensionData($extension_position);
        }

    }

    protected function renderCustomBlockExtensionBasedOnCustomMethod($cnfg=[]) {
        $data = ''; $method = $cnfg['method'];
        $result = $this->renderExtensionData($cnfg['position'], ['load_view'=>0]);
        if($result['data_display']) {
            if($this->config->item('cache_positions')) {
                $cache_key = $this->basicCacheKey($this->current_app.'_'.$cnfg['position'], $this->config->item('cache_positions_prefix'));
                if(!$data = $this->getCacheVal($cache_key)) {
                    $data = $this->load->view($this->current_app.$cnfg['view'], ['items'=>$this->$method(), 'heading'=>$result['heading']], TRUE);
                    $this->setCacheVal($cache_key, $data, $this->config->item($cnfg['cache_life']));
                }
            }
            else {
                $data = $this->load->view($this->current_app.$cnfg['view'], ['items'=>$this->$method(), 'heading'=>$result['heading']], TRUE);
            }
        }
    return $data;
    }

    protected function validate_decrypt_ID($id, $validate = 'number') {
        $error = 0;
        $id = $this->decodeData($id);
        switch ($validate) :
            case 'date' : $chkdate = DateTime::createFromFormat('Y-m-d', $id);
                $error = (!$chkdate) ? 1 : 0;
                break;
            default : $error = (!filter_var($id, FILTER_VALIDATE_INT)) ? 1 : 0;
                break;
        endswitch;
        if ($error) : redirect($this->setAppURL('errors/error404'));
        endif;
        return $id;
    }

}

abstract class Public_Controller extends RPTAPP_Controller {

    public function __construct() {
        parent::__construct();
    }

}

abstract class Admin_Controller extends RPTAPP_Controller {

    protected $cache_list = [];

    public function __construct() {
        parent::__construct();
        $cache_dependecny_list = $this->config->item('cache_dependecny_list');
        $this->cache_list = (isset($cache_dependecny_list[$this->tbl]))? array_merge($cache_dependecny_list[$this->tbl], [$this->tbl.'*']):[$this->tbl.'*'];
        $this->is_admin = 1;
        $this->default_view = 'admin';
        $this->userdata = $this->session->userdata('logged_in');
    }

    protected function BuildAdminEnv($data) {
        $data['user_data'] = $this->userdata;
        $data['inline_script'] = '';
        setUI_Env('admin');
        return $data;
    }

    protected function do_upload($cnfg1=[], $input_name = 'file') {
        $result = [];
        if (!empty($_FILES[$input_name]['name'])) :
            $this->load->library('upload');

            $cnfg['upload_path'] = './uploads/';
            $cnfg['allowed_types'] = 'gif|jpg|png';
            $cnfg['max_size'] = 500;
            $cnfg['max_width'] = 1024;
            $cnfg['max_height'] = 768;
            $cnfg['file_ext_tolower'] = TRUE;
            $cnfg['file_name'] = '';
            $cnfg = OverwriteArray($cnfg, $cnfg1);

            $this->upload->initialize($cnfg);
            $result = (!$this->upload->do_upload($input_name)) ? ['error' => $this->upload->display_errors()] : ['upload_data' => $this->upload->data()];
        endif;
        return $result;
    }

    protected function do_image_resize($file, $cnfg1=[]) {
        if (file_exists($file)) :
            $this->load->library('image_lib');

            $cnfg['image_library'] = 'gd2';
            $cnfg['maintain_ratio'] = TRUE;
            $cnfg['width'] = 150;
            $cnfg['height'] = 150;
            $cnfg = OverwriteArray($cnfg, $cnfg1);
            $cnfg['source_image'] = $file;
            if($cnfg['width'] > 0 || $cnfg['height'] > 0) {
                $this->image_lib->initialize($cnfg);
                $this->image_lib->resize();
            }
        endif;
    }

    protected function deletefile($file) {
        if (is_file($file) && file_exists($file)) :
            unlink($file);
        endif;
    }

    protected function deleteFileDirectory($source, $removeOnlyChildren = false) {
        if (empty($source) || file_exists($source) === false) : return false;
        endif;
        if (is_file($source) || is_link($source)) : return unlink($source);
        endif;

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
        //$fileinfo as SplFileInfo
        foreach ($files as $fileinfo) :
            if ($fileinfo->isDir()) : if ($this->deleteFileDirectory($fileinfo->getRealPath()) === false) : return false;
                endif;
            else : if (unlink($fileinfo->getRealPath()) === false) : return false;
                endif;
            endif;
        endforeach;

        if ($removeOnlyChildren === false) : return rmdir($source);
        endif;

        return true;
    }

    protected function make_directory($dirpath, $mode = 0777) {
        if (file_exists($dirpath)) : return 0;
        endif;
        return mkdir($dirpath, $mode, true);
    }

    protected function getNavItems($nav, $domain_id) {
        $mcnfg = [
            'model_name' => 'settings/Menu_item_model',
            'model_var' => 'menu_item_model'
        ];
        $cnfg = ['filterArr' =>
            [
                'db_Filters' => ['where' => ['a.published' => 1, 'c.menu_type_slug' => $nav, 'c.domain_id' => $domain_id]]
            ],
            'hooks' => ['treeOrder(parent_id, 1)']
        ];
        $items = Modules::run($this->master_app . '/settings/menu_items/get_Items', $cnfg, $mcnfg);
        return $items;
    }

    protected function displayView($data) {
        $acl_btns = $this->setACL_Btns();
        $data = (count($acl_btns) > 0)? array_merge($data, $acl_btns):$data;
        $data['display_name'] = $this->userdata->display_name;
        parent::displayView($data);
    }

    public function utilityList($cnfg1=[]) {
        $filters = [];
        $cnfg = ['select'=> [], 'filters'=> [], 'hooks'=> [], 'order'=>[], 'groups'=> [], 'joins'=> []];
        $cnfg = array_filter(OverwriteArray(OverwriteArray($cnfg, $this->utility_cnfg), $cnfg1));
        foreach(['select'=>'db_Select', 'filters'=>'db_Filters', 'order'=>'db_Order', 'groups'=>'db_Groups', 'joins'=>'db_Joins'] as $k => $v) {
            if(isset($cnfg[$k])) { $filters[$v] = $cnfg[$k]; }
        }
        $cnfg['hooks'] = (isset($cnfg['hooks']))? $cnfg['hooks']:[];
        return $this->get_Items(['filterArr'=>$filters, 'hooks'=>$cnfg['hooks']]);
    }

    public function dataAjaxGrid() {
        $this->BuildAjaxGridEnv($this->grid_settings['search_cols'], $this->grid_settings['order_cols']);
    }

}

require_once('master_Controller.php');
require_once('bex_Controller.php');
