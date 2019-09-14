<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cache extends AdminMaster {

    protected $parent_dir = 'settings';
    protected $list_link = 'settings/cache';
    protected $cache_type_list = [];
    protected $no_redirect = 0;

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/cache');
    }

    public function index()
    {
        $data['error'] = ($this->session->flashdata('flashError'))? $this->session->flashdata('flashError'):'';
        $data['success'] = ($this->session->flashdata('flashSuccess'))? $this->session->flashdata('flashSuccess'):'';
        $this->FormProcess($data);
    }

    private function _initBasicFormEnv($data) {
        $data['cache_type_list'] = $this->cache_type_list;
        $data['form_action'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/cache_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/cache';
        $data['view_class'] = 'cache_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('cache_type', 'lang:err_cache_title','trim|required|in_list['.implode(',',array_keys($this->cache_type_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->cache_type_list = [1=>$this->lang->line('clear_js_cache_lbl'), 2=>$this->lang->line('clear_db_cache_lbl'), 3=>$this->lang->line('clear_positions_cache_lbl'), 4=>$this->lang->line('clear_all_cache_lbl')];
        $this->BuildContentEnv();
        if($this->doFormValidation()) :

            switch($this->input->post('cache_type')) :
                case '1': $data['success'] = sprintf($this->lang->line('success_cache_type_delete'), strtoupper('js'));
                    $this->removeJScache();
                    break;
                case '2':
                    $data['success'] = sprintf($this->lang->line('success_cache_type_delete'), strtoupper('db'));
                    $this->deleteCacheProcess('db_*');
                    break;
                case '3':
                    $data['success'] = sprintf($this->lang->line('success_cache_type_delete'), strtoupper('positions'));
                    $this->deleteCacheProcess('positions_*');
                    break;
                case '4':
                    $data['success'] = $this->lang->line('success_cache_delete');
                    $this->removeJScache(); $this->deleteCacheProcess('*');
                    break;
            endswitch;
        endif;
        $data = $this->_initBasicFormEnv($data);
        $this->displayView($data);
    }

    protected function removeJScache() {
        $th_grp = $this->config->item('themes_grp');
        array_walk($th_grp, function(&$n, $k, $mixed) {
            array_map('unlink', glob($mixed.$n.'/*.js'));
        }, FCPATH.$this->config->item('theme_folder'));

    }

    public function removecache($cache_type='db', $key='') {
        $this->_initEnv();
        $key = ($key)? $this->decodeData($key):$cache_type.'_';
        $this->deleteCacheProcess($key.'*');
        $this->session->set_flashdata('flashSuccess', sprintf($this->lang->line('success_cache_type_delete'), strtoupper($cache_type)));
        redirect($this->setAppURL('settings/cache/managecache/'.$cache_type));
    }

    protected function getDBcacheFiles($cache_type) {
        $items = [];
        $cache_info = $this->app_cache->cache_info();
        $cache_sep = $this->config->item('cache_key_separator');
        $cache_prefix = $this->config->item('caching_prefix');

        if($this->apc_cache_on) :
            foreach($cache_info['cache_list'] as $v) :
                if(strpos($v['info'], $cache_prefix.$cache_type) === false) : continue; endif;
                $key = explode($cache_sep, str_replace($cache_prefix, '', $v['info']));
                $key = $key[0];
                $items[$key] = (!isset($items[$key]))? $v['mem_size']:$items[$key]+$v['mem_size'];
            endforeach;
        else :
            foreach($cache_info as $k => $v) :
                if(strpos($k, $cache_prefix.$cache_type) === false) : continue; endif;
                $key = explode($cache_sep, str_replace($cache_prefix, '', $k));
                $key = $key[0];
                $items[$key] = (!isset($items[$key]))? $v['size']:$items[$key]+$v['size'];
            endforeach;
        endif;
        return $items;
    }

    public function managecache($cache_type='db') {
        $data['error'] = ($this->session->flashdata('flashError'))? $this->session->flashdata('flashError'):'';
        $data['success'] = ($this->session->flashdata('flashSuccess'))? $this->session->flashdata('flashSuccess'):'';

        $this->_initEnv();
        $this->lang->load($this->parent_dir.'/'.$cache_type.'_cache');

        $data['items'] = $this->getDBcacheFiles($cache_type);
        if(count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/db_cache.js', 'page');
        endif;
        $data['cache_type'] = $cache_type;
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/db_cache';
        $data['view_class'] = 'db_cache';
        $this->displayView($data);
    }
}
