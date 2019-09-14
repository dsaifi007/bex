<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends PublicMaster {

    protected $list_link = 'auth/login';
    
    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in')) : redirect($this->setAppURL('dashboard/home'));
        endif;
        $this->load->library('UserAuth');
        $this->lang->load('auth/login');
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('email', 'lang:err_login_email', 'trim|required|valid_email|min_length[2]|max_length[150]');
        $this->form_validation->set_rules('password', 'lang:err_login_password', 'trim|required|min_length[5]|max_length[20]');
        return $this->form_validation->run();
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        
        $this->BuildContentEnv();
        if ($this->doFormValidation()) :
            $error = $this->userauth->doAuthentication($this->input->post('email'), $this->input->post('password'), $this->master_app, $this->hook_data->curr_domain->user_groups);
            if (!$error) : 
                $this->deleteCache(Modules::run($this->master_app . '/users/users/getVal', 'tbl') . '*');
                redirect($this->setAppURL('dashboard/home'));
            else : $data['error'] = $error;
            endif;
        endif;
        $this->minify->add_js($this->th_custom_js_path . 'auth/login.js', 'page');
        $this->minify->add_css($this->config->item('th_media_pages') . 'css/login.min.css', 'page');
        $data['form_action'] = $this->setAppURL($this->list_link);
        $data['forgot_pwd_link'] = $this->setAppURL('auth/forgotpassword');
        $data['register_link'] = $this->setAppURL('auth/signup');
        $data['view_name'] = 'auth/login';
        $data['view_class'] = 'login';
        $this->displayView($data);
    }
    
}
