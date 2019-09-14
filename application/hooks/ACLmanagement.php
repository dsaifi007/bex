<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once('acllibs/allLibs.php');

class ACLmanagement {
    
    use aclLibs\AllLibs;
    
    protected $CI = '';
    protected $req_controller;
    protected $req_method;
    
    protected $userdata = '';
    protected $error = 0;
    protected $error_msg = '';

    private function setupBasicEnv() {
        $this->CI = & get_instance();
        if (!isset($this->CI->session)) : $this->CI->load->library('session');
        endif;
        if (!isset($this->CI->router)) : $this->CI->load->library('router');
        endif;
        $this->req_controller = strtolower($this->CI->router->fetch_class());
        $this->req_method = strtolower($this->CI->router->fetch_method());
        $this->userdata = $this->CI->session->userdata('logged_in');
        $this->CI->hook_data = new stdClass();
        
        $this->setDomainEnv();
        $this->setUpACL_PermissionTree();
    }

    public function content_management() {
        $this->setupBasicEnv();
        $this->ACLContentVerification();
        $this->processAuthentication();
    }

}

?>
