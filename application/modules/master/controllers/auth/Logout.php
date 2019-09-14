<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends AdminMaster {
    
    public function index() {
        $this->load->library('UserAuth');
        $this->userauth->clearUserSession();
        redirect($this->setAppURL('auth/login'));
    }
    
}
