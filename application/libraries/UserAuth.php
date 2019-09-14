<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserAuth {

    private $CI;
    protected $master_app = '';
    protected $domain_usergroups = [];
    protected $user_info = [];
    
    public $parent_child_grp = [];

    public function __construct() {
        $this->CI = & get_instance();
    }

    private function setUserInfo($email) {
        $this->user_info = Modules::run($this->master_app . '/users/users/get_Items', ['filterArr' => ['db_Filters' => ['where' => ['a.email' => $email]]]]);
    }
    
    private function setUserModel() {
        $this->CI->load->model($this->master_app . '/users/user_model', 'user_model');
    }
    
    private function setBasicAuthInfo($app, $domain_usergroups) {
        $this->master_app = $app;
        $this->domain_usergroups = $domain_usergroups;
    }

    private function user_state_auth_error() {
        if (count(array_intersect($this->domain_usergroups, $this->user_info->user_groups)) < 1) :
            return $this->CI->lang->line('err_email_domain_mismatch');
        endif;
        if (!$this->user_info->active) :
            return $this->CI->lang->line('err_account_inactive');
        endif;
        if ($this->user_info->block) :
            return $this->CI->lang->line('err_account_block');
        endif;

        return false;
    }
    
    private function beginAuthProcess($email, $app, $domain_usergroups) {
        $this->setBasicAuthInfo($app, $domain_usergroups);
        $this->setUserInfo($email);
        if (count($this->user_info) < 1) : return $this->CI->lang->line('err_email_not_registered');
        endif;
        $this->user_info = current($this->user_info);
    return false;
    }
    
    public function doAuthentication($email, $password, $app, $domain_usergroups) {
        $error = $this->beginAuthProcess($email, $app, $domain_usergroups);
        if($error) : return $error; endif;
        
        if (!password_verify($password, $this->user_info->password_hash)) : return $this->CI->lang->line('err_password_mismatch');
        endif;
        $error = $this->user_state_auth_error();
        if (!$error) :
            $this->afterLoginUpdate();
            $this->CI->session->set_userdata(['logged_in' => $this->user_info]);
        endif;
        return $error;
    }

    public function do_forgotpwd_auth($email, $app, $domain_usergroups) {
        $error = $this->beginAuthProcess($email, $app, $domain_usergroups);
        if($error) : return $error; endif;
        $error = $this->user_state_auth_error();
        if($error) : return $error; endif;
        
        $this->setUserModel();
        $this->CI->load->helper('string');
        $token = random_string('numeric', $this->CI->config->item('reset_pwd_token_length'));
        $this->CI->user_model->updateResetToken($this->user_info->id, $token);
    return $token;        
    }
    
    public function do_resetpwd_auth($data, $app, $domain_usergroups) {
        $error = $this->beginAuthProcess($data['email'], $app, $domain_usergroups);
        if($error) : return $error; endif;
        $error = $this->user_state_auth_error();
        if($error) : return $error; endif;
        
        if($data['token_no'] != $this->user_info->reset_hash) : return $this->CI->lang->line('err_reset_token_mismatch'); endif;
        if((strtotime('now')-strtotime($this->user_info->modified_on))/ 60 > $this->CI->config->item('reset_pwd_token_expire')) : 
            return $this->CI->lang->line('err_reset_token_expired');
        endif;
        $this->setUserModel();
        $this->CI->user_model->updateResetPassword($this->user_info->id, $data['password_hash']);
    return false;
    }
    
    private function afterLoginUpdate() {
        $this->setUserModel();
        $this->CI->user_model->updateAfterLogin($this->user_info->id);
    }

    public function clearUserSession() {
        $this->CI->session->unset_userdata(['logged_in', 'package']);
        //$this->CI->session->sess_destroy();
        $this->CI->session->set_flashdata('flashSuccess', $this->CI->lang->line('logout_success_label'));
    }
    
    private function getChildrenFromParent($grp_id, $parent_child_grp, $children = []) {
        if (!isset($parent_child_grp[$grp_id])) : return $children;
        endif;
        foreach ($parent_child_grp[$grp_id] as $v) :
            array_push($children, $v);
            $children = $this->getChildrenFromParent($v, $parent_child_grp, $children);
        endforeach;
        return $children;
    }

    private function getUserChildgrps() {
        $logged_in_user_childgrp = [];
        $usergroups_list = Modules::run($this->master_app . '/users/usergroups/utilityList', 1);
        if (count($usergroups_list) > 0) :
            foreach ($usergroups_list as $k => $row) :
                $this->parent_child_grp[$row->parent_id][] = $row->id;
            endforeach;
        endif;

        foreach ($this->CI->session->userdata('logged_in')->user_groups as $val) :
            $logged_in_user_childgrp += $this->getChildrenFromParent($val, $this->parent_child_grp);
        endforeach;

        return array_unique($logged_in_user_childgrp);
    }

    public function setUserChildGroups($app, $domain_grps_check=0) {
        $this->master_app = $app;
        $this->parent_child_grp = $this->getUserChildgrps();
        if($domain_grps_check) : 
            $this->parent_child_grp = array_intersect($this->CI->hook_data->curr_domain->user_groups, $this->parent_child_grp);
        endif;
        $acl_pwrfl_grps = $this->CI->config->item('acl_powerful_grps');
        if (count($acl_pwrfl_grps) > 0) :
            foreach ($acl_pwrfl_grps as $v) :
                if (in_array($v, $this->parent_child_grp)) : continue; endif;
                if (in_array($v, $this->CI->session->userdata('logged_in')->user_groups)) : array_push($this->parent_child_grp, $v);
                endif;
            endforeach;
        endif;
    }
    
    public function userGroupsBasedOnParent($list) { 
        $new_list = [];
        if(count($this->parent_child_grp) > 0 && count($list) > 0) : 
            $new_list = array_intersect_key($list, array_flip($this->parent_child_grp));
        endif;
    return $new_list;
    }

}

?>