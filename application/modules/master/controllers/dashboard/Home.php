<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends AdminMaster {

	public function __construct() {
		parent::__construct();
		$this->lang->load('dashboard/home');
	}
	
	public function index()
	{
	    $data['view_name'] = 'dashboard/home';
		$data['view_class'] = 'dashboard-home';
		$this->displayView($data);
	}
	
}
