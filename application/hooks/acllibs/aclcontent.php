<?php

namespace aclLibs;

defined('BASEPATH') OR exit('No direct script access allowed');

trait Aclcontent {

    protected $accessLevelsArr;
    protected $contentArr;
    
    protected $content_acl_level_id = 0;
    protected $selected_acl_level_usergroups = [];
    protected $content_acl_usergroups = [];

    protected function setContentsArr() {
        $this->contentArr = \Modules::run($this->master_app . '/acl/acl_content/get_Items', ['filterArr' => ['db_Select' => ['a.id', 'lower(a.content_title) as content_title', 'a.content_type', 'a.parent_id', 'a.acl_level_id', 'a.user_groups', 'a.published'], 'db_Filters' => ['where' => ['a.domain_id' => $this->curr_domain->id]]], 'hooks' => ['acl_content_arr_wrap']]);
    }

    protected function setACL_AccessLevelsArr() {
        $this->accessLevelsArr = \Modules::run($this->master_app . '/acl/access_levels/get_Items', ['filterArr' => ['db_Select' => ['a.id', 'a.acl_level', 'a.user_groups'], 'db_Filters' => ['where' => ['a.published' => 1]]]]);
        $this->CI->hook_data->access_levels_arr = $this->accessLevelsArr;
    }
    
    protected function setACL_AccessFunctions() {
        $this->CI->hook_data->access_functions = \Modules::run($this->master_app . '/acl/access_actions_functions/get_Items', ['filterArr' => ['db_Select' => ['a.id', 'a.method', 'a.function_name', 'a.acl_level_id', 'a.user_groups', 'c.acl_action'], 'db_Filters' => ['where' => ['a.published' => 1, 'controller'=>$this->req_controller, 'domain_id'=>$this->curr_domain->id]]], 'hooks' => ['acl_access_functions_arr_wrap('.base64_encode(json_encode($this->accessLevelsArr)).')']]);      
    }
    
    protected function setUpACL_PermissionTree() {
        $this->setACL_AccessLevelsArr();
        $this->setContentsArr();
        $this->setACL_AccessFunctions();
    }
    
    protected function setSelectedContentData($cnt) {
        $this->content_acl_level_id = $cnt->acl_level_id;
	$this->selected_acl_level_usergroups = $this->accessLevelsArr[$cnt->acl_level_id]->user_groups;
	$this->content_acl_usergroups = $cnt->user_groups;
	$this->no_auth = 0;
    }
    
    protected function ACLContentVerification() {
        if (!array_key_exists($this->req_controller, $this->contentArr)) : $this->redirection('errors/error404');
        endif;
        if (!$this->contentArr[$this->req_controller]->published) : $this->redirection('errors/error403');
        endif;
        $this->req_method .= $this->contentArr[$this->req_controller]->id;
        if (array_key_exists($this->req_method, $this->contentArr)) :
            if (!($this->contentArr[$this->req_method]->published && ($this->contentArr[$this->req_controller]->id == $this->contentArr[$this->req_method]->parent_id))) : $this->redirection('errors/error403');
            endif;
            if(!array_key_exists($this->contentArr[$this->req_method]->acl_level_id, $this->accessLevelsArr)) : $this->redirection('errors/error406');
            endif;
            if(!in_array($this->contentArr[$this->req_method]->acl_level_id, $this->CI->config->item('acl_public_access_level_id'))) :
                $this->is_logged_in();
                $this->setSelectedContentData($this->contentArr[$this->req_method]);
            endif;
        endif;
        
        if($this->no_auth) : 
            if(!array_key_exists($this->contentArr[$this->req_controller]->acl_level_id, $this->accessLevelsArr)) : $this->redirection('errors/error406'); endif; 
            if(!in_array($this->contentArr[$this->req_controller]->acl_level_id, $this->CI->config->item('acl_public_access_level_id'))) : 
                $this->is_logged_in();
                $this->setSelectedContentData($this->contentArr[$this->req_controller]);
            endif;
        endif;
    }

}

?>