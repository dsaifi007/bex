<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class PublicBex extends Public_Controller {

    public $autoload = [];
    protected $parent_dir = 'site';

    public function __construct() {
        parent::__construct();
        $this->setApplicationEnv();
        $this->userdata = $this->session->userdata('logged_in');
        if(!$this->userdata) {
            $this->userdata = new stdClass();
            $this->userdata->user_groups = [];
        }
        $this->BuildContentEnv();
    }

    protected function setGlobalPositionsPublicContent(&$data) {
        //$this->minify->add_js($this->config->item('js_bxslider'), 'pageplg');
        //$this->minify->add_js($this->th_custom_js_path .$this->parent_dir.'/slider.js', 'page');
        
        foreach(['top_bar_call', 'top_logo', 'footer_navigation', 'footer_copyright', 'footer_address_blk'] as $v) {
            $data[$v] = $this->renderExtensionData($v);
        }
        $data['header_navigation'] = $this->renderExtensionData('header_navigation',['do_cache'=>0]);
        $data['header_slider'] = (isset($data['header_slider']))? $data['header_slider']:$this->renderExtensionData('header_slider_blk', ['view_name' => 'positions/header_slider_blk']);
    }

	protected function displayView($data) {
        $this->setGlobalPositionsPublicContent($data);
        $data['is_inner'] = (isset($data['is_inner']))? $data['is_inner']:0;
        if($data['is_inner']) {
            $this->minify->add_css($this->config->item('th_media_layts_lyt_css').'inner-pages.css', 'page');
        }
        parent::displayView($data);
    }
}

abstract class AdminBex extends Admin_Controller {
    public $autoload = [];

    public function __construct() {
        parent::__construct();
        $this->setApplicationEnv();
    }
}
