<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_levels extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'acl';
    protected $list_link = 'acl/access_levels';
    protected $tbl = 'acl_access_levels';
    protected $usergroups_list = [];

    protected $model_name = 'acl/Access_level_model';
    protected $model = 'acl_level_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.acl_level'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> ['FormattedResultList(acl_level)']
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/access_levels');
        $this->_loadModelEnv();
    }

    public function index()
    {
        $data['error'] = ($this->session->flashdata('flashError'))? $this->session->flashdata('flashError'):'';
        $data['success'] = ($this->session->flashdata('flashSuccess'))? $this->session->flashdata('flashSuccess'):'';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if(count($data['items']) > 0) :
            $data['usergroup_items'] = Modules::run($this->master_app.'/users/usergroups/utilityList', 1);
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/access_levels.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/access_levels';
        $data['view_class'] = 'access_levels';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if($this->item_ID) :
            $data['form_action'] = $this->list_link.'/edit/'.$this->encodeData($this->item_ID);
            $lang = 'edit_access_level';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link.'/add';
            $lang = 'add_access_level';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/access_levels_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/access_level_form';
        $data['view_class'] = 'acllevel_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        build_postvar(['user_groups'=>[]]);
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('acl_level')) == strtolower($this->{$this->model}->data_item->acl_level))? '':'|is_unique['.$this->tbl.'.acl_level]';
        $this->form_validation->set_rules('acl_level', 'lang:err_acl_level','trim|required|alpha|min_length[2]|max_length[50]'.$is_unique);
        $this->form_validation->set_rules('user_groups[]', 'lang:err_usergroups','trim|in_list['.implode(',',array_keys($this->usergroups_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
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

        $grparr = Modules::run($this->master_app.'/users/usergroups/utilityList');
        $this->usergroups_list = $data['usergroups_list'] = $grparr;
        $this->BuildContentEnv();
        if($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['acl_level'=>'', 'user_groups'=>[], 'published'=>'']));
            if($item_id) :
                $data['success'] = $this->lang->line('success_acl_level_save');
                $this->deleteCache($this->cache_list);
                if(!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link.'/edit/'.$this->encodeData($item_id)));
                else :
                    $this->{$this->model}->setItem($this->item_ID);
                endif;
            else : $data['error'] = $this->lang->line('err_acl_level_save'); endif;
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
        if(!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access(); endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_acl_level_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

}
