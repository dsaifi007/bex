<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Error401 extends PublicMaster {

    public function __construct() {
        parent::__construct();
        $this->lang->load('errors/error401');
    }
	//this is index
    public function index() {
        $this->output->cache($this->config->item('cache_week_ttl'));
        $this->minify->add_css($this->config->item('th_media_pages') . 'css/error.min.css', 'page');
        $data['return_url'] = $this->setAppURL();
        $data['heading1_desc'] = $this->lang->line('heading1_desc');
        $data['heading2_desc'] = $this->lang->line('heading2_desc');
        $data['view_name'] = 'errors/common_error';
        $data['view_class'] = 'page-500-full-page';
        $this->displayView($data);
    }
	function newfunction(){
		echo "branch name is master";
	}

	function testing(){
		// this is testing purpose
		echo "this is testing function";
	}

}
