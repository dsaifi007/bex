<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class PublicMaster extends Public_Controller {

    public $autoload = [
        //'config' => ['utility', 'theme'],
        //'language' => ['master/application'],
            //'helper'    => array('url', 'form'),
            //'libraries' => array('email'),
    ];

    public function __construct() {
        parent::__construct();
        $this->setApplicationEnv();
    }

}

abstract class AdminMaster extends Admin_Controller {

    public $autoload = [
        //'config' => ['utility', 'theme'],
        //'language' => ['master/application'],
            //'helper'    => array('url', 'form'),
            //'libraries' => array('email'),
    ];

    public function __construct() {
        parent::__construct();
        $this->setApplicationEnv();
        $this->app_home_url = $this->setAppURL('dashboard/home');
    }

    protected function displayView($data) {
        $this->assignBasicEnv($data);
        $data['sidebar_menu_items'] = $this->getNavItems('master-sidebar', $this->hook_data->curr_domain->id);
        $data['home_url'] = $this->app_home_url;
        $data['logout_url'] = $this->setAppURL('auth/logout');
        parent::displayView($data);
    }

    protected function assignBasicEnv(&$data) {
        $encypt_user_id = $this->encodeData($this->userdata->id);
        $data['profile_url'] = (array_intersect($this->userdata->user_groups, $this->config->item('acl_powerful_grps')))? $this->setAppURL('users/users/profile/'.$encypt_user_id):$this->setAppURL('users/usersinfo/profile/'.$encypt_user_id);
    }

}
