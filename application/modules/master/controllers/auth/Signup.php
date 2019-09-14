<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends PublicMaster {

    protected $list_link = 'auth/signup';
    protected $model_name = 'users/user_model';
    protected $model = 'user_model';
    protected $tbl = 'users';
    protected $is_model = 0;
    protected $login_link = 'auth/login';

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')) :
            redirect($this->setAppURL('dashboard/home'));
        endif;
        if(!$this->config->item('allow_user_registration')) {
            $this->session->set_flashdata('flashError', $this->lang->line('allow_user_registration_error'));
            redirect($this->setAppURL($this->login_link));
        }
        $this->load->library('UserAuth');
        $this->lang->load('auth/signup');

    }

    private function _initEnv() {
        $this->_loadModelEnv();
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('first_name', 'lang:err_user_fname', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('middle_name', 'lang:err_user_mname', 'trim|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('last_name', 'lang:err_user_lname', 'trim|required|min_length[2]|max_length[50]');
        $is_unique = '|is_unique[users.email]';
        $this->form_validation->set_rules('email', 'lang:err_user_email', 'trim|required|valid_email|min_length[2]|max_length[150]'.$is_unique, ['is_unique'=> $this->lang->line('err_is_unique')]);
        $this->form_validation->set_rules('password_hash', 'lang:err_user_password','trim|required|min_length[5]|max_length[20]');
        $this->form_validation->set_rules('verify_password', 'lang:err_user_vpassword','trim|required|matches[password_hash]');
        return $this->form_validation->run();
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->FormProcess($data);
        $this->_initBasicFormEnv($data);
    }

    private function _initBasicFormEnv($data) {
        $this->BuildContentEnv(['form']);
        $this->minify->add_js($this->th_custom_js_path . 'auth/signup.js', 'page');
        $this->minify->add_css($this->config->item('th_media_pages') . 'css/login.min.css', 'page');
        $data['form_action'] = $this->setAppURL($this->list_link);
        $data['login_link'] = $this->setAppURL($this->login_link);
        $data['view_name'] = 'auth/signup';
        $data['view_class'] = 'login';
        return $data;
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->BuildContentEnv();
        if ($this->doFormValidation()) :
            $post_fields = ['first_name'=>'', 'middle_name'=>'', 'last_name' =>'', 'email' =>'', 'password_hash'=>''];
            build_postvar(['user_groups'=>$this->config->item('default_register_groups')]);

            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), $post_fields), []);
            if ($item_id) :
                $error = $this->userauth->doAuthentication($this->input->post('email'), $this->input->post('password_hash'), $this->master_app, $this->hook_data->curr_domain->user_groups);
                if (!$error) :
                    $this->deleteCache(Modules::run($this->master_app . '/users/users/getVal', 'tbl') . '*');
                    Modules::run($this->master_app . '/users/users/sendNewUserNotifications');
                    redirect($this->setAppURL('dashboard/home'));
                else : $data['error'] = $error;
                endif;
            else :
                $data['error'] = $this->lang->line('err_user_save');
            endif;
        endif;
        $data = $this->_initBasicFormEnv($data);
        $this->displayView($data);
    }

}
