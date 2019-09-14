<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acl_content extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'acl';
    protected $list_link = 'acl/acl_content';
    protected $tbl = 'acl_content';

    protected $usergroups_list = [];
    protected $acl_levels_list = [];
    protected $domains_list = [];
    protected $content_parent_list = [];

    protected $model_name = 'acl/Acl_content_model';
    protected $model = 'acl_content_model';
    protected $is_model = 0;

    protected $content_type_arr = [];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/acl_content');
        $this->_loadModelEnv();
        $this->content_type_arr = array_flip($this->config->item('acl_content_types'));
    }

    public function index()
    {
        $data['error'] = ($this->session->flashdata('flashError'))? $this->session->flashdata('flashError'):'';
        $data['success'] = ($this->session->flashdata('flashSuccess'))? $this->session->flashdata('flashSuccess'):'';
        $this->_initEnv();
        $this->load->helper('string');
        $data['items'] = $this->get_Items(['hooks'=>['treeOrder']]);
        if(count($data['items']) > 0) :
            //$data['usergroup_items'] = Modules::run($this->master_app.'/users/usergroups/utilityList', 1);
            $data['content_type_arr'] = $this->content_type_arr;
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/acl_content.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/acl_content';
        $data['view_class'] = 'acl_content';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['content_type_arr'] = $this->content_type_arr;
        $data['add_link'] = '';
        if($this->item_ID) :
            $data['form_action'] = $this->list_link.'/edit/'.$this->encodeData($this->item_ID);
            $lang = 'edit_acl_content';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link.'/add';
            $lang = 'add_acl_content';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/acl_content_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/acl_content_form';
        $data['view_class'] = 'acl_content_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        build_postvar(['user_groups'=>[]]);

        $this->form_validation->set_rules('content_title', 'lang:err_content_title', 'trim|required|min_length[2]|max_length[100]');

        $this->form_validation->set_rules('acl_level_id', 'lang:err_acl_level','trim|required|in_list['.implode(',',array_keys($this->acl_levels_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('domain_id', 'lang:err_domain','trim|required|in_list['.implode(',',array_keys($this->domains_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);

        $multi_unique_param = [
            'id'=>$this->item_ID,
            'fields'=> ['content_title', 'parent_id', 'domain_id'],
            'table'=>$this->tbl
        ];
        $this->form_validation->set_rules('content_title', 'lang:err_content_title', 'multi_is_unique['.json_encode($multi_unique_param).']');

        $this->form_validation->set_rules('user_groups[]', 'lang:err_usergroups','trim|in_list['.implode(',',array_keys($this->usergroups_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('description', 'lang:err_content_description', 'trim|required|min_length[2]|max_length[200]');
        $this->form_validation->set_rules('content_type', 'lang:err_parent_id','trim|required|in_list['.implode(',',array_keys($this->content_parent_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        if($this->input->post('content_type') == 2) : $this->form_validation->set_rules('parent_id', 'lang:err_parent_id','trim|required|in_list['.implode(',',array_keys($this->content_parent_list)).']', ['in_list'=>$this->lang->line('err_in_list')]); endif;
        $this->form_validation->set_rules('published', 'lang:err_published','trim|required|in_list['.implode(',',array_keys($this->boolean_arr)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->load->helper('string');
        $this->{$this->model}->setItem($this->item_ID);
        if($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;

        $this->usergroups_list = $data['usergroups_list'] = Modules::run($this->master_app.'/users/usergroups/utilityList');
        $this->acl_levels_list = $data['acl_levels_list'] = Modules::run($this->master_app.'/acl/access_levels/utilityList');
        $this->domains_list = $data['domains_list'] = Modules::run($this->master_app.'/settings/domains/utilityList');
        $this->content_parent_list = $data['content_parent_list'] = $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.content_title as title', 'a.parent_id'], 'db_Filters'=>['where'=>['a.published'=>1, 'a.content_type'=>1]]], 'hooks'=>['treeOrder', 'getParentsList(title, 1, '.$this->lang->line('drop_down_initial').')']]);
        //d($this->{$this->model}->data_item);
        $this->BuildContentEnv();
        if($this->doFormValidation()) :
            if($this->item_ID > 0 && ($this->item_ID == $this->input->post('parent_id'))) :
                $data['error'] = $this->lang->line('err_acl_content_self_parent');
            else :
                $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['content_title'=>'', 'acl_level_id'=>0, 'domain_id'=>0, 'description'=>'', 'user_groups'=>[], 'content_type'=>1, 'parent_id'=>0, 'published'=>1]));
                if($item_id) :
                    $data['success'] = $this->lang->line('success_acl_content_save');
                    $this->deleteCache($this->cache_list);
                    if(!$this->item_ID) :
                        $this->session->set_flashdata('flashSuccess', $data['success']);
                        redirect($this->setAppURL($this->list_link.'/edit/'.$this->encodeData($item_id)));
                    else :
                        $this->{$this->model}->setItem($this->item_ID);
                    endif;
                else : $data['error'] = $this->lang->line('err_acl_content_save'); endif;
            endif;
        endif;

        $data = $this->_initBasicFormEnv($data);
        $this->displayView($data);
    }

    public function add() {
        $data['error'] = ''; $data['success'] = '';
        $this->FormProcess($data);
    }

    public function edit($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $data['error'] = ''; $data['success'] = ($this->session->flashdata('flashSuccess'))? $this->session->flashdata('flashSuccess'):'';
        $this->FormProcess($data);
    }

    public function delete($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->_initEnv();
        $children = $this->get_Items(['filterArr'=>['db_Select'=>['a.id'], 'db_Filters'=>['where'=>['a.parent_id'=>$this->item_ID]]]]);
        if(count($children) > 0) :
            $this->session->set_flashdata('flashError', sprintf($this->lang->line('error_acl_content_delete'), count($children)));
        else :
            if(!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access(); endif;
            $this->session->set_flashdata('flashSuccess', $this->lang->line('success_acl_content_delete'));
            $this->deleteCache($this->cache_list);
        endif;
        redirect($this->setAppURL($this->list_link));
    }

}
