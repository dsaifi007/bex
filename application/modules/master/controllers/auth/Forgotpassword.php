<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Forgotpassword extends PublicMaster {

    protected $list_link = 'auth/forgotpassword';
    protected $login_link = 'auth/login';

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in')) : redirect($this->setAppURL('dashboard/home'));
        endif;
        $this->load->library('UserAuth');
        $this->lang->load('auth/forgotpassword');
    }

    protected function doFormValidation_confirm() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('email', 'lang:err_forgot_pwd_email', 'trim|required|valid_email|min_length[2]|max_length[150]');
        return $this->form_validation->run();
    }

    protected function doFormValidation_reset() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('email', 'lang:err_forgot_pwd_email', 'trim|required|valid_email|min_length[2]|max_length[150]');
        $this->form_validation->set_rules('token_no', 'lang:err_reset_token', 'trim|required|numeric|exact_length[' . $this->config->item('reset_pwd_token_length') . ']');
        $this->form_validation->set_rules('password_hash', 'lang:err_reset_password', 'trim|required|min_length[5]|max_length[20]');
        $this->form_validation->set_rules('verify_password', 'lang:err_reset_vpassword', 'trim|required|matches[password_hash]');
        return $this->form_validation->run();
    }

    protected function forgot_password_token_email_process($token) {
        $this->BuildContentEnv(['email']); $email_data = [];
        $subject = email_subject_frmt([$this->lang->line('forgot_pwd_email_subject'), $this->lang->line('site_name')]);
        $email_data['email_header'] = $this->lang->line('forgot_pwd_email_msg_heading');
        $email_data['email_view_name'] = 'auth/forgotpassword';
        $email_data['token'] = $token;
        //$email_data['regards_link'] = 1;
        $this->SendEmail($this->input->post('email'), $subject, $email_data);
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';

        if ($this->doFormValidation_confirm()) :
            $error = $this->userauth->do_forgotpwd_auth($this->input->post('email'), $this->master_app, $this->hook_data->curr_domain->user_groups);
            if (is_numeric($error) && strlen($error) == $this->config->item('reset_pwd_token_length')) :
                $this->deleteCache(Modules::run($this->master_app . '/users/users/getVal', 'tbl') . '*');
                $this->session->set_flashdata('flashSuccess', $this->lang->line('forgot_pwd_set_token_successful'));
                $this->forgot_password_token_email_process($error);
                redirect($this->setAppURL($this->list_link . '/reset'));
            else : $data['error'] = $error;
            endif;
        endif;

        $this->BuildContentEnv();
        $this->minify->add_js($this->th_custom_js_path . 'auth/forgotpassword.js', 'page');
        $this->minify->add_css($this->config->item('th_media_pages') . 'css/login.min.css', 'page');
        $data['form_action'] = $this->setAppURL($this->list_link);
        $data['login_link'] = $this->setAppURL($this->login_link);
        $data['view_name'] = 'auth/forgotpassword';
        $data['view_class'] = 'login';
        $this->displayView($data);
    }

    public function reset() {
        $this->lang->load('auth/resetpassword');
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';

        if ($this->doFormValidation_reset()) :
            $error = $this->userauth->do_resetpwd_auth($this->input->post(), $this->master_app, $this->hook_data->curr_domain->user_groups);
            if (!$error) :
                $this->deleteCache(Modules::run($this->master_app . '/users/users/getVal', 'tbl') . '*');
                $this->session->set_flashdata('flashSuccess', $this->lang->line('reset_password_success'));
                redirect($this->setAppURL('auth/login'));
            else : $data['error'] = $error;
            endif;
        endif;

        $this->BuildContentEnv();
        $this->minify->add_js($this->th_custom_js_path . 'auth/resetpassword.js', 'page');
        $this->minify->add_css($this->config->item('th_media_pages') . 'css/login.min.css', 'page');
        $data['form_action'] = $this->setAppURL($this->list_link . '/reset');
        $data['login_link'] = $this->setAppURL($this->login_link);
        $data['view_name'] = 'auth/resetpassword';
        $data['view_class'] = 'login';
        $this->displayView($data);
    }

}
