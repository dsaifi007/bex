<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Error404 extends PublicMaster {

    public function __construct() {
        parent::__construct();
        $this->lang->load('errors/error404');
    }

    public function index() {
        $this->output->cache($this->config->item('cache_week_ttl'));
        $this->minify->add_css($this->config->item('th_media_pages') . 'css/error.min.css', 'page');
        $data['return_url'] = $this->setAppURL();
        $data['view_name'] = 'errors/error404';
        $data['view_class'] = 'page-404-3';
        $this->displayView($data);
    }

}
