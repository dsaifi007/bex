<?php

namespace aclLibs;

defined('BASEPATH') OR exit('No direct script access allowed');

trait Authentication {

    protected $no_auth = 1;

    protected function is_logged_in() {
        if (!$this->userdata) :
            $this->CI->session->set_flashdata('flashError', $this->CI->lang->line('err_no_login'));
            $this->redirection('auth/login');
        endif;
    }

    protected function processAuthentication() {
        if (!$this->no_auth) :
            if (count($this->content_acl_usergroups) > 0) :
                $error = (count(array_intersect($this->content_acl_usergroups, $this->userdata->user_groups)) > 0) ? 0 : 1;
            else :
                $error = (count(array_intersect($this->selected_acl_level_usergroups, $this->userdata->user_groups)) > 0) ? 0 : 1;
            endif;
            if ($error) : $this->redirection('errors/error401');
            endif;
        endif;
    }

}

?>