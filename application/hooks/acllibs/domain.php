<?php

namespace aclLibs;

defined('BASEPATH') OR exit('No direct script access allowed');

trait Domain {

    protected $current_app;
    protected $master_app;
    protected $curr_domain;

    protected function setDomainEnv() {
        $this->current_app = $this->CI->config->item('current_app');
        $this->master_app = $this->CI->config->item('master_app');

        $this->curr_domain = \Modules::run($this->master_app . '/settings/domains/get_Items', ['filterArr' => ['db_Filters' => ['where' => ['a.slug' => $this->current_app, 'a.published' => 1]]]]);
        if (count($this->curr_domain) < 1) :
            echo \Modules::run($this->current_app . '/errors/error405/index', $this->CI->lang->line('err_domain_inactive'));
            exit();
        endif;
        $this->curr_domain = current($this->curr_domain);
        $this->CI->hook_data->curr_domain = $this->curr_domain;
        if ($this->curr_domain->is_down) : 
            echo \Modules::run($this->current_app . '/errors/error405/index', $this->curr_domain->down_message);
            exit();
        endif;
        if ($this->userdata) :
            if (count(array_intersect($this->curr_domain->user_groups, $this->userdata->user_groups)) < 1) :
                echo \Modules::run($this->current_app . '/errors/error405/index');
                exit();
            endif;
        endif;
    }
    
    protected function redirection($url='') {
        redirect($this->current_app . '/'.$url);
    }

}

?>